@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.modelVarient.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.model-varients.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.modelVarient.fields.id') }}
                        </th>
                        <td>
                            {{ $modelVarient->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.modelVarient.fields.business_model') }}
                        </th>
                        <td>
                            {{ $modelVarient->business_model->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.modelVarient.fields.name') }}
                        </th>
                        <td>
                            {{ $modelVarient->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.modelVarient.fields.is_default') }}
                        </th>
                        <td>
                            {{ App\Models\ModelVarient::IS_DEFAULT_RADIO[$modelVarient->is_default] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.model-varients.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection