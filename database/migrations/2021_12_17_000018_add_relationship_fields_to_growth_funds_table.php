<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToGrowthFundsTable extends Migration
{
    public function up()
    {
        Schema::table('growth_funds', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id', 'account_fk_5608439')->references('id')->on('accounts');
            $table->unsignedBigInteger('subscription_id');
            $table->foreign('subscription_id', 'subscription_fk_5608440')->references('id')->on('subscriptions');
        });
    }
}
