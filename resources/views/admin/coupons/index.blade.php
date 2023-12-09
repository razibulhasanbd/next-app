@extends('layouts.admin')
@section('content')
@can('coupon_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.coupons.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.coupon.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Coupon', 'route' => 'admin.coupons.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.coupon.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Coupon">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.code') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.expiry_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.max_redemption') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.max_redemption_per_user') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.coupon.fields.amount') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
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
@can('coupon_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.coupons.massDestroy') }}",
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
    ajax: "{{ route('admin.coupons.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'type', name: 'type' },
{ data: 'name', name: 'name' },
{ data: 'code', name: 'code' },
{ data: 'expiry_date', name: 'expiry_date' },
{ data: 'max_redemption', name: 'max_redemption' },
{ data: 'max_redemption_per_user', name: 'max_redemption_per_user' },
{ data: 'status', name: 'status' },
{ data: 'amount', name: 'amount' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Coupon').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
