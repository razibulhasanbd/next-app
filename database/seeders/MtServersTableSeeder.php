<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MtServersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('mt_servers')->delete();
        
        \DB::table('mt_servers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'url' => 'http://3.235.254.53:6502/v1',
                'server' => 'mtdemo1.leverate.com',
                'login' => '302',
                'password' => 'qDgi5er',
                'group' => 'demoSLJUSD',
                'friendly_name' => 'mtdemo1.leverate.com',
                'trading_server_type' => 'demo',
                'created_at' => '2022-02-04 14:23:26',
                'updated_at' => '2022-02-04 14:23:26',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'url' => 'http://3.235.254.53:6502/v1',
                'server' => 'mtdemo1.leverate.com',
                'login' => '2501',
                'password' => 'abc123',
                'group' => 'demoHFX-USD',
                'friendly_name' => 'Leverate Real',
                'trading_server_type' => 'real',
                'created_at' => '2022-02-04 14:23:26',
                'updated_at' => '2022-02-04 14:23:26',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'url' => 'http://3.235.254.53:6502/v1',
                'server' => 'mtdemo1.leverate.com',
                'login' => '302',
                'password' => 'qDgi5er',
                'group' => 'demoSLJUSD',
                'friendly_name' => 'mtdemo1.leverate.com',
                'trading_server_type' => 'demo',
                'created_at' => '2022-02-04 14:23:26',
                'updated_at' => '2022-02-04 14:23:26',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'url' => 'http://3.235.254.53:6502/v1',
                'server' => 'mtdemo1.leverate.com',
                'login' => '2501',
                'password' => 'abc123',
                'group' => 'demoHFX-USD',
                'friendly_name' => 'Leverate Real',
                'trading_server_type' => 'real',
                'created_at' => '2022-02-04 14:23:26',
                'updated_at' => '2022-02-04 14:23:26',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}