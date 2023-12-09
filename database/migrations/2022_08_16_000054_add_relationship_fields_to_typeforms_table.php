<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTypeformsTable extends Migration
{
    public function up()
    {
        Schema::table('typeforms', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id', 'plan_fk_7156321')->references('id')->on('plans');
        });
    }
}
