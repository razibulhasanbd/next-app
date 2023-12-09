@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.utilityCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.utility-categories.update", [$utilityCategory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.utilityCategory.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $utilityCategory->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityCategory.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="order_value">{{ trans('cruds.utilityCategory.fields.order_value') }}</label>
                <input class="form-control {{ $errors->has('order_value') ? 'is-invalid' : '' }}" type="number" name="order_value" id="order_value" value="{{ old('order_value', $utilityCategory->order_value) }}" step="1" required>
                @if($errors->has('order_value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityCategory.fields.order_value_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.utilityCategory.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\UtilityCategory::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $utilityCategory->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityCategory.fields.status_helper') }}</span>
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