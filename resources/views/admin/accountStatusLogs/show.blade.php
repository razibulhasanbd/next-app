@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.accountStatusLog.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-status-logs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusLog.fields.id') }}
                        </th>
                        <td>
                            {{ $accountStatusLog->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusLog.fields.account') }}
                        </th>
                        <td>
                            {{ $accountStatusLog->account->customer ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusLog.fields.data') }}
                        </th>
                        <td>
                            {!! $accountStatusLog->data !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusLog.fields.old_status') }}
                        </th>
                        <td>
                            {{ $accountStatusLog->old_status->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusLog.fields.new_status') }}
                        </th>
                        <td>
                            {{ $accountStatusLog->new_status->status ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-status-logs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection