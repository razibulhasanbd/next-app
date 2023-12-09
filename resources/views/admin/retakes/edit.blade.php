@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.retake.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.retakes.update", [$retake->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.retake.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $retake->account->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.retake.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="subscription_id">{{ trans('cruds.retake.fields.subscription') }}</label>
                <select class="form-control select2 {{ $errors->has('subscription') ? 'is-invalid' : '' }}" name="subscription_id" id="subscription_id" required>
                    @foreach($subscriptions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('subscription_id') ? old('subscription_id') : $retake->subscription->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('subscription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subscription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.retake.fields.subscription_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="retake_count">{{ trans('cruds.retake.fields.retake_count') }}</label>
                <input class="form-control {{ $errors->has('retake_count') ? 'is-invalid' : '' }}" type="number" name="retake_count" id="retake_count" value="{{ old('retake_count', $retake->retake_count) }}" step="1" required>
                @if($errors->has('retake_count'))
                    <div class="invalid-feedback">
                        {{ $errors->first('retake_count') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.retake.fields.retake_count_helper') }}</span>
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