@extends('layouts.app')

@section('title', 'Presensi Hari Ini')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h1 class="mb-3 mb-md-0 fw-bold text-primary animate__animated animate__fadeInDown">
                    <i class="bi bi-calendar-check me-2"></i>Presensi Hari Ini
                </h1>
                <div class="badge bg-info text-white fs-6 px-3 py-2 animate__animated animate__fadeInRight">
                    <i class="bi bi-calendar-event me-1"></i>{{ $tanggal }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeInUp">
                <div class="card-header bg-gradient-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-list-check me-2"></i>Daftar Jadwal Presensi
                    </h4>
                    <small class="text-white-50">Kelola kehadiran murid dengan mudah</small>
                </div>
                <div class="card-body p-0">
                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="presensiTable">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th class="border-0 fw-bold">#</th>
                                        <th class="border-0 fw-bold">Nama Murid</th>
                                        <th class="border-0 fw-bold">Alat Musik</th>
                                        <th class="border-0 fw-bold">Waktu</th>
                                        <th class="border-0 fw-bold">Status</th>
                                        <th class="border-0 fw-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwals as $index => $jadwal)
                                        @php
                                            $alat_id = $jadwal->alatMusik->id ?? $jadwal->alat_id;
                                            $key = $jadwal->murid_id.'-'.$alat_id;
                                            $presensi = $presensis[$key] ?? null;

                                            // Format jam hanya H:i
                                            $jamMulai = $jadwal->jam_mulai_baru ?? $jadwal->jam_mulai;
                                            $jamSelesai = $jadwal->jam_selesai_baru ?? $jadwal->jam_selesai;
                                            $jamMulaiFormatted = \Carbon\Carbon::parse($jamMulai)->format('H:i');
                                            $jamSelesaiFormatted = \Carbon\Carbon::parse($jamSelesai)->format('H:i');
                                        @endphp
                                        <tr class="animate__animated animate__fadeInLeft" style="animation-delay: {{ $index * 0.05 }}s;">
                                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                        {{ strtoupper(substr($jadwal->murid->nama, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $jadwal->murid->nama }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $jadwal->alatMusik->nama ?? 'Tidak ada alat' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <i class="bi bi-clock me-1 text-muted"></i>
                                                {{ $jamMulaiFormatted }} - {{ $jamSelesaiFormatted }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill fs-6 px-3 py-2 
                                                    @if($presensi)
                                                        {{ $presensi->status === 'hadir' ? 'bg-success' : 'bg-danger' }}
                                                    @else
                                                        bg-secondary
                                                    @endif"
                                                    id="status-{{ $jadwal->murid_id }}-{{ $alat_id }}">
                                                    {{ $presensi ? ucfirst(str_replace('_',' ',$presensi->status)) : 'Belum diisi' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Presensi">
                                                    <button class="btn btn-success btn-hadir shadow-sm position-relative"
                                                        data-id="{{ $jadwal->murid_id }}"
                                                        data-alat="{{ $alat_id }}"
                                                        @if($presensi && $presensi->status === 'hadir') disabled @endif
                                                        aria-label="Tandai Hadir">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        <span class="d-none d-sm-inline">Hadir</span>
                                                    </button>
                                                    <button class="btn btn-danger btn-tidak shadow-sm position-relative"
                                                        data-id="{{ $jadwal->murid_id }}"
                                                        data-alat="{{ $alat_id }}"
                                                        @if($presensi && $presensi->status === 'tidak_hadir') disabled @endif
                                                        aria-label="Tandai Tidak Hadir">
                                                        <i class="bi bi-x-circle-fill me-1"></i>
                                                        <span class="d-none d-sm-inline">Tidak Hadir</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="bi bi-info-circle fs-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada jadwal presensi hari ini</h5>
                                                <p class="text-muted">Silakan periksa kembali tanggal atau hubungi admin.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        @forelse ($jadwals as $index => $jadwal)
                            @php
                                $alat_id = $jadwal->alatMusik->id ?? $jadwal->alat_id;
                                $key = $jadwal->murid_id.'-'.$alat_id;
                                $presensi = $presensis[$key] ?? null;

                                // Format jam hanya H:i
                                $jamMulai = $jadwal->jam_mulai_baru ?? $jadwal->jam_mulai;
                                $jamSelesai = $jadwal->jam_selesai_baru ?? $jadwal->jam_selesai;
                                $jamMulaiFormatted = \Carbon\Carbon::parse($jamMulai)->format('H:i');
                                $jamSelesaiFormatted = \Carbon\Carbon::parse($jamSelesai)->format('H:i');
                            @endphp
                            <div class="mobile-card p-3 border-bottom animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.05 }}s;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            {{ strtoupper(substr($jadwal->murid->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $jadwal->murid->nama }}</h6>
                                            <small class="text-muted">{{ $jadwal->alatMusik->nama ?? 'Tidak ada alat' }}</small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill fs-6 px-2 py-1 
                                        @if($presensi)
                                            {{ $presensi->status === 'hadir' ? 'bg-success' : 'bg-danger' }}
                                        @else
                                            bg-secondary
                                        @endif"
                                        id="status-mobile-{{ $jadwal->murid_id }}-{{ $alat_id }}">
                                        {{ $presensi ? ucfirst(str_replace('_',' ',$presensi->status)) : 'Belum diisi' }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    <span class="text-muted">{{ $jamMulaiFormatted }} - {{ $jamSelesaiFormatted }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm flex-fill btn-hadir shadow-sm"
                                        data-id="{{ $jadwal->murid_id }}"
                                        data-alat="{{ $alat_id }}"
                                        @if($presensi && $presensi->status === 'hadir') disabled @endif>
                                        <i class="bi bi-check-circle-fill me-1"></i>Hadir
                                    </button>
                                    <button class="btn btn-danger btn-sm flex-fill btn-tidak shadow-sm"
                                        data-id="{{ $jadwal->murid_id }}"
                                        data-alat="{{ $alat_id }}"
                                        @if($presensi && $presensi->status === 'tidak_hadir') disabled @endif>
                                        <i class="bi bi-x-circle-fill me-1"></i>Tidak Hadir
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-info-circle fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada jadwal presensi hari ini</h5>
                                <p class="text-muted">Silakan periksa kembali tanggal atau hubungi admin.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center" style="z-index: 1050;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn-hadir, .btn-tidak');
    const loadingOverlay = document.getElementById('loadingOverlay');

    buttons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const murid_id = e.target.closest('button').dataset.id;
            const alat_id = e.target.closest('button').dataset.alat;
            const status = btn.classList.contains('btn-hadir') ? 'hadir' : 'tidak_hadir';

            // Disable buttons and show loading
            const rowButtons = e.target.closest('.btn-group, .d-flex').querySelectorAll('button');
            rowButtons.forEach(b => b.disabled = true);
            loadingOverlay.classList.remove('d-none');

            // Add loading spinner to clicked button
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Loading...';

            try {
                const response = await fetch("{{ route('pengajar.presensi.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        murid_id,
                        alat_id,
                        status
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Update badge status (both desktop and mobile)
                    const badgeDesktop = document.getElementById(`status-${murid_id}-${alat_id}`);
                    const badgeMobile = document.getElementById(`status-mobile-${murid_id}-${alat_id}`);
                    const displayStatus = status === 'hadir' ? 'Hadir' : 'Tidak Hadir';
                    
                    if (badgeDesktop) {
                        badgeDesktop.textContent = displayStatus;
                        badgeDesktop.className = `badge rounded-pill fs-6 px-3 py-2 ${status === 'hadir' ? 'bg-success' : 'bg-danger'}`;
                    }
                    if (badgeMobile) {
                        badgeMobile.textContent = displayStatus;
                        badgeMobile.className = `badge rounded-pill fs-6 px-2 py-1 ${status === 'hadir' ? 'bg-success' : 'bg-danger'}`;
                    }

                    // Disable/enable buttons
                    if (status === 'hadir') {
                        btn.disabled = true;
                        btn.closest('.btn-group, .d-flex').querySelector('.btn-tidak').disabled = false;
                    } else {
                        btn.disabled = true;
                        btn.closest('.btn-group, .d-flex').querySelector('.btn-hadir').disabled = false;
                    }

                    // Success notification
                    Swal.fire({
                        title: '✅ Berhasil!',
                        text: result.message || 'Presensi berhasil diperbarui!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    // Error notification
                    Swal.fire({
                        title: '❌ Gagal!',
                        text: result.message || 'Gagal update presensi! Silakan coba lagi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    // Re-enable buttons
                    rowButtons.forEach(b => b.disabled = false);
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    title: '❌ Terjadi Kesalahan!',
                    text: 'Terjadi kesalahan! Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                // Re-enable buttons
                rowButtons.forEach(b => b.disabled = false);
            } finally {
                // Hide loading
                loadingOverlay.classList.add('d-none');
                btn.innerHTML = originalContent;
            }
        });
    });
});
</script>

<style>
/* Professional and Aesthetic Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --shadow-soft: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-medium: 0 10px 25px rgba(0, 0, 0, 0.1);
    --shadow-strong: 0 20px 40px rgba(0, 0, 0, 0.15);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.bg-gradient-primary {
    background: var(--primary-gradient);
}

.card {
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-medium);
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.table tbody tr {
    transition: var(--transition);
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
}

.btn-group .btn {
    border-radius: 8px !important;
    margin: 0 2px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

.btn-group .btn:active {
    transform: translateY(0);
}

.btn-group .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.badge {
    font-weight: 500;
    transition: var(--transition);
}

.animate__animated {
    animation-duration: 0.8s;
}

/* Mobile Card Styles */
.mobile-card {
    background: #fff;
    border-left: 4px solid #667eea;
    transition: var(--transition);
}

.mobile-card:hover {
    background-color: rgba(102, 126, 234, 0.02);
    transform: translateX(5px);
}

.mobile-card .avatar-circle {
    width: 40px;
    height: 40px;
    font-size: 1rem;
}

.mobile-card .btn {
    border-radius: 8px !important;
    transition: var(--transition);
}

.mobile-card .btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
}

/* Responsive Enhancements */
@media (max-width: 576px) {
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .card-header h4 {
        font-size: 1.25rem;
    }

    .mobile-card {
        padding: 1rem;
    }

    .mobile-card .d-flex.gap-2 .btn {
        flex: 1;
        margin: 0 2px;
    }
}

@media (max-width: 768px) {
    .d-flex.flex-column {
        text-align: center;
    }

    .badge.bg-info {
        margin-top: 1rem;
    }
}

/* Loading States */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

#loadingOverlay .spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Accessibility */
.btn[aria-label] {
    position: relative;
}

.btn[aria-label]:focus {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}
</style>