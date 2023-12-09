<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountRuleTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('account_rule_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('default_value');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
