```blade
@extends('layouts.app')

@section('title', 'Generate QR')

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

            <!-- Description -->
            <div class="alert alert-info bg-gradient border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Info:</strong> QR Code dibuat untuk satu hari. Jika belum ada, klik tombol Generate di bawah ini.
            </div>

            <!-- Generate Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <form method="POST" action="{{ route('admin.generate-qr') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-4 py-3 rounded-pill shadow-lg">
                            <i class="fas fa-magic me-2"></i>
                            Generate QR Hari Ini
                        </button>
                    </form>
                </div>
            </div>

            <!-- QR Code Display -->
            @if(isset($qrCodeImage))
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            QR Code untuk tanggal: {{ $tanggal }}
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="qr-container bg-white rounded-3 p-4 d-inline-block shadow-inner" 
                             style="width: 100%; max-width: 700px; height: auto; aspect-ratio: 1;">
                            {!! $qrCodeImage !!}
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-download me-1"></i>
                                Scan QR ini untuk absensi harian.
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .qr-container {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }
    .qr-container:hover {
        border-color: #0d6efd;
        transform: scale(1.02);
    }
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .nav-link.active {
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 8px;
        color: #0d6efd !important;
    }
</style>
@endsection
```