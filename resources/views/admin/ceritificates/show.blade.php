@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ceritificate.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ceritificates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ceritificate.fields.id') }}
                        </th>
                        <td>
                            {{ $ceritificate->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ceritificate.fields.name') }}
                        </th>
                        <td>
                            {{ $ceritificate->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ceritificate.fields.html_markup') }}
                        </th>
                        <td>
                            {{ $ceritificate->html_markup }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ceritificate.fields.type') }}
                        </th>
                        <td>
                            {{ $ceritificate->type->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Demo image
                        </th>
                        <td width="50%">
                            <img style="width: 20%" src="{{$ceritificate->demo_image}}">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ceritificates.index') }}">
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
            <a class="nav-link" href="#certificate_account_certificates" role="tab" data-toggle="tab">
                {{ trans('cruds.accountCertificate.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="certificate_account_certificates">
            @includeIf('admin.ceritificates.relationships.certificateAccountCertificates', ['accountCertificates' => $ceritificate->certificateAccountCertificates])
        </div>
    </div>
</div>

@endsection
