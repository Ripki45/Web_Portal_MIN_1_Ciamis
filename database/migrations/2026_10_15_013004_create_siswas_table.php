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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel kelas (rombel aktif saat ini)
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            
            // Identitas Nomor & Nama
            $table->string('nomor_induk', 30)->nullable();
            $table->string('nisn', 20)->nullable();
            $table->string('nama_siswa', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            
            // Kelahiran
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            
            // Data Orang Tua
            $table->string('nama_ayah', 150)->nullable();
            $table->string('nama_ibu', 150)->nullable();
            $table->text('alamat_ortu')->nullable();
            $table->string('pekerjaan_ortu', 100)->nullable();
            
            // Status Akademik Siswa
            $table->enum('status', ['Aktif', 'Mutasi Keluar', 'Lulus'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
