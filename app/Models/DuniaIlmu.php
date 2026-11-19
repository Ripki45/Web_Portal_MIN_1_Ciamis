<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DuniaIlmu extends Model
{
    protected $table = 'dunia_ilmus';

    protected $fillable = [
        'judul',
        'slug',
        'kelas',
        'mapel',
        'kategori_label',
        'kategori_filter',
        'warna_gradasi',
        'gambar',
        'tipe_sumber',
        'link_eksternal',
        'nama_file_kode',
        'isi_kode',
        'deskripsi',
        'urutan',
        'views',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Pilihan Warna Gradasi Kartu
     */
    public static function daftarGradasi(): array
    {
        return [
            'dunia-grad-quran' => '🟢 Hijau Zamrud & Emas (Al-Qur\'an / PAI)',
            'dunia-grad-phet' => '🔵 Biru Langit Sains (Matematika / IPAS)',
            'dunia-grad-perpus' => '🟠 Oranye Hangat (Bahasa / Literasi)',
            'dunia-grad-wordwall' => '🔴 Merah Muda Ceria (PJOK / Game)',
            'dunia-grad-kamus' => '🟣 Ungu Royal (Bahasa Arab / Inggris)',
            'dunia-grad-elearning' => '🔷 Biru Indigo (TIK / Informatika)',
            'dunia-grad-video' => '📕 Merah Maroon (Video / Seni Budaya)',
            'dunia-grad-quizizz' => '🔮 Ungu Muda (Kuis Interaktif)',
            'dunia-grad-hadits' => '🌿 Hijau Daun (Akidah / Fikih)',
            'dunia-grad-belajar' => '🟡 Kuning Amber (Tematik / Umum)',
        ];
    }

    /**
     * Ambil Daftar Kelas Langsung dari Tabel Database `kelas`
     */
    public static function ambilDaftarKelas(): array
    {
        $daftar = ['Semua Kelas'];

        if (Schema::hasTable('kelas')) {
            $rows = DB::table('kelas')->orderBy('id', 'asc')->get();
            foreach ($rows as $row) {
                $nama = $row->nama_kelas
                    ?? $row->nama
                    ?? $row->kelas
                    ?? $row->rombel
                    ?? $row->kode_kelas
                    ?? null;

                if (! empty($nama)) {
                    $daftar[] = trim((string) $nama);
                }
            }
        }

        // Jika tabel kelas masih kosong, sediakan tingkat kelas default MI
        if (count($daftar) <= 1) {
            $daftar = array_merge($daftar, [
                'Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6',
            ]);
        }

        return array_values(array_unique($daftar));
    }

    /**
     * Ambil Daftar Mata Pelajaran Langsung dari Tabel Database `mapels` / `mata_pelajarans`
     */
    public static function ambilDaftarMapel(): array
    {
        $namaTabel = null;
        foreach (['mapels', 'mata_pelajarans', 'mapel', 'matapelajarans'] as $kandidat) {
            if (Schema::hasTable($kandidat)) {
                $namaTabel = $kandidat;
                break;
            }
        }

        // Jika belum ada tabel mapel sama sekali, buatkan otomatis di SQLite beserta data awalnya
        if (is_null($namaTabel)) {
            Schema::create('mapels', function (Blueprint $table) {
                $table->id();
                $table->string('kode_mapel', 30)->nullable();
                $table->string('nama_mapel', 120);
                $table->string('kelompok', 60)->default('Umum');
                $table->timestamps();
            });

            $mapelAwal = [
                ['kode' => 'QH',   'nama' => 'Al-Qur\'an Hadits',              'kelompok' => 'PAI'],
                ['kode' => 'AA',   'nama' => 'Akidah Akhlak',                  'kelompok' => 'PAI'],
                ['kode' => 'FKH',  'nama' => 'Fikih',                          'kelompok' => 'PAI'],
                ['kode' => 'SKI',  'nama' => 'Sejarah Kebudayaan Islam (SKI)', 'kelompok' => 'PAI'],
                ['kode' => 'BAR',  'nama' => 'Bahasa Arab',                    'kelompok' => 'PAI'],
                ['kode' => 'MTK',  'nama' => 'Matematika',                     'kelompok' => 'Umum'],
                ['kode' => 'IPAS', 'nama' => 'IPAS (Sains & Sosial)',          'kelompok' => 'Umum'],
                ['kode' => 'BIND', 'nama' => 'Bahasa Indonesia',               'kelompok' => 'Umum'],
                ['kode' => 'PJOK', 'nama' => 'PJOK (Olahraga)',                'kelompok' => 'Umum'],
                ['kode' => 'PPKN', 'nama' => 'Pendidikan Pancasila',           'kelompok' => 'Umum'],
                ['kode' => 'SBDP', 'nama' => 'Seni Budaya & Prakarya',         'kelompok' => 'Umum'],
                ['kode' => 'BING', 'nama' => 'Bahasa Inggris',                 'kelompok' => 'Mulok'],
                ['kode' => 'BSUN', 'nama' => 'Bahasa Sunda',                   'kelompok' => 'Mulok'],
                ['kode' => 'TIK',  'nama' => 'TIK / Informatika',              'kelompok' => 'Mulok'],
            ];

            foreach ($mapelAwal as $m) {
                DB::table('mapels')->insert([
                    'kode_mapel' => $m['kode'],
                    'nama_mapel' => $m['nama'],
                    'kelompok' => $m['kelompok'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $namaTabel = 'mapels';
        }

        $daftar = [];
        $rows = DB::table($namaTabel)->orderBy('id', 'asc')->get();
        foreach ($rows as $row) {
            $nama = $row->nama_mapel
                ?? $row->nama
                ?? $row->mata_pelajaran
                ?? $row->mapel
                ?? null;

            if (! empty($nama)) {
                $daftar[] = trim((string) $nama);
            }
        }

        if (empty($daftar)) {
            $daftar = [
                'PJOK (Olahraga)', 'Matematika', 'IPAS (Sains & Sosial)',
                'Bahasa Indonesia', 'Al-Qur\'an Hadits', 'Akidah Akhlak',
                'Fikih', 'Bahasa Arab', 'Bahasa Inggris', 'TIK / Informatika',
            ];
        }

        $daftar[] = 'Umum / Lintas Mapel';

        return array_values(array_unique($daftar));
    }

    /**
     * URL Tujuan Saat Kartu Diklik
     */
    public function getUrlTujuanAttribute(): string
    {
        if ($this->tipe_sumber === 'kode_interaktif') {
            return route('dunia-ilmu.play', $this->slug);
        }

        return $this->link_eksternal ?: '#';
    }

    /**
     * URL Ikon / Thumbnail Kartu
     */
    public function getGambarUrlAttribute(): string
    {
        if (! empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'frontend/') or str_starts_with($this->gambar, 'backend/') or str_starts_with($this->gambar, 'http')) {
                return asset($this->gambar);
            }

            return asset('storage/'.$this->gambar);
        }

        return asset('backend/assets/images/icon_min.png');
    }

    public static function pastikanTabelSiap(): void
    {
        if (! Schema::hasTable('dunia_ilmus')) {
            Schema::create('dunia_ilmus', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 160);
                $table->string('slug', 190)->unique();
                $table->string('kelas', 80)->default('Semua Kelas');
                $table->string('mapel', 120)->default('Umum / Lintas Mapel');
                $table->string('kategori_label', 100)->default('Media Interaktif');
                $table->string('kategori_filter', 40)->default('game');
                $table->string('warna_gradasi', 50)->default('dunia-grad-phet');
                $table->string('gambar', 255)->nullable();
                $table->string('tipe_sumber', 30)->default('link');
                $table->string('link_eksternal', 255)->nullable();
                $table->string('nama_file_kode', 150)->nullable();
                $table->longText('isi_kode')->nullable();
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(1);
                $table->unsignedBigInteger('views')->default(12);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada dari langkah sebelumnya, tambahkan kolom kelas & mapel tanpa menghapus data!
            if (! Schema::hasColumn('dunia_ilmus', 'kelas')) {
                Schema::table('dunia_ilmus', function (Blueprint $table) {
                    $table->string('kelas', 80)->default('Semua Kelas')->after('slug');
                });
            }
            if (! Schema::hasColumn('dunia_ilmus', 'mapel')) {
                Schema::table('dunia_ilmus', function (Blueprint $table) {
                    $table->string('mapel', 120)->default('PJOK (Olahraga)')->after('kelas');
                });
            }
        }
    }
}
