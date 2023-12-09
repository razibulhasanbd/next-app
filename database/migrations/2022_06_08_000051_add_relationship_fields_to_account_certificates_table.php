<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAccountCertificatesTable extends Migration
{
    public function up()
    {
        Schema::table('account_certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('certificate_id')->nullable();
            $table->foreign('certificate_id', 'certificate_fk_6753054')->references('id')->on('certificates');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id', 'account_fk_6753055')->references('id')->on('accounts');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id', 'customer_fk_6753056')->references('id')->on('customers');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id', 'subscription_fk_7181756')->references('id')->on('subscriptions');
        });
    }
}
