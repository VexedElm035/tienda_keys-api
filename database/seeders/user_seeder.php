<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'username' => 'usuario',
                'email' => 'usuario@usuario.com',
                'password' => 'secreto',
                'avatar' => 'avatar',
                'role' => 'user',
                'register_date' => now(),
            ],
            [
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => 'secretoadmin',
                'avatar' => 'avataradmin',
                'role' => 'admin',
                'register_date' => now(),
            ],
        );
    }
}
