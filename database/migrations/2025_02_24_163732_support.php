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
         Schema::create('support', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_seller');
            $table->unsignedBigInteger('user_id_buyer');
            $table->string('issue', 255);
            $table->text('description');
            $table->string('state', 64)->default('abierto');
            $table->unsignedBigInteger('game_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id_buyer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_seller')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::dropIfExists('support');
    }
};
