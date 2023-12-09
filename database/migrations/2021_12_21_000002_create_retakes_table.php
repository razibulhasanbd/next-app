<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetakesTable extends Migration
{
    public function up()
    {
        Schema::create('retakes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('retake_count');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
