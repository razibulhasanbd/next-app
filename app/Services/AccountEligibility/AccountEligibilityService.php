<?php

namespace App\Services\AccountEligibility;

use App\Constants\FrontendResourceConstants;
use App\Models\Account;
use App\Models\CustomerKycs;
use App\Models\Plan;
use App\Services\KYCService;
use Throwable;

class AccountEligibilityService
{

    public const PHASE_ONE_PHASE_TWO_MIGRATION_TYPE = "phase_one_phase_two_migration";
    public const TRADING_DAYS_EXTENSION             = "trading_days_extension";

    public $account;

    /**
     * account eligibility constructor
     *
     * @param Account $account
     */
    public function __construct(Account $account){
        $this->account = $account;
    }


    /**
     * eligibility status
     *
     * @return array
     * @throws $exception
     */
    public function status() : array{
        try {
            if($this->account){
                if ($this->account->plan->type == Plan::EV_P1) {

                    //phase one phase two checker
                    $response = (new EvaluationPhaseOneMigration)->phaseOneMigrationEligibilityCheck($this->account);
                    $data['phase_one_phase_two_eligibility_status'] = [
                        'status'            => $response['data'],
                        'message'           => $response['message'],
                        'frontend_resource' => [
                            'button_name' => $response['data'] ? FrontendResourceConstants::PHASE_ONE_PHASE_TWO_BUTTON_NAME : null
                        ],
                        'type' => $response['data'] ? self::PHASE_ONE_PHASE_TWO_MIGRATION_TYPE : null,
                    ];

                    // trading days extension checker
                    $response = (new TradingDaysExtension)->tradingDaysExtensionEligibilityCheck($this->account);
                    $data['trading_days_extension_eligibility_status'] = [
                        'status'            => $response['data'],
                        'message'           => $response['message'],
                        'frontend_resource' => [
                            'button_name' => $response['data'] ? FrontendResourceConstants::TRADING_DAYS_BUTTON_NAME : null
                        ],
                        'type' => $response['data'] ? self::TRADING_DAYS_EXTENSION : null,
                    ];

                    return $data;

                // kyc verification check
                }elseif (in_array($this->account->plan->type, [Plan::EV_P2, Plan::EX_DEMO])){
                   $response = (new KYCService())->kycEligibilityCheck($this->account);
                    $totalAttempt = $response['data']['status'] ? CustomerKycs::where('customer_id', $this->account->customer_id)->groupBy('customer_id')->count() : 0;

                    $type = $response['data']['type'];
                    if($response['data']['step'] == 1 && $type == CustomerKycs::TYPE_KYC){
                        if($totalAttempt >= (int)env('MAX_ATTEMPTS',3)){
                            $type = CustomerKycs::TYPE_TERMINATE;
                        }
                    }

                    $data['kyc_status'] = [
                        'status'            => $response['data']['status'],
                        'type'              => $type,
                        'step'              =>  $response['data']['step'],
                        'message'           => $response['message'],
                        'customer_info'     => $response['data']['customer_info'],
                        'max_attempt'       => (int)env('MAX_ATTEMPTS',3),
                        'total_attempt'     => $totalAttempt,
                    ];
                    return $data;
                }
                return [
                    'status'  => false,
                    'message' => "Feature not available",
                ];
            }
            else{
                return [
                    'status'  => false,
                    'message' => "Account not found",
                ];
            }

        } catch (Throwable $exception) {
            throw $exception;
        }
    }


    /**
     * eligibility action
     *
     * @return array
     * @throws $exception
     */
    public function action(string $type): array
    {
        try {
            if($this->account){
                if ($this->account->plan->type == Plan::EV_P1) {
                    if ($type == self::PHASE_ONE_PHASE_TWO_MIGRATION_TYPE) {
                        $response = (new EvaluationPhaseOneMigration)->phaseOneToPhaseTwoMigrateRequest($this->account);
                        return [
                            'status'  => $response['data'],
                            'message' => $response['message'],
                        ];
                    }

                    if ($type == self::TRADING_DAYS_EXTENSION) {
                        $response = (new TradingDaysExtension)->daysExtendRequest($this->account);
                        return [
                            'status'  => $response['data'],
                            'message' => $response['message'],
                        ];
                    }
                }

                return [
                    'status'  => false,
                    'message' => "Feature not available",
                ];
            }
            else{
                return [
                    'status'  => false,
                    'message' => "Account not found",
                ];
            }

        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
