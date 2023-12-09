@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.payment_method.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">

                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.id') }}
                        </th>
                        <td>
                            {{ $payment_method->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.payment_method_name') }}
                        </th>
                        <td>
                            {{ $payment_method->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.payment_method') }}
                        </th>
                        <td>
                            {{ $payment_method->payment_method ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.country_category') }}
                        </th>
                        <td>
                            @if($payment_method->country_category == 0)
                                NON-OFAC
                            @elseif($payment_method->country_category ==1)
                                OFAC
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.commission') }}
                        </th>
                        <td>
                            {{ $payment_method->commission ?? '' }}
                        </td>
                    </tr>
                    @if($payment_method->payment_method != 'bank-transfer')
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.address') }}
                        </th>
                        <td>
                            {{ $payment_method->address ?? '' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.status') }}
                        </th>
                        <td>
                            @if($payment_method->status == 1)
                                <span class="badge badge-success">Active</span>
                            @elseif($payment_method->status == 0)
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.qr_code_instructions') }}
                        </th>
                        <td>
                            @if($payment_method->qr_code_instructions)
                                <ul>
                                    @foreach(json_decode($payment_method->qr_code_instructions, true) as $qr_inst)
                                        <li> {{ $qr_inst }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>

                    @if($payment_method->payment_method_form_type == 'bank_transfer')
                        @php
                            $bankTransfer = $payment_method->data ? json_decode($payment_method->data): null;
                        @endphp
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.account_number') }}
                        </th>
                        <td>
                           {{ $bankTransfer->account_number ?? '' }}
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.routing_number') }}
                        </th>
                        <td>
                           {{ $bankTransfer->routing_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.account_type') }}
                        </th>
                        <td>
                           {{ $bankTransfer->account_type ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.iban') }}
                        </th>
                        <td>
                           {{ $bankTransfer->iban ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.swift_code') }}
                        </th>
                        <td>
                           {{ $bankTransfer->swift_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.bank_name') }}
                        </th>
                        <td>
                           {{ $bankTransfer->bank_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.beneficiary_name') }}
                        </th>
                        <td>
                           {{ $bankTransfer->beneficiary_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.beneficiary_address') }}
                        </th>
                        <td>
                           {{ $bankTransfer->beneficiary_address ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.payment_method.fields.beneficiary_email') }}
                        </th>
                        <td>
                           {{ $bankTransfer->beneficiary_email ?? '' }}
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-dark" href="{{ route('admin.payment-method.index') }}">
                        Back to {{ trans('global.payment_method_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>



@endsection
