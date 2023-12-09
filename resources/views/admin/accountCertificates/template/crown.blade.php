<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crown Certificate</title>

    <style>
        @page {
            background-image: url("img/crown-trader.png");
            background-image-resize: 6;
        }
    </style>
</head>
<body style="-webkit-print-color-adjust: exact">
<div style="width: 100%">
    <div style="width: 1%">&nbsp;</div>
    <div style=" height: 250px;
            position: fixed;
            font-size: 42px;
            margin-bottom: 0px;
            float: left;
            color: #665bff;
            width: 75%;
          ">
        <div style="margin-top: 175px; margin-left: 11px; text-align: left; font-family: oleo-script;">
            {{ substr( $preview['fullname'], 0,25) }}
        </div>
    </div>
</div>

<div style="width: 240px; height: 100px;float: left;">

</div>
<div style="width: 210px; height: 100px;float: left;">
    <div
        style="font-size:14px; margin-top: 210px;text-align: center; color: white;font-family: oswald-regular, sans-serif;font-weight: bold ">
        {{date('d-m-Y')}}
    </div>
</div>
</body>
</html>
