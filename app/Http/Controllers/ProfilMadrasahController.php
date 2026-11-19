<?php

namespace App\Http\Controllers;

use App\Models\ProfilMadrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProfilMadrasahController extends Controller
{
    /**
     * Halaman Dashboard Pengaturan Profil Madrasah
     */
    public function index()
    {
        $profil = ProfilMadrasah::ambilData();

        return view('profil_madrasah.index', compact('profil'));
    }

    /**
     * Simpan Perubahan 5 Bagian Profil Madrasah
     */
    public function update(Request $request)
    {
        $profil = ProfilMadrasah::ambilData();

        $request->validate([
            'judul_hero' => ['required', 'string', 'max:180'],
            'subjudul_hero' => ['nullable', 'string', 'max:200'],
            'deskripsi_1' => ['nullable', 'string'],
            'deskripsi_2' => ['nullable', 'string'],
            'deskripsi_3' => ['nullable', 'string'],
            'caption_foto' => ['nullable', 'string', 'max:400'],
            'visi_teks' => ['nullable', 'string'],
            'motto_teks' => ['nullable', 'string', 'max:255'],
            'motto_uraian' => ['nullable', 'string'],
            'foto_gedung' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        // 1. Susun Daftar Identitas Resmi Madrasah
        $identitasList = [];
        $idIcons = $request->input('identitas_icon', []);
        $idLabels = $request->input('identitas_label', []);
        $idNilais = $request->input('identitas_nilai', []);
        for ($i = 0; $i < count($idLabels); $i++) {
            $lbl = strip_tags(trim((string) ($idLabels[$i] ?? '')));
            $nil = strip_tags(trim((string) ($idNilais[$i] ?? '')));
            if (! empty($lbl) && ! empty($nil)) {
                $identitasList[] = [
                    'icon' => trim((string) ($idIcons[$i] ?? 'fas fa-info-circle')),
                    'label' => $lbl,
                    'nilai' => $nil,
                ];
            }
        }

        // 2. Susun Daftar Misi
        $misiList = [];
        foreach ($request->input('misi_item', []) as $item) {
            $teks = strip_tags(trim((string) $item));
            if (! empty($teks)) {
                $misiList[] = $teks;
            }
        }

        // 3. Susun Linimasa Sejarah Madrasah
        $sejarahList = [];
        $sejTahuns = $request->input('sejarah_tahun', []);
        $sejJuduls = $request->input('sejarah_judul', []);
        $sejUraians = $request->input('sejarah_uraian', []);
        for ($i = 0; $i < count($sejJuduls); $i++) {
            $jdl = strip_tags(trim((string) ($sejJuduls[$i] ?? '')));
            if (! empty($jdl)) {
                $sejarahList[] = [
                    'tahun' => strip_tags(trim((string) ($sejTahuns[$i] ?? ''))),
                    'judul' => $jdl,
                    'uraian' => strip_tags(trim((string) ($sejUraians[$i] ?? ''))),
                ];
            }
        }

        // 4. Susun Kepala Sekolah dari Masa ke Masa
        $kepsekMasaList = [];
        $kNamas = $request->input('kepsek_nama', []);
        $kPeriodes = $request->input('kepsek_periode', []);
        $kFotoLamas = $request->input('kepsek_foto_lama', []);
        $kAktifIndex = (string) $request->input('kepsek_aktif_index', '');
        $kFotoFiles = $request->file('kepsek_foto', []);

        for ($i = 0; $i < count($kNamas); $i++) {
            $nama = strip_tags(trim((string) ($kNamas[$i] ?? '')));
            if (empty($nama)) {
                continue;
            }

            $pathFoto = trim((string) ($kFotoLamas[$i] ?? ''));
            if (isset($kFotoFiles[$i]) && $kFotoFiles[$i]->isValid()) {
                if (! empty($pathFoto) && ! str_starts_with($pathFoto, 'frontend/') && Storage::disk('public')->exists($pathFoto)) {
                    Storage::disk('public')->delete($pathFoto);
                }
                $pathFoto = $kFotoFiles[$i]->store('profil_madrasah/kepsek', 'public');
            }

            $kepsekMasaList[] = [
                'nama' => $nama,
                'periode' => strip_tags(trim((string) ($kPeriodes[$i] ?? ''))),
                'foto' => $pathFoto,
                'is_aktif' => ($kAktifIndex !== '' && (int) $kAktifIndex === $i),
            ];
        }

        $updateData = [
            'judul_hero' => strip_tags(trim($request->judul_hero)),
            'subjudul_hero' => strip_tags(trim((string) $request->subjudul_hero)),
            'deskripsi_1' => trim((string) $request->deskripsi_1),
            'deskripsi_2' => trim((string) $request->deskripsi_2),
            'deskripsi_3' => trim((string) $request->deskripsi_3),
            'caption_foto' => strip_tags(trim((string) $request->caption_foto)),
            'identitas_list' => $identitasList,
            'visi_teks' => trim((string) $request->visi_teks),
            'misi_list' => $misiList,
            'motto_teks' => strip_tags(trim((string) $request->motto_teks)),
            'motto_uraian' => trim((string) $request->motto_uraian),
            'sejarah_list' => $sejarahList,
            'kepsek_masa_list' => $kepsekMasaList,
        ];

        if ($request->hasFile('foto_gedung')) {
            if ($profil->foto_gedung && ! str_starts_with($profil->foto_gedung, 'frontend/') && Storage::disk('public')->exists($profil->foto_gedung)) {
                Storage::disk('public')->delete($profil->foto_gedung);
            }
            $updateData['foto_gedung'] = $request->file('foto_gedung')->store('profil_madrasah', 'public');
        }

        $profil->update($updateData);

        return redirect()->back()->with('success', 'Data Profil Madrasah berhasil diperbarui!');
    }

    /**
     * 🔗 PASANG OTOMATIS MENU INDUK "PROFIL MADRASAH" + 3 SUBMENU UTAMA KE NAVBAR
     */
    public function sinkronKeNavbar()
    {
        if (! Schema::hasTable('nav_menus')) {
            return redirect()->back()->with('error', 'Tabel nav_menus belum tersedia.');
        }

        $kolomTabel = Schema::getColumnListing('nav_menus');

        $buatDataMenu = function (string $nama, string $url, ?int $parentId, int $urutan) use ($kolomTabel) {
            $payload = [
                'nama_menu' => $nama,
                'url' => $url,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (in_array('parent_id', $kolomTabel)) {
                $payload['parent_id'] = $parentId;
            }
            if (in_array('target', $kolomTabel)) {
                $payload['target'] = '_self';
            }
            if (in_array('urutan', $kolomTabel)) {
                $payload['urutan'] = $urutan;
            }
            if (in_array('is_active', $kolomTabel)) {
                $payload['is_active'] = true;
            }

            return $payload;
        };

        // 1. Cari atau buat Menu Induk "Profil Madrasah"
        $menuInduk = DB::table('nav_menus')
            ->where('nama_menu', 'like', '%Profil%')
            ->whereNull('parent_id')
            ->first();

        if ($menuInduk) {
            DB::table('nav_menus')->where('id', $menuInduk->id)->update([
                'nama_menu' => 'Profil Madrasah',
                'url' => '#',
                'updated_at' => now(),
            ]);
            $indukId = $menuInduk->id;
        } else {
            $indukId = DB::table('nav_menus')->insertGetId(
                $buatDataMenu('Profil Madrasah', '#', null, 2)
            );
        }

        // Bersihkan sub-menu anchor lama (#profil-intro, #identitas, dll.) jika sebelumnya sempat terpasang
        DB::table('nav_menus')
            ->where('parent_id', $indukId)
            ->where('url', 'like', '/profil-madrasah#%')
            ->delete();

        // 2. Daftarkan 3 Sub-Menu Utama di bawah Induk "Profil Madrasah"
        $daftarSubmenu = [
            [
                'nama' => 'Profil Madrasah',
                'url' => '/profil-madrasah',
            ],
            [
                'nama' => 'Kepala Madrasah dan Staff',
                'url' => '/kepala-madrasah-dan-staff',
            ],
            [
                'nama' => 'Guru dan Staff',
                'url' => '/guru-dan-staff',
            ],
        ];

        foreach ($daftarSubmenu as $idx => $sub) {
            $subEksis = DB::table('nav_menus')
                ->where('parent_id', $indukId)
                ->where('nama_menu', $sub['nama'])
                ->first();

            if ($subEksis) {
                DB::table('nav_menus')->where('id', $subEksis->id)->update([
                    'url' => $sub['url'],
                    'urutan' => $idx + 1,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('nav_menus')->insert(
                    $buatDataMenu($sub['nama'], $sub['url'], $indukId, $idx + 1)
                );
            }
        }

        return redirect()->back()->with('success', 'Menu Induk "Profil Madrasah" beserta 3 Sub-Menu (Profil Madrasah, Kepala Madrasah dan Staff, Guru dan Staff) berhasil dipasang ke Navbar!');
    }

    /**
     * Halaman Publik Profil Madrasah (/profil-madrasah)
     */
    public function showFrontend()
    {
        $profil = ProfilMadrasah::ambilData();

        return view('frontend.profil_madrasah', compact('profil'));
    }
}
