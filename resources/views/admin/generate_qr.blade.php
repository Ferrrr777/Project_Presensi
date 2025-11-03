@extends('layouts.app')

@section('title', 'Generate QR Code Harian')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <div class="bg-primary text-white rounded-pill px-4 py-3 d-inline-block shadow-lg">
                    <i class="fas fa-qrcode fa-2x me-3"></i>
                    <h2 class="d-inline-block mb-0 fw-bold">Generate QR Code Harian</h2>
                </div>
            </div>

            <!-- Info Section -->
            <div class="alert alert-info bg-gradient border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Info:</strong> QR Code dibuat untuk satu hari. 
                Jika belum ada, klik tombol <b>Generate</b> di bawah ini.
            </div>

            <!-- Generate Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                   <form method="POST" action="{{ secure_url(route('admin.generate-qr')) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg">
                            <i class="fas fa-magic me-2"></i> Generate QR Hari Ini
                        </button>
                    </form>
                </div>
            </div>

            <!-- QR Code Display -->
            @if(isset($qrCodeImage))
            <div class="card border-0 shadow-lg mt-4">
                <div class="card-header bg-success text-white text-center py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-calendar-day me-2"></i>
                        QR Code untuk tanggal: {{ $tanggal }}
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="qr-container bg-white rounded-4 shadow-inner mx-auto">
                        {!! $qrCodeImage !!}
                    </div>
                    <div class="mt-3">
                        <small class="text-muted d-block">
                            <i class="fas fa-qrcode me-1"></i>
                            Scan QR ini untuk absensi harian.
                        </small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Styling -->
<style>
    /* Container QR agar proporsional dan responsif */
    .qr-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 400px;
        aspect-ratio: 1 / 1;
        margin: 0 auto;
        border: 2px dashed #dee2e6;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    /* Hover efek */
    .qr-container:hover {
        border-color: #0d6efd;
        transform: scale(1.03);
    }

    /* Pastikan SVG QR menyesuaikan */
    .qr-container svg {
        width: 100%;
        height: auto;
    }

    /* Gradien info box */
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Nav aktif */
    .nav-link.active {
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 8px;
        color: #0d6efd !important;
    }

    /* Responsif tambahan */
    @media (max-width: 576px) {
        .qr-container {
            max-width: 300px;
            padding: 1rem;
        }

        .btn-lg {
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
        }
    }
</style>
@endsection
