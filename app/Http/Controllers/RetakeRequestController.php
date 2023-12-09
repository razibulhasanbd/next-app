<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helper\Helper;
use App\Jobs\SendEmailJob;
use App\Models\Account;
use App\Models\GrowthFund;
use App\Models\RetakeRequest;
use App\Models\Subscription;
use App\Models\TopupLog;
use App\Services\SendGrid\SendMailService;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RetakeRequestController extends Controller
{
    //

    public function eligible(int $id)
    {

        $account = Account::findOrFail($id);
        $planRules = $account->planRules();
        $subscription = $account->latestSubscription;
        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $now = Carbon::today();

        if ($account->plan->type != Account::EV_P1) {
            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => false,
                'reason' => 'not EV P1 plan',
            ]);
        }
        $pendingRequest = RetakeRequest::where([
            'account_id' => $account->id,
            'subscription_id' => $subscription->id,
            'approved_at' => null,
            'denied_at' => null,
        ])->first();

        if ($pendingRequest != null) {

            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => false,
                'request_status' => 'pending',
            ]);
        }

        $deniedRequest = RetakeRequest::where([
            'account_id' => $account->id,
            'subscription_id' => $subscription->id,
        ])->where('denied_at', '!=', null)->first();

        if ($deniedRequest != null) {

            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => false,
                'request_status' => 'declined',
                'message' => $deniedRequest->admin_message,
            ]);
        }

        if ($now->lte(date('Y-m-d', strtotime($subscription->ending_at)))) {

            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => false,
                'reason' => 'subscription did not end yet',
            ]);
        }

        if (!$account->breached) {
            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => false,
                'reason' => 'Account not breached',
            ]);
        }

        $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
        $redisData = json_decode($redisDataFromApi, 1);

        $currentBalance = $redisData['balance'];
        $acntStartingBalance = $account->starting_balance;
        $accountProfit = $currentBalance - $acntStartingBalance;
        $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;
        $runningTrades = Redis::smembers('orders:' . $account->login . ':working');
        $tradingDays = $account->tradingDays();
        if (($accountProfit > 0) && ($profitPercentage < $planRules['PT']['value']) && (($tradingDays) >= $planRules['MTD']['value']) && (empty($runningTrades))) {

            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => true,
                'message' => "You are eligible to request for a retake!",
            ]);
        } else {

            return response()->json([
                'account_id' => $account->id,
                'retake_eligible' => false,
                'reason' => [
                    'profit' => $accountProfit,
                    'profitPercentage' => $profitPercentage,
                    'tradingDays' => $tradingDays,
                    'running_trades' => empty($runningTrades) ? false : json_encode($runningTrades),
                    'login' => $account->login,
                ],
            ]);
        }
    }

    public function receiveRetakeRequest(Request $request)
    {
        if (isset($request->account_id) && ($request->retake_request == true)) {
            $account = Account::with('plan')->find($request->account_id);
            $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
            $currentBalance = $redisData['balance'];
            $currentEquity = $redisData['equity'];
            $acntStartingBalance = $account->starting_balance;
            $accountProfit = $currentBalance - $acntStartingBalance;
            $profitPercentage = ($accountProfit / $acntStartingBalance) * 100;

            DB::beginTransaction();
            try {
                RetakeRequest::create(
                    [
                        'account_id' => $account->id,
                        //! target reach category
                        'metric_info' => json_encode([
                            'balance' => $currentBalance,
                            'equity' => $currentEquity,
                            'starting_balance' => $acntStartingBalance,
                        ]),
                        'rules_reached' => json_encode([
                            'minimum_trading_days' => $account->tradingDays(),
                            'profit_percentage' => $profitPercentage,
                        ]),
                        'plan_id' => $account->plan_id,
                        'subscription_id' => $account->latestSubscription->id,
                    ]
                );
                DB::commit();
                return response()->json(['account_id' => $account->id, 'status' => "success", 'message' => "Retake requested successfully!", 'retake_request' => 'pending', 'retake_eligible' => false], 200);
            } catch (\Exception $e) {
                throw $e;
                DB::rollback();
                return response()->json(['account_id' => $account->id, 'status' => "failed", 'message' => "Retake requested is not successfully!"], 404);
            }
        } else {
            return response()->json(['status' => "failed", 'message' => "Failed To Request retake try again latter!"], 404);
        }
    }

    public function retakeRequestList()
    {
        abort_if(Gate::denies('target_reached_account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $retakeRequestList = RetakeRequest::with(['account', 'plan'])->get();
        return view('admin.retakes.retakeRequestList', compact('retakeRequestList'));
    }

    public function retakeRequestModal($id)
    {
        if ($id) {
            $list = RetakeRequest::with('account')->find($id);
            $getValue = [
                'account_id' => $list->account_id,
                'login' => $list->account->login,
                'retake_id' => $list->id,
            ];
            return response()->json($getValue);
        } else {
            return false;
        }
    }

    public function approveRetakeRequest(Request $request)
    {
        $account = Account::find($request->account_id);

        $approveStatus = $this->requestApprove($account->login);
        $approveStatus = $approveStatus->getData();
        if (isset($approveStatus->error)) {
            Session::flash('alert-warning', 'Something Went Wrong!');
            return redirect()->back();
        } else {
            DB::beginTransaction();
            try {
                $getRetakeRequest = RetakeRequest::find($request->retake_id);
                $getRetakeRequest->update(['admin_message' => $request->admin_message, 'denied_at' => null, 'approved_at' => Carbon::now()]);

                //!sendMail
                $details = [
                    'template_id' => SendMailService::Retake_Request_Accepted,
                    'name' => Helper::getOnlyCustomerName($account->customer->name),
                    'email' => $account->customer->email,
                    'login_id' => $account->login,
                    'date' => Carbon::parse($getRetakeRequest->created_at)->format('Y-m-d'),
                ];
                SendEmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_ON_BREACH_NOTIFY_USER_JOB);
                DB::commit();
            } catch (\Exception $e) {
                Log::info("error hoise\n" . $e);
                DB::rollback();
                throw $e;
                return "no";
            }
            Session::flash('alert-success', 'Retake Request Approved');
            return redirect()->route('retakeRequestList');
        }
    }

    public function retakeDenyRequestModal($id)
    {
        if ($id) {
            $list = RetakeRequest::with('account')->find($id);
            $getValue = [
                'account_id' => $list->account_id,
                'login' => $list->account->login,
            ];
            return response()->json($getValue);
        } else {
            return false;
        }
    }

    public function denyRetakeRequest(Request $request)
    {
        RetakeRequest::where('account_id', '=', $request->account_id)
            ->update(['admin_message' => $request->admin_message, 'denied_at' => Carbon::now()]);
        return redirect()->route('retakeRequestList');
    }

    public function requestApprove($loginId)
    {

        try {
            $account = Account::with('plan')->whereLogin($loginId)->first();

            $planRules = $account->planRules();
            $accountStartingBalance = $account->starting_balance;

            $server = $account->server;
            $url = $server->url;
            $sessionToken = $account->server->login;
            Redis::del('margin:' . $account->login); //!Delete Redis Key
            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];

            if ($accountStartingBalance <= $currentBalance) {
                //! in profit

                //!MT4 Update Withdraw

                $withdraw = Http::acceptJson()->post($url . "/user/withdraw?token=" . $sessionToken, [

                    'login' => $account->login,
                    'amount' => $currentBalance - $accountStartingBalance,
                    "is_credit" => false,
                    "comment" => "Withdraw topup",
                    "check_free_margin" => false,
                ]);
                if (json_decode($withdraw, 1)['code'] != 200) {

                    Session::flash('alert-danger', 'Error: Could not withdraw on account!');
                    return redirect()->back();
                }
            } else {

                $deposit = Http::acceptJson()->post($url . "/user/deposit?token=" . $sessionToken, [

                    'login' => $account->login,
                    'amount' => $accountStartingBalance - $currentBalance,
                    "is_credit" => false,
                    "comment" => "Deposit-Topup",
                    "check_free_margin" => false,
                ]);

                if (json_decode($deposit, 1)['code'] != 200) {

                    Session::flash('alert-danger', 'Error: Could not deposit to account!');
                    return redirect()->back();
                }
            }

            $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
                'login' => $account->login,
                'read_only' => 0,

            ]);
            if (json_decode($updateAccount, 1)['code'] != 200) {

                Session::flash('alert-danger', 'Error:Could not enable account!');
                return redirect()->back();
            }

            $account->breached = false;
            $account->breachedby = null;
            $account->balance = $accountStartingBalance;
            $account->equity = $accountStartingBalance;

            $last_metric = $account->beforeLatestMetric->toJson();
            $breachMetric = $account->latestMetric->toJson();

            $subend = Helper::subend_days($account->duration);
            $subscription = Subscription::create([
                'account_id' => $account->id,
                'login' => $account->login,
                'plan_id' => $account->plan_id,
                'ending_at' => $subend['string'],
            ]);

            //!create topup log
            $saveTopupLog = TopupLog::create(
                [
                    'account_id' => $account->id,
                    'last_metric' => $last_metric,
                    'breach_metric' => $breachMetric,
                    'topup_amount' => $accountStartingBalance - $currentBalance,
                ]
            );

            //Clear Growth Fund
            if (isset($planRules['AGF'])) {
                $growthFund = GrowthFund::whereAccountId($account->id)->delete();
            }
            //!delete old metrics
            $account->beforeLatestMetric->delete();
            $account->latestMetric->delete();
            $account->unsetRelation('plan');
            $account->unsetRelation('accountRules');
            $account->unsetRelation('planRules');
            $account->push();
        } catch (\Exception $e) {
            // TODO:flash error
            throw $e;
            return response()->json(['message' => $e, 'error' => "failed"], 404);
        }
        Cache::forget($account->id . ':firstTrade');
        //Helper::discordAlert("**Retake Request Approved**:\nAccntID : " . $account->id . "\nTopup Amount : " . $accountStartingBalance - $currentBalance . "\nTopup Log ID : " . $saveTopupLog->id);

        // Session::flash('alert-success', 'Account Topup Successfull by $' . ($accountStartingBalance - $currentBalance));
        return response()->json(['message' => "success"], 200);
    }
}
