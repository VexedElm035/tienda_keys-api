<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
            Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 32);
            $table->string('email', 64)->unique();
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->string('role', 64);
            $table->timestamp('register_date')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void
    {
           Schema::dropIfExists('users');
    }
};
