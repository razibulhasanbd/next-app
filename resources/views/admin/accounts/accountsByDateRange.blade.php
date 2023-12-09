@extends('layouts.admin')
@section('content')
    @can('allow_real_account_csv_download')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.accounts.downloadExpressReal') }}">
                    Express Real
                </a>
                <a class="btn btn-success" href="{{ route('admin.accounts.downloadEvReal') }}">
                    Evaluation Real
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.account.title_singular') }} {{ trans('global.list') }}
        </div>


        <div class="row card-body">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="startDate">Start date and time</label>
                    <input class="form-control datetime" type="text" name="" id="startDate">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="endDate">End date and time</label>
                    <input class="form-control datetime" type="text" name="" id="endDate">
                </div>
            </div>
            <div class="col-md-2 mt-4">
                <button class="btn btn-success" onclick="filterByDate()">Go</button>
            </div>
        </div>


        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Account">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.account.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.customer') }}
                        </th>
                        @can('user_email_show_hide')
                            <th>
                                Customer Email
                            </th>
                        @endcan

                        <th>
                            {{ trans('cruds.account.fields.login') }}
                        </th>
                        <th>

                            Parent Account
                        </th>
                        @can('mt4_password_show_hide')
                            <th>
                                {{ trans('cruds.account.fields.password') }}
                            </th>
                        @endcan

                        <th>
                            {{ trans('cruds.account.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.plan') }}
                        </th>

                        <th>
                            {{ trans('cruds.account.fields.balance') }}
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.equity') }}
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.pnl') }}
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.breached') }}
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.breachedby') }}
                        </th>
                        <th>
                            Trading Server Type
                        </th>
                        <th>
                            Subscription End Date
                        </th>
                        <th>
                            Creation Date
                        </th>
                        <th>
                            &nbsp;
                        </th>
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
                        @can('user_email_show_hide')
                            <td>
                                <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                            </td>
                        @endcan
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        @can('mt4_password_show_hide')
                            <td></td>
                        @endcan

                        <td>
                        </td>

                        <td>
                        </td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
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
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        function filterByDate() {
            let start_Date = '';
            let end_Date = '';

            let startDate = document.getElementById('startDate').value;
            let endDate = document.getElementById('endDate').value;

            if (startDate != '' && endDate != '') {
                start_Date = startDate;
                end_Date = endDate;

            $('.datatable-Account').DataTable().destroy();
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('account_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.accounts.massDestroy') }}",
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



            let dtOverrideGlobalsNew = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,

                retrieve: true,
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000, 1500, -1],
                    [10, 25, 50, 100, 500, 1000, 1500, "All"]
                ],

                aaSorting: [],
                ajax: ({
                    url: "{{ route('admin.accounts.accountsByDateRange') }}",
                    data: {
                        "startDate": start_Date,
                        "endDate": end_Date
                    }
                }),
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer.name',
                        name: 'customer.name'
                    },
                    @can('user_email_show_hide')
                        {
                            data: 'customer.email',
                            name: 'customer.email'
                        },
                    @endcan {
                        data: 'login',
                        name: 'login'
                    },
                    {
                        data: 'parent_account_id',
                        name: 'parent_account_id'
                    },
                    @can('mt4_password_show_hide')
                        {
                            data: 'password',
                            name: 'password'
                        },
                    @endcan {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'plan.title',
                        name: 'plan.title',
                        width: '1em'
                    },
                    {
                        data: 'balance',
                        name: 'balance',
                        width: '1em'
                    },
                    {
                        data: 'equity',
                        name: 'equity'
                    },
                    {
                        data: 'pnl',
                        name: 'pnl'
                    },
                    {
                        data: 'breached',
                        name: 'breached'
                    },
                    {
                        data: 'breachedby',
                        name: 'breachedby'
                    },
                    {
                        data: 'trading_server_type',
                        name: 'trading_server_type'
                    },
                    {
                        data: 'latestSubscription',
                        name: 'latestSubscription.ending_at'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                columnDefs: [{
                    targets: 'sort',
                    orderable: true
                }],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
            };

            let newTable = $('.datatable-Account').DataTable(dtOverrideGlobalsNew);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexesNew = null;
            $('.datatable thead').on('input', '.search', function() {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value
                let index = $(this).parent().index()
                if (visibleColumnsIndexesNew !== null) {
                    index = visibleColumnsIndexesNew[index]
                }
                newTable
                    .column(index)
                    .search(value, strict)
                    .draw()
            });

            newTable.on('column-visibility.dt', function(e, settings, column, state) {
                visibleColumnsIndexesNew = []
                newTable.columns(":visible").every(function(colIdx) {
                    visibleColumnsIndexesNew.push(colIdx);
                });
            });

        } else {
                alert("Fill up both date fileds !!");

            }


        }


        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('account_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.accounts.massDestroy') }}",
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
                buttons: [],
                processing: false,
                serverSide: false,
                retrieve: false,
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000, 1500, -1],
                    [10, 25, 50, 100, 500, 1000, 1500, "All"]
                ],
                aaSorting: [],
            };
            let table = $('.datatable-Account').DataTable(dtOverrideGlobals);
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
