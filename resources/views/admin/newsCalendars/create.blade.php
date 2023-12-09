@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.newsCalendar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.news-calendars.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.newsCalendar.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.newsCalendar.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', '') }}" required>
                @if($errors->has('country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.newsCalendar.fields.date') }}</label>
                <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="impact">{{ trans('cruds.newsCalendar.fields.impact') }}</label>
                <input class="form-control {{ $errors->has('impact') ? 'is-invalid' : '' }}" type="text" name="impact" id="impact" value="{{ old('impact', '') }}" required>
                @if($errors->has('impact'))
                    <div class="invalid-feedback">
                        {{ $errors->first('impact') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.impact_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="forecast">{{ trans('cruds.newsCalendar.fields.forecast') }}</label>
                <input class="form-control {{ $errors->has('forecast') ? 'is-invalid' : '' }}" type="text" name="forecast" id="forecast" value="{{ old('forecast', '') }}" required>
                @if($errors->has('forecast'))
                    <div class="invalid-feedback">
                        {{ $errors->first('forecast') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.forecast_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="previous">{{ trans('cruds.newsCalendar.fields.previous') }}</label>
                <input class="form-control {{ $errors->has('previous') ? 'is-invalid' : '' }}" type="text" name="previous" id="previous" value="{{ old('previous', '') }}" required>
                @if($errors->has('previous'))
                    <div class="invalid-feedback">
                        {{ $errors->first('previous') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.previous_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_restricted') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="is_restricted" id="is_restricted" value="1" required {{ old('is_restricted', 0) == 1 ? 'checked' : '' }}>
                    <label class="required form-check-label" for="is_restricted">{{ trans('cruds.newsCalendar.fields.is_restricted') }}</label>
                </div>
                @if($errors->has('is_restricted'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_restricted') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.newsCalendar.fields.is_restricted_helper') }}</span>
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