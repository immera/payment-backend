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
  
    public function pay($method, $currency, $amount) {
      return $this->stripe->paymentIntents->create([
        'amount' => $amount,
        'currency' => $currency,
        'payment_method_types' => [$method],
      ]);
    }
  }