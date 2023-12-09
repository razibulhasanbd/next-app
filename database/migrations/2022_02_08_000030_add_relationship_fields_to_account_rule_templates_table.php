<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAccountRuleTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('account_rule_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('rule_name_id')->nullable();
            $table->foreign('rule_name_id', 'rule_name_fk_5949886')->references('id')->on('rule_names');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id', 'plan_fk_5949887')->references('id')->on('plans');
        });
    }
}
