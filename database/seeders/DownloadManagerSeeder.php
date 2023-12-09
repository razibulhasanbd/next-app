<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DownloadManagerSeeder extends Seeder
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
            'title' => 'download_manager',
            'updated_at' => NULL,
        ),
            array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'download_manager_delete_generated_csv',
                'updated_at' => NULL,
            ),array(
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => NULL,
                'title' => 'download_manager_download_generated_csv',
                'updated_at' => NULL,
            ),
            ]);

    }
}
