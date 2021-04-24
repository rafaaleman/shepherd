<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareteamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careteams', function (Blueprint $table) {
            $table->id();
            $table->integer('loveone_id');
            $table->integer('user_id');
            $table->integer('relationship_id');
            $table->string('role')->default('member');
            $table->text('permissions')->nullable();
            $table->text('status');
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
        Schema::dropIfExists('careteams');
    }
}
