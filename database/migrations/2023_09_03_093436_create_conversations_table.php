<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('conversation_id')->unique();
            $table->string('sender_nopeg');
            $table->string('admin');
            $table->boolean('in_queue')->default(true);
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();

            $table->foreign('sender_nopeg')->references('nopeg')->on('users')->onDelete('cascade');
            $table->foreign('admin')->references('nopeg')->on('users')->onDelete('cascade'); // admin adalah karyawan HC yang menjadi admin dari Employee Service
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
