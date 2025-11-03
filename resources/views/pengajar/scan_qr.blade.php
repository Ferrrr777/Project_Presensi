@extends('layouts.app')

@section('title', 'Scan QR Absensi - Pengajar')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm bg-gradient-primary text-white mb-4">
        <div class="card-body d-flex align-items-center">
            <i class="fas fa-qrcode fs-1 me-3 opacity-75"></i>
            <div>
                <h3 class="mb-1">Scan QR Absensi Pengajar</h3>
                <p class="mb-0">Tekan tombol di bawah untuk mulai scan QR harian admin.</p>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    <!-- @if(session('success'))
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
    @endif -->

    <!-- Tombol Fullscreen -->
    <div class="text-center mt-5">
        <button id="openScannerBtn" class="btn btn-primary btn-lg px-4 py-3 rounded-pill shadow">
            <i class="fas fa-qrcode me-2"></i> Mulai Scan QR
        </button>
    </div>

    <!-- Overlay Fullscreen Scanner -->
    <div id="scannerOverlay" class="scanner-overlay d-none">
        <div id="fullscreenReader" class="fullscreen-reader"></div>
        <button id="closeScannerBtn" class="btn btn-danger close-btn rounded-pill shadow">
            <i class="fas fa-times me-2"></i> Tutup
        </button>
    </div>
</div>

<!-- Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
}

/* Fullscreen overlay */
.scanner-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.95);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.fullscreen-reader {
    width: 90vw;
    max-width: 600px;
    aspect-ratio: 1/1;
    border: 2px dashed #888;
    border-radius: 1rem;
    overflow: hidden;
    background: #000;
}

.close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
}

.d-none { display: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let html5QrCode = null;
    let isScanning = false;

    const openBtn = document.getElementById('openScannerBtn');
    const overlay = document.getElementById('scannerOverlay');
    const closeBtn = document.getElementById('closeScannerBtn');

    openBtn.addEventListener('click', async () => {
        overlay.classList.remove('d-none');

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("fullscreenReader");
        }

        try {
            const devices = await Html5Qrcode.getCameras();
            if (devices && devices.length) {
                const backCamera = devices.find(d =>
                    d.label.toLowerCase().includes('back') ||
                    d.label.toLowerCase().includes('environment')
                );
                const cameraId = backCamera ? backCamera.id : devices[0].id;

                await html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess,
                    onScanFailure
                );
                isScanning = true;
            } else {
                Swal.fire('Gagal', 'Tidak ada kamera ditemukan.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Tidak bisa mengakses kamera: ' + err.message, 'error');
        }
    });

    closeBtn.addEventListener('click', stopScanner);

    async function onScanSuccess(decodedText) {
    console.log('QR:', decodedText);

    const scanButton = document.getElementById('openScannerBtn');
    if (scanButton) scanButton.disabled = true;

    try {
       const response = await fetch('{{ route('pengajar.presensi.scan.store') }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'include', // penting agar session terbawa
    body: JSON.stringify({ kode_qr: decodedText })
});


        const result = await response.json();

        if (result.status === 'success') {
            Swal.fire({
                title: 'Berhasil!',
                html: `${result.message}<br>`,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                title: 'Info',
                html: `${result.message}<br>`,
                icon: 'info'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error',
            html: `Gagal menyimpan hasil scan.<br>`,
            icon: 'error'
        });
    }

    stopScanner();

    setTimeout(() => {
        if (scanButton) scanButton.disabled = false;
    }, 3000);
}
    async function stopScanner() {
        if (html5QrCode && isScanning) {
            await html5QrCode.stop();
            isScanning = false;
        }
        overlay.classList.add('d-none');
    }
});
</script>
@endsection
