@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.growthFund.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.growth-funds.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.growthFund.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.growthFund.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.growthFund.fields.date') }}</label>
                <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.growthFund.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.growthFund.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ old('account_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.growthFund.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="subscription_id">{{ trans('cruds.growthFund.fields.subscription') }}</label>
                <select class="form-control select2 {{ $errors->has('subscription') ? 'is-invalid' : '' }}" name="subscription_id" id="subscription_id" required>
                    @foreach($subscriptions as $id => $entry)
                        <option value="{{ $id }}" {{ old('subscription_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('subscription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subscription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.growthFund.fields.subscription_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fund_type">{{ trans('cruds.growthFund.fields.fund_type') }}</label>
                <input class="form-control {{ $errors->has('fund_type') ? 'is-invalid' : '' }}" type="text" name="fund_type" id="fund_type" value="{{ old('fund_type', '') }}">
                @if($errors->has('fund_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fund_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.growthFund.fields.fund_type_helper') }}</span>
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