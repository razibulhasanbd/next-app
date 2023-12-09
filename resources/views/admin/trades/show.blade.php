@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.trade.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trades.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.id') }}
                        </th>
                        <td>
                            {{ $trade->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.account') }}
                        </th>
                        <td>
                            {{ $trade->account->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.close_price') }}
                        </th>
                        <td>
                            {{ $trade->close_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.close_time') }}
                        </th>
                        <td>
                            {{ $trade->close_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.close_time_str') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($trade->close_time_str) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.commission') }}
                        </th>
                        <td>
                            {{ $trade->commission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.digits') }}
                        </th>
                        <td>
                            {{ $trade->digits }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.login') }}
                        </th>
                        <td>
                            {{ $trade->login }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.lots') }}
                        </th>
                        <td>
                            {{ $trade->lots }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.open_price') }}
                        </th>
                        <td>
                            {{ $trade->open_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.open_time') }}
                        </th>
                        <td>
                            {{ $trade->open_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.open_time_str') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($trade->open_time_str) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.pips') }}
                        </th>
                        <td>
                            {{ $trade->pips }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.profit') }}
                        </th>
                        <td>
                            {{ $trade->profit }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.reason') }}
                        </th>
                        <td>
                            {{ $trade->reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.sl') }}
                        </th>
                        <td>
                            {{ $trade->sl }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.state') }}
                        </th>
                        <td>
                            {{ $trade->state }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.swap') }}
                        </th>
                        <td>
                            {{ $trade->swap }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.symbol') }}
                        </th>
                        <td>
                            {{ $trade->symbol }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.ticket') }}
                        </th>
                        <td>
                            {{ $trade->ticket }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.tp') }}
                        </th>
                        <td>
                            {{ $trade->tp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.type') }}
                        </th>
                        <td>
                            {{ $trade->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.type_str') }}
                        </th>
                        <td>
                            {{ $trade->type_str }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trade.fields.volume') }}
                        </th>
                        <td>
                            {{ $trade->volume }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trades.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
