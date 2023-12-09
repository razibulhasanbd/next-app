<?php

namespace App\Services\ApproveAccount;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Log;
use App\Models\TargetReachedAccount;
use App\Services\ApproveAccount\Operations\EvaluationRealAccountApprove;
use App\Services\ApproveAccount\Operations\ExpressRealAccountApprove;
use Throwable;

class ApproveAccountService
{

    public $account, $targetReachedRow, $profitAmounts, $scaleUp;
    /**
     * approve account constructor
     *
     * @param Account $account
     */
    public function __construct(int $accountId, int $targetReachedRowId, float $profit = 0, float $withdrawableAmount = 0, float $growthFundAmount = 0, bool|string $willScaleUp = false, float $scaleUpAmount = 0)

    {
        $this->account = Account::with(['plan'])->find($accountId);
        $this->targetReachedRow = TargetReachedAccount::with('approval_category')->find($targetReachedRowId);
        $this->profitAmounts = [
            'profit'             => $profit,
            'withdrawableAmount' => $withdrawableAmount,
            'growthFundAmount'   => $growthFundAmount,
        ];
        $this->scaleUp = [
            'willScaleUp'   => $willScaleUp,
            'scaleUpAmount' => $scaleUpAmount ?? 0,
        ];
    }

    /**
     * account operation
     *
     * @return void
     */
    public function approveAccount()
    {
        try {

            $planType = $this->account->plan->type;

            switch ($planType) {
                case Plan::EV_REAL:
                    $response = EvaluationRealAccountApprove::approve($this->account, $this->targetReachedRow, $this->profitAmounts, $this->scaleUp);
                    return ResponseService::basicResponse(200, "", [], $response);
                    break;
                case Plan::EX_REAL:
                    $response = ExpressRealAccountApprove::approve($this->account, $this->targetReachedRow, $this->profitAmounts, $this->scaleUp);
                    return ResponseService::basicResponse(200, "", [], $response);
                    break;
                default:
                    # code...
                    break;
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
