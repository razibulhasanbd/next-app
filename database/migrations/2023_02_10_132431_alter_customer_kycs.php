<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomerKycs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_kycs', function (Blueprint $table) {
           $table->tinyInteger('user_agreement')->default(0)->after('status');
           $table->string('pdf_path')->nullable()->after('user_agreement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_kycs', function (Blueprint $table) {
            //
        });
    }
}
