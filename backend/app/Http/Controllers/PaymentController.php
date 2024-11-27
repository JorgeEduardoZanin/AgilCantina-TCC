<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class PaymentController extends Controller
{
    protected function authenticate()
    {
        $mpAccessToken = env('MERCADOPAGO_ACCESS_TOKEN');
        if (!$mpAccessToken) {
            throw new \Exception('MERCADOPAGO_ACCESS_TOKEN não está definido.');
        }
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    function createPreferenceRequest($items, $payer): array
    {
        return [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => [
                "excluded_payment_types" => [["id" => "ticket"]],
                "installments" => 1,
                "default_installments" => 1,
            ],
            "statement_descriptor" => "Teste",
            "expires" => false,
            "auto_return" => 'approved',
        ];
    }

    public function createPaymentPreference(Order $order)
    {
        $this->authenticate();
        $products = $order->products;
        $user = User::find($order->user_id);
        $items = [];

        foreach ($products as $product) {
            $items[] = [
                "id" => $product->id,
                "title" => $product->name,
                "description" => $product->description,
                "currency_id" => "BRL",
                "quantity" => $product->pivot->quantity,
                "unit_price" => $product->pivot->unit_price,
            ];
        }

        $payer = [
            "name" => $user->name,
            "surname" => $user->surname,
            "email" => $user->email,
        ];

        $request = $this->createPreferenceRequest($items, $payer);
        $request['external_reference'] = $order->id;

        $request['back_urls'] = [
            'success' => url('http://3.15.225.151:8085/status'),
            'failure' => url('http://3.15.225.151:8085/status'),
            'pending' => url('hhttp://3.15.225.151:8085/status'),
        ];

        $request['notification_url'] = 'http://3.15.225.151:8085/api/notifications';

        $client = new PreferenceClient();

        try {
            $preference = $client->create($request);
            return $preference;
        } catch (MPApiException $error) {
            return null;
        }
    }

    public function handle(Request $request)
    {
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');

        if (!$xSignature || !$xRequestId) {
            return response()->json(['error' => 'Missing headers'], 400);
        }

        $dataID = $request->get('data')['id'] ?? null;

        if (!$dataID) {
            return response()->json(['error' => 'Missing data.id'], 400);
        }

        $parts = explode(',', $xSignature);
        $ts = null;
        $hash = null;

        foreach ($parts as $part) {
            $keyValue = explode('=', $part, 2);
            if (count($keyValue) == 2) {
                $key = trim($keyValue[0]);
                $value = trim($keyValue[1]);

                if ($key === "ts") {
                    $ts = $value;
                } elseif ($key === "v1") {
                    $hash = $value;
                }
            }
        }

        if (!$ts || !$hash) {
            return response()->json(['error' => 'Invalid signature format'], 400);
        }

        $secret = env('MERCADO_PAGO_SECRET_KEY');
        $manifest = "id:$dataID;request-id:$xRequestId;ts:$ts;";
        $sha = hash_hmac('sha256', $manifest, $secret);

        if ($sha === $hash) {
            $this->fetchPaymentDetails($dataID);
            return response()->json(['message' => 'HMAC verification passed'], 200);
        } else {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }

    protected function fetchPaymentDetails($paymentId)
{
    Log::info('Webhook triggered with payment ID: ' . $paymentId);

    $this->authenticate();
    $url = "https://api.mercadopago.com/v1/payments/$paymentId";
    $token = env('MERCADOPAGO_ACCESS_TOKEN');

    Log::info('Fetching payment details from URL: ' . $url);

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get($url);

    if ($response->successful()) {
        Log::info('Payment details fetched successfully: ', $response->json());

        $paymentDetails = $response->json();
        $externalReference = $paymentDetails['external_reference'] ?? null;
        Log::info('External reference received: ' . $externalReference);

        $order = Order::find($externalReference);

        if ($order) {

            $order->payment_status = $this->mapPaymentStatus($paymentDetails['status'])->value;

            $order->save();

            if($order->payment_status !=="paid"){
                return response()->json(['error' => 'Erro no pagamento'], 500);
            }

                $pivotRecords = DB::table('order_products')
                ->where('order_id', $order->id)
                ->get();

                foreach ($pivotRecords as $productData) {
                    $product = Product::findOrFail($productData->product_id);

                // Atualiza a quantidade do produto
                    $product->quantity -= $productData->quantity;
                    $product->save();
                
                }
        }
        return response()->json(['message' => 'Order status updated successfully'], 200);
             } else {
        return response()->json(['error' => 'Unable to fetch payment details'], 500);
    }
        
    }
    



    protected function mapPaymentStatus($paymentStatus)
    {
        return match ($paymentStatus) {
            'approved' => PaymentStatusEnum::PAID,
            'pending' => PaymentStatusEnum::PENDING,
            'rejected' => PaymentStatusEnum::NOT_PAID,
            default => PaymentStatusEnum::NOT_PAID,
        };
    }
}
