<span><b>Total Lot Traded: </b>{{ $totalLotTrades / 100 }}</span>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Symbol</th>
            <th>Lot</th>
    </thead>
    <tbody>
        @if (isset($topSixPair))


            @foreach ($topSixPair as $rowData)
                <tr>

                    <td>

                        {{ $rowData['symbol'] }}
                    </td>
                    <td>

                        {{ $rowData['lot_size'] }}
                    </td>
                </tr>
            @endforeach
        @else
            <h1>No Data Available</h1>
        @endif

    </tbody>
</table>
