<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Account;
use App\Models\MtServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ServerUptimeController extends Controller
{
    //

    public function test($id)
    {

        $account = Account::find($id);


        $planRules = $account->planRules();
        if (isset($planRules['CRD'])) {
            return json_encode($account->isConsistent($planRules['CRD']));
        } else {
            return "Does not have CRD rule";
        }
    }

    public function initCheckRead($id)
    {


        $account = Account::find($id);
        $server = $account->server;
        $url = $server->url;

        $updateAccount = Http::acceptJson()->post($url . "/user/update?token=" . $server->login, [
            'login' => $account->login,
            'read_only' => 1,

        ]);


        return $updateAccount;
    }
    public function redisSetCheck()
    {
        $redisData = Redis::info()['Persistence']['rdb_changes_since_last_save'];
        // $redisData = Redis::info();
        // return $redisData;
        if (Cache::has('lastSaveCount')) {
            $lastSaveCount = Cache::get('lastSaveCount');



            if ($redisData - $lastSaveCount < 50) {


                Helper::discordAlert("**Redis Pump Alert** \nNo Running trade found");
            }
        } else $lastSaveCount = 0;
        Cache::put('lastSaveCount', $redisData);
        return [
            'redis' => $redisData,
            'cache' => $lastSaveCount
        ];
    }
    public function redisServerCheck()
    {

        try {
            $redisData = Redis::echo("check");
            if ($redisData != "check") {
                Helper::discordAlert("**Redis Echo Alert**");
                return response()->json(['error' => 'msg not same'], 500);
            }
            // Storage::put('file.txt',  $redisData);
            return "working";
        } catch (\Exception $e) {
            Helper::discordAlert("**Redis Echo Alert**");
            Storage::put('file.txt',  $e);
            return $e;
        }
    }
    public function mt4ServerCheck()
    {
        $servers = MtServer::all();
        foreach ($servers as $server) {

            $result = Helper::mt4init($server);
            if ($result['error']) {
                Helper::discordAlert("**MT4 Server Alert** \n" . $result['error'] . "\nServer ID : " . $server->id);
            }
        }

        return "working";
    }
}
