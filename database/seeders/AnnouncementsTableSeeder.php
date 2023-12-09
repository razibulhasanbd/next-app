<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnnouncementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('announcements')->delete();
        
        \DB::table('announcements')->insert(array (
            0 => 
            array (
                'created_at' => '2022-02-23 08:22:12',
                'deleted_at' => NULL,
                'id' => 1,
                'news' => '<p>From now on our traders will receive a 90% profit share upon getting a real account. For more detail visit <a href="http://fundednext.com/">http://fundednext.com/</a></p>',
                'title' => 'Profit Share Increased 90%',
                'updated_at' => '2022-02-23 08:22:12',
            ),
            1 => 
            array (
                'created_at' => '2022-02-23 08:23:27',
                'deleted_at' => NULL,
                'id' => 2,
                'news' => '<p>Traders can retake the challenge unlimited times until they are in profit</p>',
                'title' => 'Introducing Unlimited Retakes',
                'updated_at' => '2022-02-23 08:23:27',
            ),
        ));
        
        
    }
}