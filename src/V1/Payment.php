<?php

namespace Immera\Payment\V1;
use Immera\Payment\Payment as PaymentDefault;

class Payment extends PaymentDefault
{
    public function pay($method, $currency, $amount, $options = [])
    {
        $ephemeralKey = $this->stripe->ephemeralKeys->create(
            ['customer' => $this->customer->getId()],
            ['stripe_version' => config('payment.stripe_version_date')]
        );

        $intent_object = [
            'amount' => $amount,
            'currency' => $currency,
            'automatic_payment_methods' => ['enabled' => true],
            'customer' => $this->customer->getId(),
        ];  

        $intents =  $this->stripe->paymentIntents->create($intent_object);

        $output = [
            'payment-intents' => $intents,
            'customer-options' => [
              'customer' => $this->customer->getId(),
              'ephemeralKey' => $ephemeralKey->secret,
            ],
        ];

        return $output;
    }

    public function createCardByToken(array $card)
    {
        return $this->stripe->customers->createSource(
            $this->customer->getId(),
            ['source' => $card['token']]
        );
    }
}