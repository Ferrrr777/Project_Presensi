<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Murid;
use App\Models\Pengajar;
use App\Models\AlatMusik;

class JadwalController extends Controller
{
    // Tampilkan halaman kelola jadwal
public function index()
{
    $jadwals = Jadwal::with(['murid', 'pengajar', 'alatMusik'])
        ->orderBy('hari')
        ->orderBy('jam_mulai')
        ->get();

    $murids = Murid::all();
    $pengajars = Pengajar::all();
    $alatMusiks = AlatMusik::all();

    return view('admin.kelola_jadwal', compact('jadwals','murids','pengajars','alatMusiks'));
}


    // Simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'murid_id' => 'required|exists:murids,id',
            'pengajar_id' => 'required|exists:pengajars,id',
            'alat_id' => 'required|exists:alat_musiks,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]
    );

        Jadwal::create($request->all());

        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil ditambahkan');
    }

    // Edit jadwal
    public function edit(Jadwal $jadwal)
    {
        $murids = Murid::all();
        $pengajars = Pengajar::all();
        $alatMusiks = AlatMusik::all();
        return view('admin.edit_jadwal', compact('jadwal','murids','pengajars','alatMusiks'));
    }

    // Update jadwal
    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'murid_id' => 'required|exists:murids,id',
            'pengajar_id' => 'required|exists:pengajars,id',
            'alat_id' => 'required|exists:alat_musiks,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil diupdate');
    }

    // Hapus jadwal
    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil dihapus');
    }

    // Ambil murid berdasarkan alat musik (AJAX)
   public function getMuridByAlat($alat_id)
{
    $murids = Murid::where('alat_id', $alat_id)->get(['id','nama']); 
    return response()->json($murids);
}

}
