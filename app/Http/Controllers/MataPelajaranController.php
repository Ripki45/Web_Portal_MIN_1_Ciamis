<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Gtk;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::with(['kelasMapels.kelas', 'kelasMapels.guru'])
            ->orderBy('id', 'asc')
            ->get();

        $kelasList = Kelas::with(['waliKelas', 'kelasMapels.mapel', 'kelasMapels.guru'])
            ->orderBy('tingkat', 'asc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        return view('mapel.index', compact('mapels', 'kelasList', 'gtks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string|max:150',
            'kelompok'   => 'nullable|string|max:100',
            'tingkat'    => 'nullable|string|max:50',
            'kkm'        => 'nullable|integer|min:0|max:100',
        ], [
            'kode_mapel.unique' => 'Kode Mata Pelajaran ini sudah terdaftar!',
        ]);

        MataPelajaran::create([
            'kode_mapel' => strtoupper(trim($request->kode_mapel)),
            'nama_mapel' => trim($request->nama_mapel),
            'kelompok'   => $request->kelompok ?? 'Wajib / Umum',
            'tingkat'    => $request->tingkat ?? 'Semua Tingkat (1-6)',
            'kkm'        => $request->kkm ?? 75,
        ]);

        return redirect()->back()->with('success', 'Mata Pelajaran baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajarans,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:150',
            'kelompok'   => 'nullable|string|max:100',
            'tingkat'    => 'nullable|string|max:50',
            'kkm'        => 'nullable|integer|min:0|max:100',
        ]);

        $mapel->update([
            'kode_mapel' => strtoupper(trim($request->kode_mapel)),
            'nama_mapel' => trim($request->nama_mapel),
            'kelompok'   => $request->kelompok ?? 'Wajib / Umum',
            'tingkat'    => $request->tingkat ?? 'Semua Tingkat (1-6)',
            'kkm'        => $request->kkm ?? 75,
        ]);

        return redirect()->back()->with('success', 'Data Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }

    /**
     * HAPUS BANYAK MATA PELAJARAN SEKALIGUS (FITUR CEKLIS)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 Mata Pelajaran yang ingin dihapus!');
        }

        MataPelajaran::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Mata Pelajaran terpilih berhasil dihapus!');
    }
}