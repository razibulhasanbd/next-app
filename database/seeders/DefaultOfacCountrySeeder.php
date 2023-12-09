<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class DefaultOfacCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default OFAC country enter
        Country::whereIn('name', ["Belarus", "Cuba","Russian Federation","Congo, the Democratic Republic of the","Iran, Islamic Republic of","Iraq",
            "Korea, Democratic People's Republic of","Sudan","South Sudan","Syrian Arab Republic","Zimbabwe"])
            ->update(['country_category' => 1]);
    }
}
