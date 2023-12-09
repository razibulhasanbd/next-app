<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('business_model_id')->nullable();
            $table->foreign('business_model_id', 'business_model_fk_7772771')->references('id')->on('business_models');
            $table->unsignedBigInteger('model_varient_id')->nullable();
            $table->foreign('model_varient_id', 'model_varient_fk_7772772')->references('id')->on('model_varients');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id', 'plan_fk_7772773')->references('id')->on('plans');
        });
    }
}
