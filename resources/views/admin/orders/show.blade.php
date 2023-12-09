@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} Order
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                    @php
                        $pendingOrderCount = $order->refundRequest->where('status', 0)->count();
                    @endphp
                    @if (!$pendingOrderCount)
                        @can('refund_request')
                            <a class="btn btn-success" href="{{ route('admin.refunds.create', $order->id) }}">
                                Refund Request
                            </a>
                        @endcan
                    @endif
                    @if ($order->status == 1 && $gateway != 'Outside')
                        @can('charge_request')
                            <a class="btn btn-warning" href="{{ route('admin.charges.create', $order->id) }}">
                                Add Charge
                            </a>
                        @endcan
                    @endif
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                Order ID
                            </th>
                            <td>
                                {{ $order->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Customer Email
                            </th>
                            <td>
                                {{ $order->customer->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Account login
                            </th>
                            <td>
                                {!! $order->account
                                    ? '<a href=' .
                                        route('admin.accounts.show', $order->account->id) .
                                        ' target=_blank>' .
                                        $order->account->login .
                                        '</a>'
                                    : '' !!}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Plan Name
                            </th>
                            <td>
                                {{ $order->jlPlans->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Coupon
                            </th>
                            <td>
                                {{ $order->coupon->code ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Parent Order
                            </th>
                            <td>
                                {!! $order->parent_order_id
                                    ? 'Id:' .
                                        $order->parent_order_id .
                                        ' (' .
                                        '<a href=' .
                                        route('admin.orders.show', $order->parent_order_id) .
                                        ' target=_blank>' .
                                        'View order' .
                                        '</a>)'
                                    : '' !!} </td>
                        </tr>
                        <tr>
                            <th>
                                Order Type
                            </th>
                            <td>
                                {{ $orderType ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Gateway
                            </th>
                            <td>
                                {{ $gateway ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction ID
                            </th>
                            <td>
                                {{ $order->transaction_id ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total
                            </th>
                            <td>
                                {{ $order->total }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Discount
                            </th>
                            <td>
                                {{ $order->discount ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Grand Total
                            </th>
                            <td>
                                {{ $netAmount }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                {{ statusArray()[$order->status] }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Created At
                            </th>
                            <td>
                                {{ $order->created_at }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Updated At
                            </th>
                            <td>
                                {{ $order->updated_at ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Remarks
                            </th>
                            <td>
                                {{ $order->remarks ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Order ID
                        </th>
                        <td>
                            {{ $order->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Customer Email
                        </th>
                        <td>
                            {{ $order->customer->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Account login
                        </th>
                        <td>
                            {!! $order->account ? '<a href=' . route('admin.accounts.show', $order->account->id) . ' target=_blank>'.$order->account->login.'</a>' : "" !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Plan Name
                        </th>
                        <td>
                            {{ $order->jlPlans->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Coupon
                        </th>
                        <td>
                            {{ $order->coupon->code ?? "" }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Server Name
                        </th>
                        <td>
                            {{ getTradingServer()[$order->server_name] }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                          Parent Order
                        </th>
                        <td>
                            {!! $order->parent_order_id ? 'Id:'.$order->parent_order_id ." (".'<a href=' . route('admin.orders.show', $order->parent_order_id) . ' target=_blank>'."View order".'</a>)' : "" !!}                        </td>
                    </tr>
                    <tr>
                        <th>
                           Order Type
                        </th>
                        <td>
                            {{  $orderType ?? "" }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Gateway
                        </th>
                        <td>
                            {{ $gateway ?? "" }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Transaction ID
                        </th>
                        <td>
                            {{ $order->transaction_id ?? "" }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Total
                        </th>
                        <td>
                            {{ $order->total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Discount
                        </th>
                        <td>
                            {{ $order->discount ?? "" }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Grand Total
                        </th>
                        <td>
                            {{ $order->grand_total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            {{ statusArray()[$order->status] }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Created At
                        </th>
                        <td>
                            {{ $order->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Updated At
                        </th>
                        <td>
                            {{ $order->updated_at ?? ""}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Remarks
                        </th>
                        <td>
                            {{ $order->remarks ?? ""}}
                        </td>
                    </tr>
                </tbody>
            </table>
>>>>>>> 91d87bb772dcd759a43ad547493e51eef8d9058d
        </div>
    </div>

    @if ($order->refundRequest->count())
        <div class="card">
            <div class="card-header">
                {{ trans('global.show') }} Refund Request List
            </div>

            <div class="card-body">
                <div class="form-group">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">User Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Coment</th>
                                <th scope="col">Reply Coment</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->refundRequest as $item)
                                <tr>
                                    <th>{{ $item->user->name }}</th>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ refundStatusArray()[$item->status] }}</td>
                                    <td>{{ $item->comment }}</td>
                                    <td>{{ $item->reply_comment }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    @endif


    @if ($order->addCharges->count())
        <div class="card">
            <div class="card-header">
                {{ trans('global.show') }} Add Charges List
            </div>
            <div class="card-body">
                <div class="form-group">
                    <span class="btn btn-success" href="#">
                        Total Amount: {{ $order->addCharges->sum('amount') }}
                    </span>
                </div>

                <div class="form-group">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">User Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->addCharges as $item)
                                <tr>
                                    <th>{{ $item->user->name }}</th>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    @endif

@endsection
