<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RescheduleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reschedules')->insert([
            [
                'jadwal_id' => 5,
                'tanggal_awal' => Carbon::today(),
                'hari_awal' => 'Senin',
                'jam_mulai_awal' => '09:00:00',
                'jam_selesai_awal' => '10:00:00',
                'hari_baru' => 'Rabu',
                'jam_mulai_baru' => '13:00:00',
                'jam_selesai_baru' => '14:00:00',
                'alasan' => 'Guru ada keperluan mendadak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jadwal_id' => 4,
                'tanggal_awal' => Carbon::today(),
                'hari_awal' => 'Selasa',
                'jam_mulai_awal' => '10:00:00',
                'jam_selesai_awal' => '11:00:00',
                'hari_baru' => 'Kamis',
                'jam_mulai_baru' => '15:00:00',
                'jam_selesai_baru' => '16:00:00',
                'alasan' => 'Murid meminta jadwal sore',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jadwal_id' => 3,
                'tanggal_awal' => Carbon::today(),
                'hari_awal' => 'Rabu',
                'jam_mulai_awal' => '14:00:00',
                'jam_selesai_awal' => '15:00:00',
                'hari_baru' => 'Jumat',
                'jam_mulai_baru' => '09:00:00',
                'jam_selesai_baru' => '10:00:00',
                'alasan' => 'Ruang kelas dipakai untuk ujian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
