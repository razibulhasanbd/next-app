<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->integer('login');
            $table->string('password');
            $table->string('type');
            $table->integer('plan_id');
            $table->string('name');
            $table->string('comment')->nullable();
            $table->float('balance', 12, 2);
            $table->float('equity', 12, 2);
            $table->float('credit', 9, 2)->nullable();
            $table->tinyInteger('breached')->default('0');
            $table->string('breachedby')->nullable();
            $table->string('trading_server_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
