<?php

namespace Immera\Payment;

use Illuminate\Support\Facades\Http;

class Paypal
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUrl = config('payment.paypal.url');
        $this->clientId = config('payment.paypal.client_id');
        $this->clientSecret = config('payment.paypal.secret_key');
    }

    private function route($url)
    {
        return $this->baseUrl . $url;
    }

    private function getToken()
    {
        return Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->withHeaders([
                "Content-Type" => "application/x-www-form-urlencoded"
            ])
            ->post(
                $this->route('v1/oauth2/token?grant_type=client_credentials')
            );
    }

    private function v2Headers()
    {
        $token = $this->getToken()->json('access_token');
        return [
            'Accept' => 'application/json',
            'Accept-Language' => 'en_US',
            'Content-Type' => 'application/json',
            "Authorization" => "Bearer " . $token,
        ];
    }

    public function createOrder($currency, $amount)
    {
        return Http::withHeaders($this->v2Headers())
            ->post($this->route('v2/checkout/orders'), [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                  "currency_code" => $currency,
                  "value" => $amount
                ]
            ]]
        ]);
    
    }

    public function captureOrder($orderId)
    {
        return Http::withHeaders($this->v2Headers())
            ->post($this->route('/v2/checkout/orders/'.$orderId.'/capture'));        
    }
}
