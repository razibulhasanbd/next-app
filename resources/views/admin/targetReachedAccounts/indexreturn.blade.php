@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <div class="alert alert-success" style="display:none">
                {{ Session::get('success') }}
            </div>
            @include('csvImport.modal', [
                'model' => 'TargetReachedAccount',
                'route' => 'admin.target-reached-accounts.parseCsvImport',
            ])
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.targetReachedAccount.title_singular') }} {{ trans('global.list') }}
        </div>


        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-TargetReachedAccount">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.account.fields.id') }}
                        </th>
                        <th>
                            Account Id
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Account {{ trans('cruds.account.fields.login') }}
                        </th>

                        <th>
                            Customer Email
                        </th>

                        <th>
                            Plan
                        </th>


                        <th>
                            Next Plan Name
                        </th>

                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.approval_category') }}
                        </th>

                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.metric_info') }}

                        </th>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.rules_reached') }}
                        </th>
                        <th>
                            {{ trans('cruds.targetReachedAccount.fields.kyc_status') }}
                        </th>

                        <th>
                            Created_at
                        </th>
                        @can('target_reached_account_confirm_show_hide')
                            <th>
                                Action
                            </th>
                        @endcan
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>

                        </td>

                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>

                        <td>
                        </td>

                        <td>
                        </td>
                        <td>
                        </td>

                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        @can('target_reached_account_confirm_show_hide')
                            <td>
                            </td>
                        @endcan

                    </tr>


                    <div class="modal fade" id="smallModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">

                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Target Reached Account
                                        Activation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="smallBody">
                                    <div class="alert alert-danger" style="display:none">
                                        {{ Session::get('success') }}
                                    </div>
                                    {{-- <form method="POST" action="{{ route('admin.targetReachedAccountsPlanMigrate') }}"> --}}
                                    <form id="CustomerForm" name="CustomerForm" class="form-horizontal">

                                        <div class="form-group">
                                            <label class="required" for="login"
                                                id="show_account_id">{{ trans('cruds.account.fields.login') }}</label>
                                            <input class="form-control" type="hidden" name="account_id" id="account_id"
                                                required>

                                            <input class="form-control" type="hidden" name="tra_id" id="tra_id"
                                                required>

                                        </div>


                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Approval Category:</label>
                                            <input type="text" readonly class="form-control" name="approval_category"
                                                id="approval_category">
                                        </div>

                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Profit:</label>
                                            <input type="number" step="any" class="form-control" name="profit" onkeyup="if(value<0) value=0;" id="profit">
                                        </div>

                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">WithdrawableAmount:</label>
                                            <input type="number" step="any" class="form-control" name="withdrawableAmount" onkeyup="if(value<0) value=0;"
                                                id="withdrawableAmount">
                                        </div>

                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">GrowthFundAmount:</label>
                                            <input type="number" step="any" class="form-control"
                                                name="growthFundAmount" onkeyup="if(value<0) value=0;" id="growthFundAmount">
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check {{ $errors->has('scaleUp') ? 'is-invalid' : '' }}">
                                                <input class="form-check-input" type="checkbox" name="scaleUp"
                                                    id="scaleUp">
                                                <label class="form-check-label" for="scaleUp">Scale Up</label>
                                            </div>
                                        </div>
                                        <div class="form-group" id="scaleUpAmountVisible">
                                            <label for="recipient-name" class="col-form-label">Starting Balance after Scale Up (40% increased)</label>
                                            <input type="number" step="any" onkeyup="if(value<0) value=0;" class="form-control"
                                                name="scaleUpAmount" id="scaleUpAmount">
                                        </div>



                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" id="saveBtn" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#smallModal">Send</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent

    <script>

$('#scaleUpAmountVisible').hide();
        $('#scaleUpAmount').attr("disabled", true);
        const checkbox = document.getElementById('scaleUp')
        checkbox.addEventListener('change', (event) => {
            if (event.currentTarget.checked) {
                $('#scaleUpAmount').attr("disabled", false);
                checkbox.value = "true";
                $('#scaleUpAmountVisible').show();
            } else {
                checkbox.value = "false";
                $('#scaleUpAmount').attr("disabled", true);
                $('#scaleUpAmountVisible').hide();
            }
        });
        $(document).on('click', '#smallButton', function(event) {
            event.preventDefault();
            $('#saveBtn').html('Send');
            $(".alert-danger").css("display", "none");
            $(".alert-danger").val();
            $(".alert-success").css("display", "none");
            $(".alert-danger").val();

            let href = $(this).attr('data-attr');

            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#smallButton').disabled = true;
                    $('#loader').show();
                },
                // return the result
                success: function(response) {
                    if (response.length == 0) {
                        alert("Not found");
                    } else {

                        $('#profit').val(response.profit);
                        $('#account_id').val(response.account_id);
                        $('#tra_id').val(response.tra_id);
                        $('#approval_category').val(response.approval_category);
                        $('#show_account_id').html("Account ID" + response.account_id);
                        $('#withdrawableAmount').val(response.withdrawableAmount);
                        $('#growthFundAmount').val(response.growthFundAmount);
                        $('#scaleUpAmount').val(response.scaleUpAmount);




                    }
                    $('#smallModal').modal("show");


                },
                complete: function() {
                    $('#smallButton').disabled = false;
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        $(function() {

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('account_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.target-reached-accounts.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });
                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')
                            return
                        }
                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan
            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.target-reached-accounts.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'account.id',
                        name: 'account.id'
                    },
                    {
                        data: 'account.customer.name',
                        name: 'account.customer.name'
                    },
                    {
                        data: 'account.login',
                        name: 'account.login'
                    },

                    {
                        data: 'account.customer.email',
                        name: 'account.customer.email'
                    },
                    {
                        data: 'plan.description',
                        name: 'plan.description'
                    },

                    {
                        data: 'plan.next_plan',
                        name: 'plan.next_plan'
                    },

                    {
                        data: 'approval_category.name',
                        name: 'approval_category.name'
                    },
                    {
                        data: 'metric_info',
                        name: 'metric_info'
                    },
                    {
                        data: 'rules_reached',
                        name: 'rules_reached'
                    },
                    {
                        data: 'account.customer.approvedCustomerKyc.status',
                        name: 'account.customer.approvedCustomerKyc.status'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'

                    },
                    @can('target_reached_account_confirm_show_hide')
                        {
                            data: 'approved',
                            name: 'approved'
                        }
                    @endcan
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };
            let table = $('.datatable-TargetReachedAccount').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $(document).on('click', '#denyButton', function(event) {
                let href = $(this).attr('data-attr');
                $(".alert-success").css("display", "none");
                $(".alert-danger").val();
                $(".alert-danger").css("display", "none");
                $(".alert-danger").val();

                $.ajax({
                    url: href,
                    type: "GET",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#denyButton').disabled = true;
                    },
                    success: function(dataResult) {
                        table.draw();
                    },
                    complete: function() {
                        $('#denyButton').disabled = false;
                    },
                    error: function(dataResult) {
                        console.log(dataResult.responseJSON.message);
                    }
                });
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $(".alert-danger").css("display", "none");
                $(".alert-danger").val();

                $(".alert-success").css("display", "none");
                $(".alert-danger").val();

                $.ajax({
                    data: $('#CustomerForm').serialize(),
                    url: "{{ route('admin.targetReachedAccountsPlanMigrate') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#saveBtn').disabled = true;
                    },
                    success: function(dataResult) {
                        document.querySelectorAll('.modal').forEach(item=>item.click())
                        $(".alert-success").css("display", "block");
                        $(".alert-success").html(dataResult.message);
                        table.draw();
                    },
                    complete: function() {
                        $('#saveBtn').disabled = false;
                    },
                    error: function(dataResult) {
                        console.log(dataResult)
                        console.log(dataResult.responseJSON.message);
                        $('#CustomerForm').val('');
                        $(".alert-danger").css("display", "block");
                        $(".alert-danger").html(dataResult.responseJSON.message);
                        $('#smallModal').modal("show");
                        var oTable = $('#datatable-TargetReachedAccount').dataTable();
                        oTable.fnDraw(false);



                    }
                });
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
