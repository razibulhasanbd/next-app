@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.analytics.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="mb-3">
            <form class="row" method="get" action="{{ route('admin.analytics.index') }}">
                <div class="col-md-3 col-6 mr-0 pr-0">
                    <input type="date" class="form-control" name="from_date" value="{{ !empty($fromDate)?$fromDate:'' }}" required/>
                </div>
                <div class="col-md-3 col-6 mr-0 pr-0">
                    <input type="date" class="form-control" name="to_date"  value="{{ !empty($toDate)?$toDate:'' }}" required/>
                </div>
                <div class="col-md-2 col-12">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        @if (empty($fromDate) && empty($toDate))
            <p>Last 30 Days Result showing.</p>
        @else
            <p>Result showing from {{ $fromDate }} to {{ $toDate }}.</p>
        @endif

        <table class="table table-bordered table-striped table-hover datatable">
            <thead>
                <tr>
                    <th>{{ trans('cruds.analytics.fields.date') }}</th>
                    <th>{{ trans('cruds.analytics.fields.new_sale_revenue') }}</th>
                    <th>{{ trans('cruds.analytics.fields.new_sale_count') }}</th>
                    <th>{{ trans('cruds.analytics.fields.top_up_revenue') }}</th>
                    <th>{{ trans('cruds.analytics.fields.top_up_count') }}</th>
                    <th>{{ trans('cruds.analytics.fields.reset_revenue') }}</th>
                    <th>{{ trans('cruds.analytics.fields.reset_count') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($getDailyReportData as $sale)
                    <tr>
                        <td>{{ $sale->date }}</td>
                        <td>{{ trans('cruds.analytics.currency_symbol')."".$sale->new_sale_revenue }}</td>
                        <td>{{ $sale->new_sale_count }}</td>
                        <td>{{ trans('cruds.analytics.currency_symbol')."".$sale->top_up_revenue }}</td>
                        <td>{{ $sale->top_up_count }}</td>
                        <td>{{ trans('cruds.analytics.currency_symbol')."".$sale->reset_revenue }}</td>
                        <td>{{ $sale->reset_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="analytics-pagination">
            {{ $getDailyReportData->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>



@endsection
