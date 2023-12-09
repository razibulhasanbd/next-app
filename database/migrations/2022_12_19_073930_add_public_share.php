<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_certificates', function (Blueprint $table) {
            $table->enum('trading_public_share', ['yes', 'no'])->default('no')->after('doc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_certificates', function (Blueprint $table) {
            $table->dropColumn('trading_public_share');
        });
    }
}
