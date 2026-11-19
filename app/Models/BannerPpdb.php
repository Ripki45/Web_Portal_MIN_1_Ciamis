<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BannerPpdb extends Model
{
    protected $table = 'banner_ppdbs';

    protected $fillable = [
        'judul',
        'subjudul',
        'gambar',
        'tipe_link',
        'link_url',
        'is_active',
        'is_active_popup',
        'auto_popup',
        'popup_judul',
        'popup_badge',
        'popup_gambar',
        'popup_konten',
        'popup_tombol_teks',
        'popup_tombol_url',
        'popup_wa_nomor',
        'popup_wa_teks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_active_popup' => 'boolean',
        'auto_popup' => 'boolean',
    ];

    /**
     * URL Banner Landscape PPDB
     */
    public function getGambarUrlAttribute(): string
    {
        if (! empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'frontend/') || str_starts_with($this->gambar, 'backend/') || str_starts_with($this->gambar, 'http')) {
                return asset($this->gambar);
            }

            return asset('storage/'.$this->gambar);
        }

        return self::ambilDummyBannerSvg();
    }

    /**
     * URL Flyer/Brosur di dalam Modal Popup PPDB
     */
    public function getPopupGambarUrlAttribute(): ?string
    {
        if (! empty($this->popup_gambar)) {
            if (str_starts_with($this->popup_gambar, 'frontend/') || str_starts_with($this->popup_gambar, 'backend/') || str_starts_with($this->popup_gambar, 'http')) {
                return asset($this->popup_gambar);
            }

            return asset('storage/'.$this->popup_gambar);
        }

        return null;
    }

    /**
     * SVG Dummy Standar Banner PPDB Landscape (Responsif Laptop & Mobile)
     */
    public static function ambilDummyBannerSvg(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 580" width="100%" height="100%">'
            .'<defs>'
            .'<linearGradient id="ppdbGrad" x1="0%" y1="0%" x2="100%" y2="0%">'
            .'<stop offset="0%" stop-color="#FFFFFF"/>'
            .'<stop offset="55%" stop-color="#F8FAFC"/>'
            .'<stop offset="100%" stop-color="#DBEAFE"/>'
            .'</linearGradient>'
            .'</defs>'
            .'<rect width="1600" height="580" fill="url(#ppdbGrad)"/>'
            .'<circle cx="1380" cy="290" r="340" fill="#1E3A8A" opacity="0.08"/>'
            .'<path d="M1280,580 C1340,420 1480,380 1600,380 L1600,580 Z" fill="#F59E0B"/>'
            .'<text x="85" y="135" font-family="Arial, sans-serif" font-size="68" font-weight="900" fill="#1E3A8A">PENERIMAAN</text>'
            .'<text x="85" y="210" font-family="Arial, sans-serif" font-size="68" font-weight="900" fill="#F59E0B">PESERTA DIDIK BARU</text>'
            .'<rect x="85" y="240" width="370" height="54" rx="12" fill="#1E3A8A"/>'
            .'<text x="270" y="276" font-family="Arial, sans-serif" font-size="25" font-weight="bold" fill="#FFFFFF" text-anchor="middle">TAHUN AJARAN 2026/2027</text>'
            .'<text x="85" y="345" font-family="Arial, sans-serif" font-size="28" font-style="italic" fill="#334155">"Membentuk Generasi Qur\'ani, Berakhlak Mulia &amp; Unggul Prestasi"</text>'
            .'<rect x="85" y="395" width="330" height="68" rx="14" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>'
            .'<text x="250" y="438" font-family="Arial, sans-serif" font-size="23" font-weight="bold" fill="#1E3A8A" text-anchor="middle">✓ KURIKULUM MERDEKA</text>'
            .'<rect x="435" y="395" width="330" height="68" rx="14" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>'
            .'<text x="600" y="438" font-family="Arial, sans-serif" font-size="23" font-weight="bold" fill="#1E3A8A" text-anchor="middle">✓ PRESTASI UNGGUL</text>'
            .'<rect x="785" y="395" width="330" height="68" rx="14" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>'
            .'<text x="950" y="438" font-family="Arial, sans-serif" font-size="23" font-weight="bold" fill="#1E3A8A" text-anchor="middle">✓ FASILITAS LENGKAP</text>'
            .'<rect x="1180" y="70" width="330" height="56" rx="28" fill="#1E3A8A"/>'
            .'<text x="1345" y="107" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#FFE15D" text-anchor="middle">KUOTA TERBATAS!</text>'
            .'<rect x="1160" y="160" width="370" height="270" rx="24" fill="#1B9C85"/>'
            .'<text x="1345" y="280" font-family="Arial, sans-serif" font-size="42" font-weight="900" fill="#FFFFFF" text-anchor="middle">MIN 1 CIAMIS</text>'
            .'<text x="1345" y="335" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#FFE15D" text-anchor="middle">KLIK DAFTAR SEKARANG →</text>'
            .'</svg>';

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
    }

    /**
     * Mengambil Data Singleton Pengaturan Banner & Popup PPDB
     */
    public static function ambilData(): self
    {
        self::pastikanTabelSiap();

        $data = self::first();
        if (! $data) {
            $data = self::create([
                'judul' => 'Penerimaan Peserta Didik Baru (PPDB) MIN 1 Ciamis',
                'subjudul' => "Membentuk Generasi Qur'ani, Berakhlak Mulia & Unggul Prestasi",
                'tipe_link' => 'popup',
                'link_url' => '#ppdb',
                'is_active' => true,
                'is_active_popup' => true,
                'auto_popup' => false,
                'popup_judul' => 'Informasi & Pendaftaran PPDB MIN 1 Ciamis',
                'popup_badge' => 'PPDB ONLINE T.A 2026/2027',
                'popup_konten' => "Penerimaan Peserta Didik Baru (PPDB) MIN 1 Ciamis Tahun Ajaran 2026/2027 membuka Jalur Prestasi, Tahfidz, dan Reguler.\n\nKeunggulan MIN 1 Ciamis:\n✓ Penerapan Kurikulum Merdeka Terpadu Berbasis Karakter\n✓ Program Tahfidz Al-Qur'an & Bimbingan Ibadah Praktis Harian\n✓ Pembinaan Ekstrakurikuler Unggulan & Prestasi Juara\n✓ Fasilitas Pembelajaran Digital, Smart Board & Lingkungan Asri\n\nSilakan hubungi Panitia PPDB melalui WhatsApp resmi atau klik tombol daftar di bawah untuk pengisian formulir online.",
                'popup_tombol_teks' => 'Daftar Online Sekarang',
                'popup_tombol_url' => 'https://wa.me/6282215650442?text=Halo%20Panitia%20PPDB%20MIN%201%20Ciamis%2C%20saya%20ingin%20mendaftar%20siswa%20baru',
                'popup_wa_nomor' => '082215650442',
                'popup_wa_teks' => 'Halo Panitia PPDB MIN 1 Ciamis, saya ingin menanyakan informasi pendaftaran siswa baru.',
            ]);
        }

        return $data;
    }

    /**
     * Memastikan Tabel banner_ppdbs Siap
     */
    public static function pastikanTabelSiap(): void
    {
        if (! Schema::hasTable('banner_ppdbs')) {
            Schema::create('banner_ppdbs', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 200)->default('Penerimaan Peserta Didik Baru (PPDB) MIN 1 Ciamis');
                $table->string('subjudul', 255)->default("Membentuk Generasi Qur'ani, Berakhlak Mulia & Unggul Prestasi");
                $table->string('gambar', 255)->nullable();
                $table->string('tipe_link', 30)->default('popup');
                $table->string('link_url', 255)->nullable()->default('#ppdb');
                $table->boolean('is_active')->default(true);

                $table->boolean('is_active_popup')->default(true);
                $table->boolean('auto_popup')->default(false);
                $table->string('popup_judul', 200)->default('Informasi & Pendaftaran PPDB MIN 1 Ciamis');
                $table->string('popup_badge', 100)->default('PPDB ONLINE T.A 2026/2027');
                $table->string('popup_gambar', 255)->nullable();
                $table->longText('popup_konten')->nullable();
                $table->string('popup_tombol_teks', 100)->default('Daftar Online Sekarang');
                $table->string('popup_tombol_url', 255)->default('https://wa.me/6282215650442?text=Halo%20Panitia%20PPDB%20MIN%201%20Ciamis');
                $table->string('popup_wa_nomor', 50)->default('082215650442');
                $table->string('popup_wa_teks', 255)->default('Halo Panitia PPDB MIN 1 Ciamis, saya ingin menanyakan informasi pendaftaran siswa baru.');
                $table->timestamps();
            });
        }
    }
}
