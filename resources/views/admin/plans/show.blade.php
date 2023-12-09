@extends('layouts.admin')
@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ trans('global.show') }} {{ trans('cruds.plan.title') }}
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group">
                                <a class="btn btn-default" href="{{ route('admin.plans.index') }}">
                                    {{ trans('global.back_to_list') }}
                                </a>
                            </div>
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $plan->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.type') }}
                                        </th>
                                        <td>
                                            {{ $plan->type }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.title') }}
                                        </th>
                                        <td>
                                            {{ $plan->title }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.description') }}
                                        </th>
                                        <td>
                                            {{ $plan->description }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.leverage') }}
                                        </th>
                                        <td>
                                            {{ $plan->leverage }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.starting_balance') }}
                                        </th>
                                        <td>
                                            {{ $plan->startingBalance }}K
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.upgrade_threshold') }}
                                        </th>
                                        <td>
                                            {{ $plan->upgrade_threshold }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.liquidate_friday') }}
                                        </th>
                                        <td>
                                            {{ App\Models\Plan::LIQUIDATE_FRIDAY_RADIO[$plan->liquidate_friday] ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.package') }}
                                        </th>
                                        <td>
                                            {{ $plan->package->name ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.server') }}
                                        </th>
                                        <td>
                                            {{ $plan->server->friendly_name ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.duration') }}
                                        </th>
                                        <td>
                                            {{ $plan->duration }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.next_plan') }}
                                        </th>
                                        <td>
                                            {{ $plan->next_plan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.plan.fields.new_account_on_next_plan') }}
                                        </th>
                                        <td>
                                            <input type="checkbox" disabled="disabled"
                                                {{ $plan->new_account_on_next_plan ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ trans('global.show') }} Plan Rules
                    </div>

                    <div class="card-body">
                        <div class="form-group">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Rule Name</th>
                                        <th>Value</th>
                                        <th>Condition</th>
                                </thead>

                                <tbody>
                                    @if (isset($plan->planRule))
                                        @foreach ($plan->planRule as $key => $row)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $row->ruleName['name'] }}</td>
                                                <td>{{ $row['value'] }}</td>
                                                <td>{{ $row->ruleName['condition'] }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.plans.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>




@endsection
