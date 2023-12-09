<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAccountStatusLogsTable extends Migration
{
    public function up()
    {
        Schema::table('account_status_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id', 'account_fk_7260925')->references('id')->on('accounts');
            $table->unsignedBigInteger('old_status_id')->nullable();
            $table->foreign('old_status_id', 'old_status_fk_7260927')->references('id')->on('account_statuses');
            $table->unsignedBigInteger('new_status_id')->nullable();
            $table->foreign('new_status_id', 'new_status_fk_7260928')->references('id')->on('account_statuses');
			$table->unsignedBigInteger('account_status_message_id')->nullable();
            $table->foreign('account_status_message_id', 'account_status_message_fk_7260928')->references('id')->on('account_status_messages');
        });
    }
}
