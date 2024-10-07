<?php

namespace App\Http\Controllers;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Preference\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class PaymentController extends Controller
{
    public function createPreference(Request $request, $order)
    {
        try {
            // Verifique se o pedido existe
            if (!$order || !$order->exists) {
                throw new \Exception('Pedido não encontrado ou não foi salvo.');
            }
    
            // Configuração dos dados da preferência
            $preferenceData = [
                "items" => [],
                "external_reference" => $order->id, // ID do pedido como referência externa
                "payer" => [
                    "email" => $request->user()->email, // Email do usuário autenticado
                ],
                "back_urls" => [
                    "success" => route('payment.success'),
                    "failure" => route('payment.failure'),
                    "pending" => route('payment.pending')
                ],
                "auto_return" => "approved", // Retorno automático se aprovado
                "currency_id" => "BRL", // Moeda brasileira
            ];
    
            // Adicionando produtos do pedido à preferência
            foreach ($order->products as $product) {
                $preferenceData['items'][] = [
                    "title" => $product->name,
                    "quantity" => $product->pivot->quantity,
                    "unit_price" => $product->pivot->unit_price, // Preço do produto
                ];
            }
    
            // Fazer a requisição HTTP diretamente à API do Mercado Pago
            $response = Http::withToken(env('MERCADOPAGO_ACCESS_TOKEN'))
                ->post('https://api.mercadopago.com/checkout/preferences', $preferenceData);
    
            // Verifica se a requisição foi bem-sucedida
            if ($response->successful()) {
                $preference = $response->json();
    
                return response()->json([
                    'message' => 'Preferencia criada com sucesso!',
                    'preference_id' => $preference['id'],
                    'init_point' => $preference['init_point'], // URL de checkout
                ]);
            } else {
                // Tratar erros da API do Mercado Pago
                throw new \Exception('Erro ao criar preferência: ' . $response->body());
            }
    
        } catch (\Exception $e) {
            // Lidar com erros e registrar logs para depuração
            Log::error('Erro ao criar preferência: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Erro ao criar preferência de pagamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
        public function success(Request $request)
        {
            // Lógica para lidar com o pagamento bem-sucedido
            return view('payment.success'); // Ou redirecione para uma página específica
        }
    
        public function failure(Request $request)
        {
            // Lógica para lidar com falhas no pagamento
            return view('payment.failure'); // Ou redirecione para uma página específica
        }
    
        public function pending(Request $request)
        {
            // Lógica para lidar com pagamentos pendentes
            return view('payment.pending'); // Ou redirecione para uma página específica
        }
    }

