<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('murid_id')->constrained('murids')->onDelete('cascade');
            $table->foreignId('alat_id')->constrained('alat_musiks')->onDelete('cascade');
            $table->enum('status', ['hadir', 'tidak_hadir'])->default('hadir');
            $table->foreignId('pengajar_id')->constrained('pengajars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
