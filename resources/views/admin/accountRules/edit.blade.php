@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.accountRule.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-rules.update", [$accountRule->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            {{-- <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.accountRule.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $accountRule->account->login ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRule.fields.account_helper') }}</span>
            </div> --}}

            <div class="form-group">
                <label class="required" for="login">{{ trans('cruds.account.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number" name="login" id="login" value="{{ $accountRule->account->login }}" required>
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRule.fields.value_helper') }}</span>
            </div>


            <div class="form-group">
                <label class="required" for="rule_id">{{ trans('cruds.accountRule.fields.rule') }}</label>
                <select class="form-control select2 {{ $errors->has('rule') ? 'is-invalid' : '' }}" name="rule_id" id="rule_id" required>
                    @foreach($rules as $id => $entry)
                        <option value="{{ $id }}" {{ (old('rule_id') ? old('rule_id') : $accountRule->rule->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('rule'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rule') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRule.fields.rule_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="value">{{ trans('cruds.accountRule.fields.value') }}</label>
                <input class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="value" value="{{ old('value', $accountRule->value) }}" required>
                @if($errors->has('value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRule.fields.value_helper') }}</span>
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
