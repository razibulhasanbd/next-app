<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTradeSlTpsTable extends Migration
{
    public function up()
    {
        Schema::table('trade_sl_tps', function (Blueprint $table) {
            $table->unsignedBigInteger('trade_id')->nullable();
            $table->foreign('trade_id', 'trade_fk_6670201')->references('id')->on('trades');
        });
    }
}
