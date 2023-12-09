<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(ApprovalCategoriesTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(MtServersTableSeeder::class);
        $this->call(PlansTableSeeder::class);
        $this->call(RuleNamesTableSeeder::class);
        $this->call(PlanRulesTableSeeder::class);
        $this->call(AnnouncementsTableSeeder::class);
        $this->call(AccountStatusSeeder::class);
        $this->call(AccountStatusMessageSeeder::class);
        $this->call(UtilityCategorySeerder::class);
        $this->call(AccountPasswordSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CouponPermissionSeeder::class);
        $this->call(AnalyticsPermissionSeeder::class);
    }
}
