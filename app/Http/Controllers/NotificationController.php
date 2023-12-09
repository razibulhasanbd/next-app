<?php

namespace App\Http\Controllers;

use Berkayk\OneSignal\OneSignalClient;

class NotificationController extends Controller
{
    public function __construct()
    {
    }

    public function init()
    {
        OneSignalClient::sendNotificationToUser(
            "Some Message",
            1,
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null
        );
    }

}
