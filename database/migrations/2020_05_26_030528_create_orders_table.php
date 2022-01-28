<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 60);
            $table->string('lastname', 60)->nullable();
            $table->string('email', 60)->comment('Encrypted email');
            $table->string('mobile_number', 13)->nullable();
            $table->string('reference_number', 100)->unique();
            $table->string('source', 30);
            $table->longText('shipping_address');
            $table->longText('billing_address')->nullable();
            $table->string('payment_method', 30);
            $table->string('payment_status', 100);
            
            $table->enum('state', ['new order', 'processing', 'shipping', 'delivered', 'cancelled'])->default('new order');
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
        Schema::dropIfExists('orders');
    }
}
