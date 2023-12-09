<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeformsTable extends Migration
{
    public function up()
    {
        Schema::create('typeforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payments_for');
            $table->string('funding_package')->nullable();
            $table->string('funding_amount')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('payment_method');
            $table->string('payment_proof');
            $table->string('paid_amount');
            $table->string('name');
            $table->string('email');
            $table->string('country');
            $table->string('login')->nullable();
            $table->string('payment_verification')->default(0);
            $table->datetime('approved_at')->nullable();
            $table->string('transaction_id');
            $table->datetime('denied_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->datetime('archived_at')->nullable();
            $table->string('referred_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
