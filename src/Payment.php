<?php

namespace Immera\Payment;

use Stripe\StripeClient;

class Payment
{
    protected $stripe;

    public function __construct()
    {
        $stripe_key = config('payment.stripe.secret_key');
        $this->stripe = $stripe_key !== null ? new StripeClient($stripe_key) : null;
    }

    public function pay($method, $currency, $amount, $options = [])
    {
        switch ($method) {
            case 'cash':
                return (object) [
                    'id' => 'cash',
                    'client_secret' => 'not_present',
                    'status' => 'pending',
                ];
                break;

            case 'multibanco':
                return $this->stripe->sources->create([
                    'type' => 'multibanco',
                    'currency' => $currency,
                    'amount' => $amount,
                    'owner' => [
                        'email' => $options['email'],
                        'name' => $options['name'],
                    ],
                ]);
                break;
        }

        return $this->stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => [$method],
        ]);
    }
}
