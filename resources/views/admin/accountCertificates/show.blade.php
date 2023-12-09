@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.accountCertificate.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-certificates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.id') }}
                        </th>
                        <td>
                            {{ $accountCertificate->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.certificate') }}
                        </th>
                        <td>
                            {{ $accountCertificate->certificate->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.account') }}
                        </th>
                        <td>
                            {{ $accountCertificate->account->login ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.certificate_data') }}
                        </th>
                        <td>
                            {{ $accountCertificate->certificate_data }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.customer') }}
                        </th>
                        <td>
                            {{ $accountCertificate->customer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.url') }}
                        </th>
                        <td>
                            {{ $accountCertificate->url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.accountCertificate.fields.share') }}
                        </th>
                        <td>
                            {{ App\Models\AccountCertificate::SHARE_RADIO[$accountCertificate->share] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.account-certificates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
