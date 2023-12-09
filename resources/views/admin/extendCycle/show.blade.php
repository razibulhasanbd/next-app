@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Subscription Details
                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success text-center">
                        {{ session()->get('success') }}
                    </div>
                @endif

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
                                        {{ $account->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.customer.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $account->customer->name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Email
                                    </th>
                                    <td>
                                        {{ $account->customer->email }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Login
                                    </th>
                                    <td>
                                        {{ $account->login }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Subscription Start Date
                                    </th>
                                    <td>
                                        {{ frontEndTimeConverterView($account->latestSubscription->created_at) ?? "" }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Subscription End Date
                                    </th>
                                    <td>
                                        {{ frontEndTimeConverterView($account->latestSubscription->ending_at) ?? "" }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Change Cycle Week
                                    </th>
                                    <td>
                                        <select
                                            class="form-control select2 {{ $errors->has('account_id') ? 'is-invalid' : '' }}"
                                            name="cycle" id="cycle" required>

                                            <option value="1">1 Week</option>
                                            <option value="2">2 Week</option>
                                            <option value="3">3 Week</option>
                                            <option value="4">4 Week</option>

                                        </select>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.extend-cycle.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6" id="show-cycle">

            <div class="card">
                <div class="card-header">
                    Modify Subscription
                </div>

                <div class="card-body" >
                    <div id="viewExtendCycle">

                    </div>

                </div>
            </div>

        </div>
    @endsection
    @section('scripts')
        <script type='text/javascript'>
            document.getElementById('show-cycle').style.display = 'none';
            $('#cycle').change(function() {
                var week = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.check.extend-cycle.view') }}",
                    data: {
                        week: week,
                        accountId: {{ $account->id }}
                    },
                    success: function(responseData) {
                        let getDataView = responseData.view;
                        console.log(getDataView);
                        document.getElementById('show-cycle').style.display = 'block';
                        $("#viewExtendCycle").html(getDataView);
                    }
                });
            });
        </script>
    @endsection
