<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @page {
            background-image: url("img/real-payout.png");
            background-image-resize: 6;
        }
    </style>
</head>
<body style="-webkit-print-color-adjust: exact">
<div style="width: 1%">&nbsp;</div>
<div style="width: 17%; float:left; height: 250px;">
</div>
<div style="width: 65%; float:left; height: 250px ; ">
    <div style="margin-top: 110px; font-size: 42px; color: #332e89; text-align: center; font-family: oleo-script;">
        {{ substr( $preview['fullname'], 0,25) }}
    </div>
</div>
<div style="width: 17%; float:left; height: 250px ;">
    <div style="margin-top: 60px; color: #332e89; text-align: right;">
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
    <div style=" height: 200px;
            position: fixed;
            font-size: 20px;
            float: left;
            color: #332e89;
            width: 60%;
            font-family: oswald-regular;">

        @foreach($preview['certificate_data']['breakdown'] as $row)
            <div
                style=" margin-left: 11px;float: left; margin-bottom: 5px;
             border-bottom: 1px solid #aaa3a3; width: 66%">
                <span style="text-align: left;"> {{$row['key']}}</span>
            </div>
            <div
                style=" float: left; border-bottom: 1px solid #aaa3a3; width: 26%;  text-align: right;   color: #665bff;">
                {{ !empty($row['value']) ? ' $'.$row['value']: ''}}
            </div>

        @endforeach
    </div>

    <div style=" height: 250px;float: left;color: #665bff;width: 38%;">
        <div
            style="margin-top: 30px; font-size: 40px; margin-left: 11px; text-align: center; font-family: oswald-regular;">
            ${{$preview['current_profit']}}
        </div>
    </div>
</div>
</body>
</html>
