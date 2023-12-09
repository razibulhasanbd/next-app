@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Retake Request {{ trans('global.list') }}
        </div>
        <div class="alert alert-success" style="display:none">
            {{ Session::get('success') }}
        </div>

        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if (Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                @endif
            @endforeach
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-TargetReachedAccount">
                    <thead>
                        <tr>
                            <th width="10">
                            </th>
                            <th>
                                {{ trans('cruds.role.fields.id') }}
                            </th>
                            <th>
                                Account Id
                            </th>
                            <th>
                                {{ trans('cruds.targetReachedAccount.fields.plan') }}
                            </th>
                            <th>
                                Login
                            </th>

                            <th>
                                {{ trans('cruds.targetReachedAccount.fields.metric_info') }}
                            </th>
                            <th>
                                {{ trans('cruds.targetReachedAccount.fields.rules_reached') }}
                            </th>
                            <th>
                                Admin Message
                            </th>
                            {{-- <th>
                            {{ trans('cruds.targetReachedAccount.fields.denay') }}
                        </th> --}}
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($retakeRequestList as $key => $list)
                            <tr data-entry-id="{{ $list->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $list->id ?? '' }}
                                </td>
                                <td>
                                    {{ $list->account_id ?? '' }}
                                </td>
                                <td>
                                    {{ $list->plan->description ?? '' }}
                                </td>
                                <td>
                                    {{ $list->account->login }}
                                </td>

                                <td>
                                    @php $metric = json_decode($list->metric_info);@endphp
                                    @foreach ($metric as $key => $result)
                                        <span class="badge badge-info">{{ $key }}: {{ $result }}</span>
                                    @endforeach
                                </td>

                                <td>
                                    @php $rules_reached = json_decode($list->rules_reached);@endphp
                                    @foreach ($rules_reached as $key => $result)
                                        <span class="badge badge-info"> {{ $key }}: {{ $result }}</span>
                                    @endforeach
                                </td>

                                <td>
                                    {{ $list->admin_message }}
                                </td>
                                {{-- <td>
                                @foreach ($role->permissions as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td> --}}
                                @can('target_reached_account_confirm_show_hide')
                                    <td>
                                        @if (isset($list->approved_at))
                                            <button type="button" class="btn btn-primary" data-toggle="modal">Approved</button>
                                        @elseif (isset($list->denied_at))
                                            <button type="button" class="btn btn-outline-success btn-sm" id="retakeModal"
                                                data-toggle="modal" data-attr="{{ route('retakeRequestModal', $list->id) }}"
                                                data-target="#retakeModalShow">Re Confirm</button>
                                            <button class="btn btn-danger mt-2" disabled>
                                                Denied
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-info" id="retakeModal" data-toggle="modal"
                                                data-attr="{{ route('retakeRequestModal', $list->id) }}"
                                                data-target="#retakeModalShow">Confirm</button>

                                            <button type="button" class="btn btn-xs btn-danger" id="retakeDenyModal"
                                                data-toggle="modal"
                                                data-attr="{{ route('retakeDenyRequestModal', $list->id) }}"
                                                data-target="#retakeDenyModalShow">Deny</button>
                                        @endif

                                    </td>
                                @endcan

                            </tr>



                            <div class="modal fade" id="retakeModalShow" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">

                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Retake Approve Admin Message
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="smallBody">
                                            <form method="POST" action="{{ route('approveRetakeRequest') }}">

                                                <div class="form-group">
                                                    <label class="col-form-label" for="account_id"
                                                        id="show_account_id"></label>
                                                    <input class="form-control" type="hidden" name="account_id"
                                                        id="account_id" required>
                                                    <input class="form-control" type="hidden" name="retake_id"
                                                        id="retake_id" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label" for="login" id="show_login_id"></label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="recipient-name" class="col-form-label">Admin
                                                        Message:</label>
                                                    <textarea class="form-control" name="admin_message"></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            {{-- Deny Modal --}}
                            <div class="modal fade" id="retakeDenyModalShow" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">

                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Retake Deny Admin Message</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="smallBody">
                                            <form method="POST" action="{{ route('denyRetakeRequest') }}">

                                                <div class="form-group">
                                                    <label class="col-form-label" for="account_id"
                                                        id="deny_show_account_id"></label>
                                                    <input class="form-control" type="hidden" name="account_id"
                                                        id="deny_account_id" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label" for="login"
                                                        id="deny_show_login_id"></label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="recipient-name" class="col-form-label">Admin
                                                        Message:</label>
                                                    <textarea class="form-control" name="admin_message"></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        // display a modal (small modal)
        $(document).on('click', '#retakeModal', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(response) {
                    if (response.length == 0) {
                        alert("Datensatz-ID nicht gefunden.");
                    } else {
                        $('#show_account_id').html("Account ID:" + response.account_id);
                        $('#show_login_id').html("Login ID:" + response.login);
                        $('#account_id').val(response.account_id);
                        $('#retake_id').val(response.retake_id);
                    }
                    $('#retakeModalShow').modal("show");
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });


        //Deny Modal
        $(document).on('click', '#retakeDenyModal', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(response) {
                    if (response.length == 0) {
                        alert("Datensatz-ID nicht gefunden.");
                    } else {
                        $('#deny_show_account_id').html("Account ID:" + response.account_id);
                        $('#deny_show_login_id').html("Login ID:" + response.login);
                        $('#deny_account_id').val(response.account_id);
                    }
                    $('#retakeDenyModalShow').modal("show");
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });


        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('role_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.roles.massDestroy') }}",
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
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-TargetReachedAccount:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
