<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('buy_price', 15, 2);
            $table->decimal('topup_price', 15, 2);
            $table->decimal('reset_price', 15, 2);
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
