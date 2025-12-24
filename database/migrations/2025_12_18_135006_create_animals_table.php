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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hewan')->unique();
            $table->string('nama_hewan');
            $table->enum('jenis_hewan', ['sapi', 'kambing', 'domba']);
            $table->string('ras_hewan');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['jantan', 'betina']);
            $table->decimal('berat_badan', 8, 2)->comment('Weight in kg');
            $table->enum('status_kesehatan', ['sehat', 'sakit', 'karantina'])
                ->default('sehat');
            $table->string('qr_url')->nullable();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
