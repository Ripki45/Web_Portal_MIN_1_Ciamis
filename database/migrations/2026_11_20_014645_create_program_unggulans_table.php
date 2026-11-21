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
        Schema::create('program_unggulans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 160);
            $table->string('slug', 180)->unique();
            $table->string('kategori_badge', 80)->default('Program Unggulan');
            $table->string('icon', 80)->default('fas fa-star');
            $table->text('ringkasan')->nullable();
            $table->string('gambar_sampul', 255)->nullable();
            $table->text('kutipan')->nullable();
            $table->text('keunggulan_poin')->nullable();
            $table->longText('isi_konten')->nullable();
            $table->string('penulis', 100)->default('Tim Kurikulum MIN 1 Ciamis');
            $table->integer('urutan')->default(1);
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_unggulans');
    }
};
