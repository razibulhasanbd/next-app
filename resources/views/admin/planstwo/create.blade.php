@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.plan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.plans.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.plan.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', '') }}" required>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.plan.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.plan.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}" required>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="upgradePlanId">{{ trans('cruds.plan.fields.upgrade_plan') }}</label>
                <input class="form-control {{ $errors->has('upgradePlanId') ? 'is-invalid' : '' }}" type="number" name="upgradePlanId" id="upgradePlanId" value="{{ old('upgradePlanId', '') }}" step="1">
                @if($errors->has('upgradePlanId'))
                    <div class="invalid-feedback">
                        {{ $errors->first('upgradePlanId') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.upgrade_plan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="serverGroupName">{{ trans('cruds.plan.fields.server_group_name') }}</label>
                <input class="form-control {{ $errors->has('serverGroupName') ? 'is-invalid' : '' }}" type="text" name="serverGroupName" id="serverGroupName" value="{{ old('serverGroupName', 'demoSLJUSD') }}" required>
                @if($errors->has('serverGroupName'))
                    <div class="invalid-feedback">
                        {{ $errors->first('serverGroupName') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.server_group_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="leverage">{{ trans('cruds.plan.fields.leverage') }}</label>
                <input class="form-control {{ $errors->has('leverage') ? 'is-invalid' : '' }}" type="number" name="leverage" id="leverage" value="{{ old('leverage', '100') }}" step="1" required>
                @if($errors->has('leverage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('leverage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.leverage_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="accountMaxDrawdown">{{ trans('cruds.plan.fields.account_max_drawdown') }}</label>
                <input class="form-control {{ $errors->has('accountMaxDrawdown') ? 'is-invalid' : '' }}" type="number" name="accountMaxDrawdown" id="accountMaxDrawdown" value="{{ old('accountMaxDrawdown', '1250.00') }}" step="0.01" required>
                @if($errors->has('accountMaxDrawdown'))
                    <div class="invalid-feedback">
                        {{ $errors->first('accountMaxDrawdown') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.account_max_drawdown_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="accountProfitTarget">{{ trans('cruds.plan.fields.account_profit_target') }}</label>
                <input class="form-control {{ $errors->has('accountProfitTarget') ? 'is-invalid' : '' }}" type="number" name="accountProfitTarget" id="account_profit_target" value="{{ old('accountProfitTarget', '') }}" step="0.01" required>
                @if($errors->has('accountProfitTarget'))
                    <div class="invalid-feedback">
                        {{ $errors->first('accountProfitTarget') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.account_profit_target_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="startingBalance">{{ trans('cruds.plan.fields.starting_balance') }}</label>
                <input class="form-control {{ $errors->has('startingBalance') ? 'is-invalid' : '' }}" type="number" name="startingBalance" id="startingBalance" value="{{ old('startingBalance', '') }}" step="1" required>
                @if($errors->has('startingBalance'))
                    <div class="invalid-feedback">
                        {{ $errors->first('startingBalance') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.starting_balance_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="dailyLossLimit">{{ trans('cruds.plan.fields.daily_loss_limit') }}</label>
                <input class="form-control {{ $errors->has('dailyLossLimit') ? 'is-invalid' : '' }}" type="number" name="dailyLossLimit" id="dailyLossLimit" value="{{ old('dailyLossLimit', '') }}" step="0.01" required>
                @if($errors->has('dailyLossLimit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dailyLossLimit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.daily_loss_limit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="upgradeThreshold">{{ trans('cruds.plan.fields.upgrade_threshold') }}</label>
                <input class="form-control {{ $errors->has('upgradeThreshold') ? 'is-invalid' : '' }}" type="number" name="upgradeThreshold" id="upgradeThreshold" value="{{ old('upgradeThreshold', '') }}" step="0.01" required>
                @if($errors->has('upgradeThreshold'))
                    <div class="invalid-feedback">
                        {{ $errors->first('upgradeThreshold') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.upgrade_threshold_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="accumulatedProfit">{{ trans('cruds.plan.fields.accumulated_profit') }}</label>
                <input class="form-control {{ $errors->has('accumulatedProfit') ? 'is-invalid' : '' }}" type="number" name="accumulatedProfit" id="accumulatedProfit" value="{{ old('accumulatedProfit', '') }}" step="0.01" required>
                @if($errors->has('accumulatedProfit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('accumulated_profit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.accumulated_profit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="profitShare">{{ trans('cruds.plan.fields.profit_share') }}</label>
                <input class="form-control {{ $errors->has('profitShare') ? 'is-invalid' : '' }}" type="number" name="profitShare" id="profitShare" value="{{ old('profitShare', '') }}" step="0.01" required>
                @if($errors->has('profitShare'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profitShare') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.profit_share_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label class="required">{{ trans('cruds.plan.fields.liquidate_friday') }}</label>
                @foreach(App\Models\Plan::LIQUIDATE_FRIDAY_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('liquidate_friday') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="liquidate_friday_{{ $key }}" name="liquidate_friday" value="{{ $key }}" {{ old('liquidate_friday', '0') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="liquidate_friday_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('liquidate_friday'))
                    <div class="invalid-feedback">
                        {{ $errors->first('liquidate_friday') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.liquidate_friday_helper') }}</span>
            </div> --}}

            <div class="form-group">
                <div class="form-check {{ $errors->has('liquidateFriday') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="liquidateFriday" value="0">
                    <input class="form-check-input" type="checkbox" name="liquidateFriday" id="liquidateFriday" value="1" {{ old('is_enabled', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="liquidateFriday">{{ trans('cruds.plan.fields.liquidate_friday') }}</label>
                </div>
                @if($errors->has('liquidateFriday'))
                    <div class="invalid-feedback">
                        {{ $errors->first('liquidateFriday') }}
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