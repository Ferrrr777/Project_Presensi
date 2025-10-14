<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    protected $fillable = ['nama', 'alat_id'];

    public function alatMusik()
    {
        return $this->belongsTo(AlatMusik::class, 'alat_id');
    }
}
