@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.accountCertificate.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-certificates.update", [$accountCertificate->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="certificate_id">{{ trans('cruds.accountCertificate.fields.certificate') }}</label>
                <select class="form-control select2 {{ $errors->has('certificate') ? 'is-invalid' : '' }}" name="certificate_id" id="certificate_id" required>
                    @foreach($certificates as $id => $entry)
                        <option value="{{ $id }}" {{ (old('certificate_id') ? old('certificate_id') : $accountCertificate->certificate->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('certificate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('certificate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountCertificate.fields.certificate_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.accountCertificate.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $accountCertificate->account->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountCertificate.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="certificate_data">{{ trans('cruds.accountCertificate.fields.certificate_data') }}</label>
                <input class="form-control {{ $errors->has('certificate_data') ? 'is-invalid' : '' }}" type="text" name="certificate_data" id="certificate_data" value="{{ old('certificate_data', $accountCertificate->certificate_data) }}" required>
                @if($errors->has('certificate_data'))
                    <div class="invalid-feedback">
                        {{ $errors->first('certificate_data') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountCertificate.fields.certificate_data_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="customer_id">{{ trans('cruds.accountCertificate.fields.customer') }}</label>
                <select class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}" name="customer_id" id="customer_id" required>
                    @foreach($customers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('customer_id') ? old('customer_id') : $accountCertificate->customer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('customer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountCertificate.fields.customer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.accountCertificate.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $accountCertificate->url) }}">
                @if($errors->has('url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountCertificate.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.accountCertificate.fields.share') }}</label>
                @foreach(App\Models\AccountCertificate::SHARE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('share') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="share_{{ $key }}" name="share" value="{{ $key }}" {{ old('share', $accountCertificate->share) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="share_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('share'))
                    <div class="invalid-feedback">
                        {{ $errors->first('share') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountCertificate.fields.share_helper') }}</span>
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