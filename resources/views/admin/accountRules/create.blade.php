@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.accountRule.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-rules.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="login">{{ trans('cruds.account.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number" name="login" id="login" value="{{ old('login', '') }}" required>
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
                        <option value="{{ $id }}" {{ old('rule_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <input class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="value" value="{{ old('value', '') }}" required>
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
