<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananCepat extends Model
{
    protected $table = 'layanan_cepats';

    protected $fillable = [
        'nama_layanan',
        'icon',
        'link_url',
        'target',
        'urutan',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Daftar rekomendasi ikon Font Awesome populer untuk layanan madrasah & pendidikan.
     *
     * @return array<string, string>
     */
    public static function daftarIconRekomendasi(): array
    {
        return [
            'fas fa-qrcode' => 'Scanner QR / Barcode',
            'fas fa-server' => 'Server / Database / EMIS',
            'fas fa-file-signature' => 'Rapor Digital (RDM)',
            'fas fa-id-card' => 'Dapodik / Kartu Siswa',
            'fas fa-user-check' => 'Verval PD / Validasi',
            'fas fa-coins' => 'BOS / Keuangan / Anggaran',
            'fas fa-graduation-cap' => 'SNPMB / Alumni / Akademik',
            'fas fa-university' => 'PDDikti / Kampus / Lembaga',
            'fas fa-database' => 'Data Pokok Pendidikan',
            'fas fa-chalkboard-teacher' => 'SIMPATIKA / Guru & Pendidik',
            'fas fa-mosque' => 'PUSAKA / Kementerian Agama',
            'fas fa-book-reader' => 'E-Perpustakaan / SIBI',
            'fas fa-book' => 'Buku / Modul Pembelajaran',
            'fas fa-laptop-code' => 'TIK / Komputer & Lab',
            'fas fa-globe' => 'Portal / Website Luar',
            'fas fa-envelope' => 'Surat / Pengumuman / Kotak Pesan',
            'fas fa-phone-alt' => 'Call Center / Kontak',
            'fa-brands fa-whatsapp' => 'WhatsApp Resmi / Bantuan',
            'fa-brands fa-youtube' => 'Channel YouTube',
            'fas fa-award' => 'Prestasi / Sertifikat',
        ];
    }

    /**
     * @deprecated Tabel dikelola resmi melalui Laravel Migrations.
     */
    public static function pastikanTabelSiap(): void
    {
        // Dikosongkan untuk menjaga kompatibilitas tanpa query Schema::hasTable berulang.
    }
}