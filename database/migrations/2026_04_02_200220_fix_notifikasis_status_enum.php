<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Ensure notifikasis.status ENUM includes all required values:
     * pending, terkirim, dibaca, belum_dibaca, sudah_dibaca
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN status ENUM('pending', 'terkirim', 'dibaca', 'belum_dibaca', 'sudah_dibaca') NOT NULL DEFAULT 'belum_dibaca'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN status ENUM('pending', 'terkirim', 'dibaca', 'belum_dibaca') NOT NULL DEFAULT 'pending'");
    }
};
