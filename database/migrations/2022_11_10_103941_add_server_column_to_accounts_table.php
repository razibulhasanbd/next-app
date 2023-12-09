<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServerColumnToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->nullable();
        });
        //! for each row in accounts table, set server_id equal to account.plan.server_id
        DB::statement('UPDATE accounts  INNER JOIN  plans ON accounts.plan_id = plans.id SET accounts.server_id = plans.server_id');
        Schema::table('accounts', function (Blueprint $table) {

            $table->foreign('server_id', 'server_id')->references('id')->on('mt_servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            //
        });
    }
}
