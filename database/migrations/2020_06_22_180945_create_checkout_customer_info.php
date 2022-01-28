<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutCustomerInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_customer_info', function (Blueprint $table) {
            $table->id();
            $table->string('fb_id', 30);
            $table->string('name', 100)->nullable();
            $table->string('email_address', 100)->nullable();
            $table->string('mobile_number', 13)->nullable();
            $table->string('city', 100)->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('payment_type', 30)->nullable();
            $table->bigInteger('previous_step_done')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout_customer_info');
    }
}
