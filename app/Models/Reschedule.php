<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reschedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'murid_id',
        'pengajar_id',
        'alat_musik_id',
        'tanggal_awal',
        'hari_awal',
        'jam_mulai_awal',
        'jam_selesai_awal',
        'hari_baru',
        'tanggal_baru', // Tambahkan ini untuk tanggal spesifik reschedule
        'jam_mulai_baru',
        'jam_selesai_baru',
        'alasan',
    ];

    // Cast dates
    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_baru' => 'date',
        'jam_mulai_awal' => 'datetime:H:i',
        'jam_selesai_awal' => 'datetime:H:i',
        'jam_mulai_baru' => 'datetime:H:i',
        'jam_selesai_baru' => 'datetime:H:i',
    ];

    // Relasi (sudah ada, tapi pastikan import model)


    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

    public function pengajar()
    {
        return $this->belongsTo(Pengajar::class);
    }

    public function alatMusik()
    {
        return $this->belongsTo(AlatMusik::class, 'alat_musik_id');
    }

    // Accessor untuk format tanggal (opsional, untuk view)
    public function getTanggalBaruFormattedAttribute()
    {
        return $this->tanggal_baru ? Carbon::parse($this->tanggal_baru)->format('d/m/Y') : '-';
    }

     public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function getHariBaruFormattedAttribute()
    {
        $hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $hariList[array_search($this->hari_baru, $hariList)] ?? $this->hari_baru;
    }
}
