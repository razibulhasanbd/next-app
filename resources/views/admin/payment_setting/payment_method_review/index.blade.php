@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header text-primary">
        {{ trans('global.payment_method_list_waiting_for_approval') }}  : Total-{{ $payment_methods->toArray()['total'] }}

    </div>

    <section class="panel panel-default">

        <form action="{{ route('admin.payment-method-review.index')}}" method="GET">
            <div class="row card-body">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.payment_method.fields.name') }}</label>
                        <input type="text" class="form-control" value="{{ request('name') }}" name="name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_method_form_type">{{ trans('cruds.payment_method.fields.payment_method_form_type') }}</label>
                        <select class="form-control" name="payment_method_form_type" id="payment_method_sc">
                            <option value="">{{ trans('cruds.payment_method.fields.select_payment_method_form_type') }}</option>
                            @foreach(paymentMethodFormType() as $key => $module)
                            <option {{ request('payment_method_form_type') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                            @endforeach
                        </select>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="startDate">{{ trans('cruds.payment_method.fields.date_from') }}</label>
                        <input class="form-control datetime" value="{{ request('date_from') }}" type="text" name="date_from" id="startDate">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="endDate">{{ trans('cruds.payment_method.fields.date_to') }}</label>
                        <input class="form-control datetime" value="{{ request('date_to') }}" type="text" name="date_to" id="endDate">
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <button type="submit" class="btn btn-success" style="padding: 6px 19px;"><i class="fa fa-search"></i></button>
                    <a href="{{ route('admin.payment-method-review.index')}}" class="btn btn-danger">Clear</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">

            <table class="table table-striped" id="clients-table">
                @if($payment_methods->count())
                <thead>

                <tr>
                    <th>{{ trans('cruds.payment_method.fields.serial_no') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.name') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.payment_method_form_type') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.commission') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.address') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.country_category') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.icon') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.status') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.created_at') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.action') }}</th>
                </tr>

                </thead>
                <tbody>

                <?php $i = $payment_methods->toArray()['from'];  ?>
                @foreach ($payment_methods as $paymentMethod)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $paymentMethod->name }}</td>
                        <td>{{ paymentMethodFormType($paymentMethod->payment_method_form_type) }}</td>
                        <td>{{ $paymentMethod->commission }}</td>
                        <td>{{ $paymentMethod->address }}</td>
                        <td>
                            @if($paymentMethod->country_category == 0)
                                NON-OFAC
                            @elseif($paymentMethod->country_category ==1)
                                OFAC
                            @endif
                        </td>
                        <td>
                           <img height="50" width="50" src="{{ $paymentMethod->icon }}" alt="Icon">
                        </td>

                        <td>
                            <span class="badge badge-warning">Waiting for Approval</span>
                        </td>
                        <td> {{ $paymentMethod->created_at ?? '' }}</td>
                        <td>
                            @can("payment_method_review")
                            <a href="{{ route('admin.payment-method-review.edit', $paymentMethod->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can("payment_method_show")
                                <a href="{{ route('admin.payment-method-review.show', $paymentMethod->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
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

        {{ $payment_methods->withQueryString()->links('pagination::bootstrap-4') }}
    </section>
</div>

@endsection
@section('scripts')
@parent
<script>

</script>
@endsection
