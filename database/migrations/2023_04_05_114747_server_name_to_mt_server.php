<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServerNameToMtServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mt_servers', function (Blueprint $table) {
            $table->enum('server_name', ['MT4', 'MT5'])->default('MT4')->after('trading_server_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mt_servers', function (Blueprint $table) {
            //
        });
    }
}
