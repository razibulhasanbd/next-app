@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header text-primary">
        {{ trans('global.payment_method_list') }}  : Total-{{ $payment_methods->toArray()['total'] }}

        @can("payment_method_create")
        <a href="{{ route('admin.payment-method.create')}}" class="btn btn-success btn-sm pull-right">Create Method</a>
        @endcan
        @can("payment_method_order")
            <a class="btn btn-info btn-sm  m-b-2 mr-2 m-t-sm pull-right" href="{{ route('admin.payment_method.order') }}">Serialize Payment Method</a>
        @endcan

    </div>

    <section class="panel panel-default">

        <form action="{{ route('admin.payment-method.index')}}" method="GET">
            <div class="row card-body">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.payment_method.fields.name') }}</label>
                        <input type="text" class="form-control" value="{{ request('name') }}" name="name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_method_sc">{{ trans('cruds.payment_method.fields.payment_method_form_type') }}</label>
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
                        <label for="status">{{ trans('cruds.payment_method.fields.status') }}</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">{{ trans('cruds.payment_method.fields.select_status') }}</option>
                            @foreach($paymentMethodStatus as $key => $value)
                            <option {{ request('status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
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
                    <a href="{{ route('admin.payment-method.index')}}" class="btn btn-danger">Clear</a>
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
                    <th>{{ trans('cruds.payment_method.fields.order_serial_number') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.icon') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.status') }}</th>
                    <th>{{ trans('cruds.payment_method.fields.review_status') }}</th>
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
                        <td> {{ $paymentMethod->serial_number ?? '' }}</td>
                        <td>
                           <img height="50" width="50" src="{{ $paymentMethod->icon }}" alt="Icon">
                        </td>

                        <td>
                            @if($paymentMethod->status == 1)
                                <span class="badge badge-success">Active</span>
                            @elseif($paymentMethod->status == 0)
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($paymentMethod->is_sent_for_review == 1)
                                <span class="badge badge-warning">Sent for Review</span>
                            @elseif($paymentMethod->is_sent_for_review == 0)
                                <span class="badge badge-success">Approved</span>
                            @elseif($paymentMethod->is_sent_for_review == 2)
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td> {{ $paymentMethod->created_at ?? '' }}</td>
                        <td>
                            @can("payment_method_update")
                                  <a href="{{ route('admin.payment-method.edit', $paymentMethod->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can("payment_method_delete")
                                <form action="{{ route('admin.payment-method.destroy', $paymentMethod->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this?');" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                            @endcan
                            @can("payment_method_show")
                                <a href="{{ route('admin.payment-method.show', $paymentMethod->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
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
