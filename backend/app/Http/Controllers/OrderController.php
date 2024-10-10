<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Cantina;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use MercadoPago\MercadoPago;
use MercadoPago\Preference;
use MercadoPago\Item;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

       
        
        // Verifica se a cantina existe
        $cantina = Cantina::findOrFail($cantina_id);
       
        // Pega o usuário autenticado
        $user = auth()->user();

        // Inicializa o preço total
        $totalPrice = 0;

        // Itera sobre os produtos validados para calcular o preço total e ajustar o estoque
        foreach ($validatedData['products'] as $productData) {
            $product = Product::findOrFail($productData['id']);  // Busca o produto no banco de dados

            // Verifica se a quantidade disponível no estoque é suficiente
            if ($product->quantity < $productData['quantity']) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Estoque insuficiente para o produto: ' . $product->name,
                ], 400);  // Retorna erro se o estoque for insuficiente
            }

            // Reduz a quantidade do produto no estoque
            $product->quantity -= $productData['quantity'];
            $product->save();  // Salva a nova quantidade no banco de dados

            // Calcula o preço total do pedido (quantidade * preço do produto)
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

        // Busca o pedido pelo código de retirada
        $order = Order::where('withdrawal_code', $validated['withdrawal_code'])
                    ->where('user_id', auth()->id())  // Verifica se o pedido pertence ao usuário autenticado
                    ->first();

        $orderGet = Order::find($order->id);
             

        // Verifica se o pedido foi encontrado
        if (!$order) {
            return response()->json([
                'message' => 'Codigo de retirada invalido ou nao encontrado.',
            ], 404);
        }

        // Verifica se o pedido está fechado
        if ($order->status == 0) {  // Supondo que 0 signifique "fechado"
            return response()->json([
                'message' => 'Este pedido ja foi fechado e nao pode mais ser retirado.',
            ], 400);
        }

        // Verifica se o código de retirada expirou
        if (now()->greaterThan($order->validity_code)) {
            return response()->json([
                'message' => 'Este codigo de retirada expirou.',
            ], 400);
        }

        // Se todas as verificações passarem, o pedido pode ser retirado
        // Agora podemos fechar o pedido
        $order->status = 0; 
        $order->withdrawal_at = now(); // Marca o pedido como "fechado"
        $order->save();

        return response()->json([
            'message' => 'Codigo de retirada valido. Voce pode retirar seu pedido.',
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
