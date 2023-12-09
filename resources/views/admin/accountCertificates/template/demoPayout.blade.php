<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <style>
        @page {
            background-image: url("img/demo-payout.png");
            background-image-resize: 6;
        }
    </style>
</head>
<body style="-webkit-print-color-adjust: exact">
<div style="width: 20%; float:left; height: 200px;">
</div>
<div style="width: 60%; float:left; height: 200px ; ">

</div>
<div style="width: 19%; float:left; height: 200px; ">

    <div style="margin-top: 65px; color: #332e89; text-align: right; font-family: oleo-script;">
        <?php
        $login   = $preview['login'];
        $base64 = base64_encode($login.'/'.$docId);
        $baseUrl = env('APP_URL') . "trader/certificate/$base64";
        $qr      = urlencode($baseUrl); ?>
        <img src="https://chart.googleapis.com/chart?chs=100x100&chld=L|0&cht=qr&chl=<?= $qr ?>"
             style="width: 60%; height: 60%; float: right"
             alt="QR Code"
        /><br>

    </div>
    <div style="color: black;font-family: oswald-regular, sans-serif; text-align: right">CR-45266845</div>
</div>
<div style="width: 100%">
    <div style=" height: 250px;
            position: fixed;
            font-size: 30px;
            float: left;
            color: #665bff;
            width: 50%;
          ">
        <div style="margin-top: 100px; margin-left: 11px; text-align: center; font-family: oleo-script;">
            {{ substr( $preview['fullname'], 0,25) }}
        </div>
    </div>

    <div style=" height: 250px;float: left;color: #665bff;width: 49%;">
        <div
            style="margin-top: 85px; font-size: 40px; margin-left: 11px; text-align: center; font-family: oswald-regular;">
            {{$preview['current_profit']}}
        </div>
    </div>
</div>


</body>
</html>
