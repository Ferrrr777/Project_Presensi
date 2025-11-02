<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiScan extends Model
{
    use HasFactory;

    protected $table = 'presensi_scan';

    protected $fillable = [
        'user_id',
        'kode_qr',
        'tanggal_scan',
        'waktu_scan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
