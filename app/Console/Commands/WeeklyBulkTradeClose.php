<?php

namespace App\Console\Commands;

use App\Constants\AppConstants;
use App\Constants\CommandConstants;
use App\Jobs\TradeCloseEvent;
use App\Models\Account;
use App\Services\PlanRulesService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WeeklyBulkTradeClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = CommandConstants::Weekly_Bulk_Trade_Close;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All trade will be closed on friday';

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
            Log::info("Weekly job closing command started");
            $accounts         = $this->getAccounts();
            $planRulesService = new PlanRulesService(true);
            $now              = Carbon::now();
            $endOfDay         = $now->copy()->endOfDay();

            foreach ($accounts as $account) {
                $endingDate = Carbon::parse($account->currentSubscription->ending_at);
                $rules      = $planRulesService->getRules($account);
                if ((!isset($rules['WH']) || (isset($rules['WH']) && $rules['WH']['value'] == 0)) || ($endOfDay->gte($endingDate))) {
                    TradeCloseEvent::dispatch($account, [], false, AppConstants::QUEUE_TRADE_CLOSE_EVENT_JOB);
                }
            }
            Log::info("Weekly job closing command started processing to close the jobs");
            return 0;
        } catch (Exception $exception) {
            Log::error($exception);
        }

    }

    private function getAccounts(){
        return Account::with(['plan', 'plan.server', 'currentSubscription'])->where('breached', '0')->get();
    }
}
