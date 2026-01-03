<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new  class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('langganans', function (Blueprint $table) {
            // Tambah kolom untuk tracking otomatis
            $table->boolean('auto_renew')->default(false)->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('auto_renew');

            // Update enum metode pembayaran untuk include Midtrans options
            $table->dropColumn('metode_pembayaran');
        });

        Schema::table('langganans', function (Blueprint $table) {
            $table->enum('metode_pembayaran', [
                'midtrans',
                'manual_transfer',
                'other'
            ])->default('midtrans')->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('langganans', function (Blueprint $table) {
            $table->dropColumn(['auto_renew', 'cancelled_at', 'metode_pembayaran']);
        });

        Schema::table('langganans', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['transfer_bank', 'e_wallet', 'kartu_kredit'])
                ->after('harga');
        });
    }
};
