<?php

namespace Immera\Payment;

use Stripe\StripeClient;
use App\Models\Customer; #make sure we remove this one afterwards. / move to package
use Illuminate\Support\Facades\Auth;
use Immera\Payment\StripeCustomer;

class Payment
{
    protected $stripe;
    protected StripeCustomer $customer;

    public function __construct()
    {
        $stripe_key = config('payment.stripe.secret_key');
        $this->stripe = $stripe_key !== null ? new StripeClient($stripe_key) : null;
        $this->setCustomer();
    }

    public function setCustomer(string $email = "")
    {
        $user = Auth::user();
        $cust = Customer::getCustomer($user->id);
        if ($cust != null && $cust->stripe_customer_id != null) {
            $this->customer = new StripeCustomer($cust->stripe_customer_id, $user->full_name, $user->email);
        } else {
            $stripeCust = $this->stripe->customers->create([
                'name' => $user->full_name,
                'email' => $user->email,
            ]);
            Customer::create([
                'user_id' => $user->id,
                'stripe_customer_id' => $stripeCust->id,
                'invoice_prefix' => $stripeCust->invoice_prefix,
            ]);
            $this->customer = new StripeCustomer($stripeCust->id, $user->full_name, $user->email);
        }
    }

    public function createCard(array $card)
    {
        $token = $this->stripe->tokens->create(['card' => $card]);
        return $this->stripe->customers->createSource(
            $this->customer->getId(),
            ['source' => $token->id]
        );
    }

    public function getCards()
    {
        // stripe call with customer id to get cards and return them.
        return $this->stripe->customers->allSources(
            $this->customer->getId(),
            [
                'object' => 'card',
                'limit' => 10
            ]
        );
    }

    public function pay($method, $currency, $amount, $options = [])
    {
        switch ($method) {
            case 'paypal':
                return "whatever";
                break;
            case 'cash':
                return (object) [
                    'id' => 'cash',
                    'client_secret' => 'not_present',
                    'status' => 'pending',
                ];
                break;
            case 'card':
                return $this->stripe->charges->create([
                    'amount' => $amount,
                    'currency' => $currency,
                    'source' => $options['source'],
                    'customer' => $this->customer->getId(),
                ]);
            case 'multibanco':
                return $this->stripe->sources->create([
                    'type' => 'multibanco',
                    'currency' => $currency,
                    'amount' => $amount,
                    'owner' => [
                        'email' => $this->customer->getEmail(),
                        'name' => $this->customer->getName(),
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
