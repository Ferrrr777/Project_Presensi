<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reschedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');

            $table->date('tanggal_awal'); // otomatis tanggal hari ini
            $table->string('hari_awal', 20);
            $table->time('jam_mulai_awal');
            $table->time('jam_selesai_awal');

            $table->string('hari_baru', 20);
            $table->time('jam_mulai_baru');
            $table->time('jam_selesai_baru');

            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reschedules');
    }
};
