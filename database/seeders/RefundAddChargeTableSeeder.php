<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RefundAddChargeTableSeeder extends Seeder
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
            'title' => 'refund_list',
            'updated_at' => NULL,
        ),
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'charge_list',
                'updated_at' => NULL,
            ),

            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'refund_request',
                'updated_at' => NULL,
            ),

            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'charge_request',
                'updated_at' => NULL,
            ),

            ]);
    }
}
