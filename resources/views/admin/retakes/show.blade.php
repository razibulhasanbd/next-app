@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.retake.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.retakes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.retake.fields.id') }}
                        </th>
                        <td>
                            {{ $retake->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.retake.fields.account') }}
                        </th>
                        <td>
                            {{ $retake->account->login ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.retake.fields.subscription') }}
                        </th>
                        <td>
                            {{ $retake->subscription->ending_at ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.retake.fields.retake_count') }}
                        </th>
                        <td>
                            {{ $retake->retake_count }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.retakes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection