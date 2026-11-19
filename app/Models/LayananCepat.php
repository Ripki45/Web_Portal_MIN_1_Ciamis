<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
     * Daftar rekomendasi ikon Font Awesome populer untuk layanan madrasah & pendidikan
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
     * Memastikan Tabel layanan_cepats Siap dan Mengisi 12 Layanan Default jika Kosong
     */
    public static function pastikanTabelSiap(): void
    {
        if (! Schema::hasTable('layanan_cepats')) {
            Schema::create('layanan_cepats', function (Blueprint $table) {
                $table->id();
                $table->string('nama_layanan', 150);
                $table->string('icon', 80)->default('fas fa-link');
                $table->string('link_url', 255);
                $table->string('target', 20)->default('_blank');
                $table->integer('urutan')->default(1);
                $table->boolean('is_active')->default(true);
                $table->string('keterangan', 255)->nullable();
                $table->timestamps();
            });
        }

        // Jika tabel baru dibuat atau masih kosong, isi dengan 12 layanan standar madrasah
        if (self::count() === 0) {
            $layananAwal = [
                ['nama' => 'Scanner QR Presensi',   'icon' => 'fas fa-qrcode',             'link' => '/presensi-scanner',                            'target' => '_blank', 'urutan' => 1],
                ['nama' => 'EMIS Madrasah',         'icon' => 'fas fa-server',             'link' => 'https://emis.kemenag.go.id/',                  'target' => '_blank', 'urutan' => 2],
                ['nama' => 'Rapor Digital (RDM)',   'icon' => 'fas fa-file-signature',     'link' => 'https://rdm.kemenag.go.id/',                   'target' => '_blank', 'urutan' => 3],
                ['nama' => 'Dapodik',               'icon' => 'fas fa-id-card',            'link' => 'https://dapo.kemdikbud.go.id/',               'target' => '_blank', 'urutan' => 4],
                ['nama' => 'Verval Peserta Didik',  'icon' => 'fas fa-user-check',         'link' => 'https://vervalpd.data.kemdikbud.go.id/',      'target' => '_blank', 'urutan' => 5],
                ['nama' => 'Portal BOS',            'icon' => 'fas fa-coins',              'link' => 'https://bos.kemenag.go.id/',                  'target' => '_blank', 'urutan' => 6],
                ['nama' => 'Portal SNPMB',          'icon' => 'fas fa-graduation-cap',     'link' => 'https://snpmb.bppp.kemdikbud.go.id/',         'target' => '_blank', 'urutan' => 7],
                ['nama' => 'PDDikti',               'icon' => 'fas fa-university',         'link' => 'https://pddikti.kemdikbud.go.id/',             'target' => '_blank', 'urutan' => 8],
                ['nama' => 'Data Pokok Pendidikan', 'icon' => 'fas fa-database',           'link' => 'https://referensi.data.kemdikbud.go.id/',     'target' => '_blank', 'urutan' => 9],
                ['nama' => 'SIMPATIKA Kemenag',     'icon' => 'fas fa-chalkboard-teacher', 'link' => 'https://simpatika.kemenag.go.id/',            'target' => '_blank', 'urutan' => 10],
                ['nama' => 'PUSAKA Kemenag',        'icon' => 'fas fa-mosque',             'link' => 'https://pusaka.kemenag.go.id/',               'target' => '_blank', 'urutan' => 11],
                ['nama' => 'E-Perpustakaan SIBI',   'icon' => 'fas fa-book-reader',        'link' => 'https://buku.kemdikbud.go.id/',               'target' => '_blank', 'urutan' => 12],
            ];

            foreach ($layananAwal as $item) {
                self::create([
                    'nama_layanan' => $item['nama'],
                    'icon' => $item['icon'],
                    'link_url' => $item['link'],
                    'target' => $item['target'],
                    'urutan' => $item['urutan'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
