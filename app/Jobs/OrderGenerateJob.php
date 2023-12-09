<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\DataSource\OrderData;
use App\Services\FirstPromoterService;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;

    /**
     * Order generate constructor
     *
     * @param OrderData $orderData
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
        (new OrderService())->generateOrder($this->orderData);
        if($this->orderData->orderType == AppConstants::PRODUCT_ORDER_NEW_ACCOUNT){
            (new FirstPromoterService())->affiliate($this->orderData);
        }
    }
}
