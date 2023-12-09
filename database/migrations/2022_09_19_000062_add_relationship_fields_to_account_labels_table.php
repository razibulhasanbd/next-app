<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAccountLabelsTable extends Migration
{
    public function up()
    {
        Schema::table('account_labels', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id', 'account_fk_7336416')->references('id')->on('accounts');
        });
    }
}
