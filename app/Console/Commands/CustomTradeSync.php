<?php

namespace App\Console\Commands;

use App\Constants\AppConstants;
use App\Constants\CommandConstants;
use App\Jobs\TradeSync;
use App\Models\Account;
use Illuminate\Console\Command;

class CustomTradeSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Custom_TradeSync;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $accounts = $this->getAccounts($this->option("accountId"));
        $this->info($accounts->count(). " accounts trade sync is in process");
        foreach ($accounts as $account) {
            TradeSync::dispatch($account, $this->option("fromHour"), $this->option("toHour"))->onQueue(AppConstants::QUEUE_TRADE_SYNC_JOB);
        }
        $this->info($accounts->count() . " accounts trade sync job is dispatched successfully");
    }

    /**
     * get trade sync delegable accounts
     *
     * @param $array $accounts
     * @return object
     */
    private function getAccounts($accounts = []){
        if(sizeof($accounts)){
            return Account::with('plan', 'plan.server')->where("breached", 0)->whereIn('id', $accounts)->get();
        }
        return Account::with('plan', 'plan.server')->where("breached", 0)->get();
    }
}
