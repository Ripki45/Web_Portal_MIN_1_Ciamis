<?php

namespace App\Http\Controllers;

use App\Models\MutasiSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MutasiSiswaController extends Controller
{
    /**
     * Jika ada yang mengakses URL lama /mutasi, otomatis arahkan ke /siswa
     */
    public function index()
    {
        return redirect()->route('siswa.index');
    }

    /**
     * SIMPAN MUTASI MASUK (Otomatis masuk ke Tabel Siswa Aktif & Buku Mutasi Masuk)
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
            'foto'                 => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa/foto', 'public');
        }

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
            'foto'           => $fotoPath,
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

        return redirect()->route('siswa.index', ['tab' => 'masuk'])
            ->with('success', 'Siswa Mutasi Masuk (' . $siswa->nama_siswa . ') berhasil disimpan dan otomatis masuk ke Data Siswa Aktif!');
    }

    /**
     * SIMPAN MUTASI KELUAR (Siswa Pindah Sekolah -> Otomatis Hilang dari Tabel Siswa Aktif)
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

        $siswa      = Siswa::with('kelas')->findOrFail($request->siswa_id);
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
            'kelas_terakhir' => $siswa->kelas->nama_kelas ?? '-',
            'status'         => 'Mutasi Keluar',
            'kelas_id'       => null,
        ]);

        return redirect()->route('siswa.index', ['tab' => 'keluar'])
            ->with('success', 'Siswa (' . $siswa->nama_siswa . ') berhasil dicatat Mutasi Keluar dan telah dikeluarkan dari daftar kelas aktif!');
    }

    public function destroy($id)
    {
        $mutasi = MutasiSiswa::findOrFail($id);
        $tab    = $mutasi->jenis_mutasi === 'Masuk' ? 'masuk' : 'keluar';
        $mutasi->delete();

        return redirect()->route('siswa.index', ['tab' => $tab])
            ->with('success', 'Riwayat data mutasi berhasil dihapus!');
    }

    /**
     * HAPUS BANYAK DATA MUTASI SEKALIGUS (FITUR CEKLIS)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        $tab = $request->input('tab', 'masuk');

        if (empty($ids)) {
            return redirect()->route('siswa.index', ['tab' => $tab])
                ->with('error', 'Pilih minimal 1 data mutasi yang ingin dihapus!');
        }

        MutasiSiswa::whereIn('id', $ids)->delete();

        return redirect()->route('siswa.index', ['tab' => $tab])
            ->with('success', count($ids) . ' Data mutasi terpilih berhasil dihapus!');
    }
}