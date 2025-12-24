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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')
                ->constrained('animals')
                ->onDelete('cascade');
            $table->enum('jenis_notifikasi', ['vaksin', 'reproduksi', 'kesehatan', 'umum']);
            $table->text('pesan');
            $table->dateTime('tanggal_kirim');
            $table->enum('status', ['pending', 'terkirim', 'dibaca'])
                ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
