<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountStatusLogsTable extends Migration
{
    public function up()
    {
        Schema::create('account_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('data');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
