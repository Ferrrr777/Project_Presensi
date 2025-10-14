<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'murid_id',     // <--- tambahkan
        'pengajar_id',  // <--- tambahkan
        'sesi',         // <--- tambahkan
        'waktu_absen',  // <--- tambahkan
        'status',       // <--- tambahkan
        'qr_code'       // <--- tambahkan
    ];

    public function murid() {
        return $this->belongsTo(Murid::class);
    }

    public function pengajar() {
        return $this->belongsTo(Pengajar::class);
    }
}

