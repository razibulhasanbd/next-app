<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTargetReachedAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('target_reached_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id', 'account_fk_5712390')->references('id')->on('accounts');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id', 'plan_fk_5712391')->references('id')->on('plans');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id', 'subscription_fk_5712394')->references('id')->on('subscriptions');
            $table->unsignedBigInteger('approval_category_id')->nullable();
            $table->foreign('approval_category_id', 'approval_category_fk_6009084')->references('id')->on('approval_categories');
        });
    }
}
