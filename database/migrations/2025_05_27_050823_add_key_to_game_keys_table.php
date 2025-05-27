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
        Schema::table('game_keys', function (Blueprint $table) {
            $table->string('key', 64)->after('platform')->nullable(); // Nullable temporalmente
        });

        // Si quieres hacerlo no nullable después de llenar los datos existentes:
        // Schema::table('game_keys', function (Blueprint $table) {
        //     $table->string('key', 64)->nullable(false)->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_keys', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }
};