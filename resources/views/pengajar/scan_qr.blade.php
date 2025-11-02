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
.scanner-overlay {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.95); z-index: 9999;
    display: flex; flex-direction: column; justify-content: center; align-items: center;
}
.fullscreen-reader {
    width: 90vw; max-width: 600px; aspect-ratio: 1/1;
    border: 2px dashed #888; border-radius: 1rem; overflow: hidden; background: #000;
}
.close-btn {
    position: absolute; top: 20px; right: 20px;
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

    // Buka scanner
    openBtn.addEventListener('click', async () => {
        overlay.classList.remove('d-none');

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("fullscreenReader");
        }

        try {
            const devices = await Html5Qrcode.getCameras();
            if (!devices || !devices.length) {
                Swal.fire('Gagal', 'Tidak ada kamera ditemukan.', 'error');
                return;
            }

            const backCamera = devices.find(d =>
                d.label.toLowerCase().includes('back') ||
                d.label.toLowerCase().includes('environment')
            );
            const cameraId = backCamera ? backCamera.id : devices[0].id;

            await html5QrCode.start(
                cameraId,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            );
            isScanning = true;

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Tidak bisa mengakses kamera.', 'error');
        }
    });

    // Tutup scanner
    closeBtn.addEventListener('click', stopScanner);

    async function onScanSuccess(decodedText) {
        console.log('QR Ditemukan:', decodedText);

        const scanButton = document.getElementById('openScannerBtn');
        if (scanButton) scanButton.disabled = true;

        try {
            const response = await fetch('{{ route('pengajar.presensi.scan.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin', // ✅ wajib untuk session cookie
                body: JSON.stringify({ kode_qr: decodedText })
            });

            // Debug raw response
            const raw = await response.text();
            console.log('RAW RESPONSE:', raw);

            let result;
            try {
                result = JSON.parse(raw);
            } catch(e) {
                Swal.fire('Error', 'Response server tidak valid. Cek console.', 'error');
                stopScanner();
                return;
            }

            if (result.status === 'success') {
                Swal.fire({ title: 'Berhasil!', text: result.message, icon: 'success', timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire('Info', result.message, 'info');
            }

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Gagal menyimpan hasil scan.', 'error');
        }

        stopScanner();

        setTimeout(() => { if (scanButton) scanButton.disabled = false; }, 3000);
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
@extends('layouts.app')