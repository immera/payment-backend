<?php

namespace Immera\Payment;

use Stripe\StripeClient;
use App\Models\Customer; #make sure we remove this one afterwards. / move to package
use Illuminate\Support\Facades\Auth;
use Immera\Payment\StripeCustomer;
use Immera\Payment\Models\PaymentInstance;
use Immera\Payment\Events\PaymentInstanceUpdated;
use Illuminate\Support\Facades\Http;
use Immera\Payment\Contracts\PaymentUserContract;

class Payment
{
    protected $stripe;
    protected StripeCustomer $customer;

    public function __construct(PaymentUserContract $user)
    {
        $stripe_key = config('payment.stripe.secret_key');
        $this->stripe = $stripe_key !== null ? new StripeClient($stripe_key) : null;
        $this->setCustomer($user);
    }

    public function setCustomer(PaymentUserContract $user)
    {
        $cust = Customer::getCustomer($user->getId());
        if ($cust != null && $cust->stripe_customer_id != null) {
            $this->customer = new StripeCustomer($cust->stripe_customer_id, $user->getName(), $user->getEmail());
        } else {
            $stripeCust = $this->stripe->customers->create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]);
            Customer::create([
                'user_id' => $user->getId(),
                'stripe_customer_id' => $stripeCust->id,
                'invoice_prefix' => $stripeCust->invoice_prefix,
            ]);
            $this->customer = new StripeCustomer($stripeCust->id, $user->getName(), $user->getEmail());
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

    public function deleteCard($card)
    {
        return $this->stripe->customers->deleteSource(
            $this->customer->getId(),
            $card,
            []
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

    public function paypal()
    {
        return new Paypal;
    }

    public function pay($method, $currency, $amount, $options = [])
    {
        $intent_object = [
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => [$method],
        ];

        switch ($method) {
            case 'paypal':
                return (new Paypal)->createOrder($currency, $amount)->json();
                break;
            case 'bank_transfer':
                $intent_object['customer'] = $this->customer->getId();
                $intent_object['payment_method_types'] = ['customer_balance'];
                $intent_object['payment_method_data'] = ['type' => 'customer_balance'];
                $intent_object['payment_method_options'] = [
                  'customer_balance' => [
                    'funding_type' => 'bank_transfer',
                    'bank_transfer' => [
                      'type' => 'eu_bank_transfer',
                      'eu_bank_transfer' => [
                        'country' => 'FR'
                      ]
                    ],
                  ],
                ];
                break;
        
            case 'cash':
                return [
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
                break;
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
        return $this->stripe->paymentIntents->create($intent_object);
    }

    public static function updateStatus(PaymentInstance $pi, $status)
    {
        if ($pi)
        {
            $pi->status = $status;
            $pi->save();
            event(new PaymentInstanceUpdated($pi));
        }
        return $pi;
    }

    public function fundingInstructions()
    {
        return $this->stripe->customers->createFundingInstructions(
            $this->customer->getId(),
            [
                'bank_transfer' => [
                    'eu_bank_transfer' => ['country' => config('payment.stripe.country')],
                    'type' => 'eu_bank_transfer',
                ],
                'currency' => 'eur',
                'funding_type' => 'bank_transfer',
            ]
        );
    }

    public static function handleWebhook($payload)
    {
        // try {
        //     $event = \Stripe\Event::constructFrom($payload);
        // } catch (\UnexpectedValueException $e) {
        //     // Invalid payload
        //     Log::info('UnexpectedValueException' . $e->getMessage());
        //     return 400;
        // }
    }
}
