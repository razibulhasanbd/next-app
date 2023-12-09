<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilityItemsTable extends Migration
{
    public function up()
    {
        Schema::create('utility_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon_url')->nullable();
            $table->string('header');
            $table->longText('description');
            $table->string('download_file_url')->nullable();
            $table->longText('youtube_embedded_url')->nullable();
            $table->text('youtube_thumbnail_url')->nullable();
            $table->tinyInteger('status');
            $table->integer('order_value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
