<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reschedule;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Pengajar;

class RescheduleController extends Controller
{
    // Menampilkan semua reschedule
   public function index()
{
    try {
        // Hanya ambil reschedule dengan tanggal baru >= hari ini
        $reschedules = Reschedule::with([
                'jadwal.murid',
                'jadwal.pengajar',
                'jadwal.alatMusik',
                'murid',
                'pengajar',
                'alatMusik'
            ])
            ->where('tanggal_baru', '>=', Carbon::today()) // 🔹 filter tanggal
            ->orderBy('tanggal_baru', 'asc') // bisa ubah ke desc kalau mau terbaru di atas
            ->get();

        // Ambil semua pengajar untuk dropdown di modal edit
        $pengajars = Pengajar::all();

        return view('admin.reschedule', [
            'reschedules' => $reschedules,
            'pengajars'   => $pengajars,
        ]);
    } catch (\Exception $e) {
        Log::error('Error loading reschedule: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memuat data reschedule.');
    }
}


    // Simpan reschedule
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal_baru' => 'required|date|after_or_equal:today',
            'jam_mulai_baru' => 'required|date_format:H:i',
            'jam_selesai_baru' => 'required|date_format:H:i|after:jam_mulai_baru',
            'pengajar_id' => 'required|exists:pengajars,id',
            'alasan' => 'nullable|string|max:500',
        ], [
            'jam_selesai_baru.after' => 'Jam selesai harus setelah jam mulai.',
            'tanggal_baru.after_or_equal' => 'Tanggal baru harus hari ini atau setelahnya.',
            'pengajar_id.exists' => 'Pengajar tidak valid.',
        ]); 

        DB::beginTransaction();

        try {
            // Ambil data jadwal lengkap
            $jadwal = Jadwal::with(['murid', 'pengajar', 'alatMusik'])
                ->findOrFail($validated['jadwal_id']);

            // Pastikan data lengkap
            if (!$jadwal->murid_id || !$jadwal->pengajar_id || !$jadwal->alat_id) {
                throw new \Exception('Data jadwal tidak lengkap (murid/pengajar/alat musik kosong).');
            }

            // Hitung hari baru pakai locale Indonesia
            $hari_baru = Carbon::parse($validated['tanggal_baru'])
                ->locale('id')->isoFormat('dddd');

            // Simpan reschedule
            Reschedule::create([
                'jadwal_id' => $jadwal->id,
                'murid_id' => $jadwal->murid_id,
                'pengajar_id' => $jadwal->pengajar_id,
                'alat_musik_id' => $jadwal->alat_id,
                'tanggal_awal' => now()->format('Y-m-d'),
                'hari_awal' => Carbon::now()->locale('id')->isoFormat('dddd'),
                'jam_mulai_awal' => $jadwal->jam_mulai,
                'jam_selesai_awal' => $jadwal->jam_selesai,
                'tanggal_baru' => $validated['tanggal_baru'],
                'hari_baru' => $hari_baru,
                'jam_mulai_baru' => $validated['jam_mulai_baru'],
                'jam_selesai_baru' => $validated['jam_selesai_baru'],
                'alasan' => $validated['alasan'] ?? null,
            ]);

            // Update jadwal dinamis
            $jadwal->update([
                'status' => 'reschedule',
            ]);

            DB::commit();

            return redirect()->route('admin.dashboard')
                ->with('success', 'Reschedule berhasil disimpan dan jadwal diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error menyimpan reschedule: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menyimpan reschedule: ' . $e->getMessage());
        }
    }

    public function cancel($id)
{
    DB::beginTransaction();
    try {
        $reschedule = Reschedule::findOrFail($id);
        $jadwal = Jadwal::findOrFail($reschedule->jadwal_id);

        // Hanya ubah status jadwal menjadi aktif
        $jadwal->update([
            'status' => 'aktif',
        ]);

        // Hapus reschedule
        $reschedule->delete();

        DB::commit();
        return redirect()->back()->with('success', 'Reschedule berhasil dibatalkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal batal reschedule: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal membatalkan reschedule.');
    }
}

  // Update reschedule dari modal
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_baru' => 'required|date|after_or_equal:today',
            'jam_mulai_baru' => 'required|date_format:H:i',
            'jam_selesai_baru' => 'required|date_format:H:i|after:jam_mulai_baru',
            'pengajar_id' => 'required|exists:pengajars,id',
            'alasan' => 'nullable|string|max:500',
        ], [
            'jam_selesai_baru.after' => 'Jam selesai harus setelah jam mulai.',
            'tanggal_baru.after_or_equal' => 'Tanggal baru harus hari ini atau setelahnya.',
        ]);
        $validated['jam_mulai_baru']   = $validated['jam_mulai_baru'] . ':00';
        $validated['jam_selesai_baru'] = $validated['jam_selesai_baru'] . ':00';

        DB::beginTransaction();
        try {
            $reschedule = Reschedule::findOrFail($id);
            $jadwal = Jadwal::findOrFail($reschedule->jadwal_id);

            $hari_baru = Carbon::parse($validated['tanggal_baru'])->locale('id')->isoFormat('dddd');

            $reschedule->update([
                'tanggal_baru' => $validated['tanggal_baru'],
                'hari_baru' => $hari_baru,
                'jam_mulai_baru' => $validated['jam_mulai_baru'],
                'jam_selesai_baru' => $validated['jam_selesai_baru'],
                'pengajar_id' => $validated['pengajar_id'],
                'alasan' => $validated['alasan'] ?? $reschedule->alasan,
            ]);

            $jadwal->update([
                'jam_mulai' => $validated['jam_mulai_baru'],
                'jam_selesai' => $validated['jam_selesai_baru'],
                'status' => 'reschedule',
            ]);

            DB::commit();
            return redirect()->route('admin.reschedule.index')->with('success', 'Reschedule berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update reschedule: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui reschedule.');
        }
    }

}
