<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Account;
use App\Models\GrowthFund;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreGrowthFundRequest;
use App\Http\Requests\UpdateGrowthFundRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyGrowthFundRequest;

class GrowthFundController extends Controller
{
    public function index(Request $request)
    {


        abort_if(Gate::denies('growth_fund_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GrowthFund::with(['account', 'subscription'])->select(sprintf('%s.*', (new GrowthFund())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'growth_fund_show';
                $editGate = 'growth_fund_edit';
                $deleteGate = 'growth_fund_delete';
                $crudRoutePart = 'growth-funds';

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
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });

            $table->addColumn('account_login', function ($row) {
                return $row->account ? $row->account->login : '';
            });

            $table->editColumn('account.login', function ($row) {
                return $row->account ? (is_string($row->account) ? $row->account : $row->account->login) : '';
            });
            $table->addColumn('subscription_ending_at', function ($row) {
                return $row->subscription ? frontEndTimeConverterView($row->subscription->ending_at) : '';
            });

            $table->addColumn('date', function ($row) {
                return $row->date ? frontEndTimeConverterView($row->date) : '';
            });

            $table->editColumn('fund_type', function ($row) {
                return $row->fund_type ? $row->fund_type : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account', 'subscription']);

            return $table->make(true);
        }

        return view('admin.growthFunds.index');
    }

    public function create()
    {
        abort_if(Gate::denies('growth_fund_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('ending_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.growthFunds.create', compact('accounts', 'subscriptions'));
    }

    public function store(StoreGrowthFundRequest $request)
    {
        $growthFund = GrowthFund::create($request->all());

        return redirect()->route('admin.growth-funds.index');
    }

    public function edit(GrowthFund $growthFund)
    {
        abort_if(Gate::denies('growth_fund_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('ending_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        $growthFund->load('account', 'subscription');

        return view('admin.growthFunds.edit', compact('accounts', 'growthFund', 'subscriptions'));
    }

    public function update(UpdateGrowthFundRequest $request, GrowthFund $growthFund)
    {
        $growthFund->update($request->all());

        return redirect()->route('admin.growth-funds.index');
    }

    public function show(GrowthFund $growthFund)
    {
        abort_if(Gate::denies('growth_fund_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $growthFund->load('account', 'subscription');

        return view('admin.growthFunds.show', compact('growthFund'));
    }

    public function destroy(GrowthFund $growthFund)
    {
        abort_if(Gate::denies('growth_fund_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $growthFund->delete();

        return back();
    }

    public function massDestroy(MassDestroyGrowthFundRequest $request)
    {
        GrowthFund::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
