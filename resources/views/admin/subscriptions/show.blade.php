@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.subscription.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subscriptions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.subscription.fields.id') }}
                        </th>
                        <td>
                            {{ $subscription->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subscription.fields.account') }}
                        </th>
                        <td>
                            {{ $subscription->account->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subscription.fields.login') }}
                        </th>
                        <td>
                            {{ $subscription->login }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subscription.fields.plan') }}
                        </th>
                        <td>
                            {{ $subscription->plan->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subscription.fields.ending_at') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($subscription->ending_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subscriptions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
