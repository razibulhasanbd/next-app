<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('packages')->delete();
        
        \DB::table('packages')->insert(array (
            0 => 
            array (
                'created_at' => '2022-02-04 08:23:26',
                'deleted_at' => NULL,
                'id' => 1,
                'name' => 'Rapid',
                'updated_at' => '2022-02-04 08:23:26',
            ),
            1 => 
            array (
                'created_at' => '2022-02-04 08:23:26',
                'deleted_at' => NULL,
                'id' => 2,
                'name' => 'Evaluation',
                'updated_at' => '2022-02-04 08:23:26',
            ),
            2 => 
            array (
                'created_at' => '2022-02-16 10:19:05',
                'deleted_at' => NULL,
                'id' => 3,
                'name' => 'Rapid',
                'updated_at' => '2022-02-16 10:19:05',
            ),
            3 => 
            array (
                'created_at' => '2022-02-16 10:19:05',
                'deleted_at' => NULL,
                'id' => 4,
                'name' => 'Evaluation',
                'updated_at' => '2022-02-16 10:19:05',
            ),
        ));
        
        
    }
}