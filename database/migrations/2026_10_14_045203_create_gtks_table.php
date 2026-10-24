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
        Schema::create('gtks', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (untuk keperluan Role & Login di halaman Hak Akses nanti)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Biodata GTK
            $table->string('foto')->nullable();
            $table->string('nama_lengkap');
            $table->string('nomor_induk', 50)->nullable(); // Untuk NIP / NUPTK / NIK (Nullable)
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 20)->nullable();
            
            // Kepegawaian & Penugasan
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'Honorer']);
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->onDelete('set null');
            $table->foreignId('posisi_id')->nullable()->constrained('posisis')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtks');
    }
};
