@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.accountMetric.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-metrics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.id') }}
                        </th>
                        <td>
                            {{ $accountMetric->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.account') }}
                        </th>
                        <td>
                            {{ $accountMetric->account->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.max_daily_loss') }}
                        </th>
                        <td>
                            {{ $accountMetric->max_daily_loss }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.metric_date') }}
                        </th>
                        <td>
                            {{ $accountMetric->metric_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.is_active_trading_day') }}
                        </th>
                        <td>
                            {{ App\Models\AccountMetric::IS_ACTIVE_TRADING_DAY_RADIO[$accountMetric->is_active_trading_day] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.trades') }}
                        </th>
                        <td>
                            {{ $accountMetric->trades }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.average_losing_trade') }}
                        </th>
                        <td>
                            {{ $accountMetric->average_losing_trade }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.average_winning_trade') }}
                        </th>
                        <td>
                            {{ $accountMetric->average_winning_trade }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.last_balance') }}
                        </th>
                        <td>
                            {{ $accountMetric->last_balance }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.last_equity') }}
                        </th>
                        <td>
                            {{ $accountMetric->last_equity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.last_risk') }}
                        </th>
                        <td>
                            {{ $accountMetric->last_risk }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountMetric.fields.max_monthly_loss') }}
                        </th>
                        <td>
                            {{ $accountMetric->max_monthly_loss }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-metrics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection