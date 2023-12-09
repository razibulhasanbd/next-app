@extends('layouts.admin')
@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-2">
                <section class="panel panel-default">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            {{ trans('cruds.payment_method.fields.update_payment_method') }} - {{ $payment_method->name ?? '' }}
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route("admin.payment-method.update",$payment_method) }}" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <div class="form-group">
                                    <label for="name">{{ trans('cruds.payment_method.fields.payment_method_name') }}  <span style="color: red;">*</span></label>
                                    <input type="text" name="name" value="{{ $payment_method->name ?? '' }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method"> {{ trans('cruds.payment_method.fields.payment_method_form_type') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="payment_method_form_type" id="payment_method">
                                        <option value=""> {{ trans('cruds.payment_method.fields.select_payment_method_form_type') }}</option>
                                        @foreach(paymentMethodFormType() as $key => $module)
                                            <option {{ $payment_method->payment_method_form_type == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="country_category">{{ trans('cruds.payment_method.fields.country_category') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="country_category" id="country_category">
                                        <option value=""> {{ trans('cruds.payment_method.fields.select_country_category') }}</option>
                                        @foreach($paymentCountryCategory as $key => $module)
                                            <option {{ $payment_method->country_category == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="commission">{{ trans('cruds.payment_method.fields.commission') }} <span style="color: red;">*</span></label>
                                    <input type="number" min="0" max="100" name="commission" value="{{ $payment_method->commission?? '' }}" class="form-control" required>
                                </div>


                                <div class="form-group payment_address_show" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? 'none': '' }}">
                                    <label for="address">{{ trans('cruds.payment_method.fields.address') }} <span class="" style="color: red;">*</span></label>
                                    <input type="text" name="address" class="form-control bb-transfer-address-input" value="{{ $payment_method->address ?? '' }}">
                                </div>

                                @php
                                if($payment_method->payment_method == 'bank-transfer'){
                                     $bankTransfer = $payment_method->data ? json_decode($payment_method->data): null;
                                }


                                @endphp
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.account_number') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="account_number" value="{{  $bankTransfer->account_number ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.routing_number') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="routing_number" value="{{  $bankTransfer->routing_number ?? '' }}" class="form-control bb-transfer-input" />
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.account_type') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="account_type" value="{{  $bankTransfer->account_type ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data">{{ trans('cruds.payment_method.fields.iban') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="iban" value="{{  $bankTransfer->iban ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.swift_code') }} <span style="color: red;">*</span> </label>
                                    <input type="text" name="swift_code" value="{{  $bankTransfer->swift_code ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.bank_name') }} <span style="color: red;">*</span> </label>
                                    <input type="text" name="bank_name" value="{{  $bankTransfer->bank_name ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.beneficiary_name') }} <span style="color: red;">*</span> </label>
                                    <input type="text" name="beneficiary_name" value="{{  $bankTransfer->beneficiary_name ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.beneficiary_address') }} <span style="color: red;">*</span> </label>
                                    <input type="text" name="beneficiary_address" value="{{  $bankTransfer->beneficiary_address ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? '': 'none' }}">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.beneficiary_email') }}  <span style="color: red;">*</span></label>
                                    <input type="email" name="beneficiary_email" value="{{  $bankTransfer->beneficiary_email ?? '' }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group payment_address_show" style="display: {{ $payment_method->payment_method == 'bank-transfer' ? 'none': '' }}" >
                                    <label for="qr_code_instructions"> {{ trans('cruds.payment_method.fields.qr_code_instructions') }} </label>
                                    <div id="qr_code_instructions_container">
                                        @if($payment_method->qr_code_instructions)
                                            @foreach(json_decode($payment_method->qr_code_instructions, true) as $qr_inst)
                                            <div class="input-group mb-2">
                                                <input type="text" name="qr_code_instructions[]" value="{{ $qr_inst }}" class="form-control">
                                                <div class="input-group-append">
                                                    <button class="btn btn-danger remove_qr_code_instruction" type="button"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-info mt-2" id="add_qr_code_instruction"> + Add More</button>
                                </div>

                                <div class="form-group">
                                    <label for="icon">{{ trans('cruds.payment_method.fields.icon') }} <span style="color: red;">*</span></label>
                                    <div>
                                        <input type="file" accept="image/*" name="icon" class="form-control-file">

                                    </div>
                                    <img height="50" width="50" src="{{ $payment_method->icon }}" alt="Icon" style="display: inline-block !important; float: right; margin-bottom: 2px;">

                                </div>

                                <div class="form-group">
                                    <label for="status">{{ trans('cruds.payment_method.fields.status') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select {{ trans('cruds.payment_method.fields.status') }}</option>
                                        @foreach($paymentMethodStatus as $key => $module)
                                            <option  {{  1 ==  $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label for="remarks">{{ trans('cruds.payment_method.fields.remarks') }}</label>
                                    <textarea name="remarks" rows="2" cols="2" class="form-control">{{  $payment_method->remarks ?? '' }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success">{{ trans('cruds.payment_method.fields.update_payment_method') }}</button>
                            </form>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
<style>

</style>
@section('scripts')
    @parent
    <script>
        $(document).ready(function () {
            // Add new input fields for QR code instructions
            $('#add_qr_code_instruction').click(function () {
                $('#qr_code_instructions_container').append('<div class="input-group mb-2"><input type="text" name="qr_code_instructions[]" class="form-control"><div class="input-group-append"><button class="btn btn-danger remove_qr_code_instruction" type="button"><i class="fa fa-trash"></i></button></div></div>');
            });

            // Remove input fields for QR code instructions
            $('#qr_code_instructions_container').on('click', '.remove_qr_code_instruction', function() {
                $(this).closest('.input-group').remove();
            });

            // Show bank transfer fields if payment method is bank transfer
            if ($('#payment_method').val() === 'bank-transfer') {
                $('#bank_transfer_fields').show();
            }

            $("#payment_method").on("change", function(){

                if($(this).val() == 'bank_transfer'){
                    $(".bank_transfer_wrapper").show();
                    $(".payment_address_show").hide();
                    $(".bb-transfer-address-input").attr('required', false);
                    $(".bb-transfer-input").attr('required', true);
                }else{
                    $(".bank_transfer_wrapper").hide();
                    $(".payment_address_show").show();
                    $(".bb-transfer-address-input").attr('required', true);
                    $(".bb-transfer-input").attr('required', false);
                }

            });

            // Trigger change event on page load
            $('#payment_method').trigger('change');
        });
    </script>
@endsection
