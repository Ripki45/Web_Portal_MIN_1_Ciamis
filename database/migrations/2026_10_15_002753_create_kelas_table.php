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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->integer('tingkat'); // Tingkat 1, 2, 3, 4, 5, atau 6
            $table->string('nama_kelas', 50)->unique(); // Contoh: Kelas 1-A, Kelas 3-B
            
            // Relasi ke tabel gtks untuk Wali Kelas (jika guru dihapus, kelas tetap aman & wali kelas jadi kosong)
            $table->foreignId('wali_kelas_id')->nullable()->constrained('gtks')->onDelete('set null');
            
            $table->string('ruangan', 100)->nullable(); // Contoh: R.01 / Gedung Utara
            $table->integer('kapasitas')->default(30); // Daya tampung siswa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
