<?php

namespace Immera\Payment\V1;

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

    public function __construct(PaymentUserContract $user = NULL)
    {
        $stripe_key = config('payment.stripe.secret_key');
        $this->stripe = $stripe_key !== null ? new StripeClient($stripe_key) : null;
        $this->setCustomer($user);
    }

    public function setCustomer(PaymentUserContract $user = NULL)
    {
        if($user === NULL) $user = Auth::user();
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
    

    public function pay($method, $currency, $amount, $options = [])
    {
        $intent_object = [
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => [$method],
        ];        
        return $this->stripe->paymentIntents->create($intent_object);
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
