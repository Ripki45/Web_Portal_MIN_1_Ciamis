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
        Schema::create('mutasi_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajarans')->onDelete('set null');
            
            $table->enum('jenis_mutasi', ['Masuk', 'Keluar']);
            
            // Kolom khusus Mutasi Masuk & Keluar sesuai format Excel
            $table->string('kelas_asal', 50)->nullable(); // Contoh: "6", "2", atau "Kelas 5-D"
            $table->foreignId('diterima_di_kelas_id')->nullable()->constrained('kelas')->onDelete('set null'); // Contoh: 6B, 1C, 6A
            $table->string('sekolah_asal_tujuan', 150)->nullable(); // Contoh: MIS DARUSSALAM / SDN PAWITRA
            $table->string('alamat_sekolah', 255)->nullable(); // Alamat sekolah asal / tujuan
            $table->date('tanggal_mutasi')->nullable(); // Tgl Masuk / Tgl Keluar
            $table->string('alasan_mutasi')->nullable(); // Catatan / alasan pindah
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_siswas');
    }
};
