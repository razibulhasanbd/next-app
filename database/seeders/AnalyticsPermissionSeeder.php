<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnalyticsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permissions for analytics module
        $analyticsPermissions = [
            [
                'title' => 'analytics',
                'created_at' => NULL,
                'deleted_at' => NULL,
                'updated_at' => NULL,
            ],
            [
                'title' => 'analytics_daily_reports',
                'created_at' => NULL,
                'deleted_at' => NULL,
                'updated_at' => NULL,
            ]
        ];

        // Inserting external permissions based on analytics module listed above.
        \DB::table('permissions')->insert($analyticsPermissions);
    }
}
