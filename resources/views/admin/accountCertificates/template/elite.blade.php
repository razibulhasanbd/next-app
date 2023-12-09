<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Elite Certificate</title>
    <style>
        @page {
            background-image: url("img/elite-trader.png");
            background-image-resize: 6;
        }
    </style>
</head>
<body style="-webkit-print-color-adjust: exact">
<div style="width: 100%">
    <div style="width: 25%">&nbsp;</div>
    <div style=" height: 250px;
            position: fixed;
            font-size: 42px;
            margin-bottom: 0px;
            float: right;
            color: #665bff;
            width: 68%;
            "
    >
        <div style="margin-top: 178px; text-align: center; font-family: oleo-script;">
            {{ substr( $preview['fullname'], 0,25) }}
        </div>
    </div>
</div>
<div style="width: 200px; height: 300px;float: left;">
    <div
        style="font-size:17px; margin-top: 212px;text-align: center; color: white;font-family: oswald-regular, sans-serif;font-weight: bold ">
        DATE: {{date('d-m-Y')}}
    </div>
</div>
<div style="width: 60%; height: 300px;  float: right">
    <div style="width: 50%; float: right;">
        <div style="width: 50%; float: right;text-align: right ; margin-top: 160px">
            <span style="color: black;font-family: oswald-regular, sans-serif; text-align: center">CR-45266845</span>
            <br>
            <div>
                <?php
                $login   = $preview['login'];
                $base64 = base64_encode($login.'/'.$docId);
                $baseUrl = env('APP_URL') . "trader/certificate/$base64";
                $qr      = urlencode($baseUrl); ?>
                <img src="https://chart.googleapis.com/chart?chs=100x100&chld=L|0&cht=qr&chl=<?= $qr ?>"
                     style="width: 75%; height: 75%; float: right"
                     alt="QR Code"
                />
            </div>
        </div>
    </div>
</div>
</body>
</html>
