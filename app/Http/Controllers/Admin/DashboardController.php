<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Pengajar;
use App\Models\Murid;
use App\Models\AlatMusik;

class DashboardController extends Controller
{
    public function index()
    {
        // Hari ini
        $hariList = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $hariIni = $hariList[date('w')];

        // Ambil jadwal tetap hari ini dengan relasi
        $jadwalHariIni = Jadwal::with(['alatMusik','murid','pengajar'])
            ->where('hari', $hariIni)
            ->get();

        // Ambil semua pengajar untuk dropdown modal
        $pengajars = Pengajar::all();

        // Ambil jadwal hari ini
$jadwalHariIni = Jadwal::where('hari', $hariIni)->get();

        // Statistik
        $totalSiswa = Murid::count();
        $totalPengajar = Pengajar::count();
        $totalAlat = AlatMusik::count();
        $totalJadwalHariIni = $jadwalHariIni->count();

        return view('admin.dashboard', [
            'jadwalHariIni' => $jadwalHariIni,
            'hariIni' => $hariIni,
            'totalSiswa' => $totalSiswa,
            'totalJadwalHariIni' => $totalJadwalHariIni,
            'totalPengajar' => $totalPengajar,
            'totalAlat' => $totalAlat,
            'pengajars' => $pengajars,
        ]);
     
    }
}
