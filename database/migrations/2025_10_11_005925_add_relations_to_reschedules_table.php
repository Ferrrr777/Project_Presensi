<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reschedules', function (Blueprint $table) {
            // Tambahkan foreign keys jika belum ada
            if (!Schema::hasColumn('reschedules', 'murid_id')) {
                $table->foreignId('murid_id')->after('jadwal_id')->constrained('murids')->onDelete('cascade');
            }

            if (!Schema::hasColumn('reschedules', 'pengajar_id')) {
                $table->foreignId('pengajar_id')->after('murid_id')->constrained('pengajars')->onDelete('cascade');
            }

            if (!Schema::hasColumn('reschedules', 'alat_musik_id')) {
                $table->foreignId('alat_musik_id')->after('pengajar_id')->constrained('alat_musiks')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reschedules', function (Blueprint $table) {
            if (Schema::hasColumn('reschedules', 'murid_id')) {
                $table->dropForeign(['murid_id']);
                $table->dropColumn('murid_id');
            }

            if (Schema::hasColumn('reschedules', 'pengajar_id')) {
                $table->dropForeign(['pengajar_id']);
                $table->dropColumn('pengajar_id');
            }

            if (Schema::hasColumn('reschedules', 'alat_musik_id')) {
                $table->dropForeign(['alat_musik_id']);
                $table->dropColumn('alat_musik_id');
            }
        });
    }
};
