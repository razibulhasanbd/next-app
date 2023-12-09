@extends('layouts.admin')
@section('content')
    @can('trade_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.trades.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.trade.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Trade', 'route' => 'admin.trades.parseCsvImport'])
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.trade.title_singular') }} {{ trans('global.list') }}
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

            <div class="col-md-4 mt-4">
                <button class="btn btn-info" onclick="tradeFilterCal()">Summary</button>
            </div>
        </div>

        <div id="accountForTrade">

        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Trade">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.account') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.open_time_str') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.close_time_str') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.ticket') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.symbol') }}
                        </th>
                        <th>
                            Duration
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.close_price') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.close_time') }}
                        </th>

                        <th>
                            {{ trans('cruds.trade.fields.commission') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.digits') }}
                        </th>

                        <th>
                            {{ trans('cruds.trade.fields.login') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.lots') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.open_price') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.open_time') }}
                        </th>

                        <th>
                            {{ trans('cruds.trade.fields.pips') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.profit') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.reason') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.sl') }}
                        </th>
                        <th>
                            First SL
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.state') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.swap') }}
                        </th>


                        <th>
                            {{ trans('cruds.trade.fields.tp') }}
                        </th>
                        <th>
                            First TP
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.type_str') }}
                        </th>
                        <th>
                            {{ trans('cruds.trade.fields.volume') }}
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

                $('.datatable-Trade').DataTable().destroy();

                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('trade_delete')
                    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                    let deleteButton = {
                        text: deleteButtonTrans,
                        url: "{{ route('admin.trades.massDestroy') }}",
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
                        url: "{{ route('admin.trades.tradesByDateRange') }}",
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
                            data: 'account.login',
                            name: 'account.login'
                        },
                        {
                            data: 'open_time_str',
                            name: 'open_time_str'
                        },
                        {
                            data: 'close_time_str',
                            name: 'close_time_str'
                        },
                        {
                            data: 'ticket',
                            name: 'ticket'
                        },
                        {
                            data: 'symbol',
                            name: 'symbol'
                        },
                        {
                            data: 'duration',
                            name: 'duration'
                        },
                        {
                            data: 'close_price',
                            name: 'close_price'
                        },
                        {
                            data: 'close_time',
                            name: 'close_time'
                        },

                        {
                            data: 'commission',
                            name: 'commission'
                        },
                        {
                            data: 'digits',
                            name: 'digits'
                        },

                        {
                            data: 'login',
                            name: 'login'
                        },
                        {
                            data: 'lots',
                            name: 'lots'
                        },
                        {
                            data: 'open_price',
                            name: 'open_price'
                        },
                        {
                            data: 'open_time',
                            name: 'open_time'
                        },

                        {
                            data: 'pips',
                            name: 'pips'
                        },
                        {
                            data: 'profit',
                            name: 'profit'
                        },
                        {
                            data: 'reason',
                            name: 'reason'
                        },
                        {
                            data: 'sl',
                            name: 'sl'
                        },
                        {
                            data: 'tradeSlFirst',
                            name: 'tradeSlFirst.value'
                        },
                        {
                            data: 'state',
                            name: 'state'
                        },
                        {
                            data: 'swap',
                            name: 'swap'
                        },


                        {
                            data: 'tp',
                            name: 'tp'
                        },
                        {
                            data: 'tradeTPFirst',
                            name: 'tradeTPFirst.value'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'type_str',
                            name: 'type_str'
                        },
                        {
                            data: 'volume',
                            name: 'volume'
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

            } else {
                alert("Fill up both date fileds !!");

            }


        }




        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('trade_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.trades.massDestroy') }}",
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

        function tradeFilterCal() {
            let start_Date = '';
            let end_Date   = '';
            let startDate  = document.getElementById('startDate').value;
            let endDate    = document.getElementById('endDate').value;

            const endDay    = new Date(endDate);
            let   getEndDay = endDay.getDate()

            const startDay      = new Date(startDate);
            let   getStartDay   = startDay.getDate()
            var   differenceDay = getEndDay-getStartDay
            if(differenceDay > 2){
                alert('Please select max 2 days!');
                return;

            }


            if (startDate != '' && endDate != '') {
                start_Date = startDate;
                end_Date   = endDate;

                $.ajax({
                    url: "{{ route('admin.trades.tradesFilterCal') }}",
                    type: 'get',
                    data: {
                        "startDate": start_Date,
                        "endDate": end_Date
                    },
                    success: function(dataResult) {
                        let getDataView = dataResult.view;
                        $("#accountForTrade").html(getDataView);
                    }
                });
            } else {
                alert("Fill up both date fileds !!");

            }
        }
    </script>
@endsection
