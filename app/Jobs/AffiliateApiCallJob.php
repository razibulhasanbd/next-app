<?php

namespace App\Jobs;

use App\DataSource\OrderData;
use App\Services\FirstPromoterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AffiliateApiCallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;

    /**
     * Order generate constructor
     *
     * @param OrderData $orderData
     *
     * @return void
     */
    public function __construct(OrderData $orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new FirstPromoterService())->affiliate($this->orderData);
    }
}
