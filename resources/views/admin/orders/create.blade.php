@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} Order for Account
        </div>

        <div class="card-body">
            <div class="col-md-8">

                <span id="field_error"></span>
                <form method="POST" action="{{ route('admin.orders.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="required col-sm-2" for="email">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" id="email" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="required col-sm-2" for="firstname">First Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="firstname" id="first_name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2" for="lastname">Last Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="lastname" id="last_name">
                        </div>
                    </div>

                    <div class="form-group row" id="passwords">
                        <label class="col-sm-2" for="password">Password</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="password" id="password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="required col-sm-2" for="plan">Country</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="country_id" id="country_id" required>
                                @foreach ($countries as $id => $entry)
                                    <option value="{{ $id }}">{{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="required col-sm-2" for="plan">Plan</label>
                        <div class="col-sm-10">
                            <select class="form-control " name="plan_id" id="plan_id" onchange="setTotalPrice()"
                                required>
                                <option value="">Please select</option>
                                @foreach ($plans as $id => $entry)
                                    <option plan_price="{{ $entry->price }}" value="{{ $entry->id }}">{{ $entry->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="required col-sm-2" for="payment_gateway_id">Payment gateway</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="payment_gateway_id" id="payment_gateway_id" required>
                                @foreach ($paymentGatewayId as $id => $entry)
                                    <option value="{{ $id }}">{{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row is_free_account">
                        <label class="col-sm-2" for="coupon_id">Coupon</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="coupon_id" id="coupon_id">
                                @foreach ($coupons as $id => $entry)
                                    <option value="{{ $id }}">{{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label class="col-sm-2 required" for="server_name">Server Name</label>
                        <div class="col-sm-10">
                            <select class="form-control" required name="server_name" id="server_name">
                                <option value="">Please select</option>
                                @foreach (getTradingServer() as $id => $entry)
                                    <option value="{{ $id }}">{{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row is_free_account">
                        <label class="required col-sm-2" for="transaction_id">Transaction ID</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="transaction_id" id="transaction_id" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="required col-sm-2" for="total">Total</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="total" id="total" required readonly>
                            <p class="mt-3 d-none" id="price_summary">Product price = <span id="default_price"></span> |
                                Coupon
                                discount = <span id="discount"></span> | Total = <span id="grand_total"></span> </p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remarks" class="col-sm-2">Remarks</label>
                        <div class="col-sm-10">
                            <textarea class="form-control ckeditor" name="remarks" id="remarks"></textarea>
                        </div>
                    </div>

                    <div class="form-group row justify-content-end">
                        <button class="btn btn-danger col-sm-2" id="submit" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function setTotalPrice(id) {
            var totalPrice = document.getElementById("total");
            var planPrice = document.querySelector("#plan_id");
            totalPrice.value = planPrice.options[planPrice.selectedIndex].getAttribute('plan_price');
            totalPrice.value = totalPrice.value / 100;
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $("#coupon_id").change(function() {
                if ($("#plan_id").val() == 0) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Please select PLan first!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                var coupon_code = $(this).val();
                if (coupon_code == '') {
                    setTotalPrice();
                    $("#price_summary").addClass('d-none');
                    return false;
                }
                var plan_id = $("#plan_id").val();
                var self = $(this);
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.orders.coupon-info') }}",
                    data: {
                        coupon_code: coupon_code,
                        plan_id: plan_id,
                    },
                    beforeSend: function() {
                        $(self).after('<span class="loading_data">Loading...</span>');
                    },
                    complete: function() {
                        $(self).next().hide();
                    },
                    success: function(response) {
                        if (response.status === true) {
                            $("#total").val(response.amount);
                            $("#default_price").html("$" + response.old_amount);
                            $("#grand_total").html("$" + response.amount);
                            $("#discount").html("$" + response.discount);
                            $("#total").prop('readonly', true);
                            $("#price_summary").removeClass('d-none');
                        } else {
                            $("#total").val('');
                            $("#total").prop('readonly', false);
                            $("#default_price").html(0);
                            $("#grand_total").html(0);
                            $("#discount").html(0);
                            $("#price_summary").addClass('d-none');
                        }
                    }
                });
            });
            $("#email").blur(function() {
                var email = $(this).val();
                if (email == '') {
                    return
                }
                if (!isEmail(email)) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Please a valid email address',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return false;
                }
                var self = $(this);
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.orders.customer-info') }}",
                    data: {
                        email: email,
                    },
                    beforeSend: function() {
                        $(self).after('<span class="loading_data">Loading...</span>');
                    },
                    error: function(reject) {
                        Swal.fire({
                            position: 'top-end',
                            title: "Error",
                            html: '<li class="alert alert-danger">' + reject.responseJSON.message + ' </li>',
                            showConfirmButton: false,
                            timer: 4500
                        })
                    },
                    success: function(response) {
                        if (response.status === true) {
                            $("#first_name").val(response.data.first_name);
                            $("#last_name").val(response.data.last_name);
                            $("#country_id").val(response.data.country_id);
                            $("#first_name, #last_name").prop('readonly', true);
                            $("#passwords").addClass('d-none');
                        } else {
                            $("#first_name").val('');
                            $("#last_name").val('');
                            $("#passwords").removeClass('d-none');

                            $("#first_name, #last_name").prop('readonly', false);
                        }
                        $(self).next().hide();
                    }
                });
            });

            function isEmail(email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                return regex.test(email);
            }


            $("#transaction_id").blur(function() {
                var transaction_id = $(this).val();
                if(transaction_id ==''){return}
                var self = $(this);
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.orders.transaction-id') }}",
                    data: {
                        transaction_id: transaction_id,
                    },
                    beforeSend: function() {
                        $(self).after('<span class="loading_data">Loading...</span>');
                    },
                    complete: function() {
                        $(self).next().hide();
                    },
                    success: function(response) {
                        if (response.status === true) {
                            Swal.fire({
                                title: "Warning!",
                                text: response.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok',
                                allowOutsideClick: false
                            })

                        }
                    }
                });
            });

            $("#submit").click(function(e) {
                e.preventDefault();

                var email = $("#email").val();
                var first_name = $("#first_name").val();
                var last_name = $("#last_name").val();
                var password = $("#password").val();
                var country_id = $("select[name=country_id]").val();
                var plan_id = $("select[name=plan_id]").val();
                var coupon_id = $("select[name=coupon_id]").val();
                var transaction_id = $("#transaction_id").val();
                var payment_gateway_id = $("#payment_gateway_id").val();
                var remarks = $("#remarks").val();
                var total = $("#total").val();
                var server_name = $("#server_name").val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.orders.create') }}",
                    data: {
                        email: email,
                        first_name: first_name,
                        last_name: last_name,
                        password: password,
                        country_id: country_id,
                        plan_id: plan_id,
                        coupon_code: coupon_id,
                        transaction_id: transaction_id,
                        payment_gateway_id: payment_gateway_id,
                        remarks: remarks,
                        total: total,
                        server_name: server_name,
                    },
                    beforeSend: function() {
                        $("#submit").prop('disabled', true);
                        $("#submit").html('loading..');
                    },
                    complete: function() {
                        $("#submit").prop('disabled', false);
                        $("#submit").html("Save");
                    },
                    error: function(reject) {
                        if (reject.status === 422) {
                            var result = '';
                            $.each(reject.responseJSON.errors, function(key, item) {

                                result = result.concat('<li class="alert alert-danger">' + item[0] + ' </li><br>');
                            });
                            Swal.fire({
                                position: 'top-end',
                                title: "Error",
                                html: result,
                                showConfirmButton: false,
                                timer: 5500
                            })
                        }else{
                            Swal.fire({
                                position: 'top-end',
                                title: "Error",
                                html: '<li class="alert alert-danger">' + reject.responseJSON.message + ' </li>',
                                showConfirmButton: false,
                                timer: 4500
                            })
                        }
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            window.location = "/admin/orders";
                            // TODO:: need to add some code here
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3500
                            });
                        }
                    }
                });
            });

            $("#payment_gateway_id").change(function (){
                var gateway_id = $(this).val();

                if(gateway_id == 4){ // free account
                    $(".is_free_account").addClass('d-none');
                    $("#transaction_id").val('');
                    $("#coupon_id").val('');
                    $("#coupon_id").trigger("change");
                }else{
                    $(".is_free_account").removeClass('d-none');
                }
            })
        });
    </script>
@endsection
