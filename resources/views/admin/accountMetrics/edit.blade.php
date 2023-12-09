@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.accountMetric.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-metrics.update", [$accountMetric->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.accountMetric.fields.account') }}</label>
                <input class="form-control {{ $errors->has('account_id') ? 'is-invalid' : '' }}" type="number" name="account_id" id="account_id" value="{{ old('account_id', $accountMetric->account_id) }}" step="1" required>
                @if($errors->has('account_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="maxDailyLoss">{{ trans('cruds.accountMetric.fields.max_daily_loss') }}</label>
                <input class="form-control {{ $errors->has('maxDailyLoss') ? 'is-invalid' : '' }}" type="number" name="maxDailyLoss" id="maxDailyLoss" value="{{ old('maxDailyLoss', $accountMetric->maxDailyLoss) }}" step="0.0001" required>
                @if($errors->has('maxDailyLoss'))
                    <div class="invalid-feedback">
                        {{ $errors->first('maxDailyLoss') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.max_daily_loss_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="metricDate">{{ trans('cruds.accountMetric.fields.metric_date') }}</label>
                <input class="form-control datetime {{ $errors->has('metricDate') ? 'is-invalid' : '' }}" type="text" name="metricDate" id="metricDate" value="{{ old('metricDate', $accountMetric->metricDate) }}" required>
                @if($errors->has('metricDate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metricDate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.metric_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.accountMetric.fields.is_active_trading_day') }}</label>

                    <select class="form-control" name="isActiveTradingDay" id="isActiveTradingDay">
                        <option value="1" {{ old('isActiveTradingDay', $accountMetric->isActiveTradingDay) == 1 ? 'selected' : '' }} >Yes</option>
                        <option value="0" {{ old('isActiveTradingDay', $accountMetric->isActiveTradingDay)== 0 ? 'selected' : '' }} >No</option>
                    </select>

            </div>
            <div class="form-group">
                <label class="required" for="trades">{{ trans('cruds.accountMetric.fields.trades') }}</label>
                <input class="form-control {{ $errors->has('trades') ? 'is-invalid' : '' }}" type="number" name="trades" id="trades" value="{{ old('trades', $accountMetric->trades) }}" step="1" required>
                @if($errors->has('trades'))
                    <div class="invalid-feedback">
                        {{ $errors->first('trades') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.trades_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="averageLosingTrade">{{ trans('cruds.accountMetric.fields.average_losing_trade') }}</label>
                <input class="form-control {{ $errors->has('averageLosingTrade') ? 'is-invalid' : '' }}" type="number" name="averageLosingTrade" id="averageLosingTrade" value="{{ old('average_losing_trade', $accountMetric->averageLosingTrade) }}" step="0.001" required>
                @if($errors->has('averageLosingTrade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('averageLosingTrade') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.average_losing_trade_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="averageWinningTrade">{{ trans('cruds.accountMetric.fields.average_winning_trade') }}</label>
                <input class="form-control {{ $errors->has('averageWinningTrade') ? 'is-invalid' : '' }}" type="number" name="averageWinningTrade" id="averageWinningTrade" value="{{ old('averageWinningTrade', $accountMetric->averageWinningTrade) }}" step="0.001" required>
                @if($errors->has('averageWinningTrade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('averageWinningTrade') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.average_winning_trade_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lastBalance">{{ trans('cruds.accountMetric.fields.last_balance') }}</label>
                <input class="form-control {{ $errors->has('lastBalance') ? 'is-invalid' : '' }}" type="number" name="lastBalance" id="lastBalance" value="{{ old('lastBalance', $accountMetric->lastBalance) }}" step="0.001" required>
                @if($errors->has('lastBalance'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lastBalance') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.last_balance_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lastEquity">{{ trans('cruds.accountMetric.fields.last_equity') }}</label>
                <input class="form-control {{ $errors->has('lastEquity') ? 'is-invalid' : '' }}" type="number" name="lastEquity" id="lastEquity" value="{{ old('lastEquity', $accountMetric->lastEquity) }}" step="0.001" required>
                @if($errors->has('lastEquity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lastEquity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.last_equity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lastRisk">{{ trans('cruds.accountMetric.fields.last_risk') }}</label>
                <input class="form-control {{ $errors->has('lastRisk') ? 'is-invalid' : '' }}" type="number" name="lastRisk" id="lastRisk" value="{{ old('lastRisk', $accountMetric->lastRisk) }}" step="0.001" required>
                @if($errors->has('lastRisk'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lastRisk') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.last_risk_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="maxMonthlyLoss">{{ trans('cruds.accountMetric.fields.max_monthly_loss') }}</label>
                <input class="form-control {{ $errors->has('maxMonthlyLoss') ? 'is-invalid' : '' }}" type="number" name="maxMonthlyLoss" id="maxMonthlyLoss" value="{{ old('maxMonthlyLoss', $accountMetric->maxMonthlyLoss) }}" step="0.0001" required>
                @if($errors->has('maxMonthlyLoss'))
                    <div class="invalid-feedback">
                        {{ $errors->first('maxMonthlyLoss') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountMetric.fields.max_monthly_loss_helper') }}</span>
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
