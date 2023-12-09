@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.accountRuleTemplate.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-rule-templates.update", [$accountRuleTemplate->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="rule_name_id">{{ trans('cruds.accountRuleTemplate.fields.rule_name') }}</label>
                <select class="form-control select2 {{ $errors->has('rule_name') ? 'is-invalid' : '' }}" name="rule_name_id" id="rule_name_id" required>
                    @foreach($rule_names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('rule_name_id') ? old('rule_name_id') : $accountRuleTemplate->rule_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('rule_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rule_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRuleTemplate.fields.rule_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="plan_id">{{ trans('cruds.accountRuleTemplate.fields.plan') }}</label>
                <select class="form-control select2 {{ $errors->has('plan') ? 'is-invalid' : '' }}" name="plan_id" id="plan_id" required>
                    @foreach($plans as $id => $entry)
                        <option value="{{ $id }}" {{ (old('plan_id') ? old('plan_id') : $accountRuleTemplate->plan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRuleTemplate.fields.plan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="default_value">{{ trans('cruds.accountRuleTemplate.fields.default_value') }}</label>
                <input class="form-control {{ $errors->has('default_value') ? 'is-invalid' : '' }}" type="text" name="default_value" id="default_value" value="{{ old('default_value', $accountRuleTemplate->default_value) }}" required>
                @if($errors->has('default_value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('default_value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountRuleTemplate.fields.default_value_helper') }}</span>
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