<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PasswordToTypeForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('typeforms', function (Blueprint $table) {
            $table->string("server_name", 20)->default("mt4")->after("remarks");
            $table->string("password")->nullable()->after("server_name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('typeforms', function (Blueprint $table) {
            //
        });
    }
}
