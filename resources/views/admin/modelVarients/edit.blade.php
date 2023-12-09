@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.modelVarient.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.model-varients.update", [$modelVarient->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="business_model_id">{{ trans('cruds.modelVarient.fields.business_model') }}</label>
                <select class="form-control select2 {{ $errors->has('business_model') ? 'is-invalid' : '' }}" name="business_model_id" id="business_model_id" required>
                    @foreach($business_models as $id => $entry)
                        <option value="{{ $id }}" {{ (old('business_model_id') ? old('business_model_id') : $modelVarient->business_model->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('business_model'))
                    <div class="invalid-feedback">
                        {{ $errors->first('business_model') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.modelVarient.fields.business_model_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.modelVarient.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $modelVarient->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.modelVarient.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.modelVarient.fields.is_default') }}</label>
                @foreach(App\Models\ModelVarient::IS_DEFAULT_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('is_default') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="is_default_{{ $key }}" name="is_default" value="{{ $key }}" {{ old('is_default', $modelVarient->is_default) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="is_default_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('is_default'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_default') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.modelVarient.fields.is_default_helper') }}</span>
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