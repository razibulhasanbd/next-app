@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.account.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="row card-body">
            <div class="col-md-2 mt-4">
                <button class="btn btn-success" onclick="profitFilter()">Accounts in profit</button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Trade">
                <thead>
                    <tr>

                        <th>
                            Customer Name
                        </th>
                        <th>
                            Customer Email
                        </th>
                        <th>
                            Accounts Login
                        </th>
                        <th>
                            Plan
                        </th>
                        <th>
                            Trades Reason
                        </th>
                        <th>
                            EA Trades Count
                        </th>


                    </tr>
                    <tr>

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

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
       function profitFilter() {
            $('.datatable-Trade').DataTable().destroy();

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('trade_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.trades.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                return entry.id
                });
                if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}')
                return
                }
                if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: config.url,
                data: { ids: ids, _method: 'DELETE' }})
                .done(function () { location.reload() })
                }
                }
                }
            @endcan

            let dtOverrideGlobalsNew = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: ({
                    url: "{{ route('admin.trades.EATrades') }}",
                    data: {
                        profitFilter: 'profitFilter',
                    }
                }),
                columns: [


                    {
                        data: 'customers.name',
                        name: 'customers.name'
                    },

                    {
                        data: 'customers.email',
                        name: 'customers.email'
                    },

                    {
                        data: 'accounts.login',
                        name: 'accounts.login'
                    },
                    {
                        data: 'plans.title',
                        name: 'plans.title'
                    },
                    {
                        data: 'trades.reason',
                        name: 'trades.reason'
                    },
                    {
                        data: 'tradeCount',
                        name: 'tradeCount'
                    },

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

            let newTable = $('.datatable-Trade').DataTable(dtOverrideGlobalsNew);



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
        }


        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('trade_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.trades.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                return entry.id
                });
                if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}')
                return
                }
                if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                headers: {'x-csrf-token': _token},
                method: 'POST',
                url: config.url,
                data: { ids: ids, _method: 'DELETE' }})
                .done(function () { location.reload() })
                }
                }
                }
            @endcan


            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: ({
                    url: "{{ route('admin.trades.EATrades') }}",
                }),
                columns: [


                    {
                        data: 'customers.name',
                        name: 'customers.name'
                    },

                    {
                        data: 'customers.email',
                        name: 'customers.email'
                    },

                    {
                        data: 'accounts.login',
                        name: 'accounts.login'
                    },
                    {
                        data: 'plans.title',
                        name: 'plans.title'
                    },
                    {
                        data: 'trades.reason',
                        name: 'trades.reason'
                    },
                    {
                        data: 'tradeCount',
                        name: 'tradeCount'
                    },

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

            let table = $('.datatable-Trade').DataTable(dtOverrideGlobals);
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
