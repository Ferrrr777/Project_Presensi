@extends('layouts.app')

@section('title', 'Scan QR Absensi - Pengajar')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white rounded-3 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-qrcode fs-1 me-3 opacity-75"></i>
                        <div>
                            <h3 class="mb-1">Scan QR Absensi Pengajar</h3>
                            <p class="mb-0">Admin generate QR harian untuk absensi pengajar. Scan QR ini untuk mencatat kehadiran diri Anda secara otomatis. Data absensi akan tercatat di sistem admin.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Debug Info (Hapus setelah test) -->
            <div class="alert alert-info rounded-3">
                <small>
                    <i class="fas fa-bug me-1"></i>
                    <strong>Debug:</strong> Buka F12 > Console untuk lihat error. Pastikan HTTPS/localhost dan allow camera permissions.
                </small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Camera Scanner Section (Full Width) -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-light rounded-top-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-camera text-secondary me-2"></i>
                        Scan QR Harian Admin dengan Kamera
                    </h5>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill me-2" id="startScanner" onclick="startQrScanner()">
                            <i class="fas fa-play me-1"></i> Mulai Scan
                        </button>
                        <button class="btn btn-outline-danger btn-sm rounded-pill" id="stopScanner" onclick="stopQrScanner()" style="display: none;">
                            <i class="fas fa-stop me-1"></i> Stop Scan
                        </button>
                    </div>
                </div>
                <div class="card-body p-4 text-center">
                    <div id="qr-reader" class="qr-scanner-container" style="width: 100%; max-width: 100%; height: 300px; border: 2px dashed #dee2e6; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                        <div class="text-muted">
                            <i class="fas fa-qrcode fs-1 mb-3 opacity-50"></i>
                            <p class="mb-0">Arahkan kamera ke QR code harian dari admin</p>
                            <small class="text-muted">Scan untuk absen diri sendiri. Data kehadiran akan tercatat di sistem admin.</small>
                        </div>
                    </div>
                    <div id="qr-result" class="mt-3"></div>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Scanner akan otomatis mendeteksi QR harian, verifikasi, dan catat absensi pengajar. Admin dapat melihat data kehadiran semua pengajar.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Scan History (Dummy - Riwayat Absensi Pengajar Lain untuk Demo) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history text-secondary me-2"></i>
                        Riwayat Absensi Pengajar Hari Ini (Demo)
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        @php
                            // Data dummy untuk riwayat absensi pengajar
                            $absensiHistory = collect([
                                ['nama' => 'Budi Santoso', 'kelas' => 'Piano', 'status' => 'Hadir', 'waktu' => now()->subMinutes(5)->format('H:i')],
                                ['nama' => 'Siti Nurhaliza', 'kelas' => 'Gitar', 'status' => 'Hadir', 'waktu' => now()->subMinutes(10)->format('H:i')],
                                ['nama' => 'Eko Prasetyo', 'kelas' => 'Vokal', 'status' => 'Hadir', 'waktu' => now()->subMinutes(15)->format('H:i')],
                            ]);
                        @endphp
                        @forelse($absensiHistory as $absen)
                            <div class="list-group-item px-0 border-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher text-primary me-2 fs-5"></i>
                                        <div>
                                            <strong>{{ $absen['nama'] }}</strong> - {{ $absen['kelas'] }}
                                            <br><small class="text-muted">{{ $absen['status'] }} • {{ $absen['waktu'] }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success rounded-pill">{{ $absen['status'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-clock fs-3 mb-2 opacity-50"></i>
                                <p>Belum ada absensi pengajar hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML5 QR Code Library (Coba versi full jika minified error) -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
    /* Custom styles untuk membuat lebih menarik di mobile - sama seperti sebelumnya */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .qr-scanner-container {
        transition: all 0.3s ease;
        position: relative;
    }

    .qr-scanner-container video {
        border-radius: 0.75rem !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsif untuk mobile: Scanner full-width, sections stack */
    @media (max-width: 768px) {
        .qr-scanner-container {
            height: 250px !important;
            margin: 0 auto;
        }

        #qr-reader {
            max-width: 100% !important;
        }

        .card-body {
            padding: 1.25rem !important;
        }

        h3, h5, h6 {
            font-size: 1.2rem !important;
        }

        .btn {
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
        }

        .row.g-4 > div {
            margin-bottom: 1rem;
        }

        /* Full screen scanner di mobile portrait */
        .qr-scanner-container {
            border: 1px solid #dee2e6;
        }

        .list-group-item {
            padding: 0.75rem 0;
        }
    }

    @media (max-width: 576px) {
        .qr-scanner-container {
            height: 200px !important;
        }

        .alert {
            font-size: 0.9rem;
        }
    }

    /* Scanner overlay untuk panduan */
    .qr-scanner-container::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 200px;
        border: 2px solid #4f46e5;
        border-radius: 0.5rem;
        pointer-events: none;
        display: none;
    }

    .qr-scanner-container.scanning::after {
        display: block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { box-shadow: 0 0 0 20px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
</style>

<script>
    let html5QrcodeScanner = null;
    let isScanning = false;

    // Fungsi untuk tampilkan error dengan console log
    function showError(message, err = null) {
        console.error('QR Scanner Error:', message, err); // Debug log
        const resultDiv = document.getElementById('qr-result');
        resultDiv.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> ${message}
                <br><small class="text-muted">Cek console (F12) untuk detail. Pastikan allow camera dan HTTPS.</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    // Inisialisasi scanner dengan debug
    function initScanner() {
        console.log('Initializing QR Scanner...'); // Debug
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        // Cek apakah library loaded
        if (typeof Html5Qrcode === 'undefined') {
            showError('Library Html5Qrcode tidak loaded. Cek koneksi internet atau CDN.');
            return;
        }

        Html5Qrcode.getCameras().then(devices => {
            console.log('Available cameras:', devices); // Debug: Lihat daftar kamera
            if (devices && devices.length > 0) {
                // Pilih kamera belakang jika tersedia (untuk mobile), atau kamera pertama
                const cameraId = devices.find(device => device.facingMode && device.facingMode === 'environment')?.id || devices[0].id;
                console.log('Selected camera ID:', cameraId); // Debug

                html5QrcodeScanner.start(
                    cameraId,
                    config,
                    onScanSuccess
                ).then(() => {
                    console.log('Scanner started successfully!'); // Debug
                    isScanning = true;
                    document.getElementById('startScanner').style.display = 'none';
                    document.getElementById('stopScanner').style.display = 'inline-block';
                    document.querySelector('.qr-scanner-container').classList.add('scanning');
                    document.getElementById('qr-result').innerHTML = '<div class="alert alert-info">Scanner aktif. Arahkan ke QR code harian admin...</div>';
                }).catch(err => {
                    console.error('Start scanner error:', err); // Debug
                    showError('Gagal memulai scanner. Error: ' + (err.message || err), err);
                });
            } else {
                console.warn('No cameras found'); // Debug
                showError('Tidak ada kamera yang terdeteksi. Cek hardware atau permissions.');
            }
        }).catch(err => {
            console.error('Get cameras error:', err); // Debug
            showError('Tidak dapat mengakses kamera. Error: ' + (err.message || err), err);
            // Common errors: NotAllowedError (permissions), NotFoundError (no camera)
        });
    }

    // Fungsi scan success (sama seperti sebelumnya)
    function onScanSuccess(decodedText, decodedResult) {
        console.log('QR scanned successfully:', decodedText); // Debug
        const resultDiv = document.getElementById('qr-result');
        resultDiv.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                QR Harian terbaca: <strong>${decodedText}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        stopQrScanner();

        setTimeout(() => {
            Swal.fire({
                title: 'Absensi Pengajar Berhasil!',
                text: `Kehadiran Anda (${{ Auth::user()->name ?? 'Pengajar' }}) tercatat melalui QR harian. Admin dapat melihat data ini.`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }, 500);
    }

    // Mulai scanner
    function startQrScanner() {
        console.log('Start scanner button clicked'); // Debug
        if (!isScanning) {
            initScanner();
        } else {
            console.log('Scanner already running'); // Debug
        }
    }

    // Stop scanner (lengkap)
    function stopQrScanner() {
        console.log('Stop scanner called'); // Debug
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.stop().then(() => {
                console.log('Scanner stopped successfully'); // Debug
                isScanning = false;
                document.getElementById('startScanner').style.display = 'inline-block';
                document.getElementById('stopScanner').style.display = 'none';
                document.querySelector('.qr-scanner-container').classList.remove('scanning');
                document.getElementById('qr-result').innerHTML = '';
            }).catch(err => {
                console.error('Error stopping scanner:', err);
                showError('Gagal stop scanner: ' + (err.message || err), err);
            });
        } else {
            console.log('Scanner not running or not initialized');
            // Reset UI jika tidak scanning
            document.getElementById('startScanner').style.display = 'inline-block';
            document.getElementById('stopScanner').style.display = 'none';
            document.querySelector('.qr-scanner-container').classList.remove('scanning');
            document.getElementById('qr-result').innerHTML = '';
        }
    }

    // Inisialisasi saat DOM loaded (opsional, untuk auto-check library)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, QR Scanner ready.'); // Debug
        // Cek library loaded
        if (typeof Html5Qrcode === 'undefined') {
            console.error('Html5Qrcode library not loaded!');
            showError('Library QR Scanner gagal load. Refresh halaman atau cek internet.');
        }
    });
</script>
@endsection