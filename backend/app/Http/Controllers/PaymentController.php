<?php

namespace App\Http\Controllers;

use App\Models\User;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class PaymentController extends Controller
{
    protected function authenticate()
    {
        // Getting the access token from .env file (create your own function)
        $mpAccessToken = env('MERCADOPAGO_ACCESS_TOKEN');
        // Set the token the SDK's config
        if (!$mpAccessToken) {
            Log::error('MERCADOPAGO_ACCESS_TOKEN não está definido no .env.');
            throw new \Exception('MERCADOPAGO_ACCESS_TOKEN não está definido.');
        }
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        // (Optional) Set the runtime enviroment to LOCAL if you want to test on localhost
        // Default value is set to SERVER
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        
    }

    function createPreferenceRequest($items, $payer): array
    {
        
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 1,
            "default_installments" => 1
        ];
     
        $backUrls = array(
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failure')
        );
       
        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "Teste",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];
        
        return $request;
    }

    public function createPaymentPreference($order) 
{
        $this->authenticate();
        $products = $order->products;
        $user = User::find($order->user_id);
        $items = [];

        foreach($products as $product){
            $items[] = [
                "id" => $product->id,
                "title" => $product->name,
                "description" => $product->description,
                "currency_id" => "BRL",
                "quantity" => $product->pivot->quantity,
                "unit_price" => $product->pivot->unit_price
            ];
        }

        
        
        $payer = [
            "name" =>$user->name,
            "surname" => $user->surname,
            "email" => $user->email
        ];
        
        $request = $this->createPreferenceRequest($items, $payer);

        
        $request['back_urls'] = [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failure'),
            'pending' => route('mercadopago.pending'),
        ];
        
        $request['auto_return'] = 'approved';
       
        // Instantiate a new Preference Client
        $client = new PreferenceClient();
        
        try {
            // Send the request that will create the new preference for user's checkout flow
            $preference = $client->create($request);
            
            // Useful props you could use from this object is 'init_point' (URL to Checkout Pro) or the 'id'
            return $preference;
        } catch (MPApiException $error) {
            Log::error('Erro ao criar preferência no Mercado Pago: ' . $error->getMessage());
            return null;
        }
    }
    public function success(Request $request)
    {
        // Aqui você pode manipular o que fazer após o pagamento ser aprovado
        return view('mercadopago.success', ['payment_id' => $request->payment_id]);
    }

    public function failure(Request $request)
    {
        // Aqui você pode manipular o que fazer em caso de falha no pagamento
        return view('mercadopago.failure');
    }

    public function pending(Request $request)
    {
        // Aqui você pode manipular o que fazer quando o pagamento estiver pendente
        return view('mercadopago.pending');
    }

}