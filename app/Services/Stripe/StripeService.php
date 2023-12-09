<?php

namespace App\Services\Stripe;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class StripeService
{

    /**
     * @param float $amount
     * @param string $name
     * @param string $email
     * @return PromiseInterface|Response
     */
    public function requestPaymentIntent(float $amount, string $name, string $email)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
        ])->get(config('ph-config.PH_URL') . "/create-payment/intent", ['amount' => $amount, 'name' => $name, 'email' => $email]);
    }


    /**
     * card add request
     * @param $token
     * @return PromiseInterface|Response
     */
    public function cardAddRequest(string $token, string $payment_intent_id)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
        ])->get(config('ph-config.PH_URL') . "/stripe-add-card",
            ['token' => $token, 'payment_intent_id' => $payment_intent_id]);
    }


}
