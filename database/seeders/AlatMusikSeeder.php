<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlatMusikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alat_musiks')->insert([
            ['nama' => 'Piano 1'],
            ['nama' => 'Piano 2'],
            ['nama' => 'Gitar'],
            ['nama' => 'Drum'],
        ]);
    }
}
