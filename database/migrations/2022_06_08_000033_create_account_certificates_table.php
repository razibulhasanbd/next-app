<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('account_certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('certificate_data');
            $table->string('url')->nullable();
            $table->string('share')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
