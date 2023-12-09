@extends('layouts.admin')
@section('content')
@can('account_create')

@endcan
<div class="card">
    <div class="card-header">
        TopUp Log {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Account">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.account.fields.id') }}
                    </th>
                    <th>
                        Login
                    </th>
                    <th>
                       Name
                    </th>
                    <th>
                        Topup Amount
                    </th>
                    <th>
                       Time
                    </th>
                    <th>
                        BreachEquity
                    </th>
                    <th>
                        BreachBalance
                    </th>
                    <th>
                        BreachMaxDailyLoss
                    </th>
                    <th>
                        BreachMaxMonthlyLoss
                    </th>
                    <th>
                        LastDayEquity
                    </th>
                    <th>
                        LastDayBalance
                    </th>

                    <th>
                        &nbsp;
                    </th>
                </tr>
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
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>


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
    ajax: "{{ route('admin.accounts.topUpLog') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'account.login', name: 'account.login' },
{ data: 'account.name', name:  'account.name' },
{ data: 'topup_amount', name:  'topup_amount' },
{ data: 'created_at', name:  'created_at' ,"sortable": false},
{ data: 'BreachEquity', name:  'BreachEquity' ,"sortable": false},
{ data: 'BreachBalance', name:  'BreachBalance' ,"sortable": false},
{ data: 'BreachMaxDailyLoss', name:  'BreachMaxDailyLoss' ,"sortable": false},
{ data: 'BreachMaxMonthlyLoss', name:  'BreachMaxMonthlyLoss' ,"sortable": false},
{ data: 'LastDayEquity', name:  'LastDayEquity' ,"sortable": false},
{ data: 'LastDayBalance', name:  'LastDayBalance' ,"sortable": false},


{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Account').DataTable(dtOverrideGlobals);
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
