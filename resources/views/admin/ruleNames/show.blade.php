@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ruleName.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rule-names.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ruleName.fields.id') }}
                        </th>
                        <td>
                            {{ $ruleName->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruleName.fields.name') }}
                        </th>
                        <td>
                            {{ $ruleName->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruleName.fields.is_percent') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $ruleName->is_percent ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruleName.fields.condition') }}
                        </th>
                        <td>
                            {{ $ruleName->condition }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rule-names.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection