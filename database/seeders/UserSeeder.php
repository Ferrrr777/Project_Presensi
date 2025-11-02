<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ← penting agar DB::table bisa dipakai
use Illuminate\Support\Facades\Hash; // ← opsional, bisa pakai Hash::make

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('12345678'), // atau Hash::make('12345678')
                'role' => 'admin'
            ],
        ]);
    }
}
