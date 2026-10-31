<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $user       = auth()->user();
        $isGuru     = $user && $user->role === 'guru';
        $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first();

        // 1. Ambil daftar kelas sesuai Role (Semua kelas untuk Admin, hanya kelas ampuan untuk Guru)
        $kelasList = $user ? $user->getDaftarKelasAmpuan() : Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

        $query = Siswa::with('kelas')->where('status', 'Aktif');
        $selectedKelasId = $request->get('kelas_id');

        if ($isGuru) {
            $allowedIds = $kelasList->pluck('id')->toArray();

            // Jika guru belum memilih kelas atau mencoba akses kelas lain, kunci ke kelas pertamanya
            if (empty($selectedKelasId) || !in_array($selectedKelasId, $allowedIds)) {
                $selectedKelasId = $kelasList->first()->id ?? 0;
            }

            $query->where('kelas_id', $selectedKelasId);
        } else {
            if (!empty($selectedKelasId)) {
                $query->where('kelas_id', $selectedKelasId);
            }
        }

        $siswas = $query->orderBy('kelas_id', 'asc')->orderBy('nama_siswa', 'asc')->get();

        $totalL = $siswas->where('jenis_kelamin', 'L')->count();
        $totalP = $siswas->where('jenis_kelamin', 'P')->count();

        // 2. Data Buku Mutasi Masuk & Keluar (Hanya dimuat untuk Superadmin / Admin TU)
        $mutasiMasuk     = collect();
        $mutasiKeluar    = collect();
        $siswaAktifSemua = collect();

        if (!$isGuru) {
            $mutasiMasuk = \App\Models\MutasiSiswa::with(['siswa', 'kelasTujuan'])
                ->where('jenis_mutasi', 'Masuk')
                ->latest('id')
                ->get();

            $mutasiKeluar = \App\Models\MutasiSiswa::with(['siswa'])
                ->where('jenis_mutasi', 'Keluar')
                ->latest('id')
                ->get();

            $siswaAktifSemua = Siswa::with('kelas')
                ->where('status', 'Aktif')
                ->orderBy('kelas_id', 'asc')
                ->orderBy('nama_siswa', 'asc')
                ->get();
        }

        return view('siswa.index', compact(
            'tahunAktif', 'siswas', 'kelasList', 'selectedKelasId', 'totalL', 'totalP',
            'mutasiMasuk', 'mutasiKeluar', 'siswaAktifSemua', 'isGuru'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'nomor_induk'    => 'nullable|string|max:30',
            'nisn'           => 'nullable|string|max:20',
            'nama_siswa'     => 'required|string|max:150',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'nama_ayah'      => 'nullable|string|max:150',
            'nama_ibu'       => 'nullable|string|max:150',
            'alamat_ortu'    => 'nullable|string',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa/foto', 'public');
        }

        Siswa::create([
            'kelas_id'       => $request->kelas_id,
            'nomor_induk'    => $request->nomor_induk,
            'nisn'           => $request->nisn,
            'nama_siswa'     => strtoupper($request->nama_siswa),
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'nama_ayah'      => $request->nama_ayah,
            'nama_ibu'       => $request->nama_ibu,
            'alamat_ortu'    => $request->alamat_ortu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
            'foto'           => $fotoPath,
            'status'         => 'Aktif',
        ]);

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'nomor_induk'    => 'nullable|string|max:30',
            'nisn'           => 'nullable|string|max:20',
            'nama_siswa'     => 'required|string|max:150',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'nama_ayah'      => 'nullable|string|max:150',
            'nama_ibu'       => 'nullable|string|max:150',
            'alamat_ortu'    => 'nullable|string',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:Aktif,Mutasi Keluar,Lulus',
        ]);

        $data = [
            'kelas_id'       => $request->kelas_id,
            'nomor_induk'    => $request->nomor_induk,
            'nisn'           => $request->nisn,
            'nama_siswa'     => strtoupper($request->nama_siswa),
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'nama_ayah'      => $request->nama_ayah,
            'nama_ibu'       => $request->nama_ibu,
            'alamat_ortu'    => $request->alamat_ortu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
            'status'         => $request->status,
        ];

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $data['foto'] = $request->file('foto')->store('siswa/foto', 'public');
        }

        $siswa->update($data);

        return redirect()->back()->with('success', 'Data siswa & pasfoto berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        if (auth()->user()->role === 'guru') {
            return redirect()->back()->with('error', 'Guru tidak diizinkan menghapus data induk siswa!');
        }

        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();
        return redirect()->back()->with('success', 'Data siswa berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        if (auth()->user()->role === 'guru') {
            return redirect()->back()->with('error', 'Guru tidak diizinkan menghapus data induk siswa!');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 data siswa yang ingin dihapus!');
        }

        $siswas = Siswa::whereIn('id', $ids)->get();
        foreach ($siswas as $s) {
            if ($s->foto && Storage::disk('public')->exists($s->foto)) {
                Storage::disk('public')->delete($s->foto);
            }
            $s->delete();
        }

        return redirect()->back()->with('success', count($siswas) . ' Data siswa terpilih berhasil dihapus!');
    }

    public function importPaste(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'raw_data' => 'required|string',
        ]);

        $lines = preg_split('/\r\n|\r|\n/', trim($request->raw_data));
        $berhasil = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $cols = explode("\t", $line);
            $firstCol = trim($cols[0] ?? '');
            $offset = (is_numeric($firstCol) && strlen($firstCol) <= 4) ? 1 : 0;

            $nomorInduk = trim($cols[$offset] ?? '');
            $nisn       = trim($cols[$offset + 1] ?? '');
            $namaSiswa  = trim($cols[$offset + 2] ?? '');
            $jk         = strtoupper(trim($cols[$offset + 3] ?? 'L'));
            $tmpLahir   = trim($cols[$offset + 4] ?? '');
            $tglRaw     = trim($cols[$offset + 5] ?? '');
            $ayah       = trim($cols[$offset + 6] ?? '');
            $ibu        = trim($cols[$offset + 7] ?? '');
            $alamat     = trim($cols[$offset + 8] ?? '');
            $pekerjaan  = trim($cols[$offset + 9] ?? '');

            if (empty($namaSiswa)) continue;

            $tglLahir = $this->parseTanggalIndo($tglRaw);

            Siswa::create([
                'kelas_id'       => $request->kelas_id,
                'nomor_induk'    => $nomorInduk ?: null,
                'nisn'           => $nisn ?: null,
                'nama_siswa'     => strtoupper($namaSiswa),
                'jenis_kelamin'  => in_array($jk, ['L', 'P']) ? $jk : 'L',
                'tempat_lahir'   => $tmpLahir ?: null,
                'tanggal_lahir'  => $tglLahir,
                'nama_ayah'      => $ayah ?: null,
                'nama_ibu'       => $ibu ?: null,
                'alamat_ortu'    => $alamat ?: null,
                'pekerjaan_ortu' => $pekerjaan ?: null,
                'status'         => 'Aktif',
            ]);

            $berhasil++;
        }

        return redirect()->route('siswa.index', ['kelas_id' => $request->kelas_id])
                         ->with('success', $berhasil . ' Data Siswa berhasil diimport sekaligus!');
    }

    private function parseTanggalIndo($tglRaw)
    {
        if (empty($tglRaw)) return null;

        $indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $eng  = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        $translated = str_ireplace($indo, $eng, $tglRaw);

        try {
            return Carbon::parse($translated)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}