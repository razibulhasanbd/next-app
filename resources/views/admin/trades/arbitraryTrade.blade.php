@extends('layouts.admin')
@section('content')
@can('trade_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
            {{ trans('global.app_csvImport') }}
        </button>
        @include('csvImport.modal', ['model' => 'Trade', 'route' => 'admin.trades.parseCsvImport'])
    </div>
</div>
@endcan
<div class="card">
    <div class="card-header">
        Arbitrary Trade {{ trans('global.list') }}
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
                        Customer Name
                    </th>
                    <th>
                        {{ trans('cruds.trade.fields.login') }}
                    </th>
                    <th>
                        {{ trans('cruds.trade.fields.ticket') }}
                    </th>
                    <th>Time Difference</th>
                    <th>Count</th>
                    <th>Action</th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
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


    $(function () {
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
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.trades.arbitraryTrade') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id', width:'1em' },
{ data: 'account_id', name: 'account_id', width:'1em' },
{ data: 'customer_name', name: 'account_id', width:'1em' },
{ data: 'login', name: 'login', width:'1em' },
{ data: 'ticket', name: 'ticket', width:'1em' },
{ data: 'time_difference', name: 'time_difference', width:'1em' },
{ data: 'count', name: 'count', width:'1em' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    
    pageLength: 25,
  };
  let table = $('.datatable-Trade').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
  
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