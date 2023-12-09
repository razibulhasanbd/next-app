<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->string('name');
            $table->string('code');
            $table->longText('description')->nullable();
            $table->datetime('expiry_date');
            $table->integer('max_redemption')->nullable();
            $table->integer('max_redemption_per_user')->nullable();
            $table->float('amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
