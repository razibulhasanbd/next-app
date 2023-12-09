<?php

return array(
    /*
	|--------------------------------------------------------------------------
	| One Signal App Id
	|--------------------------------------------------------------------------
	|
	|
	*/
    'app_id' => env('ONESIGNAL_APP_ID',''),
    'api_url' => env('ONESIGNAL_API_URL','https://onesignal.com'),

    /*
	|--------------------------------------------------------------------------
	| Rest API Key
	|--------------------------------------------------------------------------
	|
    |
	|
	*/
    'api_key' => env('ONESIGNAL_REST_API_KEY',''),


    /*
	|--------------------------------------------------------------------------
	| Guzzle Timeout
	|--------------------------------------------------------------------------
	|
    |
	|
	*/
    'guzzle_client_timeout' => env('ONESIGNAL_GUZZLE_CLIENT_TIMEOUT', 0),
    'notification_time_before_minutes' => env('ONESIGNAL_PUSH_TIME_BEFORE_MINUTES',15),
    'notification_time_after_minutes' => env('ONESIGNAL_PUSH_TIME_AFTER_MINUTES',5),
);
