@extends('layouts.app') 

@section('content')
<!-- Konten tetap sama -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Presensi Pengajar
                    </h5>
                    <small class="text-light">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</small>
                </div>
                <div class="card-body">
                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama (Siswa)</th>
                                        <th>Alat Musik</th>
                                        <th class="text-center">Jam Mulai - Selesai</th>
                                        <th class="text-center">Status Les</th>
                                        <th class="text-center">Status Presensi</th>
                                        <th class="text-center">Materi</th>
                                        <th class="text-center">Aksi</th>
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
                                            // Jika field jam_selesai ada, gunakan itu; jika tidak, hitung otomatis (misalnya +1 jam)
                                            $jamSelesai = $jadwal->jam_selesai_baru ?? $jadwal->jam_selesai ?? null;
                                            if (!$jamSelesai && $jamMulai) {
                                                // Hitung jam selesai = jam mulai + 1 jam (durasi default)
                                                $jamSelesai = \Carbon\Carbon::parse($jamMulai)->addHour()->format('H:i:s');
                                            }
                                            $jamMulaiFormatted = $jamMulai ? \Carbon\Carbon::parse($jamMulai)->format('H:i') : 'N/A';
                                            $jamSelesaiFormatted = $jamSelesai ? \Carbon\Carbon::parse($jamSelesai)->format('H:i') : 'N/A';
                                            
                                            // Hitung status les berdasarkan waktu sekarang
                                            $now = \Carbon\Carbon::now();
                                            $jamMulaiCarbon = $jamMulai ? \Carbon\Carbon::parse($jamMulai) : null;
                                            $jamSelesaiCarbon = $jamSelesai ? \Carbon\Carbon::parse($jamSelesai) : null;
                                            $lesSelesai = $jamSelesaiCarbon && $now->gte($jamSelesaiCarbon);
                                            $lesBerlangsung = $jamMulaiCarbon && $jamSelesaiCarbon && $now->gte($jamMulaiCarbon) && $now->lt($jamSelesaiCarbon);
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
                                                @if($lesBerlangsung)
                                                    <span class="badge bg-success">Les sedang berlangsung</span>
                                                @elseif($lesSelesai)
                                                    <span class="badge bg-danger">Les telah selesai</span>
                                                @else
                                                    <span class="badge bg-secondary">Les belum dimulai</span>
                                                @endif
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
    <input type="text" class="form-control form-control-sm input-materi" 
       placeholder="Isi materi..." 
       data-id="{{ $jadwal->murid_id }}" 
       data-alat="{{ $alat_id }}"
       value="{{ $presensi->materi ?? '' }}"
       @if($lesSelesai) disabled @endif>

</td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Presensi">
                                                    <button class="btn btn-success btn-hadir shadow-sm position-relative"
                                                        data-id="{{ $jadwal->murid_id }}"
                                                        data-alat="{{ $alat_id }}"
                                                        @if($lesSelesai) disabled @endif 
                                                        aria-label="Tandai Hadir">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        <span class="d-none d-sm-inline">Hadir</span>
                                                    </button>
                                                    <button class="btn btn-danger btn-tidak shadow-sm position-relative"
                                                        data-id="{{ $jadwal->murid_id }}"
                                                        data-alat="{{ $alat_id }}"
                                                        @if($lesSelesai) disabled @endif   
                                                        aria-label="Tandai Tidak Hadir">
                                                        <i class="bi bi-x-circle-fill me-1"></i>
                                                        <span class="d-none d-sm-inline">Tidak Hadir</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
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
                                $jamSelesai = $jadwal->jam_selesai_baru ?? $jadwal->jam_selesai ?? null;
                                if (!$jamSelesai && $jamMulai) {
                                    $jamSelesai = \Carbon\Carbon::parse($jamMulai)->addHour()->format('H:i:s');
                                }
                                $jamMulaiFormatted = $jamMulai ? \Carbon\Carbon::parse($jamMulai)->format('H:i') : 'N/A';
                                $jamSelesaiFormatted = $jamSelesai ? \Carbon\Carbon::parse($jamSelesai)->format('H:i') : 'N/A';
                                
                                // Hitung status les
                                $now = \Carbon\Carbon::now();
                                $jamMulaiCarbon = $jamMulai ? \Carbon\Carbon::parse($jamMulai) : null;
                                $jamSelesaiCarbon = $jamSelesai ? \Carbon\Carbon::parse($jamSelesai) : null;
                                $lesSelesai = $jamSelesaiCarbon && $now->gte($jamSelesaiCarbon);
                                $lesBerlangsung = $jamMulaiCarbon && $jamSelesaiCarbon && $now->gte($jamMulaiCarbon) && $now->lt($jamSelesaiCarbon);
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
                                    <div class="text-end">
                                        <span class="badge rounded-pill fs-6 px-2 py-1 
                                            @if($presensi)
                                                {{ $presensi->status === 'hadir' ? 'bg-success' : 'bg-danger' }}
                                            @else
                                                bg-secondary
                                            @endif"
                                            id="status-mobile-{{ $jadwal->murid_id }}-{{ $alat_id }}">
                                            {{ $presensi ? ucfirst(str_replace('_',' ',$presensi->status)) : 'Belum diisi' }}
                                        </span>
                                        <br>
                                        <small class="text-muted mt-1">
                                            @if($lesBerlangsung)
                                                <i class="bi bi-play-circle-fill text-success"></i> Les sedang berlangsung
                                            @elseif($lesSelesai)
                                                <i class="bi bi-stop-circle-fill text-danger"></i> Les telah selesai
                                            @else
                                                <i class="bi bi-pause-circle-fill text-secondary"></i> Les belum dimulai
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    <span class="text-muted">{{ $jamMulaiFormatted }} - {{ $jamSelesaiFormatted }}</span>
                                </div>
 <div class="mb-2">
       <input type="text" class="form-control form-control-sm input-materi" 
       placeholder="Isi materi..." 
       data-id="{{ $jadwal->murid_id }}" 
       data-alat="{{ $alat_id }}"
       value="{{ $presensi->materi ?? '' }}"
       @if($lesSelesai) disabled @endif>
    </div>
                                 
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm flex-fill btn-hadir shadow-sm"
                                        data-id="{{ $jadwal->murid_id }}"
                                        data-alat="{{ $alat_id }}"
                                        @if($lesSelesai) disabled @endif>  <!-- Hanya disabled jika les selesai -->
                                        <i class="bi bi-check-circle-fill me-1"></i>Hadir
                                    </button>
                                    <button class="btn btn-danger btn-sm flex-fill btn-tidak shadow-sm"
                                        data-id="{{ $jadwal->murid_id }}"
                                        data-alat="{{ $alat_id }}"
                                        @if($lesSelesai) disabled @endif>  <!-- Hanya disabled jika les selesai -->
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

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Pastikan jQuery di-include -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Pastikan SweetAlert2 di-include -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log('Script loaded'); // Debug: Pastikan script di-load

   // Event listener untuk tombol Hadir
$(document).on('click', '.btn-hadir', function() {
    const muridId = $(this).data('id');
    const alatId = $(this).data('alat');

    // Cari input materi di card/row yang sama
    const materiInput = $(this).closest('.mobile-card, tr').find('.input-materi');
    const materi = materiInput.val()?.trim();

    if (!materi) {
        Swal.fire({
            title: '⚠️ Wajib diisi!',
            text: 'Silakan isi materi sebelum menandai presensi.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    updatePresensi(muridId, alatId, 'hadir', materi, $(this));
});

// Event listener untuk tombol Tidak Hadir
$(document).on('click', '.btn-tidak', function() {
    const muridId = $(this).data('id');
    const alatId = $(this).data('alat');

    const materiInput = $(this).closest('.mobile-card, tr').find('.input-materi');
    const materi = materiInput.val()?.trim();

    if (!materi) {
        Swal.fire({
            title: '⚠️ Wajib diisi!',
            text: 'Silakan isi materi sebelum menandai presensi.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    updatePresensi(muridId, alatId, 'tidak_hadir', materi, $(this));
});


function updatePresensi(muridId, alatId, status, materi, button) {
    $('#loadingOverlay').removeClass('d-none'); // Tampilkan loading

    $.ajax({
        url: '{{ route("pengajar.presensi.store") }}',
        type: 'POST',
        data: {
            murid_id: muridId,
            alat_id: alatId,
            status: status,
            materi: materi,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#loadingOverlay').addClass('d-none'); // Sembunyikan loading
            if (response.success) {
                $('#status-' + muridId + '-' + alatId)
                    .removeClass('bg-success bg-danger bg-secondary')
                    .addClass(status === 'hadir' ? 'bg-success' : 'bg-danger')
                    .text(status === 'hadir' ? 'Hadir' : 'Tidak Hadir');

                $('#status-mobile-' + muridId + '-' + alatId)
                    .removeClass('bg-success bg-danger bg-secondary')
                    .addClass(status === 'hadir' ? 'bg-success' : 'bg-danger')
                    .text(status === 'hadir' ? 'Hadir' : 'Tidak Hadir');

                Swal.fire({
                    title: '✅ Berhasil!',
                    text: 'Presensi berhasil diperbarui.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    title: '❌ Gagal!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr) {
            $('#loadingOverlay').addClass('d-none');
            Swal.fire({
                title: '⚠️ Terjadi Kesalahan',
                text: xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

});

</script>
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .mobile-card {
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .btn-group-sm .btn {
        font-size: 0.8rem;
    }
</style>
@endsection