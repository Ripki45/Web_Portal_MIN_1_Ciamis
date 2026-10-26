<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Gtk;
use App\Models\KelasMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MataPelajaranController extends Controller
{
    /**
     * Otomatis memastikan seluruh kolom di tabel mata_pelajarans tersedia di SQLite
     */
    private function pastikanKolomMapelLengkap()
    {
        if (!Schema::hasColumn('mata_pelajarans', 'kelompok')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->string('kelompok', 100)->default('Wajib / Umum')->nullable();
            });
        }
        if (!Schema::hasColumn('mata_pelajarans', 'tingkat')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->string('tingkat', 50)->default('Semua Tingkat (1-6)')->nullable();
            });
        }
        if (!Schema::hasColumn('mata_pelajarans', 'kkm')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->integer('kkm')->default(75)->nullable();
            });
        }
    }

    public function index()
    {
        $this->pastikanKolomMapelLengkap();

        $mapels = MataPelajaran::with(['kelasMapels.kelas', 'kelasMapels.guru'])
            ->latest('id')
            ->get();

        $kelasList = Kelas::with(['waliKelas', 'kelasMapels.mapel', 'kelasMapels.guru'])
            ->orderBy('tingkat', 'asc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        return view('mapel.index', compact('mapels', 'kelasList', 'gtks'));
    }

    /**
     * TAMBAH MATA PELAJARAN BARU (BISA SEKALIGUS DITERAPKAN KE KELAS TERPILIH!)
     */
    public function store(Request $request)
    {
        $this->pastikanKolomMapelLengkap();

        $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string|max:150',
            'kelompok'   => 'nullable|string|max:100',
            'tingkat'    => 'nullable|string|max:50',
            'gtk_id'     => 'nullable|exists:gtks,id',
            'kelas_ids'  => 'nullable|array',
        ], [
            'kode_mapel.unique' => 'Kode Mata Pelajaran ini sudah digunakan, gunakan kode lain!',
        ]);

        // 1. Simpan Mata Pelajaran Baru ke Tabel Master
        $mapel = new MataPelajaran();
        $mapel->kode_mapel = strtoupper(trim($request->kode_mapel));
        $mapel->nama_mapel = trim($request->nama_mapel);
        $mapel->kelompok   = $request->kelompok ?: 'Wajib / Umum';
        $mapel->tingkat    = $request->tingkat ?: 'Semua Tingkat (1-6)';
        $mapel->kkm        = $request->kkm ?: 75;
        $mapel->save();

        // 2. Jika sekaligus memilih Kelas (dan Guru), langsung hubungkan otomatis!
        $jmlKelas = 0;
        if ($request->filled('kelas_ids') && is_array($request->kelas_ids)) {
            foreach ($request->kelas_ids as $klsId) {
                $klsObj = Kelas::find($klsId);
                // Jika guru tidak dipilih di form, otomatis gunakan Wali Kelas dari kelas tersebut
                $guruId = $request->gtk_id ?: ($klsObj->wali_kelas_id ?? null);

                KelasMapel::updateOrCreate(
                    ['kelas_id' => $klsId, 'mapel_id' => $mapel->id],
                    ['gtk_id'   => $guruId]
                );
                $jmlKelas++;
            }
        }

        $pesan = "Mata Pelajaran baru ({$mapel->nama_mapel}) berhasil ditambahkan!";
        if ($jmlKelas > 0) {
            $pesan .= " Sekaligus berhasil diterapkan ke {$jmlKelas} kelas.";
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function update(Request $request, $id)
    {
        $this->pastikanKolomMapelLengkap();

        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajarans,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:150',
            'kelompok'   => 'nullable|string|max:100',
            'tingkat'    => 'nullable|string|max:50',
        ]);

        $mapel->kode_mapel = strtoupper(trim($request->kode_mapel));
        $mapel->nama_mapel = trim($request->nama_mapel);
        $mapel->kelompok   = $request->kelompok ?: 'Wajib / Umum';
        $mapel->tingkat    = $request->tingkat ?: 'Semua Tingkat (1-6)';
        $mapel->save();

        return redirect()->back()->with('success', 'Data Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }

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