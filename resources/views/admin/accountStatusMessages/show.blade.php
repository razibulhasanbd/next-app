@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.accountStatusMessage.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-status-messages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusMessage.fields.id') }}
                        </th>
                        <td>
                            {{ $accountStatusMessage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountStatusMessage.fields.message') }}
                        </th>
                        <td>
                            {!! $accountStatusMessage->message !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-status-messages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection