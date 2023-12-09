@extends('layouts.admin')
@section('content')
@can('growth_fund_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.growth-funds.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.growthFund.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.growthFund.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-GrowthFund">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.growthFund.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.growthFund.fields.amount') }}
                    </th>
                    <th>
                        {{ trans('cruds.growthFund.fields.date') }}
                    </th>
                    <th>
                        {{ trans('cruds.growthFund.fields.account') }}
                    </th>
                    <th>
                        {{ trans('cruds.account.fields.login') }}
                    </th>
                    <th>
                        {{ trans('cruds.growthFund.fields.subscription') }}
                    </th>
                    <th>
                        {{ trans('cruds.growthFund.fields.fund_type') }}
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
@can('growth_fund_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.growth-funds.massDestroy') }}",
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
    ajax: "{{ route('admin.growth-funds.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'amount', name: 'amount' },
{ data: 'date', name: 'date' },
{ data: 'account_login', name: 'account.login' },
{ data: 'account.login', name: 'account.login' },
{ data: 'subscription_ending_at', name: 'subscription.ending_at' },
{ data: 'fund_type', name: 'fund_type' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-GrowthFund').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection