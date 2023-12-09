@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.extendCycleLog.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.extend-cycle-logs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.id') }}
                        </th>
                        <td>
                            {{ $extendCycleLog->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.login') }}
                        </th>
                        <td>
                            {{ $extendCycleLog->login->login ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.subcription') }}
                        </th>
                        <td>
                            {{ $extendCycleLog->subcription->account ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.weeks') }}
                        </th>
                        <td>
                            {{ $extendCycleLog->weeks }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.before_subscription') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($extendCycleLog->before_subscription) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.after_subscription') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($extendCycleLog->after_subscription) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.extendCycleLog.fields.account') }}
                        </th>
                        <td>
                            {{ $extendCycleLog->account->login ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.extend-cycle-logs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
