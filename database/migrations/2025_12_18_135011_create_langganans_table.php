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
        Schema::create('langganans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('paket_langganan', ['trial', 'premium_monthly', 'premium_yearly']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->enum('status', ['aktif', 'kadaluarsa', 'dibatalkan'])
                ->default('aktif');
            $table->decimal('harga', 10, 2);
            $table->enum('metode_pembayaran', ['transfer_bank', 'e_wallet', 'kartu_kredit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('langganans');
    }
};
