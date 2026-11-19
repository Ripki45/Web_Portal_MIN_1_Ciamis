<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProfilMadrasah extends Model
{
    protected $table = 'profil_madrasahs';

    protected $fillable = [
        'judul_hero',
        'subjudul_hero',
        'deskripsi_1',
        'deskripsi_2',
        'deskripsi_3',
        'foto_gedung',
        'caption_foto',
        'identitas_list',
        'visi_teks',
        'misi_list',
        'motto_teks',
        'motto_uraian',
        'sejarah_list',
        'kepsek_masa_list',
        'foto_bersama_guru',
        'judul_guru_staff',
        'subjudul_guru_staff',
        'struktur_guru_staff',
    ];

    protected $casts = [
        'identitas_list' => 'array',
        'misi_list' => 'array',
        'sejarah_list' => 'array',
        'kepsek_masa_list' => 'array',
        'struktur_guru_staff' => 'array',
    ];

    /**
     * URL Foto Gedung / Kampus Madrasah
     */
    public function getFotoGedungUrlAttribute(): string
    {
        if (! empty($this->foto_gedung)) {
            if (str_starts_with($this->foto_gedung, 'frontend/') or str_starts_with($this->foto_gedung, 'backend/') or str_starts_with($this->foto_gedung, 'http')) {
                return asset($this->foto_gedung);
            }

            return asset('storage/'.$this->foto_gedung);
        }

        return asset('frontend/landing/about-img.png');
    }

    /**
     * URL Banner Foto Bersama Guru & Staff
     */
    public function getFotoBersamaGuruUrlAttribute(): string
    {
        if (! empty($this->foto_bersama_guru)) {
            if (str_starts_with($this->foto_bersama_guru, 'frontend/') or str_starts_with($this->foto_bersama_guru, 'backend/') or str_starts_with($this->foto_bersama_guru, 'http')) {
                return asset($this->foto_bersama_guru);
            }

            return asset('storage/'.$this->foto_bersama_guru);
        }

        return asset('frontend/landing/about-img.png');
    }

    public static function ambilData(): self
    {
        self::pastikanTabelSiap();

        return self::first();
    }

    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (! Schema::hasTable('profil_madrasahs')) {
            Schema::create('profil_madrasahs', function (Blueprint $table) {
                $table->id();
                $table->string('judul_hero', 180)->default('Profil MIN 1 Ciamis');
                $table->string('subjudul_hero', 200)->nullable();
                $table->longText('deskripsi_1')->nullable();
                $table->longText('deskripsi_2')->nullable();
                $table->longText('deskripsi_3')->nullable();
                $table->string('foto_gedung', 255)->nullable();
                $table->text('caption_foto')->nullable();
                $table->json('identitas_list')->nullable();
                $table->text('visi_teks')->nullable();
                $table->json('misi_list')->nullable();
                $table->string('motto_teks', 255)->nullable();
                $table->text('motto_uraian')->nullable();
                $table->json('sejarah_list')->nullable();
                $table->json('kepsek_masa_list')->nullable();
                $table->timestamps();
            });
        } else {
            // Auto-tambah kolom motto_teks & motto_uraian jika tabel sudah ada sebelumnya
            if (! Schema::hasColumn('profil_madrasahs', 'motto_teks')) {
                Schema::table('profil_madrasahs', function (Blueprint $table) {
                    $table->string('motto_teks', 255)->nullable()->default('Berakhlak Mulia, Unggul Prestasi, Berwawasan Lingkungan');
                });
            }
            if (! Schema::hasColumn('profil_madrasahs', 'motto_uraian')) {
                Schema::table('profil_madrasahs', function (Blueprint $table) {
                    $table->text('motto_uraian')->nullable();
                });
            }
            if (! Schema::hasColumn('profil_madrasahs', 'foto_bersama_guru')) {
                Schema::table('profil_madrasahs', function (Blueprint $table) {
                    $table->string('foto_bersama_guru', 255)->nullable();
                    $table->string('judul_guru_staff', 180)->nullable()->default('Dewan Guru dan Tenaga Kependidikan MIN 1 Ciamis');
                    $table->string('subjudul_guru_staff', 255)->nullable()->default('Mewujudkan generasi Qur\'ani yang unggul, berakhlak mulia, berprestasi, dan berwawasan lingkungan.');
                });
            }
            if (! Schema::hasColumn('profil_madrasahs', 'struktur_guru_staff')) {
                Schema::table('profil_madrasahs', function (Blueprint $table) {
                    $table->json('struktur_guru_staff')->nullable();
                });
            }
        }

        if (self::count() === 0) {
            self::create([
                'judul_hero' => 'Profil MIN 1 Ciamis',
                'subjudul_hero' => 'Madrasah Maju, Bermutu, Berakhlak Mulia & Berwawasan Lingkungan',
                'deskripsi_1' => 'Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis merupakan lembaga pendidikan dasar formal berciri khas Islam di bawah naungan Kementerian Agama Kabupaten Ciamis yang berlokasi di Desa Maparah, Kecamatan Panjalu.',
                'deskripsi_2' => 'Dengan semangat "Tiada Hari Tanpa Prestasi", MIN 1 Ciamis memadukan kurikulum nasional dengan pembiasaan akhlakul karimah, program Tahfidzul Qur\'an, literasi digital, serta lingkungan belajar yang ramah anak dan asri.',
                'deskripsi_3' => 'Didukung oleh tenaga pendidik profesional dan sinergi komite madrasah, MIN 1 Ciamis terus berinovasi melahirkan generasi Qur\'ani yang cerdas, mandiri, dan berdaya saing.',
                'foto_gedung' => null,
                'caption_foto' => 'Lingkungan belajar yang sejuk, asri, dan kondusif di kampus MIN 1 Ciamis, Kecamatan Panjalu, Kabupaten Ciamis.',
                'identitas_list' => [
                    ['icon' => 'fas fa-school',         'label' => 'Nama Resmi Madrasah', 'nilai' => 'MIN 1 Ciamis'],
                    ['icon' => 'fas fa-id-card',        'label' => 'NPSN',                'nilai' => '60708447'],
                    ['icon' => 'fas fa-hashtag',        'label' => 'NSM',                 'nilai' => '111132070001'],
                    ['icon' => 'fas fa-award',          'label' => 'Akreditasi',          'nilai' => 'A (Unggul)'],
                    ['icon' => 'fas fa-building',       'label' => 'Status Madrasah',     'nilai' => 'Negeri (Kemenag RI)'],
                    ['icon' => 'fas fa-map-marker-alt', 'label' => 'Alamat Lengkap',      'nilai' => 'Jl. Maparah No. 65, Kec. Panjalu, Kab. Ciamis'],
                ],
                'visi_teks' => 'Terwujudnya Peserta Didik yang Beriman dan Bertaqwa, Cerdas, Terampil, Mandiri, Berakhlakul Karimah, serta Berwawasan Lingkungan.',
                'misi_list' => [
                    'Menanamkan keimanan, ketakwaan, dan pembiasaan ibadah harian serta Tahfidzul Qur\'an sejak dini.',
                    'Menyelenggarakan pembelajaran aktif, inovatif, kreatif, efektif, dan menyenangkan berbasis teknologi digital.',
                    'Mengembangkan potensi minat, bakat, dan prestasi peserta didik di bidang akademik, sains, olahraga, maupun seni.',
                    'Mewujudkan lingkungan madrasah yang bersih, hijau, sehat, aman, dan ramah anak.',
                ],
                'motto_teks' => 'Berakhlak Mulia, Unggul Prestasi, Berwawasan Lingkungan',
                'motto_uraian' => 'Semboyan pendorong semangat segenap pendidik, peserta didik, dan komite madrasah untuk mengedepankan adab di atas ilmu, mengejar capaian prestasi tertinggi, serta menjaga kelestarian alam.',
                'sejarah_list' => [
                    [
                        'tahun' => 'Awal Pendirian',
                        'judul' => 'Cikal Bakal Madrasah di Desa Maparah',
                        'uraian' => 'Berdiri sebagai wujud kepedulian masyarakat dan tokoh agama Panjalu dalam menghadirkan pendidikan dasar Islam yang berkualitas bagi putra-putri daerah.',
                    ],
                    [
                        'tahun' => 'Penegerian Madrasah',
                        'judul' => 'Resmi Menjadi Madrasah Ibtidaiyah Negeri',
                        'uraian' => 'Ditetapkan secara resmi oleh Kementerian Agama Republik Indonesia sebagai Madrasah Ibtidaiyah Negeri yang terus berkembang pesat.',
                    ],
                    [
                        'tahun' => 'Era Transformasi Digital',
                        'judul' => 'Madrasah Unggul, Ramah Anak & Digital',
                        'uraian' => 'Meraih predikat Akreditasi A (Unggul) serta mengintegrasikan layanan pembelajaran digital, presensi QR Code, dan program unggulan Tahfidz.',
                    ],
                ],
                'kepsek_masa_list' => [
                    [
                        'nama' => 'Kepala Madrasah Periode I',
                        'periode' => 'Periode Awal',
                        'foto' => '',
                        'is_aktif' => false,
                    ],
                    [
                        'nama' => 'Kepala Madrasah Periode II',
                        'periode' => 'Periode Lanjutan',
                        'foto' => '',
                        'is_aktif' => false,
                    ],
                    [
                        'nama' => 'Drs. H. Ahmad Sobari, M.Pd.I.',
                        'periode' => 'Sekarang',
                        'foto' => '',
                        'is_aktif' => true,
                    ],
                ],
            ]);
        }
    }
}
