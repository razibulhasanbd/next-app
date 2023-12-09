@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.payment_method.fields.country_list') }} : Total-{{ $countries->toArray()['total'] }}

    </div>

    <section class="panel panel-default">

        <form action="{{ route('admin.payment_method.country_list.index')}}" method="GET">
            <div class="row card-body">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_method_sc">{{ trans('cruds.payment_method.fields.country_name') }}</label>
                        <input class="form-control" value="{{ request('name') }}" type="text" name="name" id="name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_method_sc">{{ trans('cruds.payment_method.fields.country_category') }}</label>
                        <select class="form-control" name="country_category" id="payment_method_cc">
                            <option value="">{{ trans('cruds.payment_method.fields.select_country_category') }}</option>
                            @foreach($paymentCountryCategory as $key => $module)
                            <option {{ request('country_category','') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mt-4">
                    <button type="submit" class="btn btn-success" style="padding: 6px 19px;"><i class="fa fa-search"></i></button>
                    <a href="{{ route('admin.payment_method.country_list.index')}}" class="btn btn-danger">Clear</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">

            <table class="table table-striped" id="clients-table">
                @if($countries->count())
                <thead>

                <tr>
                    <th>{{ trans('cruds.payment_method.fields.serial_no') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.name') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.short_name') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.country_category') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.action') }}</th>
                </tr>

                </thead>
                <tbody>

                <?php $i = $countries->toArray()['from'];  ?>
                @foreach ($countries as $key => $country)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $country->name ?? '' }}</td>
                        <td>{{ $country->short_name ?? '' }}</td>

                        <td>
                            @if($country->country_category == 0)
                                NON-OFAC
                            @elseif($country->country_category ==1)
                                OFAC
                            @endif
                        </td>
                        <td>
                            @can("payment_method_country_category_swap")
                            <a href="{{ route('admin.payment_method.country_list.edit', $country->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            @endcan

                        </td>
                    </tr>
                @endforeach

                </tbody>
                @else
                    <tr>
                        <td rowspan="5"> <h5 class="text-center">Not available</h5></td>
                    </tr>
                @endif
            </table>

        </div>
        {{ $countries->withQueryString()->links('pagination::bootstrap-4') }}

    </section>
</div>

@endsection
@section('scripts')
@parent
<script>

</script>
@endsection
