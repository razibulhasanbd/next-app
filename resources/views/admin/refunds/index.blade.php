@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Refund {{ trans('global.list') }}
        </div>

        @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Order">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Id</th>
                        <th>
                            Transection Id
                        </th>
                        <th>
                            User
                        </th>
                        <th>Order Id</th>
                        <th>
                        Status
                    </th>
                        <th>
                            Amount
                        </th>
                        <th>
                            Comment
                        </th>
                        <th>
                            Reply Comment
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>

                    <tr>
                        <td>
                        </td>

                        <td>
                        </td>
                        <td>
                        </td>
                        <td></td>
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


                    let dtOverrideGlobals = {
                        buttons: dtButtons,
                        processing: true,
                        serverSide: true,
                        retrieve: true,
                        aaSorting: [],
                        ajax: "{{ route('admin.refunds.index') }}",
                        columns: [{
                                data: 'placeholder',
                                name: 'placeholder'
                            },
                            {
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'order.transaction_id',
                                name: 'order.transaction_id'
                            },
                            {
                                data: 'user.name',
                                name: 'user.name'
                            },
                            {
                                data: 'order_id',
                                name: 'order_id'
                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function(data, type) {
                                    if (data == 1)
                                    return '<span class="badge bg-success text-white">Approved</span>';
                                    else if (data == 2)
                                    return '<span class="badge bg-warning text-black">Rejected</span>';
                                    else return '<span class="badge bg-danger text-white">Pending</span>';
                                }
                            },
                                {
                                    data: 'amount',
                                    name: 'amount'
                                },
                                {
                                    data: 'comment',
                                    name: 'comment'
                                },
                                {
                                    data: 'reply_comment',
                                    name: 'reply_comment'
                                },
                                // { data: 'created_at', name: 'created_at' },
                                // { data: 'updated_at', name: 'updated_at' },

                                {
                                    data: 'actions',
                                    name: 'actions'
                                },
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 100,
                        };
                        let table = $('.datatable-Order').DataTable(dtOverrideGlobals);


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
