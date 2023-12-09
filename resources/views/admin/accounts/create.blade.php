@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.account.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.accounts.store") }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="required" for="customer_id">{{ trans('cruds.account.fields.customer') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="customer_id" id="customer_id" required>
                    @foreach($customers as $id => $entry)
                        <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.customer_helper') }}</span>
            </div>

        
            <div class="form-group">
                <label class="required" for="login">{{ trans('cruds.account.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number" name="login" id="login" value="{{ old('login', '') }}" step="1" required>
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.login_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.account.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="text" name="password" id="password" value="{{ old('password', '') }}" required>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.account.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', '') }}" required>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.type_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="plan_id">{{ trans('cruds.account.fields.plan') }}</label>
                <select class="form-control select2 {{ $errors->has('plan_id') ? 'is-invalid' : '' }}" name="plan_id" id="plan_id" required>
                    @foreach($plans as $id => $entry)
                        <option value="{{ $id }}" {{ old('plan_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('plan_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('plan_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.plan_helper') }}</span>
            </div>

            
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.account.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comment">{{ trans('cruds.account.fields.comment') }}</label>
                <input class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}" type="text" name="comment" id="comment" value="{{ old('comment', '') }}">
                @if($errors->has('comment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.comment_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="balance">{{ trans('cruds.account.fields.balance') }}</label>
                <input class="form-control {{ $errors->has('balance') ? 'is-invalid' : '' }}" type="number" name="balance" id="balance" value="{{ old('balance', '') }}" step="0.01" required>
                @if($errors->has('balance'))
                    <div class="invalid-feedback">
                        {{ $errors->first('balance') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.balance_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="equity">{{ trans('cruds.account.fields.equity') }}</label>
                <input class="form-control {{ $errors->has('equity') ? 'is-invalid' : '' }}" type="number" name="equity" id="equity" value="{{ old('equity', '') }}" step="0.01" required>
                @if($errors->has('equity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('equity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.equity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="credit">{{ trans('cruds.account.fields.credit') }}</label>
                <input class="form-control {{ $errors->has('credit') ? 'is-invalid' : '' }}" type="number" name="credit" id="credit" value="{{ old('credit', '') }}" step="0.01" required>
                @if($errors->has('credit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('credit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.credit_helper') }}</span>
            </div>

            <div class="form-group">
                <div class="form-check {{ $errors->has('breached') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="breached" value="0">
                    <input class="form-check-input" type="checkbox" name="breached" id="breached" value="1" {{ old('is_enabled', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="breached">{{ trans('cruds.account.fields.breached') }}</label>
                </div>
                @if($errors->has('breached'))
                    <div class="invalid-feedback">
                        {{ $errors->first('breached') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.breached_helper') }}</span>
            </div>

            
            <div class="form-group">
                <label for="breachedby">{{ trans('cruds.account.fields.breachedby') }}</label>
                <input class="form-control {{ $errors->has('breachedby') ? 'is-invalid' : '' }}" type="text" name="breachedby" id="breachedby" value="{{ old('breachedby', '') }}">
                @if($errors->has('breachedby'))
                    <div class="invalid-feedback">
                        {{ $errors->first('breachedby') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.account.fields.breachedby_helper') }}</span>
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