<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class AccountPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert(
            [
                [
                    "title"      => "account_password_reset",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
            ]
        );
    }
}
