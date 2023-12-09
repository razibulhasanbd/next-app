@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.targetReachedAccount.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.target-reached-accounts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.id') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.account') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->account->login ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.plan') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->plan->type ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.metric_info') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->metric_info }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.rules_reached') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->rules_reached }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.subscription') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->subscription->account ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.approved_at') }}
                        </th>
                        <td>
                            {{ $targetReachedAccount->approved_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.target-reached-accounts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection