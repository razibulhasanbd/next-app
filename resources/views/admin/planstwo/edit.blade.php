@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.plan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.plans.update", [$plan->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.plan.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', $plan->type) }}" required>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.plan.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $plan->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.plan.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $plan->description) }}" required>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="upgrade_plan">{{ trans('cruds.plan.fields.upgrade_plan') }}</label>
                <input class="form-control {{ $errors->has('upgrade_plan') ? 'is-invalid' : '' }}" type="number" name="upgrade_plan" id="upgrade_plan" value="{{ old('upgrade_plan', $plan->upgrade_plan) }}" step="1">
                @if($errors->has('upgrade_plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('upgrade_plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.upgrade_plan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="server_group_name">{{ trans('cruds.plan.fields.server_group_name') }}</label>
                <input class="form-control {{ $errors->has('server_group_name') ? 'is-invalid' : '' }}" type="text" name="server_group_name" id="server_group_name" value="{{ old('server_group_name', $plan->server_group_name) }}" required>
                @if($errors->has('server_group_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('server_group_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.server_group_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="leverage">{{ trans('cruds.plan.fields.leverage') }}</label>
                <input class="form-control {{ $errors->has('leverage') ? 'is-invalid' : '' }}" type="number" name="leverage" id="leverage" value="{{ old('leverage', $plan->leverage) }}" step="1" required>
                @if($errors->has('leverage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('leverage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.leverage_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="account_max_drawdown">{{ trans('cruds.plan.fields.account_max_drawdown') }}</label>
                <input class="form-control {{ $errors->has('account_max_drawdown') ? 'is-invalid' : '' }}" type="number" name="account_max_drawdown" id="account_max_drawdown" value="{{ old('account_max_drawdown', $plan->account_max_drawdown) }}" step="0.01" required>
                @if($errors->has('account_max_drawdown'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_max_drawdown') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.account_max_drawdown_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="account_profit_target">{{ trans('cruds.plan.fields.account_profit_target') }}</label>
                <input class="form-control {{ $errors->has('account_profit_target') ? 'is-invalid' : '' }}" type="number" name="account_profit_target" id="account_profit_target" value="{{ old('account_profit_target', $plan->account_profit_target) }}" step="0.01" required>
                @if($errors->has('account_profit_target'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_profit_target') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.account_profit_target_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="starting_balance">{{ trans('cruds.plan.fields.starting_balance') }}</label>
                <input class="form-control {{ $errors->has('starting_balance') ? 'is-invalid' : '' }}" type="number" name="starting_balance" id="starting_balance" value="{{ old('starting_balance', $plan->starting_balance) }}" step="1" required>
                @if($errors->has('starting_balance'))
                    <div class="invalid-feedback">
                        {{ $errors->first('starting_balance') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.starting_balance_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="daily_loss_limit">{{ trans('cruds.plan.fields.daily_loss_limit') }}</label>
                <input class="form-control {{ $errors->has('daily_loss_limit') ? 'is-invalid' : '' }}" type="number" name="daily_loss_limit" id="daily_loss_limit" value="{{ old('daily_loss_limit', $plan->daily_loss_limit) }}" step="0.01" required>
                @if($errors->has('daily_loss_limit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('daily_loss_limit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.daily_loss_limit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="upgrade_threshold">{{ trans('cruds.plan.fields.upgrade_threshold') }}</label>
                <input class="form-control {{ $errors->has('upgrade_threshold') ? 'is-invalid' : '' }}" type="number" name="upgrade_threshold" id="upgrade_threshold" value="{{ old('upgrade_threshold', $plan->upgrade_threshold) }}" step="0.01" required>
                @if($errors->has('upgrade_threshold'))
                    <div class="invalid-feedback">
                        {{ $errors->first('upgrade_threshold') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.upgrade_threshold_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="accumulated_profit">{{ trans('cruds.plan.fields.accumulated_profit') }}</label>
                <input class="form-control {{ $errors->has('accumulated_profit') ? 'is-invalid' : '' }}" type="number" name="accumulated_profit" id="accumulated_profit" value="{{ old('accumulated_profit', $plan->accumulated_profit) }}" step="0.01" required>
                @if($errors->has('accumulated_profit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('accumulated_profit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.accumulated_profit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="profit_share">{{ trans('cruds.plan.fields.profit_share') }}</label>
                <input class="form-control {{ $errors->has('profit_share') ? 'is-invalid' : '' }}" type="number" name="profit_share" id="profit_share" value="{{ old('profit_share', $plan->profit_share) }}" step="0.01" required>
                @if($errors->has('profit_share'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profit_share') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.profit_share_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.plan.fields.liquidate_friday') }}</label>
                @foreach(App\Models\Plan::LIQUIDATE_FRIDAY_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('liquidate_friday') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="liquidate_friday_{{ $key }}" name="liquidate_friday" value="{{ $key }}" {{ old('liquidate_friday', $plan->liquidate_friday) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="liquidate_friday_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('liquidate_friday'))
                    <div class="invalid-feedback">
                        {{ $errors->first('liquidate_friday') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.liquidate_friday_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection