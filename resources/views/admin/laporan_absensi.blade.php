@extends('layouts.app')

@section('title', 'Laporan Presensi')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Presensi</h5>
            <a href="{{ route('admin.laporan.exportExcel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>

        <div class="card-body">
            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.laporan.presensi') }}" class="row mb-4 g-3">
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label>Group By</label>
                    <select name="group_by" class="form-control">
                        <option value="">Tidak Ada</option>
                        <option value="alat_id" {{ request('group_by')=='alat_id'?'selected':'' }}>Alat Musik</option>
                        <option value="pengajar_id" {{ request('group_by')=='pengajar_id'?'selected':'' }}>Pengajar</option>
                        <option value="tanggal" {{ request('group_by')=='tanggal'?'selected':'' }}>Tanggal</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Alat Musik</th>
                            <th>Pengajar</th>
                            <th>Materi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $key => $presensi)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>{{ $presensi->tanggal }}</td>
                                <td>{{ $presensi->murid->nama ?? '-' }}</td>
                                <td>{{ $presensi->alat->nama ?? '-' }}</td>
                                <td>{{ $presensi->pengajar->nama ?? '-' }}</td>
                                <td>{{ $presensi->materi ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $presensi->status == 'hadir' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($presensi->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
