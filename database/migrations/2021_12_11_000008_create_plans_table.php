<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('plans');
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('title');
            $table->string('description');
            $table->integer('leverage');
            $table->integer('startingBalance');
            $table->string('duration');
            $table->integer('next_plan');
            $table->boolean('new_account_on_next_plan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

      public function down()
    {
        Schema::dropIfExists('plans');
    }
}
