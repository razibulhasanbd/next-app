@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.trade.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.trades.store") }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.trade.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account_id') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ old('account_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.account_helper') }}</span>
            </div>

           
            <div class="form-group">
                <label class="required" for="close_price">{{ trans('cruds.trade.fields.close_price') }}</label>
                <input class="form-control {{ $errors->has('close_price') ? 'is-invalid' : '' }}" type="number" name="close_price" id="close_price" value="{{ old('close_price', '') }}" step="0.01" required>
                @if($errors->has('close_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('close_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.close_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="close_time">{{ trans('cruds.trade.fields.close_time') }}</label>
                <input class="form-control {{ $errors->has('close_time') ? 'is-invalid' : '' }}" type="number" name="close_time" id="close_time" value="{{ old('close_time', '') }}" step="1" required>
                @if($errors->has('close_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('close_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.close_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="close_time_str">{{ trans('cruds.trade.fields.close_time_str') }}</label>
                <input class="form-control {{ $errors->has('close_time_str') ? 'is-invalid' : '' }}" type="text" name="close_time_str" id="close_time_str" value="{{ old('close_time_str', '') }}" required>
                @if($errors->has('close_time_str'))
                    <div class="invalid-feedback">
                        {{ $errors->first('close_time_str') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.close_time_str_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="commission">{{ trans('cruds.trade.fields.commission') }}</label>
                <input class="form-control {{ $errors->has('commission') ? 'is-invalid' : '' }}" type="number" name="commission" id="commission" value="{{ old('commission', '') }}" step="0.0000000001" required>
                @if($errors->has('commission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('commission') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.commission_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="digits">{{ trans('cruds.trade.fields.digits') }}</label>
                <input class="form-control {{ $errors->has('digits') ? 'is-invalid' : '' }}" type="number" name="digits" id="digits" value="{{ old('digits', '') }}" step="1" required>
                @if($errors->has('digits'))
                    <div class="invalid-feedback">
                        {{ $errors->first('digits') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.digits_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="login">{{ trans('cruds.trade.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number" name="login" id="login" value="{{ old('login', '') }}" step="1" required>
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.login_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lots">{{ trans('cruds.trade.fields.lots') }}</label>
                <input class="form-control {{ $errors->has('lots') ? 'is-invalid' : '' }}" type="number" name="lots" id="lots" value="{{ old('lots', '') }}" step="0.0000000001" required>
                @if($errors->has('lots'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lots') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.lots_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="open_price">{{ trans('cruds.trade.fields.open_price') }}</label>
                <input class="form-control {{ $errors->has('open_price') ? 'is-invalid' : '' }}" type="number" name="open_price" id="open_price" value="{{ old('open_price', '') }}" step="0.0000000001" required>
                @if($errors->has('open_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('open_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.open_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="open_time">{{ trans('cruds.trade.fields.open_time') }}</label>
                <input class="form-control {{ $errors->has('open_time') ? 'is-invalid' : '' }}" type="number" name="open_time" id="open_time" value="{{ old('open_time', '') }}" step="1" required>
                @if($errors->has('open_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('open_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.open_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="open_time_str">{{ trans('cruds.trade.fields.open_time_str') }}</label>
                <input class="form-control {{ $errors->has('open_time_str') ? 'is-invalid' : '' }}" type="text" name="open_time_str" id="open_time_str" value="{{ old('open_time_str', '') }}" required>
                @if($errors->has('open_time_str'))
                    <div class="invalid-feedback">
                        {{ $errors->first('open_time_str') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.open_time_str_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pips">{{ trans('cruds.trade.fields.pips') }}</label>
                <input class="form-control {{ $errors->has('pips') ? 'is-invalid' : '' }}" type="number" name="pips" id="pips" value="{{ old('pips', '') }}" step="0.000001" required>
                @if($errors->has('pips'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pips') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.pips_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="profit">{{ trans('cruds.trade.fields.profit') }}</label>
                <input class="form-control {{ $errors->has('profit') ? 'is-invalid' : '' }}" type="number" name="profit" id="profit" value="{{ old('profit', '') }}" step="0.000001" required>
                @if($errors->has('profit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.profit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reason">{{ trans('cruds.trade.fields.reason') }}</label>
                <input class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" type="number" name="reason" id="reason" value="{{ old('reason', '') }}" step="1" required>
                @if($errors->has('reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reason') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.reason_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sl">{{ trans('cruds.trade.fields.sl') }}</label>
                <input class="form-control {{ $errors->has('sl') ? 'is-invalid' : '' }}" type="number" name="sl" id="sl" value="{{ old('sl', '') }}" step="0.0000000001" required>
                @if($errors->has('sl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.sl_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="state">{{ trans('cruds.trade.fields.state') }}</label>
                <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="number" name="state" id="state" value="{{ old('state', '') }}" step="1" required>
                @if($errors->has('state'))
                    <div class="invalid-feedback">
                        {{ $errors->first('state') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.state_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="swap">{{ trans('cruds.trade.fields.swap') }}</label>
                <input class="form-control {{ $errors->has('swap') ? 'is-invalid' : '' }}" type="number" name="swap" id="swap" value="{{ old('swap', '') }}" step="1" required>
                @if($errors->has('swap'))
                    <div class="invalid-feedback">
                        {{ $errors->first('swap') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.swap_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="symbol">{{ trans('cruds.trade.fields.symbol') }}</label>
                <input class="form-control {{ $errors->has('symbol') ? 'is-invalid' : '' }}" type="text" name="symbol" id="symbol" value="{{ old('symbol', '') }}" required>
                @if($errors->has('symbol'))
                    <div class="invalid-feedback">
                        {{ $errors->first('symbol') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.symbol_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ticket">{{ trans('cruds.trade.fields.ticket') }}</label>
                <input class="form-control {{ $errors->has('ticket') ? 'is-invalid' : '' }}" type="number" name="ticket" id="ticket" value="{{ old('ticket', '') }}" step="1" required>
                @if($errors->has('ticket'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ticket') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.ticket_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="tp">{{ trans('cruds.trade.fields.tp') }}</label>
                <input class="form-control {{ $errors->has('tp') ? 'is-invalid' : '' }}" type="number" name="tp" id="tp" value="{{ old('tp', '') }}" step="0.0000000001" required>
                @if($errors->has('tp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.tp_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.trade.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="number" name="type" id="type" value="{{ old('type', '') }}" step="1" required>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="type_str">{{ trans('cruds.trade.fields.type_str') }}</label>
                <input class="form-control {{ $errors->has('type_str') ? 'is-invalid' : '' }}" type="text" name="type_str" id="type_str" value="{{ old('type_str', '') }}" required>
                @if($errors->has('type_str'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type_str') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.type_str_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="volume">{{ trans('cruds.trade.fields.volume') }}</label>
                <input class="form-control {{ $errors->has('volume') ? 'is-invalid' : '' }}" type="number" name="volume" id="volume" value="{{ old('volume', '') }}" step="1" required>
                @if($errors->has('volume'))
                    <div class="invalid-feedback">
                        {{ $errors->first('volume') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.trade.fields.volume_helper') }}</span>
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