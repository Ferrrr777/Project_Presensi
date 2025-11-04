@extends('layouts.app')

@section('title', 'Dashboard Pengajar')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chalkboard-teacher fs-1 me-3 opacity-75"></i>
                        <div>
                            <h3 class="mb-1">Selamat Datang, Pengajar!</h3>
                            <p class="mb-0">Kelola absensi siswa, materi pelajaran, dan jadwal Anda dengan mudah. Pilih opsi di bawah untuk memulai.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Dashboard Overview -->
        <div class="col-12 col-md-4">
            <a href="{{ route('pengajar.dashboard') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card rounded-3 overflow-hidden">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-tachometer-alt fs-1 text-primary mb-3"></i>
                        <h5 class="card-title mb-2">Dashboard Utama</h5>
                        <p class="card-text text-muted mb-0">Lihat ringkasan aktivitas harian, jadwal, dan statistik siswa Anda.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 3: Scan QR -->
        <div class="col-12 col-md-4">
            <a href="{{ route('pengajar.scan-qr-form') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card rounded-3 overflow-hidden">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-qrcode fs-1 text-info mb-3"></i>
                        <h5 class="card-title mb-2">Scan QR Absensi</h5>
                        <p class="card-text text-muted mb-0">Scan kode QR siswa untuk mencatat kehadiran dengan cepat.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Additional Stats Section (Opsional - Bisa diisi dengan data real nanti) -->
    <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-users fs-2 text-primary mb-2"></i>
                    <h4 class="mb-1">25</h4>
                    <p class="text-muted mb-0">Siswa Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-calendar-check fs-2 text-success mb-2"></i>
                    <h4 class="mb-1">8</h4>
                    <p class="text-muted mb-0">Jadwal Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-check-circle fs-2 text-info mb-2"></i>
                    <h4 class="mb-1">95%</h4>
                    <p class="text-muted mb-0">Tingkat Absensi</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-clock fs-2 text-warning mb-2"></i>
                    <h4 class="mb-1">14</h4>
                    <p class="text-muted mb-0">Jam Mengajar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles untuk membuat lebih menarik di mobile */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* Responsif untuk mobile: Pastikan cards stack dengan baik */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem !important; /* Kurangi padding di mobile untuk ruang lebih efisien */
        }

        h3, h5 {
            font-size: 1.25rem !important; /* Ukuran font lebih kecil di mobile */
        }

        .card-text {
            font-size: 0.9rem;
        }

        .fs-1 {
            font-size: 2rem !important; /* Icon lebih kecil di mobile */
        }

        .fs-2 {
            font-size: 1.5rem !important;
        }
    }

    /* Animasi fade-in untuk cards */
    .card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
    .card:nth-child(4) { animation-delay: 0.4s; }
    .card:nth-child(5) { animation-delay: 0.5s; }
    .card:nth-child(6) { animation-delay: 0.6s; }
    .card:nth-child(7) { animation-delay: 0.7s; }
    .card:nth-child(8) { animation-delay: 0.8s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection