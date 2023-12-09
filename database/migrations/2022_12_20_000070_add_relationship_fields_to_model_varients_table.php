<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToModelVarientsTable extends Migration
{
    public function up()
    {
        Schema::table('model_varients', function (Blueprint $table) {
            $table->unsignedBigInteger('business_model_id')->nullable();
            $table->foreign('business_model_id', 'business_model_fk_7772690')->references('id')->on('business_models');
        });
    }
}
