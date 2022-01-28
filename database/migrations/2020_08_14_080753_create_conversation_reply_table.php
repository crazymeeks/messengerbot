<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_reply', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chatter_id')->unsigned();
            $table->bigInteger('admin_user_id')->unsigned()->nullable();
            $table->longText('reply');
            $table->enum('answered_by_admin', ['0', '1'])->default('0');
            $table->string('stime', 30);
            $table->timestamps();

            $table->foreign('chatter_id')->references('id')->on('chatters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_reply');
    }
}
