@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Add Charge List {{ trans('global.list') }}
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
                            Amount
                        </th>
                        <th>
                            remarks
                        </th>
                    </tr>

                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
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
                        buttons: [],
                        processing: true,
                        serverSide: true,
                        retrieve: true,
                        aaSorting: [],
                        ajax: "{{ route('admin.charges.index') }}",
                        columns: [
                            { data: 'placeholder', name: 'placeholder' },
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
                                    data: 'amount',
                                    name: 'amount'
                                },
                                {
                                    data: 'remarks',
                                    name: 'remarks'
                                },
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 100,
                        };
                        let table = $('.datatable-Order').DataTable(dtOverrideGlobals);
                    });
    </script>
@endsection
