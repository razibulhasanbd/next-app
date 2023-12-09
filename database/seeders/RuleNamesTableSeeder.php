<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RuleNamesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('rule_names')->delete();
        
        \DB::table('rule_names')->insert(array (
            0 => 
            array (
                'condition' => 'DLL',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 1,
                'is_percent' => 1,
                'name' => 'Daily Loss Limit',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            1 => 
            array (
                'condition' => 'MLL',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 2,
                'is_percent' => 1,
                'name' => 'Monthly Loss Limit',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            2 => 
            array (
                'condition' => 'MTD',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 3,
                'is_percent' => 1,
                'name' => 'Minimum Trading Days',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            3 => 
            array (
                'condition' => 'PT',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 4,
                'is_percent' => 1,
                'name' => 'Profit Target',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            4 => 
            array (
                'condition' => 'SUP',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 5,
                'is_percent' => 1,
                'name' => 'Scale Up fund',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            5 => 
            array (
                'condition' => 'SUPT',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 6,
                'is_percent' => 1,
                'name' => 'Scale Up fund Target',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            6 => 
            array (
                'condition' => 'PS',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 7,
                'is_percent' => 1,
                'name' => 'Profit Share',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            7 => 
            array (
                'condition' => 'WH',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 8,
                'is_percent' => 1,
                'name' => 'Weekend Holding',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            8 => 
            array (
                'condition' => 'ANP',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 9,
                'is_percent' => 1,
                'name' => 'Auto Next Plan',
                'updated_at' => '2022-02-22 12:42:05',
            ),
            9 => 
            array (
                'condition' => 'FRL',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 10,
                'is_percent' => 1,
                'name' => 'Free Retake Limit',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            10 => 
            array (
                'condition' => 'CRD',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 11,
                'is_percent' => 0,
                'name' => 'Consistency Rule Deviation',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            11 => 
            array (
                'condition' => 'AGF',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 12,
                'is_percent' => 0,
                'name' => 'Add Growth Fund',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            12 => 
            array (
                'condition' => 'PSE',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 13,
                'is_percent' => 1,
                'name' => 'Pause Account On Subscription End',
                'updated_at' => '2022-02-22 12:20:51',
            ),
            13 => 
            array (
                'condition' => 'ATRA',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 14,
                'is_percent' => 1,
                'name' => 'Add to Target Reached Accounts',
                'updated_at' => '2022-02-22 12:20:51',
            ),

            13 => 
            array (
                'condition' => 'NCA',
                'created_at' => '2022-02-22 12:20:51',
                'deleted_at' => NULL,
                'id' => 15,
                'is_percent' => 1,
                'name' => ' ',
                'updated_at' => '2022-10-31 12:20:51',
            ),
        ));
        
        
    }
}