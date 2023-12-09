<?php

namespace Database\Seeders;

use App\Models\PlanRule;
use App\Models\RuleName;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RuleNamesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rule_names = [
            [
                "name" => "Daily Loss Limit",
                "is_percent" => "1",
                "condition" => "DLL",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Monthly Loss Limit",
                "is_percent" => "1",
                "condition" => "MLL",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Minimum Trading Days",
                "is_percent" => "1",
                "condition" => "MTD",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            
            [
                "name" => "Profit Target",
                "is_percent" => "1",
                "condition" => "PT",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Scale Up fund",
                "is_percent" => "1",
                "condition" => "SUP",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Scale Up fund Target",
                "is_percent" => "1",
                "condition" => "SUPT",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],


            [
                "name" => "Profit Share",
                "is_percent" => "1",
                "condition" => "PS",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Weekend Holding",
                "is_percent" => "1",
                "condition" => "WH",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            
            [
                "name" => "Next Plan",
                "is_percent" => "1",
                "condition" => "ANP",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Free Retake Limit",
                "is_percent" => "1",
                "condition" => "FRL",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Consistency Rule Deviation",
                "is_percent" => "0",
                "condition" => "CRD",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Add Growth Fund",
                "is_percent" => "0",
                "condition" => "AGF",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Pause Account On Subscription End",
                "is_percent" => "1",
                "condition" => "PSE",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            [
                "name" => "Add to Target Reached Accounts",
                "is_percent" => "1",
                "condition" => "ATRA",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

        ];
        RuleName::insert($rule_names);
    }
}
