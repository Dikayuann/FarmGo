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
        Schema::table('notifikasis', function (Blueprint $table) {
            // Add user_id column only if it doesn't exist
            if (!Schema::hasColumn('notifikasis', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('id')
                    ->constrained('users')
                    ->onDelete('cascade');
            }
        });

        // Update jenis_notifikasi enum to include 'langganan'
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN jenis_notifikasi ENUM('vaksin', 'reproduksi', 'kesehatan', 'langganan', 'umum')");

        // Update status enum to match model usage
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN status ENUM('pending', 'terkirim', 'dibaca', 'belum_dibaca', 'sudah_dibaca') DEFAULT 'belum_dibaca'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            if (Schema::hasColumn('notifikasis', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        // Revert enums (optional, usually not needed in down)
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN jenis_notifikasi ENUM('vaksin', 'reproduksi', 'kesehatan', 'umum')");
        DB::statement("ALTER TABLE notifikasis MODIFY COLUMN status ENUM('pending', 'terkirim', 'dibaca') DEFAULT 'pending'");
    }
};
