<?php

namespace App\Http\Controllers;

use App\Models\AnnualManagement;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;


class AnnualManagementController extends Controller
{
    public function createOrUpdateAnnualManagement()
    {

        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
        $monthReference = $startDate->format('Y-m-01');

        // Verifica se já existe um registro para a cantina e o mês de referência
        $existingRecord = AnnualManagement::where('cantina_id', $cantinaId)
                                         ->where('month_reference', $monthReference)
                                         ->first();

        // Processa os dados
        $orders = Order::where('cantina_id', $cantinaId)
                       ->whereBetween('created_at', [$startDate, $endDate])
                       ->with('products')
                       ->get();
       
        $totalValue = 0;
        $totalCost = 0;
        $productCounts = [];

        foreach ($orders as $order) {
            if ($order->payment_status === 'paid') {
                foreach ($order->products as $product) {
                    $productId = $product->id ?? null;
                    $quantity = $product->pivot->quantity ?? 0;
                 
                    if ($productId && $quantity > 0) {
                        $productCounts[$productId] = ($productCounts[$productId] ?? 0) + $quantity;
                        $totalCost += $product->cost_price * $quantity;
                   
                    }
                }

                $totalValue += $order->total_price;
            }
        }

        $mostRequestedProductId = null;
        $maxQuantity = 0;

        foreach ($productCounts as $productId => $quantity) {
            if ($quantity > $maxQuantity) {
                $maxQuantity = $quantity;
                $mostRequestedProductId = $productId;
            }
        }
    
        $monthlyProfit = $totalValue - $totalCost;
        $salesMedia = count($orders) > 0 ? $totalValue / count($orders) : 0;

        $monthProduct = Product::where('cantina_id', $cantinaId)
        ->where('id',   $mostRequestedProductId)
        ->first();

        // Decide entre criar ou atualizar
        if ($existingRecord) {
            $existingRecord->update([
                'total_sales_for_the_year' => $totalValue,
                'annual_profit' => $monthlyProfit,
                'average_value_of_annual_sales' => $salesMedia,
                'annual_best_seling_product' => $monthProduct->name,
            ]);

            return response()->json([
                'message' => 'Registro atualizado com sucesso.',
                'data' => $existingRecord,
            ], 200);
        } else {
            $newRecord = AnnualManagement::create([
                'cantina_id' => $cantinaId,
                'total_sales_for_the_year' => $totalValue,
                'annual_profit' => $monthlyProfit,
                'average_value_of_annual_sales' => $salesMedia,
                'annual_best_seling_product' => $monthProduct->name,
                'month_reference' => $monthReference,
            ]);

            return response()->json([
                'message' => 'Novo registro criado com sucesso.',
                'data' => $newRecord,
            ], 201);
        }
    }

    public function indexAnnualManagement(){

        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
  
           // Processa os dados
        $management = AnnualManagement::where('cantina_id', $cantinaId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->first();

        
        return response()->json([
            '$management' => $management,
    ],200);

    }

    public function showAnnualManagement(string $managementId){

        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
  
           // Processa os dados
        $management = AnnualManagement::where('cantina_id', $cantinaId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where('id', $managementId)
        ->first();
       
        return response()->json([
            '$management' => $management,
    ],200);

    }

}
