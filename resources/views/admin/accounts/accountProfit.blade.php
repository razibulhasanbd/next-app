@extends('layouts.admin')
@section('content')
    @can('account_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.accounts.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.account.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', [
                    'model' => 'Account',
                    'route' => 'admin.accounts.parseCsvImport',
                ])
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.account.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6>Profit % filter</h6>
                    <input class="search-profit w-50 mb-2" id="min" type="text" placeholder="min">
                    <input class="search-profit w-50" id="max" type="text" placeholder="max">
                    <button id="profit-btn" class="btn btn-success" onclick="filterProfit()">Go</button>
                </div>

            </div>
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
                        <th>
                            {{ trans('cruds.account.fields.login') }}
                        </th>
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
                            Profit %
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
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let routeUrl = "{{ route('admin.accounts.accountProfit') }}";


            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: routeUrl,
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
                    {
                        data: 'login',
                        name: 'login'
                    },
                    {
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
                        data: 'profit',
                        name: 'profit'
                    },

                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
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


        let newTable = '';

        function filterProfit() {

            $('.datatable-Account').DataTable().destroy();

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let maxValue = document.getElementById('max').value;
            let minValue = document.getElementById('min').value;


            if (maxValue != null && minValue != null) {
                let routeUrl = "{{ route('admin.accounts.accountProfit') }}";

                let newTableData = {
                    buttons: dtButtons,
                    processing: true,
                    serverSide: true,
                    retrieve: true,
                    aaSorting: [],
                    ajax: ({
                        url: routeUrl,
                        data: {
                            min: minValue,
                            max: maxValue
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
                        {
                            data: 'login',
                            name: 'login'
                        },
                        {
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
                            data: 'profit',
                            name: 'profit'
                        },

                    ],
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 25,
                };

                newTable = $('.datatable-Account').DataTable(newTableData);
            }


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
    </script>
@endsection
