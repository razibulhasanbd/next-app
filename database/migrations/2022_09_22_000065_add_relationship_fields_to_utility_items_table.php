<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToUtilityItemsTable extends Migration
{
    public function up()
    {
        Schema::table('utility_items', function (Blueprint $table) {
            $table->unsignedBigInteger('utility_category_id')->nullable();
            $table->foreign('utility_category_id', 'utility_category_fk_7358537')->references('id')->on('utility_categories');
        });
    }
}
