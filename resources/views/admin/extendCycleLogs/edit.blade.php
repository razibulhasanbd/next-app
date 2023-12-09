@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.extendCycleLog.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.extend-cycle-logs.update", [$extendCycleLog->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="login_id">{{ trans('cruds.extendCycleLog.fields.login') }}</label>
                <select class="form-control select2 {{ $errors->has('login') ? 'is-invalid' : '' }}" name="login_id" id="login_id" required>
                    @foreach($logins as $id => $entry)
                        <option value="{{ $id }}" {{ (old('login_id') ? old('login_id') : $extendCycleLog->login->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.extendCycleLog.fields.login_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="subcription_id">{{ trans('cruds.extendCycleLog.fields.subcription') }}</label>
                <select class="form-control select2 {{ $errors->has('subcription') ? 'is-invalid' : '' }}" name="subcription_id" id="subcription_id" required>
                    @foreach($subcriptions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('subcription_id') ? old('subcription_id') : $extendCycleLog->subcription->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('subcription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subcription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.extendCycleLog.fields.subcription_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="weeks">{{ trans('cruds.extendCycleLog.fields.weeks') }}</label>
                <input class="form-control {{ $errors->has('weeks') ? 'is-invalid' : '' }}" type="number" name="weeks" id="weeks" value="{{ old('weeks', $extendCycleLog->weeks) }}" step="1" required>
                @if($errors->has('weeks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('weeks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.extendCycleLog.fields.weeks_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="before_subscription">{{ trans('cruds.extendCycleLog.fields.before_subscription') }}</label>
                <input class="form-control datetime {{ $errors->has('before_subscription') ? 'is-invalid' : '' }}" type="text" name="before_subscription" id="before_subscription" value="{{ old('before_subscription', $extendCycleLog->before_subscription) }}" required>
                @if($errors->has('before_subscription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('before_subscription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.extendCycleLog.fields.before_subscription_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="after_subscription">{{ trans('cruds.extendCycleLog.fields.after_subscription') }}</label>
                <input class="form-control datetime {{ $errors->has('after_subscription') ? 'is-invalid' : '' }}" type="text" name="after_subscription" id="after_subscription" value="{{ old('after_subscription', $extendCycleLog->after_subscription) }}" required>
                @if($errors->has('after_subscription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('after_subscription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.extendCycleLog.fields.after_subscription_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.extendCycleLog.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $extendCycleLog->account->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.extendCycleLog.fields.account_helper') }}</span>
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