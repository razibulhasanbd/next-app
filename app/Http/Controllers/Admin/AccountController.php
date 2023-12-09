<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\Country;
use App\Models\Customer;
use App\Models\TopupLog;
use App\Traits\Auditable;
use App\Models\GrowthFund;
use App\Exports\UsersExport;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\AccountMetric;
use App\Models\ArbitraryTrade;
use App\Services\AccountService;
use App\Services\PasswordService;
use App\Services\ResponseService;
use App\Exports\ExpressRealExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvaluationRealExport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreAccountRequest;
use Maatwebsite\Excel\Excel as ExcelExcel;
use App\Http\Requests\UpdateAccountRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAccountRequest;
use PhpParser\Node\Stmt\TryCatch;
use App\Services\TradeService;

class AccountController extends Controller
{
    use CsvImportTrait;
    use Auditable;

    protected $accountService;


    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }


    public function index(Request $request)
    {

        abort_if(Gate::denies('account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Account::with('customer', 'plan', 'latestSubscription','parentAccount')->select(sprintf('%s.*', (new Account())->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_show';
                $editGate = 'account_edit';
                $deleteGate = 'account_delete';
                $crudRoutePart = 'accounts';

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
            $table->editColumn('customer.name', function ($row) {
                return  $row->customer ? '<a href="' . route('admin.customers.show', $row->customer->id) . '">' . $row->customer->name . '</a>' : '';
            });
            $table->editColumn('customer.email', function ($row) {
                if (Gate::allows('account_email_column_hide_searchable')) {
                    return $row->customer ? (is_string($row->customer) ? $row->customer : '*****') : '';
                } else return $row->customer ? (is_string($row->customer) ? $row->customer : $row->customer->email) : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? '<a href=' . route('admin.accounts.show', $row->id) . '>' . $row->login . '</a>' : '';
            });

            $table->editColumn('parent_account_id', function ($row) {

                return $row->parent_account_id ? $row->parentAccount->login  : 'Null';
            });

            $table->editColumn('password', function ($row) {
                return $row->password ? $row->password : '';
            });

            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('plan.title', function ($row) {
                return $row->plan ? (is_string($row->plan) ? $row->plan : $row->plan->title) : '';
            });

            $table->editColumn('balance', function ($row) {
                return $row->balance ? $row->balance : '';
            });
            $table->editColumn('equity', function ($row) {
                return $row->equity ? $row->equity : '';
            });
            $table->editColumn('pnl', function ($row) {
                return $row->equity ? round(($row->equity) - ($row->starting_balance), 6) : 0;
            });

            $table->editColumn('accountLabels', function ($row) {
                $labels = [];
                if($row->accountLabels != null)
                {
                    foreach ($row->accountLabels->labels as $label) {
                        $labels[] = sprintf('<span class="badge badge-info">%s</span>', $label->title);
                    }
                    $labels[] =  '<a href="' . route('admin.create.account-labels-account-id-wise', $row->id) . '" target="_blank">' . '<span class="badge badge-danger">' . 'Add Label' . '</span>' . '</a>';
                    return implode(' ', $labels);
                }
                return '';
            });

            $table->editColumn('breached', function ($row) {
                return $row->breached ? '<b class="text-danger">Yes</b>' : 'No';
            });
            $table->editColumn('breachedby', function ($row) {
                return $row->breachedby ? $row->breachedby : '';
            });
            $table->editColumn('trading_server_type', function ($row) {
                return $row->trading_server_type ? $row->trading_server_type : '';
            });

            $table->editColumn('latestSubscription.ending_at', function ($row) {


                return $row->latestSubscription ? $row->latestSubscription->ending_at : '';
            });

            $table->editColumn('created_at', function ($row) {
                return  $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });



            $table->rawColumns(['actions', 'placeholder', 'plan', 'user', 'customer.name', 'latestSubscription', 'login', 'breached']);

            return $table->make(true);
        }

        return view('admin.accounts.index');
    }
    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
    public function topUpLog(Request $request)
    {
        abort_if(Gate::denies('account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TopupLog::with('account')->select(sprintf('%s.*', (new TopupLog())->table));
            // $query = TopupLog::get();
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_show';
                $editGate = 'account_edit';
                $deleteGate = 'account_delete';
                $crudRoutePart = 'accounts';

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
            $table->editColumn('account.login', function ($row) {
                // $account = Account::find($row->account_id);

                return $row->account->login ? $row->account->login : '';
            });
            $table->editColumn('account.name', function ($row) {
                // $account = Account::find($row->account_id);

                return $row->account->name ? $row->account->name : '';
            });
            $table->editColumn('topup_amount', function ($row) {

                return $row->topup_amount ? $row->topup_amount : '';
            });
            $table->editColumn('created_at', function ($row) {

                return $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });
            $table->editColumn('BreachEquity', function ($row) {

                return $row->breach_metric ? json_decode($row->breach_metric, 1)['lastEquity'] : '';
            });
            $table->editColumn('BreachBalance', function ($row) {

                return $row->breach_metric ? json_decode($row->breach_metric, 1)['lastBalance'] : '';
            });
            $table->editColumn('BreachMaxDailyLoss', function ($row) {

                return $row->breach_metric ? json_decode($row->breach_metric, 1)['maxDailyLoss'] : '';
            });
            $table->editColumn('BreachMaxMonthlyLoss', function ($row) {

                return $row->breach_metric ? json_decode($row->breach_metric, 1)['maxMonthlyLoss'] : '';
            });
            $table->editColumn('LastDayEquity', function ($row) {

                return $row->last_metric ? json_decode($row->last_metric, 1)['lastEquity'] : '';
            });
            $table->editColumn('LastDayBalance', function ($row) {

                return $row->last_metric ? json_decode($row->last_metric, 1)['lastBalance'] : '';
            });

            $table->rawColumns(['actions', 'placeholder','account.name','account.login']);

            return $table->make(true);
        }

        return view('admin.accounts.topup');
    }


    public function accountProfit(Request $request)
    {

        abort_if(Gate::denies('account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = '';

            if (isset($request->min) && isset($request->max)) {
                $query = Account::join('plans', 'plans.id', '=', 'accounts.plan_id')
                    ->whereRaw('((accounts.balance / accounts.starting_balance) - 1) * 100 >= ?', [$request->min])
                    ->whereRaw('((accounts.balance / accounts.starting_balance) - 1) * 100 <= ?', [$request->max])
                    ->select(sprintf('%s.*', (new Account())->table))
                    ->with('customer', 'plan');
            } else {
                $query = Account::with('customer', 'plan')->select(sprintf('%s.*', (new Account())->table));
            }

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');


            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('customer.name', function ($row) {
                return $row->customer ? (is_string($row->customer) ? $row->customer : $row->customer->name) : '';
            });
            $table->editColumn('login', function ($row) {
                return  $row->login ? '<a href=' . route('admin.accounts.show', $row->id) . '>' . $row->login . '</a>' : '';
                // return $row->login ? $row->login : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('plan.title', function ($row) {
                return $row->plan ? (is_string($row->plan) ? $row->plan : $row->plan->title) : '';
            });
            $table->editColumn('balance', function ($row) {
                return $row->balance ? $row->balance : '';
            });
            $table->editColumn('equity', function ($row) {
                return $row->equity ? $row->equity : '';
            });

            // Account profit
            $table->editColumn('profit', function ($row) {


                $accountDetails = Account::with('plan')->whereId($row->id)->get();
                $startingBalance = $accountDetails[0]->starting_balance;
                $currentBalance = $accountDetails[0]->balance;
                $profitPercentage = ((($currentBalance / $startingBalance) - 1) * 100);
                return round($profitPercentage, 2) ?: 0;
            });
            $table->rawColumns(['placeholder', 'plan', 'user', 'login', 'password', 'type', 'plan.title', 'balance', 'equity', 'profit']);


            return $table->make(true);
        }
        return view('admin.accounts.accountProfit');
    }

    public function accountsByDateRange(Request $request)
    {

        abort_if(Gate::denies('account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = Account::with('customer', 'plan', 'latestSubscription','parentAccount')->whereBetween('accounts.created_at', [$request->startDate, $request->endDate])->select(sprintf('%s.*', (new Account())->table));

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_show';
                $editGate = 'account_edit';
                $deleteGate = 'account_delete';
                $crudRoutePart = 'accounts';

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
            $table->editColumn('customer.name', function ($row) {
                return  $row->customer ? '<a href="' . route('admin.customers.show', $row->customer->id) . '">' . $row->customer->name . '</a>' : '';
            });
            $table->editColumn('customer.email', function ($row) {
                if (Gate::allows('account_email_column_hide_searchable')) {
                    return $row->customer ? (is_string($row->customer) ? $row->customer : '*****') : '';
                } else return $row->customer ? (is_string($row->customer) ? $row->customer : $row->customer->email) : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? '<a href=' . route('admin.accounts.show', $row->id) . '>' . $row->login . '</a>' : '';
            });

            $table->editColumn('parent_account_id', function ($row) {

                return $row->parent_account_id ? $row->parentAccount->login  : 'Null';
            });

            $table->editColumn('password', function ($row) {
                return $row->password ? $row->password : '';
            });

            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('plan.title', function ($row) {
                return $row->plan ? (is_string($row->plan) ? $row->plan : $row->plan->title) : '';
            });

            $table->editColumn('balance', function ($row) {
                return $row->balance ? $row->balance : '';
            });
            $table->editColumn('equity', function ($row) {
                return $row->equity ? $row->equity : '';
            });
            $table->editColumn('pnl', function ($row) {
                return $row->equity ? round(($row->equity) - ($row->starting_balance), 6) : '';
            });

            $table->editColumn('breached', function ($row) {
                return $row->breached ? '<b class="text-danger">Yes</b>' : 'No';
            });
            $table->editColumn('breachedby', function ($row) {
                return $row->breachedby ? $row->breachedby : '';
            });
            $table->editColumn('trading_server_type', function ($row) {
                return $row->trading_server_type ? $row->trading_server_type : '';
            });

            $table->editColumn('latestSubscription', function ($row) {
                return $row->latestSubscription ? frontEndTimeConverterView($row->latestSubscription->ending_at) : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });



            $table->rawColumns(['actions', 'placeholder', 'plan', 'user', 'customer.name','customer.email', 'latestSubscription', 'login', 'breached']);

            return $table->make(true);
        }

        return view('admin.accounts.accountsByDateRange');
    }


    public function create()
    {
        abort_if(Gate::denies('account_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $plans = Plan::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.accounts.create', compact('customers', 'plans'));
    }

    public function store(StoreAccountRequest $request)
    {
        $account = Account::create($request->all());

        return redirect()->route('admin.accounts.index');
    }

    public function edit(Account $account)
    {
        abort_if(Gate::denies('account_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.accounts.edit', compact('account'));
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {

        $account->update($request->all());

        return redirect()->route('admin.accounts.index');
    }

    public function show(Account $account)
    {

        abort_if(Gate::denies('account_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $controller = new \App\Http\Controllers\AccountController();
        $status = $controller->accountReport($account->id);

        $userPlanRules = $controller->accountPlanRules($account->id);
        $cardDetails = json_decode($controller->endpoint($account->id)->content(), 1);

        $controllerForConsistency = new \App\Http\Controllers\TradeController();
        $consistencyRule = $controllerForConsistency->consistencyRule($account->id);


        $openTrades = $account->thisCycleTrades()->where('close_time', '=', 0)->count();
        $closeTrades = $account->thisCycleTrades()->where('close_time', '!=', 0)->count();
        $arbitraryTrades = ArbitraryTrade::where('account_id', $account->id)->count();

        $account->load('subscriptions', 'growthFund');

        $planRules = $account->planRules();
        $cardData = [
            'minimumTradingDays' => isset($planRules['MTD']) ? $planRules['MTD']['value'] : '0',
            'isActiveTradingDay' => $cardDetails['activeTradingDay'],
            'maxDailyLoss' => $cardDetails['maxDailyLoss'],
            'maxDailyLossLimit' => ($account->starting_balance * $planRules['DLL']['value']) / 100,
            'maxMonthlyLoss' => $cardDetails['maxMonthlyLoss'],
            'maxMonthlyLossLimit' => ($account->starting_balance * $planRules['MLL']['value']) / 100,
            'profitTarget' => isset($cardDetails['profitTarget']) ? $cardDetails['profitTarget'] : '0',
            'profitTargetReached' =>  $cardDetails['profitTargetReached'],
            'lots' => $cardDetails['lots'],
            'dailyLossThreshold' => $cardDetails['dailyLossThreshold'] ? $cardDetails['dailyLossThreshold'] : '0',
            'maxLossThreshold' => $cardDetails['maxLossThreshold'] ? $cardDetails['maxLossThreshold'] : '0',

        ];
        // Log::info("before view");
        // Log::info(json_encode($status));
        return view('admin.accounts.show', compact('account', 'status', 'consistencyRule', 'userPlanRules', 'cardData', 'openTrades', 'closeTrades', 'arbitraryTrades'));
    }

    public function destroy(Account $account)
    {
        abort_if(Gate::denies('account_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $account->delete();

        return back();
    }

    public function massDestroy(MassDestroyAccountRequest $request)
    {
        Account::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getAccountMetrics($id)
    {
        if ($id) {
            $getAccountMetrics = AccountMetric::whereAccountId($id)->orderBy('id', 'desc')->get();
            return json_encode(array('data' => $getAccountMetrics));
        }
    }

    public function getAccountIdForOnOf($id)
    {
        if ($id) {
            $account = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $account = Account::find($account);
            if (strpos($id, 'open') !== false) {

                if ($account == null) {

                    return response()->json(['message' => "This account doesn't exist"], 404);
                }
                $server = $account->server;

                $updateAccount = Http::acceptJson()->post($server->url . "/user/update?token=" . $server->login, [
                    'login' => $account->login,
                    'read_only' => 0,

                ]);
                if (json_decode($updateAccount, 1)['code'] == 200) {
                    $model = array(
                        'properties' => array('login' => $account->login, 'status' => "Open"),
                    );
                    $model = json_encode($model);
                    $model = json_decode($model);
                    Auditable::audit("account:ON", $model);
                    $account->update(['breached' => "0", 'breachedby' => null]);
                    return response()->json(['message' => 'Account Open successfull'], 200);
                }
            }
            if (strpos($id, 'close') !== false) {

                if ($account == null) {
                    return response()->json(['message' => "This account doesn't exist"], 404);
                }

                $server = $account->server;

                $updateAccount = Http::acceptJson()->post($server->url . "/user/update?token=" . $server->login, [
                    'login' => $account->login,
                    'read_only' => 1,

                ]);
                if (json_decode($updateAccount, 1)['code'] == 200) {
                    $model = array(
                        'properties' => array('login' => $account->login, 'status' => "Close"),
                    );
                    $model = json_encode($model);
                    $model = json_decode($model);
                    Auditable::audit("account:OFF", $model);
                    $account->update(['breached' => "1", 'breachedby' => "Admin"]);
                    return response()->json(['message' => 'Account Close successfull'], 200);
                }
            }
        }
    }

    public function getAccountTrades($id)
    {
        if ($id) {
            $account = getAuthenticateAccount($id);
            // $getAccountTrades = Trade::whereAccountId($id)->get();
            $getAccountTrades = $account->thisCycleTrades->sortByDesc('id');
            foreach ($getAccountTrades as $trade) {
                if ($trade->close_time == 0) {
                    $pl = Redis::get('orderpl:' . $trade->ticket);
                    if ($pl == null) {
                        $pl = $trade->profit . "(OLD)";
                    }

                    $trade->profit = $pl;
                    $trade->close_time_str = "Currently Running";
                }

                $trade->sl = round($trade->sl, 4);
                $trade->tp = round($trade->tp, 4);
                $trade->open_price = round((float)$trade->open_price, 4);
                $trade->profit = round((float) $trade->profit, 4);
                $trade->close_price = round((float)$trade->close_price, 4);
                $trade->commission = round($trade->commission, 4);
                $trade->lots = ($trade->volume / 100);
                $liveTrades[] = $trade;
            }

            // return json_encode(array('data' => isset($liveTrades) ? Helper::paginate($liveTrades) : ["error" => "No Trades available"]));

            return ((isset($liveTrades) ? Helper::paginate($liveTrades) : ["error" => "No Trades available"]));
        }
    }

    // for pagination Account Trade
    public function getAccountTradesPagination(Request $request)
    {

        if (isset($request->id)) {
            $account = Account::find($request->id);
            $getAccountTrades = $account->thisCycleTrades->sortByDesc('id');
            foreach ($getAccountTrades as $trade) {

                if ($trade->close_time == 0) {
                    $pl = Redis::get('orderpl:' . $trade->ticket);
                    if ($pl == null) {
                        $pl = $trade->profit . "(OLD)";
                    }

                    $trade->profit = $pl;
                    $trade->close_time_str = "Currently Running";
                }

                $trade->sl = round($trade->sl, 4);
                $trade->tp = round($trade->tp, 4);
                $trade->open_price = round($trade->open_price, 4);
                $trade->profit = round((float) $trade->profit, 4);
                $trade->close_price = round($trade->close_price, 4);
                $trade->commission = round($trade->commission, 4);
                $trade->lots = ($trade->volume / 100);
                $liveTrades[] = $trade;
            }

            if (isset($liveTrades)) {
                $makePaginator = Helper::getTradePage($liveTrades, 30, $request->pageNo)->toArray();
                $view = view('admin.accounts.partialTable', compact('makePaginator'))->render();
                return response(['status' => 200, 'view' => $view]);
            } else {
                return response(['status' => 200, 'view' => "No Trade Available"]);
            }
        }
    }
    // for pagination end

    public function  getAccountGrowthStatus(Request $request, $id)
    {

        if ($id) {
            if ($request->start_date && $request->end_date) {
                $getAccountGrowth = AccountMetric::select("metricDate", "lastBalance", "lastEquity")->whereAccountId($id)->whereBetween('metricDate', [$request->start_date, $request->end_date])->get();
                return $getAccountGrowth;
            } else {
                $getAccountGrowth = AccountMetric::select("metricDate", "lastBalance", "lastEquity")->whereAccountId($id)->get();
                return $getAccountGrowth;
            }
            //Helper::paginate($getAccountGrowth);
        } else {
            return "No Data Found";
        }
    }

    public function checkTopup(Request $request)
    {

        if (strpos($request->login_id, '/topup') !== false) {
            $loginId = strstr($request->login_id, '/', true);
            try {
                $account = Account::with('plan')->whereLogin($loginId)->first();
                if ($account == null) {
                    Session::flash('alert-danger', 'Error: Account not found! Check login');
                    return redirect()->back();
                }
                $closeTrades = $account->closeRunningTrades();
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
                return response()->json(['message' => $e], 404);
            }
            Cache::forget($account->id . ':firstTrade');
            Helper::discordAlert("**Account Topup**:\nAccntID : " . $account->id . "\nTopup Amount : " . $accountStartingBalance - $currentBalance . "\nTopup Log ID : " . $saveTopupLog->id);
            Helper::discordAlert("**" . Customer::TAGS[$account->customer->tags] . "Customer" . " **\n**Account Topup**:\nAccntID : " . $account->id . "\nTopup Amount : " . $accountStartingBalance - $currentBalance . "\nTopup Log ID : " . $saveTopupLog->id,true);

            Session::flash('alert-success', 'Account Topup Successfull by $' . ($accountStartingBalance - $currentBalance));
            return redirect()->back();
        } else {

            Session::flash('alert-warning', 'Please Enter your valid account!');
            return redirect()->back();
        }
    }
    public function disableTrading($id)
    {

        $account = Account::find($id);

        $server = $account->server;

        $updateAccount = Http::acceptJson()->post($server->url . "/user/update?token=" . $server->login, [
            'login' => $account->login,
            'read_only' => 1,

        ]);
        if (json_decode($updateAccount, 1)['code'] == 200) {
            return response()->json(['message' => 'Account Trading Disabled'], 200);
        }
    }

    public function accountSettings($id)
    {
        $account = Account::with('plan', 'latestMetric')->find($id);
        $details = [
            'server' => $account->server,
            'url' => $account->server->url,
            'sessionToken' => $account->server->login,
            'login' => $account->login,
        ];
        // $getAllGroups = $this->accountService->getAllGroups($details);

        // $userGroupName = $this->accountService->userReport($details);

        return view('admin.accounts.settings', compact('account'));

        //return view('admin.accounts.settings', compact('account', 'getAllGroups', 'userGroupName'));
    }

    public function balanceDeposit(Request $request)
    {

        $account = Account::with('plan', 'plan.server')->whereLogin($request->login)->first();
        $details = [
            'server' => $account->server,
            'url' => $account->server->url,
            'sessionToken' => $account->server->login,
            'login' => $account->login,
            'amount' => (float)$request->depositeAmount,
        ];

        $model = array(
            'properties' => array('login' => $account->login, 'amount' => (float)$request->depositeAmount),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("settings:deposit", $model);

        return  $this->accountService->deposit($account, (float)$request->depositeAmount);
    }

    public function balanceWithdraw(Request $request)
    {
        $account = Account::with('plan', 'plan.server')->whereLogin($request->login)->first();
        $details = [
            'server' => $account->server,
            'url' => $account->server->url,
            'sessionToken' => $account->server->login,
            'login' => $account->login,
            'amount' => (float)$request->amount,
        ];

        $model = array(
            'properties' => array('login' => $account->login, 'amount' => (float)$request->amount),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("settings:withdraw", $model);

        return $this->accountService->withdraw($account, (float)$request->amount);
    }

    public function groupChange(Request $request)
    {
        $account = Account::with('plan')->whereLogin($request->login)->first();
        $details = [
            'server' => $account->server,
            'url' => $account->server->url,
            'sessionToken' => $account->server->login,
            'login' => $account->login,
            'group' => $request->getGroups,
        ];

        $model = array(
            'properties' => array('login' => $account->login, 'amount' => (float)$request->depositeAmount),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("settings:groupChange", $model);

        return $this->accountService->updateGroup($details);
    }

    public function resetMetric(Request $request)
    {

        $account = Account::with(['plan', 'plan.server'])->whereLogin($request->login)->first();


        $margin = $this->accountService->margin($account);
        $this->accountService->createYesterdayMetric($account);
        $this->accountService->createTodayMetric($account, $margin);
        //$this->accountService->accountOn($details);

        $model = array(
            'properties' => array('login' => $account->login, 'amount' => (float)$request->depositeAmount),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("settings:resetMetrics", $model);

        return response()->json(['message' => 'Reset Loss Metric'], 200);
    }


    public function enableTrading($id)
    {

        $account = Account::find($id);

        $server = $account->server;

        $updateAccount = Http::acceptJson()->post($server->url . "/user/update?token=" . $server->login, [
            'login' => $account->login,
            'read_only' => 0,

        ]);
        if (json_decode($updateAccount, 1)['code'] == 200) {
            return response()->json(['message' => 'Account Trading Enabled'], 200);
        }
    }

    public function commentUpdate(Request $request)
    {
        $account = Account::where('id', $request->accountId)->update(['comment' => $request->comment]);
        return redirect()->back();
    }

    public function downloadExpressReal()
    {
        return Excel::download(new ExpressRealExport, 'users-ex-real.csv');
    }

    public function downloadEvReal()
    {
        return Excel::download(new EvaluationRealExport, 'users-ev-real.csv');
    }

    public function getAccountPassword(Request $request, PasswordService $passwordService)
    {
        try {
            $account = Account::find($request->account_id);
            if (!$account) {
                return  ResponseService::apiResponse(404, 'Account not found');
            }

            if (!$account->password) {
                $passwordService->changeAccountPassword($account);
            }

            return  ResponseService::apiResponse(200, 'Account password retrieved',
            [
                'account_password' => $passwordService->getAccountPassword($account)
            ]);

        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }

    public function setAccountPassword(Request $request, PasswordService $passwordService)
    {
        try {
            $account = Account::find($request->account_id);
            if (!$account) {
                return  ResponseService::apiResponse(404, 'Account not found');
            }
            $passwordService->changeAccountPassword($account);
            return  ResponseService::apiResponse(200, 'Successfully changed account password',['account_password' => $passwordService->getAccountPassword($account)]);
        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }

    public function forceMigrationToNextPhase(Request $request)
    {
        try{
            $parent=Account::where('parent_account_id',$request->accountId)->count();
            if($parent){
                return  ResponseService::apiResponse(422, 'This account login already in parent');
            }
            $newAccountResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('JL_BACKEND') . "/api/v1/webhook/account-phase-migration", [
                "accountId" => $request->accountId,
                "phaseId" => null,
                "createNewAccount" => null
            ]);

            $model = array(
                'properties' => array('login' => '',  'type' => "Next phase account(force migration)"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("accountMigration:forcely created", $model);
            $newAccountResponse=json_decode($newAccountResponse);
            if($newAccountResponse[0]->original->status == 'ERROR'){
                return  ResponseService::apiResponse(422, 'Account forcely migration failed');
            }else if($newAccountResponse[0]->original->status == 'SUCCESS'){
                return  ResponseService::apiResponse(200, 'Account forcely migration successful');
           }else{
                return  ResponseService::apiResponse(500, 'Something went wrong!');
           }
        }
        catch(Exception $exception){
            Log::error($exception);
            return ResponseService::apiResponse(500, 'Internal server error!');
        }


    }



    public function breachedAccountAllTradeClose($id)
    {
        try {
            $account = Account::find($id);
            if (!$account || $account->breached == 0 ) {
                return back()->with('success', 'account not found');
            }
            $tradeService = new TradeService;
            $response     = $tradeService->bulkTradeClose($account);
            Log::alert("force trade close for account: ",[$account->id, $response]);
            return back()->with('success', 'all trade closed');
        } catch (Exception $exception) {
            Log::error($exception);
            return back()->with('success', 'something went wrong!');
        }
    }
}
