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
    public function store(Request $request, int $cantina_id)
{
    try {
        // Validação dos dados de entrada
        $validatedData = $request->validate([
            'products' => 'required|array', // Array de produtos
            'products.*.id' => 'required|exists:products,id', // ID de cada produto
            'products.*.quantity' => 'required|integer|min:1', // Quantidade de cada produto
            'status' => 'required|in:open,closed', // Status do pedido (aberto ou fechado)
        ]);

        $cantina = Cantina::findOrFail($cantina_id); // Obtendo a cantina pelo ID na URL
        $user = auth()->user(); // Obtendo o usuário autenticado

        $totalPrice = 0;

        // Criando o pedido
        $order = Order::create([
            'user_id' => $user->id,
            'cantina_id' => $cantina->id,
            'status' => $validatedData['status'],
            'total_price' => 0, // Será atualizado após calcular o preço total
        ]);

        // Processando os produtos do pedido
        foreach ($validatedData['products'] as $productData) {
            $product = Product::findOrFail($productData['id']); // Obtendo o produto

            $totalPrice += $product->price * $productData['quantity']; // Calculando o preço total

            // Inserindo na tabela intermediária Order_Product
            Order_Product::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
            ]);
        }

        // Atualizando o preço total do pedido
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
