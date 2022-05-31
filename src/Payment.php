<?php

namespace Adiechahk\PaymentBackend;

class Payment
{
    protected $stripe;

    public function __construct()
    {
      $stripe_key = config('payment.stripe.secret_key');
      $this->stripe = $stripe_key != null ? new \Stripe\StripeClient($stripe_key): null;
    }
  
    public function pay($method, $currency, $amount, $options = []) {
      if ($method == "cash") {
        return (object) [
          "id" => "cash",
          "client_secret" => "not_present",
          "status" => "pending"
        ];
      }
      if ($method == "multibanco") {
        return $this->stripe->sources->create([
          "type" => "multibanco",
          "currency" => $currency,
          "amount" => $amount,
          "owner" => [
            "email" => $options['email'],
            "name" => $options['name']
          ]
        ]);
      }
      return $this->stripe->paymentIntents->create([
        'amount' => $amount,
        'currency' => $currency,
        'payment_method_types' => [$method],
      ]);
    }
  }