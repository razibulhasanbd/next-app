<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCalendarsTable extends Migration
{
    public function up()
    {
        Schema::create('news_calendars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('country');
            $table->datetime('date');
            $table->string('impact');
            $table->string('forecast');
            $table->string('previous');
            $table->boolean('is_restricted')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
