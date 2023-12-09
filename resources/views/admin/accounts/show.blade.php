@extends('layouts.admin')

@section('content')
    @if ($status === 'error')
        <div class="alert alert-danger"><b>Account Deleted</b></div>
    @endif
    <div class="row">
        <div class="col-sm-5">
            <div class="alert alert-info" style="display:none">
                {{ Session::get('success') }}
            </div>

            <div class="card">

                <div class="card-header d-flex flex-row justify-content-between">

                    <p>{{ trans('global.show') }} {{ trans('cruds.account.title') }}</p>

                    @if ($status !== 'error')
                        <div>

                            <a href="{{ route('admin.account-news-trades.view', $account->id) }}" target="_blank"
                                class="btn btn-info">News</a>

                            {{-- trade sync check created at datetime --}}
                            @can('account_view_trade_sync')
                                <a href="{{ route('admin.trades.accountTradeSyncCheck', $account->id) }}" class="btn btn-dark"
                                    target="_blank" class="btn btn-info">Trade sync</a>
                            @endcan

                            @can('running_show_trade')
                                <button class="btn btn-success" id="{{ $account->id }}" value="{{ $account->id }}"
                                    onclick="runningTrade(this.id)" data-toggle="tooltip" data-placement="top"
                                    title="Redis Running Trade"><i class="fa fa-wheelchair-alt"></i></button>
                            @endcan


                            @can('redis_margin_clear')
                                <button class="btn btn-warning" id="{{ $account->id }}" value="{{ $account->id }}"
                                    onclick="marginClear(this.id)" data-toggle="tooltip" data-placement="top"
                                    title="Redis Margin Clear"><i class="fa fa-eraser"></i></button>
                            @endcan

                            @can('smember_delete')
                                <button class="btn btn-danger" id="{{ $account->id }}" value="{{ $account->id }}"
                                    onclick="smemeberDelete(this.id)" data-toggle="tooltip" data-placement="top"
                                    title="Account Smember Delete"><i class="fa fa-trash"></i></button>
                            @endcan
                            @can('account_profit_checker')
                                <button type="button" id="{{ $account->id . 'profitCheckerBtn' }}"
                                    value="{{ $account->id }}" onclick="accountProfitChecker(this.id)"
                                    class="btn btn-info profitCheckerBtn">Profit Checker</button>
                            @endcan

                            @can('account_password_reset')
                                <button type="button" id="{{ $account->id . 'accountPasswordResetBtn' }}"
                                    value="{{ $account->id }}" onclick="accountPasswordReset({{ $account->id }})"
                                    class="btn btn-warning" title="Account password reset"><i class="fa fa-key"
                                        aria-hidden="true"></i></button>
                            @endcan

                            @can('investor_password_reset')
                                <button type="button" id="{{ $account->id . 'investorPasswordResetBtn' }}"
                                    value="{{ $account->id }}" onclick="investorPasswordReset({{ $account->id }})"
                                    class="btn btn-danger" title="Investor password reset"><i class="fa fa-key"
                                        aria-hidden="true"></i></button>
                            @endcan

                            @can('running_show_trade')
                            @if( $account->breached == 1)
                                <a type="button" value="{{ $account->id }}" href="{{ route('admin.accounts.breachedAccountAllTradeClose',['id'=>$account->id]) }}"
                                     class="btn btn-danger" title="Close All Trade"><i class="fa fa-window-close-o" aria-hidden="true"></i></a>
                            @endif
                            @endcan
                        </div>
                    @endif


                </div>


                <div class="card-body">
                    <div class="form-group">

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.id') }}</li>
                            <li class="list-group-item" style="width:160px">{{ $account->id }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.customer') }}
                            </li>
                            <li class="list-group-item" style="width:160px"><a target="_blank"
                                    href="{{ route('admin.customers.show', $account->customer->id) }}">{{ $account->customer->name }}</a>
                            </li>
                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.name') }}</li>
                            <li class="list-group-item" style="width:160px">{{ $account->name }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.country') }}</li>
                            <li class="list-group-item" style="width:160px">{{ $account->customer->customerCountry->name ?? "" }}</li>
                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.login') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->login }}</li>
                        </ul>
                        @can('mt4_password_show_hide')
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.password') }}
                                </li>
                                <li class="list-group-item" id="accountPasswordItemId" style="width:160px">
                                    <button type="button" id="{{ $account->id . 'accountPasswordViewBtn' }}"
                                        value="{{ $account->id }}" onclick="accountPasswordView({{ $account->id }})"
                                        class="btn btn-dark" title="Account password view"><i class="fa fa-eye-slash"
                                            aria-hidden="true"></i></button>
                                </li>
                            </ul>
                        @endcan
                        @can('investor_password_view')
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item" style="width:160px">Investor
                                    {{ trans('cruds.account.fields.password') }}
                                </li>
                                <li class="list-group-item" id="investorPasswordItemId" style="width:160px">
                                    <button type="button" id="{{ $account->id . 'investorPasswordViewBtn' }}"
                                        value="{{ $account->id }}" onclick="investorPasswordView({{ $account->id }})"
                                        class="btn btn-dark" title="Investor password view"><i class="fa fa-eye-slash"
                                            aria-hidden="true"></i></button>
                                </li>
                            </ul>
                        @endcan

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.type') }}</li>
                            <li class="list-group-item" style="width:160px">{{ $account->plan->title }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.plan') }}</li>
                            <li class="list-group-item" style="width:160px">{{ $account->plan->type }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.server') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->server->friendly_name }}
                            </li>
                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.balance') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->balance }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px"> {{ trans('cruds.account.fields.equity') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->equity }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px"> {{ trans('cruds.account.fields.plan') . ' ' . trans('cruds.account.fields.duration') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->duration }} days</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.credit') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->credit }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">{{ trans('cruds.account.fields.breached') }}
                            </li>
                            <li class="list-group-item" style="width:160px">
                                {{ $account->breached ? 'Yes' : 'Not yet' }}
                            </li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">
                                {{ trans('cruds.account.fields.breachedby') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->breachedby }}</li>
                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px"><button type="button" class="btn btn-alert"
                                    data-toggle="modal" data-target="#commentModal"><i class="fas fa-plus pr-2"
                                        aria-hidden="true"></i></button>{{ trans('cruds.account.fields.comment') }}
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $account->comment }}</li>

                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">Open Trade
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $openTrades }}</li>
                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">Close Trade
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $closeTrades }}</li>
                        </ul>

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">Arbitrary Trade
                            </li>
                            <li class="list-group-item" style="width:160px">{{ $arbitraryTrades }}</li>
                        </ul>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item" style="width:160px">Account lots
                            </li>
                            <li class="list-group-item" style="width:160px"> {{ $cardData['lots'] }}</li>
                        </ul>

                        </br>

                        @if ($status !== 'error')
                            @can('top_up_reset_section_show_hide')
                                {{-- Topup Button --}}
                                <form name="resetLogin" method="POST" action="{{ route('admin.accounts.topup') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="required"
                                            for="login">{{ trans('cruds.account.fields.login') }}</label>
                                        <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}"
                                            type="text" name="login_id" id="login_id" placeholder="loginid/topup"
                                            value="{{ old('login', '') }}" step="1" required>
                                        @if ($errors->has('login'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('login') }}
                                            </div>
                                        @endif
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" id="resetButton" class="btn btn-default">Reset</button>
                                    </div>

                                </form>


                                <div class="mt-3">
                                    <b>Accout Hard On/Off </br></b>
                                </div>

                                <div class="btn-group btn-toggle mt-1">

                                    <button
                                        class="btn btn-md  {{ ($status === true || $status === false) && $account->breached == '0' ? 'btn-success active' : 'btn-default' }}"
                                        id="open{{ $account->id }}" value="{{ $account->id }}"
                                        onclick="accountOpen(this.id)">Account On</button>
                                    <button
                                        class="btn btn-md  {{ ($status === false || $status === true) && $account->breached == '1' ? 'btn-danger active' : 'btn-default' }}"
                                        id="close{{ $account->id }}" value="{{ $account->id }}"
                                        onclick="accountClose(this.id)">Account Off</button>
                                </div>



                                <div class="mt-3">
                                    <b>Accout Trading </br></b>
                                </div>
                                <div class="btn-group btn-toggle mt-1">



                                    <button class="btn btn-md  {{ $status === true ? 'btn-success active' : 'btn-default' }}"
                                        id="{{ $account->id }}" onclick="enableTrading(this.id)">Enable Trading</button>
                                    <button class="btn btn-md  {{ $status === false ? 'btn-danger active' : 'btn-default' }}"
                                        id="{{ $account->id }}" onclick="disableTrading(this.id)">Disable Trading</button>
                                </div>
                            @endcan
                        @endif

                    </div>
                </div>
                <div class="alert alert-success" style="display:none">
                    {{ Session::get('success') }}
                </div>

                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if (Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card userCard">
                        <div class="card-header">
                            Minimun Trading Days
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-secondary p-2">
                                    Minimun <span
                                        class="badge badge-primary badge-dark">{{ $cardData['minimumTradingDays'] }}</span>
                                </li>
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-dark mt-3 p-2">
                                    Current Result <span
                                        class="badge badge-primary  badge-dark">{{ $cardData['isActiveTradingDay'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card userCard">
                        <div class="card-header">
                            Daily Loss Limit
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-secondary p-2">
                                    Max.Loss <span
                                        class="badge badge-primary badge-dark">{{ $cardData['maxDailyLossLimit'] }}</span>
                                </li>
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-dark mt-3 p-2">
                                    Max.Loss recorded <span
                                        class="badge badge-primary  badge-dark">{{ $cardData['maxDailyLoss'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card userCard">
                        <div class="card-header">
                            Monthly Loss Limit
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-secondary p-2">
                                    Max.Loss <span
                                        class="badge badge-primary badge-dark">{{ $cardData['maxMonthlyLossLimit'] }}</span>
                                </li>
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-dark mt-3 p-2">
                                    Max.Loss recorded <span
                                        class="badge badge-primary  badge-dark">{{ $cardData['maxMonthlyLoss'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card userCard">
                        <div class="card-header">
                            Profit Target
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-secondary p-2">
                                    Minimum<span
                                        class="badge badge-primary badge-dark">{{ $cardData['profitTarget'] }}</span>
                                </li>
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-dark mt-3 p-2">
                                    Current Result<span
                                        class="badge badge-primary  badge-dark">{{ $cardData['profitTargetReached'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- <x-card :minimumTradingDays="$cardData['minimumTradingDays']">
        </x-card>
        <x-card :isActiveTradingDay="$cardData['isActiveTradingDay']">
        </x-card> --}}


        <div class="col-sm-7">

            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between">

                    <p>{{ trans('global.show') }} {{ trans('cruds.account.title') }}</p>
                    <div>
                        
                        @can('force-account-migrate')
                            @if ($account->breached == 1 && $account->breachedby == 'Profit Target Reached')
                                <button class="btn btn-danger" id="accountForcelyMigrateId" value="{{ $account->id }}"
                                    onclick="accountForcelyMigrate({{ $account->id }})" data-toggle="tooltip"
                                    data-placement="top" title="Forcefully Migrate to Next Phase"><i
                                        class="fa fa-flag alt"></i></button>
                            @else
                                <span class="badge badge-primary badge-dark">Breached:
                                    {{ $account->breached == 1 ? 'Yes' : 'No' }}</span>
                                <span class="badge badge-primary badge-dark">Profit Target Reached:
                                    {{ $account->breachedby == 'Profit Target Reached' ? 'Yes' : 'No' }}</span>
                            @endif
                        @endcan

                        @can('account-settings')
                            <a href="{{ route('admin.account-settings.view', $account->id) }}" class="btn btn-success"
                                id="{{ $account->id }}" value="{{ $account->id }}" data-toggle="tooltip"
                                data-placement="top" title="Account Settings"><i class="fa fa-cog"></i></a>
                        @endcan


                        {{-- @can('extend_cycle_access')
                            <a href="{{ route('admin.extend-cycle.view', $account->id) }}" class="btn btn-info">Extended
                                Cycle</a>
                        @endcan --}}

                    </div>

                </div>
                <div class="card-header">
                    {{ trans('global.show') }} Subscription
                </div>

                <div class="card-body">
                    <div class="form-group">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Type</th>
                                    <th>Plan</th>
                                    <th>Starting_at</th>
                                    <th>Ending_at</th>
                            </thead>

                            <tbody>
                                @if (isset($account->subscriptions))
                                    @foreach ($account->subscriptions as $row)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td><a target="_blank"
                                                    href="{{ route('admin.subscriptions.edit', $row->id) }}">{{ $row->plan->type }}</a>
                                            </td>
                                            <td>{{ $row->plan->title }}</td>
                                            <td>{{ frontEndTimeConverterView($row->created_at) }}</td>
                                            <td>{{ frontEndTimeConverterView($row->ending_at) }} <a
                                                    href="{{ route('admin.account-specific-news-trades.view', $row->id) }}"
                                                    target="_blank" class="btn btn-info">News</a></td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} User Plan Rules
                </div>

                <div class="card-body">
                    <div class="form-group">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Rule Name</th>
                                    <th>Value</th>
                                    <th>Condition</th>
                            </thead>

                            <tbody>
                                @if (isset($userPlanRules))
                                    @foreach ($userPlanRules as $key => $row)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td
                                                class="{{ isset($row['is_accountRule']) ? 'text-danger' : 'text-dark' }}">
                                                {{ $row['rule'] }}
                                            </td>
                                            <td>{{ $row['value'] }}</td>
                                            <td>{{ $row['condition'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>



            @if (isset($userPlanRules) && !$userPlanRules->has('NCA'))
                <div class="card" id="accountConsistenyRule">
                    <div class="card-header">
                        Consistency Rule
                    </div>
                    <div class="card-body">
                        <div class="form-group">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Consistency</th>
                                        <th>This Week Av</th>
                                        <th>Overall Av</th>
                                        <th>Uper Limit</th>
                                        <th>Lower Limit</th>
                                        <th>Standard Deviation</th>
                                </thead>
                                <tbody>

                                    <tr>
                                        <th scope="row">Trade</th>

                                        <td>{{ $consistencyRule['trade']['weekly_average'] }}</td>
                                        <td> {{ $consistencyRule['trade']['overall_average'] }}</td>
                                        <td>{{ $consistencyRule['trade']['high'] }}</td>
                                        <td>{{ $consistencyRule['trade']['low'] }}</td>

                                        <td>{{ $userPlanRules['CRD']['value'] ?? '2.5' }}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Lot</th>

                                        <td>{{ $consistencyRule['lot']['weekly_average'] }}</td>
                                        <td>{{ $consistencyRule['lot']['overall_average'] }}</td>
                                        <td>{{ $consistencyRule['lot']['high'] }}</td>
                                        <td>{{ $consistencyRule['lot']['low'] }}</td>
                                        <td>{{ $userPlanRules['CRD']['value'] ?? '2.5' }}</td>
                                    </tr>




                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            @endif



            <div class="card" id="accountConsistenyRule">
                <div class="card-header">
                    Show Growth Fund
                </div>
                <div class="card-body">
                    <div class="form-group">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Fund Type</th>
                                    <th>Date</th>
                            </thead>
                            <tbody>
                                @if (isset($account->growthFund))
                                    @foreach ($account->growthFund as $item)
                                        <tr>
                                            <td>{{ $item->amount }}</td>
                                            <td> {{ $item->fund_type }}</td>
                                            <td>{{ $item->date }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="card userCard">
                        <div class="card-header">
                            Current Results
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-secondary p-2">
                                    Today's Permitted Loss <span
                                        class="badge badge-primary badge-dark">{{ $cardData['dailyLossThreshold'] }}</span>
                                </li>
                                <li
                                    class="d-flex justify-content-between align-items-center list-group-item-dark mt-3 p-2">
                                    Max Permitted Loss <span
                                        class="badge badge-primary  badge-dark">{{ $cardData['maxLossThreshold'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>


        </div>


        <div class="card w-100" id="accountMetric">
            <div class="card-header">
                Account Metrics
            </div>

            <div class="card-body ">
                <div class="form-group">
                    <div class="form-group">
                        <button class="btn btn-default" id="{{ $account->id }}"
                            onClick="getAccountIdForMetrics(this.id)">Refresh Account Metrics</button>
                    </div>

                    <div class="mr-2 overflow-auto">
                        <table class="table table-bordered table-striped px-2-2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Metric Date</th>
                                    <th>Max Daily Loss</th>
                                    <th>Max Monthly Loss</th>
                                    <th>Active Trading</th>
                                    <th>Trades</th>
                                    <th>Last Balance</th>
                                    <th>LastEquity</th>
                                    <th>Avg Losing Trade</th>
                                    <th>Avg Winning Trade</th>
                                    <th>Last Risk</th>
                            </thead>

                            <tbody id="accountForMetric">

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>

        {{-- Consistency Report --}}
        @if (isset($userPlanRules) && !$userPlanRules->has('NCA'))
            <div class="card w-100" id="accountConsisitencyReport">
                <div class="card-header">
                    Show Account Consistency report

                    <div id="consistancyreportrange" class="float-right"
                        style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div>

                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div id="accountForConsistencyReport">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Account ID Wise Trades --}}
        <div class="card w-100" id="accountTrade">
            <div class="card-header">
                Show Account Trade
            </div>
            <div class="card-body">
                <div class="form-group">

                    <div class="form-group">
                        <button class="btn btn-default" id="{{ $account->id }}"
                            onClick="getAccountIdForTrades(this.id)">Show Account Trades</button>
                    </div>



                    <div id="accountForTrade">

                    </div>



                </div>
            </div>
        </div>

        {{-- Account ID Wise Trades --}}

    </div>


    <!-- Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.accounts.comment.update') }}">
                        <input type="hidden" name="accountId" value="{{ $account->id }}">
                        <div class="form-group">
                            <label for="comment" class="col-form-label">Comment:</label>
                            <input type="text" class="form-control" maxlength="240"
                                value="{{ $account->comment ? $account->comment : '' }}" name="comment" id="comment">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type='text/javascript'>
        function validateLogin(id) {

            let fullTopUp = document.forms["resetLogin"]["login_id"].value;
            let loginId = fullTopUp.split('/').at(0);
            let accountId = {{ $account->login }};

            if (loginId == accountId) {
                return true;
            } else {
                alert("Login Id not match!!");
                return false;
            }
        }


        function getAccountIdForMetrics(id) {
            $("#accountMetric").show();

            var e = document.getElementById(id);
            $.ajax({
                url: 'id-wise-account-metrics/' + id,
                type: 'get',
                dataType: 'json',
                success: function(dataResult) {
                    var resultData = dataResult.data;
                    var bodyData = '';
                    var i = 1;
                    $.each(resultData, function(index, row) {
                        var editUrl = '/' + 'admin/account-metrics/' + +row.id + "/edit";
                        bodyData += "<tr>"
                        bodyData += "<td><a href=" + editUrl + ">" + i++ + "</a></td><td>" + row
                            .metricDate + "</td><td>" + row
                            .maxDailyLoss + "</td><td>" + row.maxMonthlyLoss + "</td><td>" + row
                            .isActiveTradingDay + "</td><td>" + row.trades + "</td><td>" + row
                            .lastBalance + "</td><td>" + row.lastEquity + "</td>" +
                            "<td>" + row.averageLosingTrade + "</td><td>" + row.averageWinningTrade +
                            "</td><td>" + row.lastRisk + "</td>";
                        bodyData += "</tr>";

                    })
                    $("#accountForMetric").html(bodyData);

                }
            });
        }


        // for pagination
        function getAccountIdForTrades(id) {

            $("#accountTrade").show();
            var e = document.getElementById(id);
            var pageNo = 1;
            $.ajax({
                url: 'id-wise-account-trades-page/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "pageNo": pageNo,
                    "id": id
                },
                success: function(responseData) {

                    let getDataView = responseData.view;
                    $("#accountForTrade").html(getDataView);
                },
                error: function(responseData) {
                    console.log(responseData);

                }
            });

        }


        function hitPreviousBtn(prevUrl, accountId) {

            $.ajax({
                url: 'id-wise-account-trades-page/' + accountId,
                type: 'post',
                dataType: 'json',
                data: {
                    "pageNo": prevUrl.replace('/?page=', ''),
                    "id": accountId
                },
                success: function(responseData) {
                    let getDataView = responseData.view;
                    $("#accountForTrade").html(getDataView);
                }
            });
        }

        function hitNextBtn(nextUrl, accountId) {

            $.ajax({
                url: 'id-wise-account-trades-page/' + accountId,
                type: 'post',
                dataType: 'json',
                data: {
                    "pageNo": nextUrl.replace('/?page=', ''),
                    "id": accountId
                },
                success: function(responseData) {
                    let getDataView = responseData.view;
                    $("#accountForTrade").html(getDataView);
                }
            });
        }



        $('.btn-toggle').click(function() {
            $(this).find('.btn').toggleClass('active');

            if ($(this).find('.btn-primary').length > 0) {
                $(this).find('.btn').toggleClass('btn-primary');
            }
            if ($(this).find('.btn-danger').length > 0) {
                $(this).find('.btn').toggleClass('btn-danger');
            }
            if ($(this).find('.btn-success').length > 0) {
                $(this).find('.btn').toggleClass('btn-success');
            }
            if ($(this).find('.btn-info').length > 0) {
                $(this).find('.btn').toggleClass('btn-info');
            }

            $(this).find('.btn').toggleClass('btn-default');

        });



        function accountClose(id) {
            if (id) {
                let response = confirm('WARNING ! Are you sure you want to  ' + id +
                    ' account? The account will be breached and account metrics will not be updated.');
                if (response == true) {
                    $.ajax({
                        url: 'id-wise-account-status/' + id,
                        type: 'get',
                        dataType: 'json',
                        success: function(dataResult) {
                            console.log('response', dataResult);
                            window.location.reload();
                            $(".alert-success").css("display", "block");
                            $(".alert-success").append("<P>" + dataResult.message);

                        }
                    });

                } else {
                    console.log('dsfsd');

                }
            } else {

            }
        }

        function accountOpen(id) {
            if (id) {
                let response = confirm('WARNING ! Are you sure you want to  ' + id +
                    ' account? The account metrics might not be accurate and the account might breach instantly after opening.'
                );
                if (response == true) {
                    $.ajax({
                        url: 'id-wise-account-status/' + id,
                        type: 'get',
                        dataType: 'json',
                        success: function(dataResult) {
                            console.log('response', dataResult);
                            window.location.reload();
                            $(".alert-success").css("display", "block");
                            $(".alert-success").append("<P>" + dataResult.message);

                        }
                    });

                } else {
                    console.log('dsfsd');

                }
            } else {

            }
        }


        function enableTrading(id) {

            if (id) {
                $.ajax({
                    url: '/admin/enable-trading/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(dataResult) {
                        window.location.reload();
                        $(".alert-success").css("display", "block");
                        $(".alert-success").append("<P>" + dataResult.message);
                        console.log('response', dataResult.message);
                    },
                    error: function(dataResult) {
                        console.log('response', dataResult);
                    }
                });

            } else {

            }
        }

        function disableTrading(id) {

            if (id) {
                $.ajax({
                    url: '/admin/disable-trading/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(dataResult) {
                        window.location.reload();
                        $(".alert-success").css("display", "block");
                        $(".alert-success").append("<P>" + dataResult.message);
                        console.log('response', dataResult);
                    },
                    error: function(dataResult) {
                        console.log('response', dataResult);
                    }
                });

            } else {

            }
        }

        function runningTrade(id) {
            if (id) {
                $.ajax({
                    url: '/api/test/account/' + id + '/redisrunningtrade',
                    type: 'get',
                    dataType: 'json',
                    success: function(dataResult) {
                        $(".alert-info").css("display", "block");
                        $(".alert-info").append("<P>" + dataResult);

                    },
                    error: function(dataResult) {
                        console.log('response', dataResult);
                    }
                });

            } else {

            }
        }

        function marginClear(id) {
            if (id) {
                $.ajax({
                    url: '/api/test/account/' + id + '/marginclear',
                    type: 'get',
                    dataType: 'json',
                    success: function(dataResult) {
                        $(".alert-info").css("display", "block");
                        $(".alert-info").append("<P>" + dataResult);

                    },
                    error: function(dataResult) {
                        console.log('response', dataResult);
                    }
                });

            } else {

            }
        }

        function smemeberDelete(id) {
            if (id) {
                $.ajax({
                    url: '/api/test/account/' + id + '/smemDelete',
                    type: 'get',
                    dataType: 'json',
                    success: function(dataResult) {
                        window.location.reload();

                    },
                    error: function(dataResult) {
                        console.log('response', dataResult);
                    }
                });

            } else {

            }
        }

        function accountProfitChecker(id) {
            let spinner = '<span class="spinner-border spinner-border-sm ml-3" role="status" aria-hidden="true"></span>';
            let newSpan = document.createElement("span");
            let btnClicked = document.getElementById(id);

            newSpan.innerHTML = spinner;
            var regex = /\d+/g;
            id = id.match(regex);

            if (id) {
                $.ajax({
                    url: '/api/test/account/' + id + '/profitChecker',
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function() {
                        btnClicked.appendChild(newSpan);
                        // btnClicked.disabled = true;
                    },
                    success: function(dataResult) {
                        if (dataResult && dataResult.message == 'reload') {
                            setTimeout(function() {
                                btnClicked.removeChild(btnClicked.lastChild);
                                window.location.reload();
                            }, 3000);

                        } else {
                            btnClicked.removeChild(btnClicked.lastChild);
                            btnClicked.disabled = false;
                            $(".alert-info").css("display", "block");
                            $(".alert-info").append("<P>" + dataResult);
                        }

                    },
                    error: function(dataResult) {
                        console.log('response', dataResult);
                    }
                });

            } else {

            }
        }

        function investorPasswordReset(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change the passowrd!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if (id) {
                        $.ajax({
                            url: "{{ route('investor-password-set') }}",
                            type: 'post',
                            dataType: 'json',
                            data: {
                                "account_id": id,
                            },
                            beforeSend: function() {
                                $('#' + id + 'investorPasswordResetBtn').attr("disabled", "disabled");
                                $('#' + id + 'investorPasswordResetBtn').css("opacity", ".5");
                            },
                            success: function(dataResult) {
                                investorPasswordEmailAlert(id);
                                investorPasswordView(id);

                            },

                            error: function(dataResult) {
                                Swal.fire(
                                    'Error!',
                                    dataResult.message,
                                    'error'
                                )
                            },
                            complete: function() {
                                $('#' + id + 'investorPasswordResetBtn').removeAttr("disabled");
                                $('#' + id + 'investorPasswordResetBtn').css("opacity", "1");
                            },

                        });
                    }
                }
            });


        }

        function investorPasswordEmailAlert(id) {
            Swal.fire({
                title: 'Password changed successfully!',
                text: "Do want to send customer an email?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes send to customer'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('investor-password-email') }}",
                        type: 'post',
                        dataType: 'json',
                        data: {
                            "account_id": id,
                        },

                        success: function(dataResult) {
                            Swal.fire(
                                'Sent!',
                                dataResult.message,
                                'success'
                            )
                        },


                        error: function(dataResult) {
                            Swal.fire(
                                'Error!',
                                dataResult.message,
                                'error'
                            )
                        },

                    });
                }
            })
        }


        function investorPasswordView(id) {
            if (id) {
                $.ajax({
                    url: "{{ route('investor-password-get') }}",
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        "account_id": id,
                    },
                    beforeSend: function() {
                        $('#' + id + 'investorPasswordViewBtn').attr("disabled", "disabled");
                        $('#' + id + 'investorPasswordViewBtn').css("opacity", ".5");
                    },
                    success: function(dataResult) {
                        $('#investorPasswordItemId').text(dataResult.data.investor_password);
                    },

                    error: function(dataResult) {
                        console.log(dataResult);
                        Swal.fire(
                            'Error!',
                            dataResult.message,
                            'error'
                        )
                    },
                    complete: function() {
                        $('#' + id + 'investorPasswordViewBtn').removeAttr("disabled");
                        $('#' + id + 'investorPasswordViewBtn').css("opacity", "1");
                    },
                });

            } else {

            }
        }

        function accountPasswordView(id) {
            if (id) {
                $.ajax({
                    url: "{{ route('account-password-get') }}",
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        "account_id": id,
                    },
                    beforeSend: function() {
                        $('#' + id + 'accountPasswordViewBtn').attr("disabled", "disabled");
                        $('#' + id + 'accountPasswordViewBtn').css("opacity", ".5");
                    },
                    success: function(dataResult) {
                        $('#accountPasswordItemId').text(dataResult.data.account_password);
                    },

                    error: function(dataResult) {
                        console.log(dataResult);
                        Swal.fire(
                            'Error!',
                            dataResult.message,
                            'error'
                        )
                    },
                    complete: function() {
                        $('#' + id + 'accountPasswordViewBtn').removeAttr("disabled");
                        $('#' + id + 'accountPasswordViewBtn').css("opacity", "1");
                    },
                });

            } else {

            }
        }

        function accountPasswordReset(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change the passowrd!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if (id) {
                        $.ajax({
                            url: "{{ route('account-password-set') }}",
                            type: 'post',
                            dataType: 'json',
                            data: {
                                "account_id": id,
                            },
                            beforeSend: function() {
                                $('#' + id + 'accountPasswordResetBtn').attr("disabled", "disabled");
                                $('#' + id + 'accountPasswordResetBtn').css("opacity", ".5");
                            },
                            success: function(dataResult) {
                                accountPasswordView(id);
                                Swal.fire('Password changed!', '', 'success')
                            },

                            error: function(dataResult) {
                                Swal.fire(
                                    'Error!',
                                    dataResult.message,
                                    'error'
                                )
                            },
                            complete: function() {
                                $('#' + id + 'accountPasswordResetBtn').removeAttr("disabled");
                                $('#' + id + 'accountPasswordResetBtn').css("opacity", "1");
                            },

                        });
                    }
                }
            });
        }


        function accountForcelyMigrate(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are going to migrate to next phase",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.value) {
                    if (id) {
                        $.ajax({
                            url: "{{ route('account-forcely-migrate') }}",
                            type: 'post',
                            dataType: 'json',
                            data: {
                                "accountId": id,
                                "phaseId": null,
                                "createNewAccount": true
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },

                            beforeSend: function() {
                                $('#accountForcelyMigrateId').attr("disabled", "disabled");
                                $('#accountForcelyMigrateId').css("opacity", ".5");
                            },
                            success: function(dataResult) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: dataResult.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            },

                            error: function(dataResult) {
                                Swal.fire(
                                    'Error!',
                                    dataResult.responseJSON.message,
                                    'error'
                                )
                            },
                            complete: function() {
                                $('#accountForcelyMigrateId').removeAttr("disabled");
                                $('#accountForcelyMigrateId').css("opacity", "1");
                            },

                        });
                    };
                }
            });


        }
    </script>



    <script type="text/javascript">
        $(function() {

            var start = moment().subtract(29, 'days');
            var end = moment();
            var lstart, lend;

            $('#consistancyreportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);
            cb(start, end);

            function cb(start, end) {
                $('#consistancyreportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                lstart = moment($('#consistancyreportrange').data('daterangepicker').startDate).toDate(),
                    startDate = new Intl.DateTimeFormat("us", {
                        year: "numeric",
                        month: "numeric",
                        day: "numeric"
                    }).format(new Date(lstart)),

                    lend = moment($('#consistancyreportrange').data('daterangepicker').endDate).toDate();

                endDate = new Intl.DateTimeFormat("us", {
                        year: "numeric",
                        month: "numeric",
                        day: "numeric"
                    }).format(new Date(lend)),
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('admin.trades.ConsistencyReport') }}",
                        data: {
                            startDate: startDate,
                            endDate: endDate,
                            accountId: {{ $account->id }}
                        },
                        success: function(data) {

                            $("#accountForConsistencyReport").html(data.view);
                        }
                    });
            }

        });
    </script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endsection
