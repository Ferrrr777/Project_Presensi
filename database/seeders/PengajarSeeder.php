<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ← penting, agar DB::table bisa dipakai
use Illuminate\Support\Facades\Hash; // ← opsional, jika ingin pakai Hash::make

class PengajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengajars')->insert([
            [
                'nama' => 'pengajar',
                'email' => 'pengajar1@example.com',
                'password' => bcrypt('12345678'), // atau Hash::make('12345678')
                'alat_id' => 1
            ],
        ]);
    }
}
