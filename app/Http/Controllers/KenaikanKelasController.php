<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\HariLibur;
use App\Models\PengaturanPresensi;
use App\Models\TahunAjaran;
use App\Models\KelasMapel;
use App\Models\Ekskul;
use App\Models\EkskulAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $semuaTahun = TahunAjaran::orderBy('id', 'desc')->get();

        // Urutkan kelas dari Tingkat 6 turun ke Tingkat 1 (Sesuai urutan wajib kenaikan kelas)
        $kelasListDesc = Kelas::withCount(['siswas' => function ($q) {
                $q->where('status', 'Aktif');
            }])
            ->orderBy('tingkat', 'desc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $kelasListAsc = Kelas::withCount(['siswas' => function ($q) {
                $q->where('status', 'Aktif');
            }])
            ->orderBy('tingkat', 'asc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $selectedKelasAsalId = $request->get('kelas_asal_id', $kelasListDesc->first()->id ?? null);
        $kelasAsal           = Kelas::find($selectedKelasAsalId);

        $siswaAsal = collect();
        $kelasTujuanList = collect();

        if ($kelasAsal) {
            $siswaAsal = Siswa::where('kelas_id', $kelasAsal->id)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();

            // Jika bukan kelas 6, cari daftar kelas di tingkat atasnya (Tingkat + 1)
            if ($kelasAsal->tingkat < 6) {
                $kelasTujuanList = Kelas::withCount(['siswas' => function ($q) {
                        $q->where('status', 'Aktif');
                    }])
                    ->where('tingkat', $kelasAsal->tingkat + 1)
                    ->orderBy('nama_kelas', 'asc')
                    ->get();
            }
        }

        // Data Arsip Alumni (Siswa yang sudah Lulus)
        $alumnis = Siswa::where('status', 'Lulus')
            ->orderBy('updated_at', 'desc')
            ->orderBy('nama_siswa', 'asc')
            ->get();

        // Statistik Ringkas untuk Panel Tombol Ajaib
        $statSistem = [
            'total_alumni'     => $alumnis->count(),
            'total_hari_libur' => HariLibur::count(),
            'total_presensi'   => Presensi::count(),
            'total_siswa_aktif'=> Siswa::where('status', 'Aktif')->count(),
        ];

        return view('kenaikan.index', compact(
            'tahunAktif', 'semuaTahun', 'kelasListDesc', 'kelasListAsc',
            'selectedKelasAsalId', 'kelasAsal', 'siswaAsal', 'kelasTujuanList',
            'alumnis', 'statSistem'
        ));
    }

    /**
     * PROSES KENAIKAN KELAS & KELULUSAN DENGAN KUNCI ANTI-TUMPUK (TOP-DOWN LOCK)
     */
    public function prosesNaikKelas(Request $request)
    {
        $request->validate([
            'kelas_asal_id' => 'required|exists:kelas,id',
            'siswa_ids'     => 'required|array|min:1',
            'siswa_ids.*'   => 'exists:siswas,id',
        ], [
            'siswa_ids.required' => 'Ceklis minimal 1 siswa yang akan dinaikkan / diluluskan!',
        ]);

        $kelasAsal  = Kelas::findOrFail($request->kelas_asal_id);
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $labelTahun = $tahunAktif->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);

        // =====================================================================
        // KASUS A: KELAS 6 -> DILULUSKAN MENJADI ALUMNI (RUANG KELAS 6 JADI KOSONG)
        // =====================================================================
        if ($kelasAsal->tingkat == 6) {
            $jumlahLulus = Siswa::whereIn('id', $request->siswa_ids)
                ->where('kelas_id', $kelasAsal->id)
                ->update([
                    'status'         => 'Lulus',
                    'kelas_terakhir' => $kelasAsal->nama_kelas,
                    'tahun_lulus'    => $labelTahun,
                    'kelas_id'       => null, // Kosongkan agar Kelas 6 siap ditempati adik Kelas 5
                ]);

            return redirect()->route('kenaikan.index', ['kelas_asal_id' => $kelasAsal->id])
                ->with('success', "Alhamdulillah! {$jumlahLulus} Siswa dari {$kelasAsal->nama_kelas} berhasil DILULUSKAN ke Arsip Alumni. Kini ruang {$kelasAsal->nama_kelas} sudah kosong dan siap menerima kenaikan dari Kelas 5!");
        }

        // =====================================================================
        // KASUS B: KELAS 1 s/d 5 -> NAIK KE TINGKAT BERIKUTNYA (DENGAN KUNCI PENGAMAN!)
        // =====================================================================
        $request->validate([
            'kelas_tujuan_id' => 'required|exists:kelas,id',
        ], [
            'kelas_tujuan_id.required' => 'Pilih Kelas Tujuan kenaikan terlebih dahulu!',
        ]);

        $kelasTujuan = Kelas::findOrFail($request->kelas_tujuan_id);

        if ($kelasTujuan->tingkat != ($kelasAsal->tingkat + 1)) {
            return redirect()->back()->with('error', 'Kelas tujuan harus berada tepat 1 tingkat di atas kelas asal!');
        }

        // KUNCI PENGAMAN UTAMA: Cek apakah Kelas Tujuan masih berisi siswa aktif yang belum dinaikkan!
        $jumlahPenghuniTujuan = Siswa::where('kelas_id', $kelasTujuan->id)
            ->where('status', 'Aktif')
            ->count();

        if ($jumlahPenghuniTujuan > 0 && !$request->has('paksa_gabung')) {
            return redirect()->back()->with(
                'error',
                "DITOLAK OTOMATIS (ANTI DATA MENUMPANG): Kelas tujuan ({$kelasTujuan->nama_kelas}) saat ini masih berisi {$jumlahPenghuniTujuan} siswa aktif yang BELUM dinaikkan ke tingkat berikutnya! Silakan naikkan/luluskan siswa di {$kelasTujuan->nama_kelas} terlebih dahulu agar data siswa tidak bercampur."
            );
        }

        // Jika Kelas Tujuan sudah kosong (0 siswa), pindahkan siswa terpilih ke Kelas Tujuan
        $jumlahNaik = Siswa::whereIn('id', $request->siswa_ids)
            ->where('kelas_id', $kelasAsal->id)
            ->update([
                'kelas_id' => $kelasTujuan->id,
            ]);

        return redirect()->route('kenaikan.index', ['kelas_asal_id' => $kelasAsal->id])
            ->with('success', "Berhasil! {$jumlahNaik} Siswa dari {$kelasAsal->nama_kelas} telah resmi naik ke {$kelasTujuan->nama_kelas}!");
    }

    /**
     * HAPUS MASSAL ARSIP ALUMNI TERPILIH (FITUR CEKLIS)
     */
    public function bulkDeleteAlumni(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Ceklis minimal 1 data alumni yang ingin dihapus permanen!');
        }

        $alumnis = Siswa::whereIn('id', $ids)->where('status', 'Lulus')->get();
        foreach ($alumnis as $al) {
            if ($al->foto && Storage::disk('public')->exists($al->foto)) {
                Storage::disk('public')->delete($al->foto);
            }
            $al->delete();
        }

        return redirect()->route('kenaikan.index', ['tab' => 'alumni'])
            ->with('success', count($alumnis) . ' Data alumni terpilih berhasil dihapus permanen!');
    }

    /**
     * PANEL "TOMBOL AJAIB" RESET & PEMELIHARAAN SISTEM (6 FITUR KHUSUS SUPERADMIN)
     */
    public function magicAction(Request $request)
    {
        $aksi = $request->input('aksi');

        // 1. Hapus Cache Aplikasi
        if ($aksi === 'clear_cache') {
            Artisan::call('optimize:clear');
            return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                ->with('success', '✨ Cache sistem, view, dan route berhasil dibersihkan!');
        }

        // 2. Reset Kalender Hari Libur
        if ($aksi === 'reset_hari_libur') {
            HariLibur::query()->delete();
            return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                ->with('success', '🗓️ Seluruh data Hari Libur tahun lama berhasil direset hingga bersih!');
        }

        // 3. Reset Jam Scan Barcode & PIN ke Default
        if ($aksi === 'reset_jam_scan') {
            $setting = PengaturanPresensi::first();
            if ($setting) {
                $setting->update([
                    'jam_buka'         => '07:15:00',
                    'jam_tutup'        => '09:00:00',
                    'jam_tutup_manual' => '14:00:00',
                    'pin_akses'        => '1234',
                    'libur_minggu'     => true,
                ]);
            }
            return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                ->with('success', '⏰ Jam Scan Barcode berhasil dikembalikan ke setelan Default (07:15 - 09:00 WIB, Batas Wali Kelas 14:00 WIB, PIN: 1234)!');
        }

        // 4. Hapus Semua Data Siswa yang Sudah Lulus (Alumni)
        if ($aksi === 'hapus_semua_alumni') {
            $alumnis = Siswa::where('status', 'Lulus')->get();
            $jml = $alumnis->count();
            foreach ($alumnis as $al) {
                if ($al->foto && Storage::disk('public')->exists($al->foto)) {
                    Storage::disk('public')->delete($al->foto);
                }
                $al->delete();
            }
            return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                ->with('success', "🎓 {$jml} Data siswa yang sudah lulus (Alumni) beserta file fotonya berhasil dihapus permanen!");
        }

        // 5. Reset / Buat Tahun Ajaran Baru
        if ($aksi === 'reset_tahun_ajaran') {
            $request->validate([
                'tahun_ajaran_baru' => 'required|string|max:30',
                'semester_baru'     => 'required|in:Ganjil,Genap',
            ]);

            TahunAjaran::query()->update(['is_active' => false]);
            TahunAjaran::updateOrCreate(
                [
                    'tahun_ajaran' => trim($request->tahun_ajaran_baru),
                    'semester'     => $request->semester_baru,
                ],
                [
                    'is_active' => true,
                ]
            );

            return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                ->with('success', "📚 Tahun Ajaran Aktif berhasil diperbarui menjadi {$request->tahun_ajaran_baru} ({$request->semester_baru})!");
        }

        // 6. HARD RESET (Hapus Semua Data Operasional)
        if ($aksi === 'hard_reset') {
            if (trim($request->konfirmasi_teks) !== 'RESET') {
                return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                    ->with('error', 'Konfirmasi gagal! Anda harus mengetik kata RESET dengan huruf kapital.');
            }

            Schema::disableForeignKeyConstraints();
            Presensi::truncate();
            HariLibur::truncate();
            EkskulAnggota::truncate();
            Ekskul::truncate();
            KelasMapel::truncate();
            Siswa::truncate();
            Kelas::truncate();
            Schema::enableForeignKeyConstraints();

            return redirect()->route('kenaikan.index', ['tab' => 'magic'])
                ->with('success', '⚠️ HARD RESET SELESAI: Seluruh data siswa, kelas, ekskul, hari libur, dan riwayat presensi telah dikosongkan ke setelan pabrik!');
        }

        return redirect()->back();
    }
}