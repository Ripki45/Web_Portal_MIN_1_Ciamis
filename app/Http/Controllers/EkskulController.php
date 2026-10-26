<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\EkskulAnggota;
use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskuls = Ekskul::with(['pembina.jabatan', 'anggotas'])->orderBy('id', 'asc')->get();
        $gtks    = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        return view('ekskul.index', compact('ekskuls', 'gtks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekskul'  => 'required|string|max:100',
            'pembina_id'   => 'nullable|exists:gtks,id',
            'hari_latihan' => 'nullable|string|max:50',
            'jam_mulai'    => 'nullable|string|max:10',
            'jam_selesai'  => 'nullable|string|max:10',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $jamMulai   = $request->jam_mulai ?: '13:00';
        $jamSelesai = $request->jam_selesai ?: '16:30';
        $jamLatihan = $jamMulai . ' - ' . $jamSelesai;

        Ekskul::create([
            'nama_ekskul'  => trim($request->nama_ekskul),
            'pembina_id'   => $request->pembina_id,
            'hari_latihan' => $request->hari_latihan ?: 'Setiap Hari Kerja',
            'jam_latihan'  => $jamLatihan,
            'status'       => $request->status,
        ]);

        return redirect()->back()->with('success', 'Ekstrakurikuler baru beserta jam presensinya berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $ekskul = Ekskul::findOrFail($id);

        $request->validate([
            'nama_ekskul'  => 'required|string|max:100',
            'pembina_id'   => 'nullable|exists:gtks,id',
            'hari_latihan' => 'nullable|string|max:50',
            'jam_mulai'    => 'nullable|string|max:10',
            'jam_selesai'  => 'nullable|string|max:10',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $jamMulai   = $request->jam_mulai ?: '13:00';
        $jamSelesai = $request->jam_selesai ?: '16:30';
        $jamLatihan = $jamMulai . ' - ' . $jamSelesai;

        $ekskul->update([
            'nama_ekskul'  => trim($request->nama_ekskul),
            'pembina_id'   => $request->pembina_id,
            'hari_latihan' => $request->hari_latihan ?: 'Setiap Hari Kerja',
            'jam_latihan'  => $jamLatihan,
            'status'       => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data & Jam Presensi Ekstrakurikuler berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ekskul = Ekskul::findOrFail($id);
        $ekskul->delete();

        return redirect()->back()->with('success', 'Ekstrakurikuler berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 ekstrakurikuler yang ingin dihapus!');
        }

        Ekskul::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Ekstrakurikuler terpilih berhasil dihapus!');
    }

    /**
     * DASHBOARD KELOLA ANGGOTA EKSKUL (SISTEM DAFTAR VIA CEKLIS SISWA)
     */
    public function show(Request $request, $id)
    {
        $ekskul = Ekskul::with(['pembina', 'anggotas.siswa.kelas'])->findOrFail($id);
        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

        // Ambil semua siswa aktif untuk ditampilkan di Modal Pendaftaran Anggota
        $semuaSiswa = Siswa::with('kelas')
            ->where('status', 'Aktif')
            ->orderBy('kelas_id', 'asc')
            ->orderBy('nama_siswa', 'asc')
            ->get();

        $terdaftarIds = $ekskul->anggotas->pluck('siswa_id')->toArray();

        return view('ekskul.show', compact('ekskul', 'kelasList', 'semuaSiswa', 'terdaftarIds'));
    }

    /**
     * SIMPAN BANYAK ANGGOTA EKSKUL SEKALIGUS DARI CEKLIS TABEL SISWA
     */
    public function storeAnggota(Request $request, $id)
    {
        $ekskul = Ekskul::findOrFail($id);

        $request->validate([
            'siswa_ids'   => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswas,id',
        ], [
            'siswa_ids.required' => 'Ceklis minimal 1 siswa untuk didaftarkan ke ekstrakurikuler ini!',
        ]);

        $ditambahkan = 0;
        foreach ($request->siswa_ids as $siswaId) {
            EkskulAnggota::firstOrCreate([
                'ekskul_id' => $ekskul->id,
                'siswa_id'  => $siswaId,
            ]);
            $ditambahkan++;
        }

        return redirect()->back()->with('success', $ditambahkan . ' Siswa berhasil didaftarkan sebagai anggota ' . $ekskul->nama_ekskul . '!');
    }

    public function destroyAnggota($anggotaId)
    {
        $ag = EkskulAnggota::findOrFail($anggotaId);
        $ag->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dikeluarkan dari daftar anggota ekstrakurikuler.');
    }

    public function bulkDestroyAnggota(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 anggota yang ingin dikeluarkan!');
        }

        EkskulAnggota::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Anggota terpilih berhasil dikeluarkan dari ekstrakurikuler!');
    }
}