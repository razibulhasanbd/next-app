@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.traderGame.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.trader-games.update", [$traderGame->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="dashboard_user">{{ trans('cruds.traderGame.fields.dashboard_user') }}</label>
                <input class="form-control {{ $errors->has('dashboard_user') ? 'is-invalid' : '' }}" type="number" name="dashboard_user" id="dashboard_user" value="{{ old('dashboard_user', $traderGame->dashboard_user) }}" step="1" required>
                @if($errors->has('dashboard_user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dashboard_user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.traderGame.fields.dashboard_user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.traderGame.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $traderGame->date) }}" required>
                @if($errors->has('date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.traderGame.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dashboard_email">{{ trans('cruds.traderGame.fields.dashboard_email') }}</label>
                <input class="form-control {{ $errors->has('dashboard_email') ? 'is-invalid' : '' }}" type="email" name="dashboard_email" id="dashboard_email" value="{{ old('dashboard_email', $traderGame->dashboard_email) }}">
                @if($errors->has('dashboard_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dashboard_email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.traderGame.fields.dashboard_email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pnl">{{ trans('cruds.traderGame.fields.pnl') }}</label>
                <input class="form-control {{ $errors->has('pnl') ? 'is-invalid' : '' }}" type="number" name="pnl" id="pnl" value="{{ old('pnl', $traderGame->pnl) }}" step="0.01">
                @if($errors->has('pnl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pnl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.traderGame.fields.pnl_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mental_score">{{ trans('cruds.traderGame.fields.mental_score') }}</label>
                <input class="form-control {{ $errors->has('mental_score') ? 'is-invalid' : '' }}" type="text" name="mental_score" id="mental_score" value="{{ old('mental_score', $traderGame->mental_score) }}">
                @if($errors->has('mental_score'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mental_score') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.traderGame.fields.mental_score_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tactical_score">{{ trans('cruds.traderGame.fields.tactical_score') }}</label>
                <input class="form-control {{ $errors->has('tactical_score') ? 'is-invalid' : '' }}" type="text" name="tactical_score" id="tactical_score" value="{{ old('tactical_score', $traderGame->tactical_score) }}">
                @if($errors->has('tactical_score'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tactical_score') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.traderGame.fields.tactical_score_helper') }}</span>
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