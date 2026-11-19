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
        if (! Schema::hasTable('layanan_cepats')) {
            Schema::create('layanan_cepats', function (Blueprint $table) {
                $table->id();
                $table->string('nama_layanan', 150);
                $table->string('icon', 80)->default('fas fa-link');
                $table->string('link_url', 255);
                $table->string('target', 20)->default('_blank'); // '_blank' atau '_self'
                $table->integer('urutan')->default(1);
                $table->boolean('is_active')->default(true);
                $table->string('keterangan', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_cepats');
    }
};
