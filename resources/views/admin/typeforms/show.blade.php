@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.typeform.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.typeforms.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.id') }}
                        </th>
                        <td>
                            {{ $typeform->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.payments_for') }}
                        </th>
                        <td>
                            {{ $typeform->payments_for }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.funding_package') }}
                        </th>
                        <td>
                            {{ $typeform->funding_package }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.funding_amount') }}
                        </th>
                        <td>
                            {{ $typeform->funding_amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.coupon_code') }}
                        </th>
                        <td>
                            {{ $typeform->coupon_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.payment_method') }}
                        </th>
                        <td>
                            {{ $typeform->payment_method }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.payment_proof') }}
                        </th>
                        <td>
                            @if($typeform->payment_proof)
                                <a href="{{ $typeform->payment_proof }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $typeform->payment_proof }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.paid_amount') }}
                        </th>
                        <td>
                            {{ $typeform->paid_amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.name') }}
                        </th>
                        <td>
                            {{ $typeform->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.email') }}
                        </th>
                        <td>
                            {{ $typeform->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.country') }}
                        </th>
                        <td>
                            {{ $typeform->country }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.login') }}
                        </th>
                        <td>
                            {{ $typeform->login }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.payment_verification') }}
                        </th>
                        <td>
                            {{ App\Models\Typeform::PAYMENT_VERIFICATION_SELECT[$typeform->payment_verification] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.approved_at') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($typeform->approved_at) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.denied_at') }}
                        </th>
                        <td>
                            {{ $typeform->denied_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.typeform.fields.remarks') }}
                        </th>
                        <td>
                            {!! $typeform->remarks !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Reffered By
                        </th>
                        <td>
                            {{ $typeform->reffered_by }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.typeforms.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
