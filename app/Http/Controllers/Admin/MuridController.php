<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\AlatMusik; // <-- jangan lupa ini

class MuridController extends Controller
{
    public function create()
    {
        $alatMusik = AlatMusik::all(); // ambil semua data alat musik
        return view('admin.tambah_siswa', compact('alatMusik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alat_id' => 'required|exists:alat_musiks,id',
        ]);

        Murid::create([
            'nama' => $request->nama,
            'alat_id' => $request->alat_id,
        ]);

        return redirect()->back()->with('success', 'Murid berhasil ditambahkan!');
    }
}
