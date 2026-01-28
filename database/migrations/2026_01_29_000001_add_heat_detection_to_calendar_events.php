<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to add 'heat_detection'
        DB::statement("ALTER TABLE calendar_events MODIFY COLUMN event_type ENUM('vaccination', 'birth_estimate', 'health_checkup', 'heat_detection', 'custom') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE calendar_events MODIFY COLUMN event_type ENUM('vaccination', 'birth_estimate', 'health_checkup', 'custom') NOT NULL");
    }
};
