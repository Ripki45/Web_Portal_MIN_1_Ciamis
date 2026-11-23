<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Daftar rekomendasi ikon Font Awesome untuk program unggulan madrasah.
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
     * Sanitasi HTML aman untuk konten dari Quill.js (mencegah XSS & HTML kosong).
     */
    public static function bersihkanHtml(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        // Hapus blok script, style, dan iframe beserta isinya
        $clean = preg_replace('#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', $html);

        // Sisakan hanya tag pemformatan dasar yang dipakai Quill
        $clean = strip_tags((string) $clean, '<p><br><strong><b><em><i><u><ul><ol><li>');

        // Hapus atribut event handler (onclick, onerror, dll.) & protokol javascript:
        $clean = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);
        $clean = preg_replace('/javascript\s*:/i', '', (string) $clean);
        $clean = trim((string) $clean);

        // Deteksi isi kosong bawaan Quill seperti <p><br></p>
        $teksPolos = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', $clean)), ENT_QUOTES, 'UTF-8'));

        return $teksPolos === '' ? null : $clean;
    }

    /**
     * URL Gambar Sampul Program.
     */
    public function getGambarUrlAttribute(): string
    {
        if (! empty($this->gambar_sampul)) {
            if (
                str_starts_with($this->gambar_sampul, 'frontend/')
                || str_starts_with($this->gambar_sampul, 'backend/')
                || str_starts_with($this->gambar_sampul, 'http')
            ) {
                return asset($this->gambar_sampul);
            }

            return asset('storage/'.$this->gambar_sampul);
        }

        return asset('frontend/assets/imgs/page/img-4.png');
    }

    /**
     * Ringkasan HTML yang sudah disanitasi untuk halaman detail.
     */
    public function getRingkasanBersihAttribute(): ?string
    {
        return self::bersihkanHtml($this->ringkasan);
    }

    /**
     * Ringkasan teks polos tanpa tag HTML untuk kartu katalog & tabel admin.
     */
    public function getRingkasanPolosAttribute(): string
    {
        if (empty($this->ringkasan)) {
            return '';
        }

        $decoded = html_entity_decode(strip_tags((string) $this->ringkasan), ENT_QUOTES, 'UTF-8');

        return trim((string) preg_replace('/\s+/', ' ', str_replace("\u{00A0}", ' ', $decoded)));
    }

    /**
     * Daftar poin-poin keunggulan dalam format array bersih.
     *
     * @return array<int, string>
     */
    public function getPoinListAttribute(): array
    {
        if (is_array($this->keunggulan_poin)) {
            return array_values(array_filter(array_map('trim', $this->keunggulan_poin)));
        }

        if (is_string($this->keunggulan_poin)) {
            $decoded = json_decode($this->keunggulan_poin, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded)));
            }

            $lines = explode("\n", str_replace("\r", '', $this->keunggulan_poin));

            return array_values(array_filter(array_map('trim', $lines)));
        }

        return [];
    }

    /**
     * Mengambil maksimal 2 mini feature cards di Section 1 (tanpa paksa dummy SRA).
     *
     * @return array<int, array{icon: string, title: string, desc: string}>
     */
    public function getMiniFeaturesListAttribute(): array
    {
        if (! is_array($this->mini_features) || count($this->mini_features) === 0) {
            return [];
        }

        $cleaned = [];
        foreach (array_slice($this->mini_features, 0, 2) as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $desc = trim((string) ($item['desc'] ?? ''));

            if ($title !== '' || $desc !== '') {
                $cleaned[] = [
                    'icon' => trim((string) ($item['icon'] ?? '')) ?: 'fas fa-check-circle',
                    'title' => $title !== '' ? $title : 'Keunggulan Program',
                    'desc' => $desc,
                ];
            }
        }

        return $cleaned;
    }

    /**
     * Mengambil maksimal 4 Pilar Program di Section 2 (tanpa paksa dummy SRA).
     *
     * @return array<int, array{icon: string, title: string, desc: string}>
     */
    public function getPilarListFormattedAttribute(): array
    {
        if (! is_array($this->pilar_list) || count($this->pilar_list) === 0) {
            return [];
        }

        $cleaned = [];
        foreach (array_slice($this->pilar_list, 0, 4) as $index => $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $desc = trim((string) ($item['desc'] ?? ''));

            if ($title !== '' || $desc !== '') {
                $cleaned[] = [
                    'icon' => trim((string) ($item['icon'] ?? '')) ?: 'fas fa-award',
                    'title' => $title !== '' ? $title : 'Pilar '.($index + 1),
                    'desc' => $desc,
                ];
            }
        }

        return $cleaned;
    }

    /**
     * Judul Section 3 Kegiatan.
     */
    public function getKegiatanJudulFormattedAttribute(): string
    {
        return trim((string) $this->kegiatan_judul) ?: 'Kegiatan & Implementasi '.$this->judul;
    }

    /**
     * Badge Kategori Section 3 Kegiatan.
     */
    public function getKegiatanBadgeFormattedAttribute(): string
    {
        return trim((string) $this->kegiatan_badge) ?: 'Aktivitas & Edukasi';
    }

    /**
     * Deskripsi subtitle Section 3 Kegiatan.
     */
    public function getKegiatanDeskripsiFormattedAttribute(): string
    {
        return trim((string) $this->kegiatan_deskripsi) ?: 'Dokumentasi agenda, kegiatan, dan pembiasaan peserta didik pada program '.$this->judul.'.';
    }

    /**
     * Mengambil maksimal 6 kartu kegiatan di Section 3 (tanpa paksa dummy SRA).
     *
     * @return array<int, array{badge: string, title: string, desc: string}>
     */
    public function getKegiatanListDataAttribute(): array
    {
        if (! is_array($this->kegiatan_list) || count($this->kegiatan_list) === 0) {
            return [];
        }

        $cleaned = [];
        foreach (array_slice($this->kegiatan_list, 0, 6) as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $desc = trim((string) ($item['desc'] ?? ''));

            if ($title !== '' || $desc !== '') {
                $cleaned[] = [
                    'badge' => trim((string) ($item['badge'] ?? '')) ?: 'Kegiatan',
                    'title' => $title !== '' ? $title : 'Kegiatan Program',
                    'desc' => $desc,
                ];
            }
        }

        return $cleaned;
    }

    /**
     * @deprecated Tabel dikelola resmi melalui Laravel Migrations (database/migrations).
     *             Method dibiarkan kosong untuk menjaga kompatibilitas jika masih dipanggil controller lain.
     */
    public static function pastikanTabelSiap(): void
    {
        // Sengaja dikosongkan agar tidak menjalankan Schema::hasTable / seeding otomatis di setiap request.
    }
}