@extends('layouts.app')

@section('title', 'Laporan Mengajar - Pengajar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-file-invoice text-primary me-2"></i>
                        Laporan Mengajar
                    </h3>
                    <p class="text-muted mb-0">Lihat statistik mengajar Anda, kehadiran murid, dan unduh laporan untuk administrasi atau gaji.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pengajar.laporan.pdf') }}" class="btn btn-outline-danger rounded-pill shadow-sm">
                        <i class="fas fa-file-pdf me-2"></i>Unduh PDF
                    </a>
                    <a href="{{ route('pengajar.laporan.excel') }}" class="btn btn-outline-success rounded-pill shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Unduh Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <!-- Jam Mengajar Per Bulan -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-clock fs-2 text-warning mb-3"></i>
                    <h4 class="mb-1" id="jam-mengajar">45</h4>
                    <p class="text-muted mb-2">Jam Mengajar Bulan Ini</p>
                    <small class="text-success fw-bold">+5 jam dari bulan lalu</small>
                </div>
            </div>
        </div>

        <!-- Jumlah Murid -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-users fs-2 text-primary mb-3"></i>
                    <h4 class="mb-1" id="jumlah-murid">25</h4>
                    <p class="text-muted mb-2">Murid Aktif</p>
                    <small class="text-info fw-bold">12 murid baru</small>
                </div>
            </div>
        </div>

        <!-- Tingkat Kehadiran Rata-rata -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-chart-line fs-2 text-success mb-3"></i>
                    <h4 class="mb-1" id="kehadiran-rata">92%</h4>
                    <p class="text-muted mb-2">Kehadiran Rata-rata Murid</p>
                    <small class="text-warning fw-bold">Naik 3% dari bulan lalu</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Jam Mengajar Per Bulan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-secondary me-2"></i>
                        Jam Mengajar Per Bulan (6 Bulan Terakhir)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="jamChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan Kehadiran Per Murid -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-table text-secondary me-2"></i>
                        Laporan Kehadiran Per Murid (Bulan Ini)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:5%;">No</th>
                                    <th style="width:25%;">Nama Murid</th>
                                    <th style="width:15%;">Alat Musik</th>
                                    <th style="width:15%;">Jumlah Sesi</th>
                                    <th style="width:15%;">Hadir</th>
                                    <th style="width:15%;">Absen</th>
                                    <th style="width:10%;">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Data dummy untuk laporan kehadiran (ganti dengan data real dari controller)
                                    $kehadiranMurid = collect([
                                        [
                                            'nama' => 'Ahmad Rizki',
                                            'alat' => 'Piano',
                                            'sesi' => 12,
                                            'hadir' => 11,
                                            'absen' => 1,
                                            'persentase' => 92
                                        ],
                                        [
                                            'nama' => 'Siti Nurhaliza',
                                            'alat' => 'Gitar',
                                            'sesi' => 12,
                                            'hadir' => 10,
                                            'absen' => 2,
                                            'persentase' => 83
                                        ],
                                        [
                                            'nama' => 'Budi Santoso',
                                            'alat' => 'Vokal',
                                            'sesi' => 12,
                                            'hadir' => 12,
                                            'absen' => 0,
                                            'persentase' => 100
                                        ],
                                        [
                                            'nama' => 'Dewi Sartika',
                                            'alat' => 'Biola',
                                            'sesi' => 12,
                                            'hadir' => 9,
                                            'absen' => 3,
                                            'persentase' => 75
                                        ],
                                        [
                                            'nama' => 'Eko Prasetyo',
                                            'alat' => 'Drum',
                                            'sesi' => 12,
                                            'hadir' => 11,
                                            'absen' => 1,
                                            'persentase' => 92
                                        ],
                                    ]);
                                @endphp

                                @foreach($kehadiranMurid as $index => $murid)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-muted me-2 fs-5"></i>
                                                {{ $murid['nama'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark px-2 py-1">
                                                <i class="fas fa-music me-1"></i>
                                                {{ $murid['alat'] }}
                                            </span>
                                        </td>
                                        <td><span class="fw-bold">{{ $murid['sesi'] }}</span></td>
                                        <td>
                                            <span class="badge bg-success px-2 py-1">{{ $murid['hadir'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger px-2 py-1">{{ $murid['absen'] }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-{{ $murid['persentase'] >= 90 ? 'success' : ($murid['persentase'] >= 80 ? 'warning' : 'danger') }}">
                                                {{ $murid['persentase'] }}%
                                            </span>
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
                        Total Murid: {{ $kehadiranMurid->count() }} | Rata-rata Kehadiran: <span id="rata-kehadiran">92%</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Custom styles untuk membuat lebih menarik di mobile */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .table th, .table td {
        vertical-align: middle;
        border-color: #e9ecef;
    }

    .badge {
        font-weight: 500;
    }

    /* Animasi fade-in untuk cards dan rows */
    .card, .table tr {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }

    .table tr:nth-child(1) { animation-delay: 0.4s; }
    .table tr:nth-child(2) { animation-delay: 0.5s; }
    .table tr:nth-child(3) { animation-delay: 0.6s; }
    .table tr:nth-child(4) { animation-delay: 0.7s; }
    .table tr:nth-child(5) { animation-delay: 0.8s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsif untuk mobile: Cards stack, table scroll */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem !important;
        }

        h3, h5 {
            font-size: 1.2rem !important;
        }

        .table-responsive {
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .table th, .table td {
            font-size: 0.9rem;
            padding: 0.75rem 0.5rem;
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

        .btn {
            width: 100%;
        }

        #jamChart {
            max-height: 250px !important;
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

        h4 {
            font-size: 1.5rem !important;
        }
    }
</style>

<script>
    // Inisialisasi Chart.js untuk jam mengajar per bulan
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('jamChart').getContext('2d');
        const jamChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Jam Mengajar',
                    data: [35, 40, 42, 38, 45, 50], // Data dummy - ganti dengan real
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 10 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Hitung rata-rata kehadiran dari data dummy (ganti dengan real calculation)
        const rataKehadiran = 92; // Misal dari average persentase
        document.getElementById('kehadiran-rata').textContent = rataKehadiran + '%';
        document.getElementById('rata-kehadiran').textContent = rataKehadiran + '%';
    });

    // Download confirmation (opsional - untuk UX)
    document.querySelectorAll('a[href*="pdf"], a[href*="excel"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Uncomment jika ingin konfirmasi
            // if (!confirm('Unduh laporan?')) e.preventDefault();
        });
    });
</script>
@endsection