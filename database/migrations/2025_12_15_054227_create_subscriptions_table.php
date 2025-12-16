<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya tambahkan kolom jika belum ada
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }

            $table->enum('status_langganan', ['trial', 'premium'])
                ->default('trial');

            $table->integer('batas_ternak')->default(10);
            $table->integer('batas_vaksin')->default(10);
            $table->integer('batas_reproduksi')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'status_langganan',
                'batas_ternak',
                'batas_vaksin',
                'batas_reproduksi'
            ]);
        });
    }
};
