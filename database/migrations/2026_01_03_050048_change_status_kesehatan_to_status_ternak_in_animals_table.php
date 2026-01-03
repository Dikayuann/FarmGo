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
        // Step 1: Add new column with new enum values
        Schema::table('animals', function (Blueprint $table) {
            $table->enum('status_ternak', ['beli', 'perkawinan', 'hadiah'])
                ->default('beli')
                ->after('berat_badan');
        });

        // Step 2: Migrate existing data - set all to 'hadiah'
        DB::table('animals')->update(['status_ternak' => 'hadiah']);

        // Step 3: Drop old column
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('status_kesehatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add back old column
        Schema::table('animals', function (Blueprint $table) {
            $table->enum('status_kesehatan', ['sehat', 'sakit', 'karantina'])
                ->default('sehat')
                ->after('berat_badan');
        });

        // Step 2: Set default value for old column
        DB::table('animals')->update(['status_kesehatan' => 'sehat']);

        // Step 3: Drop new column
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('status_ternak');
        });
    }
};
