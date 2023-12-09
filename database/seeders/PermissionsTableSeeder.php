<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('permissions')->delete();

        \DB::table('permissions')->insert(array(
            0 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 1,
                'title' => 'user_management_access',
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 2,
                'title' => 'permission_create',
                'updated_at' => NULL,
            ),
            2 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 3,
                'title' => 'permission_edit',
                'updated_at' => NULL,
            ),
            3 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 4,
                'title' => 'permission_show',
                'updated_at' => NULL,
            ),
            4 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 5,
                'title' => 'permission_delete',
                'updated_at' => NULL,
            ),
            5 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 6,
                'title' => 'permission_access',
                'updated_at' => NULL,
            ),
            6 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 7,
                'title' => 'role_create',
                'updated_at' => NULL,
            ),
            7 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 8,
                'title' => 'role_edit',
                'updated_at' => NULL,
            ),
            8 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 9,
                'title' => 'role_show',
                'updated_at' => NULL,
            ),
            9 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 10,
                'title' => 'role_delete',
                'updated_at' => NULL,
            ),
            10 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 11,
                'title' => 'role_access',
                'updated_at' => NULL,
            ),
            11 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 12,
                'title' => 'user_create',
                'updated_at' => NULL,
            ),
            12 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 13,
                'title' => 'user_edit',
                'updated_at' => NULL,
            ),
            13 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 14,
                'title' => 'user_show',
                'updated_at' => NULL,
            ),
            14 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 15,
                'title' => 'user_delete',
                'updated_at' => NULL,
            ),
            15 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 16,
                'title' => 'user_access',
                'updated_at' => NULL,
            ),
            16 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 17,
                'title' => 'audit_log_show',
                'updated_at' => NULL,
            ),
            17 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 18,
                'title' => 'audit_log_access',
                'updated_at' => NULL,
            ),
            18 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 19,
                'title' => 'customer_create',
                'updated_at' => NULL,
            ),
            19 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 20,
                'title' => 'customer_edit',
                'updated_at' => NULL,
            ),
            20 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 21,
                'title' => 'customer_show',
                'updated_at' => NULL,
            ),
            21 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 22,
                'title' => 'customer_delete',
                'updated_at' => NULL,
            ),
            22 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 23,
                'title' => 'customer_access',
                'updated_at' => NULL,
            ),
            23 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 24,
                'title' => 'account_create',
                'updated_at' => NULL,
            ),
            24 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 25,
                'title' => 'account_edit',
                'updated_at' => NULL,
            ),
            25 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 26,
                'title' => 'account_show',
                'updated_at' => NULL,
            ),
            26 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 27,
                'title' => 'account_delete',
                'updated_at' => NULL,
            ),
            27 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 28,
                'title' => 'account_access',
                'updated_at' => NULL,
            ),
            28 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 29,
                'title' => 'account_metric_create',
                'updated_at' => NULL,
            ),
            29 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 30,
                'title' => 'account_metric_edit',
                'updated_at' => NULL,
            ),
            30 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 31,
                'title' => 'account_metric_show',
                'updated_at' => NULL,
            ),
            31 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 32,
                'title' => 'account_metric_delete',
                'updated_at' => NULL,
            ),
            32 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 33,
                'title' => 'account_metric_access',
                'updated_at' => NULL,
            ),
            33 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 34,
                'title' => 'plan_create',
                'updated_at' => NULL,
            ),
            34 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 35,
                'title' => 'plan_edit',
                'updated_at' => NULL,
            ),
            35 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 36,
                'title' => 'plan_show',
                'updated_at' => NULL,
            ),
            36 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 37,
                'title' => 'plan_delete',
                'updated_at' => NULL,
            ),
            37 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 38,
                'title' => 'plan_access',
                'updated_at' => NULL,
            ),
            38 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 39,
                'title' => 'subscription_create',
                'updated_at' => NULL,
            ),
            39 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 40,
                'title' => 'subscription_edit',
                'updated_at' => NULL,
            ),
            40 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 41,
                'title' => 'subscription_show',
                'updated_at' => NULL,
            ),
            41 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 42,
                'title' => 'subscription_delete',
                'updated_at' => NULL,
            ),
            42 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 43,
                'title' => 'subscription_access',
                'updated_at' => NULL,
            ),
            43 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 44,
                'title' => 'trade_create',
                'updated_at' => NULL,
            ),
            44 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 45,
                'title' => 'trade_edit',
                'updated_at' => NULL,
            ),
            45 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 46,
                'title' => 'trade_show',
                'updated_at' => NULL,
            ),
            46 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 47,
                'title' => 'trade_delete',
                'updated_at' => NULL,
            ),
            47 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 48,
                'title' => 'trade_access',
                'updated_at' => NULL,
            ),
            48 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 49,
                'title' => 'profile_password_edit',
                'updated_at' => NULL,
            ),
            49 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 50,
                'title' => 'package_access',
                'updated_at' => NULL,
            ),
            50 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 51,
                'title' => 'package_create',
                'updated_at' => NULL,
            ),
            51 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 52,
                'title' => 'package_show',
                'updated_at' => NULL,
            ),
            52 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 53,
                'title' => 'package_delete',
                'updated_at' => NULL,
            ),
            53 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 54,
                'title' => 'mt_server_access',
                'updated_at' => NULL,
            ),
            54 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 55,
                'title' => 'mt_server_create',
                'updated_at' => NULL,
            ),
            55 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 56,
                'title' => 'mt_server_show',
                'updated_at' => NULL,
            ),
            56 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 57,
                'title' => 'mt_server_edit',
                'updated_at' => NULL,
            ),
            57 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 58,
                'title' => 'mt_server_delete',
                'updated_at' => NULL,
            ),
            58 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 59,
                'title' => 'rule_name_access',
                'updated_at' => NULL,
            ),
            59 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 60,
                'title' => 'rule_name_create',
                'updated_at' => NULL,
            ),
            60 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 61,
                'title' => 'rule_name_show',
                'updated_at' => NULL,
            ),
            61 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 62,
                'title' => 'rule_name_edit',
                'updated_at' => NULL,
            ),
            62 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 63,
                'title' => 'rule_name_delete',
                'updated_at' => NULL,
            ),
            63 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 64,
                'title' => 'plan_rule_access',
                'updated_at' => NULL,
            ),
            64 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 65,
                'title' => 'plan_rule_show',
                'updated_at' => NULL,
            ),
            65 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 66,
                'title' => 'plan_rule_create',
                'updated_at' => NULL,
            ),
            66 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 67,
                'title' => 'plan_rule_edit',
                'updated_at' => NULL,
            ),
            67 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 68,
                'title' => 'plan_rule_delete',
                'updated_at' => NULL,
            ),
            68 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 69,
                'title' => 'growth_fund_create',
                'updated_at' => NULL,
            ),
            69 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 70,
                'title' => 'growth_fund_edit',
                'updated_at' => NULL,
            ),
            70 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 71,
                'title' => 'growth_fund_show',
                'updated_at' => NULL,
            ),
            71 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 72,
                'title' => 'growth_fund_delete',
                'updated_at' => NULL,
            ),
            72 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 73,
                'title' => 'growth_fund_access',
                'updated_at' => NULL,
            ),
            73 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 74,
                'title' => 'retake_create',
                'updated_at' => NULL,
            ),
            74 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 75,
                'title' => 'retake_edit',
                'updated_at' => NULL,
            ),
            75 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 76,
                'title' => 'retake_show',
                'updated_at' => NULL,
            ),
            76 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 77,
                'title' => 'retake_delete',
                'updated_at' => NULL,
            ),
            77 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 78,
                'title' => 'retake_access',
                'updated_at' => NULL,
            ),
            78 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 79,
                'title' => 'target_reached_account_create',
                'updated_at' => NULL,
            ),
            79 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 80,
                'title' => 'target_reached_account_edit',
                'updated_at' => NULL,
            ),
            80 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 81,
                'title' => 'target_reached_account_show',
                'updated_at' => NULL,
            ),
            81 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 82,
                'title' => 'target_reached_account_delete',
                'updated_at' => NULL,
            ),
            82 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 83,
                'title' => 'target_reached_account_access',
                'updated_at' => NULL,
            ),
            83 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 84,
                'title' => 'account_rule_create',
                'updated_at' => NULL,
            ),
            84 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 85,
                'title' => 'account_rule_edit',
                'updated_at' => NULL,
            ),
            85 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 86,
                'title' => 'account_rule_show',
                'updated_at' => NULL,
            ),
            86 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 87,
                'title' => 'account_rule_delete',
                'updated_at' => NULL,
            ),
            87 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 88,
                'title' => 'account_rule_access',
                'updated_at' => NULL,
            ),
            88 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 89,
                'title' => 'announcement_create',
                'updated_at' => NULL,
            ),
            89 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 90,
                'title' => 'announcement_edit',
                'updated_at' => NULL,
            ),
            90 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 91,
                'title' => 'announcement_show',
                'updated_at' => NULL,
            ),
            91 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 92,
                'title' => 'announcement_delete',
                'updated_at' => NULL,
            ),
            92 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 93,
                'title' => 'announcement_access',
                'updated_at' => NULL,
            ),
            93 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 94,
                'title' => 'account_rule_template_create',
                'updated_at' => NULL,
            ),
            94 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 95,
                'title' => 'account_rule_template_edit',
                'updated_at' => NULL,
            ),
            95 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 96,
                'title' => 'account_rule_template_show',
                'updated_at' => NULL,
            ),
            96 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 97,
                'title' => 'account_rule_template_delete',
                'updated_at' => NULL,
            ),
            97 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 98,
                'title' => 'account_rule_template_access',
                'updated_at' => NULL,
            ),
            98 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 99,
                'title' => 'approval_category_create',
                'updated_at' => NULL,
            ),
            99 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 100,
                'title' => 'approval_category_edit',
                'updated_at' => NULL,
            ),
            100 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 101,
                'title' => 'approval_category_show',
                'updated_at' => NULL,
            ),
            101 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 102,
                'title' => 'approval_category_delete',
                'updated_at' => NULL,
            ),
            102 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 103,
                'title' => 'approval_category_access',
                'updated_at' => NULL,
            ),

            103 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 104,
                'title' => 'faq_management_access',
                'updated_at' => NULL,
            ),

            104 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 105,
                'title' => 'category_create',
                'updated_at' => NULL,
            ),

            105 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 106,
                'title' => 'category_edit',
                'updated_at' => NULL,
            ),

            106 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 107,
                'title' => 'category_show',
                'updated_at' => NULL,
            ),

            107 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 108,
                'title' => 'category_delete',
                'updated_at' => NULL,
            ),

            108 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 109,
                'title' => 'category_access',
                'updated_at' => NULL,
            ),

            109 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 110,
                'title' => 'type_create',
                'updated_at' => NULL,
            ),

            110 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 111,
                'title' => 'type_edit',
                'updated_at' => NULL,
            ),

            111 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 112,
                'title' => 'type_show',
                'updated_at' => NULL,
            ),

            112 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 113,
                'title' => 'type_delete',
                'updated_at' => NULL,
            ),

            113 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 114,
                'title' => 'type_access',
                'updated_at' => NULL,
            ),

            114 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 115,
                'title' => 'tag_create',
                'updated_at' => NULL,
            ),

            115 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 116,
                'title' => 'tag_edit',
                'updated_at' => NULL,
            ),

            116 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 117,
                'title' => 'tag_show',
                'updated_at' => NULL,
            ),

            117 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 118,
                'title' => 'tag_delete',
                'updated_at' => NULL,
            ),

            118 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 119,
                'title' => 'tag_access',
                'updated_at' => NULL,
            ),

            119 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 120,
                'title' => 'section_create',
                'updated_at' => NULL,
            ),

            120 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 121,
                'title' => 'section_edit',
                'updated_at' => NULL,
            ),

            121 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 122,
                'title' => 'section_show',
                'updated_at' => NULL,
            ),

            122 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 123,
                'title' => 'section_delete',
                'updated_at' => NULL,
            ),

            123 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 124,
                'title' => 'section_access',
                'updated_at' => NULL,
            ),

            124 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 125,
                'title' => 'question_create',
                'updated_at' => NULL,
            ),

            125 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 126,
                'title' => 'question_edit',
                'updated_at' => NULL,
            ),

            126 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 127,
                'title' => 'question_show',
                'updated_at' => NULL,
            ),

            127 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 128,
                'title' => 'question_delete',
                'updated_at' => NULL,
            ),

            128 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 129,
                'title' => 'question_access',
                'updated_at' => NULL,
            ),



            129 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 130,
                'title' => 'profile_password_edit',
                'updated_at' => NULL,
            ),


            130 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 131,
                'title' => 'mt4_password_show_hide',
                'updated_at' => NULL,
            ),

            131 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 132,
                'title' => 'top_up_reset_section_show_hide',
                'updated_at' => NULL,
            ),

            132 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 133,
                'title' => 'target_reached_account_confirm_show_hide',
                'updated_at' => NULL,
            ),
            133 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 134,
                'title' => 'trader_game_create',
                'updated_at' => NULL,
            ),
            134 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 135,
                'title' => 'trader_game_edit',
                'updated_at' => NULL,
            ),
            135 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 136,
                'title' => 'trader_game_show',
                'updated_at' => NULL,
            ),
            136 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 137,
                'title' => 'trader_game_delete',
                'updated_at' => NULL,
            ),
            137 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 138,
                'title' => 'trader_game_access',
                'updated_at' => NULL,
            ),

            138 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 139,
                'title' => 'account_profit_checker',
                'updated_at' => NULL,
            ),

            139 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 140,
                'title' => 'smember_delete',
                'updated_at' => NULL,
            ),

            140 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 141,
                'title' => 'redis_margin_clear',
                'updated_at' => NULL,
            ),

            141 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 142,
                'title' => 'running_show_trade',
                'updated_at' => NULL,
            ),

            142 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 143,
                'title' => 'cycle_extension_access',
                'updated_at' => NULL,
            ),

            143 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 144,
                'title' => 'extend_cycle_log_create',
                'updated_at' => NULL,
            ),

            144 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 145,
                'title' => 'extend_cycle_log_edit',
                'updated_at' => NULL,
            ),

            145 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 146,
                'title' => 'extend_cycle_log_show',
                'updated_at' => NULL,
            ),

            146 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 147,
                'title' => 'extend_cycle_log_delete',
                'updated_at' => NULL,
            ),

            147 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 148,
                'title' => 'extend_cycle_log_access',
                'updated_at' => NULL,
            ),

            148 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 149,
                'title' => 'extend_cycle_access',
                'updated_at' => NULL,
            ),
            149 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 150,
                'title' => 'user_email_show_hide',
                'updated_at' => NULL,
            ),

            150 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 151,
                'title' => 'trade_sl_tp_create',
                'updated_at' => NULL,
            ),

            151 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 152,
                'title' => 'trade_sl_tp_edit',
                'updated_at' => NULL,
            ),

            152 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 153,
                'title' => 'trade_sl_tp_show',
                'updated_at' => NULL,
            ),

            153 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 154,
                'title' => 'trade_sl_tp_delete',
                'updated_at' => NULL,
            ),

            154 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 155,
                'title' => 'trade_sl_tp_access',
                'updated_at' => NULL,
            ),

            155 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 156,
                'title' => 'fn_certificate_access',
                'updated_at' => NULL,
            ),

            156 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 157,
                'title' => 'certificate_type_create',
                'updated_at' => NULL,
            ),

            157 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 158,
                'title' => 'certificate_type_edit',
                'updated_at' => NULL,
            ),

            158 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 159,
                'title' => 'certificate_type_show',
                'updated_at' => NULL,
            ),

            159 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 160,
                'title' => 'certificate_type_delete',
                'updated_at' => NULL,
            ),

            160 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 161,
                'title' => 'certificate_type_access',
                'updated_at' => NULL,
            ),

            161 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 162,
                'title' => 'ceritificate_create',
                'updated_at' => NULL,
            ),

            162 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 163,
                'title' => 'ceritificate_edit',
                'updated_at' => NULL,
            ),

            163 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 164,
                'title' => 'ceritificate_show',
                'updated_at' => NULL,
            ),

            164 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 165,
                'title' => 'ceritificate_delete',
                'updated_at' => NULL,
            ),

            165 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 166,
                'title' => 'ceritificate_access',
                'updated_at' => NULL,
            ),

            166 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 167,
                'title' => 'account_certificate_create',
                'updated_at' => NULL,
            ),

            167 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 168,
                'title' => 'account_certificate_edit',
                'updated_at' => NULL,
            ),

            168 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 169,
                'title' => 'account_certificate_show',
                'updated_at' => NULL,
            ),

            169 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 170,
                'title' => 'account_certificate_delete',
                'updated_at' => NULL,
            ),

            170 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 171,
                'title' => 'account_certificate_access',
                'updated_at' => NULL,

            ),

            171 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 172,

                'title' => 'account-settings',
                'updated_at' => NULL,
            ),

            172 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 173,
                'title' => 'account-settings-deposite',
                'updated_at' => NULL,
            ),

            173 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 174,
                'title' => 'account-settings-withdraw',
                'updated_at' => NULL,
            ),

            174 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 175,
                'title' => 'account-settings-group',
                'updated_at' => NULL,
            ),

            175 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 176,
                'title' => 'account-settings-reset-metric',
                'updated_at' => NULL,
            ),

                176 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 177,
                'title' => 'account_email_column_hide_searchable',
                'updated_at' => NULL,
            ),

            177 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 178,
                'title' => 'breach-event-account_show',
                'updated_at' => NULL,
            ),
            178 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 179,
                'title' => 'breach-event-account_edit',
                'updated_at' => NULL,
            ),

            179 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 180,
                'title' => 'breach-event-account_delete',
                'updated_at' => NULL,
            ),

            180 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 181,
                'title' => 'breach-event-accounts',
                'updated_at' => NULL,
            ),

            181 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 182,
                'title' => 'news_access',
                'updated_at' => NULL,
            ),

            182 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 183,
                'title' => 'news_calendar_create',
                'updated_at' => NULL,
            ),

            183 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 184,
                'title' => 'news_calendar_edit',
                'updated_at' => NULL,
            ),

            184 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 185,
                'title' => 'news_calendar_show',
                'updated_at' => NULL,
            ),

            185 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 186,
                'title' => 'news_calendar_delete',
                'updated_at' => NULL,
            ),

            186 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 187,
                'title' => 'news_calendar_access',
                'updated_at' => NULL,
            ),

            187 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 188,
                'title' => 'show_news_calendar_restricted_button',
                'updated_at' => NULL,
            ),

            187 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 188,
                'title' => 'show_news_calendar_Unrestrict_button',
                'updated_at' => NULL,
            ),

            188 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 189,
                'title' => 'allow_real_account_csv_download',
                'updated_at' => NULL,
            ),
            189 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 190,
                'title' => 'account_view_trade_sync',
                'updated_at' => NULL,
            ),
            190 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 191,
                'title' => 'outside_payment_access',
                'updated_at' => NULL,
            ),

            191 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 192,
                'title' => 'typeform_create',
                'updated_at' => NULL,
            ),

            192 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 193,
                'title' => 'typeform_edit',
                'updated_at' => NULL,
            ),

            193 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 194,
                'title' => 'typeform_show',
                'updated_at' => NULL,
            ),

            194 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 195,
                'title' => 'typeform_delete',
                'updated_at' => NULL,
            ),

            195 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 196,
                'title' => 'typeform_access',
                'updated_at' => NULL,
            ),

            196 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 197,
                'title' => 'master_data_access',
                'updated_at' => NULL,
            ),

            197 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 198,
                'title' => 'account_status_create',
                'updated_at' => NULL,
            ),

            198 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 199,
                'title' => 'account_status_edit',
                'updated_at' => NULL,
            ),

            199 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 200,
                'title' => 'account_status_show',
                'updated_at' => NULL,
            ),

            200 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 201,
                'title' => 'account_status_delete',
                'updated_at' => NULL,
            ),

            201 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 202,
                'title' => 'account_status_access',
                'updated_at' => NULL,
            ),

            202 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 203,
                'title' => 'account_status_message_create',
                'updated_at' => NULL,
            ),

            203 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 204,
                'title' => 'account_status_message_edit',
                'updated_at' => NULL,
            ),

            204 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 205,
                'title' => 'account_status_message_show',
                'updated_at' => NULL,
            ),

            205 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 206,
                'title' => 'account_status_message_delete',
                'updated_at' => NULL,
            ),

            206 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 207,
                'title' => 'account_status_message_access',
                'updated_at' => NULL,
            ),

            207 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 208,
                'title' => 'account_status_log_create',
                'updated_at' => NULL,
            ),

            208 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 209,
                'title' => 'account_status_log_edit',
                'updated_at' => NULL,
            ),

            209 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 210,
                'title' => 'account_status_log_show',
                'updated_at' => NULL,
            ),

            210 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 211,
                'title' => 'account_status_log_delete',
                'updated_at' => NULL,
            ),

            211 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 212,
                'title' => 'account_status_log_access',
                'updated_at' => NULL,
            ),

            212 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 213,
                'title' => 'utility_access',
                'updated_at' => NULL,
            ),
            213 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 214,
                'title' => 'utility_category_create',
                'updated_at' => NULL,
            ),
            214 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 215,
                'title' => 'utility_category_edit',
                'updated_at' => NULL,
            ),
            215 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 216,
                'title' => 'utility_category_show',
                'updated_at' => NULL,
            ),
            216 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 217,
                'title' => 'utility_category_access',
                'updated_at' => NULL,
            ),
            217 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 218,
                'title' => 'utility_item_create',
                'updated_at' => NULL,
            ),
            218 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 219,
                'title' => 'utility_item_edit',
                'updated_at' => NULL,
            ),

            219 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 220,
                'title' => 'utility_item_show',
                'updated_at' => NULL,
            ),

            220 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 221,
                'title' => 'utility_item_delete',
                'updated_at' => NULL,
            ),
            221 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 222,
                'title' => 'utility_item_access',
                'updated_at' => NULL,
            ),
            222 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 223,
                'title' => 'investor_password_reset',
                'updated_at' => NULL,
            ),

            223 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 224,
                'title' => 'investor_password_view',
                'updated_at' => NULL,
            ),

            224 =>
                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,
                    'id' => 225,
                    'title' => 'outside_payment_transaction_id_update',
                    'updated_at' => NULL,
                ),

            225 =>
                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,
                    'id' => 226,
                    'title' => 'outside_payment_show_history',
                    'updated_at' => NULL,
                ),

            226 =>
                array(
                    'created_at' => NULL,
                    'deleted_at' => NULL,
                    'id' => 227,
                    'title' => 'kyc_verification_create',
                    'updated_at' => NULL,
                ),
        ));
    }
}
