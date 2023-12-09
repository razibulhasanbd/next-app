@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.accountLabel.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-labels.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.accountLabel.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ old('account_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountLabel.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="labels">{{ trans('cruds.accountLabel.fields.label') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('labels') ? 'is-invalid' : '' }}" name="labels[]" id="labels" multiple required>
                    @foreach($labels as $id => $label)
                        <option value="{{ $id }}" {{ in_array($id, old('labels', [])) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('labels'))
                    <div class="invalid-feedback">
                        {{ $errors->first('labels') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountLabel.fields.label_helper') }}</span>
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