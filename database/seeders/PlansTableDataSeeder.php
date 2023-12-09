<?php

namespace Database\Seeders;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PlansTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                "type" => "Express Demo",
                "title" => "Express Demo | 1M |15K",
                "description" => "Express Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "15000",
                "duration" => "30",
                "next_plan" => "5",
                "new_account_on_next_plan" => "1",
                "package_id" => "1",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Express Demo",
                "title" => "Express Demo | 1M |25K",
                "description" => "Express Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "25000",
                "duration" => "30",
                "next_plan" => "5",
                "new_account_on_next_plan" => "1",
                "package_id" => "1",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),

            ],

            [
                "type" => "Express Demo",
                "title" => "Express Demo | 1M |50K",
                "description" => "Express Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "50000",
                "duration" => "30",
                "next_plan" => "5",
                "new_account_on_next_plan" => "1",
                "package_id" => "1",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Express Demo",
                "title" => "Express Demo | 1M |100K",
                "description" => "Express Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "100000",
                
                "duration" => "30",
                "next_plan" => "5",
                "new_account_on_next_plan" => "1",
                "package_id" => "1",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],


            //Real 

            [
                "type" => "Express Real",
                "title" => "Express Real | 1M |15K",
                "description" => "Express Real 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "15000",
                
                "duration" => "30",
                "next_plan" => "5",
                "new_account_on_next_plan" => "0",
                "package_id" => "1",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Express Real",
                "title" => "Express Real | 1M |25K",
                "description" => "Express Real 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "25000",
                
                "duration" => "30",
                "next_plan" => "6",
                "new_account_on_next_plan" => "0",
                "package_id" => "1",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),

            ],

            [
                "type" => "Express Real",
                "title" => "Express Real | 1M |50K",
                "description" => "Express Real 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "50000",
                
                "duration" => "30",
                "next_plan" => "7",
                "new_account_on_next_plan" => "0",
                "package_id" => "1",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Express Real",
                "title" => "Express Real | 1M |100K",
                "description" => "Express Real 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "100000",
                
                "duration" => "30",
                "next_plan" => "8",
                "new_account_on_next_plan" => "0",
                "package_id" => "1",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),

            ],


            //Evaluation

            [
                "type" => "Evaluation P1",
                "title" => "Evaluation P1 | 1M |15K",
                "description" => "Evaluation Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "15000",
               
                "duration" => "30",
                "next_plan" => "14",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),

            ],
            
            [
                "type" => "Evaluation P1",
                "title" => "Evaluation P1 | 1M |25K",
                "description" => "Evaluation Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "25000",
                
                "duration" => "30",
                "next_plan" => "15",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P1",
                "title" => "Evaluation P1 | 1M |50K",
                "description" => "Evaluation Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "50000",
                
                "duration" => "30",
                "next_plan" => "16",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P1",
                "title" => "Evaluation P1|1M|100K",
                "description" => "Evaluation Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "100000",
                
                "duration" => "30",
                "next_plan" => "17",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P1",
                "title" => "Evaluation P1|1M|200K",
                "description" => "Evaluation Demo 1 Month Period",
                "leverage" => "100",
                "startingBalance" => "200000",
                
                "duration" => "30",
                "next_plan" => "18",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            //Evaluation For 60 Days
            [
                "type" => "Evaluation P2",
                "title" => "Evaluation P2|2M|15K",
                "description" => "Evaluation Demo 2 Month Period",
                "leverage" => "100",
                "startingBalance" => "15000",
                
                "duration" => "60",
                "next_plan" => "19",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P2",
                "title" => "Evaluation P2|2M|25K",
                "description" => "Evaluation Demo 2 Month Period",
                "leverage" => "100",
                "startingBalance" => "25000",
                
                "duration" => "60",
                "next_plan" => "20",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P2",
                "title" => "Evaluation P2|2M|50K",
                "description" => "Evaluation Demo 2 Month Period",
                "leverage" => "100",
                "startingBalance" => "50000",
                
                "duration" => "60",
                "next_plan" => "21",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P2",
                "title" => "Evaluation P2|2M|100K",
                "description" => "Evaluation Demo 2 Month Period",
                "leverage" => "100",
                "startingBalance" => "100000",
                
                "duration" => "60",
                "next_plan" => "22",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation P2",
                "title" => "Evaluation P2|2M|200K",
                "description" => "Evaluation Demo 2 Month Period",
                "leverage" => "100",
                "startingBalance" => "200000",
                
                "duration" => "60",
                "next_plan" => "23",
                "new_account_on_next_plan" => "1",
                "package_id" => "2",
                "server_id" => "1",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            //Evaluation For Real

            [
                "type" => "Evaluation Real",
                "title" => "Evaluation Real|1M|15K",
                "description" => "Evaluation Real 30 Period",
                "leverage" => "100",
                "startingBalance" => "15000",
                
                "duration" => "30",
                "next_plan" => "19",
                "new_account_on_next_plan" => "0",
                "package_id" => "2",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation Real",
                "title" => "Evaluation Real|1M|25K",
                "description" => "Evaluation Real 30 Period",
                "leverage" => "100",
                "startingBalance" => "25000",
                
                "duration" => "30",
                "next_plan" => "20",
                "new_account_on_next_plan" => "0",
                "package_id" => "2",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation Real",
                "title" => "Evaluation Real|1M|50K",
                "description" => "Evaluation Real 30 Period",
                "leverage" => "100",
                "startingBalance" => "50000",
               
                "duration" => "30",
                "next_plan" => "21",
                "new_account_on_next_plan" => "0",
                "package_id" => "2",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation Real",
                "title" => "Evaluation Real|1M|100K",
                "description" => "Evaluation Real 30 Period",
                "leverage" => "100",
                "startingBalance" => "100000",
                
                "duration" => "30",
                "next_plan" => "22",
                "new_account_on_next_plan" => "0",
                "package_id" => "2",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "type" => "Evaluation Real",
                "title" => "Evaluation Real|1M|200K",
                "description" => "Evaluation Real 30 Period",
                "leverage" => "100",
                "startingBalance" => "200000",
                
                "duration" => "30",
                "next_plan" => "23",
                "new_account_on_next_plan" => "0",
                "package_id" => "2",
                "server_id" => "2",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            ];

            Plan::insert($plans);
    
    }
}
