@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ceritificate.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ceritificates.update", [$ceritificate->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.ceritificate.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $ceritificate->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ceritificate.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="html_markup">{{ trans('cruds.ceritificate.fields.html_markup') }}</label>
                <input class="form-control {{ $errors->has('html_markup') ? 'is-invalid' : '' }}" type="text" name="html_markup" id="html_markup" value="{{ old('html_markup', $ceritificate->html_markup) }}" required>
                @if($errors->has('html_markup'))
                    <div class="invalid-feedback">
                        {{ $errors->first('html_markup') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ceritificate.fields.html_markup_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="type_id">{{ trans('cruds.ceritificate.fields.type') }}</label>
                <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type_id" id="type_id">
                    @foreach($types as $id => $entry)
                        <option value="{{ $id }}" {{ (old('type_id') ? old('type_id') : $ceritificate->type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ceritificate.fields.type_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="image_file">Demo Image</label>
                <input @if($ceritificate->demo_image == null) required @endif type="file" class="form-control " name="demo_image">
                <small class="small font-italic">Example: jpeg,jpg,png. max:500 kb</small><br>
                @if($ceritificate->demo_image != null)
                    <a target="_blank" href="{{$ceritificate->demo_image}}">Click here</a><br>
                @endif
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
