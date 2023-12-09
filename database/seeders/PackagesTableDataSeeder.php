<?php

namespace Database\Seeders;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PackagesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            [
                "name" => "Rapid",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "name" => "Evaluation",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]

        ];
        Package::insert($packages);
    }
}
