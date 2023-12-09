@extends('layouts.admin')

@section('content')
    <h2>News Trade Info</h2>
    <div class="row">

        <div class="col-sm-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }}News Trade Info
                </div>

                <div class="card-body">
                    <div class="form-group">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th>No</th>
                                    <th>Account Login</th>
                                    <th>Ticket</th>
                                    <th>Trade Open Time</th>
                                    <th>Trade Close Time</th>
                                    <th>Trade Symbol</th>
                                    <th>Commision</th>
                                    <th>Profit</th>
                                    <th>News Time</th>
                                    <th>News Title</th>
                                    <th>News Country</th>
                            </thead>

                            <tbody>
                                @if (!empty($newsTradeInfo))
                                    @foreach ($newsTradeInfo as $key => $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data['login'] }}</td>
                                            <td>{{ $data['ticket'] }}</td>
                                            <td>{{ $data['open_time_str'] }}</td>
                                            <td>{{ $data['close_time_str'] }}</td>
                                            <td>{{ $data['symbol'] }}</td>
                                            <td>{{ $data['commission'] }}</td>
                                            <td>{{ round($data['profit'], 4) }}</td>
                                            <td>{{ $data['date'] }}</td>
                                            <td>{{ $data['title'] }}</td>
                                            <td>{{ $data['country'] }}</td>




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
