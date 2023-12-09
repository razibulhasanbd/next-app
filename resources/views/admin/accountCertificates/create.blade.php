@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.accountCertificate.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.account-certificates.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="required" for="account_id">{{ trans('cruds.accountCertificate.fields.account') }}</label>
                    <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id"
                        id="account_id" onchange="accountId(this);" required>
                        @foreach ($accounts as $id => $entry)
                            <option value="{{ $id }}" {{ old('account_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('account'))
                        <div class="invalid-feedback">
                            {{ $errors->first('account') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.accountCertificate.fields.account_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required"
                        for="subscription_id">{{ trans('cruds.accountCertificate.fields.subscription') }}</label>
                    <select class="form-control" name="subscription_id" id="subscription_id" required>
                    </select>
                </div>

                <div class="form-group" id="currentProdfitSection">
                    <label class="required" for="login">Current Profit</label>
                    <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number"
                        name="currentProfit" id="currentProfit" step="0.001">
                    @if ($errors->has('currentProfit'))
                        <div class="invalid-feedback">
                            {{ $errors->first('currentProfit') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.account.fields.login_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required"
                        for="certificate_id">{{ trans('cruds.accountCertificate.fields.certificate') }}</label>
                    <select class="form-control select2 {{ $errors->has('certificate') ? 'is-invalid' : '' }}"
                        name="certificate_id" id="certificate_id" onchange="showDiv('totalShared', this)" required>
                        @foreach ($certificates as $id => $entry)
                            <option value="{{ $id }}" {{ old('certificate_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('certificate'))
                        <div class="invalid-feedback">
                            {{ $errors->first('certificate') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.accountCertificate.fields.certificate_helper') }}</span>
                </div>

                <div class="form-group" id="totalShared">
                    <label class="required" for="login">Total Shared</label>
                    <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="number"
                        name="totalShared" step="0.001">
                    @if ($errors->has('totalShared'))
                        <div class="invalid-feedback">
                            {{ $errors->first('totalShared') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.account.fields.login_helper') }}</span>
                </div>

                <div class="form-group">
                    <div class="card">
                        <div class="card-header">
                            Certificate Data
                        </div>

                        <div class="card-body">
                            <table class="table" id="products_table">
                                <thead>
                                    <tr>
                                        <th>Text In Certificate</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="product0">
                                        <td>

                                            <select name="text[]" class="form-control">
                                                <option value="">-- choose Text --</option>

                                                @foreach ($certificate_text as $key => $text)
                                                    <option value="{{ $key }}">
                                                        {{ $text }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="value[]" class="form-control" step="0.001"
                                                value="" />
                                        </td>
                                    </tr>
                                    <tr id="product1"></tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-12">
                                    <button id="add_row" class="btn btn-default pull-left">+ Add Row</button>
                                    <button id='delete_row' class="pull-right btn btn-danger">- Delete Row</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="form-group">
                    <label class="required"
                        for="certificate_data">{{ trans('cruds.accountCertificate.fields.certificate_data') }}</label>
                    <input class="form-control {{ $errors->has('certificate_data') ? 'is-invalid' : '' }}" type="text"
                        name="certificate_data" id="certificate_data" value="{{ old('certificate_data', '') }}" required>
                    @if ($errors->has('certificate_data'))
                        <div class="invalid-feedback">
                            {{ $errors->first('certificate_data') }}
                        </div>
                    @endif
                    <span
                        class="help-block">{{ trans('cruds.accountCertificate.fields.certificate_data_helper') }}</span>
                </div> --}}
                {{-- <div class="form-group">
                    <label class="required"
                        for="customer_id">{{ trans('cruds.accountCertificate.fields.customer') }}</label>
                    <select class="form-control select2 {{ $errors->has('customer') ? 'is-invalid' : '' }}"
                        name="customer_id" id="customer_id" required>
                        @foreach ($customers as $id => $entry)
                            <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('customer'))
                        <div class="invalid-feedback">
                            {{ $errors->first('customer') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.accountCertificate.fields.customer_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="url">{{ trans('cruds.accountCertificate.fields.url') }}</label>
                    <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text"
                        name="url" id="url" value="{{ old('url', '') }}">
                    @if ($errors->has('url'))
                        <div class="invalid-feedback">
                            {{ $errors->first('url') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.accountCertificate.fields.url_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.accountCertificate.fields.share') }}</label>
                    @foreach (App\Models\AccountCertificate::SHARE_RADIO as $key => $label)
                        <div class="form-check {{ $errors->has('share') ? 'is-invalid' : '' }}">
                            <input class="form-check-input" type="radio" id="share_{{ $key }}" name="share"
                                value="{{ $key }}" {{ old('share', '1') === (string) $key ? 'checked' : '' }}
                                required>
                            <label class="form-check-label" for="share_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @if ($errors->has('share'))
                        <div class="invalid-feedback">
                            {{ $errors->first('share') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.accountCertificate.fields.share_helper') }}</span>
                </div> --}}

                <div class="modal fade bd-example-modal-lg" id="ajaxModelForPhoto" tabindex="-1" role="dialog"
                     data-backdrop="static" data-keyboard="false"  aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Certificate Preview</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <input name="url" type="hidden" id="certificate_img_url">
                            <div class="modal-body text-center" id="dataPhotoElement">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" id="cancel_certificate" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-danger save">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#currentProdfitSection').hide();


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#account_id').on('change', function(e) {
                var account_id = e.target.value;
                $.ajax({
                    url: "{{ route('admin.getAllSubscription') }}",
                    type: "POST",
                    data: {
                        account_id: account_id
                    },
                    success: function(data) {
                        $('#subscription_id').empty();
                        $.each(data.accountGetAllSubs, function(index,
                        subDetails) {
                            $('#subscription_id').append('<option value="' + subDetails
                                .id + '">' + subDetails.sub_createdAt + ' To ' + subDetails.sub_endingAt+ '</option>');
                        })
                    }
                })
            });
        });

        function accountId(sel) {
            var account_id = sel.value;
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.account-certificates.currentProfit') }}",
                data: {
                    account_id: sel.value,
                },
                success: function(data) {
                    $("#currentProdfitSection").show();
                    document.getElementById("currentProfit").value = data.currentProfit;
                }
            });
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".save").click(function(e) {
            e.preventDefault();

            if (!$("select[name=account_id]").val() || !$("select[name=certificate_id]").val()) {
                alert("please Insert suficent data");
                return;
            }
            var account_id = $("select[name=account_id]").val();
            var certificate_id = $("select[name=certificate_id]").val();
            var totalShared = $("input[name=totalShared]").val();
            var currentProfit = $("input[name=currentProfit]").val();
            var textArr = [];
            var val1 = $("select[name='text[]']").map(function() {
                textArr.push($(this).val());
            });

            var textValue = [];
            var val2 = $("input[name='value[]']").map(function() {
                textValue.push($(this).val());
            });



            $.ajax({
                type: 'POST',
                url: "{{ route('admin.account-certificates.preview') }}",
                data: {
                    account_id: account_id,
                    certificate_id: certificate_id,
                    totalShared: totalShared,
                    text: textArr,
                    value: textValue,
                    currentProfit: currentProfit,
                },
                beforeSend: function(){
                    $(".save").prop('disabled', true);
                    $(".save").html('loading..');
                },
                complete: function(){
                    $(".save").prop('disabled', false);
                    $(".save").html("Show");
                },
                success: function(data) {

                    if(data.response == false){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Internal server error',
                        })
                        return false;
                    }
                    if(data.response == 1){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Certificate already exist',
                        })
                        return false;
                    }

                    $('#ajaxModelForPhoto').modal("show");
                    let src = data.response;
                    $("#certificate_img_url").val(src);
                    //add that inside modal
                    $('#dataPhotoElement').html('<img src="'+src+'" class="col-md-12" />').show();
                }


            });
        });

        $("#cancel_certificate").click(function (){

            var certificate_img_url = $("#certificate_img_url").val();
            $.ajax({
                url: "{{ route('admin.account-certificates.delete') }}",
                type: "POST",
                data: {
                    certificate_img_url: certificate_img_url
                },
                success: function(data) {
                }
            })
        })






        function showDiv(divId, element) {

            document.getElementById(divId).style.display = (element.value === 'demoPayout' || element.value ===
                'realPayout') ? 'block' : 'none';
        }

        window.onload = function() {
            document.getElementById("totalShared").style.display = 'none';
        };


        $(document).ready(function() {
            let row_number = 1;
            $("#add_row").click(function(e) {
                e.preventDefault();
                let new_row_number = row_number - 1;
                $('#product' + row_number).html($('#product' + new_row_number).html()).find(
                    'td:first-child');
                $('#products_table').append('<tr id="product' + (row_number + 1) + '"></tr>');
                row_number++;
            });

            $("#delete_row").click(function(e) {
                e.preventDefault();
                if (row_number > 1) {
                    $("#product" + (row_number - 1)).html('');
                    row_number--;
                }
            });
        });
    </script>
@endsection
