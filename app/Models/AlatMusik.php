<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatMusik extends Model
{
    use HasFactory;

    protected $table = 'alat_musiks'; // tabel di database

    protected $fillable = ['nama'];
}
