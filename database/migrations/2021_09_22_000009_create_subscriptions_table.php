<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->integer('login');
            $table->integer('plan_id');
            $table->datetime('ending_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
