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
        Schema::create('heat_detections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
            $table->date('tanggal_deteksi');
            $table->json('gejala')->nullable(); // gelisah, mengembik, nafsu_makan_turun, etc
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'bred', 'ignored'])->default('pending');
            $table->foreignId('perkawinan_id')->nullable()->constrained('perkawinans')->onDelete('set null');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('animal_id');
            $table->index('tanggal_deteksi');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heat_detections');
    }
};
