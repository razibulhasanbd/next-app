@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.plan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.plans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.id') }}
                        </th>
                        <td>
                            {{ $plan->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.type') }}
                        </th>
                        <td>
                            {{ $plan->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.title') }}
                        </th>
                        <td>
                            {{ $plan->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.description') }}
                        </th>
                        <td>
                            {{ $plan->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.upgrade_plan') }}
                        </th>
                        <td>
                            {{ $plan->upgrade_plan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.server_group_name') }}
                        </th>
                        <td>
                            {{ $plan->server_group_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.leverage') }}
                        </th>
                        <td>
                            {{ $plan->leverage }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.account_max_drawdown') }}
                        </th>
                        <td>
                            {{ $plan->account_max_drawdown }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.account_profit_target') }}
                        </th>
                        <td>
                            {{ $plan->account_profit_target }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.starting_balance') }}
                        </th>
                        <td>
                            {{ $plan->starting_balance }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.daily_loss_limit') }}
                        </th>
                        <td>
                            {{ $plan->daily_loss_limit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.upgrade_threshold') }}
                        </th>
                        <td>
                            {{ $plan->upgrade_threshold }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.accumulated_profit') }}
                        </th>
                        <td>
                            {{ $plan->accumulated_profit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.profit_share') }}
                        </th>
                        <td>
                            {{ $plan->profit_share }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.plan.fields.liquidate_friday') }}
                        </th>
                        <td>
                            {{ App\Models\Plan::LIQUIDATE_FRIDAY_RADIO[$plan->liquidate_friday] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.plans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection