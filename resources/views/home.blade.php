@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center">
                    Welcome To Dashboard

                    {{-- <div id="reportrange" class="float-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div> --}}

                </div>

                {{-- <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="row mt-5">
                        <div class="col-md-6">
                            <h5 class="text-center text-dark text-uppercase ">Plan Wise Total Account</h3>
                                <canvas id="planChart" height="280" width="600"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-center text-dark text-uppercase ">Plan Wise Breach Account</h3>
                                <canvas id="breachChart" height="280" width="600"></canvas>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <!-- <div class="col-md-6">
                                <h5 class="text-center text-dark text-uppercase ">TRADES STATISTICS</h3>
                                <canvas id="tradesChart" height="280" width="600"></canvas>
                            </div> -->
                        <div class="col-md-6">
                            <h5 class="text-center text-dark text-uppercase ">REGISTERED ACCOUNTS STATISTICS</h3>
                                <canvas id="accountsChart" height="280" width="600"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-center text-dark text-uppercase ">BREACH TYPE ACCOUNTS STATISTICS</h3>
                                <canvas id="breachTypeAccountsChart" height="280" width="600"></canvas>
                        </div>
                    </div>
                    <!-- <div class="row mt-5 justify-content-center">

                    </div> -->
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>

{{-- <script>
    var myChart1;
    var myChart;
    var myChart2;
    var myChart3;
    var myChart4;
    const planChart = (
        planName,
        userTotalCount,
    ) => {
        if (myChart1) {
            console.log('destroy');
            myChart1.destroy();
        }
        let canvasctx = document.querySelector("#planChart").getContext("2d");
        myChart1 = new Chart(canvasctx, {
            type: 'bar',
            data: {
                labels: planName,
                datasets: [{
                    label: 'Plan Wise Total Account',
                    data: userTotalCount,
                    backgroundColor: 'green',
                    borderColor: 'Violet',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,

                    }
                }
            }
        });
    }
    const breachChart = (
        breachPlanName,
        breachAccountCount
    ) => {
        if (myChart) {
            myChart.destroy();
        }
        let ctx2 = document.getElementById("breachChart").getContext("2d");
        myChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: breachPlanName,
                datasets: [{
                    label: 'Total Breach Account',
                    data: breachAccountCount,
                    backgroundColor: '#CF9FFF',
                    borderColor: 'Violet',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // const tradesChart = (
    //     tradesLabel,
    //     allTradesCount,
    // ) => {

    //     if (myChart2) {
    //         console.log('destroy');
    //         myChart2.destroy();
    //     }
    //     let tradesChartCanvas = document.querySelector("#tradesChart").getContext("2d");
    //     myChart2 = new Chart(tradesChartCanvas, {
    //         type: 'bar',
    //         data: {
    //             labels: tradesLabel,
    //             datasets: [{
    //                 label: 'TRADES STATISTICS',
    //                 data: allTradesCount,
    //                 backgroundColor: '#C88141',
    //                 borderColor: '#E9AB17',
    //                 borderWidth: 1
    //             }]
    //         },
    //         options: {
    //             scales: {
    //                 y: {
    //                     beginAtZero: true
    //                 }
    //             }
    //         }
    //     });
    // }

    const accountsChart = (
        accountPackage,
        accountCount,
    ) => {

        if (myChart3) {
            console.log('destroy');
            myChart3.destroy();
        }
        let accountsChartCanvas = document.querySelector("#accountsChart").getContext("2d");
        myChart3 = new Chart(accountsChartCanvas, {
            type: 'bar',
            data: {
                labels: accountPackage,
                datasets: [{
                    label: 'REGISTERED ACCOUNTS STATISTICS',
                    data: accountCount,
                    backgroundColor: 'SlateBlue',
                    borderColor: 'Violet',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,

                    }
                }
            }
        });
    }




    const breachTypeAccountsChart = (
        breachType,
        breachTypeAccountCount,
    ) => {

        if (myChart4) {
            myChart4.destroy();
        }
        let breachTypeAccountsChartCanvas = document.querySelector("#breachTypeAccountsChart").getContext("2d");
        myChart4 = new Chart(breachTypeAccountsChartCanvas, {
            type: 'bar',
            data: {
                labels: breachType,
                datasets: [{
                    label: 'BREACH TYPE ACCOUNTS STATISTICS',
                    data: breachTypeAccountCount,
                    backgroundColor: 'SlateBlue',
                    borderColor: 'Violet',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
<script type="text/javascript">
    // function cb(start, end) {
    //     $('#reportrange span').html("From: " + start.format('MMMM D, YYYY') + ' - To: ' + end.format('MMMM D, YYYY'));
    //     lstart = moment($('#reportrange').data('daterangepicker').startDate);

    //     startDate = lstart.format('MM/DD/YYYY');
    //     lend = moment($('#reportrange').data('daterangepicker').endDate);
    //     endDate = lend.format('MM/DD/YYYY');
    //     $.ajax({
    //         type: 'GET',
    //         url: "{{ route('admin.home') }}",
    //         data: {
    //             startDate: startDate,
    //             endDate: endDate
    //         },
    //         success: function(data) {
    //             planChart(data['planName'], data['userTotalCount']);
    //             breachChart(data['breachPlanName'], data['breachAccountCount']);
    //             // tradesChart(data['tradesLabel'],data['allTradesCount']);
    //             accountsChart(data['accountPackage'], data['accountCount']);
    //             breachTypeAccountsChart(data['breachType'], data['breachTypeAccountCount']);
    //         }
    //     });
    // }
    // $(function() {
    //     //var start = moment();
    //     var start = moment();
    //     var end = moment();
    //     var lstart, lend;
    //     $('#reportrange span').html("From: " + moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - To: ' + end.format('MMMM D, YYYY'));
    //     $.ajax({
    //         type: 'GET',
    //         // url: "{{ route('admin.home') }}",
    //         url: "#",
    //         data: {
    //             // startDate: moment('01/01/2021').format('MM/DD/YYYY'),
    //             startDate: moment().subtract(29, 'days').format('MM/DD/YYYY'),
    //             endDate: end.format('MM/DD/YYYY')
    //         },
    //         success: function(data) {
    //             planChart(data['planName'], data['userTotalCount']);
    //             breachChart(data['breachPlanName'], data['breachAccountCount']);
    //             // tradesChart(data['tradesLabel'],data['allTradesCount']);
    //             accountsChart(data['accountPackage'], data['accountCount']);
    //             breachTypeAccountsChart(data['breachType'], data['breachTypeAccountCount']);
    //         }
    //     });

        // $('#reportrange').daterangepicker({
        //     startDate: start,
        //     endDate: end,

        //     ranges: {
        //         'All time': [moment('01/01/2021'), moment()],
        //         'Today': [moment(), moment()],
        //         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        //         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        //         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //         'This Month': [moment().startOf('month'), moment().endOf('month')],
        //         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
        //             'month').endOf('month')]
        //     }
        // }, cb(startDate,endDate));
        //  cb(start, end);
    // });
</script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//momentjs.com/downloads/moment-timezone-with-data-10-year-range.js"></script> --}}

<!-- Include Date Range Picker -->
{{-- <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script> --}}
{{-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" /> --}}
@parent
@endsection
