<?php

namespace App\Jobs;

use Exception;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use App\Services\AccountService;
use App\Services\RuleBreachService;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BeachEventNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $breachedBy;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, $breachedBy)
    {
        $this->account    = $account;
        $this->breachedBy = $breachedBy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->account->refresh();
        $ruleBreachService = new RuleBreachService(new AccountService);
        $ruleBreachService->notifyBackend($this->account, $this->breachedBy);
        Helper::discordAlert("**Breach Event**:\nAccntID : " . $this->account->id . "\nLogin : " . $this->account->login . "\nBreached By : " . $this->breachedBy . "\nMetricID : " . $this->account->todayMetric->id);
        if($this->account->customer->tags != 0 || $this->account->customer->tags != null){
            Helper::discordAlert("
            **" . Customer::TAGS[$this->account->customer->tags] . "Customer" . "**:
            **Breach Event**:\nAccntID : " . $this->account->id . "\nLogin : " . $this->account->login . "\nBreached By : " . $this->breachedBy . "\nMetricID : " . $this->account->todayMetric->id
            ,true);
        }
    }
}
