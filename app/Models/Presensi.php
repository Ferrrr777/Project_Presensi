<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'tanggal',
        'murid_id',
        'alat_id',
        'status',
        'pengajar_id',
        'materi',
    ];

    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

    public function alat()
    {
        return $this->belongsTo(AlatMusik::class, 'alat_id');
    }

    public function pengajar()
    {
        return $this->belongsTo(Pengajar::class);
    }
}
