<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelVarientsTable extends Migration
{
    public function up()
    {
        Schema::create('model_varients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('is_default');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
