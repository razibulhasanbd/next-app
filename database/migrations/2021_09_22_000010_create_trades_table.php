<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('account_id');
            $table->decimal('close_price', 40, 16);
            $table->bigInteger('close_time');
            $table->string('close_time_str');
            $table->decimal('commission', 40, 16);
            $table->integer('digits');
            $table->integer('login');
            $table->decimal('lots', 40, 6);
            $table->decimal('open_price', 40, 16);
            $table->bigInteger('open_time');
            $table->string('open_time_str');
            $table->decimal('pips', 40, 6);
            $table->decimal('profit', 40, 6);
            $table->integer('reason');
            $table->decimal('sl', 40, 16);
            $table->integer('state');
            $table->integer('swap');
            $table->string('symbol');
            $table->bigInteger('ticket')->unique();
            $table->decimal('tp', 40, 16);
            $table->integer('type');
            $table->string('type_str');
            $table->integer('volume');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
