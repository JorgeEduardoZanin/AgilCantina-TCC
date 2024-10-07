<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Cantina;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MercadoPago\MercadoPago;
use MercadoPago\Preference;
use MercadoPago\Item;

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
            'total_price' => $totalPrice,  // Define o preço total do pedido
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
            'order' => $order->load('products'),  // Carrega os produtos associados ao pedido
        ], 201);

    } catch (ValidationException $e) {
        // Retorna erro de validação
        return response()->json([
            'message' => 'Erro de validação',
            'errors' => $e->errors(),
        ], 422);
    }  catch (\Exception $e) {
        // Retorna erro genérico em caso de falha no processo
        return response()->json([
            'message' => 'Erro ao criar pedido',
            'error' => $e->getMessage(),
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
