<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('from_user');
            $table->string('to_user');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('from_user')->references('nopeg')->on('users');
            $table->foreign('to_user')->references('nopeg')->on('users');
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
