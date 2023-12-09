<?php

namespace App\Services;

use App\DataSource\OrderData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirstPromoterService
{
    /**
     * @param OrderData $orderData
     * @return void
     */
    public function affiliate(OrderData $orderData)
    {
        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'x-api-key' => config('first-promoter.PROMOTER_TOKEN'),
        ])->post(config('first-promoter.PROMOTER_URL') . "/track/sale",
            [
                'email'    => $orderData->email,
                'event_id' => $orderData->transactionId,
                'amount'   => $orderData->gradTotal * 100
            ]
        );
        if (!$response->successful()) {
            Log::error($response->body());
        }
    }
}
