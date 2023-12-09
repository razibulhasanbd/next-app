@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.coupon.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coupons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.id') }}
                        </th>
                        <td>
                            {{ $coupon->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.type') }}
                        </th>
                        <td>
                            {{ $types[$coupon->type] }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.name') }}
                        </th>
                        <td>
                            {{ $coupon->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.code') }}
                        </th>
                        <td>
                            {{ $coupon->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.description') }}
                        </th>
                        <td>
                            {!! $coupon->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.expiry_date') }}
                        </th>
                        <td>
                            {{ $coupon->expiry_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.max_redemption') }}
                        </th>
                        <td>
                            {{ $coupon->max_redemption }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.max_redemption_per_user') }}
                        </th>
                        <td>
                            {{ $coupon->max_redemption_per_user }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.coupon.fields.amount') }}
                        </th>
                        <td>
                            {{ $coupon->amount }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coupons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
