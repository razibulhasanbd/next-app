<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountRulesTable extends Migration
{
    public function up()
    {
        Schema::create('account_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
