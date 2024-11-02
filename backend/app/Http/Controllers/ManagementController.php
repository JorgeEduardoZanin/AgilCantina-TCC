<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\Management;
use App\Models\Order;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function monthlyPregnancy(string $cantina_id)
    {
        // Define o intervalo de datas para o mês atual
        $startDate = \Carbon\Carbon::now()->startOfMonth();
        $endDate = \Carbon\Carbon::now()->endOfMonth();
    
        // Verifica se a cantina existe
        $cantina = Cantina::findOrFail($cantina_id);
    
        // Filtra os pedidos da cantina para o mês atual
        $orders = Order::where('cantina_id', $cantina_id)
                       ->whereBetween('created_at', [$startDate, $endDate])
                       ->get();
    
        // Calcula o valor total de vendas e o custo total dos produtos vendidos
        $totalValue = 0;
        $totalCost = 0;
        
        
        $productCounts = [];

        // Itera sobre cada pedido
        foreach ($orders as $order) {
            // Verifica se o pedido foi pago
            if ($order->payment_status === 'paid') {
                // Itera sobre os produtos em cada pedido
                foreach ($order->products as $product) {
                    $productId = $product->pivot->product_id;
                    $quantity = $product->pivot->quantity;
        
                    // Soma a quantidade para esse produto
                    if (isset($productCounts[$productId])) {
                        $productCounts[$productId] += $quantity;
                    } else {
                        $productCounts[$productId] = $quantity;
                    }
                }
            }
        }
        
        // Identifica o ID do produto mais requisitado e a quantidade de vezes que foi pedido
        $mostRequestedProductId = array_keys($productCounts, max($productCounts))[0];
        $mostRequestedProductCount = $productCounts[$mostRequestedProductId];

        foreach ($orders as $order) {
            if ($order->payment_status === 'paid') {
                // Adiciona o preço total do pedido ao valor total de vendas
                $totalValue += $order->total_price;
    
                // Calcula o custo dos produtos neste pedido
                foreach ($order->products as $product) {
                    $quantity = $product->pivot->quantity; // Quantidade do produto no pedido
                    $totalCost += $product->cost_price * $quantity;
                }
            }
        }
    
        $management = Management::create(

        );

        // Retorna o resultado
        return response()->json([
            'message' => 'Total calculado com sucesso para o mês atual!',
            'total_sales_value' => $totalValue,
            'total_cost_value' => $totalCost,
            'most_requested_product_id' => $mostRequestedProductId,
            'most_requested_product_count' => $mostRequestedProductCount,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
