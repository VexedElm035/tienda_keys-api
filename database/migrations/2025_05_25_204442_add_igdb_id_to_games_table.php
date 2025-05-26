<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_add_igdb_id_to_games_table.php
public function up()
{
    Schema::table('games', function (Blueprint $table) {
        $table->unsignedBigInteger('igdb_id')->nullable()->unique()->after('id');
    });
}

public function down()
{
    Schema::table('games', function (Blueprint $table) {
        $table->dropColumn('igdb_id');
    });
}
};
