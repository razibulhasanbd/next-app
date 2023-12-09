@extends('layouts.admin')
@section('content')
    @foreach (['success', 'warning', 'danger', 'info', 'message'] as $alert)
        @if (Session::has($alert))
            <div class="alert alert-{{ $alert }}" role="alert">{{ Session::get($alert) }}</div>
        @endif
    @endforeach
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.customer.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.customers.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $customer->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $customer->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.phone') }}
                                    </th>
                                    <td>
                                        {{ $customer->phone }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.city') }}
                                    </th>
                                    <td>
                                        {{ $customer->city }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.state') }}
                                    </th>
                                    <td>
                                        {{ $customer->state }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.address') }}
                                    </th>
                                    <td>
                                        {{ $customer->address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.zip') }}
                                    </th>
                                    <td>
                                        {{ $customer->zip }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.country') }}
                                    </th>
                                    <td>
                                        {{ $customer->customerCountry ? $customer->customerCountry->name : '' }}
                                    </td>
                                </tr>
                                @can('user_email_show_hide')
                                    <tr>
                                        <th>
                                            {{ trans('cruds.customer.fields.email') }}
                                        </th>
                                        <td>
                                            {{ $customer->email }}
                                        </td>
                                    </tr>
                                @endcan
                                @can('mt4_password_show_hide')
                                    <tr>
                                        <th>
                                            {{ trans('cruds.customer.fields.password') }}
                                        </th>
                                        <td>
                                            {{ $customer->password }}
                                        </td>
                                    </tr>
                                @endcan
                                <tr>
                                    <th>
                                        Tags
                                    </th>
                                    <td>
                                        @if ($customer->tags != null)
                                            @if ($customer->tags == 0)
                                                <span
                                                    class="badge badge-success">{{ $customerTags[$customer->tags] }}</span>
                                            @elseif($customer->tags == 1)
                                                <span
                                                    class="badge badge-danger">{{ $customerTags[$customer->tags] }}</span>
                                            @elseif($customer->tags == 2)
                                                <span
                                                    class="badge badge-warning">{{ $customerTags[$customer->tags] }}</span>
                                            @endif
                                        @elseif($customer->tags == null)
                                            <span class="badge badge-success">{{ $customerTags[0] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.customers.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="historyModalOpen" data-keyboard="false" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Audit log details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="html_content">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                onclick="remarksModalClose()">Close
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    KYC verification Lists
                    @can('kyc_verification_create')
                        <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-primary"><i
                                class="fa fa-plus"></i> Manual Verification
                        </button>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Modal -->
                    <div class="modal fade  " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.customers.manualKyc') }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Manual KYC Information</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" value="{{ $customer->id }}" name="customer_id">
                                        <input type="hidden" value="{{ $customer->email }}" name="customer_email">
                                        <div class="form-group">
                                            <label for="email">Customer Name:</label>
                                            <input type="text" name="customer_name" class="form-control"
                                                placeholder="Enter customer name" id="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Reason:</label>
                                            <textarea type="text" name="reason" required class="form-control" placeholder="Enter Reason" id="remarks"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped ">
                            <thead>
                                <tr>

                                    <th>
                                        {{ trans('cruds.customer.fields.id') }}
                                    </th>

                                    <th>
                                        Login
                                    </th>
                                    <th>
                                        Veriff id
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        User Agreement
                                    </th>
                                    <th>
                                        Agreement File
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($customer->customerKyc as $item)
                                    <tr>
                                        <th scope="row">{{ $item->id }}</th>
                                        <th scope="row">{{ $item->account->login ?? null }}</th>
                                        <td><a role="button" data-toggle="modal" href="#modal"
                                                id="show_history{{ $item->id }}"
                                                onclick="historyModalOptions({{ $item->id }}, '{{ Str::limit($item->veriff_id, 20) }}')"
                                                style="color:white" type="button" class="badge badge-primary"
                                                class="btn btn-xs btn-primary">{{ Str::limit($item->veriff_id, 20) }}</a>
                                        </td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            @if ($item->user_agreement == 1)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->pdf_path)
                                                <a target="_blank" href="{{ $item->pdf_path }}"
                                                    class="btn btn-info btn-xs">Download</a>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            @if ($customer->approvedCustomerKyc->count() == 0 && $item->approval_status == 0)
                                                @if ($item->user_agreement == 1 && $item->status == 'approved')
                                                    <a id="confirm_btn{{ $item->id }}"
                                                        class="btn btn-success text-white" role="button"
                                                        onclick="approvalStatus({{ $item->id }})">Confirm</a>
                                                @endif
                                            @endif
                                            @if ($item->approval_status == 1)
                                                <h5><span class="badge badge-success badge-sm">Approved</span></h5>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">

            <div class="card">
                <div class="card-header">
                    Customer Account List
                </div>

                <div class="card-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Customer">
                        <thead>
                            <tr>

                                <th>
                                    {{ trans('cruds.customer.fields.id') }}
                                </th>

                                <th>
                                    Login Id
                                </th>

                                @can('mt4_password_show_hide')
                                    <th>
                                        Password
                                    </th>
                                @endcan
                                <th>
                                    Plan Name
                                </th>
                                <th>
                                    Balance
                                </th>
                                <th>
                                    Equity
                                </th>
                                <th>
                                    Breached
                                </th>

                            </tr>

                        </thead>

                        <tbody>
                            @if (isset($customer->accounts))
                                @foreach ($customer->accounts as $row)
                                    <tr>
                                        <th scope="row">1</th>
                                        <td><a class="btn btn-xs btn-primary"
                                                href="{{ url('admin/accounts/' . $row->id) }}">{{ $row->login }}</a></td>
                                        @can('mt4_password_show_hide')
                                            <td>{{ $row->password }}</td>
                                        @endcan
                                        <td>{{ $row->plan->title }}</td>
                                        <td>{{ $row->balance }}</td>
                                        <td>{{ $row->equity }}</td>
                                        <td>{{ $row->breached }}</td>


                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>

        </div>


    @endsection

    @section('scripts')
        @parent

        <script>
            function historyModalOptions(rowId, veriff_id) {
                // loader = '<h3 class="in-page-loader">Loading .. <i class="icofont-layers"></i></h3>';
                loader = 'Loading..';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{!! route('admin.customers.kycInfo') !!}",
                    type: "get",
                    data: {
                        id: rowId
                    },
                    dataType: "html",
                    beforeSend: function() {
                        // before send code
                        var show_history = 'show_history' + rowId
                        document.getElementById(show_history).innerHTML = loader;
                    },
                    success: function(data) {
                        $("#historyModalOpen").modal('show');
                        // success code
                        $("#html_content").html(data);
                        var html_content
                        var show_history = 'show_history' + rowId
                        document.getElementById(show_history).innerHTML = veriff_id;
                    },
                    complete: function(data) {
                        var show_history = 'show_history' + rowId
                        document.getElementById(show_history).innerHTML = veriff_id;
                    },
                    fail: function(data) {
                        // fail code
                    }
                });
            }

            function approvalStatus(rowId) {
                var confirmStatus = confirm("Are you sure?")
                if (!confirmStatus) {
                    return false
                }
                var confirm_btn = 'confirm_btn' + rowId
                loader = 'Loading..';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{!! route('admin.customers.kycApprovalStatus') !!}",
                    type: "POST",
                    data: {
                        id: rowId
                    },
                    beforeSend: function() {
                        document.getElementById(confirm_btn).innerHTML = loader;
                    },
                    success: function(data) {
                        location.reload();
                    },
                    fail: function(data) {
                        // fail code
                    }
                });
            }
        </script>
    @endsection
