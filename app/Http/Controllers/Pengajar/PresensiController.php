<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Jadwal;
use App\Models\Reschedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PresensiController extends Controller
{
    public function index()
    {
        $pengajar = Auth::guard('pengajars')->user();
        $tanggal = Carbon::today()->toDateString();
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l'); // 'Senin', 'Selasa', dll

        // Ambil jadwal asli hari ini
        $jadwals = Jadwal::with(['murid', 'alatMusik'])
            ->where('pengajar_id', $pengajar->id)
            ->where('hari', $hariIni)
            ->get();

        // Ambil reschedule hari ini
        $reschedules = Reschedule::with(['murid', 'alatMusik'])
            ->where('pengajar_id', $pengajar->id)
            ->whereDate('tanggal_baru', $tanggal)
            ->get();

        // Gabungkan jadwal asli + reschedule (hindari duplikasi jika ada)
        $jadwalHariIni = $jadwals->toBase()->merge($reschedules->toBase())->unique(function ($item) {
            $alatId = $item->alatMusik->id ?? $item->alat_id ?? $item->alat_musik_id;
           return $item->murid_id . '-' . $alatId;
        });
        // Urutkan berdasarkan jam mulai (jam_mulai_baru dulu jika ada, kalau tidak jam_mulai)
          $jadwalHariIni = $jadwalHariIni->sortBy(function ($item) {
         $jamMulai = $item->jam_mulai_baru ?? $item->jam_mulai;
         return \Carbon\Carbon::parse($jamMulai);
         });

        // Ambil presensi hari ini untuk pengajar
        $presensis = Presensi::where('tanggal', $tanggal)
            ->where('pengajar_id', $pengajar->id)
            ->get()
            ->keyBy(function ($item) {
                return $item->murid_id . '-' . $item->alat_id;
            });

        return view('pengajar.presensi', [
            'jadwals' => $jadwalHariIni,
            'presensis' => $presensis,
            'tanggal' => $tanggal
        ]);
    }

    public function store(Request $request)
    {
        $pengajar = Auth::guard('pengajars')->user();
        $tanggal = Carbon::today()->toDateString();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'murid_id' => 'required|exists:murids,id',
            'alat_id' => 'required|exists:alat_musiks,id',
            'status' => 'required|in:hadir,tidak_hadir'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        try {
            // Periksa apakah pengajar memiliki jadwal untuk murid dan alat ini hari ini
            $hariIni = Carbon::now()->locale('id')->translatedFormat('l');
            $jadwalExists = Jadwal::where('pengajar_id', $pengajar->id)
                ->where('murid_id', $request->murid_id)
                ->where('alat_id', $request->alat_id)
                ->where('hari', $hariIni)
                ->exists();

            $rescheduleExists = Reschedule::where('pengajar_id', $pengajar->id)
                ->where('murid_id', $request->murid_id)
                ->where('alat_musik_id', $request->alat_id)
                ->whereDate('tanggal_baru', $tanggal)
                ->exists();

            if (!$jadwalExists && !$rescheduleExists) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan untuk pengajar ini.'], 403);
            }

            // Simpan presensi atau update jika sudah ada
            $presensi = Presensi::updateOrCreate(
                [
                    'murid_id' => $request->murid_id,
                    'alat_id' => $request->alat_id, // Tambahkan alat_id ke unique key untuk konsistensi
                    'tanggal' => $tanggal,
                    'pengajar_id' => $pengajar->id
                ],
                [
                    'status' => $request->status
                ]
            );

            return response()->json(['success' => true, 'data' => $presensi, 'message' => 'Presensi berhasil diperbarui.']);
        } catch (\Exception $e) {
            // Log error jika perlu
            \Log::error('Error updating presensi: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan presensi.'], 500);
        }
    }
}
