<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Preview</title>
        <style>
            html,
            body {
                display: flex;
                flex-direction: column;
                flex: 1;
                width: 100%;
                height: 100%;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
    </head>
    <body>
        <div style="width: 691px; height: 704px; position: relative; overflow: hidden; background: #fff;">
            <div style="text-align: center;">
                <img src="https://fundednext.fra1.cdn.digitaloceanspaces.com/Certificates%2FLogo_Main.png" style="width: 300px;">
            </div>
            <div style="display: block;position: relative;min-height: 210px;">
                <div style="width: 50%; float: left;">
                    <p style="margin-top: 50px; font-size: 20px; font-weight: bold; text-align: left; color: #1E1E1E;"> Invoice </p>
                    <p style="font-size: 16px; text-align: left; color: #464646;">
                        <span style="font-size: 16px; text-align: left; color: #7a7a7a;">Account Login: </span>
                        <span style="font-size: 16px; font-weight: bold; text-align: left; color: #464646;">{{ $order->account->login }}</span>
                    </p>
                    <p style="font-size: 16px; text-align: left;">
                        <span style="font-size: 16px; text-align: left; color: #7a7a7a;">Name:</span>
                        <span style="font-size: 16px; text-align: left; color: #464646;"> {{ \App\Helper\Helper::getOnlyCustomerName($order->customer->name) }}</span>
                    </p>
                    <p style="font-size: 16px; text-align: left;">
                        <span style="font-size: 16px; text-align: left; color: #838383;">Email:</span>
                        <span style="font-size: 16px; text-align: left; color: #464646;"> {{ $order->customer->email }}</span>
                    </p>
                </div>
                <div style="width: 50%; float: right; text-align: right; clear: right;">
                    <p style="margin-top: 50px; font-size: 20px; font-weight: bold; text-align: left; color: #1E1E1E;"></p>
                    <p style="margin-top: 50px; font-size: 16px; text-align: right;">
                        <span style="font-size: 16px; text-align: left; color: #6a6a6a;">Billing Date: </span>
                        <span style="font-size: 16px; text-align: left; color: #181818;">{{ date("d M Y") }}</span>
                    </p>
                </div>
            </div>

            <div style="width: 100%; background: #BEBEBE !important; height: 2px; display: block;" ></div>

            <div>
                <p style="font-size: 18px; font-weight: bold; text-align: left; color: #635bff;"> Thank you for your order </p>
                <p style="font-size: 16px; text-align: left; color: #464646;"> Please find your payment details in the following section. </p>
                <p style="font-size: 16px; text-align: left; color: #464646;"> If you have any questions, please let us know. Our support team will get back to you soon. </p>
            </div>

            <div style="width: 100%; background: #BEBEBE !important; height: 2px; display: block;" ></div>

            <div>
                <p style="font-size: 18px; font-weight: bold; text-align: left; color: #635bff;">
                    @if ($type == 1)
                        New Account Subscription
                    @elseif ($type == 2)
                        Topup Request Charge
                    @elseif ($type == 3)
                        Reset Request Charge
                    @endif
                </p>
                <div>
                    <p style="font-size: 16px; text-align: left; color: #222; float: left; width: 50%;margin-bottom: 10px;margin-top: 0px;">
                        <span style="font-size: 16px; text-align: left; color: #222;">Plan - </span>
                        <span style="font-size: 16px; font-weight: bold; text-align: left; color: #222;">{{ $order->jlPlans->name }}</span>
                    </p>
                    <p style="font-size: 16px; text-align: right; color: #222;float: right; width: 50%;margin-bottom: 10px;margin-top: 0px;"> ${{ $order->total }} </p>
                </div>

                @if (!empty($order->coupon) && !is_null($order->coupon))
                    @php
                        $discount = $order->discount;
                    @endphp
                    <div>
                        <p style="font-size: 16px; text-align: left; color: #222;float: left; width: 50%;margin-bottom: 10px;margin-top: 0px;"> Discount - Coupon({{ $order->coupon->code }}) </p>
                        <p style="font-size: 16px; text-align: right; color: #222;float: right; width: 50%;margin-bottom: 10px;margin-top: 0px;"> -${{ $order->discount }} </p>
                    </div>
                @else
                    @php
                        $discount = 0;
                    @endphp
                    <div>
                        <p style="font-size: 16px; text-align: left; color: #222;float: left; width: 50%;margin-bottom: 10px;margin-top: 0px;"> Discount - </p>
                        <p style="font-size: 16px; text-align: right; color: #222;float: right; width: 50%;margin-bottom: 10px;margin-top: 0px;"> -$0 </p>
                    </div>
                @endif

                @if ($order->gateway == 2)
                    <div>
                        <p style="font-size: 16px; text-align: left; color: #222;float: left; width: 50%;margin-bottom: 10px;margin-top: 0px;"> Comission fee - </p>
                        <p style="font-size: 16px; text-align: right; color: #222;float: right; width: 50%;margin-bottom: 10px;margin-top: 0px;"> +${{ ($order->grand_total - $order->total)+$discount }} </p>
                    </div>
                @endif

                <div style="width: 100%; background: #BEBEBE !important; height: 2px; display: block;" ></div>

                <div>
                    <p style="font-size: 16px; font-weight: bold; text-align: left; color: #222;float: left; width: 50%;margin-top: 0px;"> Total </p>
                    <p style="font-size: 16px; font-weight: bold; text-align: right; color: #474747;float: right; width: 50%;margin-top: 0px;"> ${{ $order->grand_total }} </p>
                </div>
            </div>
        </div>
    </body>
</html>
