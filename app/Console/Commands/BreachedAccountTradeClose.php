<?php

namespace App\Console\Commands;

use App\Constants\AppConstants;
use App\Constants\CommandConstants;
use App\Jobs\TradeCloseEvent;
use App\Models\Account;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BreachedAccountTradeClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Breached_Account_Trade_Close;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All breached account trade close';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Log::info("Breached accounts trade job closing command started");
            $accounts         = $this->getAccounts();

            foreach ($accounts as $account) {
                TradeCloseEvent::dispatch($account, [], false, AppConstants::QUEUE_TRADE_CLOSE_EVENT_JOB);
            }
            Log::info("Breached accounts trade job closing command started processing to close the jobs for ". $account->count() ." accounts");
            return 0;
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    private function getAccounts(){
        return Account::with(['plan', 'plan.server'])->where('breached', '1')->get();
    }
}
