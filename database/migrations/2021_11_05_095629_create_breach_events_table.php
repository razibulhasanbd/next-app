<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreachEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breach_events', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('login');
            $table->decimal('balance', 9, 2);
            $table->decimal('equity', 9, 2);
            $table->longText('metrics');
            $table->longText('trades');
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
        Schema::dropIfExists('breach_events');
    }
}
