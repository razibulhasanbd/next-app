<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAccountMetricRequest;
use App\Http\Requests\StoreAccountMetricRequest;
use App\Http\Requests\UpdateAccountMetricRequest;
use App\Models\Account;
use App\Models\AccountMetric;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AccountMetricsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('account_metric_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountMetric::with('account')->select(sprintf('%s.*', (new AccountMetric())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_metric_show';
                $editGate = 'account_metric_edit';
                $deleteGate = 'account_metric_delete';
                $crudRoutePart = 'account-metrics';

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
                return  $row->account->login ? '<a href="' . route('admin.accounts.show', $row->account->id) . '">' . $row->account->login . '</a>' : '';
            });
            $table->editColumn('maxDailyLoss', function ($row) {
                return $row->maxDailyLoss ? $row->maxDailyLoss : '';
            });

            $table->editColumn('metricDate ', function ($row) {
                return $row->metricDate  ? frontEndTimeConverterView($row->metricDate)  : '';
            });

            $table->editColumn('isActiveTradingDay', function ($row) {
                $status='';
                if($row->isActiveTradingDay == 1){
                    $status= "Yes";
                }else{
                    $status= "No";
                }
                return $status ? $status : '';
            });
            $table->editColumn('trades', function ($row) {
                return $row->trades ? $row->trades : '';
            });
            $table->editColumn('averageLosingTrade', function ($row) {
                return $row->averageLosingTrade ? $row->averageLosingTrade : '';
            });
            $table->editColumn('averageWinningTrade', function ($row) {
                return $row->averageWinningTrade ? $row->averageWinningTrade : '';
            });
            $table->editColumn('lastBalance', function ($row) {
                return $row->lastBalance ? $row->lastBalance : '';
            });
            $table->editColumn('lastEquity', function ($row) {
                return $row->lastEquity ? $row->lastEquity : '';
            });
            $table->editColumn('lastRisk', function ($row) {
                return $row->lastRisk ? $row->lastRisk : '';
            });
            $table->editColumn('maxMonthlyLoss', function ($row) {
                return $row->maxMonthlyLoss ? $row->maxMonthlyLoss : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account.login','maxDailyLoss','metricDate','isActiveTradingDay','trades','averageLosingTrade','averageWinningTrade','lastBalance','lastEquity','lastRisk','maxMonthlyLoss']);

            return $table->make(true);
        }

        return view('admin.accountMetrics.index');
    }

    public function create()
    {
        abort_if(Gate::denies('account_metric_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accounts = Account::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.accountMetrics.create' ,compact('accounts'));
    }

    public function store(StoreAccountMetricRequest $request)
    {
        $accountMetric = AccountMetric::create($request->all());

        return redirect()->route('admin.account-metrics.index');
    }

    public function edit(AccountMetric $accountMetric)
    {
        abort_if(Gate::denies('account_metric_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.accountMetrics.edit', compact('accountMetric'));
    }

    public function update(UpdateAccountMetricRequest $request, AccountMetric $accountMetric)
    {
        $accountMetric->update($request->all());

        return redirect()->route('admin.account-metrics.index');
    }

    public function show(AccountMetric $accountMetric)
    {
        abort_if(Gate::denies('account_metric_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.accountMetrics.show', compact('accountMetric'));
    }

    public function destroy(AccountMetric $accountMetric)
    {
        abort_if(Gate::denies('account_metric_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountMetric->delete();

        return back();
    }

    public function massDestroy(MassDestroyAccountMetricRequest $request)
    {
        AccountMetric::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
