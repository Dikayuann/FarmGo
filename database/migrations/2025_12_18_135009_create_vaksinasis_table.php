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
        Schema::create('vaksinasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')
                ->constrained('animals')
                ->onDelete('cascade');
            $table->date('tanggal_vaksin');
            $table->string('jenis_vaksin');
            $table->string('dosis');
            $table->enum('rute_pemberian', ['oral', 'injeksi_im', 'injeksi_sc', 'injeksi_iv']);
            $table->integer('masa_penarikan')->comment('Withdrawal period in days');
            $table->string('nama_dokter');
            $table->date('jadwal_berikutnya')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaksinasis');
    }
};
