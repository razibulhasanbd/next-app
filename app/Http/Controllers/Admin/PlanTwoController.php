<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPlanRequest;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Plan::query()->select(sprintf('%s.*', (new Plan())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'plan_show';
                $editGate = 'plan_edit';
                $deleteGate = 'plan_delete';
                $crudRoutePart = 'plans';

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
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('upgradePlanId', function ($row) {
                return $row->upgradePlanId ? $row->upgradePlanId : '';
            });
            $table->editColumn('serverGroupName', function ($row) {
                return $row->serverGroupName ? $row->serverGroupName : '';
            });
            $table->editColumn('leverage', function ($row) {
                return $row->leverage ? $row->leverage : '';
            });
            $table->editColumn('accountMaxDrawdown', function ($row) {
                return $row->accountMaxDrawdown ? $row->accountMaxDrawdown : '';
            });
            $table->editColumn('accountProfitTarget', function ($row) {
                return $row->accountProfitTarget ? $row->accountProfitTarget : '';
            });
            $table->editColumn('startingBalance', function ($row) {
                return $row->startingBalance ? $row->startingBalance : '';
            });
            $table->editColumn('dailyLossLimit', function ($row) {
                return $row->dailyLossLimit ? $row->dailyLossLimit : '';
            });
            $table->editColumn('upgradeThreshold', function ($row) {
                return $row->upgradeThreshold ? $row->upgradeThreshold : '';
            });
            $table->editColumn('accumulatedProfit', function ($row) {
                return $row->accumulatedProfit ? $row->accumulatedProfit : '';
            });
            $table->editColumn('profitShare', function ($row) {
                return $row->profitShare ? $row->profitShare : '';
            });
            $table->editColumn('liquidateFriday', function ($row) {
                return $row->liquidateFriday ? $row->liquidateFriday : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.plans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.plans.create');
    }

    public function store(StorePlanRequest $request)
    {
        
        $plan = Plan::create($request->all());

        return redirect()->route('admin.plans.index');
    }

    public function edit(Plan $plan)
    {
        abort_if(Gate::denies('plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.plans.edit', compact('plan'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->all());

        return redirect()->route('admin.plans.index');
    }

    public function show(Plan $plan)
    {
        abort_if(Gate::denies('plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.plans.show', compact('plan'));
    }

    public function destroy(Plan $plan)
    {
        abort_if(Gate::denies('plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plan->delete();

        return back();
    }

    public function massDestroy(MassDestroyPlanRequest $request)
    {
        Plan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
