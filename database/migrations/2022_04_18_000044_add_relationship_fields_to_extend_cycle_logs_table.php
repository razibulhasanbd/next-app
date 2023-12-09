<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToExtendCycleLogsTable extends Migration
{
    public function up()
    {
        Schema::table('extend_cycle_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('login')->nullable();
            $table->unsignedBigInteger('subcription_id')->nullable();
            $table->foreign('subcription_id', 'subcription_fk_6450894')->references('id')->on('subscriptions');
            $table->unsignedBigInteger('account_id')->nullable();
        });
    }
}
