<?php

namespace Database\Seeders;

use App\Models\AccountStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AccountStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountStatus::insert(
            [
                [
                    'id'         => 1,
                    "status"     => "Created",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 2,
                    "status"     => "Running",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 3,
                    "status"     => "Reset",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 4,
                    "status"     => "Paused",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 5,
                    "status"     => "Canceled",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 6,
                    "status"     => "Migrated",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 7,
                    "status"     => "Scaled Up",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'id'         => 8,
                    "status"     => "Evaluation Phase 1-2 Migration Request",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
            ]
        );
    }
}
