<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProgramUnggulan extends Model
{
    protected $table = 'program_unggulans';

    protected $fillable = [
        'judul',
        'slug',
        'kategori_badge',
        'icon',
        'ringkasan',
        'gambar_sampul',
        'kutipan',
        'mini_features',
        'keunggulan_poin',
        'pilar_list',
        'kegiatan_badge',
        'kegiatan_deskripsi',
        'kegiatan_judul',
        'kegiatan_list',
        'isi_konten',
        'penulis',
        'urutan',
        'views',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'views' => 'integer',
        'keunggulan_poin' => 'array',
        'mini_features' => 'array',
        'pilar_list' => 'array',
        'kegiatan_list' => 'array',
    ];

    /**
     * Daftar rekomendasi ikon Font Awesome untuk program unggulan madrasah
     *
     * @return array<string, string>
     */
    public static function daftarIconRekomendasi(): array
    {
        return [
            'fas fa-child-reaching' => 'Ramah Anak & Inklusi',
            'fas fa-quran' => 'Tahfidz & Karakter Qur\'ani',
            'fas fa-flask' => 'Sains & Riset Madrasah (KSM)',
            'fas fa-laptop-code' => 'Digitalisasi & Smart Madrasah',
            'fas fa-leaf' => 'Adiwiyata & Wawasan Lingkungan',
            'fas fa-book-reader' => 'Literasi & Kebahasaan',
            'fas fa-award' => 'Prestasi & Inkubasi Talenta',
            'fas fa-heart' => 'Budi Pekerti & Pembiasaan Akhlak',
            'fas fa-hands-holding-child' => 'Parenting & Sinergi Keluarga',
            'fas fa-star' => 'Program Unggulan Umum',
        ];
    }

    /**
     * URL Gambar Sampul Program
     */
    public function getGambarUrlAttribute(): string
    {
        if (! empty($this->gambar_sampul)) {
            if (str_starts_with($this->gambar_sampul, 'frontend/') || str_starts_with($this->gambar_sampul, 'backend/') || str_starts_with($this->gambar_sampul, 'http')) {
                return asset($this->gambar_sampul);
            }

            return asset('storage/'.$this->gambar_sampul);
        }

        return asset('frontend/assets/imgs/page/img-4.png');
    }

    /**
     * Estimasi waktu baca konten artikel
     */
    public function getWaktuBacaAttribute(): string
    {
        $jumlahKata = str_word_count(strip_tags((string) $this->isi_konten));
        $menit = max(1, (int) ceil($jumlahKata / 180));

        return $menit.' menit baca';
    }

    /**
     * Dapatkan daftar poin-poin keunggulan dalam format array
     *
     * @return array<int, string>
     */
    public function getPoinListAttribute(): array
    {
        if (is_array($this->keunggulan_poin)) {
            return array_filter(array_map('trim', $this->keunggulan_poin));
        }

        if (is_string($this->keunggulan_poin)) {
            $decoded = json_decode($this->keunggulan_poin, true);
            if (is_array($decoded)) {
                return array_filter(array_map('trim', $decoded));
            }

            // Jika dipisah dengan baris baru
            $lines = explode("\n", str_replace("\r", '', $this->keunggulan_poin));

            return array_filter(array_map('trim', $lines));
        }

        return [];
    }

    /**
     * Mengambil 2 mini feature cards di Section 1
     *
     * @return array<int, array{icon: string, title: string, desc: string}>
     */
    public function getMiniFeaturesListAttribute(): array
    {
        if (is_array($this->mini_features) && count($this->mini_features) > 0) {
            $cleaned = [];
            foreach (array_slice($this->mini_features, 0, 2) as $item) {
                if (! empty($item['title']) || ! empty($item['desc'])) {
                    $cleaned[] = [
                        'icon' => ! empty($item['icon']) ? $item['icon'] : 'fas fa-check-circle',
                        'title' => $item['title'] ?? 'Keunggulan Program',
                        'desc' => $item['desc'] ?? '',
                    ];
                }
            }
            if (count($cleaned) === 2) {
                return $cleaned;
            }
        }

        $poin = $this->poin_list;

        return [
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Aman & Anti-Bullying',
                'desc' => $poin[0] ?? 'Perlindungan anak dari segala bentuk kekerasan dan pembinaan dengan keteladanan kasih sayang.',
            ],
            [
                'icon' => 'fas fa-hands-holding-child',
                'title' => 'Sinergi Parenting Day',
                'desc' => $poin[1] ?? 'Kolaborasi madrasah dan orang tua dalam tarbiyah anak cerdas berkarakter mulia.',
            ],
        ];
    }

    /**
     * Mengambil 4 Pilar Program di Section 2
     *
     * @return array<int, array{icon: string, title: string, desc: string}>
     */
    public function getPilarListFormattedAttribute(): array
    {
        if (is_array($this->pilar_list) && count($this->pilar_list) > 0) {
            $cleaned = [];
            foreach (array_slice($this->pilar_list, 0, 4) as $index => $item) {
                if (! empty($item['title']) || ! empty($item['desc'])) {
                    $cleaned[] = [
                        'icon' => ! empty($item['icon']) ? $item['icon'] : 'fas fa-award',
                        'title' => $item['title'] ?? 'Pilar '.($index + 1),
                        'desc' => $item['desc'] ?? '',
                    ];
                }
            }
            if (count($cleaned) >= 1) {
                return $cleaned;
            }
        }

        // Default 4 Pilar Teladan
        return [
            [
                'icon' => 'fas fa-smile-beam',
                'title' => '1. Zero Bullying & Bebas Kekerasan',
                'desc' => 'Menjamin suasana belajar yang nyaman tanpa kekerasan fisik, verbal, maupun psikologis melalui bimbingan konseling sahabat anak.',
            ],
            [
                'icon' => 'fas fa-chalkboard-teacher',
                'title' => '2. Guru Sahabat Anak & Inklusi',
                'desc' => 'Pendidik berperan sebagai fasilitator yang mengayomi, memahami keunikan tiap anak, dan mengedepankan pendekatan kasih sayang.',
            ],
            [
                'icon' => 'fas fa-apple-alt',
                'title' => '3. Sarana Bersih, Sehat & Ramah',
                'desc' => 'Fasilitas madrasah yang higienis, kantin sehat terverifikasi, sanitasi ramah anak, dan ruang belajar hijau berbasis lingkungan.',
            ],
            [
                'icon' => 'fas fa-users',
                'title' => '4. Sinergi Tiga Pilar Terpadu',
                'desc' => 'Kerjasama harmonis antara madrasah, orang tua (komite), dan masyarakat sekitar dalam menjaga tumbuh kembang santri.',
            ],
        ];
    }

    /**
     * Judul Section 3 Kegiatan
     */
    public function getKegiatanJudulFormattedAttribute(): string
    {
        return $this->kegiatan_judul ?: 'Kegiatan & Pembiasaan Karakter '.$this->judul;
    }

    /**
     * Badge Kategori Section 3 Kegiatan
     */
    public function getKegiatanBadgeFormattedAttribute(): string
    {
        return $this->kegiatan_badge ?: 'Program Aksi & Pembiasaan';
    }

    /**
     * Deskripsi subtitle Section 3 Kegiatan
     */
    public function getKegiatanDeskripsiFormattedAttribute(): string
    {
        return $this->kegiatan_deskripsi ?: 'Dokumentasi agenda, kegiatan, dan pembiasaan karakter peserta didik.';
    }

    /**
     * Mengambil 6 kartu kegiatan aktivitas & edukasi di Section 3
     *
     * @return array<int, array{badge: string, title: string, desc: string}>
     */
    public function getKegiatanListDataAttribute(): array
    {
        if (is_array($this->kegiatan_list) && count($this->kegiatan_list) > 0) {
            $cleaned = [];
            foreach ($this->kegiatan_list as $item) {
                if (! empty($item['title']) || ! empty($item['desc'])) {
                    $cleaned[] = [
                        'badge' => $item['badge'] ?? 'Kegiatan',
                        'title' => $item['title'] ?? 'Kegiatan Program',
                        'desc' => $item['desc'] ?? '',
                    ];
                }
            }
            if (count($cleaned) >= 1) {
                return $cleaned;
            }
        }

        // Default 6 Kartu Kegiatan Teladan
        return [
            [
                'badge' => 'Parenting Day',
                'title' => 'Kolaborasi Madrasah & Orang Tua dalam Tarbiyah Anak',
                'desc' => 'Pembekalan komprehensif bagi wali murid bersama narasumber tentang pengasuhan holistik berbasis nilai-nilai Islam.',
            ],
            [
                'badge' => 'Pengasuhan Anak',
                'title' => 'Pendampingan Transisi Belajar Anak Usia Dini',
                'desc' => 'Membekali orang tua siswa baru dalam mendampingi transisi belajar anak usia dini menuju fase sekolah dasar yang ceria.',
            ],
            [
                'badge' => 'Kader Kesehatan',
                'title' => 'Siapkan Kader Kesehatan Peduli Lingkungan',
                'desc' => 'Edukasi pertolongan pertama, kebersihan diri, dan pendampingan teman sebaya dalam menjaga sanitasi madrasah.',
            ],
            [
                'badge' => 'Pengembangan Diri',
                'title' => 'Siswa Didorong Percaya Diri Berbahasa Inggris',
                'desc' => 'Kegiatan interaktif akhir pekan untuk melatih komunikasi bahasa asing dan kemandirian bersosialisasi.',
            ],
            [
                'badge' => 'Moderasi Beragama',
                'title' => 'Sosialisasi Nilai Moderasi Beragama Lewat Refleksi',
                'desc' => 'Penanaman sikap toleransi, cinta tanah air, dan kerukunan sejak dini melalui media ekspresi visual kreatif.',
            ],
            [
                'badge' => 'Festival Anak',
                'title' => 'Classmeeting Kidfest: Ajang Ekspresi Bakat & Sportivitas',
                'desc' => 'Pekan perlombaan olahraga dan seni antarkelas yang meriah untuk memupuk kekompakan dan kebahagiaan siswa.',
            ],
        ];
    }

    /**
     * Memastikan tabel program_unggulans tersedia & terisi data awal jika kosong
     */
    public static function pastikanTabelSiap(): void
    {
        if (! Schema::hasTable('program_unggulans')) {
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

        // Jika data masih kosong, seed 4 program unggulan teladan (termasuk Sekolah Ramah Anak)
        if (self::count() === 0) {
            $defaultPrograms = [
                [
                    'judul' => 'Sekolah Ramah Anak (SRA)',
                    'slug' => 'sekolah-ramah-anak',
                    'kategori_badge' => 'Inklusif & Ramah Anak',
                    'icon' => 'fas fa-child-reaching',
                    'ringkasan' => 'Mewujudkan lingkungan belajar yang aman, nyaman, bebas kekerasan, dan inklusif dengan dukungan psikolog madrasah serta penataan kelas inspiratif.',
                    'gambar_sampul' => 'frontend/assets/imgs/page/img-4.png',
                    'kutipan' => 'Membentuk madrasah yang tidak hanya mencetak insan berprestasi, tetapi juga menjadi rumah kedua yang hangat, aman, dan penuh kasih sayang bagi setiap anak.',
                    'keunggulan_poin' => [
                        'Layanan Psikolog Khusus Tumbuh Kembang Anak',
                        'Penataan Ruang Kelas Inspiratif & Pojok Baca Nyaman',
                        'Pembelajaran Aktif, Menyenangkan & Bebas Bullying (PAIKEMI)',
                        'Program Parenting & Sinergi Harmonis Wali Murid',
                    ],
                    'isi_konten' => '<p>Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis berkomitmen kuat mewujudkan program <strong>Sekolah Ramah Anak (SRA)</strong> sebagai pilar utama dalam menghadirkan lingkungan belajar yang aman, bersih, sehat, peduli, dan berbudaya lingkungan hidup serta mampu menjamin, memenuhi, menghargai hak-hak anak dan perlindungan anak dari kekerasan, diskriminasi, dan perlakuan salah lainnya.</p>
<h4>1. Komitmen Lingkungan Belajar yang Aman & Menyenangkan</h4>
<p>Dalam implementasi Sekolah Ramah Anak, MIN 1 Ciamis memprioritaskan keselamatan fisik dan kenyamanan psikologis setiap peserta didik. Seluruh warga madrasah, mulai dari tenaga pendidik, kependidikan, tenaga keamanan, hingga pengelola kantin dibekali pemahaman mendalam tentang prinsip-prinsip perlindungan anak dan penanganan anti-perundungan (<em>anti-bullying</em>).</p>
<h4>2. Fasilitas dan Pendukung Program SRA</h4>
<ul>
<li><strong>Layanan Konseling & Psikolog Madrasah:</strong> Menyediakan ruang konsultasi ramah anak untuk membantu perkembangan emosional, minat bakat, serta penanganan kendala belajar santri sejak dini.</li>
<li><strong>Penataan Kelas Inspiratif:</strong> Desain ruang belajar dengan sirkulasi udara optimal, display karya kreativitas siswa yang terus diperbarui, serta pojok literasi lesehan yang nyaman.</li>
<li><strong>Pendekatan Pembelajaran Holistik (PAIKEMI):</strong> Pembelajaran Aktif, Inovatif, Kreatif, Efektif, Menyenangkan, dan Islami yang menempatkan anak sebagai subjek belajar aktif.</li>
<li><strong>Program Sinergi Orang Tua (Parenting):</strong> Forum berkala madrasah dan wali santri guna menyelaraskan pola asuh dan pembiasaan positif di madrasah dan di rumah.</li>
</ul>
<h4>3. Pembiasaan Karakter Mulia</h4>
<p>Keseharian santri diwarnai dengan pembiasaan 5S (Senyum, Salam, Sapa, Sopan, Santun), sholat Dhuha dan Dhuhur berjamaah, senam ceria, serta makan sehat bersama dengan memperhatikan gizi seimbang.</p>',
                    'penulis' => 'Tim Kurikulum & Humas MIN 1 Ciamis',
                    'urutan' => 1,
                    'views' => 24,
                    'is_active' => true,
                ],
                [
                    'judul' => 'Tahfidz & Tartil Qur\'an',
                    'slug' => 'tahfidz-dan-tartil-quran',
                    'kategori_badge' => 'Karakter Qur\'ani',
                    'icon' => 'fas fa-quran',
                    'ringkasan' => 'Program pembiasaan hafalan surat pilihan dan Juz 30 dengan bimbingan ustadz/ustadzah bersertifikasi makharijul huruf.',
                    'gambar_sampul' => 'frontend/assets/imgs/page/img-4.png',
                    'kutipan' => 'Menjadikan Al-Qur\'an sebagai pedoman hidup santri, menghafalnya dengan hati, dan mengamalkannya dalam pekerti sehari-hari.',
                    'keunggulan_poin' => [
                        'Target Hafalan Juz Amma (Juz 30) & Surat Pilihan',
                        'Pembiasaan Dhuha & Dhuhur Berjamaah Setiap Hari',
                        'Bimbingan Tahsin Berstandar Makharijul Huruf',
                        'Munaqosyah & Wisuda Tahfidz Terpadu',
                    ],
                    'isi_konten' => '<p>Program <strong>Tahfidz & Tartil Qur\'an</strong> merupakan program unggulan spiritual yang dirancang untuk membimbing santri mencintai, membaca sesuai kaidah tajwid, dan menghafalkan ayat-ayat suci Al-Qur\'an sejak usia dini.</p>
<h4>Metode Pembelajaran Terarah</h4>
<p>Pembelajaran tahfidz dilaksanakan setiap pagi sebelum Kegiatan Belajar Mengajar (KBM) dimulai dengan metode <em>talaqqi</em> dan <em>tasmi\'</em> berkala bersama guru pembimbing bersertifikasi.</p>',
                    'penulis' => 'Koordinator Keagamaan & Tahfidz',
                    'urutan' => 2,
                    'views' => 45,
                    'is_active' => true,
                ],
                [
                    'judul' => 'Club Sains Madrasah (KSM & OSN)',
                    'slug' => 'club-sains-madrasah',
                    'kategori_badge' => 'Sains & Riset',
                    'icon' => 'fas fa-flask',
                    'ringkasan' => 'Inkubasi talenta sains dan matematika terintegrasi nilai-nilai keislaman untuk mencetak juara olimpiade sains madrasah tingkat kabupaten hingga nasional.',
                    'gambar_sampul' => 'frontend/assets/imgs/page/img-5.png',
                    'kutipan' => 'Mengasah daya kritis, kecerdasan analitis, dan kecintaan terhadap sains dalam naungan tauhid.',
                    'keunggulan_poin' => [
                        'Bimbingan Intensif Matematika & IPAS Terintegrasi',
                        'Praktikum Sains & Pengamatan Lingkungan Hidup',
                        'Mentoring Juara KSM & OSN Tingkat Nasional',
                        'Simulasi CBT & Eksplorasi Laboratorium Sains',
                    ],
                    'isi_konten' => '<p><strong>Club Sains Madrasah</strong> mengintegrasikan konsep sains modern dengan nilai-nilai tauhid dan ketakwaan. Santri dilatih melakukan observasi ilmiah, eksperimen mandiri, dan penyelesaian soal bernalar tinggi (HOTS).</p>',
                    'penulis' => 'Pembina Olimpiade Sains',
                    'urutan' => 3,
                    'views' => 38,
                    'is_active' => true,
                ],
                [
                    'judul' => 'Madrasah Digital & Penguatan Literasi',
                    'slug' => 'madrasah-digital-dan-literasi',
                    'kategori_badge' => 'Digitalisasi & Bahasa',
                    'icon' => 'fas fa-laptop-code',
                    'ringkasan' => 'Pemanfaatan media pembelajaran interaktif berbasis teknologi, asesmen komputer, presensi QR code, dan pembiasaan literasi tiga bahasa.',
                    'gambar_sampul' => 'frontend/assets/imgs/page/img-6.png',
                    'kutipan' => 'Cerdas berteknologi, bijak berliterasi, siap menjawab tantangan peradaban masa depan.',
                    'keunggulan_poin' => [
                        'Media Pembelajaran Interaktif Dunia Ilmu & Lab Komputer',
                        'Presensi Digital QR Code Terintegrasi Real-time',
                        'Pembiasaan Membaca Pagi & Review Buku 3 Bahasa',
                        'Rapor Digital Madrasah (RDM) Transparan',
                    ],
                    'isi_konten' => '<p>Program <strong>Madrasah Digital & Literasi</strong> membekali santri keterampilan abad ke-21 tanpa meninggalkan budaya membaca buku fisik yang kaya nilai pengetahuan.</p>',
                    'penulis' => 'Tim TIK & Literasi MIN 1 Ciamis',
                    'urutan' => 4,
                    'views' => 32,
                    'is_active' => true,
                ],
            ];

            foreach ($defaultPrograms as $prog) {
                self::create($prog);
            }
        }
    }
}
