@can('account_certificate_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.account-certificates.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.accountCertificate.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.accountCertificate.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-certificateAccountCertificates">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.certificate') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.account') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.certificate_data') }}
                        </th>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.customer') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.email') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountCertificates as $key => $accountCertificate)
                        <tr data-entry-id="{{ $accountCertificate->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $accountCertificate->id ?? '' }}
                            </td>
                            <td>
                                {{ $accountCertificate->certificate->name ?? '' }}
                            </td>
                            <td>
                                {{ $accountCertificate->account->login ?? '' }}
                            </td>
                            <td>
                                {{ $accountCertificate->certificate_data ?? '' }}
                            </td>
                            <td>
                                {{ $accountCertificate->customer->name ?? '' }}
                            </td>
                            <td>
                                {{ $accountCertificate->customer->email ?? '' }}
                            </td>
                            <td>
                                @can('account_certificate_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.account-certificates.show', $accountCertificate->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('account_certificate_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.account-certificates.edit', $accountCertificate->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('account_certificate_delete')
                                    <form action="{{ route('admin.account-certificates.destroy', $accountCertificate->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('account_certificate_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.account-certificates.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-certificateAccountCertificates:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection