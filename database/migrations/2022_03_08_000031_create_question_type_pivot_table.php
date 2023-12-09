<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTypePivotTable extends Migration
{
    public function up()
    {
        Schema::create('question_type', function (Blueprint $table) {
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id', 'question_id_fk_6159413')->references('id')->on('questions')->onDelete('cascade');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id', 'type_id_fk_6159413')->references('id')->on('types')->onDelete('cascade');
        });
    }
}
