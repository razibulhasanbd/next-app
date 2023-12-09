<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetReachedAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('target_reached_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('metric_info');
            $table->string('rules_reached');
            $table->datetime('approved_at')->nullable();
            $table->datetime('denied_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
