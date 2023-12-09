<?php

namespace App\Services\ProfitChecker;

use Exception;
use App\Models\Plan;
use App\Models\Account;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Log;
use App\Services\TradingAccountService;
use App\Services\ProfitChecker\Operations\EvaluationPhaseOne;

class ProfitCheckerService
{
    public $account;

    /**
     * profit checker constructor
     *
     * @param Account $account
     */
    public function __construct(Account $account){
        $this->account = $account;
    }


    /**
     * account operation
     *
     * @return void
     */
    public function accountOperation(){
        try {
            $planType = $this->account->plan->type;
            switch ($planType) {
                case Plan::EV_P1:
                    $response = EvaluationPhaseOne::checker($this->account);
                    return ResponseService::basicResponse(200, "", [], $response);
                    break;

                default:
                    # code...
                    break;
            }
        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::basicResponse(500, $exception->getMessage());
        }

    }
}
