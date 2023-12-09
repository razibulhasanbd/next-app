@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tradeSlTp.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trade-sl-tps.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tradeSlTp.fields.id') }}
                        </th>
                        <td>
                            {{ $tradeSlTp->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tradeSlTp.fields.trade') }}
                        </th>
                        <td>
                            {{ $tradeSlTp->trade->account ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tradeSlTp.fields.type') }}
                        </th>
                        <td>
                            {{ $tradeSlTp->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tradeSlTp.fields.value') }}
                        </th>
                        <td>
                            {{ $tradeSlTp->value }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trade-sl-tps.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection