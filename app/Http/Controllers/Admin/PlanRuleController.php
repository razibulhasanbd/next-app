<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPlanRuleRequest;
use App\Http\Requests\StorePlanRuleRequest;
use App\Http\Requests\UpdatePlanRuleRequest;
use App\Models\Plan;
use App\Models\PlanRule;
use App\Models\RuleName;
use Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PlanRuleController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('plan_rule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PlanRule::with(['ruleName', 'plan'])->select(sprintf('%s.*', (new PlanRule())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'plan_rule_show';
                $editGate = 'plan_rule_edit';
                $deleteGate = 'plan_rule_delete';
                $crudRoutePart = 'plan-rules';

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
            $table->addColumn('ruleName', function ($row) {
                return $row->ruleName ? $row->ruleName->name : '';
            });

            $table->addColumn('plan', function ($row) {
                return $row->plan ? $row->plan->title : '';
                //return $row->plan ? $row->plan->description.'-'.$row->starting_balance : '';
            });

            $table->addColumn('startingBalance', function ($row) {
                return $row->plan ? $row->plan->startingBalance : '';
                
            });

            $table->editColumn('value', function ($row) {
                return $row->value ? $row->value : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ruleName', 'plan','startingBalance']);

            return $table->make(true);
        }

        $rule_names = RuleName::get();
        $plans      = Plan::get();

        return view('admin.planRules.index', compact('rule_names', 'plans'));
    }

    public function create()
    {
        abort_if(Gate::denies('plan_rule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rule_names = RuleName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.planRules.create', compact('plans', 'rule_names'));
    }

    public function store(Request $request)
    {
        $planRule = PlanRule::create($request->all());
        Cache::forget('plans/'.$request->plan_id . ':plan_rules');
        return redirect()->route('admin.plan-rules.index');
    }

    public function edit(PlanRule $planRule)
    {
        abort_if(Gate::denies('plan_rule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rule_names = RuleName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $planRule->load('ruleName', 'plan');

        return view('admin.planRules.edit', compact('planRule', 'plans', 'rule_names'));
    }

    public function update(Request $request, PlanRule $planRule)
    {
        $planRule->update($request->all());
        Cache::forget('plans/'.$planRule->plan_id . ':plan_rules');
        return redirect()->route('admin.plan-rules.index');
    }

    public function show(PlanRule $planRule)
    {
        abort_if(Gate::denies('plan_rule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $planRule->load('ruleName', 'plan');

        return view('admin.planRules.show', compact('planRule'));
    }

    public function destroy(PlanRule $planRule)
    {
        abort_if(Gate::denies('plan_rule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $planRule->delete();
        Cache::forget('plans/'.$planRule->plan_id . ':plan_rules');
        return back();
    }

    public function massDestroy(Request $request)
    {
        PlanRule::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
