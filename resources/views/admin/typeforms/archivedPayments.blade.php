@extends('layouts.admin')

@section('content')
    @can('typeform_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.typeforms.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.typeform.title_singular') }}
                </a>
            </div>
        </div>
    @endcan

    <div class="alert alert-success" style="display:none">
        {{ Session::get('success') }}
    </div>

    <div class="card">
        <div class="card-header">
            Archived Payments
        </div>

        <div class="row card-body">

            <form class="form-inline" method="POST" action="{{ route('admin.typeform.downloadArchivedPayment') }}" enctype="multipart/form-data">

                @csrf
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="startDate">Start date and time</label>
                        <input class="form-control datetime" type="text" name="startDate" id="startDate">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="endDate">End date and time</label>
                        <input class="form-control datetime" type="text" name="endDate" id="endDate">
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <button class="btn btn-danger" type="submit">
                        Download CSV
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Typeform">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.funding_package') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.funding_amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.payments_for') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.coupon_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.country') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.payment_method') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.payment_proof') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.transaction') }} Id
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.paid_amount') }}
                        </th>
                        @can('typeForm_payment_verification')
                        <th>
                            {{ trans('cruds.typeform.fields.payment_verification') }}
                        </th>
                        @endcan
                        @can('typeForm_approved_at')
                        <th>
                            {{ trans('cruds.typeform.fields.approved_at') }}
                        </th>
                        @endcan
                        <th>
                            Submitted at
                         </th>
                         <th>
                            {{ trans('cruds.typeform.fields.denied_at') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.remarks') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.referred_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.typeform.fields.login') }}
                        </th>

                        <th>
                            Make unarchieve
                        </th>
                        <th>

                            &nbsp;
                        </th>

                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" style="" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" style="width:4em" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" style="width:4em" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" style="width:4em" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true" style="width:4em">
                                <option value>{{ trans('global.all') }}</option>
                                <option value="New Account">New Account</option>
                                <option value="Account TopUp Fee">Account TopUp Fee</option>
                                <option value="Account Reset Fee">Account Reset Fee</option>
                            </select>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" style="width:4em" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" style="width:4em" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" style="width:4em" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" style="width:4em" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                            @can('typeForm_approved_at')
                            <td>
                                <select class="search" strict="true" style="width:4em">
                                    <option value>{{ trans('global.all') }}</option>
                                    <option value="0">pending</option>
                                    <option value="1">verified</option>
                                    <option value="2">not verified</option>
                                    <option value="3">duplicate</option>
                                </select>
                            </td>
                        @endcan


                        <td>
                            <input class="search" style="width:4em" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>
                            <input class="search" style="width:4em" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" style="width:4em" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>

                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>

                        <td>
                        </td>


                    </tr>
                </thead>
            </table>
        </div>
    </div>

        <div class="modal fade" id="modalOpen" data-backdrop="static" data-keyboard="false" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" :no-enforce-focus="true">
            <div class="modal-dialog" role="document" style="top: 35%;">
                <div class="modal-content">

                    <div class="modal-body">
                        <h5>Previous amount : <span id="previous_amount" class="badge badge-primary"></span> </h5>
                        <input type="number" id="modalInput" name="modalInput"  onkeydown="return event.keyCode !== 69">
                        <input type="hidden" id="modalInputRow">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="modalClose()">Close</button>
                        <button type="button" class="btn btn-primary" onclick="fundingAmount()"
                            data-dismiss="modal">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="remarksModalOpen" data-backdrop="static" data-keyboard="false" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" :no-enforce-focus="true">
        <div class="modal-dialog" role="document" style="top: 35%;">
            <div class="modal-content">

                <div class="modal-body">
                    <h5>Update Remarks: </h5>
                    <textarea id="newRemarks" rows="4" cols="50"></textarea>
                    <input type="hidden" id="remarksRow">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="remarksModalClose()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="remarksUpdate()"
                        data-dismiss="modal">Update</button>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    <script>

           function updatePaymentStatus(val) {
            if (confirm('Are you sure you want to Change this?')) {
                var status_id =$(val).val().split("/")[0];
                var tr = $(val).parents('tr')[0];
                if(status_id==0){
                    $(tr).css('background-color', '#f9fac8');
                }else if(status_id==1){
                    $(tr).css('background-color', '#d2fac8');
                }
                else if(status_id==2){
                    $(tr).css('background-color', '#9f9f9f');
                }
                else if(status_id==3){
                    $(tr).css('background-color', '#f5a5a5');
                }


                var statusId = $(val).val();
                $.ajax({
                    url: "{{ route('admin.change.typeForm.status') }}",
                    type: "POST",
                    data: {
                        statusId: statusId
                    },
                    success: function(data) {
                    // console.log(val)
                    notApproved = document.getElementsByClassName("notApproved").length;
                    // console.log(notApproved);
                    rowId = $(val).val().split("/")[1];
                    // console.log(rowId);
                    if (notApproved) {

                        if (data.result.payment_verification == '0') {
                            $('#notApproved' + rowId)[0].innerText = 'Pending'
                        } else if (data.result.payment_verification == '1') {
                            $('#notApproved' + rowId)[0].innerText = 'Payment Verified'
                        }else if(data.result.payment_verification == '2'){
                            $('#notApproved' + rowId)[0].innerText = 'Payment Not Verified'
                        }else{
                            $('#notApproved' + rowId)[0].innerText = 'Duplicate'
                        }
                        return
                    }
                        if (data.result.payment_verification == '0') {
                            // pending

                            document.getElementById("denied" + data.result.id).innerHTML = "";
                            document.getElementById("denied" + data.result.id).innerHTML = '<span id="denied'+data.result.id+'" class="label label-info label-many"><button type="button" class="btn btn-warning text-white" value="2/'+data.result.id+'" onclick="updatePaymentStatus(this)">Deny</button></span>';

                            if (data.payments_for == "Account Reset Fee") {

                                if (document.getElementById("reset" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("reset" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("reset" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("reset" + data.result.id).classList.remove("visible");
                                }

                            }

                            if (data.payments_for == "New Account" && data.plan_id == null) {
                                if (document.getElementById("manual" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("manual" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("manual" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("manual" + data.result.id).classList.remove("visible");
                                }
                            }

                            if (data.payments_for == "New Account" && data.plan_id != null) {
                                if (document.getElementById("new" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("new" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("new" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("new" + data.result.id).classList.remove("visible");
                                }


                            }
                            if (data.payments_for == "Account TopUp Fee") {
                                if (document.getElementById("topup" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("topup" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("topup" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("topup" + data.result.id).classList.remove(
                                        "visible");
                                }

                            }


                        } else if (data.result.payment_verification == '1') {
                            // verified
                            document.getElementById("denied" + data.result.id).innerHTML = "";
                            document.getElementById("denied" + data.result.id).innerHTML = '<span id="denied'+data.result.id+'" class="label label-info label-many"><button type="button" class="btn btn-warning text-white" value="2/'+data.result.id+'" onclick="updatePaymentStatus(this)">Deny</button></span>';

                            if (data.payments_for == "Account Reset Fee") {
                                if (document.getElementById("reset" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("reset" + data.result.id).classList.toggle(
                                        "visible");

                                }
                                if (document.getElementById("reset" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("reset" + data.result.id).classList.remove(
                                        "invisible");

                                }
                            }

                            if (data.payments_for == "New Account" && data.plan_id == null) {

                                if (document.getElementById("manual" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("manual" + data.result.id).classList.toggle("visible");

                                }
                                if (document.getElementById("manual" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("manual" + data.result.id).classList.remove(
                                        "invisible");

                                }
                            }

                            if (data.payments_for == "New Account" && data.plan_id != null) {
                                if (document.getElementById("new" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("new" + data.result.id).classList.toggle("visible");

                                }
                                if (document.getElementById("new" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("new" + data.result.id).classList.remove(
                                        "invisible");

                                }
                            }

                            if (data.payments_for == "Account TopUp Fee") {
                                if (document.getElementById("topup" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("topup" + data.result.id).classList.toggle(
                                        "visible");

                                }
                                if (document.getElementById("topup" + data.result.id).classList.contains(
                                        'invisible') == true) {
                                    document.getElementById("topup" + data.result.id).classList.remove(
                                        "invisible");

                                }
                            }


                        } else if (data.result.payment_verification == '2') {

                            document.getElementById("denied" + data.result.id).innerHTML = "";
                            document.getElementById("denied" + data.result.id).innerHTML = data.denied_at;
                            if (data.payments_for == "Account Reset Fee") {

                                if (document.getElementById("reset" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("reset" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("reset" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("reset" + data.result.id).classList.remove(
                                        "visible");
                                }
                            }
                            if (data.payments_for == "New Account" && data.plan_id != null) {
                                if (document.getElementById("new" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("new" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("new" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("new" + data.result.id).classList.remove("visible");
                                }

                            }
                            if (data.payments_for == "New Account" && data.plan_id == null) {
                                if (document.getElementById("manual" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("manual" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("manual" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("manual" + data.result.id).classList.remove("visible");
                                }

                            }

                            if (data.payments_for == "Account TopUp Fee") {
                                if (document.getElementById("topup" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("topup" + data.result.id).classList.toggle(
                                        "invisible");
                                }
                                if (document.getElementById("topup" + data.result.id).classList.contains(
                                        'visible') == true) {
                                    document.getElementById("topup" + data.result.id).classList.remove(
                                        "visible");
                                }
                            }

                        }

                    }
                })
            }
        }

        function modalOptions(modalrow) {
            let fn_amount = document.getElementById('fn_amount' + modalrow).innerText;
            let modalInput = document.getElementById('modalInput');
            modalInput.value = fn_amount;
            let modalInputRow = document.getElementById('modalInputRow');
            modalInputRow.value = modalrow;
            let previous_amount = document.getElementById('previous_amount').innerText = fn_amount;
        }

        function remarksModalOptions(modalrow) {
            let remarks = document.getElementById('remarks' + modalrow).innerText;
            let newRemarks = document.getElementById('newRemarks');
            newRemarks.value = remarks;
            let remarksRow = document.getElementById('remarksRow');
            remarksRow.value = modalrow;

        }

        function modalClose() {
            $('#modalOpen').modal("hide");
        }

        function remarksModalClose() {
            $('#remarksModalOpen').modal("hide");
        }

        function remarksUpdate() {
            let newRemarks = document.getElementById('newRemarks').value;
            let rowId = document.getElementById('remarksRow').value;
            $.ajax({
                headers: {
                    'x-csrf-token': _token
                },
                url: '{{ route('admin.webhook.remarksUpdate') }}',
                method: 'get',
                dataType: 'json',
                data: {
                    "newRemarks": newRemarks,
                    "rowId": rowId
                },

                success: function(responseData) {
                    $('#remarksModalOpen').modal("hide");
                    let newRemarks = document.getElementById('remarks' + rowId);
                    newRemarks.innerHTML = "";
                    newRemarks.innerHTML = responseData.newRemarks;
                    rowId.value = "";
                },
                error: function(responseData) {
                    console.log(responseData);
                }
            });

        }

        function fundingAmount() {
            let modalInput = document.getElementById('modalInput').value;
            let rowId = document.getElementById('modalInputRow').value;
            let modalInputRow = document.getElementById('modalInputRow');
            $.ajax({
                headers: {
                    'x-csrf-token': _token
                },
                url: '{{ route('admin.webhook.fundingAmount') }}',
                method: 'get',
                dataType: 'json',
                data: {
                    "modalInput": modalInput,
                    "rowId": rowId
                },

                success: function(responseData) {
                    $('#modalOpen').modal("hide");
                    let previous_amount = document.getElementById('previous_amount').innerText = "";
                    modalInput.value = "";
                    modalInputRow.value = "";
                    let fn_amount = document.getElementById('fn_amount' + rowId);
                    fn_amount.innerHTML = "";
                    fn_amount.innerHTML = responseData.fn_amount;
                    rowId.value = "";
                },
                error: function(responseData) {
                    console.log(responseData);
                    Swal.fire({
                           position: 'top-end',
                           icon: 'error',
                           title: responseData.responseJSON.message,
                           showConfirmButton: false,
                           timer: 1500
                       })
                }
            });

        }

        let spinner = '<span class="spinner-border spinner-border-sm ml-3" role="status" aria-hidden="true"></span>';
        let newSpan = document.createElement("span");
        let errorSpan = document.createElement("span");

        function webhookReset(val, rowId, type) {

            let btnClicked = document.getElementById(type + rowId);
            newSpan.innerHTML ="";
            newSpan.innerHTML = spinner;
            btnClicked.appendChild(newSpan);
            errorSpan.innerHTML="";

            var brokerNumber = val;
            var note = "webhook reset";
            var type = type;
            $.ajax({
                headers: {
                    'x-csrf-token': _token
                },
                url: '{{ route('admin.webhook.topupReset') }}',
                method: 'post',
                dataType: 'json',
                data: {
                    "brokerNumber": brokerNumber,
                    "note": note,
                    "type": type,
                    "rowId": rowId
                },
                beforeSend: function() {
                    btnClicked.disabled = true;
                    btnClicked.style.opacity = "0.5";
                    },

                success: function(responseData) {
                    btnClicked.classList.add("btn-success");
                    setTimeout(function() {

                        if (btnClicked.hasChildNodes() == true) {
                            btnClicked.removeChild(btnClicked.lastChild);
                            btnClicked.classList.remove("btn-success");
                        }
                    }, 2000);

                    if (responseData.payments_for == "Account TopUp Fee") {
                        let btn_topup = document.getElementById("topup" + responseData.button_id);
                        var approvedAt = document.createElement('span');
                        const newContent = document.createTextNode(responseData.approved_at);
                        btn_topup.parentNode.replaceChild(newContent, btn_topup);
                    }
                    if (responseData.payments_for == "Account Reset Fee") {
                        let btn_topup = document.getElementById("reset" + responseData.button_id);
                        var approvedAt = document.createElement('span');
                        const newContent = document.createTextNode(responseData.approved_at);
                        btn_topup.parentNode.replaceChild(newContent, btn_topup);
                    }
                },
                error: function(responseData) {
                    if(responseData.responseJSON['message2'] != null){
                            errorSpan.innerHTML = " Email not matched!";
                            btnClicked.replaceChild(errorSpan, newSpan);
                            btnClicked.classList.add("btn-danger");

                            setTimeout(function() {

                            if (btnClicked.hasChildNodes() == true) {
                                      btnClicked.removeChild(btnClicked.lastChild);
                                         btnClicked.classList.remove("btn-danger");
                                     }
                                }, 3000);

                            } else{

                            errorSpan.innerHTML="";
                            errorSpan.innerHTML = " Failed !";
                            btnClicked.replaceChild(errorSpan, newSpan);
                            btnClicked.classList.add("btn-danger");
                            setTimeout(function() {

                                if (btnClicked.hasChildNodes() == true) {
                                    btnClicked.removeChild(btnClicked.lastChild);
                                    btnClicked.classList.remove("btn-danger");
                                }
                            }, 3000);

                    }
                },
                complete: function() {
                    btnClicked.disabled = false;
                    btnClicked.style.opacity = "1";
                    },
            });

        }


        // let newSpan = document.createElement("span");
        // let spinner = '<span class="spinner-border spinner-border-sm ml-3" role="status" aria-hidden="true"></span>';
        // let errorSpan = document.createElement("span");

        function webhookForNewAccount(val,type) {

            var id = val;
            let btnClicked = document.getElementById(type + val);
            newSpan.innerHTML ="";
            errorSpan.innerHTML="";
            newSpan.innerHTML = spinner;
            btnClicked.appendChild(newSpan);


            $.ajax({
                headers: {
                    'x-csrf-token': _token
                },
                url: '{{ route('admin.webhook.newAccount') }}',
                method: 'post',
                dataType: 'json',
                data: {
                    "id": id,
                },
                beforeSend: function() {
                    btnClicked.disabled = true;
                    btnClicked.style.opacity = "0.5";
                    },
                success: function(responseData) {
                    btnClicked.classList.add("btn-success");
                    setTimeout(function() {

                        if (btnClicked.hasChildNodes() == true) {
                            btnClicked.removeChild(btnClicked.lastChild);
                            btnClicked.classList.remove("btn-success");
                        }
                    }, 2000);

                    if (responseData.payments_for == "New Account") {
                        let btn_topup = document.getElementById("new" + responseData.button_id);
                        var approvedAt = document.createElement('span');
                        const newContent = document.createTextNode(responseData.approved_at);
                        btn_topup.parentNode.replaceChild(newContent, btn_topup);
                    }

                },
                error: function(responseData) {

                    errorSpan.innerHTML="";
                    errorSpan.innerHTML = " Failed !";
                    btnClicked.replaceChild(errorSpan, newSpan);
                    btnClicked.classList.add("btn-danger");
                    console.log(responseData);
                    setTimeout(function() {

                        if (btnClicked.hasChildNodes() == true) {
                            btnClicked.removeChild(btnClicked.lastChild);
                            btnClicked.classList.remove("btn-danger");
                        }
                    }, 2000);

                },
                complete: function() {
                    btnClicked.disabled = false;
                    btnClicked.style.opacity = "1";
                    },
            });

        }


        // let newSpan = document.createElement("span");
        // let spinner = '<span class="spinner-border spinner-border-sm ml-3" role="status" aria-hidden="true"></span>';
        // let errorSpan = document.createElement("span");

        function manuallyCreated(val, rowId, type) {

                let btnClicked = document.getElementById(type + rowId);

                newSpan.innerHTML ="";
                errorSpan.innerHTML="";
                newSpan.innerHTML = spinner;
                btnClicked.appendChild(newSpan);

                var brokerNumber = val;
                var note = "manually created";
                var type = type;
                $.ajax({
                    headers: {
                        'x-csrf-token': _token
                    },
                    url: '{{ route('admin.manuallyCreated') }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        "id": rowId,
                        "note": note,
                        "type": type
                    },
                    beforeSend: function() {
                    btnClicked.disabled = true;
                    btnClicked.style.opacity = "0.5";
                    },
                    success: function(responseData) {
                        btnClicked.classList.add("btn-success");
                        setTimeout(function() {

                            if (btnClicked.hasChildNodes() == true) {
                                btnClicked.removeChild(btnClicked.lastChild);
                                btnClicked.classList.remove("btn-success");
                            }
                        }, 2000);

                        if (responseData.payments_for == "New Account") {
                            let btn_topup = document.getElementById("manual" + responseData.button_id);
                            var approvedAt = document.createElement('span');
                            const newContent = document.createTextNode(responseData.approved_at);
                            btn_topup.parentNode.replaceChild(newContent, btn_topup);
                        }

                    },
                    error: function(responseData) {

                        errorSpan.innerHTML="";
                        errorSpan.innerHTML = " Failed ! ";
                        btnClicked.replaceChild(errorSpan, newSpan);
                        btnClicked.classList.add("btn-danger");
                        console.log(responseData);
                        setTimeout(function() {

                            if (btnClicked.hasChildNodes() == true) {
                                btnClicked.removeChild(btnClicked.lastChild);
                                btnClicked.classList.remove("btn-danger");
                            }
                        }, 2000);


                    },
                    complete: function() {
                    btnClicked.disabled = false;
                    btnClicked.style.opacity = "1";
                    },
                });

        }

        $(function() {

            let dtOverrideGlobals = {
                buttons: [],
                processing: true,
                serverSide: true,
                retrieve: true,
                scrollY: 500,
                scrollX: true,
                scroller: true,
                aaSorting: [],
                ajax: "{{ route('admin.typeforms.archivedPayments') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder',
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'funding_package',
                        name: 'funding_package'
                    },
                    {
                        data: 'funding_amount',
                        name: 'funding_amount',
                    },
                    {
                        data: 'payments_for',
                        name: 'payments_for'
                    },
                    {
                        data: 'coupon_code',
                        name: 'coupon_code'
                    },
                    {
                        data: 'country',
                        name: 'country'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'payment_proof',
                        name: 'payment_proof',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'transaction_id',
                        name: 'transaction_id'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        mRender: function(data, type, row) {
                            return '<h5><span class="badge badge-secondary" id="fn_amount'+row.id+'">'+ data +' </span></h5>'+"@can('outside_payment_paid_amount')" + '<button type="button" data-toggle="modal" data-target="#modalOpen" class="badge badge-primary" onclick="modalOptions('+row.id+')" >Update</button>'+ "@endcan";
                        }
                    },
                    @can('typeForm_payment_verification')
                        {
                            data: 'payment_verification',
                            name: 'payment_verification'
                        },
                    @endcan
                    @can('typeForm_approved_at')
                        {
                            data: 'approved_at',
                            name: 'approved_at'
                        },
                    @endcan
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'denied_at',
                        name: 'denied_at',
                        mRender: function(data, type, row) {
                            return data !='' ? '<span id="denied'+row.id+'" class="label label-info label-many">'+data+'</span>' : '<span id="denied'+row.id+'" class="label label-info label-many"><button type="button" class="btn btn-warning text-white" value="2/'+row.id+'" onclick="updatePaymentStatus(this)">Deny</button></span>';
                        }
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        mRender: function(data, type, row) {
                            return '<span id="remarks'+row.id+'">'+ data +' </span>'+"@can('outside_payment_remarks')" + '<button type="button" data-toggle="modal" data-target="#remarksModalOpen" class="badge badge-primary" onclick="remarksModalOptions('+row.id+')" >Update</button>'+ "@endcan";
                        }
                    },
                    {
                        data: 'referred_by',
                        name: 'referred_by'
                    },
                    {
                        data: 'login',
                        name: 'login'
                    },

                    {
                        data: 'unarchieve_button',
                        name: 'unarchieve_button',
                    },

                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
                rowCallback: function ( row, data ) {
                if(data.payment_status=="0"){
                    $(row).css('background-color', '#f9fac8');
                }else if(data.payment_status=="1"){
                    $(row).css('background-color', '#d2fac8');
                }
                else if(data.payment_status=="2"){
                    $(row).css('background-color', '#fae0de');
                }
                else if(data.payment_status=="3"){
                    $(row).css('background-color', '#f5a5a5');
                }
            },
            };
            let table = $('.datatable-Typeform').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function() {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value

                let index = $(this).parent().index()
                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index]
                }

                table
                    .column(index)
                    .search(value, strict)
                    .draw()
            });
            table.on('column-visibility.dt', function(e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function(colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        });


    </script>

@endsection
