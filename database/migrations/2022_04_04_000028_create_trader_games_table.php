<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraderGamesTable extends Migration
{
    public function up()
    {
        Schema::create('trader_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dashboard_user');
            $table->date('date');
            $table->string('dashboard_email')->nullable();
            $table->float('pnl', 14, 2)->nullable();
            $table->string('mental_score')->nullable();
            $table->string('tactical_score')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
