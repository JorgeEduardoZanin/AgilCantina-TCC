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
    public function indexNotCompleteUser(Request $request)
    {
    
        $validatedData = $request->validate([
            'filter' => 'nullable|string|max:255',
        ]);
        
        $user = auth()->user();
        
    
        $query = Order::where('user_id', $user->id)
                      ->where('status', 1); 
        
        if (!empty($validatedData['filter'])) {
            $query->where('name', 'like', '%' . $validatedData['filter'] . '%');
        }
        
        $orders = $query->with('products')->get();
        
        return response()->json($orders, 201);
    }
    
    public function indexCompleteUser(Request $request)
    {
        $validatedData = $request->validate([
            'filter' => 'nullable|string|max:255',
        ]);
        
        $user = auth()->user();
        
        $query = Order::where('user_id', $user->id)
                      ->where('status', 0)
                      ->where('payment_status', 'paid');
        
        if (!empty($validatedData['filter'])) {
            $query->where('name', 'like', '%' . $validatedData['filter'] . '%');
        }
        
        $orders = $query->with('products')->get();
        
        return response()->json($orders, 201);
    }
    
    public function indexNotCompleteCanteen(Request $request)
    {
       
        $validatedData = $request->validate([
            'filter' => 'nullable|string|max:255',
        ]);
        
        $user = auth()->user();
        
        $query = Order::where('cantina_id', $user->cantina->id)
                      ->where('status', 1); 
        
        if (!empty($validatedData['filter'])) {
            $query->where('name', 'like', '%' . $validatedData['filter'] . '%');
        }
        
        $orders = $query->with('products')->get();
        
        return response()->json($orders, 201);
    }
    
    public function indexCompleteCanteen(Request $request)
    {
        $validatedData = $request->validate([
            'filter' => 'nullable|string|max:255',
        ]);
        
        $user = auth()->user();
        
        $query = Order::where('cantina_id', $user->cantina->id)
                      ->where('status', 0)
                      ->where('payment_status', 'paid');
        
        if (!empty($validatedData['filter'])) {
            $query->where('name', 'like', '%' . $validatedData['filter'] . '%');
        }
        
        $orders = $query->with('products')->get();
        
        return response()->json($orders, 201);
    }

    public function store(Request $request, int $cantina_id)
    {
    try {
        
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

          
            $order = Order::create([
                'user_id' => $user->id, 
                'cantina_id' => $cantina->id,  
                'status' => 1,  
                'total_price' => $totalPrice,
                'withdrawal_code'  => $code,
                'validity_code' => $validity,
            ]);
         
         
            foreach ($validatedData['products'] as $productData) {
                $product = Product::findOrFail($productData['id']); 
                $order->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'unit_price' => $product->price,  
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
            
           
            DB::commit();

           
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
          
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        }  catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Erro ao criar pedido',
                'error' => $e->getMessage(),
            ], 500);
            }
        }

        private function generateNumericCode($length)
        {
            // Gera um código aleatório de acordo com o comprimento especificado
            $min = pow(10, $length - 1);  // Valor mínimo (ex: 10000000 para 8 dígitos)
            $max = pow(10, $length) - 1;  // Valor máximo (ex: 99999999 para 8 dígitos)
            $newCode = random_int($min, $max); // Gera o código numérico aleatório
        
            $existingOrder = Order::where('withdrawal_code', $newCode)->first();
        
            if ($existingOrder) {
            
                if (now()->greaterThan($existingOrder->validity_code)) {
              
                    return $newCode;
                }
        
            
                return $this->generateNumericCode($length);
            }
        
       
            return $newCode;
        }
     
    public function checkWithdrawalCode(Request $request)
    {
   
    $validated = $request->validate([
        'withdrawal_code' => 'required|numeric',
    ]);

    $order = Order::where('withdrawal_code', $validated['withdrawal_code'])
            ->orderBy('created_at', 'desc') // Ordena pela data de criação, do mais recente para o mais antigo
            ->first();
   
    if (!$order) {
        return response()->json([
            'message' => 'Codigo de retirada invalido ou nao encontrado.',
        ], 404);
    }

    $cantinaIdDoPedido = $order->cantina_id;
    $cantina = auth()->user()->cantina;
    $cantinaIdAutenticada = $cantina->first()->id;
     
    if ($cantinaIdAutenticada !== $cantinaIdDoPedido) {
        return response()->json([
            'message' => 'Você não tem permissão para retirar este pedido.',
        ], 403);
    }

  
    if ($order->payment_status != 'paid') {
        return response()->json([
            'message' => 'O pagamento do pedido ainda nao foi efetuado.',
        ], 404);
    }

    
    if ($order->status == 0) {
        return response()->json([
            'message' => 'Este pedido já foi fechado e nao pode mais ser retirado.',
        ], 400);
    }

   
    if (now()->greaterThan($order->validity_code)) {
        return response()->json([
            'message' => 'Este codigo de retirada expirou.',
        ], 400);
    }

   
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

   
    public function show(string $cantina_id, string $order_id)
    {
        $order = Order::findOrFail($order_id);
        return response()->json([
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
        ], 200);
    }
}
