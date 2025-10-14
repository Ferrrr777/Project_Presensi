<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'murid_id',
        'pengajar_id',
        'alat_id',
        'hari', // ubah dari tanggal ke hari
        'jam_mulai',
        'jam_selesai',
        'status'
    ];


    
public function murid()
{
    return $this->belongsTo(Murid::class, 'murid_id');
}

public function pengajar()
{
    return $this->belongsTo(Pengajar::class, 'pengajar_id');
}

public function alatMusik()
{
    return $this->belongsTo(AlatMusik::class, 'alat_id');
}

  public function reschedules()
    {
        return $this->hasMany(Reschedule::class, 'jadwal_id');
    }   
    
}
