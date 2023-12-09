@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} Order
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.refunds.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                Order ID
                            </th>
                            <td>
                                {!! $refundInfo->order
                                    ? '<a href=' .
                                        route('admin.orders.show', $refundInfo->order->id) .
                                        ' target=_blank>' .
                                        $refundInfo->order->id .
                                        '</a>'
                                    : '' !!}
                            </td>
                        </tr>
                        <tr>
                            <th>
                              User Name
                            </th>
                            <td>
                                {!! $refundInfo->user
                                    ? '<a href=' .
                                        route('admin.users.show', $refundInfo->user->id) .
                                        ' target=_blank>' .
                                        $refundInfo->user->name .
                                        '</a>'
                                    : '' !!}
                            </td>
                        </tr>
                        <tr>
                            <th>
                              Transaction Id
                            </th>
                            <td>
                                {{ $refundInfo->order->transaction_id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Amount
                            </th>
                            <td>
                               {{  $refundInfo->amount }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                {{ refundStatusArray()[$refundInfo->status] }}  
                            </td>
                        </tr>
                        <tr>
                            <th>
                               Comment
                            </th>
                            <td>
                               {{  $refundInfo->comment }} </td>
                        </tr>
                        <tr>
                            <th>
                                Reply Comment
                            </th>
                            <td>
                                {{  $refundInfo->reply_comment }}
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
