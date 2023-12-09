<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PaymentMethodPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->insert([
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_setting_menu',
                'updated_at' => NULL,
            ),
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_list',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_create',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_show',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_update',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_delete',
                'updated_at' => NULL,
            ),
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_country_list',
                'updated_at' => NULL,
            ),
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_country_category_swap',
                'updated_at' => NULL,
            ),
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_review_list',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_review',
                'updated_at' => NULL,
            ),
        ]);
    }
}
