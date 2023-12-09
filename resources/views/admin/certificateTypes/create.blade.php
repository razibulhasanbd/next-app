@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.certificateType.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.certificate-types.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.certificateType.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.certificateType.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="label">{{ trans('cruds.certificateType.fields.label') }}</label>
                <input class="form-control {{ $errors->has('label') ? 'is-invalid' : '' }}" type="text" name="label" id="label" value="{{ old('label', '') }}">
                @if($errors->has('label'))
                    <div class="invalid-feedback">
                        {{ $errors->first('label') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.certificateType.fields.label_helper') }}</span>
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