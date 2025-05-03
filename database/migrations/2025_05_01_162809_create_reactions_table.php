<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('counter_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('action', ['c', 'r', 'd' , 'p']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
