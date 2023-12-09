<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orders');


        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->tinyInteger('order_type')->nullable();
            $table->tinyInteger('gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->float('total', 12, 2);
            $table->float('discount', 12, 2)->nullable();
            $table->float('grand_total', 12, 2);
            $table->tinyInteger('status');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')->on('customers');

            $table->foreign('account_id')
                ->references('id')->on('accounts');

            $table->foreign('coupon_id')
                ->references('id')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
