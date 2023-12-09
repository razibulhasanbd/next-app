<?php

namespace App\Services\SendGrid;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;

class AccountEmailService
{
    public $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
        $this->account = $this->account->load('plan','customer');
    }

    public function sentEmail()
    {
        try {
            // Express Real, Evaluation Real migration email
            if ($this->account->plan->type == Plan::EX_REAL || $this->account->plan->type == Plan::EV_REAL) {
                $planTitle = explode(' ', $this->account->plan->type)[0];
                $details    = [
                    'template_id' => EmailConstants::REAL_ACCOUNT_ELIGIBLE,
                    'to_name'     => Helper::getOnlyCustomerName($this->account->customer->name),
                    'to_email'    => $this->account->customer->email,
                    'email_body'  => ['name'               => Helper::getOnlyCustomerName($this->account->customer->name), 'mt4_login_id' => $this->account->login,
                                      'mt4_login_password' => $this->account->password, 'mt4_server_id' => $this->account->plan->server->server,
                                      'plan_title'         => $planTitle]
                ];
                EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            }

           
        } catch (\Exception $e) {
            Log::error($e);
            Helper::discordAlert(
                "**Email sent failed for new account account id : " . $this->account->id
            );
        }

    }
}
