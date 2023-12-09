<?php

namespace Database\Seeders;

use App\Models\UtilityCategory;
use Illuminate\Database\Seeder;

class UtilityCategorySeerder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        UtilityCategory::insert(
            [
                [
                    'name' => 'Tool',
                    'order_value' => 1,
                    'status' => 1,
                ],
                [
                    'name' => 'E-Book',
                    'order_value' => 2,
                    'status' => 1,
                ],
                [
                    'name' => 'Video',
                    'order_value' => 3,
                    'status' => 1,
                ],
            ]

    );
    }
}
