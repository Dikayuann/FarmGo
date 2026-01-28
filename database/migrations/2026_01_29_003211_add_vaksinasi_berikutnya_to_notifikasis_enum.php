<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'vaksinasi_berikutnya' to jenis_notifikasi ENUM
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN jenis_notifikasi ENUM('vaksin', 'reproduksi', 'kesehatan', 'kesehatan_darurat', 'pemeriksaan_berikutnya', 'vaksinasi_berikutnya', 'langganan', 'quota_warning', 'umum') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum values
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN jenis_notifikasi ENUM('vaksin', 'reproduksi', 'kesehatan', 'kesehatan_darurat', 'pemeriksaan_berikutnya', 'langganan', 'quota_warning', 'umum') NOT NULL");
    }
};
