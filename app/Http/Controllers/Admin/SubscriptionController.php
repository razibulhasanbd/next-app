<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySubscriptionRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Account;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('subscription_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subscription::with('account', 'plan')->select(sprintf('%s.*', (new Subscription())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'subscription_show';
                $editGate = 'subscription_edit';
                $deleteGate = 'subscription_delete';
                $crudRoutePart = 'subscriptions';

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
            $table->editColumn('account.type', function ($row) {
                return $row->account ? (is_string($row->account) ? $row->account->type : $row->account->type) : '';
            });
            $table->editColumn('login', function ($row) {
                return  $row->login ? '<a href=' . route('admin.accounts.show', $row->account_id) . '>' . $row->login . '</a>' : '';
                
            });
            $table->editColumn('plan.title', function ($row) {
                return $row->plan ? (is_string($row->plan) ? $row->plan : $row->plan->title) : '';
            });
            $table->editColumn('created_at', function ($row) {
                return  $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });
            $table->editColumn('ending_at', function ($row) {
                return  $row->ending_at ? frontEndTimeConverterView($row->ending_at) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'account.type', 'login']);

            return $table->make(true);
        }

        return view('admin.subscriptions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('subscription_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::all()->pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');
        $plans = Plan::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subscriptions.create', compact('accounts', 'plans'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $subscription = Subscription::create($request->all());

        return redirect()->route('admin.subscriptions.index');
    }

    public function edit(Subscription $subscription)
    {
        abort_if(Gate::denies('subscription_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscription->load('account', 'plan');

        return view('admin.subscriptions.edit', compact('accounts', 'plans', 'subscription'));
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->all());

        return redirect()->route('admin.subscriptions.index');
    }

    public function show(Subscription $subscription)
    {
        abort_if(Gate::denies('subscription_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function destroy(Subscription $subscription)
    {
        abort_if(Gate::denies('subscription_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscription->delete();

        return back();
    }

    public function massDestroy(MassDestroySubscriptionRequest $request)
    {
        Subscription::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
