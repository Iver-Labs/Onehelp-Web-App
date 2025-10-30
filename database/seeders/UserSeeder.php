<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@example.com',
                'password_hash' => Hash::make('password'),
                'user_type' => 'admin',
                'is_active' => true
            ],
            [
                'email' => 'org1@example.com',
                'password_hash' => Hash::make('password'),
                'user_type' => 'organization',
                'is_active' => true
            ],
            [
                'email' => 'volunteer1@example.com',
                'password_hash' => Hash::make('password'),
                'user_type' => 'volunteer',
                'is_active' => true
            ],
        ]);
    }
}
