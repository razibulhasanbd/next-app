@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.traderGame.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trader-games.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.id') }}
                        </th>
                        <td>
                            {{ $traderGame->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.dashboard_user') }}
                        </th>
                        <td>
                            {{ $traderGame->dashboard_user }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.date') }}
                        </th>
                        <td>
                            {{ frontEndTimeConverterView($traderGame->date, 'date') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.dashboard_email') }}
                        </th>
                        <td>
                            {{ $traderGame->dashboard_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.pnl') }}
                        </th>
                        <td>
                            {{ $traderGame->pnl }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.mental_score') }}
                        </th>
                        <td>
                            {{ $traderGame->mental_score }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traderGame.fields.tactical_score') }}
                        </th>
                        <td>
                            {{ $traderGame->tactical_score }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trader-games.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
