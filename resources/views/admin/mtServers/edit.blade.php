@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.mtServer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mt-servers.update", [$mtServer->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="url">{{ trans('cruds.mtServer.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $mtServer->url) }}" required>
                @if($errors->has('url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="login">{{ trans('cruds.mtServer.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="text" name="login" id="login" value="{{ old('login', $mtServer->login) }}" required>
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.login_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.mtServer.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="server">{{ trans('cruds.mtServer.fields.server') }}</label>
                <input class="form-control {{ $errors->has('server') ? 'is-invalid' : '' }}" type="text" name="server" id="server" value="{{ old('server', $mtServer->server) }}" required>
                @if($errors->has('server'))
                    <div class="invalid-feedback">
                        {{ $errors->first('server') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.server_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="group">{{ trans('cruds.mtServer.fields.group') }}</label>
                <input class="form-control {{ $errors->has('group') ? 'is-invalid' : '' }}" type="text" name="group" id="group" value="{{ old('group', $mtServer->group) }}" required>
                @if($errors->has('group'))
                    <div class="invalid-feedback">
                        {{ $errors->first('group') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.group_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="friendly_name">{{ trans('cruds.mtServer.fields.friendly_name') }}</label>
                <input class="form-control {{ $errors->has('friendly_name') ? 'is-invalid' : '' }}" type="text" name="friendly_name" id="friendly_name" value="{{ old('friendly_name', $mtServer->friendly_name) }}" required>
                @if($errors->has('friendly_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('friendly_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.friendly_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="trading_server_type">{{ trans('cruds.mtServer.fields.trading_server_type') }}</label>
                <input class="form-control {{ $errors->has('trading_server_type') ? 'is-invalid' : '' }}" type="text" name="trading_server_type" id="trading_server_type" value="{{ old('trading_server_type', $mtServer->trading_server_type) }}" required>
                @if($errors->has('trading_server_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('trading_server_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.trading_server_type_helper') }}</span>
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