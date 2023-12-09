@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productDetail.fields.id') }}
                        </th>
                        <td>
                            {{ $productDetail->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productDetail.fields.product') }}
                        </th>
                        <td>
                            {{ $productDetail->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productDetail.fields.title') }}
                        </th>
                        <td>
                            {{ $productDetail->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productDetail.fields.description') }}
                        </th>
                        <td>
                            {!! $productDetail->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productDetail.fields.value') }}
                        </th>
                        <td>
                            {{ $productDetail->value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productDetail.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\ProductDetail::STATUS_RADIO[$productDetail->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection