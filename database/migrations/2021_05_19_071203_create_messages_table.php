<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('message',250);
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('creator_id');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('creator_id')->references('id')->on('careteams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
