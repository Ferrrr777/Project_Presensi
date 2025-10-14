<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('jadwals', function (Blueprint $table) {
        $table->id();

        // foreign keys
        $table->foreignId('murid_id')->constrained('murids')->onDelete('cascade');
        $table->foreignId('pengajar_id')->constrained('pengajars')->onDelete('cascade');
        $table->foreignId('alat_id')->constrained('alat_musiks')->onDelete('cascade');

        // jadwal
        $table->string('hari'); // ubah dari tanggal ke hari (misal: Senin, Selasa)
        $table->time('jam_mulai');
        $table->time('jam_selesai');

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
