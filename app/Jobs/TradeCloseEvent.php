<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Models\Account;
use App\Services\AccountService;
use App\Services\TradeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TradeCloseEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $runningTrades;
    public $breachAccountUpdateStatus;
    public $queueName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, $runningTrades, bool $breachAccountUpdateStatus = true, string $queueName = AppConstants::QUEUE_TRADE_CLOSE_JOB)
    {
        $this->account                   = $account;
        $this->runningTrades             = $runningTrades;
        $this->breachAccountUpdateStatus = $breachAccountUpdateStatus;
        $this->queueName                 = $queueName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tradeService = new TradeService();

        $response = $tradeService->bulkTradeClose($this->account);
        if ($response["code"] == 401) {
            $response = $tradeService->bulkTradeClose($this->account);
            if ($response["code"] == 401) {
                Log::error("All trade can not close for Account login ------ " . $this->account->login, [$response]);
            }
        }

        if ($this->breachAccountUpdateStatus) {
            $accountService = new AccountService();
            $accountService->updateBreachedAccount($this->account);
        }

    }
}
