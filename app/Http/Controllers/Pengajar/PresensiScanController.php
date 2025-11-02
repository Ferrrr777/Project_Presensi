<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiScan;
use Illuminate\Support\Facades\Auth;

class PresensiScanController extends Controller
{
   public function store(Request $request)
{
    $user = Auth::guard('pengajars')->user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Anda belum login sebagai pengajar.'
        ], 401);
    }

    $kodeQr = $request->kode_qr;
    if (!$kodeQr) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kode QR tidak boleh kosong.'
        ], 400);
    }

    // Cek sudah scan hari ini
    $sudahScan = PresensiScan::where('user_id', $user->id)
        ->whereDate('tanggal_scan', now()->toDateString())
        ->exists();

    if ($sudahScan) {
        return response()->json([
            'status' => 'error',
            'message' => 'Anda sudah melakukan scan hari ini.'
        ], 400);
    }

    // Debug log
    \Log::info('SIMPAN SCAN:', [
        'user_id' => $user->id,
        'kode_qr' => $kodeQr
    ]);

    // Simpan
    PresensiScan::create([
        'user_id' => $user->id,
        'kode_qr' => $kodeQr,
        'tanggal_scan' => now()->toDateString(),
        'waktu_scan' => now()->toTimeString(),
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Scan berhasil disimpan.'
    ]);
}

}
