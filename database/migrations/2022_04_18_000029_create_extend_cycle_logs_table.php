<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtendCycleLogsTable extends Migration
{
    public function up()
    {
        Schema::create('extend_cycle_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('weeks');
            $table->datetime('before_subscription');
            $table->datetime('after_subscription');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
