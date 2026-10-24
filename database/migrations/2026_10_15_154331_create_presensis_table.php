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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            
            // Menyimpan kelas & tahun ajaran saat absen dilakukan (agar aman saat naik kelas)
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajarans')->onDelete('set null');
            
            $table->date('tanggal');
            $table->time('waktu_masuk')->nullable();
            $table->string('status', 20)->default('Hadir'); // Hadir, Sakit, Izin, Alfa
            $table->string('metode', 30)->default('Scan Kamera'); // Scan Kamera, Scan Alat, Manual
            $table->string('keterangan')->nullable();
            $table->timestamps();

            // Pengaman: 1 siswa hanya bisa tercatat 1 kali per tanggal
            $table->unique(['siswa_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
