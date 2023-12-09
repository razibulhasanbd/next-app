@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.certificateType.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.certificate-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.certificateType.fields.id') }}
                        </th>
                        <td>
                            {{ $certificateType->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificateType.fields.name') }}
                        </th>
                        <td>
                            {{ $certificateType->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.certificateType.fields.label') }}
                        </th>
                        <td>
                            {{ $certificateType->label }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.certificate-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#type_ceritificates" role="tab" data-toggle="tab">
                {{ trans('cruds.ceritificate.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="type_ceritificates">
            @includeIf('admin.certificateTypes.relationships.typeCeritificates', ['ceritificates' => $certificateType->typeCeritificates])
        </div>
    </div>
</div>

@endsection