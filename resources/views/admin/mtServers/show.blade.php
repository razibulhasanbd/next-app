@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.mtServer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mt-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.id') }}
                        </th>
                        <td>
                            {{ $mtServer->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.url') }}
                        </th>
                        <td>
                            {{ $mtServer->url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.login') }}
                        </th>
                        <td>
                            {{ $mtServer->login }}
                        </td>
                    </tr>
                    @can('mt4_password_show_hide')
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.password') }}
                        </th>
                        <td>
                            ********
                        </td>
                    </tr>
                    @endcan
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.server') }}
                        </th>
                        <td>
                            {{ $mtServer->server }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.group') }}
                        </th>
                        <td>
                            {{ $mtServer->group }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.friendly_name') }}
                        </th>
                        <td>
                            {{ $mtServer->friendly_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mtServer.fields.trading_server_type') }}
                        </th>
                        <td>
                            {{ $mtServer->trading_server_type }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mt-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection