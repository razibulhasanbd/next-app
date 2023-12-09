<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PhOutsidePaymentService
{
    /**
     * Call outside payment store to ph server
     * @param string $name
     * @param string $email
     * @param float $amount
     * @param string $transaction_id
     * @param int $reference
     * @return PromiseInterface|Response
     */
    public function phOutsidePayment(string $name, string $email, float $amount, string $transaction_id, string $reference)
    {
        return Http::withHeaders([
            'Accept'            => 'application/json',
            'communication-key' => config('ph-config.COMMUNICATION_KEY'),
            'gateway-token'     => config('ph-config.GATEWAY_TOKEN_OUTSIDE'),
            'project-token'     => config('ph-config.PH_PROJECT_TOKEN'),
        ])->post(config('ph-config.PH_URL') . "/payment/outside",
            [
                'name'           => $name,
                'email'          => $email,
                'amount'         => $amount,
                'transaction_id' => $transaction_id,
                'reference'      => $reference,
            ]
        );
    }
}
