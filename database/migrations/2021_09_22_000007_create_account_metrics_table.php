<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountMetricsTable extends Migration
{
    public function up()
    {
        Schema::create('account_metrics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->decimal('maxDailyLoss', 10, 4)->default(0);
            $table->dateTime('metricDate')->nullable();
            $table->string('isActiveTradingDay')->default(false);
            $table->bigInteger('trades')->default(0);
            $table->decimal('averageLosingTrade', 6, 3)->default(0);
            $table->decimal('averageWinningTrade', 6, 3)->default(0);
            $table->decimal('lastBalance', 13, 3)->default(0);
            $table->decimal('lastEquity', 13, 3)->default(0);
            $table->decimal('lastRisk', 10, 3)->default(0);
            $table->decimal('maxMonthlyLoss', 10, 4)->default(0);;
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
