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
        // First, update any existing invalid values to 'kesehatan'
        DB::table('notifikasis')
            ->whereNotIn('jenis_notifikasi', ['vaksin', 'reproduksi', 'kesehatan', 'umum'])
            ->update(['jenis_notifikasi' => 'kesehatan']);

        // Update any invalid status values to 'pending'
        DB::table('notifikasis')
            ->whereNotIn('status', ['pending', 'terkirim', 'dibaca'])
            ->update(['status' => 'pending']);

        // Update the enum to include new notification types
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN jenis_notifikasi ENUM('vaksin', 'reproduksi', 'kesehatan', 'kesehatan_darurat', 'pemeriksaan_berikutnya', 'langganan', 'umum') NOT NULL");

        // Update status enum to match the model
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN status ENUM('pending', 'terkirim', 'dibaca', 'belum_dibaca') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN jenis_notifikasi ENUM('vaksin', 'reproduksi', 'kesehatan', 'umum') NOT NULL");
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN status ENUM('pending', 'terkirim', 'dibaca') NOT NULL DEFAULT 'pending'");
    }
};
