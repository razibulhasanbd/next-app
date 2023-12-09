<?php

namespace App\Services;


use App\Models\Plan;
use App\Jobs\EmailJob;
use App\Models\Account;
use Mpdf\MpdfException;
use App\Models\Customer;
use App\Models\CustomerKycs;
use App\Constants\AppConstants;
use App\Models\KycAgreementLogs;
use App\Constants\EmailConstants;
use App\Models\TargetReachedAccount;
use App\Services\RuleChecker\PTservice;
use App\Services\RuleChecker\MTDservice;
use Symfony\Component\HttpFoundation\Response;

class KYCService
{
    /**
     * @param Account $account
     * @return array
     */
    public function kycEligibilityCheck(Account $account)
    {
        if ($account->breached == 1 && $account->breachedby == 'Profit Target Reached') {
            $targetReach = TargetReachedAccount::where('account_id', $account->id)->whereNotNull('approved_at')->first();
            if($targetReach){
                return ResponseService::basicResponse(Response::HTTP_OK, "Your verification has been successfully completed", [], ['status' => true, 'type' => CustomerKycs::TYPE_APPROVED, 'customer_info' => [], 'step'=> null]);
            }else{
                $targetReach = TargetReachedAccount::where('account_id', $account->id)->whereNull('approved_at')->first();
                if (!$targetReach) {
                    return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible.", [], ['status' => false, 'type' => null, 'customer_info' => [], 'step' => null]);
                }
            }

//            $rules_reached   = json_decode($targetReach->rules_reached);
//            $newsTrades      = isset($rules_reached->news) ? json_decode($rules_reached->news) : [];
//            if($account->plan->type == Plan::EX_DEMO && count($newsTrades)){ // NEWS TRADE CHECK ONLY EXPRESS DEMO
//                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible because of news trade", [], ['status' => false, 'type' => null, 'customer_info' => [], 'step'=> null]);
//            }

            $kycInfo = CustomerKycs::with('customer.customerCountry')->where('customer_id', $account->customer_id)->where('status', AppConstants::KYC_APPROVED)->first();
            $accountWiseApprovalCheck    = $this->accountWiseApprovalCheck($account);
            if ($accountWiseApprovalCheck) {
                return ResponseService::basicResponse(Response::HTTP_OK, "Your verification has been successfully completed", [], ['status' => true, 'type' => CustomerKycs::TYPE_APPROVED, 'customer_info' => [], 'step'=> null]);
            }

            $customerInfo = [];
            $type = CustomerKycs::TYPE_KYC;
            if ($kycInfo) {
                $data = json_decode($kycInfo->kyc_response);
                $type         = CustomerKycs::TYPE_FORM;
                $first_name = $data->verification->person->firstName ?? null;
                $last_name = $data->verification->person->lastName ?? null;
                $country = $data->verification->document->country ?? null;
                $customerInfo = [
                    'name'    => $first_name .' '. $last_name,
                    'country' => $country,
                ];
            }

            if ($this->kycCheckForCustomer($account) >= 1) { // KYC agreement from second account
                $type = CustomerKycs::TYPE_FORM;
                if ($this->agreementCheckFromSecondAccount($account)) {
                    return ResponseService::basicResponse(Response::HTTP_OK, "Your verification has been successfully completed", [], ['status' => true, 'type' => CustomerKycs::TYPE_APPROVED, 'customer_info' => [], 'step' => 2]);
                }
                return ResponseService::basicResponse(Response::HTTP_OK, "waiting for Agreement sign", [], ['status' => true, 'type' => $type, 'customer_info' => $customerInfo, 'step' => 2]);
            }

            // For the first time kyc
            if (!$this->accountWiseAgreementCheck($account)) {
                return ResponseService::basicResponse(Response::HTTP_OK, "waiting for kyc verification", [], ['status' => true, 'type' => $type, 'customer_info' => $customerInfo, 'step'=> 1]);
            }

            if (!$accountWiseApprovalCheck) {
                return ResponseService::basicResponse(Response::HTTP_OK, "Your documents have been submitted successfully and are currently under review. Our compliance team will get back to you within 48 hours", [], ['status' => true, 'type' => CustomerKycs::TYPE_UNDER_REVIEW, 'customer_info' => [], 'step'=> 1]);
            }

        }
        return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Not eligible.", [], ['status' => false, 'type' => null, 'customer_info' => [], 'step'=> null]);

    }


    /**
     * customer wise kyc check for the first time
     * @param $account
     * @return mixed
     */
    public function kycCheckForCustomer($account)
    {
        return CustomerKycs::where('customer_id', $account->customer_id)
            ->where('approval_status', CustomerKycs::STATUS_ENABLE)
            ->count();
    }

    /**
     * user agreement check for the first time
     * @param $account
     * @return mixed
     */
    private function accountWiseAgreementCheck($account){
        return CustomerKycs::where('customer_id', $account->customer_id)
            ->where('account_id', $account->id)
            ->where('status', AppConstants::KYC_APPROVED)
            ->where('user_agreement', CustomerKycs::STATUS_ENABLE)
            ->first();
    }

    /**
     * account wise approval check
     * @param $account
     * @return mixed
     */
    private function accountWiseApprovalCheck($account){
        return CustomerKycs::where('customer_id', $account->customer_id)
            ->where('account_id', $account->id)
            ->where('approval_status', CustomerKycs::STATUS_ENABLE)
            ->first();
    }

    /**
     * Agreement check from second accounts
     * @param $account
     * @return mixed
     */
    private function agreementCheckFromSecondAccount($account)
    {
        return CustomerKycs::where('customer_id', $account->customer_id)
            ->where('account_id', $account->id)
            ->where('user_agreement', 1)
            ->first();
    }
}
