<?php

namespace App\Services\AccountEligibility;

use Throwable;
use Carbon\Carbon;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\ExtendCycleLog;
use App\Services\AccountService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\DB;
use App\Services\RuleChecker\PTservice;
use Symfony\Component\HttpFoundation\Response;

class TradingDaysExtension
{

    public const PROFIT_PERCENTAGE    = 5;
    public const DIFFERENT_DAYS       = 3;
    public const EXTENDED_DAYS        = 14;
    public const MINIMUM_TRADING_DAYS = 5;
    /**
     * Get Account Profit Percentage
     *
     * @param Account $account
     * @return float
     */
    public function getProfitPercentage(Account $account): float
    {

        $accountService  = new AccountService();
        $margin = $accountService->margin($account);
        $startingBalance  = $account->starting_balance;
        $accountProfit    = $margin["currentBalance"] - $startingBalance;
        $inProfit = ($accountProfit / $startingBalance) * 100;
        return $inProfit;
    }

    /**
     * Trading Days Extension Eligibility Status
     *
     * @param Account $account
     * @return array
     */
    public function tradingDaysExtensionEligibilityCheck(Account $account): array
    {
        try {
            if (ExtendCycleLog::where('account_id', $account->id)->where('eligibility', 0)->orderBy('id', 'desc')->first()) return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "You have already obtained Cycle Extension.", [], false);

            $accountLatestSub = $account->latestSubscription;

            if (Carbon::today()->format('Y-m-d') == Carbon::createFromFormat('Y-m-d H:i:s', $accountLatestSub->ending_at)->format('Y-m-d')) return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Today subscription end day", [], false);

            $compAccountSubEndDate = Carbon::createFromFormat('Y-m-d H:i:s', $accountLatestSub->ending_at)->gte(Carbon::today());
            $diffDays              = Carbon::today()->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s', $accountLatestSub->ending_at));

            if ($compAccountSubEndDate && $diffDays <= self::DIFFERENT_DAYS) {
                if($account->tradingDays() < self::MINIMUM_TRADING_DAYS) return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Feature Minimum Trading Days Not full filled.", [], false);

                if (PTservice::check($account)) return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Profit Target Reached.", [], false);

                return (self::PROFIT_PERCENTAGE <= $this->getProfitPercentage($account)) ?
                    ResponseService::basicResponse(Response::HTTP_OK, "Yes You are a eligible for the trading days extension.", [], true) :
                    ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Profit Percentage Rule Does not match!.", [], false);
            } else {
                return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, "Different days Minimum Trading Days Not full filled.", [], false);
            }
        } catch (Throwable $exception) {
            throw $exception;
        }
    }


    /**
     * trading days extension request
     *
     * @param Account $account
     * @return array
     */
    public function daysExtendRequest(Account $account): array
    {
        try {
            $response = $this->tradingDaysExtensionEligibilityCheck($account);
            if ($response['data']) {

                $getSubscription = $account->latestSubscription;
                $weekRequest = self::EXTENDED_DAYS;

                $ending_at = Helper::subendDaysFrom(self::EXTENDED_DAYS, $getSubscription->ending_at);

                DB::beginTransaction();

                optional(ExtendCycleLog::whereAccountId($account->id)->latest())
                ->update(['eligibility' => 0]);

                ExtendCycleLog::create(
                    [
                        'weeks'               => $weekRequest,
                        'before_subscription' => $getSubscription->ending_at,
                        'after_subscription'  => $ending_at['string'],
                        'login'               => $account->login,
                        'subcription_id'      => $getSubscription->id,
                        'account_id'          => $account->id,
                    ]
                );
                $getSubscription->update(['ending_at' => $ending_at['string']]);
                Helper::discordAlert("**Cycle Extended By User**:\nAccntID : " . $account->id . "\nLogin : " . $account->login . "\nWeeks : " . $weekRequest . "\nEndDate : " . $ending_at['string']);
                DB::commit();

                return ResponseService::basicResponse(Response::HTTP_OK, "Account trading days extended successfully.", [], true);
            }
            return ResponseService::basicResponse(Response::HTTP_PARTIAL_CONTENT, $response['message'], [],  false);
        } catch (Throwable $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
