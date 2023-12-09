@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.subscription.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.subscriptions.update", [$subscription->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            {{-- <div class="form-group">
                <label class="required" for="account">{{ trans('cruds.subscription.fields.account') }}</label>
                <input class="form-control {{ $errors->has('account_id') ? 'is-invalid' : '' }}" type="number" name="account" id="account" value="{{ old('account', $subscription->account) }}" step="1" required>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subscription.fields.account_helper') }}</span>
            </div> --}}

            {{-- <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.subscription.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account_id') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $account->account->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subscription.fields.account_helper') }}</span>
            </div> --}}

            <div class="form-group">
                <label class="required" for="login">{{ trans('cruds.subscription.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number" name="login" id="login" value="{{ old('login', $subscription->login) }}" step="1" required>
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subscription.fields.login_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label class="required" for="plan">{{ trans('cruds.subscription.fields.plan') }}</label>
                <input class="form-control {{ $errors->has('plan') ? 'is-invalid' : '' }}" type="number" name="plan" id="plan" value="{{ old('plan', $subscription->plan) }}" step="1" required>
                @if($errors->has('plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subscription.fields.plan_helper') }}</span>
            </div> --}}

            <div class="form-group">
                <label class="required" for="created_at">{{ trans('cruds.subscription.fields.created_at') }}</label>
                <input class="form-control datetime {{ $errors->has('created_at') ? 'is-invalid' : '' }}" type="text" name="created_at" id="created_at" value="{{ old('created_at', $subscription->created_at) }}" required>
                @if($errors->has('created_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('created_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subscription.fields.created_at_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="ending_at">{{ trans('cruds.subscription.fields.ending_at') }}</label>
                <input class="form-control datetime {{ $errors->has('ending_at') ? 'is-invalid' : '' }}" type="text" name="ending_at" id="ending_at" value="{{ old('ending_at', $subscription->ending_at) }}" required>
                @if($errors->has('ending_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ending_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subscription.fields.ending_at_helper') }}</span>
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