<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessengerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger_settings', function (Blueprint $table) {
            $table->id();
            $table->text('page_name');
            $table->string('page_id', 20);
            $table->string('type', 100);
            $table->longText('primary_access_token');
            $table->longText('secondary_access_token')->nullable();
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
        Schema::dropIfExists('messenger_settings');
    }
}
