<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_mapels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->foreignId('gtk_id')->nullable()->constrained('gtks')->onDelete('set null');
            $table->timestamps();

            // Mencegah mata pelajaran yang sama diinput ganda di kelas yang sama
            $table->unique(['kelas_id', 'mapel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_mapels');
    }
};