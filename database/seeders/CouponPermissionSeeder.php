<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CouponPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run()
     {

        DB::table('permissions')->insert(array (
            231 =>
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 232,
                'title' => 'coupon_create',
                'updated_at' => NULL,
            ),
            232=>
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 233,
                'title' => 'coupon_edit',
                'updated_at' => NULL,
            ),
            233 =>
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 234,
                'title' => 'coupon_show',
                'updated_at' => NULL,
            ),
            234 =>
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 235,
                'title' => 'coupon_delete',
                'updated_at' => NULL,
            ),
            235 =>
            array (
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 236,
                'title' => 'coupon_access',
                'updated_at' => NULL,
            ),
        ));

    }
}

