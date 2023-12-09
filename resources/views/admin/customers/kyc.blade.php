<table class="table table-bordered">
    <thead>
    <tr>
        <th>Tittle</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    @php
        $data = json_decode($kyc->kyc_response);
        $vendor = json_decode($data->verification->vendorData);
        $email =  $vendor->email ?? null;
        $login =  $vendor->login ?? null;
    @endphp
    <tr>
        <td>Veriff ID</td>
        <td>{{$data->verification->id ?? ""}}</td>
    </tr>
    <tr>
        <td>First Name</td>
        <td>{{$data->verification->person->firstName ?? ""}}</td>
    </tr>
    <tr>
        <td>Last Name</td>
        <td>{{$data->verification->person->lastName ?? ""}}</td>
    </tr>

    <tr>
        <td>Email</td>
        <td>
            {{$email}}
        </td>
    </tr>
    <tr>
        <td>Address</td>
        <td>{{$data->verification->person->address[0]->fullAddress ?? ""}}</td>
    </tr>

    <tr>
        <td>Status</td>
        <td>{{$data->verification->status ?? ""}}</td>
    </tr>
    <tr>
        <td>Reason</td>
        <td>{{$data->verification->reason ?? ""}}</td>
    </tr>
    <tr>
        <td>Document Type</td>
        <td>{{$data->verification->document->type ?? ""}}</td>
    </tr>

    <tr>
        <td>Document Number</td>
        <td>{{$data->verification->document->number ?? ""}}</td>
    </tr>

    <tr>
        <td>Document Country</td>
        <td>{{$data->verification->document->country ?? ""}}</td>
    </tr>
    <tr>
        <td>Document valid Until</td>
        <td>{{$data->verification->document->validUntil ?? ""}}</td>
    </tr>
    <tr>
        <td>Acceptance Time</td>
        <td>{{$data->verification->acceptanceTime ?? ""}}</td>
    </tr>

    <tr>
        <td>Decision Time</td>
        <td>{{$data->verification->decisionTime ?? ""}}</td>
    </tr>

    </tbody>
</table>

<p>
{{--<pre>--}}

{{--    {{json_encode(json_decode($kyc->kyc_response), JSON_PRETTY_PRINT)}}--}}
{{--</pre>--}}
</p>
