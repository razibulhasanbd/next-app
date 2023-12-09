<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuleNamesTable extends Migration
{
    public function up()
    {
        Schema::create('rule_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('is_percent')->default(0)->nullable();
            $table->string('condition')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
