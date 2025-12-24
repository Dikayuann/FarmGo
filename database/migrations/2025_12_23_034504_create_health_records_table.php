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
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')
                ->constrained('animals')
                ->onDelete('cascade');
            $table->dateTime('tanggal_pemeriksaan');
            $table->enum('jenis_pemeriksaan', ['rutin', 'darurat', 'follow_up']);
            $table->decimal('berat_badan', 8, 2);
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->enum('status_kesehatan', ['sehat', 'sakit', 'dalam_perawatan', 'sembuh']);
            $table->text('gejala')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('tindakan')->nullable();
            $table->string('obat')->nullable();
            $table->decimal('biaya', 10, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->date('pemeriksaan_berikutnya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
