<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryQuestionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('category_question', function (Blueprint $table) {
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id', 'question_id_fk_6159412')->references('id')->on('questions')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id', 'category_id_fk_6159412')->references('id')->on('categories')->onDelete('cascade');
        });
    }
}
