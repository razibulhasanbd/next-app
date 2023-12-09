<?php

namespace App\Http\Controllers\Admin;


use Gate;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Trade;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Traits\Auditable;
use App\Models\GrowthFund;
use App\Models\AccountRule;
use App\Models\Customer;
use App\Models\CustomerKycs;
use App\Models\NewsCalendar;
use App\Models\Subscription;
use App\Services\KYCService;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use App\Services\VeriffService;
use App\Models\ApprovalCategory;
use App\Services\AccountService;
use App\Models\KycAgreementLogs;
use App\Constants\EmailConstants;
use App\Services\ResponseService;
use Illuminate\Support\Facades\DB;
use App\Models\AccountRuleTemplate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\TargetReachedAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Services\RulesService\DurationService;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Controllers\ScheduledJobsController;
use App\Http\Requests\StoreTargetReachedAccountRequest;
use App\Http\Requests\UpdateTargetReachedAccountRequest;
use App\Http\Requests\MassDestroyTargetReachedAccountRequest;
use App\Services\ApproveAccount\ApproveAccountService;

class TargetReachedAccountsController extends Controller
{
    // public function index(Request $request)
    // {

    //     abort_if(Gate::denies('target_reached_account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $targets = TargetReachedAccount::with(['account', 'plan', 'approval_category'])->get();

    //     $accounts = Account::get();
    //     $plans = Plan::get();
    //     $subscriptions = Subscription::get();
    //     $approval_categories = ApprovalCategory::get();

    //     return view('admin.targetReachedAccounts.index', compact('accounts', 'plans', 'targets', 'approval_categories'));
    // }

    public function index(Request $request)
    {

        abort_if(Gate::denies('target_reached_account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TargetReachedAccount::with(['account.customer.approvedCustomerKyc', 'plan', 'approval_category'])->select(sprintf('%s.*', (new TargetReachedAccount())->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'target-reached-account_show';
                $editGate = 'target-reached-account_edit';
                $deleteGate = 'target-reached-account_delete';
                $crudRoutePart = 'target-reached-accounts';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('account.id', function ($row) {
                return  $row->account ? $row->account->id : '';
            });
            $table->editColumn('account.customer.name', function ($row) {
                return $row->account->customer ? (is_string($row->account->customer) ? $row->account->customer : $row->account->customer->name) : '';
            });
            $table->editColumn('account.login', function ($row) {
                return  $row->account ? '<a href="' . route('admin.accounts.show', $row->account->id) . '">' . $row->account->login . '</a>' : '';
            });
            $table->editColumn('account.customer.email', function ($row) {
                if (Gate::allows('account_email_column_hide_searchable')) {
                    return $row->account->customer ? (is_string($row->account->customer) ? $row->account->customer : '*****') : '';
                } else
                    return $row->account->customer ? (is_string($row->account->customer) ? $row->account->customer : $row->account->customer->email) : '';
            });

            $table->editColumn('plan.description', function ($row) {
                return $row->plan ? (is_string($row->plan) ? $row->plan : $row->plan->description) : '';
            });

            $table->editColumn('plan.next_plan', function ($row) {
                return \App\Models\Plan::where('id', $row->plan->next_plan)->value('description');
            });

            $table->editColumn('approval_category.name', function ($row) {
                return $row->approval_category ? (is_string($row->approval_category) ? $row->approval_category : $row->approval_category->name) : '';
            });

            $table->editColumn('created_at', function ($row) {
                return frontEndTimeConverterView($row->created_at);
            });



            $table->editColumn('metric_info', function ($row) {

                $row = json_decode($row->metric_info);
                $data = [];
                foreach ($row as $key => $result) {
                    $data[] =  $result ? '<span class="badge badge-info">' . $key . ':' . $result . '</span>' : '';
                }
                return implode(" ", $data);
            });

            $table->editColumn('rules_reached', function ($row) {

                $rows = json_decode($row->rules_reached);
                $data = [];
                foreach ($rows as $key => $result) {
                    if ($key == 'news') {

                        $newsTrades = json_decode($result, true);
                        $data[] = count($newsTrades) != 0 ? '<a href="' . route('admin.account-news-trades.view', $row->account->id) . '" target="_blank">' . '<span class="badge badge-danger">' . 'News' . '</span>' . '</a>' : '';
                    } else {
                        $data[] =  $result ? '<span class="badge badge-success">' . $key . ':' . $result . '</span>' : '';
                    }
                }
                return implode(" ", $data);
            });
            $table->editColumn('account.customer.approvedCustomerKyc.status', function ($row) {
                $statusHtml = '';
                if (isset($row->plan->type) && $row->plan->type == Plan::EV_P2 || $row->plan->type  == Plan::EX_DEMO || $row->plan->type == Plan::EV_REAL || $row->plan->type == Plan::EX_REAL) {
                    if (isset($row->account->customer->approvedCustomerKyc) && $row->account->customer->approvedCustomerKyc->isNotEmpty()) {
                        $statusHtml = '<label class="badge badge-success"> Approved </label>';
                    }
                }
                return $statusHtml;
            });

            $table->editColumn('approved', function ($row) {

                if (isset($row->approved_at)) {
                    return  $data =   '<button type="button" class="btn btn-primary"  data-toggle="modal">' . 'Approved' . '</button>';
                } elseif (isset($row->denied_at)) {
                    return  $data = '<a class="btn btn-xs btn-warning" href="#">'
                        . 'Denied' .
                        '</a>';
                } else {

                    return $data =  '<button type="button" class="btn btn-primary" id="smallButton" data-toggle="modal"
                                            data-attr="' . route("getPlanId", $row->id) . '"
                                            data-target="#smallModal">Confirm</button>
                                            <button type="button" class="btn btn-xs btn-danger" id="denyButton"
                                            data-attr="' . route("admin.denayAccount", $row->id) . '">Deny
                                        </button>';
                }
            });



            $table->rawColumns(['actions', 'placeholder', 'account.id', 'account.login', 'account.customer.name', 'account.customer.email', 'plan.description', 'approval_category.name', 'metric_info', 'rules_reached', 'approved','account.customer.approvedCustomerKyc.status']);
            return $table->make(true);
            $accounts = Account::get();
            $plans = Plan::get();
            $subscriptions = Subscription::get();
            $approval_categories = ApprovalCategory::get();
        }

        return view('admin.targetReachedAccounts.indexreturn');
    }

    public function getModalInfo($id)
    {
        $approval_category_info = TargetReachedAccount::with('approval_category')->where('id', $id)->first();

        $account = Account::find($approval_category_info->account_id);
        $template = AccountRuleTemplate::with('rule_name')->where('plan_id', $account->plan_id)->get();
        $planRules = $account->planRules();

        //Redis Connection for account current balance
        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        if ($redisData != null) {
            $telescope[] = "Redis available";
            $currentBalance = $redisData['balance'];
        } else {

            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
        }

        $currentProfit = $currentBalance - $account->starting_balance;

        $growthFundAmount = 0;
        if (isset($planRules['AGF'])) {
            // ! If account has GrowthFund add that also to profit amount

            $growthFundAmount = $currentProfit;

            // $growthFunds = $account->growthFund;
            // if ($growthFunds != null) {

            //     $growthFundAmount = $growthFunds->sum('amount');
            // }
        }



        if (isset($planRules['PT']) && ($currentProfit >= ($account->starting_balance * ($planRules['PT']['value'] / 100)))) {

            $profit = $account->starting_balance * ($planRules['PT']['value'] / 100);
        } else {

            $profit = $currentProfit;
        }


        $withdrawableAmount = $profit * ($planRules['PS']['value']) / 100;


        $getValue = [
            'account_id' => $account->id,
            'tra_id' => $id,
            'profit' => $profit,
            'approval_category' => $approval_category_info->approval_category->name,
            'withdrawableAmount' => $withdrawableAmount,
            'growthFundAmount' => $growthFundAmount,
            'template' => $template,
            'scaleUpAmount' => $account->starting_balance * 1.4,
        ];

        return response()->json($getValue);
    }

    public function approveAccount(Request $request, AccountService $accountService)
    {
        $accountInfo = Account::with(['plan','customer','customer.customerCountry'])->find($request->account_id);
        if (($accountInfo->plan->type == Plan::EV_REAL)||($accountInfo->plan->type == Plan::EX_REAL)) {
            try {
                if (($request->exists('scaleUp')) && ($request->scaleUp == 'true')) {
                    $scaleUp = true;
                    $scaleUpAmount = $request->scaleUpAmount;
                } else {
                    $scaleUp = false;
                    $scaleUpAmount = 0;
                }
                $approver = new ApproveAccountService($request->account_id, $request->tra_id, $request->profit, $request->withdrawableAmount, $request->growthFundAmount, $scaleUp, $scaleUpAmount);

                $response = $approver->approveAccount();
                $model = array(
                    'properties' => array('login' => $accountInfo->login, 'message' => "approved from target reached"),
                );
                $model = json_encode($model);
                $model = json_decode($model);
                Auditable::audit("account:approved", $model);

                if($request->withdrawableAmount > 0)
                {
                   $this->EligibleForPayoutOnTargetReachedMail($accountInfo);
                }
                Session::forget('success');
                return ResponseService::apiResponse(200, "Account Confirmation successful for $accountInfo->login");
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return ResponseService::apiResponse(500, "Something went wrong!");
            }
        }
        $get_approval_category = ApprovalCategory::where('name', $request->approval_category)->value('id');

        $planRules = $accountInfo->planRules();

        DB::beginTransaction();
        try {

            if ($get_approval_category == Account::PROFIT_TARGET_REACHED_APPROVAL) {
                $nextPlan = Plan::whereId($accountInfo->plan->next_plan)->first();
                if ($nextPlan && $nextPlan->type == Plan::EX_REAL || $nextPlan->type == Plan::EV_REAL) {
                    $kyc = (new KYCService)->kycCheckForCustomer($accountInfo);
                    if (!$kyc) {
                        DB::rollback();
                        return response()->json(['message' => 'Customer KYC is not approved'], 400);
                    }
                }
            }
            //Account Profit Webhook
            if (isset($planRules['AGF'])) {

                $details = [
                    'amount' => $request->growthFundAmount,
                    'date' => Carbon::now(),
                    'fund_type' => '1',
                    'account_id' => $accountInfo->id,
                    'subscription_id' => $accountInfo->currentSubscription->id,

                ];
                $accountService->addGrowthFund($details);
            }
            $phaseMigration = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('FRONTEND_URL') . "/api/v1/webhook/update-account-profit", [

                "accountId" => $accountInfo->id,
                "profit" => $request->profit,
                "withdrawableAmount" => $request->withdrawableAmount,
                "growthFund" => $request->growthFundAmount,
                "scaleUpFund" => 0,
                "accumulatedProfit" => 0,
            ]);

            $controller = new \App\Http\Controllers\AccountController();
            //Create new account JL Dashboard

            if ($get_approval_category == Account::PROFIT_TARGET_REACHED_APPROVAL) {

                $controller->planMigrate($request->account_id);
            }

            if ($get_approval_category == Account::MONTHEND_PARTIAL_PROFIT_SHARE_APPROVAL) {

                $server = $accountInfo->server;
                $url = $server->url;
                $sessionToken = $server->login;
                $nextPlan =  Plan::find($accountInfo->plan->next_plan);
                $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $sessionToken, [
                    'login' => $accountInfo->login,
                    'read_only' => 0,
                ]);

                Account::where('id', '=', $accountInfo->id)
                    ->update(['breached' => 0, 'breachedby' => null]);
                $subend = Helper::subend_days($accountInfo->duration);
                $subscription = Subscription::create([
                    'account_id' => $accountInfo->id,
                    'login' => $accountInfo->login,
                    'plan_id' => $accountInfo->plan_id,
                    'ending_at' => $subend['string'],
                ]);
                $controller->balanceReset($accountInfo->id);
                $accountInfo->beforeLatestMetric->delete();
                $accountInfo->latestMetric->delete();
            }


            if (isset($request->accountRule)) {

                foreach ($getRuleInfo as $info) {
                    $accRule = AccountRule::updateOrCreate([

                        'account_id' => $request->account_id,
                        'rule_id' => $info->rule_name_id,
                    ], [

                        'value' => $info->default_value,

                    ]);
                }
            }

            $res = TargetReachedAccount::find($request->tra_id);
            $res->update(['approved_at' => Carbon::now()]);
                // auditlog
                $model = array(
                    'properties' => array('login' => $accountInfo->login, 'message' => "approved from target reached"),
                );
                $model = json_encode($model);
                $model = json_decode($model);
                Auditable::audit("account:approved", $model);



                if($res && $request->withdrawableAmount > 0)
                {
                   $this->EligibleForPayoutOnTargetReachedMail($accountInfo);
                }
            //discord Notification
            Helper::discordAlert("**Approval Confirmation**:\nFor: " . $request->approval_category . "\nLogin : " . $accountInfo->login . "\nPlan : " . $accountInfo->plan->title . "\nProfit : " . $request->profit . "\nwithdrawableAmount : " . $request->withdrawableAmount . "\ngrowthFund : " . $request->growthFundAmount);

//            Helper::discordAlert("**" . Customer::TAGS[$accountInfo->customer->tags] . "Customer" . "\n**:**Approval Confirmation**:\nFor: " . $request->approval_category . "\nLogin : " . $accountInfo->login . "\nPlan : " . $accountInfo->plan->title . "\nProfit : " . $request->profit . "\nwithdrawableAmount : " . $request->withdrawableAmount . "\ngrowthFund : " . $request->growthFundAmount,true);

            Redis::del('margin:' . $accountInfo->login);
            DB::commit();
            if ($accountInfo->plan->new_account_on_next_plan != 1) {
                $accountInfo->beforeLatestMetric->delete();
                $accountInfo->latestMetric->delete();
            }



            Session::forget('success');
            return response()->json(['message' => "Account Confirmation successful for $accountInfo->login"], 200);
        } catch (\Exception $e) {
            Log::info("error hoise\n" . $e);
            DB::rollback();
            throw $e;
            return response()->json(['message' => 'Something Went Wrong'], 400);
        }
    }

    public function testTargetReachedAccountId(Request $request)
    {


        $getRuleId = AccountRuleTemplate::whereIn('id', $request->accountRule)->pluck('rule_name_id');
        $checkUserRule = AccountRule::whereAccountId($request->account_id)->whereIn('rule_id', $getRuleId)->get();
        $getRuleInfo = AccountRuleTemplate::whereIn('id', $request->accountRule)->get();


        $accountInfo = Account::with('plan')->find($request->account_id);

        $planRules = $accountInfo->planRules();

        DB::beginTransaction();
        try {

            //Account Profit Webhook
            $phaseMigration = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('FRONTEND_URL') . "/api/v1/webhook/update-account-profit", [

                "accountId" => $accountInfo->id,
                "profit" => $request->profit,
                "withdrawableAmount" => $request->withdrawableAmount,
                "growthFund" => $request->growthFundAmount,
                "scaleUpFund" => 0,
                "accumulatedProfit" => 0,
            ]);



            //Create new account JL Dashboard
            $controller = new \App\Http\Controllers\AccountController();
            $controller->planMigrate($request->account_id);

            if (isset($checkUserRule)) {
                foreach ($getRuleInfo as $info) {
                    $accRule = AccountRule::updateOrCreate([

                        'account_id' => $request->account_id,
                        'rule_id' => $info->rule_name_id,
                    ], [

                        'value' => $info->default_value,

                    ]);
                }
            }



            $res = TargetReachedAccount::where('account_id', $request->account_id)->update(['approved_at' => Carbon::now()]);
            //discord Notification
            Helper::discordAlert("**Target Reached Confirmation**:\nLogin : " . $accountInfo->login . "\nPlan : " . $accountInfo->plan->title . "\nProfit : " . $request->profit . "\nwithdrawableAmount : " . $request->withdrawableAmount . "\ngrowthFund : " . $request->growthFundAmount);

            DB::commit();

            return "yes" . $res;
        } catch (\Exception $e) {
            Log::info("error hoise\n" . $e);
            // DB::rollback();
            throw $e;
            return "no";
        }
    }

    public function denayAccount($id)
    {
        if ($id) {
            $res = TargetReachedAccount::find($id);
            $res->update(['denied_at' => Carbon::now()]);
            return response()->json(['message' => 'Account Deny successfull'], 200);
        }
    }

    public function create()
    {
        abort_if(Gate::denies('target_reached_account_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('account', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.targetReachedAccounts.create', compact('accounts', 'plans', 'subscriptions'));
    }

    public function store(StoreTargetReachedAccountRequest $request)
    {
        $targetReachedAccount = TargetReachedAccount::create($request->all());

        return redirect()->route('admin.target-reached-accounts.index');
    }

    public function edit(TargetReachedAccount $targetReachedAccount)
    {
        abort_if(Gate::denies('target_reached_account_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('account', 'id')->prepend(trans('global.pleaseSelect'), '');

        $targetReachedAccount->load('account', 'plan', 'subscription');

        return view('admin.targetReachedAccounts.edit', compact('accounts', 'plans', 'subscriptions', 'targetReachedAccount'));
    }

    public function update(UpdateTargetReachedAccountRequest $request, TargetReachedAccount $targetReachedAccount)
    {
        $targetReachedAccount->update($request->all());

        return redirect()->route('admin.target-reached-accounts.index');
    }

    public function show(TargetReachedAccount $targetReachedAccount)
    {
        abort_if(Gate::denies('target_reached_account_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $targetReachedAccount->load('account', 'plan', 'subscription');

        return view('admin.targetReachedAccounts.show', compact('targetReachedAccount'));
    }

    public function destroy(TargetReachedAccount $targetReachedAccount)
    {
        abort_if(Gate::denies('target_reached_account_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $targetReachedAccount->delete();

        return back();
    }

    public function massDestroy(MassDestroyTargetReachedAccountRequest $request)
    {
        TargetReachedAccount::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function newsTradeView($id)
    {
        $getInfo = TargetReachedAccount::find($id);
        $rows = json_decode($getInfo->rules_reached);
        $newsTrades = json_decode($rows->news);
        $tradeArray = [];
        $newsArray = [];
        foreach ($newsTrades as $row) {
            $tradeArray[] = $row->trade_id;
            $newsArray[] = $row->news_id;
        }

        $tradeInfo = Trade::whereIn('id', $tradeArray)->get();
        $newsInfo = NewsCalendar::whereIn('id', $newsArray)->get();

        $tradeInfo = $tradeInfo->keyBy('id');
        $newsInfo = $newsInfo->keyBy('id');


        $newsTradeInfo = [];
        $newsInfo = $newsInfo->toArray();
        $tradeInfo = $tradeInfo->toArray();
        foreach ($newsTrades as $row) {

            $newsTradeInfo[] = array_merge($newsInfo[$row->news_id], $tradeInfo[$row->trade_id]);
        }
        $newsTradeInfo = collect($newsTradeInfo);
        return view('admin.news.news-trade', compact('newsTradeInfo'));
    }


    public function EligibleForPayoutOnTargetReachedMail($account)
    {
        if (($account->plan->type == Plan::EX_DEMO)) {

            $details = [
                'template_id'          => EmailConstants::ELIGIBLE_FOR_PAYOUT_ON_TRA_MAIL,
                'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                'to_email'             => $account->customer->email,
                'email_body' => [
                    "name" => Helper::getOnlyCustomerName($account->customer->name),
                    "login_id" => $account->login
                ]
            ];
            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
        } elseif (($account->plan->type == Plan::EX_REAL) || ($account->plan->type == Plan::EV_REAL)) {
            $account = Account::with('customer', 'latestSubscription')->find($account->id);
                $details = [
                    'template_id'          => EmailConstants::ELIGIBLE_FOR_PAYOUT_ON_TRA_MAIL,
                    'to_name'              => Helper::getOnlyCustomerName($account->customer->name),
                    'to_email'             => $account->customer->email,
                    'email_body' => [
                        "name" => Helper::getOnlyCustomerName($account->customer->name),
                        "login_id" => $account->login
                    ]
                ];
            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
        }
    }
}
