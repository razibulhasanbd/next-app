<?php

namespace Database\Seeders;

use App\Models\AccountStatusMessage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AccountStatusMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountStatusMessage::insert([
            [
                "id"         => 1,
                "message"    => "Daily Loss Limit",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 2,
                "message"    => "Monthly Loss Limit",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 3,
                "message"    => "Account Reset/Topup",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 4,
                "message"    => "Month Ended In Loss, MTD Fulfilled",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 5,
                "message"    => "Month End Loss, MTD not fulfilled",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 6,
                "message"    => "Month End Partial Profit, MTD not fulfilled",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 7,
                "message"    => "Month End Partial Profit, MTD Fulfilled",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 8,
                "message"    => "Profit Target Reached",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "id"         => 9,
                "message"    => "Admin Paused",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ]);
    }
}
