<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductOrderTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->insert(array(
            0 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_and_order_system_access',
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'business_model_create',
                'updated_at' => NULL,
            ),
            2 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'business_model_edit',
                'updated_at' => NULL,
            ),
            3 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'business_model_show',
                'updated_at' => NULL,
            ),
            4 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'business_model_access',
                'updated_at' => NULL,
            ),
            5 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'model_varient_create',
                'updated_at' => NULL,
            ),
            6 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'model_varient_edit',
                'updated_at' => NULL,
            ),
            7 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'model_varient_show',
                'updated_at' => NULL,
            ),
            8 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'model_varient_access',
                'updated_at' => NULL,
            ),
            9 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_create',
                'updated_at' => NULL,
            ),
            10 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_edit',
                'updated_at' => NULL,
            ),
            11 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_show',
                'updated_at' => NULL,
            ),
            12 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_delete',
                'updated_at' => NULL,
            ),
            13 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_access',
                'updated_at' => NULL,
            ),
            14 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_detail_create',
                'updated_at' => NULL,
            ),
            15 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_detail_edit',
                'updated_at' => NULL,
            ),
            16 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_detail_show',
                'updated_at' => NULL,
            ),
            17 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_detail_delete',
                'updated_at' => NULL,
            ),
            18 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'title' => 'product_detail_access',
                'updated_at' => NULL,
            ),

        ));
    }
}
