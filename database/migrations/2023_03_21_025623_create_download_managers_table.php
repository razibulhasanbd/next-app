<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_managers', function (Blueprint $table) {
            $table->id();
            $table->string('module',30)->comment('Feature or Menu name');
            $table->string('title',191)->comment('File name');
            $table->string('url',191)->comment('Download link');
            $table->unsignedBigInteger('user_id')->comment('Who Download the report');
            $table->integer('status')->default(0)->comment('0 = processing, 1 = complete, 2 = data not found or error');
            $table->text('remark')->nullable()->comment('Comment or error reason');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('download_managers');
    }
}
