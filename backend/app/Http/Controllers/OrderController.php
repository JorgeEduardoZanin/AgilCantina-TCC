<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    public function store(Request $request)
    {
        try {
            // Validação dos dados de entrada
            $validatedData = $request->validate([
                'cantina_id' => 'required|exists:cantinas,id', // A cantina do pedido
                'products' => 'required|array', // Array de produtos
                'products.*.id' => 'required|exists:products,id', // ID de cada produto
                'products.*.quantity' => 'required|integer|min:1', // Quantidade de cada produto
                'status' => 'required|in:open,closed', // Status do pedido (aberto ou fechado)
            ]);

            $cantina = Cantina::findOrFail($validatedData['cantina_id']);
            $user = auth()->user();

            $totalPrice = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'cantina_id' => $cantina->id,
                'status' => $validatedData['status'],
                'total_price' =>0,

            ]);
            foreach ($validatedData['products'] as $productData){
                $product = Product::findOrFail($validatedData['id']);

                $totalPrice += $product->price * $productData['quantity'];

               Order_Product::create([

                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],

                ]);
            }
           
        
             // Atualizar o preço total do pedido
             $order->update(['total_price' => $totalPrice]);

             return response()->json([
                 'message' => 'Pedido criado com sucesso!',
                 'order' => $order->load('products') // Retorna o pedido com os produtos associados
             ], 201);
 
         } catch (ValidationException $e) {
             return response()->json([
                 'message' => 'Erro de validação',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             return response()->json([
                 'message' => 'Erro ao criar pedido',
                 'error' => $e->getMessage()
             ], 500);
         }
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
