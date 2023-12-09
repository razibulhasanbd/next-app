@extends('layouts.admin')
@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-2">
                <section class="panel panel-default">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        Create Payment Method

                    </div>
                        <div class="card-body">

                            <form method="POST" action="{{ route("admin.payment-method.store") }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ trans('cruds.payment_method.fields.payment_method_name') }}  <span style="color: red;">*</span></label>
                                    <input type="text" name="name" value="{{ old("name") }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method"> {{ trans('cruds.payment_method.fields.payment_method_form_type') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="payment_method_form_type" id="payment_method" required>
                                        <option value="">{{ trans('cruds.payment_method.fields.select_payment_method_form_type') }}</option>
                                        @foreach(paymentMethodFormType() as $key => $module)
                                            <option {{ old('payment_method_form_type','') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="country_category">{{ trans('cruds.payment_method.fields.country_category') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="country_category" id="country_category" required>
                                        <option value="">{{ trans('cruds.payment_method.fields.select_country_category') }}</option>
                                        @foreach($paymentCountryCategory as $key => $module)
                                            <option {{ old('country_category','') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="commission">{{ trans('cruds.payment_method.fields.commission') }} <span style="color: red;">*</span></label>
                                    <input type="number" min="0" max="100" name="commission" value="{{ old("commission") }}" class="form-control" required>
                                </div>


                                <div class="form-group payment_address_show">
                                    <label for="address">{{ trans('cruds.payment_method.fields.address') }} <span class="" style="color: red;">*</span></label>
                                    <input type="text" name="address" class="form-control bb-transfer-address-input" value="{{ old("address") }}">
                                </div>

                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.account_number') }} <span style="color: red;">*</span> </label>
                                    <input type="text" name="account_number" value="{{ old("account_number") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.routing_number') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="routing_number" value="{{ old("routing_number") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.account_type') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="account_type" value="{{ old("account_type") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data">{{ trans('cruds.payment_method.fields.iban') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="iban" value="{{ old('iban') }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.swift_code') }}  <span style="color: red;">*</span></label>
                                    <input type="text" name="swift_code" value="{{ old("swift_code") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.bank_name') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="bank_name" value="{{ old("bank_name") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.beneficiary_name') }} <span style="color: red;">*</span></label>
                                    <input type="text" name="beneficiary_name" value="{{ old("beneficiary_name") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.beneficiary_address') }} <span style="color: red;">*</span> </label>
                                    <input type="text" name="beneficiary_address" value="{{ old("beneficiary_address") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group bank_transfer_wrapper">
                                    <label for="data"> {{ trans('cruds.payment_method.fields.beneficiary_email') }}  <span style="color: red;">*</span></label>
                                    <input type="email" name="beneficiary_email" value="{{ old("beneficiary_email") }}" class="form-control bb-transfer-input"/>
                                </div>
                                <div class="form-group payment_address_show">
                                    <label for="qr_code_instructions"> {{ trans('cruds.payment_method.fields.qr_code_instructions') }} </label>
                                    <div id="qr_code_instructions_container">
                                        <div class="input-group mb-2">
                                            <input type="text" name="qr_code_instructions[]" class="form-control">
                                            <div class="input-group-append">
                                                <button class="btn btn-danger remove_qr_code_instruction" type="button"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-info mt-2" id="add_qr_code_instruction">+ Add More</button>
                                </div>

                                <div class="form-group">
                                    <label for="icon">{{ trans('cruds.payment_method.fields.icon') }} <span style="color: red;">*</span></label>
                                    <input type="file" accept="image/*" name="icon" class="form-control-file" value="{{ old('icon') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="remarks">{{ trans('cruds.payment_method.fields.remarks') }}</label>
                                    <textarea name="remarks" rows="2" cols="2" class="form-control">{{ old('remarks') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success">{{ trans('cruds.payment_method.fields.create_payment_method') }}</button>
                            </form>
                        </div>

                </div>
                </section>
            </div>
        </div>
    </div>

@endsection
<style>
    .bank_transfer_wrapper{
        display: none;;
    }
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
