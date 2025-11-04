<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pengajar\AbsensiController;
use App\Http\Controllers\Admin\QrController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MuridController;
use App\Http\Controllers\Admin\AlatMusikController;
use App\Http\Controllers\Admin\PengajarController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\RescheduleController;
use App\Http\Controllers\Pengajar\PresensiController;
use App\Http\Controllers\Admin\LaporanPresensiController;
use App\Http\Controllers\Pengajar\PresensiScanController;




// =======================
// HALAMAN LOGIN / UTAMA
// =======================

Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Proses login (POST)
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 🔹 Logout semua guard dulu
    Auth::guard('web')->logout();
    Auth::guard('pengajars')->logout();

    // -----------------------
    // 1️⃣ Coba login sebagai admin (guard: web)
    // -----------------------
    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::guard('web')->user();

        // Jika punya kolom role di tabel users
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika ternyata user biasa tapi bukan admin
        return redirect('/');
    }

    // -----------------------
    // 2️⃣ Coba login sebagai pengajar (guard: pengajars)
    // -----------------------
    if (Auth::guard('pengajars')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('pengajar.dashboard');
    }

    // -----------------------
    // 3️⃣ Jika dua-duanya gagal
    // -----------------------
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
});

// =======================
// ✅ LOGOUT UNTUK DUA GUARD
// =======================
Route::post('/logout', function (Request $request) {
    // Logout dari dua guard sekaligus
    Auth::guard('web')->logout();
    Auth::guard('pengajars')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Anda telah logout.');
})->name('logout');

// =======================
// ROUTE ADMIN
// =======================
Route::prefix('admin')->middleware('auth:web')->name('admin.')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Generate QR
    Route::get('generate-qr', [QrController::class, 'index'])->name('generate-qr-form');
    Route::post('generate-qr', [QrController::class, 'generate'])->name('generate-qr');

    

    // Pengajar
    Route::get('tambah-pengajar', [PengajarController::class, 'create'])->name('pengajar.create');
    Route::post('tambah-pengajar', [PengajarController::class, 'store'])->name('pengajar.store');

   
   
   // Laporan Presensi
    Route::get('/laporan-presensi', [LaporanPresensiController::class, 'index'])->name('laporan.presensi');
    Route::get('/laporan-presensi/export-excel', [LaporanPresensiController::class, 'exportExcel'])->name('laporan.exportExcel');

  

    // Alat musik
    Route::get('tambah-alatmusik', [AlatMusikController::class, 'create'])->name('alatmusik.create');
    Route::post('tambah-alatmusik', [AlatMusikController::class, 'store'])->name('alatmusik.store');

    // Murid
    Route::get('tambah-siswa', [MuridController::class, 'create'])->name('murid.create');
    Route::post('tambah-siswa', [MuridController::class, 'store'])->name('murid.store');

    // Jadwal
    Route::get('kelola_jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('kelola_jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('kelola_jadwal/edit/{jadwal}', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('kelola_jadwal/edit/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('kelola_jadwal/hapus/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('get-murid-by-alat/{alat_id}', [JadwalController::class, 'getMuridByAlat']);

    Route::get('reschedule', [RescheduleController::class, 'index'])->name('reschedule.index');
    Route::post('reschedule', [RescheduleController::class, 'store'])->name('reschedule.store');
    Route::get('reschedule/{id}/edit', [RescheduleController::class, 'edit'])->name('reschedule.edit');
    Route::put('reschedule/{id}', [RescheduleController::class, 'update'])->name('reschedule.update');
    Route::delete('reschedule/{id}/cancel', [RescheduleController::class, 'cancel'])->name('reschedule.cancel');

});

// =======================
// ROUTE PENGAJAR
// =======================
Route::prefix('pengajar')->middleware('auth:pengajars')->name('pengajar.')->group(function() {
    Route::get('dashboard', [AbsensiController::class, 'index'])->name('dashboard');
    
   

    Route::get('scan_qr', [AbsensiController::class, 'scanQrForm'])->name('scan-qr-form');
     Route::post('presensi-scan/store', [PresensiScanController::class, 'store'])->name('presensi.scan.store');
    
    Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('presensi/store', [PresensiController::class, 'store'])->name('presensi.store');

    Route::get('laporan', function(){ return view('pengajar.laporan'); })->name('laporan');
    
  Route::get('laporan/pdf', [PengajarController::class, 'laporanPdf'])->name('laporan.pdf');
  Route::get('laporan/excel', [PengajarController::class, 'laporanExcel'])->name('laporan.excel');
  

});
