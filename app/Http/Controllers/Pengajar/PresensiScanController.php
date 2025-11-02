<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiScan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PresensiScanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pengajars');
    }

    public function store(Request $request)
    {
        // Debug info guard
        $guardUser = Auth::guard('pengajars')->user();
        $debugInfo = [
            'guard' => 'pengajars',
            'user_id' => $guardUser?->id,
            'email' => $guardUser?->email,
        ];

        // Tampilkan log di Laravel log
        Log::info('DEBUG SCAN', $debugInfo);

        // Jika tidak login
        if (!$guardUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak login (guard pengajars tidak aktif).',
                'debug' => $debugInfo
            ], 401);
        }

        $kodeQr = $request->kode_qr;
        if (!$kodeQr) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode QR tidak boleh kosong.',
                'debug' => $debugInfo
            ], 400);
        }

        // Cek sudah scan hari ini
        $sudahScan = PresensiScan::where('user_id', $guardUser->id)
            ->whereDate('tanggal_scan', now()->toDateString())
            ->exists();

        if ($sudahScan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah melakukan scan hari ini.',
                'debug' => $debugInfo
            ], 400);
        }

        // Simpan
        PresensiScan::create([
            'user_id' => $guardUser->id,
            'kode_qr' => $kodeQr,
            'tanggal_scan' => now()->toDateString(),
            'waktu_scan' => now()->toTimeString(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Scan berhasil disimpan.',
            'debug' => $debugInfo
        ]);
    }
}
