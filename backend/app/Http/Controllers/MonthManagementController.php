<?php

namespace App\Http\Controllers;

use App\Models\Cantina;
use App\Models\MonthManagement;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonthManagementController extends Controller
{
    public function createOrUpdateMonthManagement()
    {

        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $monthReference = $startDate->format('Y-m-01');

        // Verifica se já existe um registro para a cantina e o mês de referência
        $existingRecord = MonthManagement::where('cantina_id', $cantinaId)
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

        // Decide entre criar ou atualizar
        if ($existingRecord) {
            $existingRecord->update([
                'total_monthly_sales' => $totalValue,
                'monthly_profit' => $monthlyProfit,
                'average_value_of_monthly_sales' => $salesMedia,
                'monthly_best_seling_product' => $mostRequestedProductId,
            ]);

            return response()->json([
                'message' => 'Registro atualizado com sucesso.',
                'data' => $existingRecord,
            ], 200);
        } else {
            $newRecord = MonthManagement::create([
                'cantina_id' => $cantinaId,
                'total_monthly_sales' => $totalValue,
                'monthly_profit' => $monthlyProfit,
                'average_value_of_monthly_sales' => $salesMedia,
                'monthly_best_seling_product' => $mostRequestedProductId,
                'month_reference' => $monthReference,
            ]);

            return response()->json([
                'message' => 'Novo registro criado com sucesso.',
                'data' => $newRecord,
            ], 201);
        }
    }

    public function indexMonthManagement(){

        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
  
           // Processa os dados
        $management = MonthManagement::where('cantina_id', $cantinaId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->first();

        $bestSellingProduct = $management->monthly_best_seling_product;

        $product = Product::where('cantina_id', $cantinaId)
        ->where('id',   $bestSellingProduct)
        ->first();
       

        return response()->json([
            '$management' => $management,
            'monthly_best_seling_product' => $product->name
    ],200);

    }

    public function showMonthManagement(string $managementId){

        $cantina = auth()->user();
        $cantinaId = $cantina->cantina->id;
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
  
           // Processa os dados
        $management = MonthManagement::where('cantina_id', $cantinaId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where('id', $managementId)
        ->first();

        $bestSellingProduct = $management->monthly_best_seling_product;

        $product = Product::where('cantina_id', $cantinaId)
        ->where('id',   $bestSellingProduct)
        ->first();
       

        return response()->json([
            '$management' => $management,
            'monthly_best_seling_product' => $product->name
    ],200);

    }

}
