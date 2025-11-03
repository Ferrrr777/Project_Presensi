<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrHarian;
use Illuminate\Support\Facades\Session;

class QrController extends Controller
{
    // Tampilkan halaman generate QR
    public function index()
    {
        $today = now()->toDateString();

        // Cari QR untuk hari ini
        $existing = QrHarian::where('tanggal', $today)->first();

        $qrCodeImage = null;
        $tanggal = null;

        if ($existing) {
            $qrCodeImage = QrCode::size(500)->generate($existing->qr_code);
            $tanggal = $existing->tanggal;
        }

        return view('admin.generate_qr', [
            'qrCodeImage' => $qrCodeImage,
            'tanggal' => $tanggal
        ]);
    }

    // Generate QR harian
    public function generate(Request $request)
    {
        $request->validate([
            // Tidak perlu validasi tambahan karena ini generate otomatis
        ]);

        $today = now()->toDateString();

        // Cek apakah QR untuk hari ini sudah ada
        $existing = QrHarian::where('tanggal', $today)->first();

        if ($existing) {
            // Sudah ada, redirect dengan pesan info
            Session::flash('info', 'QR Code untuk hari ini sudah tersedia.');
            $qrCodeImage = QrCode::size(400)->generate($existing->qr_code);
            return redirect()->route('admin.generate-qr-form')->with([
                'qrCodeImage' => $qrCodeImage,
                'tanggal' => $existing->tanggal
            ]);
        }

        // Buat data QR baru dengan timestamp untuk uniqueness
        $qrCodeData = "QR-Harian-" . $today . "-" . now()->format('His');

        try {
            $qr = QrHarian::create([
                'tanggal' => $today,
                'qr_code' => $qrCodeData,
            ]);

            Session::flash('success', 'QR Code harian berhasil digenerate!');
            
            $qrCodeImage = QrCode::size(400)->generate($qrCodeData);
            
           return redirect()->route('admin.generate-qr-form')->with([
    'qrCodeImage' => $qrCodeImage,
    'tanggal' => $qr->tanggal
]);

        } catch (\Exception $e) {
            Session::flash('error', 'Gagal generate QR Code. Silakan coba lagi.');
            return redirect()->route('admin.generate-qr-form');

        }
    }
}
