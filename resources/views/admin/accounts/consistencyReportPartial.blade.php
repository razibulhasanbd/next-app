<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Consistency</th>
            <th>This Week Av</th>
            <th>Overall Av</th>
            <th>Uper Limit</th>
            <th>Lower Limit</th>
            <th>Standard Deviation</th>
    </thead>
    <tbody>

        <tr>
            <th scope="row">Trade</th>

            <td>{{ $trade_weekly_average }}</td>
            <td> {{ $trade_overall_average }}</td>
            <td>{{ $trade_high }}</td>
            <td>{{ $trade_low }}</td>

            <td>{{ $deviation }}</td>
        </tr>

        <tr>
            <th scope="row">Lot</th>

            <td>{{ $lot_weekly_average }}</td>
            <td>{{ $lot_overall_average }}</td>
            <td>{{ $lot_high }}</td>
            <td>{{ $lot_low }}</td>

            <td>{{ $deviation }}</td>
        </tr>




    </tbody>
</table>