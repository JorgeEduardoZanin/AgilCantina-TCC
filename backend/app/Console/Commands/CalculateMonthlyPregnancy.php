<?php

namespace App\Console\Commands;

use App\Models\MonthManagement;
use Illuminate\Console\Command;
use App\Models\Cantina;
use App\Models\Order;
use Carbon\Carbon;
Use PDF;

class CalculateMonthlyPregnancy extends Command
{
    protected $signature = 'calculate:monthly-pregnancy';
    protected $description = 'Calcula o total de vendas e custo mensal para cada cantina';

    public function handle()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $monthReference = $startDate->format('Y-m-01');

        $cantinas = Cantina::all();

        foreach ($cantinas as $cantina) {
            $orders = Order::where('cantina_id', $cantina->id)
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

            // Verifica se já existe um registro para a cantina e o mês de referência
            $existingRecord = MonthManagement::where('cantina_id', $cantina->id)
                                             ->where('month_reference', $monthReference)
                                             ->first();

            if ($existingRecord) {
                // Atualiza o registro existente
                $existingRecord->update([
                    'total_monthly_sales' => $totalValue,
                    'monthly_profit' => $monthlyProfit,
                    'average_value_of_monthly_sales' => $salesMedia,
                    'best_seling_product' => $mostRequestedProductId,
                ]);

                $this->info("Registro atualizado para a cantina ID: {$cantina->id}.");
            } else {
                // Cria um novo registro
                MonthManagement::create([
                    'cantina_id' => $cantina->id,
                    'total_monthly_sales' => $totalValue,
                    'monthly_profit' => $monthlyProfit,
                    'average_value_of_monthly_sales' => $salesMedia,
                    'best_seling_product' => $mostRequestedProductId,
                    'month_reference' => $monthReference,
                ]);

                $this->info("Novo registro criado para a cantina ID: {$cantina->id}.");
            }
        }

        $this->info('Resumo mensal processado para todas as cantinas.');
    }
}
