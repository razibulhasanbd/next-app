@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.growthFund.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.growth-funds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.growthFund.fields.id') }}
                        </th>
                        <td>
                            {{ $growthFund->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.growthFund.fields.amount') }}
                        </th>
                        <td>
                            {{ $growthFund->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.growthFund.fields.date') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($growthFund->date) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.growthFund.fields.account') }}
                        </th>
                        <td>
                            {{ $growthFund->account->login ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.growthFund.fields.subscription') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($growthFund->subscription->ending_at) ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.growthFund.fields.fund_type') }}
                        </th>
                        <td>
                            {{ $growthFund->fund_type }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.growth-funds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
