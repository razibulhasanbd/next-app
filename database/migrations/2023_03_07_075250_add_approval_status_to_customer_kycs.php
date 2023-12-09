<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalStatusToCustomerKycs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_kycs', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable()->after('customer_id');
            $table->tinyInteger('approval_status')->after('user_agreement')->default(0)->comment("0= not active, 1= active");
            $table->foreign('account_id')->references('id')->on('accounts');
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
