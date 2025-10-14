<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reschedules', function (Blueprint $table) {
            $table->foreignId('pengajar_id')->nullable()->constrained('pengajars')->onDelete('set null')->after('jadwal_id');
        });
    }

    public function down(): void
    {
        Schema::table('reschedules', function (Blueprint $table) {
            $table->dropForeign(['pengajar_id']);
            $table->dropColumn('pengajar_id');
        });
    }
};
