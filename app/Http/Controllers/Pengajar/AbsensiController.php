<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    // Halaman dashboard pengajar
    public function index()
    {
        return view('pengajar.dashboard');
    }

    // Form scan QR
    public function scanQrForm()
    {
        return view('pengajar.scan_qr');
    }

    // Proses scan QR
    public function scanQr(Request $request)
    {
        $request->validate([
            
            'qr_code' => 'required|string'
        ]);

        $qrData = $request->qr_code;

        // Contoh format QR: "Sesi: Piano Dasar | PengajarID:1"
        preg_match('/Sesi: (.+) \| PengajarID:(\d+)/', $qrData, $matches);
        if (!$matches) {
            return back()->with('error', 'QR Code tidak valid');
        }

        $sesi = $matches[1];
        $pengajarId = $matches[2];

        // Ambil semua murid yang alat musiknya sesuai sesi
        $murids = Murid::where('alat_musik', 'LIKE', "%$sesi%")->get();

        foreach ($murids as $murid) {
            Absensi::create([
                'murid_id' => $murid->id,
                'pengajar_id' => $pengajarId,
                'sesi' => $sesi,
                'waktu_absen' => now(),
                'status' => 'Hadir',
                'qr_code' => $qrData
            ]);
        }

        return back()->with('success', 'Absensi murid berhasil dicatat!');
    }
}

