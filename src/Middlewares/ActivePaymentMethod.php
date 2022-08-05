<?php

namespace Immera\Payment\Middlewares;

use Closure;
use Illuminate\Support\Arr;

class ActivePaymentMethod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('Active payment method [Check: Initialized] Will check process to use only activated payment methods.');

        // Get Context
        $context = $request->get('context', 'default');
        $possible = Arr::get(config('payment.payment_methods'), $context, []);
        $payment_method = $request->get('payment_method', 'NOT_VALID');
        Log::info("Collected data to check - " . json_encode(compact('context', 'possible', 'payment_method')));

        // Check for the payment method present in the request or not.
        if($payment_method === 'NOT_VALID') {
            $message = "Bad Request - Payment method field missing.";
            Log::error($message);
            return response()->json(compact('message'), 400);
        }

        // Check if provided payment method is enabled or not.
        if(!in_array($payment_method, $possible)) {
            $message = "Unauthorized - Provided payment method is not available, contact admin.";
            Log::error($message);
            return response()->json(compact('message'), 422);
        }        

        // Everything looks fine, procceding to process the request.
        Log::info('Active payment method [Check: Pass] Proceeding to process request further.');
        return $next($request);
    }
}
