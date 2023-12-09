<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AccountLableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'title' => 'account_table_tag_access',
            ],
            [
                'title' => 'label_create',
            ],
            [
                'title' => 'label_edit',
            ],
            [
                'title' => 'label_show',
            ],
            [
                'title' => 'label_delete',
            ],
            [
                'title' => 'label_access',
            ],
            [
                'title' => 'account_label_create',
            ],
            [
                'title' => 'account_label_edit',
            ],
            [
                'title' => 'account_label_show',
            ],
            [
                'title' => 'account_label_delete',
            ],
            [
                'title' => 'account_label_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
