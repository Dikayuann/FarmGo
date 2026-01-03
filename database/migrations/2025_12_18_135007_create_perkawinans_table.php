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
        Schema::create('perkawinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jantan_id')
                ->constrained('animals')
                ->onDelete('cascade');
            $table->foreignId('betina_id')
                ->constrained('animals')
                ->onDelete('cascade');
            $table->date('tanggal_birahi')->nullable();
            $table->date('tanggal_perkawinan');
            $table->enum('metode_perkawinan', ['alami', 'inseminasi_buatan']);
            $table->enum('status_reproduksi', ['menunggu', 'bunting', 'melahirkan', 'gagal'])
                ->default('menunggu');
            $table->date('tanggal_melahirkan')->nullable();
            $table->integer('jumlah_anak')->nullable();
            $table->date('estimasi_kelahiran')->nullable();
            $table->date('reminder_birahi_berikutnya')->nullable();
            $table->enum('reminder_status', ['aktif', 'selesai'])->nullable()->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkawinans');
    }
};
