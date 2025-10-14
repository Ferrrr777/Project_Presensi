<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengajar extends Authenticatable
{
    protected $fillable = [
        'nama',
        'email',
        'password',
        'alat_id',
    ];

    protected $hidden = ['password'];

    public function alatMusik()
    {
        return $this->belongsTo(AlatMusik::class, 'alat_id');
    }
}



