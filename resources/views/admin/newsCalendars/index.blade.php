@extends('layouts.admin')
@section('content')
    @can('news_calendar_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.news-calendars.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.newsCalendar.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.newsCalendar.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-NewsCalendar">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.country') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.impact') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.is_restricted') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.forecast') }}
                        </th>
                        <th>
                            {{ trans('cruds.newsCalendar.fields.previous') }}
                        </th>
                        <th>
                            &nbsp;
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
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                    <option value="1">Restricted</option>
                                    <option value="0">Unrestrict</option>
                            </select>
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
        $(function() {
                    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                    
                        @can('news_calendar_delete')
                            // let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                            // let deleteButton = {
                            //     text: deleteButtonTrans,
                            //     url: "{{ route('admin.news-calendars.massDestroy') }}",
                            //     className: 'btn-danger',
                            //     action: function(e, dt, node, config) {
                            //         var ids = $.map(dt.rows({
                            //             selected: true
                            //         }).data(), function(entry) {
                            //             return entry.id
                            //         });

                            //         if (ids.length === 0) {
                            //             alert('{{ trans('global.datatables.zero_selected') }}')

                            //             return
                            //         }

                            //         if (confirm('{{ trans('global.areYouSure') }}')) {
                            //             $.ajax({
                            //                     headers: {
                            //                         'x-csrf-token': _token
                            //                     },
                            //                     method: 'POST',
                            //                     url: config.url,
                            //                     data: {
                            //                         ids: ids,
                            //                         _method: 'DELETE'
                            //                     }
                            //                 })
                            //                 .done(function() {
                            //                     location.reload()
                            //                 })
                            //         }
                            //     }
                            // }
                            // dtButtons.push(deleteButton)
                            @endcan

                            @can('show_news_calendar_restricted_button')
                            let publishButtonTrans = 'Restricted'
                            let publishButton = {
                                text: publishButtonTrans,
                                url: "{{ route('admin.news.makeRestricted') }}",
                                className: 'btn-danger',
                                action: function(e, dt, node, config) {
                                    var ids = $.map(dt.rows({
                                        selected: true
                                    }).data(), function(entry) {
                                        return entry.id
                                    });

                                    if (ids.length === 0) {
                                        alert('{{ trans('global.datatables.zero_selected') }}')

                                        return
                                    }

                                    if (confirm('{{ trans('global.areYouSure') }}')) {
                                        $.ajax({
                                                headers: {
                                                    'x-csrf-token': _token
                                                },
                                                method: 'POST',
                                                url: config.url,
                                                data: {
                                                    ids: ids
                                                }
                                            })
                                            .done(function() {
                                                location.reload()
                                            })
                                    }
                                }
                            }
                            dtButtons.push(publishButton)
                            @endcan

                            @can('show_news_calendar_Unrestrict_button')
                            let unRestrictedButtonTrans = 'Unrestrict'
                            let unRestrictedButton = {
                                text: unRestrictedButtonTrans,
                                url: "{{ route('admin.news.makeUnRestricted') }}",
                                className: 'btn-success',
                                action: function(e, dt, node, config) {
                                    var ids = $.map(dt.rows({
                                        selected: true
                                    }).data(), function(entry) {
                                        return entry.id
                                    });

                                    if (ids.length === 0) {
                                        alert('{{ trans('global.datatables.zero_selected') }}')

                                        return
                                    }

                                    if (confirm('{{ trans('global.areYouSure') }}')) {
                                        $.ajax({
                                                headers: {
                                                    'x-csrf-token': _token
                                                },
                                                method: 'POST',
                                                url: config.url,
                                                data: {
                                                    ids: ids
                                                }
                                            })
                                            .done(function() {
                                                location.reload()
                                            })
                                    }
                                }
                            }
                            dtButtons.push(unRestrictedButton)

                            @endcan

                        let dtOverrideGlobals = {
                            buttons: dtButtons,
                            processing: true,
                            serverSide: true,
                            retrieve: true,
                            aaSorting: [],
                            ajax: "{{ route('admin.news-calendars.index') }}",
                            columns: [{
                                    data: 'placeholder',
                                    name: 'placeholder'
                                },
                                {
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'title',
                                    name: 'title'
                                },
                                {
                                    data: 'country',
                                    name: 'country'
                                },
                                {
                                    data: 'date',
                                    name: 'date'
                                },
                                {
                                    data: 'impact',
                                    name: 'impact'
                                },
                                {
                                    data: 'is_restricted',
                                    name: 'is_restricted'
                                },
                                {
                                    data: 'forecast',
                                    name: 'forecast'
                                },
                                {
                                    data: 'previous',
                                    name: 'previous'
                                },
                                {
                                    data: 'actions',
                                    name: '{{ trans('global.actions') }}'
                                }
                            ],
                            orderCellsTop: true,
                            order: [
                                [1, 'desc']
                            ],
                            pageLength: 100,
                        };
                        let table = $('.datatable-NewsCalendar').DataTable(dtOverrideGlobals); $('a[data-toggle="tab"]')
                        .on('shown.bs.tab click', function(e) {
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        });

                        let visibleColumnsIndexes = null; $('.datatable thead').on('input', '.search', function() {
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
                        }); table.on('column-visibility.dt', function(e, settings, column, state) {
                            visibleColumnsIndexes = []
                            table.columns(":visible").every(function(colIdx) {
                                visibleColumnsIndexes.push(colIdx);
                            });
                        })
                    });
    </script>
@endsection
