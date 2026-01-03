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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('langganan_id')
                ->nullable()
                ->constrained('langganans')
                ->onDelete('set null');

            // Midtrans transaction details
            $table->string('order_id')->unique(); // Order ID untuk Midtrans
            $table->string('transaction_id')->nullable(); // ID dari Midtrans setelah payment
            $table->string('gross_amount'); // Total pembayaran

            // Payment details
            $table->enum('payment_type', [
                'credit_card',
                'bank_transfer',
                'echannel',
                'gopay',
                'qris',
                'shopeepay',
                'other'
            ])->nullable();
            $table->string('payment_code')->nullable(); // VA number, QRIS code, etc
            $table->string('bank')->nullable(); // Bank name if using transfer

            // Transaction status dari Midtrans
            $table->enum('status', [
                'pending',      // Menunggu pembayaran
                'settlement',   // Pembayaran berhasil
                'expire',       // Transaksi kadaluarsa
                'cancel',       // Dibatalkan
                'deny',         // Ditolak
                'refund'        // Dikembalikan
            ])->default('pending');

            // Metadata
            $table->json('midtrans_response')->nullable(); // Full response dari Midtrans
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran berhasil
            $table->timestamp('expired_at')->nullable(); // Waktu kadaluarsa

            $table->timestamps();

            // Indexes untuk performance
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
