<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPresensiController extends Controller
{
    public function index(Request $request)
    {
        $presensis = $this->getFilteredPresensi($request);

        return view('admin.laporan_absensi', compact('presensis'));
    }

    public function exportExcel(Request $request)
    {
        $presensis = $this->getFilteredPresensi($request);

        // Ubah ke bentuk data sederhana untuk Excel
        $exportData = $presensis->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Tanggal' => $item->tanggal,
                'Nama Siswa' => $item->murid->nama ?? '-',
                'Alat Musik' => $item->alat->nama ?? '-',
                'Pengajar' => $item->pengajar->nama ?? '-',
                'Materi' => $item->materi ?? '-',
                'Status' => ucfirst($item->status ?? '-'),
            ];
        });

        // Export langsung tanpa class tambahan
        return Excel::download(new class($exportData) implements 
            \Maatwebsite\Excel\Concerns\FromCollection, 
            \Maatwebsite\Excel\Concerns\WithHeadings 
        {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
            public function headings(): array {
                return ['No', 'Tanggal', 'Nama Siswa', 'Alat Musik', 'Pengajar', 'Materi', 'Status'];
            }
        }, 'laporan_presensi_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Fungsi bantu untuk ambil data sesuai filter
     */
    private function getFilteredPresensi(Request $request)
    {
        $query = Presensi::with(['murid', 'alat', 'pengajar']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('group_by')) {
            $query->orderBy($request->group_by);
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        return $query->get();
    }
}
