<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 1,
                'title' => 'Admin',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 2,
                'title' => 'User',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}