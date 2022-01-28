<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('fb_id', 20);
            $table->string('complete_name', 100);
            $table->string('contact_number', 15)->nullable();
            $table->text('location')->nullable();
            $table->text('product_location_purchase')->nullable();
            $table->string('date_of_purchase', 30)->nullable();
            $table->string('production_code', 60)->nullable();
            $table->longText('detailed_feedback')->nullable();
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
        Schema::dropIfExists('customer_feedback');
    }
}
