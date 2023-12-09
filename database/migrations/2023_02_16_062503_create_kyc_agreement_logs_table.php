<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycAgreementLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_agreement_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kyc_id')->nullable();
            $table->integer('login');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('kyc_id')
                ->references('id')->on('customer_kycs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc_agreement_logs');
    }
}
