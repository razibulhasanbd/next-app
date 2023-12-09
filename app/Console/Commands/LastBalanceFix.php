<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\AccountMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class LastBalanceFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:last-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct last balance of 16th November of all unbreached accounts';

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

        $accountMetrics = AccountMetric::with('account')->where('metricDate', '2022-11-16 00:00:00')->get();

        foreach ($accountMetrics as $accountMetric) {
            $account = $accountMetric->account;
            if ($account->breached == 0) {
                $margin = json_decode(Redis::get('margin:' . $account->login), 1);
                if ((isset($margin['last_day_balance'])) && ($margin['last_day_balance'] != 0)) {

                    $lastBalance = $margin['last_day_balance'];
                    $this->info("updating last day balance (" . $lastBalance . ") for login: " . $account->login);

                    $accountMetric->lastBalance = $lastBalance;
                    $accountMetric->save();
                }
            }
        }


        return 0;
    }
}
