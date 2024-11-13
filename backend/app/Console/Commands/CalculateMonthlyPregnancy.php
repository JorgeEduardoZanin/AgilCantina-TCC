<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cantina;
use App\Models\Management;
use App\Models\Order;
use Carbon\Carbon;

class CalculateMonthlyPregnancy extends Command
{
    protected $signature = 'calculate:monthly-pregnancy';
    protected $description = 'Calcula o total de vendas e custo mensal para cada cantina';

    public function handle()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $cantinas = Cantina::all();

        foreach ($cantinas as $cantina) {
            $orders = Order::where('cantina_id', $cantina->id)
                           ->whereBetween('created_at', [$startDate, $endDate])
                           ->with('products') // Garante que os produtos sejam carregados
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
                            // Acumula a quantidade de cada produto
                            $productCounts[$productId] = ($productCounts[$productId] ?? 0) + $quantity;

                            // Calcula o custo total
                            $totalCost += $product->cost_price * $quantity;
                        }
                    }

                    // Adiciona o valor total do pedido às vendas mensais
                    $totalValue += $order->total_price;
                }
            }

            // Encontra o ID do produto mais vendido
            $mostRequestedProductId = null;
            $maxQuantity = 0;

            foreach ($productCounts as $productId => $quantity) {
                if ($quantity > $maxQuantity) {
                    $maxQuantity = $quantity;
                    $mostRequestedProductId = $productId;
                }
            }

            // Log para verificar o produto mais vendido
            \Log::info("Cantina {$cantina->id}: Produto mais vendido: {$mostRequestedProductId}, Quantidade: {$maxQuantity}");

            // Calcula o lucro mensal
            $monthlyProfit = $totalValue - $totalCost;

            // Evita divisão por zero ao calcular a média de vendas
            $salesMedia = count($orders) > 0 ? $totalValue / count($orders) : 0;

            // Cria o registro em Management com os dados calculados
            Management::create([
                'cantina_id' => $cantina->id,
                'total_monthly_sales' => $totalValue,
                'monthly_profit' => $monthlyProfit,
                'average_value_of_monthly_sales' => $salesMedia,
                'best_seling_product' => $mostRequestedProductId,
                'month_reference' => $startDate->format('Y-m-01'),
            ]);
        }

        $this->info('Resumo mensal calculado e salvo com sucesso para todas as cantinas.');
    }
}
