<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\ExtendCycleLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreExtendCycleLogRequest;
use App\Http\Requests\UpdateExtendCycleLogRequest;
use App\Http\Requests\MassDestroyExtendCycleLogRequest;

class ExtendCycleLogController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('extend_cycle_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ExtendCycleLog::select(sprintf('%s.*', (new ExtendCycleLog())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'extend_cycle_log_show';
                $editGate = 'extend_cycle_log_edit';
                $deleteGate = 'extend_cycle_log_delete';
                $crudRoutePart = 'extend-cycle-logs';

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
            $table->addColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });

            $table->addColumn('subcription_id', function ($row) {
                return $row->subcription_id ? $row->subcription_id : '';
            });

            $table->editColumn('weeks', function ($row) {
                return $row->weeks ? $row->weeks : '';
            });
            $table->editColumn('before_subscription', function ($row) {
                return $row->before_subscription ? frontEndTimeConverterView($row->before_subscription) : '';
            });
            $table->editColumn('after_subscription', function ($row) {
                return $row->after_subscription ? frontEndTimeConverterView($row->after_subscription) : '';
            });

            $table->addColumn('account_id', function ($row) {
                return $row->account_id ? $row->account_id : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.extendCycleLogs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('extend_cycle_log_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logins = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subcriptions = Subscription::pluck('account_id', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.extendCycleLogs.create', compact('accounts', 'logins', 'subcriptions'));
    }

    public function store(StoreExtendCycleLogRequest $request)
    {
        $extendCycleLog = ExtendCycleLog::create($request->all());

        return redirect()->route('admin.extend-cycle-logs.index');
    }

    public function edit(ExtendCycleLog $extendCycleLog)
    {
        abort_if(Gate::denies('extend_cycle_log_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logins = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subcriptions = Subscription::pluck('account', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $extendCycleLog->load('login', 'subcription', 'account');

        return view('admin.extendCycleLogs.edit', compact('accounts', 'extendCycleLog', 'logins', 'subcriptions'));
    }

    public function update(UpdateExtendCycleLogRequest $request, ExtendCycleLog $extendCycleLog)
    {
        $extendCycleLog->update($request->all());

        return redirect()->route('admin.extend-cycle-logs.index');
    }

    public function show(ExtendCycleLog $extendCycleLog)
    {
        abort_if(Gate::denies('extend_cycle_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $extendCycleLog->load('login', 'subcription', 'account');

        return view('admin.extendCycleLogs.show', compact('extendCycleLog'));
    }

    public function destroy(ExtendCycleLog $extendCycleLog)
    {
        abort_if(Gate::denies('extend_cycle_log_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $extendCycleLog->delete();

        return back();
    }

    public function massDestroy(MassDestroyExtendCycleLogRequest $request)
    {
        ExtendCycleLog::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getAllExtendCycle(Request $request)
    {
        abort_if(Gate::denies('account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Account::with('customer', 'plan', 'latestSubscription')->select(sprintf('%s.*', (new Account())->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('customer.name', function ($row) {
                return $row->customer ? '<a href="' . route('admin.customers.show', $row->customer->id) . '">' . $row->customer->name . '</a>' : '';
            });
            $table->editColumn('customer.email', function ($row) {
                return $row->customer ? (is_string($row->customer) ? $row->customer : $row->customer->email) : '';
            });

            $table->editColumn('latestSubscription.ending_at', function ($row) {
                return $row->latestSubscription ? frontEndTimeConverterView($row->latestSubscription->ending_at) : '';
            });

            $table->editColumn('latestSubscription.created_at', function ($row) {
                return $row->latestSubscription ? frontEndTimeConverterView($row->latestSubscription->created_at) : '';
            });

            $table->rawColumns(['placeholder', 'type', 'plan', 'user', 'customer.name', 'latestSubscription.ending_at', 'latestSubscription.created_at', 'login', 'breached']);
            return $table->make(true);
        }

        return view('admin.extendCycle.index');
    }

    public function viewExtendCycle($id)
    {
        if (isset($id)) {
            $account = Account::with('latestSubscription', 'customer')->find($id);
            return view('admin.extendCycle.show', compact('account'));

        } else {
            abort(400);
        }

    }

    public function checkExtendCycle(Request $request)
    {
        if (isset($request->week) && isset($request->accountId)) {
            $account = Account::with('latestSubscription', 'customer')->find($request->accountId);
            $weekRequest = $request->week;
            $ending_at = Carbon::createFromTimeStamp(strtotime("friday 11:59 PM $weekRequest week", strtotime($account->latestSubscription->ending_at)))->format('Y-m-d H:i:s');
            $view = view('admin.extendCycle.partialTable', compact('account', 'ending_at', 'weekRequest'))->render();
            return response(['status' => 200, 'view' => $view]);
        } else {
            abort(400);
        }
    }

    public function updateExtendCycle($id, $date, $week)
    {
        if (isset($id) && isset($date)) {
            $getSubscription = Subscription::find($id);
            $inputs = [
                'weeks' => $week,
                'before_subscription' => $getSubscription->ending_at,
                'after_subscription' => $date,
                'login' => $getSubscription->login,
                'subcription_id' => $id,
                'account_id' => $getSubscription->account_id,
            ];
            DB::beginTransaction();
            try {
                ExtendCycleLog::create($inputs);
                $getSubscription->update(['ending_at' => $date]);

                  Helper::discordAlert("**Cycle Extended**:\nAccntID : " . $inputs['account_id'] . "\nLogin : " . $inputs['login'] . "\nWeeks : " . $week . "\nEndDate : " . $date);
                DB::commit();
                return Redirect::back()->with('success', 'Cycle Extension updated Successfully!');
            } catch (\Exception$e) {
                DB::rollback();
                throw $e;
                return "no";
            }
        } else {
            abort(400);
        }

    }

    public function checkCycleExtension(int $id)
    {

        $account = Account::findOrFail($id);
        $subsEnd = Carbon::parse($account->latestSubscription->ending_at)->format('Y-m-d');
        $subsEndOne = Carbon::createFromTimeStamp(strtotime($subsEnd . "-1 day"))->format('Y-m-d');
        $subsEndTwo = Carbon::createFromTimeStamp(strtotime($subsEndOne . "-1 day"))->format('Y-m-d');
        $vars = array($subsEnd, $subsEndOne, $subsEndTwo);
        $runningTrades = Redis::smembers('orders:' . $account->login . ':working');

        if ($account->plan->type != Account::EV_P1) {
            return ([
                'account_id' => $account->id,
                'cycleExtension_eligible' => false,
                'reason' => 'not EV P1 plan',
            ]);
        }

        if (!empty($runningTrades)) {
            return ([
                'account_id' => $account->id,
                'cycleExtension_eligible' => false,
                'reason' => 'Account Have Running Trade',
            ]);

        }

        if (!in_array(Carbon::today()->format('Y-m-d'), $vars)) {
            return ([
                'account_id' => $account->id,
                'cycleExtension_eligible' => false,
                'reason' => 'Not In Same Day',
            ]);
        }
        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        if ($redisData != null) {
            $currentBalance = $redisData['balance'];
            $profitThreeshold = $account->starting_balance * (5 / 100);
            $currentProfit = $currentBalance - $account->starting_balance;

            if ($currentProfit < $profitThreeshold) {
                return ([
                    'account_id' => $account->id,
                    'cycleExtension_eligible' => false,
                    'reason' => 'Account profit not in 5%',
                ]);
            }
        }

        return ([
            'account_id' => $account->id,
            'cycleExtension_eligible' => true,
            'reason' => 'You are eligible to request for a Cycle Extension!',
        ]);

    }

    public function extendCycle(Request $request)
    {
        $checkCycleExtension = $this->checkCycleExtension($request->accountId);
        if ($checkCycleExtension['cycleExtension_eligible'] == true) {
            if (isset($request->accountId)) {
                $account = Account::with('latestSubscription')->find($request->accountId);
                $getSubscription = $account->latestSubscription;
                $weekRequest = 2;
                $ending_at = Carbon::createFromTimeStamp(strtotime("friday 11:59 PM $weekRequest week", strtotime($account->latestSubscription->ending_at)))->format('Y-m-d H:i:s');
                $inputs = [
                    'weeks' => $weekRequest,
                    'before_subscription' => $getSubscription->ending_at,
                    'after_subscription' => $ending_at,
                    'login' => $getSubscription->login,
                    'subcription_id' => $getSubscription->id,
                    'account_id' => $getSubscription->account_id,
                ];
                DB::beginTransaction();
                try {
                    ExtendCycleLog::create($inputs);
                    $getSubscription->update(['ending_at' => $ending_at]);

                    //  Helper::discordAlert("**Cycle Extended**:\nAccntID : " . $getSubscription->account_id . "\nLogin : " . $getSubscription->login . "\nWeeks : " . $week . "\nEndDate : " . $date);
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Your Trading Cycle Successfully Extended Upto ' . $ending_at,
                    ]);
                } catch (\Exception$e) {
                    DB::rollback();
                    throw $e;
                    return "no";
                }
            } else {
                abort(400);
            }
        } else {
            return ([
                $checkCycleExtension,
            ]);
        }

    }

}
