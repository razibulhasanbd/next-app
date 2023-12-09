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
            <th>Lots</th>
            <th>Volume</th>
            <th>Pips</th>

    </thead>
    <tbody>
        @if (isset($makePaginator))

            @foreach ($makePaginator['data'] as $rowData)
                <tr>
                    <td>

                        {{ $loop->iteration }}
                    </td>
                    <td>

                        {{ $rowData['symbol'] }}
                    </td>
                    <td>

                        {{ $rowData['type_str'] }}
                    </td>

                    <td>

                        {{ $rowData['ticket'] }}
                    </td>
                    <td>

                        {{ $rowData['profit'] }}
                    </td>
                    <td>

                        {{ $rowData['sl'] }}
                    </td>
                    <td>

                        {{ $rowData['tp'] }}
                    </td>
                    <td>

                        {{ $rowData['open_price'] }}
                    </td>
                    <td>

                        {{ $rowData['open_time_str'] }}
                    </td>
                    <td>

                        {{ $rowData['close_price'] }}
                    </td>
                    <td>

                        {{ $rowData['close_time_str'] }}
                    </td>
                    <td>

                        {{ $rowData['lots'] }}
                    </td>
                    <td>

                        {{ $rowData['volume'] }}
                    </td>
                    <td>

                        {{ $rowData['pips'] }}
                    </td>
                </tr>
            @endforeach

    </tbody>
</table>
@if ($makePaginator['total'] > $makePaginator['per_page'])
    <ul class="pager row flex justify-content-center">
        <li class=" p-3 list-unstyled">
            <div class="form-group">
                @if ($makePaginator['prev_page_url'] == '')
                    <button class="btn btn-default btn-trade page-item disabled" disabled>←Previous</button>
                @else
                    <button class="btn btn-warning btn-trade page-item" id="previousBtn"
                        onClick="hitPreviousBtn('{!! $makePaginator['prev_page_url'] !!}',{{ $makePaginator['data'][0]['account_id'] }})">←
                        Previous</button>
                @endif

            </div>
        </li>
        <li class="p-3 list-unstyled">
            <div class="form-group">
                @if ($makePaginator['next_page_url'] == '')
                    <button class="btn btn-default btn-trade page-item disabled" id="nextBtn">Next→</button>
                @else
                    <button class="btn btn-trade btn-success page-item" id="nextBtn"
                        onClick="hitNextBtn('{!! $makePaginator['next_page_url'] !!}',{{ $makePaginator['data'][0]['account_id'] }})">Next
                        →</button>
                @endif

            </div>
        </li>

    </ul>

    <div class="form-group border-bottom">
        <p class="text-center">Showing <span class="bd-highlight"> {{ count($makePaginator['data']) }} items per
                page</span> of Total <span class="bd-highlight">{{ $makePaginator['total'] }} </span> items </p>
    </div>
@endif
@endif

