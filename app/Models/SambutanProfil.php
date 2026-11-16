<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SambutanProfil extends Model
{
    protected $table = 'sambutan_profils';

    protected $fillable = [
        'nama_kepsek',
        'jabatan_kepsek',
        'nip_kepsek',
        'foto_kepsek',
        'sambutan_kutipan',
        'sambutan_isi',
        'profil_kutipan',
        'profil_isi',
        'profil_poin',
        'visi_kutipan',
        'visi_isi',
        'misi_kutipan',
        'misi_isi',
        'misi_poin',
        'motto_kutipan',
        'motto_isi',
        'total_prestasi',
    ];

    protected $casts = [
        'profil_poin' => 'array',
        'misi_poin'   => 'array',
    ];

    /**
     * Daftar Pilihan Ikon FontAwesome untuk 4 Poin Profil & Misi
     */
    public static function daftarIkon(): array
    {
        return [
            'fas fa-check-circle'       => '✓ Centang Hijau (Standar)',
            'fas fa-star'               => '★ Bintang Keunggulan',
            'fas fa-quran'              => '📖 Al-Qur\'an / Tahfidz',
            'fas fa-mosque'             => '🕌 Masjid / Keislaman',
            'fas fa-award'              => '🎖️ Piagam / Akreditasi',
            'fas fa-trophy'             => '🏆 Piala / Prestasi Juara',
            'fas fa-laptop-code'        => '💻 Laptop / Inovasi Digital',
            'fas fa-child'              => '🧒 Anak / Ramah Anak',
            'fas fa-leaf'               => '🌿 Daun / Lingkungan Adiwiyata',
            'fas fa-book-reader'        => '📚 Literasi / Gemar Membaca',
            'fas fa-flask'              => '🧪 Sains & Riset (KSM)',
            'fas fa-chalkboard-teacher' => '👨‍🏫 Guru Profesional',
            'fas fa-shield-alt'         => '🛡️ Keamanan / Anti-Bullying',
            'fas fa-globe-asia'         => '🌏 Wawasan Global',
            'fas fa-handshake'          => '🤝 Sinergi & Karakter',
            'fas fa-graduation-cap'     => '🎓 Akademik & Lulusan',
        ];
    }

    /**
     * URL Foto Kepala Madrasah Otomatis
     */
    public function getFotoKepsekUrlAttribute(): string
    {
        if (!empty($this->foto_kepsek)) {
            if (str_starts_with($this->foto_kepsek, 'frontend/') or str_starts_with($this->foto_kepsek, 'http')) {
                return asset($this->foto_kepsek);
            }
            return asset('storage/' . $this->foto_kepsek);
        }
        return asset('frontend/landing/about-img.png');
    }

    /**
     * Ambil 1 baris data aktif (Auto-Create tabel & isi awal jika belum ada)
     */
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

        if (!Schema::hasTable('sambutan_profils')) {
            Schema::create('sambutan_profils', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kepsek', 150)->default('Drs. H. Ahmad Sobari, M.Pd.I.');
                $table->string('jabatan_kepsek', 120)->default('Kepala MIN 1 Ciamis');
                $table->string('nip_kepsek', 60)->nullable()->default('19690412 199403 1 003');
                $table->string('foto_kepsek', 255)->nullable();

                // Tab 1: Sambutan Kepala Madrasah
                $table->text('sambutan_kutipan')->nullable();
                $table->longText('sambutan_isi')->nullable();

                // Tab 2: Profil Madrasah + 4 Poin JSON
                $table->text('profil_kutipan')->nullable();
                $table->longText('profil_isi')->nullable();
                $table->json('profil_poin')->nullable();

                // Tab 3: Visi Madrasah
                $table->text('visi_kutipan')->nullable();
                $table->longText('visi_isi')->nullable();

                // Tab 4: Misi Madrasah + 4 Poin JSON
                $table->text('misi_kutipan')->nullable();
                $table->longText('misi_isi')->nullable();
                $table->json('misi_poin')->nullable();

                // Tab 5: Motto Madrasah & Total Prestasi
                $table->text('motto_kutipan')->nullable();
                $table->longText('motto_isi')->nullable();
                $table->integer('total_prestasi')->default(237);

                $table->timestamps();
            });
        }

        if (self::count() === 0) {
            self::create([
                'nama_kepsek'      => 'Drs. H. Ahmad Sobari, M.Pd.I.',
                'jabatan_kepsek'   => 'Kepala MIN 1 Ciamis',
                'nip_kepsek'       => '19690412 199403 1 003',
                'foto_kepsek'      => null,

                'sambutan_kutipan' => 'Membangun Generasi Qur\'ani yang Berakhlak Mulia, Cerdas, dan Berdaya Saing Global.',
                'sambutan_isi'     => "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\nPuji syukur kita panjatkan ke hadirat Allah SWT. Selamat datang di Portal Resmi Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis. Kami berkomitmen menghadirkan lingkungan belajar yang aman, religius, menyenangkan, dan berbasis teknologi untuk mengantarkan putra-putri terbaik meraih prestasi tertinggi dengan landasan akhlakul karimah.",

                'profil_kutipan'   => 'Madrasah Maju, Bermutu, Berakhlak Mulia',
                'profil_isi'       => "Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis adalah institusi pendidikan dasar formal berciri khas Islam di bawah naungan Kementerian Agama Kabupaten Ciamis. Beralamat di Jl. Maparah No. 65, Kecamatan Panjalu, MIN 1 Ciamis telah terakreditasi A (Unggul) dan memadukan kurikulum nasional dengan pembiasaan adab Islami, tahfidzul Qur'an, serta kepedulian lingkungan.",
                'profil_poin'      => [
                    ['icon' => 'fas fa-check-circle', 'text' => 'NPSN 60708447'],
                    ['icon' => 'fas fa-award',        'text' => 'Akreditasi A (Unggul)'],
                    ['icon' => 'fas fa-child',        'text' => 'Sekolah Ramah Anak'],
                    ['icon' => 'fas fa-quran',        'text' => 'Program Tahfidz Juz 30'],
                ],

                'visi_kutipan'     => 'Terwujudnya Peserta Didik yang Beriman dan Bertaqwa, Cerdas, Terampil, Mandiri, Berakhlakul Karimah, serta Berwawasan Lingkungan.',
                'visi_isi'         => 'Visi ini merupakan komitmen luhur MIN 1 Ciamis dalam melahirkan generasi penerus bangsa yang kokoh akidahnya, cemerlang pemikirannya, terampil keterampilannya, dan memiliki kepedulian tinggi terhadap kelestarian alam ciptaan Allah SWT.',

                'misi_kutipan'     => 'Membangun Fondasi Keislaman, Keilmuan, dan Karakter Kebangsaan Sejak Usia Dini.',
                'misi_isi'         => 'Menyelenggarakan pembiasaan ibadah dan tahfidz Al-Qur\'an setiap hari, melaksanakan pembelajaran aktif berbasis teknologi digital, membina bakat akademik maupun non-akademik menuju kejuaraan, serta mewujudkan lingkungan madrasah hijau yang bersih dan sehat.',
                'misi_poin'        => [
                    ['icon' => 'fas fa-star',        'text' => 'Pembiasaan Ibadah & Tahfidz'],
                    ['icon' => 'fas fa-laptop-code', 'text' => 'Pembelajaran Aktif & Inovatif'],
                    ['icon' => 'fas fa-trophy',      'text' => 'Pembinaan Prestasi Sains & Seni'],
                    ['icon' => 'fas fa-leaf',        'text' => 'Madrasah Hijau & Bebas Sampah'],
                ],

                'motto_kutipan'    => 'Berakhlak Mulia, Unggul Prestasi, Berwawasan Lingkungan',
                'motto_isi'        => 'Sebuah semboyan pendorong semangat segenap pendidik, peserta didik, dan komite madrasah untuk senantiasa mengedepankan adab di atas ilmu, mengejar capaian prestasi tertinggi, dan menjaga keharmonisan dengan alam semesta.',
                'total_prestasi'   => 237,
            ]);
        }
    }
}