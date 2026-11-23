<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\MataPelajaran;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MataPelajaranController extends Controller
{
    /**
     * Memastikan seluruh kolom tambahan di tabel mata_pelajarans tersedia (cukup cek 1x per request).
     */
    private function pastikanKolomMapelLengkap(): void
    {
        static $kolomDicek = false;
        if ($kolomDicek) {
            return;
        }
        $kolomDicek = true;

        if (! Schema::hasColumn('mata_pelajarans', 'kelompok')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->string('kelompok', 100)->default('Wajib / Umum')->nullable();
            });
        }
        if (! Schema::hasColumn('mata_pelajarans', 'tingkat')) {
            Schema::table('mata_pelajarans', function (Blueprint $table) {
                $table->string('tingkat', 50)->default('Semua Tingkat (1-6)')->nullable();
            });
        }
        if (! Schema::hasColumn('mata_pelajarans', 'kkm')) {
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
     * Tambah Mata Pelajaran Baru (bisa sekaligus diterapkan ke kelas terpilih).
     */
    public function store(Request $request)
    {
        $this->pastikanKolomMapelLengkap();

        $request->validate([
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string|max:150',
            'kelompok' => 'nullable|string|max:100',
            'tingkat' => 'nullable|string|max:50',
            'kkm' => 'nullable|integer|min:0|max:100',
            'gtk_id' => 'nullable|exists:gtks,id',
            'kelas_ids' => 'nullable|array',
            'kelas_ids.*' => 'integer|exists:kelas,id',
        ], [
            'kode_mapel.unique' => 'Kode Mata Pelajaran ini sudah digunakan, gunakan kode lain!',
        ]);

        $mapel = new MataPelajaran;
        $mapel->kode_mapel = strtoupper(trim($request->kode_mapel));
        $mapel->nama_mapel = trim($request->nama_mapel);
        $mapel->kelompok = $request->kelompok ?: 'Wajib / Umum';
        $mapel->tingkat = $request->tingkat ?: 'Semua Tingkat (1-6)';
        $mapel->kkm = $request->filled('kkm') ? (int) $request->kkm : 75;
        $mapel->save();

        $jmlKelas = 0;
        if ($request->filled('kelas_ids') && is_array($request->kelas_ids)) {
            $kelasMap = Kelas::whereIn('id', $request->kelas_ids)->get()->keyBy('id');

            foreach ($request->kelas_ids as $klsId) {
                $klsObj = $kelasMap->get($klsId);
                $guruId = $request->gtk_id ?: ($klsObj->wali_kelas_id ?? null);

                KelasMapel::updateOrCreate(
                    ['kelas_id' => $klsId, 'mapel_id' => $mapel->id],
                    ['guru_id' => $guruId]
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
            'kode_mapel' => 'required|string|max:30|unique:mata_pelajarans,kode_mapel,'.$mapel->id,
            'nama_mapel' => 'required|string|max:150',
            'kelompok' => 'nullable|string|max:100',
            'tingkat' => 'nullable|string|max:50',
            'kkm' => 'nullable|integer|min:0|max:100',
        ]);

        $mapel->kode_mapel = strtoupper(trim($request->kode_mapel));
        $mapel->nama_mapel = trim($request->nama_mapel);
        $mapel->kelompok = $request->kelompok ?: 'Wajib / Umum';
        $mapel->tingkat = $request->tingkat ?: 'Semua Tingkat (1-6)';
        if ($request->filled('kkm')) {
            $mapel->kkm = (int) $request->kkm;
        }
        $mapel->save();

        return redirect()->back()->with('success', 'Data Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        KelasMapel::where('mapel_id', $mapel->id)->delete();
        $mapel->delete();

        return redirect()->back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || ! is_array($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 Mata Pelajaran yang ingin dihapus!');
        }

        KelasMapel::whereIn('mapel_id', $ids)->delete();
        MataPelajaran::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids).' Mata Pelajaran terpilih berhasil dihapus!');
    }
}