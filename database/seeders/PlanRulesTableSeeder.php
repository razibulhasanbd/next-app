<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlanRulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('plan_rules')->delete();
        
        \DB::table('plan_rules')->insert(array (
            0 => 
            array (
                'id' => 5,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 1,
            ),
            1 => 
            array (
                'id' => 6,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 1,
            ),
            2 => 
            array (
                'id' => 7,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 1,
            ),
            3 => 
            array (
                'id' => 8,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 1,
            ),
            4 => 
            array (
                'id' => 9,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 1,
            ),
            5 => 
            array (
                'id' => 10,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 1,
            ),
            6 => 
            array (
                'id' => 11,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 2,
            ),
            7 => 
            array (
                'id' => 12,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 2,
            ),
            8 => 
            array (
                'id' => 13,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:34:45',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 2,
            ),
            9 => 
            array (
                'id' => 14,
                'value' => '25',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 2,
            ),
            10 => 
            array (
                'id' => 15,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 2,
            ),
            11 => 
            array (
                'id' => 16,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 2,
            ),
            12 => 
            array (
                'id' => 17,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 2,
            ),
            13 => 
            array (
                'id' => 18,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 2,
            ),
            14 => 
            array (
                'id' => 19,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 2,
            ),
            15 => 
            array (
                'id' => 20,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 2,
            ),
            16 => 
            array (
                'id' => 21,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 3,
            ),
            17 => 
            array (
                'id' => 22,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 3,
            ),
            18 => 
            array (
                'id' => 23,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:34:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 3,
            ),
            19 => 
            array (
                'id' => 24,
                'value' => '25',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 3,
            ),
            20 => 
            array (
                'id' => 25,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 3,
            ),
            21 => 
            array (
                'id' => 26,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 3,
            ),
            22 => 
            array (
                'id' => 27,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 3,
            ),
            23 => 
            array (
                'id' => 28,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 3,
            ),
            24 => 
            array (
                'id' => 29,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 3,
            ),
            25 => 
            array (
                'id' => 30,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 3,
            ),
            26 => 
            array (
                'id' => 31,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 4,
            ),
            27 => 
            array (
                'id' => 32,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 4,
            ),
            28 => 
            array (
                'id' => 33,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:34:56',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 4,
            ),
            29 => 
            array (
                'id' => 34,
                'value' => '25',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 4,
            ),
            30 => 
            array (
                'id' => 35,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 4,
            ),
            31 => 
            array (
                'id' => 36,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 4,
            ),
            32 => 
            array (
                'id' => 37,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 4,
            ),
            33 => 
            array (
                'id' => 38,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 4,
            ),
            34 => 
            array (
                'id' => 39,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 4,
            ),
            35 => 
            array (
                'id' => 40,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 4,
            ),
            36 => 
            array (
                'id' => 41,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 5,
            ),
            37 => 
            array (
                'id' => 42,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 5,
            ),
            38 => 
            array (
                'id' => 43,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:35:04',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 5,
            ),
            39 => 
            array (
                'id' => 44,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 5,
            ),
            40 => 
            array (
                'id' => 45,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 5,
            ),
            41 => 
            array (
                'id' => 46,
                'value' => '60',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 5,
            ),
            42 => 
            array (
                'id' => 47,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 5,
            ),
            43 => 
            array (
                'id' => 48,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 5,
            ),
            44 => 
            array (
                'id' => 49,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 5,
            ),
            45 => 
            array (
                'id' => 50,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 6,
            ),
            46 => 
            array (
                'id' => 51,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 6,
            ),
            47 => 
            array (
                'id' => 52,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:35:21',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 6,
            ),
            48 => 
            array (
                'id' => 53,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 6,
            ),
            49 => 
            array (
                'id' => 54,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 6,
            ),
            50 => 
            array (
                'id' => 55,
                'value' => '60',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 6,
            ),
            51 => 
            array (
                'id' => 56,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 6,
            ),
            52 => 
            array (
                'id' => 57,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 6,
            ),
            53 => 
            array (
                'id' => 58,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 6,
            ),
            54 => 
            array (
                'id' => 59,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 7,
            ),
            55 => 
            array (
                'id' => 60,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 7,
            ),
            56 => 
            array (
                'id' => 61,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:35:25',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 7,
            ),
            57 => 
            array (
                'id' => 62,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 7,
            ),
            58 => 
            array (
                'id' => 63,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 7,
            ),
            59 => 
            array (
                'id' => 64,
                'value' => '60',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 7,
            ),
            60 => 
            array (
                'id' => 65,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 7,
            ),
            61 => 
            array (
                'id' => 66,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 7,
            ),
            62 => 
            array (
                'id' => 67,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 7,
            ),
            63 => 
            array (
                'id' => 68,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 8,
            ),
            64 => 
            array (
                'id' => 69,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 8,
            ),
            65 => 
            array (
                'id' => 70,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:35:31',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 8,
            ),
            66 => 
            array (
                'id' => 71,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 8,
            ),
            67 => 
            array (
                'id' => 72,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 8,
            ),
            68 => 
            array (
                'id' => 73,
                'value' => '60',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 8,
            ),
            69 => 
            array (
                'id' => 74,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 8,
            ),
            70 => 
            array (
                'id' => 75,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 8,
            ),
            71 => 
            array (
                'id' => 76,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 8,
            ),
            72 => 
            array (
                'id' => 77,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 9,
            ),
            73 => 
            array (
                'id' => 78,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 9,
            ),
            74 => 
            array (
                'id' => 79,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 9,
            ),
            75 => 
            array (
                'id' => 80,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 9,
            ),
            76 => 
            array (
                'id' => 81,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 9,
            ),
            77 => 
            array (
                'id' => 82,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 9,
            ),
            78 => 
            array (
                'id' => 83,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 9,
            ),
            79 => 
            array (
                'id' => 84,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:39',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 9,
            ),
            80 => 
            array (
                'id' => 85,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 9,
            ),
            81 => 
            array (
                'id' => 86,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 9,
            ),
            82 => 
            array (
                'id' => 87,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 10,
            ),
            83 => 
            array (
                'id' => 88,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 10,
            ),
            84 => 
            array (
                'id' => 89,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 10,
            ),
            85 => 
            array (
                'id' => 90,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 10,
            ),
            86 => 
            array (
                'id' => 91,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 10,
            ),
            87 => 
            array (
                'id' => 92,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 10,
            ),
            88 => 
            array (
                'id' => 93,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 10,
            ),
            89 => 
            array (
                'id' => 94,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:18',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 10,
            ),
            90 => 
            array (
                'id' => 95,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 10,
            ),
            91 => 
            array (
                'id' => 96,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 10,
            ),
            92 => 
            array (
                'id' => 97,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 11,
            ),
            93 => 
            array (
                'id' => 98,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 11,
            ),
            94 => 
            array (
                'id' => 99,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 11,
            ),
            95 => 
            array (
                'id' => 100,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 11,
            ),
            96 => 
            array (
                'id' => 101,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 11,
            ),
            97 => 
            array (
                'id' => 102,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 11,
            ),
            98 => 
            array (
                'id' => 103,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 11,
            ),
            99 => 
            array (
                'id' => 104,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:39',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 11,
            ),
            100 => 
            array (
                'id' => 105,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 11,
            ),
            101 => 
            array (
                'id' => 106,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 11,
            ),
            102 => 
            array (
                'id' => 107,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 12,
            ),
            103 => 
            array (
                'id' => 108,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 12,
            ),
            104 => 
            array (
                'id' => 109,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 12,
            ),
            105 => 
            array (
                'id' => 110,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 12,
            ),
            106 => 
            array (
                'id' => 111,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 12,
            ),
            107 => 
            array (
                'id' => 112,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 12,
            ),
            108 => 
            array (
                'id' => 113,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 12,
            ),
            109 => 
            array (
                'id' => 114,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:39',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 12,
            ),
            110 => 
            array (
                'id' => 115,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 12,
            ),
            111 => 
            array (
                'id' => 116,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 12,
            ),
            112 => 
            array (
                'id' => 117,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 13,
            ),
            113 => 
            array (
                'id' => 118,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 13,
            ),
            114 => 
            array (
                'id' => 119,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 13,
            ),
            115 => 
            array (
                'id' => 120,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 13,
            ),
            116 => 
            array (
                'id' => 121,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 13,
            ),
            117 => 
            array (
                'id' => 122,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 13,
            ),
            118 => 
            array (
                'id' => 123,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 13,
            ),
            119 => 
            array (
                'id' => 124,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:39',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 13,
            ),
            120 => 
            array (
                'id' => 125,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 13,
            ),
            121 => 
            array (
                'id' => 126,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 13,
            ),
            122 => 
            array (
                'id' => 127,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 14,
            ),
            123 => 
            array (
                'id' => 128,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 14,
            ),
            124 => 
            array (
                'id' => 129,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 14,
            ),
            125 => 
            array (
                'id' => 130,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 14,
            ),
            126 => 
            array (
                'id' => 131,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 14,
            ),
            127 => 
            array (
                'id' => 132,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 14,
            ),
            128 => 
            array (
                'id' => 133,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 14,
            ),
            129 => 
            array (
                'id' => 134,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 14,
            ),
            130 => 
            array (
                'id' => 135,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 14,
            ),
            131 => 
            array (
                'id' => 136,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 15,
            ),
            132 => 
            array (
                'id' => 137,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 15,
            ),
            133 => 
            array (
                'id' => 138,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 15,
            ),
            134 => 
            array (
                'id' => 139,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 15,
            ),
            135 => 
            array (
                'id' => 140,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 15,
            ),
            136 => 
            array (
                'id' => 141,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 15,
            ),
            137 => 
            array (
                'id' => 142,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 15,
            ),
            138 => 
            array (
                'id' => 143,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 15,
            ),
            139 => 
            array (
                'id' => 144,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 15,
            ),
            140 => 
            array (
                'id' => 145,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 16,
            ),
            141 => 
            array (
                'id' => 146,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 16,
            ),
            142 => 
            array (
                'id' => 147,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 16,
            ),
            143 => 
            array (
                'id' => 148,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 16,
            ),
            144 => 
            array (
                'id' => 149,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 16,
            ),
            145 => 
            array (
                'id' => 150,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 16,
            ),
            146 => 
            array (
                'id' => 151,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 16,
            ),
            147 => 
            array (
                'id' => 152,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 16,
            ),
            148 => 
            array (
                'id' => 153,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 16,
            ),
            149 => 
            array (
                'id' => 154,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 17,
            ),
            150 => 
            array (
                'id' => 155,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 17,
            ),
            151 => 
            array (
                'id' => 156,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 17,
            ),
            152 => 
            array (
                'id' => 157,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 17,
            ),
            153 => 
            array (
                'id' => 158,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 17,
            ),
            154 => 
            array (
                'id' => 159,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 17,
            ),
            155 => 
            array (
                'id' => 160,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 17,
            ),
            156 => 
            array (
                'id' => 161,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 17,
            ),
            157 => 
            array (
                'id' => 162,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 17,
            ),
            158 => 
            array (
                'id' => 163,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 18,
            ),
            159 => 
            array (
                'id' => 164,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 18,
            ),
            160 => 
            array (
                'id' => 165,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 18,
            ),
            161 => 
            array (
                'id' => 166,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 18,
            ),
            162 => 
            array (
                'id' => 167,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 18,
            ),
            163 => 
            array (
                'id' => 168,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 18,
            ),
            164 => 
            array (
                'id' => 169,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 18,
            ),
            165 => 
            array (
                'id' => 170,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 18,
            ),
            166 => 
            array (
                'id' => 171,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 18,
            ),
            167 => 
            array (
                'id' => 172,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 19,
            ),
            168 => 
            array (
                'id' => 173,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 19,
            ),
            169 => 
            array (
                'id' => 175,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 19,
            ),
            170 => 
            array (
                'id' => 176,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 19,
            ),
            171 => 
            array (
                'id' => 177,
                'value' => '80',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 19,
            ),
            172 => 
            array (
                'id' => 178,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 19,
            ),
            173 => 
            array (
                'id' => 179,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 19,
            ),
            174 => 
            array (
                'id' => 180,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 19,
            ),
            175 => 
            array (
                'id' => 181,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 20,
            ),
            176 => 
            array (
                'id' => 182,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 20,
            ),
            177 => 
            array (
                'id' => 184,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 20,
            ),
            178 => 
            array (
                'id' => 185,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 20,
            ),
            179 => 
            array (
                'id' => 186,
                'value' => '80',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 20,
            ),
            180 => 
            array (
                'id' => 187,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 20,
            ),
            181 => 
            array (
                'id' => 188,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 20,
            ),
            182 => 
            array (
                'id' => 189,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 20,
            ),
            183 => 
            array (
                'id' => 190,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 21,
            ),
            184 => 
            array (
                'id' => 191,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 21,
            ),
            185 => 
            array (
                'id' => 193,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 21,
            ),
            186 => 
            array (
                'id' => 194,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 21,
            ),
            187 => 
            array (
                'id' => 195,
                'value' => '80',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 21,
            ),
            188 => 
            array (
                'id' => 196,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 21,
            ),
            189 => 
            array (
                'id' => 197,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 21,
            ),
            190 => 
            array (
                'id' => 198,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 21,
            ),
            191 => 
            array (
                'id' => 199,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 22,
            ),
            192 => 
            array (
                'id' => 200,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 22,
            ),
            193 => 
            array (
                'id' => 202,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 22,
            ),
            194 => 
            array (
                'id' => 203,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 22,
            ),
            195 => 
            array (
                'id' => 204,
                'value' => '80',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 22,
            ),
            196 => 
            array (
                'id' => 205,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 22,
            ),
            197 => 
            array (
                'id' => 206,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 22,
            ),
            198 => 
            array (
                'id' => 207,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 22,
            ),
            199 => 
            array (
                'id' => 208,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 23,
            ),
            200 => 
            array (
                'id' => 209,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 23,
            ),
            201 => 
            array (
                'id' => 211,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 23,
            ),
            202 => 
            array (
                'id' => 212,
                'value' => '8',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 23,
            ),
            203 => 
            array (
                'id' => 213,
                'value' => '80',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 23,
            ),
            204 => 
            array (
                'id' => 214,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 23,
            ),
            205 => 
            array (
                'id' => 215,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 23,
            ),
            206 => 
            array (
                'id' => 216,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 23,
            ),
            207 => 
            array (
                'id' => 217,
                'value' => '1',
                'created_at' => '2022-02-25 14:21:32',
                'updated_at' => '2022-02-25 14:21:32',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 12,
            ),
            208 => 
            array (
                'id' => 218,
                'value' => '1',
                'created_at' => '2022-02-28 17:03:44',
                'updated_at' => '2022-02-28 17:03:44',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 9,
            ),
            209 => 
            array (
                'id' => 219,
                'value' => '1',
                'created_at' => '2022-02-28 17:03:56',
                'updated_at' => '2022-02-28 17:03:56',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 10,
            ),
            210 => 
            array (
                'id' => 220,
                'value' => '1',
                'created_at' => '2022-02-28 17:04:06',
                'updated_at' => '2022-02-28 17:04:06',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 11,
            ),
            211 => 
            array (
                'id' => 221,
                'value' => '1',
                'created_at' => '2022-02-28 17:04:20',
                'updated_at' => '2022-02-28 17:04:20',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 13,
            ),
            212 => 
            array (
                'id' => 222,
                'value' => '1',
                'created_at' => '2022-02-28 17:04:50',
                'updated_at' => '2022-02-28 17:04:50',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 14,
            ),
            213 => 
            array (
                'id' => 223,
                'value' => '1',
                'created_at' => '2022-02-28 17:05:03',
                'updated_at' => '2022-02-28 17:05:03',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 15,
            ),
            214 => 
            array (
                'id' => 224,
                'value' => '1',
                'created_at' => '2022-02-28 17:05:27',
                'updated_at' => '2022-02-28 17:05:27',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 16,
            ),
            215 => 
            array (
                'id' => 225,
                'value' => '1',
                'created_at' => '2022-02-28 17:05:40',
                'updated_at' => '2022-02-28 17:05:40',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 17,
            ),
            216 => 
            array (
                'id' => 226,
                'value' => '1',
                'created_at' => '2022-02-28 17:05:58',
                'updated_at' => '2022-02-28 17:05:58',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 18,
            ),
            217 => 
            array (
                'id' => 227,
                'value' => '10',
                'created_at' => '2022-04-13 15:45:32',
                'updated_at' => '2022-07-04 15:35:37',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 1,
            ),
            218 => 
            array (
                'id' => 228,
                'value' => '5',
                'created_at' => '2022-04-13 16:23:25',
                'updated_at' => '2022-04-13 16:23:25',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 1,
            ),
            219 => 
            array (
                'id' => 229,
                'value' => '25',
                'created_at' => '2022-04-13 16:23:51',
                'updated_at' => '2022-04-13 16:23:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 1,
            ),
            220 => 
            array (
                'id' => 230,
                'value' => '10',
                'created_at' => '2022-04-13 16:24:10',
                'updated_at' => '2022-04-13 16:24:10',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 1,
            ),
            221 => 
            array (
                'id' => 231,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 24,
            ),
            222 => 
            array (
                'id' => 232,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 24,
            ),
            223 => 
            array (
                'id' => 233,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 24,
            ),
            224 => 
            array (
                'id' => 234,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 24,
            ),
            225 => 
            array (
                'id' => 235,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 24,
            ),
            226 => 
            array (
                'id' => 236,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 24,
            ),
            227 => 
            array (
                'id' => 237,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 25,
            ),
            228 => 
            array (
                'id' => 238,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 25,
            ),
            229 => 
            array (
                'id' => 239,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:36:37',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 25,
            ),
            230 => 
            array (
                'id' => 240,
                'value' => '25',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 25,
            ),
            231 => 
            array (
                'id' => 241,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 25,
            ),
            232 => 
            array (
                'id' => 242,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 25,
            ),
            233 => 
            array (
                'id' => 243,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 25,
            ),
            234 => 
            array (
                'id' => 244,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 25,
            ),
            235 => 
            array (
                'id' => 245,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 25,
            ),
            236 => 
            array (
                'id' => 246,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 25,
            ),
            237 => 
            array (
                'id' => 247,
                'value' => '10',
                'created_at' => '2022-04-13 15:45:32',
                'updated_at' => '2022-07-04 15:35:49',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 24,
            ),
            238 => 
            array (
                'id' => 248,
                'value' => '5',
                'created_at' => '2022-04-13 16:23:25',
                'updated_at' => '2022-04-13 16:23:25',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 24,
            ),
            239 => 
            array (
                'id' => 249,
                'value' => '25',
                'created_at' => '2022-04-13 16:23:51',
                'updated_at' => '2022-04-13 16:23:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 24,
            ),
            240 => 
            array (
                'id' => 250,
                'value' => '10',
                'created_at' => '2022-04-13 16:24:10',
                'updated_at' => '2022-04-13 16:24:10',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 24,
            ),
            241 => 
            array (
                'id' => 251,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 26,
            ),
            242 => 
            array (
                'id' => 252,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 26,
            ),
            243 => 
            array (
                'id' => 253,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:35:55',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 26,
            ),
            244 => 
            array (
                'id' => 254,
                'value' => '25',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 26,
            ),
            245 => 
            array (
                'id' => 255,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 26,
            ),
            246 => 
            array (
                'id' => 256,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 26,
            ),
            247 => 
            array (
                'id' => 257,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 26,
            ),
            248 => 
            array (
                'id' => 258,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 26,
            ),
            249 => 
            array (
                'id' => 259,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 26,
            ),
            250 => 
            array (
                'id' => 260,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 26,
            ),
            251 => 
            array (
                'id' => 261,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 27,
            ),
            252 => 
            array (
                'id' => 262,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 27,
            ),
            253 => 
            array (
                'id' => 263,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-07-04 15:36:01',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 27,
            ),
            254 => 
            array (
                'id' => 264,
                'value' => '25',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 27,
            ),
            255 => 
            array (
                'id' => 265,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 27,
            ),
            256 => 
            array (
                'id' => 266,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 27,
            ),
            257 => 
            array (
                'id' => 267,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 27,
            ),
            258 => 
            array (
                'id' => 268,
                'value' => '2',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 27,
            ),
            259 => 
            array (
                'id' => 269,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 12,
                'plan_id' => 27,
            ),
            260 => 
            array (
                'id' => 270,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 27,
            ),
            261 => 
            array (
                'id' => 271,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 28,
            ),
            262 => 
            array (
                'id' => 272,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 28,
            ),
            263 => 
            array (
                'id' => 273,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 28,
            ),
            264 => 
            array (
                'id' => 274,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 28,
            ),
            265 => 
            array (
                'id' => 275,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 28,
            ),
            266 => 
            array (
                'id' => 276,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 28,
            ),
            267 => 
            array (
                'id' => 277,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 28,
            ),
            268 => 
            array (
                'id' => 278,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:39',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 28,
            ),
            269 => 
            array (
                'id' => 279,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 28,
            ),
            270 => 
            array (
                'id' => 280,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 28,
            ),
            271 => 
            array (
                'id' => 281,
                'value' => '1',
                'created_at' => '2022-02-28 17:03:44',
                'updated_at' => '2022-02-28 17:03:44',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 28,
            ),
            272 => 
            array (
                'id' => 282,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 33,
            ),
            273 => 
            array (
                'id' => 283,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 33,
            ),
            274 => 
            array (
                'id' => 284,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 33,
            ),
            275 => 
            array (
                'id' => 285,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 33,
            ),
            276 => 
            array (
                'id' => 286,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 33,
            ),
            277 => 
            array (
                'id' => 287,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 33,
            ),
            278 => 
            array (
                'id' => 288,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 33,
            ),
            279 => 
            array (
                'id' => 289,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 33,
            ),
            280 => 
            array (
                'id' => 290,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 33,
            ),
            281 => 
            array (
                'id' => 291,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 33,
            ),
            282 => 
            array (
                'id' => 297,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 34,
            ),
            283 => 
            array (
                'id' => 298,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 34,
            ),
            284 => 
            array (
                'id' => 299,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 34,
            ),
            285 => 
            array (
                'id' => 300,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 34,
            ),
            286 => 
            array (
                'id' => 301,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 34,
            ),
            287 => 
            array (
                'id' => 302,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 34,
            ),
            288 => 
            array (
                'id' => 303,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 34,
            ),
            289 => 
            array (
                'id' => 304,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 34,
            ),
            290 => 
            array (
                'id' => 305,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 34,
            ),
            291 => 
            array (
                'id' => 306,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 34,
            ),
            292 => 
            array (
                'id' => 312,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 35,
            ),
            293 => 
            array (
                'id' => 313,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 35,
            ),
            294 => 
            array (
                'id' => 314,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 35,
            ),
            295 => 
            array (
                'id' => 315,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 35,
            ),
            296 => 
            array (
                'id' => 316,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 35,
            ),
            297 => 
            array (
                'id' => 317,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 35,
            ),
            298 => 
            array (
                'id' => 318,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 35,
            ),
            299 => 
            array (
                'id' => 319,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 35,
            ),
            300 => 
            array (
                'id' => 320,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 35,
            ),
            301 => 
            array (
                'id' => 321,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 35,
            ),
            302 => 
            array (
                'id' => 327,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 36,
            ),
            303 => 
            array (
                'id' => 328,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 36,
            ),
            304 => 
            array (
                'id' => 329,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 36,
            ),
            305 => 
            array (
                'id' => 330,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 36,
            ),
            306 => 
            array (
                'id' => 331,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 36,
            ),
            307 => 
            array (
                'id' => 332,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 36,
            ),
            308 => 
            array (
                'id' => 333,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 36,
            ),
            309 => 
            array (
                'id' => 334,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 36,
            ),
            310 => 
            array (
                'id' => 335,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 36,
            ),
            311 => 
            array (
                'id' => 336,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 36,
            ),
            312 => 
            array (
                'id' => 342,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 37,
            ),
            313 => 
            array (
                'id' => 343,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 37,
            ),
            314 => 
            array (
                'id' => 344,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 37,
            ),
            315 => 
            array (
                'id' => 345,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 37,
            ),
            316 => 
            array (
                'id' => 346,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 37,
            ),
            317 => 
            array (
                'id' => 347,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 37,
            ),
            318 => 
            array (
                'id' => 348,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 37,
            ),
            319 => 
            array (
                'id' => 349,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 37,
            ),
            320 => 
            array (
                'id' => 350,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 37,
            ),
            321 => 
            array (
                'id' => 351,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 37,
            ),
            322 => 
            array (
                'id' => 357,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 29,
            ),
            323 => 
            array (
                'id' => 358,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 29,
            ),
            324 => 
            array (
                'id' => 359,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 29,
            ),
            325 => 
            array (
                'id' => 360,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 29,
            ),
            326 => 
            array (
                'id' => 361,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 29,
            ),
            327 => 
            array (
                'id' => 362,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 29,
            ),
            328 => 
            array (
                'id' => 363,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 29,
            ),
            329 => 
            array (
                'id' => 364,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:18',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 29,
            ),
            330 => 
            array (
                'id' => 365,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 29,
            ),
            331 => 
            array (
                'id' => 366,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 29,
            ),
            332 => 
            array (
                'id' => 367,
                'value' => '1',
                'created_at' => '2022-02-28 17:03:56',
                'updated_at' => '2022-02-28 17:03:56',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 29,
            ),
            333 => 
            array (
                'id' => 368,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 30,
            ),
            334 => 
            array (
                'id' => 369,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 30,
            ),
            335 => 
            array (
                'id' => 370,
                'value' => '5',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 30,
            ),
            336 => 
            array (
                'id' => 371,
                'value' => '10',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 30,
            ),
            337 => 
            array (
                'id' => 372,
                'value' => '0',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 30,
            ),
            338 => 
            array (
                'id' => 373,
                'value' => '15',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 30,
            ),
            339 => 
            array (
                'id' => 374,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 30,
            ),
            340 => 
            array (
                'id' => 375,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:53:39',
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 30,
            ),
            341 => 
            array (
                'id' => 376,
                'value' => '100',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 30,
            ),
            342 => 
            array (
                'id' => 377,
                'value' => '1',
                'created_at' => '2022-02-22 18:20:51',
                'updated_at' => '2022-02-22 18:20:51',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 30,
            ),
            343 => 
            array (
                'id' => 378,
                'value' => '1',
                'created_at' => '2022-02-28 17:04:06',
                'updated_at' => '2022-02-28 17:04:06',
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 30,
            ),
            344 => 
            array (
                'id' => 379,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 31,
            ),
            345 => 
            array (
                'id' => 380,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 31,
            ),
            346 => 
            array (
                'id' => 381,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 31,
            ),
            347 => 
            array (
                'id' => 382,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 31,
            ),
            348 => 
            array (
                'id' => 383,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 31,
            ),
            349 => 
            array (
                'id' => 384,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 31,
            ),
            350 => 
            array (
                'id' => 385,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 31,
            ),
            351 => 
            array (
                'id' => 386,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 31,
            ),
            352 => 
            array (
                'id' => 387,
                'value' => '100',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 31,
            ),
            353 => 
            array (
                'id' => 388,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 31,
            ),
            354 => 
            array (
                'id' => 389,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 31,
            ),
            355 => 
            array (
                'id' => 394,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 32,
            ),
            356 => 
            array (
                'id' => 395,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 32,
            ),
            357 => 
            array (
                'id' => 396,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 32,
            ),
            358 => 
            array (
                'id' => 397,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 4,
                'plan_id' => 32,
            ),
            359 => 
            array (
                'id' => 398,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 32,
            ),
            360 => 
            array (
                'id' => 399,
                'value' => '15',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 32,
            ),
            361 => 
            array (
                'id' => 400,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 32,
            ),
            362 => 
            array (
                'id' => 401,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 9,
                'plan_id' => 32,
            ),
            363 => 
            array (
                'id' => 402,
                'value' => '100',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 32,
            ),
            364 => 
            array (
                'id' => 403,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 32,
            ),
            365 => 
            array (
                'id' => 404,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 32,
            ),
            366 => 
            array (
                'id' => 409,
                'value' => '1',
                'created_at' => '2022-06-29 16:57:44',
                'updated_at' => '2022-06-29 16:57:44',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 24,
            ),
            367 => 
            array (
                'id' => 410,
                'value' => '1',
                'created_at' => '2022-06-29 16:57:44',
                'updated_at' => '2022-06-29 16:57:44',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 25,
            ),
            368 => 
            array (
                'id' => 411,
                'value' => '1',
                'created_at' => '2022-06-29 16:57:44',
                'updated_at' => '2022-06-29 16:57:44',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 26,
            ),
            369 => 
            array (
                'id' => 412,
                'value' => '1',
                'created_at' => '2022-06-29 16:57:44',
                'updated_at' => '2022-06-29 16:57:44',
                'deleted_at' => NULL,
                'rule_name_id' => 13,
                'plan_id' => 27,
            ),
            370 => 
            array (
                'id' => 413,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 38,
            ),
            371 => 
            array (
                'id' => 414,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 38,
            ),
            372 => 
            array (
                'id' => 415,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 38,
            ),
            373 => 
            array (
                'id' => 416,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 38,
            ),
            374 => 
            array (
                'id' => 417,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 38,
            ),
            375 => 
            array (
                'id' => 418,
                'value' => '60',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 38,
            ),
            376 => 
            array (
                'id' => 419,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 38,
            ),
            377 => 
            array (
                'id' => 420,
                'value' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 38,
            ),
            378 => 
            array (
                'id' => 421,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 38,
            ),
            379 => 
            array (
                'id' => 428,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 39,
            ),
            380 => 
            array (
                'id' => 429,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 39,
            ),
            381 => 
            array (
                'id' => 430,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 39,
            ),
            382 => 
            array (
                'id' => 431,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 39,
            ),
            383 => 
            array (
                'id' => 432,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 39,
            ),
            384 => 
            array (
                'id' => 433,
                'value' => '60',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 39,
            ),
            385 => 
            array (
                'id' => 434,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 39,
            ),
            386 => 
            array (
                'id' => 435,
                'value' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 39,
            ),
            387 => 
            array (
                'id' => 436,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 39,
            ),
            388 => 
            array (
                'id' => 443,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 40,
            ),
            389 => 
            array (
                'id' => 444,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 40,
            ),
            390 => 
            array (
                'id' => 445,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 40,
            ),
            391 => 
            array (
                'id' => 446,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 40,
            ),
            392 => 
            array (
                'id' => 447,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 40,
            ),
            393 => 
            array (
                'id' => 448,
                'value' => '60',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 40,
            ),
            394 => 
            array (
                'id' => 449,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 40,
            ),
            395 => 
            array (
                'id' => 450,
                'value' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 40,
            ),
            396 => 
            array (
                'id' => 451,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 40,
            ),
            397 => 
            array (
                'id' => 458,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 41,
            ),
            398 => 
            array (
                'id' => 459,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 41,
            ),
            399 => 
            array (
                'id' => 460,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 3,
                'plan_id' => 41,
            ),
            400 => 
            array (
                'id' => 461,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 41,
            ),
            401 => 
            array (
                'id' => 462,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 41,
            ),
            402 => 
            array (
                'id' => 463,
                'value' => '60',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 41,
            ),
            403 => 
            array (
                'id' => 464,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 41,
            ),
            404 => 
            array (
                'id' => 465,
                'value' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 11,
                'plan_id' => 41,
            ),
            405 => 
            array (
                'id' => 466,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 41,
            ),
            406 => 
            array (
                'id' => 473,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 42,
            ),
            407 => 
            array (
                'id' => 474,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 42,
            ),
            408 => 
            array (
                'id' => 475,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 42,
            ),
            409 => 
            array (
                'id' => 476,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 42,
            ),
            410 => 
            array (
                'id' => 477,
                'value' => '80',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 42,
            ),
            411 => 
            array (
                'id' => 478,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 42,
            ),
            412 => 
            array (
                'id' => 479,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 42,
            ),
            413 => 
            array (
                'id' => 480,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 42,
            ),
            414 => 
            array (
                'id' => 488,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 43,
            ),
            415 => 
            array (
                'id' => 489,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 43,
            ),
            416 => 
            array (
                'id' => 490,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 43,
            ),
            417 => 
            array (
                'id' => 491,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 43,
            ),
            418 => 
            array (
                'id' => 492,
                'value' => '80',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 43,
            ),
            419 => 
            array (
                'id' => 493,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 43,
            ),
            420 => 
            array (
                'id' => 494,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 43,
            ),
            421 => 
            array (
                'id' => 495,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 43,
            ),
            422 => 
            array (
                'id' => 503,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 44,
            ),
            423 => 
            array (
                'id' => 504,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 44,
            ),
            424 => 
            array (
                'id' => 505,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 44,
            ),
            425 => 
            array (
                'id' => 506,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 44,
            ),
            426 => 
            array (
                'id' => 507,
                'value' => '80',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 44,
            ),
            427 => 
            array (
                'id' => 508,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 44,
            ),
            428 => 
            array (
                'id' => 509,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 44,
            ),
            429 => 
            array (
                'id' => 510,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 44,
            ),
            430 => 
            array (
                'id' => 518,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 45,
            ),
            431 => 
            array (
                'id' => 519,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 45,
            ),
            432 => 
            array (
                'id' => 520,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 45,
            ),
            433 => 
            array (
                'id' => 521,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 45,
            ),
            434 => 
            array (
                'id' => 522,
                'value' => '80',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 45,
            ),
            435 => 
            array (
                'id' => 523,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 45,
            ),
            436 => 
            array (
                'id' => 524,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 45,
            ),
            437 => 
            array (
                'id' => 525,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 45,
            ),
            438 => 
            array (
                'id' => 533,
                'value' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 1,
                'plan_id' => 46,
            ),
            439 => 
            array (
                'id' => 534,
                'value' => '10',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 2,
                'plan_id' => 46,
            ),
            440 => 
            array (
                'id' => 535,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 5,
                'plan_id' => 46,
            ),
            441 => 
            array (
                'id' => 536,
                'value' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 6,
                'plan_id' => 46,
            ),
            442 => 
            array (
                'id' => 537,
                'value' => '80',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 7,
                'plan_id' => 46,
            ),
            443 => 
            array (
                'id' => 538,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 8,
                'plan_id' => 46,
            ),
            444 => 
            array (
                'id' => 539,
                'value' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 10,
                'plan_id' => 46,
            ),
            445 => 
            array (
                'id' => 540,
                'value' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'rule_name_id' => 14,
                'plan_id' => 46,
            ),
        ));
        
        
    }
}