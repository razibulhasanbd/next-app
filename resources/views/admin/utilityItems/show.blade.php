@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.utilityItem.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.utility-items.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.id') }}
                        </th>
                        <td>
                            {{ $utilityItem->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.utility_category') }}
                        </th>
                        <td>
                            {{ $utilityItem->utility_category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.icon_url') }}
                        </th>
                        <td>
                            {{ $utilityItem->icon_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.header') }}
                        </th>
                        <td>
                            {{ $utilityItem->header }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.description') }}
                        </th>
                        <td>
                            {{ $utilityItem->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.download_file_url') }}
                        </th>
                        <td>
                            {{ $utilityItem->download_file_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.youtube_embedded_url') }}
                        </th>
                        <td>
                            {{ $utilityItem->youtube_embedded_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.youtube_thumbnail_url') }}
                        </th>
                        <td>
                            {{ $utilityItem->youtube_thumbnail_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\UtilityItem::STATUS_SELECT[$utilityItem->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.utilityItem.fields.order_value') }}
                        </th>
                        <td>
                            {{ $utilityItem->order_value }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.utility-items.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
