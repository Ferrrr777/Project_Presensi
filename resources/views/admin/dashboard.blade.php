@extends('layouts.app')

@section('title', 'Dashboard Admin - Jadwal Hari Ini') 

@section('content')

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistik Cepat --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSiswa ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jadwal Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJadwalHariIni ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pengajar Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengajar ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Alat Musik Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlat ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-music fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi Cepat --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex gap-2 flex-wrap">
                <a href="/admin/kelola_jadwal" class="btn btn-outline-primary">
                    <i class="fas fa-calendar-alt me-1"></i> Kelola Jadwal Lengkap
                </a>
                <a href="/admin/generate-qr" class="btn btn-outline-success">
                    <i class="fas fa-qrcode me-1"></i> Generate QR Absensi
                </a>
                <a href="/admin/laporan" class="btn btn-outline-info">
                    <i class="fas fa-file-invoice me-1"></i> Lihat Laporan Absensi
                </a>
                <a href="/admin/tambah-siswa" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus me-1"></i> Tambah Siswa Baru
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Jadwal Hari Ini --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-calendar-day me-2"></i> Jadwal Les {{ $hariIni ?? 'Hari Ini' }} ({{ date('d/m/Y') }})
            </h5>
            <span class="badge bg-light text-dark">{{ ($jadwalHariIni ?? collect())->count() ?? '0' }} Jadwal</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th style="width: 15%">Jam</th>
                            <th style="width: 20%">Alat Musik</th>
                            <th style="width: 25%">Murid</th>
                            <th style="width: 20%">Pengajar</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalHariIni as $jadwal)
                        <tr class="align-middle text-center {{ $jadwal->jam_mulai < date('H:i') ? 'table-warning' : '' }}">
                            <td>
                                <strong>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</strong>
                                @if($jadwal->jam_mulai < date('H:i'))
                                    <br><small class="text-danger"><i class="fas fa-clock me-1"></i>Sudah Dimulai</small>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-guitar me-1" style="color: #92140C;"></i>
                                {{ $jadwal->alatMusik->nama ?? '-' }}
                            </td>
                            <td>
                                <i class="fas fa-user-graduate me-1"></i>
                                {{ $jadwal->murid->nama ?? '-' }}
                            </td>
                            <td>
                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                {{ $jadwal->pengajar->nama ?? '-' }}
                            </td>
                            <td class="text-center">
                                @if($jadwal->status == 'aktif' || empty($jadwal->status))
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($jadwal->status == 'reschedule')
                                    <span class="badge bg-warning text-dark">Reschedule</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($jadwal->status ?? 'Unknown') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($jadwal->status != 'reschedule')
                                <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $jadwal->id }}" title="Reschedule" aria-label="Edit jadwal">
                                       <i class="fas fa-pen-to-square me-1"></i> Reschedule
                               </button>
                                    @else
                                        <span class="text-muted small">Sudah di-reschedule</span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Reschedule per Jadwal (hanya jika belum reschedule) --}}
                        @if($jadwal->status != 'reschedule')
                        <div class="modal fade" id="rescheduleModal{{ $jadwal->id }}" tabindex="-1" aria-labelledby="rescheduleModalLabel{{ $jadwal->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.reschedule.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                        {{-- Hidden input untuk hari baru, otomatis diisi JS --}}
                                        <input type="hidden" name="hari_baru" class="hari-baru" data-jadwal-id="{{ $jadwal->id }}">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rescheduleModalLabel{{ $jadwal->id }}">
                                                Reschedule Jadwal: {{ $jadwal->murid->nama ?? '-' }} - {{ $jadwal->alatMusik->nama ?? '-' }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Error validasi umum --}}
                                            @error('jadwal_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            @error('tanggal_baru')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            @error('jam_mulai_baru')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            @error('jam_selesai_baru')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            @error('pengajar_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            @error('hari_baru')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            {{-- Input Tanggal Baru --}}
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Baru</label>
                                                <input type="date" name="tanggal_baru" class="form-control tanggal-baru" id="tanggal_baru_{{ $jadwal->id }}" 
                                                       value="{{ old('tanggal_baru', date('Y-m-d')) }}" required>
                                            </div>

                                            {{-- Jam Mulai & Selesai Baru --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Jam Mulai Baru</label>
                                                    <input type="time" name="jam_mulai_baru" class="form-control" 
                                                           value="{{ old('jam_mulai_baru', $jadwal->jam_mulai) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Jam Selesai Baru</label>
                                                    <input type="time" name="jam_selesai_baru" class="form-control" 
                                                           value="{{ old('jam_selesai_baru', $jadwal->jam_selesai) }}" required>
                                                </div>
                                            </div>

                                            {{-- Pilih Pengajar Baru --}}
                                            <div class="mb-3 mt-3">
                                                <label class="form-label">Pilih Pengajar Baru</label>
                                                <select name="pengajar_id" class="form-select" required>
                                                    <option value="">-- Pilih Pengajar --</option>
                                                    @foreach($pengajars as $pengajar)
                                                        <option value="{{ $pengajar->id }}" {{ old('pengajar_id', $jadwal->pengajar_id) == $pengajar->id ? 'selected' : '' }}>
                                                            {{ $pengajar->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Alasan Reschedule --}}
                                            <div class="mb-3 mt-3">
                                                <label class="form-label">Alasan (opsional)</label>
                                                <textarea name="alasan" class="form-control" rows="3" placeholder="Masukkan alasan reschedule...">{{ old('alasan') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">Simpan Reschedule</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                Belum ada jadwal untuk hari ini ({{ $hariIni }}).
                                <br><small>Tambahkan jadwal melalui <a href="/admin/kelola_jadwal">Kelola Jadwal</a>.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="row">
        <div class="col-md-12">
            <h5 class="text-center mb-3">Fitur Cepat</h5>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/admin/tambah-siswa" class="btn btn-outline-success btn-lg">
                    <i class="fas fa-user-plus me-2"></i> Tambah Siswa
                </a>
                <a href="/admin/tambah-pengajar" class="btn btn-outline-info btn-lg">
                    <i class="fas fa-user-plus me-2"></i> Tambah Pengajar
                </a>
                <a href="{{ route('admin.reschedule.index') }}" class="btn btn-outline-warning btn-lg">
                    <i class="fas fa-exchange-alt me-2"></i> Reschedule
                </a>
                <a href="/admin/tambah-alatmusik" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-music me-2"></i> Tambah Alat Musik
                </a>
            </div>
        </div>
    </div>

    

<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(session('success'))
        Swal.fire({
            title: '✅ Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: '❌ Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            showConfirmButton: true,
        });
    @endif

    // Auto-refresh setiap 5 menit untuk update absensi
    setInterval(() => {
        location.reload();
    }, 300000);
});

function updateHariBaru(tanggalInput, hariInput) {
    const tanggalStr = tanggalInput.value;
    if (tanggalStr) {
        const tanggal = new Date(tanggalStr + 'T00:00:00'); 
        const hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const hariIndex = tanggal.getDay(); 
        hariInput.value = hariList[hariIndex];
    } else {
        hariInput.value = '';
    }
}

// Event listener
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('tanggal-baru')) {
        const jadwalId = e.target.id.replace('tanggal_baru_', '');
        const hariInput = document.querySelector(`input.hari-baru[data-jadwal-id="${jadwalId}"]`);
        if (hariInput) updateHariBaru(e.target, hariInput);
    }
});

</script>

@endsection