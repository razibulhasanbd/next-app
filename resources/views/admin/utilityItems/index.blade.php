@extends('layouts.admin')
@section('content')
@can('utility_item_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.utility-items.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.utilityItem.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'UtilityItem', 'route' => 'admin.utility-items.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.utilityItem.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-UtilityItem">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.utility_category') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.icon_url') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.header') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.description') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.download_file_url') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.youtube_embedded_url') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.youtube_thumbnail_url') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.utilityItem.fields.order_value') }}
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
@can('utility_item_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.utility-items.massDestroy') }}",
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
    ajax: "{{ route('admin.utility-items.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'utility_category_name', name: 'utility_category.name' },
{ data: 'icon_url', name: 'icon_url' },
{ data: 'header', name: 'header' },
{ data: 'description', name: 'description' },
{ data: 'download_file_url',
  name: 'download_file_url',
        mRender: function(data, type, row) {
                    return '<a href='+data+'>View file</a>';
        }
},
{ data: 'youtube_embedded_url', name: 'youtube_embedded_url' },
{ data: 'youtube_thumbnail_url', name: 'youtube_thumbnail_url',
            render: function(data, type, row) {
                    return '<img src='+ (data != null ? data : "") +' width="80px" height="60px">';
        }
},
{ data: 'status', name: 'status' },
{ data: 'order_value', name: 'order_value' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-UtilityItem').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
