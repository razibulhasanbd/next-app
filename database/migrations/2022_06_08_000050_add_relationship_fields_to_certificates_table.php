<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCertificatesTable extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id', 'type_fk_6752860')->references('id')->on('certificate_types');
        });
    }
}
