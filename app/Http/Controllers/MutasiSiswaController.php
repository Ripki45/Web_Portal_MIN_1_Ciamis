<?php

namespace App\Http\Controllers;

use App\Models\MutasiSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MutasiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $kelasList  = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

        // 1. Data untuk Tab Kenaikan Kelas (Filter berdasarkan Kelas Asal yang dipilih)
        $siswaKenaikan = collect();
        if ($request->filled('asal_kelas_id')) {
            $siswaKenaikan = Siswa::with('kelas')
                ->where('status', 'Aktif')
                ->where('kelas_id', $request->asal_kelas_id)
                ->orderBy('nama_siswa', 'asc')
                ->get();
        }

        // 2. Data untuk Tab Mutasi Masuk
        $mutasiMasuk = MutasiSiswa::with(['siswa', 'kelasTujuan'])
            ->where('jenis_mutasi', 'Masuk')
            ->orderBy('id', 'asc')
            ->get();

        // 3. Data untuk Tab Mutasi Keluar
        $mutasiKeluar = MutasiSiswa::with(['siswa'])
            ->where('jenis_mutasi', 'Keluar')
            ->orderBy('id', 'asc')
            ->get();

        // Daftar siswa aktif untuk pilihan form Mutasi Keluar
        $siswaAktif = Siswa::with('kelas')->where('status', 'Aktif')->orderBy('nama_siswa', 'asc')->get();

        return view('mutasi.index', compact(
            'tahunAktif', 'kelasList', 'siswaKenaikan', 
            'mutasiMasuk', 'mutasiKeluar', 'siswaAktif'
        ));
    }

    /**
     * FITUR 1: PROSES KENAIKAN KELAS / PINDAH ROMBEL / KELULUSAN MASSAL
     */
    public function prosesKenaikan(Request $request)
    {
        $request->validate([
            'siswa_ids'   => 'required|array|min:1',
            'jenis_aksi'  => 'required|in:naik_kelas,lulus',
            'tujuan_kelas_id' => 'required_if:jenis_aksi,naik_kelas|nullable|exists:kelas,id',
        ], [
            'siswa_ids.required' => 'Pilih minimal satu siswa yang akan diproses!',
        ]);

        $jumlah = count($request->siswa_ids);

        if ($request->jenis_aksi === 'naik_kelas') {
            Siswa::whereIn('id', $request->siswa_ids)->update([
                'kelas_id' => $request->tujuan_kelas_id,
            ]);
            $kelasTujuan = Kelas::find($request->tujuan_kelas_id);
            return redirect()->route('mutasi.index', ['asal_kelas_id' => $request->tujuan_kelas_id])
                ->with('success', $jumlah . ' Siswa berhasil dipindahkan/dinaikkan ke ' . ($kelasTujuan->nama_kelas ?? 'Kelas Baru') . '!');
        } else {
            // Jika aksi = Luluskan Siswa Kelas 6
            Siswa::whereIn('id', $request->siswa_ids)->update([
                'status'   => 'Lulus',
                'kelas_id' => null,
            ]);
            return redirect()->route('mutasi.index')
                ->with('success', $jumlah . ' Siswa berhasil dinyatakan LULUS!');
        }
    }

    /**
     * FITUR 2: SIMPAN MUTASI MASUK (Otomatis masuk ke Tabel Siswa Aktif & Buku Mutasi Masuk)
     */
    public function storeMasuk(Request $request)
    {
        $request->validate([
            'nomor_induk'          => 'nullable|string|max:30',
            'nisn'                 => 'nullable|string|max:20',
            'nama_siswa'           => 'required|string|max:150',
            'jenis_kelamin'        => 'required|in:L,P',
            'kelas_asal'           => 'nullable|string|max:50',
            'tempat_lahir'         => 'nullable|string|max:100',
            'tanggal_lahir'        => 'nullable|date',
            'nama_ayah'            => 'nullable|string|max:150',
            'nama_ibu'             => 'nullable|string|max:150',
            'pekerjaan_ortu'       => 'nullable|string|max:100',
            'alamat_ortu'          => 'nullable|string',
            'sekolah_asal_tujuan'  => 'required|string|max:150',
            'alamat_sekolah'       => 'nullable|string|max:255',
            'tanggal_mutasi'       => 'required|date',
            'diterima_di_kelas_id' => 'required|exists:kelas,id',
        ]);

        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // 1. Masukkan otomatis ke tabel Data Induk Siswa (status Aktif di kelas tujuan)
        $siswa = Siswa::create([
            'kelas_id'       => $request->diterima_di_kelas_id,
            'nomor_induk'    => $request->nomor_induk,
            'nisn'           => $request->nisn,
            'nama_siswa'     => strtoupper($request->nama_siswa),
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'nama_ayah'      => $request->nama_ayah,
            'nama_ibu'       => $request->nama_ibu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
            'alamat_ortu'    => $request->alamat_ortu,
            'status'         => 'Aktif',
        ]);

        // 2. Catat di tabel Buku Mutasi Masuk
        MutasiSiswa::create([
            'siswa_id'             => $siswa->id,
            'tahun_ajaran_id'      => $tahunAktif->id ?? null,
            'jenis_mutasi'         => 'Masuk',
            'kelas_asal'           => $request->kelas_asal,
            'diterima_di_kelas_id' => $request->diterima_di_kelas_id,
            'sekolah_asal_tujuan'  => $request->sekolah_asal_tujuan,
            'alamat_sekolah'       => $request->alamat_sekolah,
            'tanggal_mutasi'       => $request->tanggal_mutasi,
        ]);

        return redirect()->back()->with('success', 'Siswa Mutasi Masuk berhasil disimpan dan otomatis masuk ke kelas tujuan!');
    }

    /**
     * FITUR 3: SIMPAN MUTASI KELUAR (Siswa Pindah Sekolah)
     */
    public function storeKeluar(Request $request)
    {
        $request->validate([
            'siswa_id'            => 'required|exists:siswas,id',
            'tanggal_mutasi'      => 'required|date',
            'sekolah_asal_tujuan' => 'required|string|max:150',
            'alamat_sekolah'      => 'nullable|string|max:255',
            'alasan_mutasi'       => 'nullable|string|max:255',
        ]);

        $siswa = Siswa::with('kelas')->findOrFail($request->siswa_id);
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // 1. Catat di tabel Buku Mutasi Keluar (simpan nama kelas terakhirnya)
        MutasiSiswa::create([
            'siswa_id'            => $siswa->id,
            'tahun_ajaran_id'     => $tahunAktif->id ?? null,
            'jenis_mutasi'        => 'Keluar',
            'kelas_asal'          => $siswa->kelas->nama_kelas ?? '-',
            'sekolah_asal_tujuan' => $request->sekolah_asal_tujuan,
            'alamat_sekolah'      => $request->alamat_sekolah,
            'tanggal_mutasi'      => $request->tanggal_mutasi,
            'alasan_mutasi'       => $request->alasan_mutasi,
        ]);

        // 2. Ubah status siswa menjadi 'Mutasi Keluar' & lepaskan dari rombel aktif
        $siswa->update([
            'status'   => 'Mutasi Keluar',
            'kelas_id' => null,
        ]);

        return redirect()->back()->with('success', 'Data Mutasi Keluar berhasil dicatat!');
    }

    public function destroy($id)
    {
        $mutasi = MutasiSiswa::findOrFail($id);
        $mutasi->delete();

        return redirect()->back()->with('success', 'Riwayat data mutasi berhasil dihapus!');
    }
}