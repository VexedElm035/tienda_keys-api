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
         Schema::create('game_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('state', 32);
            $table->string('region', 100);
            $table->decimal('price', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->string('delivery_time', 100);
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->string('platform', 100);
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_keys');
    }
};
