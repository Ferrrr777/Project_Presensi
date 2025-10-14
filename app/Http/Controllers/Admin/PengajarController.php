<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajar;
use App\Models\AlatMusik;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PengajarController extends Controller
{
    // Tampilkan form tambah pengajar
    public function create()
    {
        $alatMusik = AlatMusik::all();
        return view('admin.tambah_pengajar', compact('alatMusik'));
       
    }

    // Simpan pengajar baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengajars,email',
            'password' => 'required|string|min:6',
            'alat_id' => 'required|exists:alat_musiks,id',
        ]);

        try {
            // Simpan ke database
            Pengajar::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'alat_id' => $request->alat_id,
            ]);

            // Redirect dengan flash message sukses
            return redirect()->back()->with('success', 'Pengajar berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Redirect dengan flash message error
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
