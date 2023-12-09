<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50)->comment('payment method title');
            $table->unsignedBigInteger("country_category");
            $table->string("payment_method", 50)->comment('payment method');
            $table->string("payment_method_form_type", 50);
            $table->integer("commission")->default(0);
            $table->string("address",191)->nullable()->comment('Amount transfer to this address');
            $table->text("icon")->nullable();
            $table->json("data")->nullable()->comment('Only for bank transfer details will store. like beneficiary name, account number, swift code etc');
            $table->json("qr_code_instructions")->nullable();
            $table->text("remarks")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->tinyInteger("is_sent_for_review")->default(true);
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("reviewed_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
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
        Schema::dropIfExists('payment_methods');
    }
}
