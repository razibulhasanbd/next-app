@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.ruleName.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.rule-names.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.ruleName.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ruleName.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_percent') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_percent" value="0">
                    <input class="form-check-input" type="checkbox" name="is_percent" id="is_percent" value="1" {{ old('is_percent', 0) == 1 || old('is_percent') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_percent">{{ trans('cruds.ruleName.fields.is_percent') }}</label>
                </div>
                @if($errors->has('is_percent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_percent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ruleName.fields.is_percent_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="condition">{{ trans('cruds.ruleName.fields.condition') }}</label>
                <input class="form-control {{ $errors->has('condition') ? 'is-invalid' : '' }}" type="text" name="condition" id="condition" value="{{ old('condition', '') }}" required>
                @if($errors->has('condition'))
                    <div class="invalid-feedback">
                        {{ $errors->first('condition') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ruleName.fields.condition_helper') }}</span>
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