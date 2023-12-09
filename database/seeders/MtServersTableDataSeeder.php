<?php

namespace Database\Seeders;

use App\Models\MtServer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MtServersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mtServers = [
            [
                "url" => "http://3.235.254.53:6502/v1",
                "server" => "mtdemo1.leverate.com",
                "login" => "302",
                "password" => "qDgi5er",
                "group" => "demoSLJUSD",
                "friendly_name" => "EightCapDemo",
                "trading_server_type" => "demo",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "url" => "http://52.15.154.254:6502/v1",
                "server" => "52.196.97.216",
                "login" => "214",
                "password" => "Ompx4tx",
                "group" => "demoMB_Raw-USD",
                "friendly_name" => "EightCapReal",
                "trading_server_type" => "real",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
            ];
        MtServer::insert($mtServers);
    }
}
