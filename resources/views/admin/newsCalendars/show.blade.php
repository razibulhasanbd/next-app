@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.newsCalendar.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.news-calendars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.id') }}
                        </th>
                        <td>
                            {{ $newsCalendar->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.title') }}
                        </th>
                        <td>
                            {{ $newsCalendar->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.country') }}
                        </th>
                        <td>
                            {{ $newsCalendar->country }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.date') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($newsCalendar->date) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.impact') }}
                        </th>
                        <td>
                            {{ $newsCalendar->impact }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.forecast') }}
                        </th>
                        <td>
                            {{ $newsCalendar->forecast }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.previous') }}
                        </th>
                        <td>
                            {{ $newsCalendar->previous }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.is_restricted') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $newsCalendar->is_restricted ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.news-calendars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
