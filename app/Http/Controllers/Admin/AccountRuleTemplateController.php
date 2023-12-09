<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAccountRuleTemplateRequest;
use App\Http\Requests\StoreAccountRuleTemplateRequest;
use App\Http\Requests\UpdateAccountRuleTemplateRequest;
use App\Models\AccountRuleTemplate;
use App\Models\Plan;
use App\Models\RuleName;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AccountRuleTemplateController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('account_rule_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountRuleTemplate::with(['rule_name', 'plan'])->select(sprintf('%s.*', (new AccountRuleTemplate())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_rule_template_show';
                $editGate = 'account_rule_template_edit';
                $deleteGate = 'account_rule_template_delete';
                $crudRoutePart = 'account-rule-templates';

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
            $table->addColumn('rule_name_name', function ($row) {
                return $row->rule_name ? $row->rule_name->name : '';
            });

            $table->addColumn('plan_description', function ($row) {
                return $row->plan ? $row->plan->title : '';
            });

            $table->editColumn('default_value', function ($row) {
                return $row->default_value ? $row->default_value : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'rule_name', 'plan']);

            return $table->make(true);
        }

        return view('admin.accountRuleTemplates.index');
    }

    public function create()
    {
        abort_if(Gate::denies('account_rule_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rule_names = RuleName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.accountRuleTemplates.create', compact('plans', 'rule_names'));
    }

    public function store(StoreAccountRuleTemplateRequest $request)
    {
        $accountRuleTemplate = AccountRuleTemplate::create($request->all());

        return redirect()->route('admin.account-rule-templates.index');
    }

    public function edit(AccountRuleTemplate $accountRuleTemplate)
    {
        abort_if(Gate::denies('account_rule_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rule_names = RuleName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('description', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accountRuleTemplate->load('rule_name', 'plan');

        return view('admin.accountRuleTemplates.edit', compact('accountRuleTemplate', 'plans', 'rule_names'));
    }

    public function update(UpdateAccountRuleTemplateRequest $request, AccountRuleTemplate $accountRuleTemplate)
    {
        $accountRuleTemplate->update($request->all());

        return redirect()->route('admin.account-rule-templates.index');
    }

    public function show(AccountRuleTemplate $accountRuleTemplate)
    {
        abort_if(Gate::denies('account_rule_template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountRuleTemplate->load('rule_name', 'plan');

        return view('admin.accountRuleTemplates.show', compact('accountRuleTemplate'));
    }

    public function destroy(AccountRuleTemplate $accountRuleTemplate)
    {
        abort_if(Gate::denies('account_rule_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountRuleTemplate->delete();

        return back();
    }

    public function massDestroy(MassDestroyAccountRuleTemplateRequest $request)
    {
        AccountRuleTemplate::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
