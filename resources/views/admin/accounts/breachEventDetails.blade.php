@extends('layouts.admin')

@section('content')
<h2>Account Login: {{ $breachEventIdDetails->login }}</h2>
<div class="row">
    
    <div class="col-sm-6">

        <div class="card">
            <div class="card-header">
                {{ trans('global.show') }} Breach Event Metrics Details
                <span id="accountEquity" class="badge badge-warning">Breach Time:
                    {{ $breachEventIdDetails->created_at }}</span>
            </div>

            <div class="card-body">
                <div class="form-group">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                
                                <th>Max Daily Loss</th>
                                <th>Max Monthly Loss</th>
                                <th>Last Balance</th>
                                <th>Last Equity</th>
                        </thead>

                        <tbody>
                            @if (isset($metrics))
                            
                            <tr>
                               
                                <td>{{ $metrics->maxDailyLoss }}</td>
                                <td>{{ $metrics->maxMonthlyLoss }}</td>
                                <td>{{ $metrics->lastBalance }}</td>
                                <td>{{ $metrics->lastEquity }}</td>
                            </tr>
                            
                            @endif

                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">

        <div class="card">
            <div class="card-header">
                {{ trans('global.show') }} Breach Event Account Details
            </div>

            <div class="card-body">
                <div class="form-group">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Balance</th>
                                <th>Equity</th>
                        </thead>

                        <tbody>
                            @if (isset($breachEventIdDetails))
                            
                            <tr>
                               
                                <td>{{ $breachEventIdDetails->balance }}</td>
                                <td>{{ $breachEventIdDetails->equity }}</td>
                            </tr>
                            
                            @endif

                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>


   
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header">
                {{ trans('global.show') }} Breach Event Trade Details
            </div>

            <div class="card-body">
                <div class="form-group">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                
                                <th>No</th>
                                <th>Symbol</th>
                                <th>Type Str</th>
                                <th>Ticket</th>
                                <th>Profit</th>
                                <th>Sl</th>
                                <th>Tp</th>
                                <th>Open Price</th>
                                <th>Open Time</th>
                                <th>Close Price</th>
                                <th>Close Time</th>
                                <th>Close Lots</th>
                                <th>Close volume</th>
                                <th>Pips</th>
                                
                        </thead>

                        <tbody>
                            @if(!empty($trade))
                            @foreach ($trade as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->symbol }}</td>
                                <td>{{ $data->type_str }}</td>
                                <td>{{ $data->ticket }}</td>
                                <td>{{round($data->profit,4) }}</td>
                                <td>{{ round($data->sl,4) }}</td>
                                <td>{{ round($data->tp,4) }}</td>
                                <td>{{ round($data->open_price,7) }}</td>
                                <td>{{ $data->open_time }}</td>
                                <td>{{ round($data->close_price,7) }}</td>
                                <td>{{ $data->close_time }}</td>
                                <td>{{ $data->lots }}</td>
                                <td>{{ $data->volume }}</td>
                                <td>{{ $data->pips }}</td>
                                

                            </tr>
                            @endforeach
                            
                            @else
                            No Data Available
                            @endif
                            
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>


</div>

@endsection

@section('scripts')

@endsection