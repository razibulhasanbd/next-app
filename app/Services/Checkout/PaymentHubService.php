<?php

namespace App\Services\Checkout;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class PaymentHubService
{

    /**
     * make payment
     *
     * @param string $name
     * @param string $email
     * @param string $token
     * @param float $amount
     * @param string $reference
     * @param array $billingAddress
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response|JsonResponse
     */
    public static function requestPayment(string $name, string $email, string $token, float $amount, string $reference = null, string $successUrl = null, string $failUrl = null, $gatewayToken, $billingAddress = [])
    {
        return Http::withHeaders([
            'Accept'        => 'application/json',
            'project-token' => config('ph-config.PH_PROJECT_TOKEN'),
            'gateway-token' => $gatewayToken,
        ])->post(config('ph-config.PH_URL') . "/payment-hub",
            [
                'name'            => $name,
                'email'           => $email,
                'token'           => $token,
                'amount'          => $amount,
                'reference'       => $reference,
                'success_url'     => $successUrl,
                'fail_url'        => $failUrl,
                'billing_address' => $billingAddress,
            ]
        );
    }

    public static function requestCardInfo(string $email)
    {
        return Http::withHeaders([
            'Accept'        => 'application/json',
            'project-token' => config('ph-config.PH_PROJECT_TOKEN'),
            'gateway-token' => config('ph-config.PH_GATEWAY_TOKEN'),
        ])->post(config('ph-config.PH_URL') . "/customer/customer-cards-details",
            ['email' => $email]
        );
    }

    public function paymentHubTraCheck($transaction_id)
    {

        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
        ])->get(config('ph-config.PH_URL') . "/payments/transaction",
            [
                'transaction_id' => $transaction_id,
            ]
        );

    }

    public function makeCardPayment(int $id, float $amount, string $reference, string $gatewayToken)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
            'project-token'     => config('ph-config.PH_PROJECT_TOKEN'),
            'gateway-token'     => $gatewayToken,
        ])->get(config('ph-config.PH_URL') . "/customer/existing-cards-payment",
            [
                'id'        => $id,
                'amount'    => $amount,
                'reference' => $reference,
            ]
        );
    }

}
