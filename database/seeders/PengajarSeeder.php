<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengajars')->insert([
            ['nama' => 'pengajar','email' => 'pengajar1@example.com', 'password' => bcrypt('12345678'),'alat_id' => 1],
                    
        ]);
    }
}