@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.targetReachedAccount.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.target-reached-accounts.update", [$targetReachedAccount->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.targetReachedAccount.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $targetReachedAccount->account->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.targetReachedAccount.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="plan_id">{{ trans('cruds.targetReachedAccount.fields.plan') }}</label>
                <select class="form-control select2 {{ $errors->has('plan') ? 'is-invalid' : '' }}" name="plan_id" id="plan_id" required>
                    @foreach($plans as $id => $entry)
                        <option value="{{ $id }}" {{ (old('plan_id') ? old('plan_id') : $targetReachedAccount->plan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.targetReachedAccount.fields.plan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="metric_info">{{ trans('cruds.targetReachedAccount.fields.metric_info') }}</label>
                <input class="form-control {{ $errors->has('metric_info') ? 'is-invalid' : '' }}" type="text" name="metric_info" id="metric_info" value="{{ old('metric_info', $targetReachedAccount->metric_info) }}" required>
                @if($errors->has('metric_info'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metric_info') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.targetReachedAccount.fields.metric_info_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="rules_reached">{{ trans('cruds.targetReachedAccount.fields.rules_reached') }}</label>
                <input class="form-control {{ $errors->has('rules_reached') ? 'is-invalid' : '' }}" type="text" name="rules_reached" id="rules_reached" value="{{ old('rules_reached', $targetReachedAccount->rules_reached) }}" required>
                @if($errors->has('rules_reached'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rules_reached') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.targetReachedAccount.fields.rules_reached_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="subscription_id">{{ trans('cruds.targetReachedAccount.fields.subscription') }}</label>
                <select class="form-control select2 {{ $errors->has('subscription') ? 'is-invalid' : '' }}" name="subscription_id" id="subscription_id" required>
                    @foreach($subscriptions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('subscription_id') ? old('subscription_id') : $targetReachedAccount->subscription->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('subscription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subscription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.targetReachedAccount.fields.subscription_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approved_at">{{ trans('cruds.targetReachedAccount.fields.approved_at') }}</label>
                <input class="form-control datetime {{ $errors->has('approved_at') ? 'is-invalid' : '' }}" type="text" name="approved_at" id="approved_at" value="{{ old('approved_at', $targetReachedAccount->approved_at) }}">
                @if($errors->has('approved_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('approved_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.targetReachedAccount.fields.approved_at_helper') }}</span>
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