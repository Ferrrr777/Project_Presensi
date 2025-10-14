@extends('layouts.app')

@section('title', 'Daftar Reschedule')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 fw-bold text-dark">
            <i class="bi bi-calendar-check me-2 text-primary"></i>
            Daftar Reschedule
        </h1>
 
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-semibold py-3">
            <i class="bi bi-clock-history me-2"></i>
            Reschedule Terbaru
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Tanggal Awal</th>
                            <th>Jam Awal</th>
                            <th>Tanggal Baru</th>
                            <th>Hari Baru</th>
                            <th>Jam Baru</th>
                            <th>Murid</th>
                            <th>Pengajar</th>
                            <th>Alat Musik</th>
                            <th>Alasan</th>
                            <th>Dibuat</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reschedules as $r)
                            <tr class="border-bottom">
                                <td class="ps-3 fw-medium">{{ optional($r->tanggal_awal)->format('d/m/Y') ?? '-' }}</td>              
                                <td>
                                    {{ optional(\Carbon\Carbon::parse($r->jam_mulai_awal))->format('H:i') ?? '-' }}
                                    -
                                    {{ optional(\Carbon\Carbon::parse($r->jam_selesai_awal))->format('H:i') ?? '-' }}
                                </td>
                                <td>{{ optional($r->tanggal_baru)->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    @if($r->hari_baru)
                                        <span class="badge bg-info text-dark">{{ ucfirst($r->hari_baru) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ optional(\Carbon\Carbon::parse($r->jam_mulai_baru))->format('H:i') ?? '-' }}
                                    -
                                    {{ optional(\Carbon\Carbon::parse($r->jam_selesai_baru))->format('H:i') ?? '-' }}
                                </td>               
                                <td class="fw-medium">{{ optional($r->murid)->nama ?? optional($r->jadwal->murid)->nama ?? '-' }}</td>
                                <td>{{ optional($r->pengajar)->nama ?? optional($r->jadwal->pengajar)->nama ?? '-' }}</td>
                                <td>{{ optional($r->alatMusik)->nama ?? optional($r->jadwal->alatMusik)->nama ?? '-' }}</td>
                                <td class="text-truncate" style="max-width: 150px;" title="{{ $r->alasan ?? '-' }}">
                                    {{ Str::limit($r->alasan ?? '-', 30) }}
                                </td>
                                <td class="text-muted small">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Tombol Edit modal -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Form Batal Reschedule -->
                                        <form action="{{ route('admin.reschedule.cancel', $r->id) }}" method="POST" style="display:inline;" class="d-inline" onsubmit="return confirm('Yakin batal reschedule ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Batal">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $r->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $r->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.reschedule.update', $r->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title mb-0" id="editModalLabel{{ $r->id }}">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Reschedule
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="tanggal_baru{{ $r->id }}" class="form-label fw-semibold">Tanggal Baru <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="date" 
                                                            name="tanggal_baru" 
                                                            id="tanggal_baru{{ $r->id }}" 
                                                            class="form-control @error('tanggal_baru') is-invalid @enderror" 
                                                            value="{{ old('tanggal_baru', optional($r->tanggal_baru)->format('Y-m-d')) }}" 
                                                            required>
                                                        @error('tanggal_baru')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                      <div class="col-md-6">
                                                        <label for="pengajar_id{{ $r->id }}" class="form-label fw-semibold">Pengajar <span class="text-danger">*</span></label>
                                                        <select name="pengajar_id" id="pengajar_id{{ $r->id }}" class="form-select @error('pengajar_id') is-invalid @enderror" required>
                                                            <option value="">Pilih Pengajar</option>
                                                            @foreach($pengajars as $p)
                                                                <option value="{{ $p->id }}"
                                                                    @if($p->id == (old('pengajar_id', $r->pengajar_id ?? $r->jadwal->pengajar_id ?? ''))) selected @endif>
                                                                    {{ $p->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('pengajar_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="jam_mulai_baru{{ $r->id }}" class="form-label fw-semibold">Jam Mulai Baru <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="time" 
                                                            name="jam_mulai_baru" 
                                                            id="jam_mulai_baru{{ $r->id }}" 
                                                            class="form-control @error('jam_mulai_baru') is-invalid @enderror" 
                                                            value="{{ old('jam_mulai_baru', optional(\Carbon\Carbon::parse($r->jam_mulai_baru))->format('H:i')) }}"
                                                            required>
                                                        @error('jam_mulai_baru')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="jam_selesai_baru{{ $r->id }}" class="form-label fw-semibold">Jam Selesai Baru <span class="text-danger">*</span></label>
                                                        <input 
                                                            type="time" 
                                                            name="jam_selesai_baru" 
                                                            id="jam_selesai_baru{{ $r->id }}" 
                                                            class="form-control @error('jam_selesai_baru') is-invalid @enderror" 
                                                            value="{{ old('jam_selesai_baru', optional(\Carbon\Carbon::parse($r->jam_selesai_baru ?? '00:00:00'))->format('H:i')) }}"
                                                            required>
                                                        @error('jam_selesai_baru')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="alasan{{ $r->id }}" class="form-label fw-semibold">Alasan</label>
                                                        <textarea name="alasan" id="alasan{{ $r->id }}" class="form-control @error('alasan') is-invalid @enderror" rows="3">{{ old('alasan', $r->alasan) }}</textarea>
                                                        @error('alasan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer bg-light border-0 px-4 py-3">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x me-1"></i>Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 mb-3 opacity-50"></i>
                                    <p class="mb-0">Belum ada data reschedule. Mulai tambahkan yang pertama!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination jika ada (asumsi dari controller) -->
    @if(isset($reschedules) && method_exists($reschedules, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $reschedules->links() }}
        </div>
    @endif
</div>
@endsection