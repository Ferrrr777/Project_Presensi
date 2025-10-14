@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Siswa</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.murid.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Siswa</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="alat_id" class="form-label">Alat Musik</label>
            <select name="alat_id" id="alat_id" class="form-select" required>
                <option value="">-- Pilih Alat Musik --</option>
                @foreach($alatMusik as $alat)
                    <option value="{{ $alat->id }}">{{ $alat->nama }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
