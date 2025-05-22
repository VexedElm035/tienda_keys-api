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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('img', 255)->nullable();
            $table->text('description')->nullable();
            $table->date('launch_date')->nullable();
            $table->string('publisher', 100)->nullable();
            $table->string('available_platforms', 255)->nullable();
            $table->string('genres', 100)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::dropIfExists('games');
    }
};
