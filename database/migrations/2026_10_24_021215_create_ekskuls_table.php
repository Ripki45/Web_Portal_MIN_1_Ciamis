<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekskuls', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ekskul', 100);
            $table->foreignId('pembina_id')->nullable()->constrained('gtks')->onDelete('set null');
            $table->string('hari_latihan', 50)->nullable();
            $table->string('jam_latihan', 50)->nullable();
            $table->string('status', 20)->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekskuls');
    }
};