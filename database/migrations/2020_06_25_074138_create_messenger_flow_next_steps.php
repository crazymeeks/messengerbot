<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessengerFlowNextSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger_flow_next_steps', function (Blueprint $table) {
            $table->id();
            $table->string('fb_id', 20);
            $table->text('next_expected_steps');
            $table->string('trigger_class', 255)->comment('The class that will be executed once user took different steps.');
            $table->string('another_trigger_class', 255)->nullable();
            $table->text('data')->nullable();
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
        Schema::dropIfExists('messenger_flow_next_steps');
    }
}
