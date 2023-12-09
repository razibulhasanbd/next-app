<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtServersTable extends Migration
{
    public function up()
    {
        Schema::create('mt_servers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('server');
            $table->string('login');
            $table->string('password');
            $table->string('group');
            $table->string('friendly_name');
            $table->string('trading_server_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
