<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekskul_anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekskul_id')->constrained('ekskuls')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->timestamps();

            // Kunci anti data ganda: 1 siswa tidak bisa didaftarkan 2 kali di ekskul yang sama
            $table->unique(['ekskul_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekskul_anggotas');
    }
};