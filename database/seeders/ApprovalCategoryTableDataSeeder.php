<?php

namespace Database\Seeders;

use App\Models\ApprovalCategory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ApprovalCategoryTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approval_category = [
            [
                'id'    => 1,
                'name' => 'Profit Target Reached',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                'id'    => 2,
                'name' => 'Month End Partially Profit',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                'id'    => 3,
                'name' => 'Free Retake',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ];
        ApprovalCategory::insert($approval_category);
    }
}
