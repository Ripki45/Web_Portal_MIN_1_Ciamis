<?php

namespace Database\Seeders;

use App\Models\AgendaAkademik;
use App\Models\KaldikDokumen;
use Illuminate\Database\Seeder;

class Kaldik20262027Seeder extends Seeder
{
    public function run(): void
    {
        $ta = '2026/2027';

        KaldikDokumen::updateOrCreate(
            ['tahun_ajaran' => $ta],
            [
                'dasar_hukum' => 'Lampiran Keputusan Direktur Jenderal Pendidikan Islam Nomor 4860 Tahun 2026 Tentang Pedoman Kalender Pendidikan Madrasah Tahun Ajaran 2026/2027',
                'hari_kerja' => 6,
                'tanggal_pengesahan' => '2026-07-13',
                'nama_kepsek' => 'IIM SITI HALIMAH, S.Ag., M.Pd.',
                'nip_kepsek' => '197206051997032003',
                'catatan_kaki' => 'Rincian hari efektif ini bersifat dinamis dan menyesuaikan dengan Keputusan Pemerintah (SKB 3 Menteri) mengenai Hari Libur Nasional dan Cuti Bersama.',
            ]
        );

        $agendas = [
            // Semester Gasal (Juli - Desember 2026)
            ['gasal', 'Libur Akhir Tahun Ajaran 2025/2026', 'libur_semester', '2026-07-01', '2026-07-11', true, false, 'Libur sebelum dimulainya tahun ajaran baru 2026/2027.'],
            ['gasal', 'Awal Masuk & Pengenalan Lingkungan Madrasah (MATSAMA)', 'matsama', '2026-07-13', '2026-07-18', false, false, 'Permulaan Tahun Ajaran 2026/2027 sekaligus Masa Ta\'aruf Siswa Madrasah (MATSAMA).'],
            ['gasal', 'HUT Kemerdekaan Republik Indonesia', 'libur_nasional', '2026-08-17', '2026-08-17', true, false, 'Hari libur nasional peringatan Proklamasi Kemerdekaan RI.'],
            ['gasal', 'Maulid Nabi Muhammad SAW', 'libur_keagamaan', '2026-08-25', '2026-08-25', true, false, 'Hari besar keagamaan Islam peringatan Maulid Nabi Muhammad SAW.'],
            ['gasal', 'Asesmen Sumatif Akhir Semester (ASAS) Gasal', 'asesmen', '2026-11-23', '2026-12-05', false, false, 'Pelaksanaan evaluasi sumatif akhir Semester Gasal.'],
            ['gasal', 'Pengayaan, Perbaikan & Pengolahan Nilai', 'kegiatan_madrasah', '2026-12-14', '2026-12-18', false, false, 'Remedial, pengayaan materi, dan pengolahan nilai rapor oleh dewan guru.'],
            ['gasal', 'Penyerahan Laporan Hasil Belajar (Rapor) Gasal', 'rapor', '2026-12-19', '2026-12-19', false, false, 'Pembagian rapor Semester Gasal kepada orang tua/wali murid.'],
            ['gasal', 'Libur Akhir Semester Gasal', 'libur_semester', '2026-12-21', '2026-12-31', true, false, 'Jeda libur antar semester ganjil menuju semester genap.'],
            ['gasal', 'Hari Raya Natal', 'libur_nasional', '2026-12-25', '2026-12-25', true, false, 'Hari libur nasional.'],

            // Semester Genap (Januari - Juni 2027)
            ['genap', 'Tahun Baru Masehi 2027', 'libur_nasional', '2027-01-01', '2027-01-01', true, false, 'Hari libur nasional Tahun Baru Masehi.'],
            ['genap', 'Hari Amal Bhakti (HAB) Kementerian Agama', 'kegiatan_madrasah', '2027-01-03', '2027-01-03', false, true, 'Peringatan Hari Amal Bhakti Kementerian Agama RI.'],
            ['genap', 'Awal Masuk Semester Genap', 'kegiatan_madrasah', '2027-01-04', '2027-01-04', false, true, 'Hari pertama kegiatan belajar mengajar Semester Genap TA 2026/2027.'],
            ['genap', 'Isra Mi\'raj Nabi Muhammad SAW', 'libur_keagamaan', '2027-01-05', '2027-01-05', true, false, 'Hari besar keagamaan Islam peringatan Isra Mi\'raj.'],
            ['genap', 'Tahun Baru Imlek 2578', 'libur_nasional', '2027-02-06', '2027-02-06', true, false, 'Hari libur nasional Tahun Baru Imlek.'],
            ['genap', 'Libur Seputar Hari Raya Idul Fitri 1448 H', 'libur_keagamaan', '2027-03-06', '2027-03-13', true, false, 'Libur seputar Hari Raya Idul Fitri 1448 H (menyesuaikan ketetapan pemerintah).'],
            ['genap', 'Hari Raya Nyepi', 'libur_nasional', '2027-03-19', '2027-03-19', true, false, 'Hari libur nasional Hari Suci Nyepi.'],
            ['genap', 'Wafat Isa Almasih', 'libur_nasional', '2027-03-26', '2027-03-26', true, false, 'Hari libur nasional.'],
            ['genap', 'Perkiraan Rentang Ujian Madrasah', 'ujian', '2027-04-12', '2027-04-24', false, true, 'Perkiraan rentang waktu pelaksanaan Ujian Madrasah kelas VI.'],
            ['genap', 'Kenaikan Isa Almasih', 'libur_nasional', '2027-05-06', '2027-05-06', true, false, 'Hari libur nasional Kenaikan Isa Almasih.'],
            ['genap', 'Asesmen Sumatif Akhir Tahun (ASAT / ASAS Genap)', 'asesmen', '2027-05-24', '2027-06-05', false, false, 'Pelaksanaan evaluasi sumatif akhir Semester Genap.'],
            ['genap', 'Hari Lahir Pancasila', 'libur_nasional', '2027-06-01', '2027-06-01', true, false, 'Hari libur nasional peringatan Hari Lahir Pancasila.'],
            ['genap', 'Pengayaan, Perbaikan & Pengolahan Nilai', 'kegiatan_madrasah', '2027-06-14', '2027-06-18', false, false, 'Remedial, pengayaan, dan pengolahan rapor kenaikan kelas.'],
            ['genap', 'Penyerahan Rapor Semester Genap', 'rapor', '2027-06-19', '2027-06-19', false, false, 'Pembagian rapor kenaikan kelas dan kelulusan.'],
            ['genap', 'Libur Akhir Tahun Ajaran 2026/2027', 'libur_semester', '2027-06-21', '2027-06-30', true, false, 'Libur kenaikan kelas akhir Tahun Ajaran 2026/2027.'],
        ];

        foreach ($agendas as [$semester, $judul, $kategori, $mulai, $selesai, $libur, $efektifKbm, $desc]) {
            AgendaAkademik::updateOrCreate(
                [
                    'tahun_ajaran' => $ta,
                    'judul' => $judul,
                    'tanggal_mulai' => $mulai,
                ],
                [
                    'semester' => $semester,
                    'kategori' => $kategori,
                    'tanggal_selesai' => $selesai,
                    'is_hari_libur' => $libur,
                    'is_efektif_kbm' => $efektifKbm,
                    'deskripsi' => $desc,
                    'is_publik' => true,
                ]
            );
        }
    }
}