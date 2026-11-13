<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BannerCarousel extends Model
{
    protected $table = 'banner_carousels';

    protected $fillable = [
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
    ];

    /**
     * URL Gambar otomatis (mendukung gambar upload & gambar bawaan template)
     */
    public function getGambarUrlAttribute(): string
    {
        if (!empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'frontend/') or str_starts_with($this->gambar, 'http')) {
                return asset($this->gambar);
            }
            return asset('storage/' . $this->gambar);
        }
        return asset('frontend/assets/imgs/page/bg-home1-sec9-1.png');
    }

    /**
     * Pastikan tabel banner_carousels siap di SQLite & isi 4 slide awal jika kosong
     */
    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (!Schema::hasTable('banner_carousels')) {
            Schema::create('banner_carousels', function (Blueprint $table) {
                $table->id();
                $table->string('kategori', 60)->default('Kegiatan Siswa');
                $table->string('judul', 180);
                $table->text('keterangan')->nullable();
                $table->string('gambar', 255)->nullable();
                $table->string('link_url', 255)->default('#');
                $table->integer('urutan')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            $defaultSlides = [
                [
                    'kategori'   => 'Kegiatan Siswa',
                    'judul'      => 'Gerakan Literasi Pagi Menghidupkan Budaya Baca',
                    'keterangan' => 'Pembiasaan membaca, diskusi singkat, dan dokumentasi karya siswa MIN 1 Ciamis setiap pagi sebelum jam pelajaran dimulai.',
                    'gambar'     => 'frontend/assets/imgs/page/bg-home1-sec9-1.png',
                    'link_url'   => '#berita-terbaru',
                    'urutan'     => 1,
                ],
                [
                    'kategori'   => 'Program Madrasah',
                    'judul'      => 'Pembelajaran Berbasis Teknologi & Karakter Islami',
                    'keterangan' => 'Integrasi kurikulum madrasah dengan inovasi pembelajaran digital interaktif yang aman dan menyenangkan.',
                    'gambar'     => 'frontend/assets/imgs/page/bg-home1-sec9-2.png',
                    'link_url'   => '#program-unggulan',
                    'urutan'     => 2,
                ],
                [
                    'kategori'   => 'Ekstrakurikuler',
                    'judul'      => 'Pembinaan Pramuka, Olahraga & Seni Budaya',
                    'keterangan' => 'Membangun kedisiplinan, kemandirian, kepemimpinan, dan sportivitas peserta didik melalui ragam ekstrakurikuler pilihan.',
                    'gambar'     => 'frontend/assets/imgs/page/bg-home1-sec9-3.png',
                    'link_url'   => '#ekstrakurikuler',
                    'urutan'     => 3,
                ],
                [
                    'kategori'   => 'Prestasi Madrasah',
                    'judul'      => 'Raih Prestasi Akademik & Kompetisi Sains Madrasah',
                    'keterangan' => 'Komitmen pendampingan intensif bagi siswa dalam ajang olimpiade tingkat kabupaten, provinsi, hingga nasional.',
                    'gambar'     => 'frontend/assets/imgs/page/bg-home1-sec9-2.png',
                    'link_url'   => '#prestasi',
                    'urutan'     => 4,
                ],
            ];

            foreach ($defaultSlides as $slide) {
                self::create($slide);
            }
        }
    }
}