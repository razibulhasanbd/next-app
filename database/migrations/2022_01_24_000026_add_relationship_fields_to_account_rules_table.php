<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAccountRulesTable extends Migration
{
    public function up()
    {
        Schema::table('account_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id', 'account_fk_5848917')->references('id')->on('accounts');
            $table->unsignedBigInteger('rule_id')->nullable();
            $table->foreign('rule_id', 'rule_fk_5848918')->references('id')->on('rule_names');
        });
    }
}
