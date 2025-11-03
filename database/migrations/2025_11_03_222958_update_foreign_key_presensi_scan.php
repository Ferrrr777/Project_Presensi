<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi_scan', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['user_id']);

            // Buat foreign key baru ke tabel pengajars
            $table->foreign('user_id')->references('id')->on('pengajars')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('presensi_scan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // Kembalikan foreign key ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
