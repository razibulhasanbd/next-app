<?php

namespace App\Jobs;

use App\Models\Account;
use App\Services\TradeService;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TradeClose implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $tradeValues;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, object $tradeValues)
    {

        $this->account     = $account;
        $this->tradeValues = $tradeValues;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tradeService = new TradeService();
        $response = $tradeService->closeTrade($this->account, $this->tradeValues);
        if ($response["code"] != 200) {
            Log::error("Trade can not close because------", [$response]);
        }
    }
}
