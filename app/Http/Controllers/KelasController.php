<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Gtk;
use App\Models\MataPelajaran;
use App\Models\KelasMapel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $kelasList = Kelas::with(['waliKelas', 'siswas' => function ($q) {
                $q->where('status', 'Aktif');
            }, 'kelasMapels.mapel', 'kelasMapels.guru'])
            ->orderBy('tingkat', 'asc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        // Daftar GTK untuk Wali Kelas & Guru Mapel
        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        // Daftar Master Mata Pelajaran
        $mapels = MataPelajaran::orderBy('id', 'asc')->get();

        return view('kelas.index', compact('kelasList', 'gtks', 'mapels', 'tahunAktif'));
    }

    /**
     * SIMPAN ROMBEL BARU (KHUSUS WALI KELAS, RUANGAN, & KAPASITAS)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tingkat'       => 'required|integer|min:1|max:6',
            'nama_kelas'    => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:gtks,id',
            'ruangan'       => 'nullable|string|max:100',
            'kapasitas'     => 'required|integer|min:1|max:100',
        ]);

        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // Cegah nama kelas ganda
        $cekGanda = Kelas::where('nama_kelas', trim($request->nama_kelas))->first();
        if ($cekGanda) {
            return redirect()->back()->with('error', 'Rombel ' . $request->nama_kelas . ' sudah terdaftar!');
        }

        Kelas::create([
            'tahun_ajaran_id' => $tahunAktif->id ?? null,
            'tingkat'         => $request->tingkat,
            'nama_kelas'      => trim($request->nama_kelas),
            'wali_kelas_id'   => $request->wali_kelas_id,
            'ruangan'         => $request->ruangan,
            'kapasitas'       => $request->kapasitas,
        ]);

        return redirect()->back()->with('success', 'Rombel / Kelas baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'tingkat'       => 'required|integer|min:1|max:6',
            'nama_kelas'    => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:gtks,id',
            'ruangan'       => 'nullable|string|max:100',
            'kapasitas'     => 'required|integer|min:1|max:100',
        ]);

        $kelas->update([
            'tingkat'       => $request->tingkat,
            'nama_kelas'    => trim($request->nama_kelas),
            'wali_kelas_id' => $request->wali_kelas_id,
            'ruangan'       => $request->ruangan,
            'kapasitas'     => $request->kapasitas,
        ]);

        return redirect()->back()->with('success', 'Data Kelas ' . $kelas->nama_kelas . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->back()->with('success', 'Data Kelas berhasil dihapus!');
    }

   /**
     * PLOTTING BANYAK MATA PELAJARAN & BANYAK KELAS SEKALIGUS (1 KALI KLIK SIMPAN)
     */
    public function storePlottingMapel(Request $request)
    {
        $request->validate([
            'gtk_id'      => 'required|exists:gtks,id',
            'mapel_ids'   => 'required|array|min:1',
            'mapel_ids.*' => 'exists:mata_pelajarans,id',
            'kelas_ids'   => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
        ], [
            'mapel_ids.required' => 'Pilih (ceklis) minimal 1 Mata Pelajaran pada tabel!',
            'kelas_ids.required' => 'Pilih (ceklis) minimal 1 Kelas tempat mata pelajaran diterapkan!',
        ]);

        $totalTersimpan = 0;

        foreach ($request->kelas_ids as $klsId) {
            foreach ($request->mapel_ids as $mpId) {
                // updateOrCreate menjamin tidak akan pernah ada mapel ganda di kelas yang sama
                KelasMapel::updateOrCreate(
                    [
                        'kelas_id' => $klsId,
                        'mapel_id' => $mpId,
                    ],
                    [
                        'gtk_id'   => $request->gtk_id,
                    ]
                );
                $totalTersimpan++;
            }
        }

        $jmlMapel = count($request->mapel_ids);
        $jmlKelas = count($request->kelas_ids);

        return redirect()->back()->with(
            'success',
            "Berhasil menyimpan {$jmlMapel} Mata Pelajaran ke {$jmlKelas} Kelas sekaligus!"
        );
    }

    /**
     * DASHBOARD DETAIL KELAS (SAAT KLIK TOMBOL PANAH DI KARTU KELAS)
     */
    public function show($id)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $kelas = Kelas::with([
            'waliKelas.jabatan',
            'siswas' => function ($q) {
                $q->where('status', 'Aktif')->orderBy('nama_siswa', 'asc');
            },
            'kelasMapels.mapel',
            'kelasMapels.guru'
        ])->findOrFail($id);

        $gtks      = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();
        $mapels    = MataPelajaran::orderBy('id', 'asc')->get();
        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

        $totalL = $kelas->siswas->where('jenis_kelamin', 'L')->count();
        $totalP = $kelas->siswas->where('jenis_kelamin', 'P')->count();

        return view('kelas.show', compact('kelas', 'tahunAktif', 'gtks', 'mapels', 'kelasList', 'totalL', 'totalP'));
    }

    public function destroyPlottingMapel($id)
    {
        $km = KelasMapel::findOrFail($id);
        $km->delete();

        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus dari kelas ini!');
    }

    public function bulkDestroyPlottingMapel(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 mata pelajaran yang ingin dihapus!');
        }

        KelasMapel::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Mata pelajaran terpilih berhasil dihapus dari kelas!');
    }
}