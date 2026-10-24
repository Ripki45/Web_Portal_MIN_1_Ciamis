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
        Schema::create('pengaturan_presensis', function (Blueprint $table) {
            $table->id();
            $table->time('jam_buka')->default('07:15:00');
            $table->time('jam_tutup')->default('09:00:00');
            $table->boolean('libur_minggu')->default(true); // Senin-Sabtu aktif, Minggu libur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_presensis');
    }
};
