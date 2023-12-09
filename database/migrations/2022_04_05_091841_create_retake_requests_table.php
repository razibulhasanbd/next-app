<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetakeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retake_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('metric_info');
            $table->string('rules_reached');
            $table->datetime('approved_at')->nullable();
            $table->datetime('denied_at')->nullable();
            $table->integer('plan_id');
            $table->integer('account_id');
            $table->integer('subscription_id')->nullable();
            $table->string('admin_message')->nullable();
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
        Schema::dropIfExists('retake_requests');
    }
}
