<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Carbon;

class RedisController extends Controller
{
    //
    public function testRedis1(Request $request)
    {

        // $account = Account::find(21);

        // $controller = new \App\Http\Controllers\AccountController();
        // $response = $controller->breachEvent($account);


        // return $response;
        // $account = Account::find(2)->metrics->where('metricDate', '=', '2021-07-07 02:07:50')->first();
        // $account = Account::find(2)->metrics()->whereDate('metricDate', '=', '2021-07-07')->latest('id')->first();
        // $account = Account::find(2)->lastDayMetric;
        // // $account = Account::find(2)->metrics->where()first();

        //19989626
        //19993294
        //19989624
        // return $account;
        // $timeNow = round(microtime(true) * 1000);
        // $metrics = Redis::get('margin:' . $request->input('login'));

        // $timeNext = round(microtime(true) * 1000);

        $metrics = Account::pluck('login')->toArray();

        dd($metrics);
        // $metrics = Redis::get('smembers orders:' . $request->input('login') . ':working');
        return response()->json($metrics);

        // dd($redis->key);
        return json_decode($metrics, 1);
    }

    public function testRedis()
    {
        // dd(getAuthJlCustomer(), getAuthCustomer());

        $account =  Account::find(2);

        // $lastDayBalance = $account->lastDayMetric->lastBalance;
        $exists = $account->ifDayMetric;
        dd($exists);
    }
}
