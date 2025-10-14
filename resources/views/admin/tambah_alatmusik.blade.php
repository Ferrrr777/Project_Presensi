@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Alat Musik</h1>

    <form action="{{ route('admin.alatmusik.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Alat Musik</label>
            <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukkan nama alat musik...">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection


