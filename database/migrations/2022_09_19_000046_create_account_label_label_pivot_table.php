<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLabelLabelPivotTable extends Migration
{
    public function up()
    {
        Schema::create('account_label_label', function (Blueprint $table) {
            $table->unsignedBigInteger('account_label_id');
            $table->foreign('account_label_id', 'account_label_id_fk_7336444')->references('id')->on('account_labels')->onDelete('cascade');
            $table->unsignedBigInteger('label_id');
            $table->foreign('label_id', 'label_id_fk_7336444')->references('id')->on('labels')->onDelete('cascade');
        });
    }
}
