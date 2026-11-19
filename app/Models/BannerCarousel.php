<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BannerCarousel extends Model
{
    protected $table = 'banner_carousels';

    protected $fillable = [
        'tipe_layout',
        'aksara_kecil',
        'aksara_besar',
        'teks_domain',
        'teks_badge',
        'keterangan_1',
        'keterangan_2',
        'tampilkan_stiker',
        'kategori',
        'judul',
        'keterangan',
        'gambar',
        'link_url',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tampilkan_stiker' => 'boolean',
    ];

    /**
     * URL Gambar otomatis
     */
    public function getGambarUrlAttribute(): string
    {
        if (! empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'frontend/') or str_starts_with($this->gambar, 'backend/') or str_starts_with($this->gambar, 'http')) {
                return asset($this->gambar);
            }

            return asset('storage/'.$this->gambar);
        }

        return asset('frontend/landing/hero-collage-students.png');
    }

    /**
     * Pastikan tabel & kolom tipe_layout siap di SQLite tanpa menghapus data lama
     */
    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (! Schema::hasTable('banner_carousels')) {
            Schema::create('banner_carousels', function (Blueprint $table) {
                $table->id();
                $table->string('tipe_layout', 30)->default('tanpa_foto'); // 'tanpa_foto' atau 'dengan_foto'
                $table->string('aksara_kecil', 120)->default('Selamat datang di');
                $table->string('aksara_besar', 150)->default('MIN 1 CIAMIS');
                $table->string('teks_domain', 180)->nullable()->default('w w w . m i n 1 c i a m i s . s c h . i d');
                $table->string('teks_badge', 40)->nullable();
                $table->string('keterangan_1', 220)->nullable();
                $table->string('keterangan_2', 220)->nullable();
                $table->boolean('tampilkan_stiker')->default(true);
                $table->string('kategori', 60)->default('Hero Utama');
                $table->string('judul', 180)->default('MIN 1 CIAMIS');
                $table->text('keterangan')->nullable();
                $table->string('gambar', 255)->nullable();
                $table->string('link_url', 255)->default('#');
                $table->integer('urutan')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            $kolomTambahan = [
                'tipe_layout' => function (Blueprint $table) {
                    $table->string('tipe_layout', 30)->default('dengan_foto');
                },
                'aksara_kecil' => function (Blueprint $table) {
                    $table->string('aksara_kecil', 120)->nullable();
                },
                'aksara_besar' => function (Blueprint $table) {
                    $table->string('aksara_besar', 150)->nullable();
                },
                'teks_domain' => function (Blueprint $table) {
                    $table->string('teks_domain', 180)->nullable();
                },
                'teks_badge' => function (Blueprint $table) {
                    $table->string('teks_badge', 40)->nullable();
                },
                'keterangan_1' => function (Blueprint $table) {
                    $table->string('keterangan_1', 220)->nullable();
                },
                'keterangan_2' => function (Blueprint $table) {
                    $table->string('keterangan_2', 220)->nullable();
                },
                'tampilkan_stiker' => function (Blueprint $table) {
                    $table->boolean('tampilkan_stiker')->default(true);
                },
            ];

            foreach ($kolomTambahan as $namaKolom => $callback) {
                if (! Schema::hasColumn('banner_carousels', $namaKolom)) {
                    Schema::table('banner_carousels', $callback);
                }
            }
        }

        // Pastikan Slide urutan ke-1 ("Selamat datang di MIN 1 CIAMIS") otomatis memakai layout 'tanpa_foto' (Teks Tengah Besar)
        $slidePertama = self::orderBy('urutan', 'asc')->first();
        if ($slidePertama && $slidePertama->aksara_besar === 'MIN 1 CIAMIS' && $slidePertama->tipe_layout !== 'tanpa_foto') {
            $slidePertama->update(['tipe_layout' => 'tanpa_foto']);
        }
    }
}
