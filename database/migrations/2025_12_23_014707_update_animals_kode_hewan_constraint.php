<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['kode_hewan']);
            // Make kode_hewan unique per user instead
            $table->unique(['user_id', 'kode_hewan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            // Reverse: drop the composite unique and restore the original
            $table->dropUnique(['user_id', 'kode_hewan']);
            $table->unique('kode_hewan');
        });
    }
};
