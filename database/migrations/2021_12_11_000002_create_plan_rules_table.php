<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanRulesTable extends Migration
{
    public function up()
    {
        Schema::create('plan_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
