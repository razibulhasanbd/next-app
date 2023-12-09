@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.tradeSlTp.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.trade-sl-tps.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="trade_id">{{ trans('cruds.tradeSlTp.fields.trade') }}</label>
                <select class="form-control select2 {{ $errors->has('trade') ? 'is-invalid' : '' }}" name="trade_id" id="trade_id" required>
                    @foreach($trades as $id => $entry)
                        <option value="{{ $id }}" {{ old('trade_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('trade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('trade') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tradeSlTp.fields.trade_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.tradeSlTp.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="number" name="type" id="type" value="{{ old('type', '') }}" step="1" required>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tradeSlTp.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="value">{{ trans('cruds.tradeSlTp.fields.value') }}</label>
                <input class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="value" value="{{ old('value', '') }}" required>
                @if($errors->has('value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tradeSlTp.fields.value_helper') }}</span>
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