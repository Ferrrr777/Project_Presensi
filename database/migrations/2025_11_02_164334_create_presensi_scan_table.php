<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presensi_scan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kode_qr');
            $table->date('tanggal_scan');
            $table->time('waktu_scan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_scan');
    }
};
