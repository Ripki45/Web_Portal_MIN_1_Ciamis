<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Halaman extends Model
{
    protected $table = 'halamans';

    protected $fillable = [
        'nav_menu_id',
        'judul',
        'slug',
        'kategori',
        'kutipan',
        'isi_konten',
        'gambar',
        'penulis',
        'views',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function navMenu()
    {
        return $this->belongsTo(NavMenu::class, 'nav_menu_id');
    }

    /**
     * Hitung estimasi waktu baca otomatis (seperti "3 mins read" di single-3.html)
     */
    public function getWaktuBacaAttribute(): string
    {
        $jumlahKata = str_word_count(strip_tags((string) $this->isi_konten));
        $menit = max(1, (int) ceil($jumlahKata / 180));
        return $menit . ' menit baca';
    }

    /**
     * Pastikan tabel halamans tersedia & buat contoh halaman Visi-Misi otomatis
     */
    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        NavMenu::pastikanTabelSiap();

        if (!Schema::hasTable('halamans')) {
            Schema::create('halamans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nav_menu_id')->nullable();
                $table->string('judul', 200);
                $table->string('slug', 220)->unique();
                $table->string('kategori', 100)->default('Profil Madrasah');
                $table->text('kutipan')->nullable();
                $table->longText('isi_konten')->nullable();
                $table->string('gambar', 255)->nullable();
                $table->string('penulis', 100)->default('Humas MIN 1 Ciamis');
                $table->unsignedBigInteger('views')->default(12);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Cari Induk Menu "Profil Madrasah" untuk dihubungkan dengan Anak Menu "Visi & Misi"
            $indukProfil = NavMenu::where('nama_menu', 'like', '%Profil%')->where('level', 1)->first();

            $menuVisiMisi = NavMenu::firstOrCreate(
                ['nama_menu' => 'Visi & Misi', 'parent_id' => $indukProfil->id ?? null],
                [
                    'url'       => '/halaman/visi-misi',
                    'level'     => $indukProfil ? 2 : 1,
                    'urutan'    => 1,
                    'target'    => '_self',
                    'is_active' => true,
                ]
            );

            self::create([
                'nav_menu_id' => $menuVisiMisi->id,
                'judul'       => 'Visi, Misi, dan Tujuan MIN 1 Ciamis',
                'slug'        => 'visi-misi',
                'kategori'    => 'Profil Madrasah',
                'kutipan'     => 'Terwujudnya Peserta Didik yang Beriman, Berakhlak Mulia, Unggul dalam Prestasi Akademik maupun Non-Akademik, serta Berwawasan Lingkungan dan Digital.',
                'isi_konten'  => '
                    <h4>Misi MIN 1 Ciamis</h4>
                    <p>Untuk mewujudkan visi madrasah tersebut, MIN 1 Ciamis menyelenggarakan misi pendidikan sebagai berikut:</p>
                    <ul>
                        <li><strong>Penguatan Karakter Islami:</strong> Menanamkan nilai-nilai keimanan, ketakwaan, serta pembiasaan ibadah dan akhlakul karimah dalam kehidupan sehari-hari di madrasah.</li>
                        <li><strong>Pembelajaran Inovatif & Digital:</strong> Melaksanakan pembelajaran aktif, kreatif, efektif, dan menyenangkan berbasis literasi serta teknologi informasi.</li>
                        <li><strong>Pengembangan Bakat & Prestasi:</strong> Membina potensi akademik, sains, olahraga, dan seni siswa melalui kegiatan ekstrakurikuler yang terarah.</li>
                        <li><strong>Lingkungan Madrasah Sehat & Aman:</strong> Mewujudkan budaya madrasah yang bersih, disiplin, ramah anak, dan kolaboratif bersama orang tua serta masyarakat.</li>
                    </ul>
                    <h4>Tujuan Pendidikan Madrasah</h4>
                    <p>Menghasilkan lulusan madrasah ibtidaiyah yang hafal juz amma, terampil berbahasa dan berhitung, menguasai dasar teknologi digital, serta siap melanjutkan pendidikan ke jenjang yang lebih tinggi dengan karakter yang tangguh.</p>
                ',
                'penulis'     => 'Tim Kurikulum & Humas',
                'views'       => 48,
                'is_active'   => true,
            ]);
        }
    }
}