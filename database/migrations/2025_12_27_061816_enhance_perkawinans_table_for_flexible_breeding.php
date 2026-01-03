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
        Schema::table('perkawinans', function (Blueprint $table) {
            // Add jantan source type
            $table->enum('jantan_type', ['owned', 'external', 'semen'])
                ->default('owned')
                ->after('id');

            // Make jantan_id nullable (not required for external/semen)
            $table->foreignId('jantan_id')->nullable()->change();

            // External jantan fields
            $table->string('jantan_external_name', 100)->nullable()->after('jantan_id');
            $table->string('jantan_external_breed', 100)->nullable()->after('jantan_external_name');
            $table->string('jantan_external_owner', 100)->nullable()->after('jantan_external_breed');
            $table->string('jantan_external_reg_number', 50)->nullable()->after('jantan_external_owner');

            // Semen fields
            $table->string('semen_code', 50)->nullable()->after('jantan_external_reg_number');
            $table->string('semen_producer', 100)->nullable()->after('semen_code');
            $table->string('semen_breed', 100)->nullable()->after('semen_producer');

            // Expand metode_perkawinan enum
            DB::statement("ALTER TABLE perkawinans MODIFY COLUMN metode_perkawinan ENUM('alami', 'inseminasi_buatan', 'ib', 'et', 'ivf', 'moet') DEFAULT 'alami'");

            // IB additional fields
            $table->string('inseminator_name', 100)->nullable()->after('metode_perkawinan');
            $table->enum('ib_time', ['pagi', 'siang', 'sore'])->nullable()->after('inseminator_name');
            $table->integer('straw_count')->nullable()->after('ib_time');
        });

        // Update existing records to have jantan_type = 'owned'
        DB::table('perkawinans')
            ->whereNotNull('jantan_id')
            ->update(['jantan_type' => 'owned']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perkawinans', function (Blueprint $table) {
            // Revert enum first
            DB::statement("ALTER TABLE perkawinans MODIFY COLUMN metode_perkawinan ENUM('alami', 'inseminasi_buatan') DEFAULT 'alami'");

            // Drop new columns
            $table->dropColumn([
                'jantan_type',
                'jantan_external_name',
                'jantan_external_breed',
                'jantan_external_owner',
                'jantan_external_reg_number',
                'semen_code',
                'semen_producer',
                'semen_breed',
                'inseminator_name',
                'ib_time',
                'straw_count',
            ]);

            // Make jantan_id required again
            $table->foreignId('jantan_id')->nullable(false)->change();
        });
    }
};
