<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class OrderManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert(
            array (

                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,

                    'title' => 'order_management_access',
                    'updated_at' => NULL,
                ),

                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,

                    'title' => 'order_access',
                    'updated_at' => NULL,
                ),

                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,

                    'title' => 'order_create',
                    'updated_at' => NULL,
                ),
                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,

                    'title' => 'order_show',
                    'updated_at' => NULL,
                ),
                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,

                    'title' => 'order_delete',
                    'updated_at' => NULL,
                ),
        ));
    }
}
