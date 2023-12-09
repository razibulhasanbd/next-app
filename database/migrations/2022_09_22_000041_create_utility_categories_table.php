<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilityCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('utility_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('order_value');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
