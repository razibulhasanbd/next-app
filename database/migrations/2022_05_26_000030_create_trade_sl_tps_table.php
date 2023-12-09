<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeSlTpsTable extends Migration
{
    public function up()
    {
        Schema::create('trade_sl_tps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
