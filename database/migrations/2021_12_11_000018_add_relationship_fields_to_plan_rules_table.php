<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPlanRulesTable extends Migration
{
    public function up()
    {
        Schema::table('plan_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('rule_name_id');
            $table->foreign('rule_name_id', 'rule_name_fk_5552408')->references('id')->on('rule_names');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id', 'plan_fk_5552409')->references('id')->on('plans');
        });
    }
}
