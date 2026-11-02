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
        $user      = auth()->user();
        $isPembina = $user && $user->role === 'pembina_ekskul';

        $query = Ekskul::with(['pembina.jabatan', 'anggotas'])->orderBy('id', 'asc');

        if ($isPembina) {
            $binaanIds = $user->getEkskulBinaanIds();
            $query->whereIn('id', $binaanIds);
        }

        $ekskuls = $query->get();
        $gtks    = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();

        return view('ekskul.index', compact('ekskuls', 'gtks', 'isPembina'));
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'pembina_ekskul') {
            return redirect()->back()->with('error', 'Pembina Ekskul tidak diizinkan menambah master ekstrakurikuler baru!');
        }

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
        $user      = auth()->user();
        $isPembina = $user && $user->role === 'pembina_ekskul';
        $ekskul    = Ekskul::findOrFail($id);

        if ($isPembina && !in_array($ekskul->id, $user->getEkskulBinaanIds())) {
            return redirect()->back()->with('error', 'Anda hanya dapat mengubah jadwal ekstrakurikuler yang Anda bina!');
        }

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

        // Jika Pembina Ekskul yang mengedit, kunci nama, pembina_id, dan status agar tidak berubah
        $dataUpdate = [
            'hari_latihan' => $request->hari_latihan ?: 'Setiap Hari Kerja',
            'jam_latihan'  => $jamLatihan,
        ];

        if (!$isPembina) {
            $dataUpdate['nama_ekskul'] = trim($request->nama_ekskul);
            $dataUpdate['pembina_id']  = $request->pembina_id;
            $dataUpdate['status']      = $request->status;
        }

        $ekskul->update($dataUpdate);

        return redirect()->back()->with('success', 'Jadwal & Jam Presensi Ekstrakurikuler berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (auth()->check() && auth()->user()->role === 'pembina_ekskul') {
            return redirect()->back()->with('error', 'Pembina Ekskul tidak diizinkan menghapus ekstrakurikuler!');
        }

        $ekskul = Ekskul::findOrFail($id);
        $ekskul->delete();

        return redirect()->back()->with('success', 'Ekstrakurikuler berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'pembina_ekskul') {
            return redirect()->back()->with('error', 'Pembina Ekskul tidak diizinkan menghapus ekstrakurikuler!');
        }

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
        $user      = auth()->user();
        $isPembina = $user && $user->role === 'pembina_ekskul';

        if ($isPembina && !in_array((int) $id, $user->getEkskulBinaanIds())) {
            return redirect()->route('ekskul.index')
                ->with('error', 'Akses ditolak! Anda hanya dapat mengelola anggota ekstrakurikuler yang Anda bina.');
        }

        $ekskul    = Ekskul::with(['pembina', 'anggotas.siswa.kelas'])->findOrFail($id);
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
        $user      = auth()->user();
        $isPembina = $user && $user->role === 'pembina_ekskul';

        if ($isPembina && !in_array((int) $id, $user->getEkskulBinaanIds())) {
            return redirect()->route('ekskul.index')->with('error', 'Akses ditolak!');
        }

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