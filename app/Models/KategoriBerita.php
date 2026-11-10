<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class KategoriBerita extends Model
{
    protected $table = 'kategori_beritas';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'warna_badge',
    ];

    /**
     * Hitung jumlah berita di kategori ini
     */
    public function beritas()
    {
        return $this->hasMany(Berita::class, 'kategori', 'nama_kategori');
    }

    /**
     * Pastikan tabel kategori_beritas siap di SQLite & isi kategori awal
     */
    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (!Schema::hasTable('kategori_beritas')) {
            Schema::create('kategori_beritas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 80)->unique();
                $table->string('slug', 100)->unique();
                $table->string('warna_badge', 20)->default('bg-1'); // bg-1, bg-2, bg-3, bg-5 (Sesuai CSS Magzin)
                $table->timestamps();
            });

            $kategoriAwal = [
                ['nama' => 'Kegiatan Siswa',      'warna' => 'bg-3'],
                ['nama' => 'Prestasi Madrasah',   'warna' => 'bg-2'],
                ['nama' => 'Inovasi Pendidikan',  'warna' => 'bg-1'],
                ['nama' => 'Ekstrakurikuler',     'warna' => 'bg-5'],
                ['nama' => 'Pengumuman Akademik', 'warna' => 'bg-2'],
                ['nama' => 'Keagamaan & Tahfidz', 'warna' => 'bg-3'],
            ];

            foreach ($kategoriAwal as $item) {
                self::create([
                    'nama_kategori' => $item['nama'],
                    'slug'          => Str::slug($item['nama']),
                    'warna_badge'   => $item['warna'],
                ]);
            }
        }
    }
}