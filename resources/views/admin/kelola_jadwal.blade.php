@extends('layouts.app') 

@section('content')
<div class="container">
    <h1>Kelola Jadwal Mingguan</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tombol tambah jadwal --}}
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </button>
    </div>

    @php
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    @endphp

    {{-- LOOP PER HARI --}}
    @foreach($hariList as $hari)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">
            {{ $hari }}
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr class="text-center">
                        <th style="width: 20%">Jam</th>
                        <th style="width: 20%">Alat Musik</th>
                        <th style="width: 25%">Murid</th>
                        <th style="width: 20%">Pengajar</th>
                        <th style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jadwalHari = $jadwals->filter(function($j) use ($hari){
                            return $j->hari == $hari;
                        });
                    @endphp

                    @forelse($jadwalHari as $jadwal)
                    <tr class="align-middle text-center">
                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->alatMusik->nama ?? '-' }}</td>
                        <td>{{ $jadwal->murid->nama ?? '-' }}</td>
                        <td>{{ $jadwal->pengajar->nama ?? '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#editJadwalModal{{ $jadwal->id }}">
                                Edit
                            </button>
                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="editJadwalModal{{ $jadwal->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Jadwal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Hari</label>
                                            <select name="hari" class="form-select" required>
                                                @foreach($hariList as $h)
                                                    <option value="{{ $h }}" {{ $jadwal->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Alat Musik</label>
                                            <select name="alat_id" class="form-select" required>
                                                @foreach($alatMusiks as $alat)
                                                    <option value="{{ $alat->id }}" {{ $jadwal->alat_id == $alat->id ? 'selected' : '' }}>
                                                        {{ $alat->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Murid</label>
                                            <select name="murid_id" class="form-select" required>
                                                @foreach($murids as $murid)
                                                    <option value="{{ $murid->id }}" {{ $jadwal->murid_id == $murid->id ? 'selected' : '' }}>
                                                        {{ $murid->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Pengajar</label>
                                            <select name="pengajar_id" class="form-select" required>
                                                @foreach($pengajars as $pengajar)
                                                    <option value="{{ $pengajar->id }}" {{ $jadwal->pengajar_id == $pengajar->id ? 'selected' : '' }}>
                                                        {{ $pengajar->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label>Jam Mulai</label>
                                                <input type="time" name="jam_mulai" value="{{ $jadwal->jam_mulai }}" class="form-control" required>
                                            </div>
                                            <div class="col">
                                                <label>Jam Selesai</label>
                                                <input type="time" name="jam_selesai" value="{{ $jadwal->jam_selesai }}" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada jadwal untuk hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

{{-- MODAL TAMBAH JADWAL --}}
<div class="modal fade" id="tambahJadwalModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.jadwal.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jadwal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Hari</label>
                <select name="hari" class="form-select" required>
                    <option value="">-- Pilih Hari --</option>
                    @foreach($hariList as $hari)
                        <option value="{{ $hari }}">{{ $hari }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Alat Musik</label>
                <select name="alat_id" class="form-select" required>
                    @foreach($alatMusiks as $alat)
                        <option value="{{ $alat->id }}">{{ $alat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Murid</label>
                <select name="murid_id" class="form-select" required>
                    @foreach($murids as $murid)
                        <option value="{{ $murid->id }}">{{ $murid->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Pengajar</label>
                <select name="pengajar_id" class="form-select" required>
                    @foreach($pengajars as $pengajar)
                        <option value="{{ $pengajar->id }}">{{ $pengajar->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>
                <div class="col">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>



@endsection
