<?php

namespace App\Http\Controllers;


use App\Models\Cantina;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }
    public function index(string $filter, string $orderId)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $cantina_id)
    {
    try {
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
           
        ], [
            'products.required' => 'É necessário incluir pelo menos um produto.',
            'products.*.id.exists' => 'O produto selecionado é inválido.',
            'products.*.quantity.min' => 'A quantidade mínima para um produto é 1.',
        
        ]);
        $code = $this->generateNumericCode(4);
        $validity = now()->endOfDay();

        $cantina = Cantina::findOrFail($cantina_id);
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }
        
        $totalPrice = 0;

      
        foreach ($validatedData['products'] as $productData) {
            $product = Product::findOrFail($productData['id']);  

           
            if ($product->quantity < $productData['quantity']) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Estoque insuficiente para o produto: ' . $product->name,
                ], 400);  
            }

          
            $product->quantity -= $productData['quantity'];
            $product->save();  

            
            $totalPrice += $product->price * $productData['quantity'];
            }

            // Cria o pedido com o preço total calculado
            $order = Order::create([
                'user_id' => $user->id,  // Associa o pedido ao usuário autenticado
                'cantina_id' => $cantina->id,  // Associa o pedido à cantina
                'status' => 1,  // Define o status do pedido (open ou closed)
                'total_price' => $totalPrice,
                'withdrawal_code'  => $code,
                'validity_code' => $validity,
                // Define o preço total do pedido
            ]);
         
            // Associa os produtos ao pedido na tabela intermediária (muitos-para-muitos)
            foreach ($validatedData['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);  // Busca o produto novamente
                $order->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],  // Quantidade comprada
                    'unit_price' => $product->price,  // Preço unitário do produto
                ]);
            }

            $paymentController = new PaymentController();
            $preference = $paymentController->createPaymentPreference($order);
           
            if (!$preference || !$preference->init_point) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao criar a preferencia de pagamento.',
                ], 500);
            }
    
            // Commit da transação
            DB::commit();

        

            // Retorna a resposta de sucesso com o pedido e os produtos associados
            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'order' => [
                'id' => $order->id,
                'products' => $order->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'unit_price' => $product->pivot->unit_price
                    ];
                }),
                'total_price' => $order->total_price,
                'withdrawal_code' => $order->withdrawal_code,
                'validity_code' => $order->validity_code,
                'payment_link' => $preference->init_point,
                ]
            ], 201);

        } catch (ValidationException $e) {
            // Retorna erro de validação
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        }  catch (\Exception $e) {
            DB::rollBack();
            // Retorna erro genérico em caso de falha no processo
            return response()->json([
                'message' => 'Erro ao criar pedido',
                'error' => $e->getMessage(),
            ], 500);
            }
        }

        private function generateNumericCode($length)
        {
        $min = pow(10, $length - 1);  // Valor mínimo (ex: 10000000 para 8 dígitos)
        $max = pow(10, $length) - 1;  // Valor máximo (ex: 99999999 para 8 dígitos)
        return random_int($min, $max);  // Retorna um número aleatório dentro do intervalo
        }
     
        public function checkWithdrawalCode(Request $request)
{
    // Validação do código de retirada
    $validated = $request->validate([
        'withdrawal_code' => 'required|numeric',
    ]);
     
   
   
    // Busca o pedido pelo código de retirada e outros critérios
    $order = Order::where('withdrawal_code', $validated['withdrawal_code'])
                  // Substitua por uma comparação válida, ex: ->where('cantina_id', $cantinaId)
                  ->first();

    

    // Verifica se o pedido foi encontrado
    if (!$order) {
        return response()->json([
            'message' => 'Codigo de retirada inválido ou nao encontrado.',
        ], 404);
    }

    // Verifica o status do pagamento
    if ($order->payment_status != 'paid') {
        return response()->json([
            'message' => 'O pagamento do pedido ainda nao foi efetuado.',
        ], 404);
    }

    // Verifica se o pedido está fechado
    if ($order->status == 0) {
        return response()->json([
            'message' => 'Este pedido já foi fechado e nao pode mais ser retirado.',
        ], 400);
    }

    // Verifica se o código de retirada expirou
    if (now()->greaterThan($order->validity_code)) {
        return response()->json([
            'message' => 'Este codigo de retirada expirou.',
        ], 400);
    }

    // Marca o pedido como fechado e registra a data de retirada
    $order->status = 0;
    $order->withdrawal_at = now();
    $order->save();

    return response()->json([
        'message' => 'Codigo de retirada valido. Voce pode retirar seu pedido.',
        'order' => [
            'id' => $order->id,
            'products' => $order->products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->pivot->unit_price,
                ];
            }),
            'total_price' => $order->total_price,
        ]
    ], 200);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
