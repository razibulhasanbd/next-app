<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrowthFundsTable extends Migration
{
    public function up()
    {
        Schema::create('growth_funds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount', 15, 2);
            $table->datetime('date');
            $table->string('fund_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
