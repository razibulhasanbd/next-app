<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArbitraryTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arbitrary_trades', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('login');
            $table->bigInteger('ticket')->unique();
            $table->integer('time_difference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arbitrary_trades');
    }
}
