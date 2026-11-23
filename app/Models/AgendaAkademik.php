<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaAkademik extends Model
{
    protected $table = 'agenda_akademiks';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'judul',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_hari_libur',
        'is_efektif_kbm',
        'deskripsi',
        'is_publik',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_hari_libur' => 'boolean',
        'is_efektif_kbm' => 'boolean',
        'is_publik' => 'boolean',
    ];

    /**
     * Daftar kategori resmi Kaldik beserta warna kalender & badge WowDash.
     *
     * @return array<string, array{label: string, color: string, dot: string, badge: string}>
     */
    public static function daftarKategori(): array
    {
        return [
            'matsama' => [
                'label' => 'Pengenalan Lingkungan (MATSAMA)',
                'color' => '#0284c7',
                'dot' => 'bg-info-600',
                'badge' => 'bg-info-focus text-info-main',
            ],
            'asesmen' => [
                'label' => 'Asesmen Sumatif (ASAS / ASAT)',
                'color' => '#d97706',
                'dot' => 'bg-warning-600',
                'badge' => 'bg-warning-focus text-warning-main',
            ],
            'ujian' => [
                'label' => 'Rentang Ujian Madrasah',
                'color' => '#7c3aed',
                'dot' => 'bg-lilac-600',
                'badge' => 'bg-lilac-100 text-lilac-600',
            ],
            'rapor' => [
                'label' => 'Penyerahan Rapor',
                'color' => '#059669',
                'dot' => 'bg-success-600',
                'badge' => 'bg-success-focus text-success-main',
            ],
            'libur_semester' => [
                'label' => 'Libur Semester / Akhir Tahun',
                'color' => '#e11d48',
                'dot' => 'bg-danger-600',
                'badge' => 'bg-danger-focus text-danger-main',
            ],
            'libur_keagamaan' => [
                'label' => 'Libur Keagamaan / Idul Fitri',
                'color' => '#dc2626',
                'dot' => 'bg-danger-600',
                'badge' => 'bg-danger-focus text-danger-main',
            ],
            'libur_nasional' => [
                'label' => 'Hari Libur Nasional',
                'color' => '#ef4444',
                'dot' => 'bg-danger-600',
                'badge' => 'bg-danger-focus text-danger-main',
            ],
            'kegiatan_madrasah' => [
                'label' => 'Kegiatan Khusus Madrasah',
                'color' => '#0d6a56',
                'dot' => 'bg-primary-600',
                'badge' => 'bg-primary-50 text-primary-600',
            ],
        ];
    }

    public function getMetaKategoriAttribute(): array
    {
        $daftar = self::daftarKategori();

        return $daftar[$this->kategori] ?? $daftar['kegiatan_madrasah'];
    }

    public function getRentangTanggalLabelAttribute(): string
    {
        if ($this->tanggal_mulai->equalTo($this->tanggal_selesai)) {
            return $this->tanggal_mulai->translatedFormat('d M Y');
        }

        if ($this->tanggal_mulai->format('Y-m') === $this->tanggal_selesai->format('Y-m')) {
            return $this->tanggal_mulai->format('d').' – '.$this->tanggal_selesai->translatedFormat('d M Y');
        }

        return $this->tanggal_mulai->translatedFormat('d M Y').' – '.$this->tanggal_selesai->translatedFormat('d M Y');
    }

    /**
     * Format event untuk FullCalendar 6 (tanggal akhir bersifat eksklusif, jadi +1 hari).
     *
     * @return array<string, mixed>
     */
    public function toCalendarEvent(): array
    {
        $meta = $this->meta_kategori;

        return [
            'id' => $this->id,
            'title' => $this->judul,
            'start' => $this->tanggal_mulai->format('Y-m-d'),
            'end' => $this->tanggal_selesai->copy()->addDay()->format('Y-m-d'),
            'allDay' => true,
            'backgroundColor' => $meta['color'],
            'borderColor' => $meta['color'],
            'extendedProps' => [
                'id' => $this->id,
                'judul' => $this->judul,
                'tahun_ajaran' => $this->tahun_ajaran,
                'semester' => $this->semester,
                'kategori' => $this->kategori,
                'kategori_label' => $meta['label'],
                'kategori_color' => $meta['color'],
                'tanggal_mulai' => $this->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $this->tanggal_selesai->format('Y-m-d'),
                'rentang_label' => $this->rentang_tanggal_label,
                'is_hari_libur' => $this->is_hari_libur,
                'is_efektif_kbm' => $this->is_efektif_kbm,
                'is_publik' => $this->is_publik,
                'deskripsi' => $this->deskripsi ?: '',
            ],
        ];
    }
}