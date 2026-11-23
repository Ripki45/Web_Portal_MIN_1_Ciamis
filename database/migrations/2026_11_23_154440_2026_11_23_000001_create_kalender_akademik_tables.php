<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 20)->index(); // Contoh: 2026/2027
            $table->enum('semester', ['gasal', 'genap'])->default('gasal');
            $table->string('judul', 160);
            $table->string('kategori', 40)->default('kegiatan_madrasah');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_hari_libur')->default(false);
            $table->boolean('is_efektif_kbm')->default(true);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_publik')->default(true);
            $table->timestamps();
        });

        Schema::create('kaldik_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 20)->unique();
            $table->string('dasar_hukum', 255)->nullable();
            $table->unsignedTinyInteger('hari_kerja')->default(6); // 5 atau 6 hari
            $table->string('file_pdf', 255)->nullable();
            $table->date('tanggal_pengesahan')->nullable();
            $table->string('nama_kepsek', 150)->default('IIM SITI HALIMAH, S.Ag., M.Pd.');
            $table->string('nip_kepsek', 50)->default('197206051997032003');
            $table->text('catatan_kaki')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kaldik_dokumens');
        Schema::dropIfExists('agenda_akademiks');
    }
};