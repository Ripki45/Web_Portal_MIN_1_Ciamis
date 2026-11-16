<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DuniaIlmu extends Model
{
    protected $table = 'dunia_ilmus';

    protected $fillable = [
        'judul',
        'slug',
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
     * Pilihan Warna Gradasi Kartu (Sesuai CSS Landing Page MIN 1 Ciamis)
     */
    public static function daftarGradasi(): array
    {
        return [
            'dunia-grad-quran'     => '🟢 Hijau Zamrud & Emas (Al-Qur\'an)',
            'dunia-grad-phet'      => '🔵 Biru Langit Sains (PhET / Lab)',
            'dunia-grad-perpus'    => '🟠 Oranye Hangat (Perpustakaan / Buku)',
            'dunia-grad-wordwall'  => '🔴 Merah Muda Ceria (Game / Wordwall)',
            'dunia-grad-kamus'     => '🟣 Ungu Royal (Kamus / Bahasa)',
            'dunia-grad-elearning' => '🔷 Biru Indigo (E-Learning / CBT)',
            'dunia-grad-video'     => 'crimson Merah Maroon (Video Edukasi)',
            'dunia-grad-quizizz'   => '🔮 Ungu Muda (Quizizz / Kuis)',
            'dunia-grad-hadits'    => '🌿 Hijau Daun (Hadits & Doa)',
            'dunia-grad-belajar'   => '🟡 Kuning Amber (Rumah Belajar)',
        ];
    }

    /**
     * Pilihan Kategori Filter Katalog
     */
    public static function daftarKategoriFilter(): array
    {
        return [
            'sains'    => 'Sains, Matematika & Simulasi',
            'quran'    => 'Al-Qur\'an, Hadits & PAI',
            'literasi' => 'Buku, Literasi & Bahasa',
            'game'     => 'Game Interaktif & Kuis (TSX/JS)',
        ];
    }

    /**
     * URL Tujuan Saat Kartu Diklik (Otomatis bedakan Link Eksternal vs Player TSX/JS)
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
        if (!empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'frontend/') or str_starts_with($this->gambar, 'backend/') or str_starts_with($this->gambar, 'http')) {
                return asset($this->gambar);
            }
            return asset('storage/' . $this->gambar);
        }
        return asset('backend/assets/images/icon_min.png');
    }

    public static function pastikanTabelSiap(): void
    {
        static $sudahDicek = false;
        if ($sudahDicek) {
            return;
        }
        $sudahDicek = true;

        if (!Schema::hasTable('dunia_ilmus')) {
            Schema::create('dunia_ilmus', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 160);
                $table->string('slug', 190)->unique();
                $table->string('kategori_label', 100)->default('Media Interaktif');
                $table->string('kategori_filter', 40)->default('game');
                $table->string('warna_gradasi', 50)->default('dunia-grad-phet');
                $table->string('gambar', 255)->nullable();
                $table->string('tipe_sumber', 30)->default('link'); // 'link' atau 'kode_interaktif'
                $table->string('link_eksternal', 255)->nullable();
                $table->string('nama_file_kode', 150)->nullable();
                $table->longText('isi_kode')->nullable();
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(1);
                $table->unsignedBigInteger('views')->default(12);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Contoh 1: Aplikasi React .TSX Interaktif Bawaan (Bisa langsung dimainkan!)
            $contohKodeTsx = <<<'TSX'
import React, { useState } from 'react';
import { Trophy, Sparkles, CheckCircle2, RefreshCw, BookOpen } from 'lucide-react';

interface Soal {
  pertanyaan: string;
  pilihan: string[];
  jawaban: number;
  bahasan: string;
}

const DAFTAR_SOAL: Soal[] = [
  {
    pertanyaan: "Berapakah hasil dari 3/4 + 1/4 dalam bentuk paling sederhana?",
    pilihan: ["1", "4/8", "2/4", "3/8"],
    jawaban: 0,
    bahasan: "3/4 + 1/4 = 4/4 = 1 utuh."
  },
  {
    pertanyaan: "Gerakan berpindah tempat dari satu titik ke titik lain dalam PJOK disebut gerak...",
    pilihan: ["Non-Lokomotor", "Lokomotor", "Manipulatif", "Statis"],
    jawaban: 1,
    bahasan: "Gerak lokomotor adalah gerak berpindah tempat seperti berjalan, berlari, dan melompat."
  },
  {
    pertanyaan: "Surat pendek dalam Juz 30 yang menceritakan tentang pasukan bergajah adalah...",
    pilihan: ["Al-Quraisy", "Al-Ma'un", "Al-Fil", "Al-Kautsar"],
    jawaban: 2,
    bahasan: "Al-Fil artinya Gajah, terdiri dari 5 ayat."
  }
];

export default function App() {
  const [indeks, setIndeks] = useState<number>(0);
  const [skor, setSkor] = useState<number>(0);
  const [terpilih, setTerpilih] = useState<number | null>(null);
  const [selesai, setSelesai] = useState<boolean>(false);

  const soalAktif = DAFTAR_SOAL[indeks];

  const pilihJawaban = (idx: number) => {
    if (terpilih !== null) return;
    setTerpilih(idx);
    if (idx === soalAktif.jawaban) {
      setSkor(skor + 100);
    }
  };

  const lanjutSoal = () => {
    if (indeks + 1 < DAFTAR_SOAL.length) {
      setIndeks(indeks + 1);
      setTerpilih(null);
    } else {
      setSelesai(true);
    }
  };

  const ulangiKuis = () => {
    setIndeks(0);
    setSkor(0);
    setTerpilih(null);
    setSelesai(false);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-emerald-900 via-teal-800 to-slate-900 flex items-center justify-center p-4 font-sans text-white">
      <div className="max-w-xl w-full bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
        <div className="flex items-center justify-between mb-6 pb-4 border-b border-white/15">
          <div className="flex items-center gap-2 text-amber-300 font-bold">
            <BookOpen className="w-6 h-6" />
            <span>Dunia Ilmu Interaktif — MIN 1 Ciamis</span>
          </div>
          <div className="bg-amber-400/20 border border-amber-400/40 text-amber-300 px-4 py-1 rounded-full text-sm font-extrabold">
            Skor: {skor}
          </div>
        </div>

        {!selesai ? (
          <div>
            <div className="text-xs uppercase tracking-wider text-emerald-300 font-bold mb-2">
              Pertanyaan {indeks + 1} dari {DAFTAR_SOAL.length}
            </div>
            <h2 className="text-xl font-extrabold mb-6 leading-relaxed">
              {soalAktif.pertanyaan}
            </h2>

            <div className="space-y-3 mb-6">
              {soalAktif.pilihan.map((teks, i) => {
                let warna = "bg-white/10 hover:bg-white/20 border-white/20";
                if (terpilih !== null) {
                  if (i === soalAktif.jawaban) {
                    warna = "bg-emerald-500/40 border-emerald-400 text-white";
                  } else if (i === terpilih) {
                    warna = "bg-rose-500/40 border-rose-400 text-white";
                  }
                }
                return (
                  <button
                    key={i}
                    onClick={() => pilihJawaban(i)}
                    className={`w-full text-left p-4 rounded-2xl border transition-all font-semibold flex items-center justify-between ${warna}`}
                  >
                    <span>{teks}</span>
                    {terpilih !== null && i === soalAktif.jawaban && (
                      <CheckCircle2 className="w-5 h-5 text-emerald-300" />
                    )}
                  </button>
                );
              })}
            </div>

            {terpilih !== null && (
              <div className="bg-slate-900/60 border border-emerald-400/30 rounded-2xl p-4 mb-6 text-sm text-emerald-100">
                <strong className="text-amber-300 block mb-1">💡 Pembahasan:</strong>
                {soalAktif.bahasan}
              </div>
            )}

            {terpilih !== null && (
              <button
                onClick={lanjutSoal}
                className="w-full py-3.5 bg-amber-400 hover:bg-amber-300 text-slate-900 font-extrabold rounded-2xl shadow-lg transition-all"
              >
                {indeks + 1 < DAFTAR_SOAL.length ? "Pertanyaan Berikutnya →" : "Lihat Hasil Akhir 🏆"}
              </button>
            )}
          </div>
        ) : (
          <div className="text-center py-6">
            <Trophy className="w-20 h-20 text-amber-400 mx-auto mb-4 animate-bounce" />
            <h2 className="text-2xl font-black mb-2">Masya Allah, Luar Biasa!</h2>
            <p className="text-emerald-200 mb-6">
              Kamu telah menyelesaikan Kuis Interaktif dengan total perolehan skor:
            </p>
            <div className="text-5xl font-black text-amber-300 mb-8">{skor} Poin</div>
            <button
              onClick={ulangiKuis}
              className="inline-flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-full shadow-lg transition-all"
            >
              <RefreshCw className="w-5 h-5" /> Mainkan Lagi
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
TSX;

            $initialItems = [
                [
                    'judul'           => 'Kuis Cerdas Cermat Interaktif (App TSX)',
                    'slug'            => 'kuis-cerdas-cermat-interaktif-tsx',
                    'kategori_label'  => 'Aplikasi React TSX AI Studio',
                    'kategori_filter' => 'game',
                    'warna_gradasi'   => 'dunia-grad-quizizz',
                    'tipe_sumber'     => 'kode_interaktif',
                    'nama_file_kode'  => 'KuisCerdasCermat.tsx',
                    'isi_kode'        => $contohKodeTsx,
                    'deskripsi'       => 'Contoh media pembelajaran interaktif berbasis file .TSX dari Google AI Studio yang langsung berjalan di browser.',
                    'urutan'          => 1,
                ],
                [
                    'judul'           => 'Al-Qur\'an Digital Kemenag',
                    'slug'            => 'al-quran-digital-kemenag',
                    'kategori_label'  => 'Tahsin & Murottal 30 Juz',
                    'kategori_filter' => 'quran',
                    'warna_gradasi'   => 'dunia-grad-quran',
                    'tipe_sumber'     => 'link',
                    'link_eksternal'  => 'https://quran.kemenag.go.id/',
                    'deskripsi'       => 'Baca Al-Qur\'an lengkap dengan tajwid berwarna, terjemah Kemenag RI, dan audio murottal 30 Juz.',
                    'urutan'          => 2,
                ],
                [
                    'judul'           => 'Lab Sains Virtual PhET',
                    'slug'            => 'lab-sains-virtual-phet',
                    'kategori_label'  => 'Simulasi Sains & Matematika',
                    'kategori_filter' => 'sains',
                    'warna_gradasi'   => 'dunia-grad-phet',
                    'tipe_sumber'     => 'link',
                    'link_eksternal'  => 'https://phet.colorado.edu/in/',
                    'deskripsi'       => 'Laboratorium virtual interaktif untuk memahami konsep sains dan matematika secara visual.',
                    'urutan'          => 3,
                ],
                [
                    'judul'           => 'E-Perpustakaan SIBI',
                    'slug'            => 'e-perpustakaan-sibi',
                    'kategori_label'  => 'Buku Teks & Cerita Santri',
                    'kategori_filter' => 'literasi',
                    'warna_gradasi'   => 'dunia-grad-perpus',
                    'tipe_sumber'     => 'link',
                    'link_eksternal'  => 'https://buku.kemdikbud.go.id/',
                    'deskripsi'       => 'Koleksi buku teks Kurikulum Merdeka, cerita bergambar, dan buku pengayaan literasi anak.',
                    'urutan'          => 4,
                ],
                [
                    'judul'           => 'Game Edukasi Wordwall',
                    'slug'            => 'game-edukasi-wordwall',
                    'kategori_label'  => 'Game Kuis & Evaluasi Belajar',
                    'kategori_filter' => 'game',
                    'warna_gradasi'   => 'dunia-grad-wordwall',
                    'tipe_sumber'     => 'link',
                    'link_eksternal'  => 'https://wordwall.net/id',
                    'deskripsi'       => 'Pembelajaran interaktif lewat roda putar kata, mencocokkan kartu, dan kuis interaktif.',
                    'urutan'          => 5,
                ],
            ];

            foreach ($initialItems as $item) {
                self::create($item);
            }
        }
    }
}