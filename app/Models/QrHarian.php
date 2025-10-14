<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'qr_code',
    ];
}
