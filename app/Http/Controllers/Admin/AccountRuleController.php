<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Account;
use App\Models\RuleName;
use App\Models\AccountRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreAccountRuleRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateAccountRuleRequest;
use App\Http\Requests\MassDestroyAccountRuleRequest;

class AccountRuleController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('account_rule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountRule::with(['account', 'rule'])->select(sprintf('%s.*', (new AccountRule())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_rule_show';
                $editGate = 'account_rule_edit';
                $deleteGate = 'account_rule_delete';
                $crudRoutePart = 'account-rules';

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
            $table->addColumn('loginId', function ($row) {
                return $row->account ? '<a href=' . route('admin.accounts.show', $row->account->id) . ' target="_blank">' . $row->account->login . '</a>' : '';
            });

            $table->addColumn('rule_name', function ($row) {
                return $row->rule ? $row->rule->name : '';
            });

            $table->editColumn('value', function ($row) {
                return $row->value ? $row->value : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account','rule','loginId']);

            return $table->make(true);
        }

        $rule_names = RuleName::get();

        return view('admin.accountRules.index', compact('rule_names'));
    }

    public function create()
    {
        abort_if(Gate::denies('account_rule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rules = RuleName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.accountRules.create', compact('accounts', 'rules'));
    }

    public function store(StoreAccountRuleRequest $request)
    {
        $account = Account::whereLogin($request->login)->first();

        $checkExistRule =  AccountRule::whereAccountId($account->id)->whereRuleId($request->rule_id)->first();
        if(isset($checkExistRule)){
            AccountRule::whereAccountId($account->id)->whereRuleId($request->rule_id)
            ->update(['value' => $request->value]);
        }else{
            AccountRule::create([
                'account_id' => $account->id,
                'rule_id' => $request->rule_id,
                'value' => $request->value
            ]);
        }
        Cache::forget($account->cacheKey() . ':account_rules');


        return redirect()->route('admin.account-rules.index');
    }

    public function edit(AccountRule $accountRule)
    {
        abort_if(Gate::denies('account_rule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rules = RuleName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accountRule->load('account', 'rule');

        return view('admin.accountRules.edit', compact('accountRule', 'accounts', 'rules'));
    }

    public function update(UpdateAccountRuleRequest $request, AccountRule $accountRule)
    {
        $account = Account::find($accountRule->account_id);
        $accountRule->value = $request->value;
        $accountRule->rule_id = $request->rule_id;
        $accountRule->account_id = $account->id;
        $accountRule->save();
        Cache::forget($account->cacheKey() . ':account_rules');
        return redirect()->route('admin.account-rules.index');
    }

    public function show(AccountRule $accountRule)
    {
        abort_if(Gate::denies('account_rule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountRule->load('account', 'rule');

        return view('admin.accountRules.show', compact('accountRule'));
    }

    public function destroy(AccountRule $accountRule)
    {

        $account = Account::find($accountRule->account_id);
        abort_if(Gate::denies('account_rule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accountRule->delete();
        Cache::forget($account->cacheKey() . ':account_rules');

        return back();
    }

    public function massDestroy(MassDestroyAccountRuleRequest $request)
    {
        AccountRule::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
