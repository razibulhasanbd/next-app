@extends('layouts.admin')
@section('content')
@can('account_metric_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.account-metrics.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.accountMetric.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'AccountMetric', 'route' => 'admin.account-metrics.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.accountMetric.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AccountMetric">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.account') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.max_daily_loss') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.metric_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.is_active_trading_day') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.trades') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.average_losing_trade') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.average_winning_trade') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.last_balance') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.last_equity') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.last_risk') }}
                    </th>
                    <th>
                        {{ trans('cruds.accountMetric.fields.max_monthly_loss') }}
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
@can('account_metric_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.account-metrics.massDestroy') }}",
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
    ajax: "{{ route('admin.account-metrics.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'account.login', name: 'account.login' },
{ data: 'maxDailyLoss', name: 'maxDailyLoss' },
{ data: 'metricDate ', name: 'metricDate ' },
{ data: 'isActiveTradingDay', name: 'isActiveTradingDay' },
{ data: 'trades', name: 'trades' },
{ data: 'averageLosingTrade', name: 'averageLosingTrade' },
{ data: 'averageWinningTrade', name: 'averageWinningTrade' },
{ data: 'lastBalance', name: 'lastBalance' },
{ data: 'lastEquity', name: 'lastEquity' },
{ data: 'lastRisk', name: 'lastRisk' },
{ data: 'maxMonthlyLoss', name: 'maxMonthlyLoss' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-AccountMetric').DataTable(dtOverrideGlobals);
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