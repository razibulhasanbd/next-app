<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\Models\Account;
use App\Models\Plan;
use App\Services\TradingAccountService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AccountBalanceDepositJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account, $plan, $balance;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, Plan $plan, float $balance)
    {
        $this->account = $account;
        $this->plan    = $plan;
        $this->balance = $balance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = (new TradingAccountService)->deposit($this->account, $this->plan, $this->balance);

            Log::info("Deposit Response", [$response]);

            if(!$response->successful()){
                throw new Exception;
            }
        } catch (Exception $exception) {
            $this->fail();
            Log::error("Account deposit error: ", [$exception]);
            Helper::discordAlert(
                "**@sauvik Account deposit failed **:\nAccntID : " . $this->account->id
            );
        }
    }
}
