@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.planRule.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.plan-rules.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.planRule.fields.id') }}
                        </th>
                        <td>
                            {{ $planRule->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.planRule.fields.rule_name') }}
                        </th>
                        <td>
                            {{ $planRule->ruleName->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.planRule.fields.plan') }}
                        </th>
                        <td>
                            {{ $planRule->plan->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.planRule.fields.value') }}
                        </th>
                        <td>
                            {{ $planRule->value }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.plan-rules.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection