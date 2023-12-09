@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.utilityCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.utility-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $utilityCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $utilityCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityCategory.fields.order_value') }}
                        </th>
                        <td>
                            {{ $utilityCategory->order_value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityCategory.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\UtilityCategory::STATUS_SELECT[$utilityCategory->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.utility-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection