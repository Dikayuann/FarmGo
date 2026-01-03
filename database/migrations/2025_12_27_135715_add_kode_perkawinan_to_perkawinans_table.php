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
        Schema::table('perkawinans', function (Blueprint $table) {
            $table->string('kode_perkawinan', 50)->unique()->after('id');
            $table->index('kode_perkawinan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perkawinans', function (Blueprint $table) {
            $table->dropIndex(['kode_perkawinan']);
            $table->dropColumn('kode_perkawinan');
        });
    }
};
