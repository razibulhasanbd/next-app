@extends('layouts.admin')
@section('content')
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
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Trade">
                <thead>
                    <tr>
                        <th>
                            Account ID
                        </th>
                        <th>
                            {{ trans('cruds.account.fields.login') }}
                        </th>
                        <th>
                            Trade count
                        </th>
                        <th>
                            Last Trade
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
@endsection
@section('scripts')
    @parent
    <script>
        let startDate = '';
        let endDate = '';



        function filterByDate() {
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


            startDate = document.getElementById('startDate').value;
            endDate = document.getElementById('endDate').value;
            console.log(startDate);

            if (startDate != null && endDate != null) {
                startDate = startDate;
                endDate = endDate;
            } else if (startDate == null && endDate != null) {
                startDate = endDate;
                endDate = endDate;
            } else if (startDate != null && endDate == null) {
                startDate = startDate;
                endDate = startDate;
            } else {
                startDate = '';
                endDate = '';
            }


            let dtOverrideGlobalsNew = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: ({
                    url: "{{ route('admin.trades.arbitraryTradeReport') }}",
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    }
                }),
                columns: [

                    {
                        data: 'account_id',
                        name: 'account_id'
                    },

                    {
                        data: 'login',
                        name: 'login'
                    },

                    {
                        data: 'trade_count',
                        name: 'trade_count'
                    },
                    {
                        data: 'last_trade',
                        name: 'last_trade'
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
                    url: "{{ route('admin.trades.arbitraryTradeReport') }}",
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    }
                }),
                columns: [

                    {
                        data: 'account_id',
                        name: 'account_id'
                    },

                    {
                        data: 'login',
                        name: 'login'
                    },

                    {
                        data: 'trade_count',
                        name: 'trade_count'
                    },
                    {
                        data: 'last_trade',
                        name: 'last_trade'
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
