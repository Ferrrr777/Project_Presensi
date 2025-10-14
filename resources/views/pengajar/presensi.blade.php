@extends('layouts.app')

@section('title', 'Presensi Siswa - Pengajar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        Presensi Siswa
                    </h3>
                    <p class="text-muted mb-0">Catat kehadiran siswa untuk jadwal hari ini. Pilih status hadir atau tidak hadir untuk setiap siswa.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="resetAllStatus()">
                        <i class="fas fa-refresh me-1"></i>Reset Semua
                    </button>
                    <button class="btn btn-success btn-sm rounded-pill" onclick="savePresensi()">
                        <i class="fas fa-save me-1"></i>Simpan Presensi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Presensi Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:8%;">No</th>
                                    <th style="width:30%;">Nama Siswa</th>
                                    <th style="width:20%;">Alat Musik</th>
                                    <th style="width:15%;">Jam Mulai</th>
                                    <th style="width:12%;">Status</th>
                                    <th style="width:15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Data dummy untuk demo (ganti dengan data real dari controller nanti, misal $siswa)
                                    $siswaList = collect([
                                        [
                                            'id' => 1,
                                            'nama' => 'Ahmad Rizki',
                                            'alat_musik' => 'Piano',
                                            'jam_mulai' => '09:00',
                                            'status' => 'Belum'
                                        ],
                                        [
                                            'id' => 2,
                                            'nama' => 'Siti Nurhaliza',
                                            'alat_musik' => 'Gitar',
                                            'jam_mulai' => '10:00',
                                            'status' => 'Belum'
                                        ],
                                        [
                                            'id' => 3,
                                            'nama' => 'Budi Santoso',
                                            'alat_musik' => 'Vokal',
                                            'jam_mulai' => '11:00',
                                            'status' => 'Belum'
                                        ],
                                        [
                                            'id' => 4,
                                            'nama' => 'Dewi Sartika',
                                            'alat_musik' => 'Biola',
                                            'jam_mulai' => '13:00',
                                            'status' => 'Belum'
                                        ],
                                        [
                                            'id' => 5,
                                            'nama' => 'Eko Prasetyo',
                                            'alat_musik' => 'Drum',
                                            'jam_mulai' => '14:00',
                                            'status' => 'Belum'
                                        ],
                                        [
                                            'id' => 6,
                                            'nama' => 'Fina Melinda',
                                            'alat_musik' => 'Piano',
                                            'jam_mulai' => '15:00',
                                            'status' => 'Belum'
                                        ],
                                    ]);
                                @endphp

                                @foreach($siswaList as $index => $siswa)
                                    <tr class="presensi-row" data-siswa-id="{{ $siswa['id'] }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-muted me-2 fs-5"></i>
                                                {{ $siswa['nama'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark px-2 py-1">
                                                <i class="fas fa-music me-1"></i>
                                                {{ $siswa['alat_musik'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $siswa['jam_mulai'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge status px-3 py-2 fw-semibold" style="min-width:100px;"
                                                @if($siswa['status'] == 'Hadir') class="bg-success" @elseif($siswa['status'] == 'Tidak Hadir') class="bg-danger" @else class="bg-secondary" @endif>
                                                {{ $siswa['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn btn-success btn-sm btn-hadir rounded-pill px-3 py-1" 
                                                        @if($siswa['status'] == 'Hadir') disabled style="opacity: 0.6;" @endif>
                                                    <i class="fas fa-check me-1"></i> Hadir
                                                </button>
                                                <button class="btn btn-danger btn-sm btn-absen rounded-pill px-3 py-1" 
                                                        @if($siswa['status'] == 'Tidak Hadir') disabled style="opacity: 0.6;" @endif>
                                                    <i class="fas fa-times me-1"></i> Absen
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Total Siswa: {{ $siswaList->count() }} | Hadir: <span id="hadir-count">0</span> | Absen: <span id="absen-count">0</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State (Jika tidak ada siswa - opsional) -->
    @if($siswaList->isEmpty())
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-8 text-center py-5">
                <i class="fas fa-users-slash fs-1 text-muted mb-4 opacity-50"></i>
                <h4 class="text-muted mb-3">Tidak Ada Siswa Hari Ini</h4>
                <p class="text-muted mb-4">Jadwal presensi kosong. Periksa jadwal atau tambah siswa baru.</p>
            </div>
        </div>
    @endif
</div>

<style>
    /* Custom styles untuk membuat lebih menarik di mobile */
    .table th, .table td {
        vertical-align: middle;
        border-color: #e9ecef;
    }

    .status {
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-hadir, .btn-absen {
        transition: all 0.2s ease;
        min-width: 80px;
    }

    .btn-hadir:hover:not(:disabled), .btn-absen:hover:not(:disabled) {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .presensi-row {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(10px);
    }

    .presensi-row:nth-child(1) { animation-delay: 0.1s; }
    .presensi-row:nth-child(2) { animation-delay: 0.2s; }
    .presensi-row:nth-child(3) { animation-delay: 0.3s; }
    .presensi-row:nth-child(4) { animation-delay: 0.4s; }
    .presensi-row:nth-child(5) { animation-delay: 0.5s; }
    .presensi-row:nth-child(6) { animation-delay: 0.6s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsif untuk mobile: Table horizontal scroll, tombol stack vertikal */
    @media (max-width: 768px) {
        .table-responsive {
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .table th, .table td {
            font-size: 0.9rem;
            padding: 0.75rem 0.5rem;
        }

        .btn-hadir, .btn-absen {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            min-width: 70px;
        }

        /* Stack tombol aksi di mobile */
        .d-flex.gap-1 {
            flex-direction: column;
            gap: 0.25rem;
        }

        .btn-hadir, .btn-absen {
            width: 100%;
        }

        h3 {
            font-size: 1.25rem !important;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .d-flex.gap-2 {
            flex-direction: column;
            width: 100%;
        }

        .btn-sm {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .table th, .table td {
            font-size: 0.85rem;
            padding: 0.5rem 0.25rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }

        .status {
            min-width: 80px;
            font-size: 0.8rem;
        }
    }
</style>

<script>
    // Update status hadir/absen
    document.querySelectorAll('.btn-hadir').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            let row = this.closest('tr');
            let status = row.querySelector('.status');
            let absenBtn = row.querySelector('.btn-absen');
            
            status.className = 'badge bg-success status px-3 py-2 fw-semibold';
            status.innerText = 'Hadir';
            this.disabled = true;
            this.style.opacity = '0.6';
            absenBtn.disabled = false;
            absenBtn.style.opacity = '1';
            
            updateCounts();
        });
    });

    document.querySelectorAll('.btn-absen').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            let row = this.closest('tr');
            let status = row.querySelector('.status');
            let hadirBtn = row.querySelector('.btn-hadir');
            
            status.className = 'badge bg-danger status px-3 py-2 fw-semibold';
            status.innerText = 'Tidak Hadir';
            this.disabled = true;
            this.style.opacity = '0.6';
            hadirBtn.disabled = false;
            hadirBtn.style.opacity = '1';
            
            updateCounts();
        });
    });

    // Update counter hadir/absen
    function updateCounts() {
        let hadir = document.querySelectorAll('.bg-success.status').length;
        let absen = document.querySelectorAll('.bg-danger.status').length;
        let belum = document.querySelectorAll('.bg-secondary.status').length;
        
        document.getElementById('hadir-count').textContent = hadir;
        document.getElementById('absen-count').textContent = absen;
        
        // Update footer text jika perlu
        if (belum === 0) {
            Swal.fire({
                title: 'Presensi Lengkap!',
                text: 'Semua siswa telah dicatat.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    }

    // Reset semua status
    function resetAllStatus() {
        Swal.fire({
            title: 'Reset Presensi?',
            text: 'Semua status akan kembali ke "Belum".',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.status').forEach(status => {
                    status.className = 'badge bg-secondary status px-3 py-2 fw-semibold';
                    status.innerText = 'Belum';
                });
                document.querySelectorAll('.btn-hadir, .btn-absen').forEach(btn => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });
                updateCounts();
            }
        });
    }

    // Simpan presensi (demo - ganti dengan AJAX atau form submit)
    function savePresensi() {
        let data = [];
        document.querySelectorAll('.presensi-row').forEach(row => {
            let id = row.dataset.siswaId;
            let status = row.querySelector('.status').innerText;
            data.push({ id: id, status: status });
        });

        // Demo: Tampilkan data yang akan disimpan
        Swal.fire({
            title: 'Simpan Presensi',
            html: `<pre>${JSON.stringify(data, null, 2)}</pre>`,
            icon: 'info',
            confirmButtonText: 'OK'
        }).then(() => {
            // Ganti dengan: fetch('/pengajar/presensi/save', { method: 'POST', body: JSON.stringify(data) })
            // Atau submit form hidden
            Swal.fire('Disimpan!', 'Presensi telah tersimpan.', 'success');
        });
    }

    // Inisialisasi counts
    updateCounts();
</script>
@endsection