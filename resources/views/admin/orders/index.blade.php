@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Orders {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Order">
            <thead>
                <tr>
                    <th  width="10">

                    </th>

                    <th>
                        {{ trans('cruds.order.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.customer_id') }} Email
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.account_id') }}
                    </th>
                     <th>
                        Server Name
                    </th>
                    <th>
                        {{ trans('cruds.plan.title') }} Name
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.coupon_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.order_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.gateway') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.transaction_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.total') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.discount') }}
                    </th>
                    
                    <th>
                        {{ trans('cruds.order.fields.grand_total') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.status') }}
                    </th>
                    
                    <th>
                        Created at
                    </th>
                    <th>
                        Updated at
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach (getTradingServer() as $key => $entry)
                                <option value="{{ $key }}">{{ $entry }}
                            </option>
                        @endforeach

                        </select>

                    </td>
                    <td>

                    </td>
                    <td>
                        {{-- coupon --}}
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach ($coupons as $key => $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach

                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach (\App\Services\Checkout\CheckoutService::type as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach

                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach (paymentGateways() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach

                        </select>
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach (statusArray() as $key => $value)
                                 <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
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
    $(function () {

let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.orders.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'customer.email', name: 'customer.email' },
{ data: 'account.login', name: 'account.login' },
{ data: 'server_name', name: 'server_name' },
{ data: 'jlPlans.name', name: 'jlPlans.name' },
{ data: 'coupon.name', name: 'coupon.name' },
{ data: 'order_type', name: 'order_type' },
{ data: 'gateway', name: 'gateway' },
{ data: 'transaction_id', name: 'transaction_id' },
{ data: 'total', name: 'total' },
{ data: 'discount', name: 'discount' },
{ data: 'grand_total', name: 'grand_total' },
{ data: 'status', name: 'status' , render: function (data, type) {
    if(data == 1) return '<span class="badge bg-success text-white">Enabled</span>';
    else if(data == 2) return '<span class="badge bg-warning text-black">Pending</span>';
    else return '<span class="badge bg-danger text-white">Disabled</span>';
}
},

{ data: 'created_at', name: 'created_at' },
{ data: 'updated_at', name: 'updated_at' },

{ data: 'actions', name: 'actions' },
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Order').DataTable(dtOverrideGlobals);


let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
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
