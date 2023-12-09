<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPlansTable extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id', 'package_fk_5552423')->references('id')->on('packages');
            $table->unsignedBigInteger('server_id');
            $table->foreign('server_id', 'server_fk_5552424')->references('id')->on('mt_servers');
        });
    }
}
