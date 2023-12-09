<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PaymentMethodOrderingPermission extends Seeder
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
                'title' => 'payment_method_order',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'payment_method_order_update',
                'updated_at' => NULL,
            ),
            ]);
    }
}
