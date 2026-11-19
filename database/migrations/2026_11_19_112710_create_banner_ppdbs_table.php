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
        if (! Schema::hasTable('banner_ppdbs')) {
            Schema::create('banner_ppdbs', function (Blueprint $table) {
                $table->id();
                // Banner Section
                $table->string('judul', 200)->default('Penerimaan Peserta Didik Baru (PPDB) MIN 1 Ciamis');
                $table->string('subjudul', 255)->default("Membentuk Generasi Qur'ani, Berakhlak Mulia & Unggul Prestasi");
                $table->string('gambar', 255)->nullable();
                $table->string('tipe_link', 30)->default('popup'); // 'popup' atau 'url'
                $table->string('link_url', 255)->nullable()->default('#ppdb');
                $table->boolean('is_active')->default(true);

                // Popup Modal Section
                $table->boolean('is_active_popup')->default(true);
                $table->boolean('auto_popup')->default(false);
                $table->string('popup_judul', 200)->default('Informasi & Pendaftaran PPDB MIN 1 Ciamis');
                $table->string('popup_badge', 100)->default('PPDB ONLINE T.A 2026/2027');
                $table->string('popup_gambar', 255)->nullable();
                $table->longText('popup_konten')->nullable();
                $table->string('popup_tombol_teks', 100)->default('Daftar Online Sekarang');
                $table->string('popup_tombol_url', 255)->default('https://wa.me/6282215650442?text=Halo%20Panitia%20PPDB%20MIN%201%20Ciamis');
                $table->string('popup_wa_nomor', 50)->default('082215650442');
                $table->string('popup_wa_teks', 255)->default('Halo Panitia PPDB MIN 1 Ciamis, saya ingin menanyakan informasi pendaftaran siswa baru.');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_ppdbs');
    }
};
