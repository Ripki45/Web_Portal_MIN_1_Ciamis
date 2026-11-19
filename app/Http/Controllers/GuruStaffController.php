<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\ProfilMadrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GuruStaffController extends Controller
{
    /**
     * Halaman Dashboard Penyusun Point A, B, C... & Dropdown GTK
     */
    public function index()
    {
        $profil = ProfilMadrasah::ambilData();
        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        $strukturPoints = $profil->struktur_guru_staff ?? [];

        // Jika masih kosong, siapkan 1 contoh Point A agar Superadmin mudah melihat formatnya
        if (empty($strukturPoints)) {
            $contohGtkId = $gtks->first()->id ?? '';
            $strukturPoints = [
                [
                    'kode' => 'A',
                    'nama' => 'Penjamin Mutu Madrasah (PMM)',
                    'rows' => [
                        [
                            'jabatan' => 'Koordinator Penjamin Mutu',
                            'gtk_id' => $contohGtkId,
                        ],
                    ],
                ],
            ];
        }

        return view('guru_staff.index', compact('profil', 'gtks', 'strukturPoints'));
    }

    /**
     * Simpan Banner Foto Bersama & Struktur Point A, B, C...
     */
    public function update(Request $request)
    {
        $profil = ProfilMadrasah::ambilData();

        $request->validate([
            'judul_guru_staff' => ['required', 'string', 'max:180'],
            'subjudul_guru_staff' => ['nullable', 'string', 'max:255'],
            'foto_bersama_guru' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        // Susun Array Point A, B, C, dst.
        $strukturHasil = [];
        $rawPoints = $request->input('points', []);

        foreach ($rawPoints as $pData) {
            $kode = strtoupper(strip_tags(trim((string) ($pData['kode'] ?? ''))));
            $namaPoint = strip_tags(trim((string) ($pData['nama'] ?? '')));

            if (empty($namaPoint)) {
                continue;
            }

            $rowsHasil = [];
            $rawRows = $pData['rows'] ?? [];
            foreach ($rawRows as $rData) {
                $jabatanTugas = strip_tags(trim((string) ($rData['jabatan'] ?? '')));
                $gtkId = (int) ($rData['gtk_id'] ?? 0);

                if ($gtkId > 0 or ! empty($jabatanTugas)) {
                    $rowsHasil[] = [
                        'jabatan' => $jabatanTugas,
                        'gtk_id' => $gtkId,
                    ];
                }
            }

            $strukturHasil[] = [
                'kode' => ! empty($kode) ? $kode : 'A',
                'nama' => $namaPoint,
                'rows' => $rowsHasil,
            ];
        }

        $updateData = [
            'judul_guru_staff' => strip_tags(trim($request->judul_guru_staff)),
            'subjudul_guru_staff' => strip_tags(trim((string) $request->subjudul_guru_staff)),
            'struktur_guru_staff' => $strukturHasil,
        ];

        if ($request->hasFile('foto_bersama_guru')) {
            if ($profil->foto_bersama_guru && ! str_starts_with($profil->foto_bersama_guru, 'frontend/') && Storage::disk('public')->exists($profil->foto_bersama_guru)) {
                Storage::disk('public')->delete($profil->foto_bersama_guru);
            }
            $updateData['foto_bersama_guru'] = $request->file('foto_bersama_guru')->store('profil_madrasah/guru_staff', 'public');
        }

        $profil->update($updateData);

        return redirect()->back()->with('success', 'Susunan Point Jabatan & Halaman Guru/Staff berhasil disimpan!');
    }

    /**
     * Halaman Publik Sub-Menu 3: Guru dan Staff (/guru-dan-staff)
     */
    public function showFrontend()
    {
        $profil = ProfilMadrasah::ambilData();
        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        // Peta lookup cepat ID GTK -> Model GTK (Nama otomatis selalu sinkron dengan Master GTK)
        $gtkMap = $gtks->keyBy('id');

        $strukturPoints = $profil->struktur_guru_staff ?? [];

        // Cek daftar ID guru yang menjadi Wali Kelas dari tabel kelas (jika ada)
        $waliKelasMap = [];
        if (Schema::hasTable('kelas')) {
            $kelasRows = Kelas::all();
            foreach ($kelasRows as $kls) {
                $idWali = $kls->wali_kelas_id ?? $kls->gtk_id ?? null;
                if (! empty($idWali)) {
                    $waliKelasMap[(int) $idWali] = 'Wali Kelas '.($kls->nama_kelas ?? '');
                }
            }
        }

        // Perkaya setiap data GTK dengan atribut Golongan/Status dan Jabatan yang bersih untuk filtering
        $daftarGolongan = [];
        $daftarJabatan = [];

        $gtksProcessed = $gtks->map(function ($g) use (&$daftarGolongan, &$daftarJabatan, $waliKelasMap) {
            // 1. Deteksi Golongan / Status Kepegawaian dari kolom database GTK
            $golRaw = trim((string) (
                $g->golongan
                ?? $g->pangkat_golongan
                ?? $g->status_kepegawaian
                ?? $g->status_pegawai
                ?? ''
            ));

            if (empty($golRaw)) {
                $nipVal = trim((string) ($g->nip ?? ''));
                $golRaw = (strlen($nipVal) >= 15) ? 'PNS / ASN' : 'Non-ASN';
            }

            $daftarGolongan[] = $golRaw;

            // 2. Deteksi Jabatan & Posisi dari database GTK
            $jabUtama = trim((string) ($g->jabatan->nama_jabatan ?? ''));
            $posUtama = trim((string) ($g->posisi->nama_posisi ?? ''));
            $isWali = isset($waliKelasMap[$g->id]);

            $listJabPersonil = [];
            if (! empty($jabUtama)) {
                $listJabPersonil[] = $jabUtama;
                $daftarJabatan[] = $jabUtama;
            }
            if (! empty($posUtama) && $posUtama !== $jabUtama) {
                $listJabPersonil[] = $posUtama;
                $daftarJabatan[] = $posUtama;
            }
            if ($isWali) {
                $listJabPersonil[] = 'Wali Kelas';
                $daftarJabatan[] = 'Wali Kelas';
            }

            if (empty($listJabPersonil)) {
                $listJabPersonil[] = 'Pendidik & Tenaga Kependidikan';
                $daftarJabatan[] = 'Pendidik & Tenaga Kependidikan';
            }

            $g->golongan_tampil = $golRaw;
            $g->jabatan_tampil = ! empty($posUtama) ? $posUtama : ($jabUtama ?: 'Pendidik & Tenaga Kependidikan');
            $g->wali_info = $waliKelasMap[$g->id] ?? null;
            $g->tags_jabatan = array_values(array_unique($listJabPersonil));

            return $g;
        });

        $daftarGolongan = array_values(array_unique(array_filter($daftarGolongan)));
        $daftarJabatan = array_values(array_unique(array_filter($daftarJabatan)));

        return view('frontend.guru_staff', compact(
            'profil',
            'strukturPoints',
            'gtkMap',
            'gtksProcessed',
            'daftarGolongan',
            'daftarJabatan'
        ));
    }
}
