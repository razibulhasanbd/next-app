<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use App\Services\TradeService;
use App\Constants\AppConstants;
use App\Services\AccountService;
use App\Services\RuleBreachService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class BreachEventJob implements ShouldQueue,ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;
    public $account;
    public $breachedBy;
    public $margin;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor=1200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, $breachedBy, $margin)
    {
        $this->account    = $account;
        $this->breachedBy = $breachedBy;
        $this->margin     = $margin;
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->account->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('breach event job started for account: ' . $this->account->id);
        $tradeService      = new TradeService();
        $runningTrades     = $tradeService->getRunningTrades($this->account);
        $ruleBreachService = new RuleBreachService(new AccountService());
        $ruleBreachService->takeBreachEventSnapshot($this->account, $this->margin, $runningTrades);
        $ruleBreachService->breachEvent($this->account, $this->breachedBy, $runningTrades);
        BeachEventNotificationJob::dispatch($this->account, $this->breachedBy)->onQueue(AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB);
    }
}
