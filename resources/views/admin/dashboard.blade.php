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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-calendar-day me-2"></i>Jadwal Les {{ $hariIni ?? 'Hari Ini' }} ({{ date('d/m/Y') }})
                    </h4>
                    <small class="text-white-50">Kelola jadwal les dengan mudah</small>
                </div>
                <div class="card-body p-0">
                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="jadwalTable">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th class="border-0 fw-bold">#</th>
                                        <th class="border-0 fw-bold">Jam</th>
                                        <th class="border-0 fw-bold">Alat Musik</th>
                                        <th class="border-0 fw-bold">Murid</th>
                                        <th class="border-0 fw-bold">Pengajar</th>
                                        <th class="border-0 fw-bold">Status</th>
                                        <th class="border-0 fw-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwalHariIni as $index => $jadwal)
                                        <tr class="animate__animated animate__fadeInLeft" style="animation-delay: {{ $index * 0.05 }}s;">
                                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                <strong>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</strong>
                                                @if($jadwal->jam_mulai < date('H:i'))
                                                    <br><small class="text-danger"><i class="bi bi-clock me-1"></i>Sudah Dimulai</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $jadwal->alatMusik->nama ?? '-' }}</span>
                                            </td>
                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                        {{ strtoupper(substr($jadwal->murid->nama ?? '-', 0, 1)) }}
                                                    </div>
                                                    <span>{{ $jadwal->murid->nama ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-info text-white me-2">
                                                        {{ strtoupper(substr($jadwal->pengajar->nama ?? '-', 0, 1)) }}
                                                    </div>
                                                    <span>{{ $jadwal->pengajar->nama ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill fs-6 px-3 py-2
                                                    @if($jadwal->status == 'aktif' || empty($jadwal->status))
                                                        bg-success
                                                    @elseif($jadwal->status == 'reschedule')
                                                        bg-warning text-dark
                                                    @else
                                                        bg-secondary
                                                    @endif">
                                                    @if($jadwal->status == 'aktif' || empty($jadwal->status))
                                                        Aktif
                                                    @elseif($jadwal->status == 'reschedule')
                                                        Reschedule
                                                    @else
                                                        {{ ucfirst($jadwal->status ?? 'Unknown') }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Jadwal">
                                                    @if($jadwal->status != 'reschedule')
                                                    <button class="btn btn-warning btn-reschedule shadow-sm position-relative"
                                                        data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $jadwal->id }}"
                                                        title="Reschedule" aria-label="Reschedule jadwal">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        <span class="d-none d-sm-inline">Reschedule</span>
                                                    </button>
                                                    @else
                                                        <span class="text-muted small">Sudah di-reschedule</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="bi bi-info-circle fs-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada jadwal hari ini</h5>
                                                <p class="text-muted">Silakan periksa kembali tanggal atau tambahkan jadwal baru.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        @forelse($jadwalHariIni as $index => $jadwal)
                            <div class="mobile-card p-3 border-bottom animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.05 }}s;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            {{ strtoupper(substr($jadwal->murid->nama ?? '-', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $jadwal->murid->nama ?? '-' }}</h6>
                                            <small class="text-muted">{{ $jadwal->alatMusik->nama ?? '-' }}</small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill fs-6 px-2 py-1
                                        @if($jadwal->status == 'aktif' || empty($jadwal->status))
                                            bg-success
                                        @elseif($jadwal->status == 'reschedule')
                                            bg-warning text-dark
                                        @else
                                            bg-secondary
                                        @endif">
                                        @if($jadwal->status == 'aktif' || empty($jadwal->status))
                                            Aktif
                                        @elseif($jadwal->status == 'reschedule')
                                            Reschedule
                                        @else
                                            {{ ucfirst($jadwal->status ?? 'Unknown') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    <span class="text-muted">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                    @if($jadwal->jam_mulai < date('H:i'))
                                        <br><small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Sudah Dimulai</small>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-circle bg-info text-white me-2">
                                        {{ strtoupper(substr($jadwal->pengajar->nama ?? '-', 0, 1)) }}
                                    </div>
                                    <small class="text-muted">{{ $jadwal->pengajar->nama ?? '-' }}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($jadwal->status != 'reschedule')
                                    <button class="btn btn-warning btn-sm flex-fill btn-reschedule shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $jadwal->id }}"
                                        title="Reschedule">
                                        <i class="bi bi-pencil-square me-1"></i>Reschedule
                                    </button>
                                    @else
                                        <span class="text-muted small">Sudah di-reschedule</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-info-circle fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada jadwal hari ini</h5>
                                <p class="text-muted">Silakan periksa kembali tanggal atau tambahkan jadwal baru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reschedule per Jadwal (hanya jika belum reschedule) --}}
    @forelse($jadwalHariIni as $jadwal)
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
    @endforelse

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