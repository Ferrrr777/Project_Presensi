@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<h3>Laporan Absensi Siswa</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Alat Musik</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Contoh Siswa</td>
            <td>Piano</td>
            <td>23-09-2025</td>
            <td>Hadir</td>
        </tr>
    </tbody>
</table>
@endsection
