<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Berita extends Model
{
    protected $table = 'beritas';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'ringkasan',
        'kutipan',
        'isi_konten',
        'gambar',
        'tags',
        'penulis',
        'user_id',
        'status',
        'is_featured',
        'views',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Estimasi waktu baca otomatis
     */
    public function getWaktuBacaAttribute(): string
    {
        $kata  = str_word_count(strip_tags((string) $this->isi_konten));
        $menit = max(1, (int) ceil($kata / 180));
        return $menit . ' menit baca';
    }

    /**
     * URL gambar otomatis (Bebas simbol pipe agar tidak terkena error \vert)
     */
    public function getGambarUrlAttribute(): string
    {
        if (!empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'frontend/') or str_starts_with($this->gambar, 'http')) {
                return asset($this->gambar);
            }
            return asset('storage/' . $this->gambar);
        }
        return asset('frontend/assets/imgs/page/img-4.png');
    }

    /**
     * Pastikan tabel beritas siap di SQLite & isi 3 berita awal jika kosong
     */
    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (!Schema::hasTable('beritas')) {
            Schema::create('beritas', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 220);
                $table->string('slug', 250)->unique();
                $table->string('kategori', 80)->default('Kegiatan Siswa');
                $table->text('ringkasan')->nullable();
                $table->text('kutipan')->nullable();
                $table->longText('isi_konten');
                $table->string('gambar', 255)->nullable();
                $table->string('tags', 255)->nullable();
                $table->string('penulis', 120)->default('Tim Humas MIN 1 Ciamis');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('status', 20)->default('Tayang');
                $table->boolean('is_featured')->default(false);
                $table->unsignedBigInteger('views')->default(15);
                $table->timestamps();
            });

            self::create([
                'judul'       => 'Gerakan Literasi Pagi Menghidupkan Budaya Baca Siswa MIN 1 Ciamis',
                'slug'        => 'gerakan-literasi-pagi-menghidupkan-budaya-baca',
                'kategori'    => 'Kegiatan Siswa',
                'ringkasan'   => 'Program pembiasaan membaca 15 menit sebelum jam pelajaran dimulai sukses meningkatkan minat baca dan keberanian siswa bercerita di depan kelas.',
                'kutipan'     => 'Membaca adalah jendela dunia; melalui pembiasaan literasi pagi, kita menumbuhkan generasi pembelajar sepanjang hayat.',
                'isi_konten'  => '<h4>Menumbuhkan Cinta Buku Sejak Dini</h4><p>MIN 1 Ciamis terus berkomitmen menguatkan budaya literasi di lingkungan madrasah melalui program <strong>Gerakan Literasi Pagi</strong>. Setiap pagi sebelum kegiatan belajar mengajar dimulai, seluruh siswa membaca buku cerita Islami, sains populer, maupun ensiklopedia anak.</p><h4>Diskusi dan Apresiasi Karya</h4><p>Selain membaca mandiri, para wali kelas memfasilitasi sesi diskusi singkat di mana siswa menceritakan kembali intisari bacaan dengan bahasa mereka sendiri.</p><ul><li>Meningkatkan kosakata dan pemahaman bacaan siswa.</li><li>Melatih keberanian berbicara di depan umum (public speaking).</li><li>Menghidupkan pojok baca di setiap ruang kelas.</li></ul>',
                'gambar'      => 'frontend/assets/imgs/page/img-4.png',
                'tags'        => 'Literasi, Kegiatan Siswa, Akademik',
                'penulis'     => 'Tim Humas MIN 1 Ciamis',
                'status'      => 'Tayang',
                'is_featured' => true,
                'views'       => 142,
            ]);

            self::create([
                'judul'       => 'Integrasi Pembelajaran Digital Interaktif di Kelas Madrasah',
                'slug'        => 'integrasi-pembelajaran-digital-interaktif-di-kelas-madrasah',
                'kategori'    => 'Inovasi Pendidikan',
                'ringkasan'   => 'Pemanfaatan media interaktif dan kecerdasan buatan membantu guru menyajikan materi pelajaran yang lebih visual dan menyenangkan.',
                'kutipan'     => 'Teknologi di tangan pendidik yang kreatif akan mengubah ruang kelas menjadi laboratorium eksplorasi yang menginspirasi.',
                'isi_konten'  => '<h4>Transformasi Digital Madrasah</h4><p>Para guru di MIN 1 Ciamis aktif mengimplementasikan media pembelajaran berbasis teknologi digital untuk mempermudah pemahaman konsep sains, matematika, dan bahasa.</p>',
                'gambar'      => 'frontend/assets/imgs/page/img-5.png',
                'tags'        => 'Digital, Pembelajaran, Guru',
                'penulis'     => 'Tim Kurikulum',
                'status'      => 'Tayang',
                'is_featured' => false,
                'views'       => 98,
            ]);

            self::create([
                'judul'       => 'Semangat Kemandirian dan Kepemimpinan dalam Latihan Rutin Pramuka',
                'slug'        => 'semangat-kemandirian-dan-kepemimpinan-dalam-latihan-pramuka',
                'kategori'    => 'Ekstrakurikuler',
                'ringkasan'   => 'Kegiatan kepramukaan menjadi wadah utama pembentukan karakter disiplin, kerja sama regu, dan kecintaan terhadap alam.',
                'kutipan'     => 'Satyaku Kudarmakan, Darmaku Kubaktikan — membentuk tunas muda madrasah yang tangguh dan berakhlak mulia.',
                'isi_konten'  => '<h4>Pembinaan Karakter Melalui Kepramukaan</h4><p>Latihan ekstrakurikuler Pramuka di MIN 1 Ciamis dikemas secara interaktif melalui permainan edukatif, keterampilan tali-temali, baris-berbaris, serta kepedulian lingkungan.</p>',
                'gambar'      => 'frontend/assets/imgs/page/img-6.png',
                'tags'        => 'Pramuka, Ekstrakurikuler, Karakter',
                'penulis'     => 'Pembina Pramuka',
                'status'      => 'Tayang',
                'is_featured' => false,
                'views'       => 115,
            ]);
        }
    }
}