<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ApprovalCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('approval_categories')->delete();
        
        \DB::table('approval_categories')->insert(array (
            0 => 
            array (
                'created_at' => '2022-02-16 10:19:05',
                'deleted_at' => NULL,
                'id' => 1,
                'name' => 'Profit Target Reached',
                'updated_at' => '2022-02-16 10:19:05',
            ),
            1 => 
            array (
                'created_at' => '2022-02-16 10:19:05',
                'deleted_at' => NULL,
                'id' => 2,
                'name' => 'Month End Partially Profit',
                'updated_at' => '2022-02-16 10:19:05',
            ),
            2 => 
            array (
                'created_at' => '2022-02-16 10:19:05',
                'deleted_at' => NULL,
                'id' => 3,
                'name' => 'Free Retake',
                'updated_at' => '2022-02-16 10:19:05',
            ),
        ));
        
        
    }
}