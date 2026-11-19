<?php

namespace App\Services;

use App\Models\Berita;
use App\Models\DuniaIlmu;
use App\Models\Gtk;
use App\Models\Halaman;
use App\Models\SambutanProfil;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Ai\Embeddings;

class SemanticSearchService
{
    /**
     * Jalankan Pencarian Semantik Menggunakan Vector Embedding
     *
     * @return array<int, array{title: string, snippet: string, url: string, category: string, similarity: float}>
     */
    public function search(string $query): array
    {
        $cleanQuery = trim($query);

        if (strlen($cleanQuery) < 2) {
            return $this->getDefaultSuggestions();
        }

        try {
            return $this->vectorSemanticSearch($cleanQuery);
        } catch (\Throwable $e) {
            // Fallback halus ke pencarian kata kunci cerdas (jika koneksi AI lambat/offline)
            return $this->keywordFallbackSearch($cleanQuery);
        }
    }

    /**
     * Pencarian Berbasis Vector Embeddings (Cosine Similarity)
     */
    protected function vectorSemanticSearch(string $query): array
    {
        $corpus = $this->getCorpus();
        if (empty($corpus)) {
            return $this->keywordFallbackSearch($query);
        }

        // 1. Ambil embedding query pengguna
        $queryEmbeddingResponse = Embeddings::for([$query])->generate();
        $queryVector = $queryEmbeddingResponse->first();

        // 2. Ambil atau simpan cache vector korpus dokumen
        $corpusVectors = $this->getCorpusVectors($corpus);

        // 3. Hitung Cosine Similarity untuk setiap dokumen
        $results = [];
        foreach ($corpus as $index => $item) {
            if (! isset($corpusVectors[$index])) {
                continue;
            }

            $score = $this->cosineSimilarity($queryVector, $corpusVectors[$index]);

            // Ambang batas relevansi semantik
            if ($score >= 0.38) {
                $item['similarity'] = round($score * 100);
                $item['source'] = 'semantic';
                $results[] = $item;
            }
        }

        // 4. Urutkan dari yang paling relevan (skor tertinggi)
        usort($results, fn ($a, $b) => $b['similarity'] <=> $a['similarity']);

        // Jika vector search tidak menemukan hasil yang cukup kuat, gabungkan dengan keyword search
        if (empty($results)) {
            return $this->keywordFallbackSearch($query);
        }

        return array_slice($results, 0, 8);
    }

    /**
     * Hitung Cosine Similarity antara dua vektor
     */
    protected function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $count = count($vecA);

        for ($i = 0; $i < $count; $i++) {
            $valA = (float) $vecA[$i];
            $valB = (float) $vecB[$i];

            $dotProduct += $valA * $valB;
            $normA += $valA * $valA;
            $normB += $valB * $valB;
        }

        $denominator = sqrt($normA) * sqrt($normB);

        return $denominator > 0 ? (float) ($dotProduct / $denominator) : 0.0;
    }

    /**
     * Ambil Vektor Korpus dari Cache atau Generate Sekali
     */
    protected function getCorpusVectors(array $corpus): array
    {
        // Cache vector selama 12 jam agar pencarian instan dan hemat kuota API
        $cacheKey = 'min1_semantic_corpus_vectors_v1_'.md5(json_encode(array_column($corpus, 'title')));

        return Cache::remember($cacheKey, 43200, function () use ($corpus) {
            $texts = array_map(function ($item) {
                return $item['title'].'. '.$item['snippet'].'. Kategori: '.$item['category'];
            }, $corpus);

            $response = Embeddings::for($texts)->generate();
            $vectors = [];

            foreach ($response as $idx => $vector) {
                $vectors[$idx] = $vector;
            }

            return $vectors;
        });
    }

    /**
     * Korpus Konten Dinamis Madrasah yang Dapat Dicari
     */
    public function getCorpus(): array
    {
        return Cache::remember('min1_searchable_corpus_v1', 3600, function () {
            $items = [];

            // 1. Berita & Kegiatan Madrasah
            if (class_exists(Berita::class) && Schema::hasTable('beritas')) {
                $beritas = Berita::where('status', 'Tayang')->latest('id')->take(15)->get();
                foreach ($beritas as $b) {
                    $items[] = [
                        'title' => $b->judul,
                        'snippet' => Str::limit($b->ringkasan ?: strip_tags($b->isi_konten), 130),
                        'url' => route('berita.show', $b->slug),
                        'category' => 'Berita & Kegiatan',
                        'icon' => 'far fa-newspaper',
                    ];
                }
            }

            // 2. Profil & Sambutan Kepala Madrasah
            if (class_exists(SambutanProfil::class) && Schema::hasTable('sambutan_profils')) {
                $sambutan = SambutanProfil::ambilData();
                $items[] = [
                    'title' => 'Sambutan Kepala Madrasah',
                    'snippet' => Str::limit($sambutan->sambutan_kutipan.' - '.$sambutan->nama_kepsek, 130),
                    'url' => url('/#sambutan'),
                    'category' => 'Profil Madrasah',
                    'icon' => 'fas fa-user-tie',
                ];
                $items[] = [
                    'title' => 'Visi dan Misi MIN 1 Ciamis',
                    'snippet' => Str::limit($sambutan->visi_kutipan.' '.$sambutan->motto_kutipan, 130),
                    'url' => url('/#profil'),
                    'category' => 'Visi & Misi',
                    'icon' => 'fas fa-bullseye',
                ];
            }

            // 3. Dunia Ilmu & Media Pembelajaran Siswa
            if (class_exists(DuniaIlmu::class) && Schema::hasTable('dunia_ilmus')) {
                $dunia = DuniaIlmu::where('status', 'Aktif')->take(10)->get();
                foreach ($dunia as $d) {
                    $items[] = [
                        'title' => $d->judul,
                        'snippet' => Str::limit($d->deskripsi ?: 'Media edukasi interaktif dan modul sains siswa madrasah.', 130),
                        'url' => route('dunia-ilmu.play', $d->slug),
                        'category' => 'Dunia Ilmu & Sains',
                        'icon' => 'fas fa-atom',
                    ];
                }
            }

            // 4. Halaman Informasi Statis
            if (class_exists(Halaman::class) && Schema::hasTable('halamans')) {
                $halamans = Halaman::where('status', 'Publish')->take(10)->get();
                foreach ($halamans as $h) {
                    $items[] = [
                        'title' => $h->judul,
                        'snippet' => Str::limit(strip_tags($h->konten), 130),
                        'url' => url('/halaman/'.$h->slug),
                        'category' => 'Halaman Informasi',
                        'icon' => 'fas fa-file-alt',
                    ];
                }
            }

            // 5. Guru & Staff Manajemen
            if (class_exists(Gtk::class) && Schema::hasTable('gtks')) {
                $gtks = Gtk::with(['jabatan', 'posisi'])->take(12)->get();
                foreach ($gtks as $g) {
                    $items[] = [
                        'title' => $g->nama_lengkap,
                        'snippet' => 'Pendidik & Tenaga Kependidikan MIN 1 Ciamis ('.$g->jabatan_tampil.')',
                        'url' => route('guru-staff.public'),
                        'category' => 'Pendidik & GTK',
                        'icon' => 'fas fa-chalkboard-teacher',
                    ];
                }
            }

            // 6. Tautan Cepat Program & Layanan
            $items[] = [
                'title' => 'Penerimaan Peserta Didik Baru (PPDB Online)',
                'snippet' => 'Informasi pendaftaran siswa baru, syarat administrasi, jadwal seleksi, dan biaya madrasah.',
                'url' => url('/#ppdb'),
                'category' => 'Layanan Siswa',
                'icon' => 'fas fa-user-plus',
            ];
            $items[] = [
                'title' => 'Program Unggulan Tahfidz Al-Qur\'an',
                'snippet' => 'Program hafalan Juz 30, tartil, dan pembiasaan ibadah shalat dhuha bagi siswa.',
                'url' => url('/#program'),
                'category' => 'Program Unggulan',
                'icon' => 'fas fa-quran',
            ];
            $items[] = [
                'title' => 'Kontak, Alamat & Lokasi Panjalu',
                'snippet' => 'Alamat Jl. Maparah No. 65 Panjalu Ciamis, WhatsApp resmi madrasah, dan peta lokasi.',
                'url' => url('/#kontak'),
                'category' => 'Kontak Madrasah',
                'icon' => 'fas fa-map-marker-alt',
            ];

            return $items;
        });
    }

    /**
     * Fallback Cepat Menggunakan Pencarian Kata Kunci & Skor Kemiripan
     */
    protected function keywordFallbackSearch(string $query): array
    {
        $corpus = $this->getCorpus();
        $keywords = array_filter(explode(' ', strtolower($query)));
        $results = [];

        foreach ($corpus as $item) {
            $text = strtolower($item['title'].' '.$item['snippet'].' '.$item['category']);
            $score = 0;

            foreach ($keywords as $kw) {
                if (str_contains($text, $kw)) {
                    $score += 25;
                }
            }

            if ($score > 0) {
                $item['similarity'] = min($score, 95);
                $item['source'] = 'keyword';
                $results[] = $item;
            }
        }

        usort($results, fn ($a, $b) => $b['similarity'] <=> $a['similarity']);

        return array_slice($results, 0, 8);
    }

    /**
     * Saran Pencarian Standar Saat Pengguna Baru Membuka Modal
     */
    protected function getDefaultSuggestions(): array
    {
        return array_slice($this->getCorpus(), 0, 6);
    }
}
