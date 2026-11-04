<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\PengaturanPresensi;
use App\Models\HariLibur;
use App\Models\Ekskul;
use App\Models\EkskulAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

class PresensiController extends Controller
{
    private function pastikanKolomPengaturanLengkap()
    {
        if (!Schema::hasColumn('pengaturan_presensis', 'jam_buka_shalat')) {
            Schema::table('pengaturan_presensis', function (Blueprint $table) {
                $table->string('jam_buka_shalat', 15)->default('11:30:00')->nullable();
                $table->string('jam_tutup_shalat', 15)->default('12:30:00')->nullable();
            });
        }
        if (!Schema::hasColumn('tahun_ajarans', 'bulan_mulai')) {
            Schema::table('tahun_ajarans', function (Blueprint $table) {
                $table->integer('bulan_mulai')->nullable();
            });
        }
    }

    /**
     * Mengecek Status Operasional:
     * - KBM & Shalat Dzuhur mengikuti PengaturanPresensi
     * - Ekstrakurikuler mengikuti jadwal di tabel ekskuls (Default: 13:00 - 16:30 WIB)
     * - Hari Minggu & Hari Libur otomatis OFF untuk SEMUA sesi
     */
    private function cekStatusOperasional($tanggalCek = null, $kategori = 'harian', $ekskulId = null)
    {
        $this->pastikanKolomPengaturanLengkap();

        $now     = Carbon::now('Asia/Jakarta');
        $tanggal = $tanggalCek ?: $now->format('Y-m-d');
        $waktu   = $now->format('H:i:s');

        $setting = PengaturanPresensi::firstOrCreate([], [
            'jam_buka'         => '07:15:00',
            'jam_tutup'        => '09:00:00',
            'jam_buka_shalat'  => '11:30:00',
            'jam_tutup_shalat' => '12:30:00',
            'jam_tutup_manual' => '14:00:00',
            'pin_akses'        => '1234',
            'libur_minggu'     => true,
        ]);

        if (!$setting->jam_buka_shalat) {
            $setting->update([
                'jam_buka_shalat'  => '11:30:00',
                'jam_tutup_shalat' => '12:30:00',
            ]);
        }

        // 1. CEK HARI MINGGU (OFF UNTUK SEMUA SESI)
        if ($setting->libur_minggu && Carbon::parse($tanggal)->isSunday()) {
            return [
                'buka'          => false,
                'buka_scan'     => false,
                'buka_manual'   => false,
                'alasan'        => 'Hari Minggu (Libur Rutin — Semua Presensi OFF)',
                'alasan_scan'   => 'Hari Minggu (Libur Rutin — Presensi OFF)',
                'alasan_manual' => 'Hari Minggu (Libur Rutin — Presensi OFF)',
                'setting'       => $setting,
            ];
        }

        // 2. CEK KALENDER HARI LIBUR (OFF UNTUK SEMUA SESI)
        $hariLibur = HariLibur::whereDate('tanggal_mulai', '<=', $tanggal)
                              ->whereDate('tanggal_selesai', '>=', $tanggal)
                              ->first();
        if ($hariLibur) {
            $msg = 'Hari Libur: ' . $hariLibur->keterangan . ' (Presensi OFF)';
            return [
                'buka'          => false,
                'buka_scan'     => false,
                'buka_manual'   => false,
                'alasan'        => $msg,
                'alasan_scan'   => $msg,
                'alasan_manual' => $msg,
                'setting'       => $setting,
            ];
        }

        // 3. CEK JAM OPERASIONAL SESUAI KATEGORI SESI
        if ($kategori === 'shalat') {
            $buka        = Carbon::parse($setting->jam_buka_shalat ?? '11:30:00')->format('H:i:s');
            $tutupScan   = Carbon::parse($setting->jam_tutup_shalat ?? '12:30:00')->format('H:i:s');
            $tutupManual = Carbon::parse($setting->jam_tutup_manual ?? '14:00:00')->format('H:i:s');
            $namaSesi    = 'Scan Shalat Dzuhur';
        } elseif ($kategori === 'ekskul') {
            // HYBRID: Baca jam latihan dari tabel Ekskul terpilih (Default: 13:00 - 16:30)
            $jamMulaiEks   = '13:00';
            $jamSelesaiEks = '16:30';
            $namaEksLabel  = 'Ekstrakurikuler';

            if ($ekskulId) {
                $eksObj = Ekskul::find($ekskulId);
                if ($eksObj) {
                    $namaEksLabel = 'Ekskul ' . $eksObj->nama_ekskul;
                    if (!empty($eksObj->jam_latihan) && preg_match_all('/\d{2}:\d{2}/', $eksObj->jam_latihan, $matches)) {
                        if (!empty($matches[0][0])) $jamMulaiEks   = $matches[0][0];
                        if (!empty($matches[0][1])) $jamSelesaiEks = $matches[0][1];
                    }
                }
            }

            $buka        = Carbon::parse($jamMulaiEks)->format('H:i:s');
            $tutupScan   = Carbon::parse($jamSelesaiEks)->format('H:i:s');
            $tutupManual = max($tutupScan, '17:30:00');
            $namaSesi    = 'Scan ' . $namaEksLabel;
        } else {
            $buka        = Carbon::parse($setting->jam_buka ?? '07:15:00')->format('H:i:s');
            $tutupScan   = Carbon::parse($setting->jam_tutup ?? '09:00:00')->format('H:i:s');
            $tutupManual = Carbon::parse($setting->jam_tutup_manual ?? '14:00:00')->format('H:i:s');
            $namaSesi    = 'Scan KBM Harian';
        }

        $bukaScan   = ($waktu >= $buka && $waktu <= $tutupScan);
        $bukaManual = ($waktu >= $buka && $waktu <= $tutupManual);

        $alasanScan = $bukaScan
            ? $namaSesi . ' Dibuka (' . substr($buka, 0, 5) . ' - ' . substr($tutupScan, 0, 5) . ' WIB)'
            : $namaSesi . ' Ditutup (Jadwal Buka: ' . substr($buka, 0, 5) . ' - ' . substr($tutupScan, 0, 5) . ' WIB)';

        $alasanManual = $bukaManual
            ? 'Input Manual Dibuka (s/d ' . substr($tutupManual, 0, 5) . ' WIB)'
            : 'Input Manual Ditutup (Jadwal: ' . substr($buka, 0, 5) . ' - ' . substr($tutupManual, 0, 5) . ' WIB)';

        return [
            'buka'          => ($bukaScan || $bukaManual),
            'buka_scan'     => $bukaScan,
            'buka_manual'   => $bukaManual,
            'alasan'        => $bukaScan ? $alasanScan : $alasanManual,
            'alasan_scan'   => $alasanScan,
            'alasan_manual' => $alasanManual,
            'setting'       => $setting,
        ];
    }

    /**
     * 1. HALAMAN DEPAN SCANNER + INPUT WALI KELAS
     */
    public function scanner(Request $request)
    {
        $this->pastikanKolomPengaturanLengkap();

        $aksesTerbuka = $this->apakahAksesScannerTerbuka();
        $user         = auth()->user();
        $isGuru       = $user && $user->role === 'guru';
        $isPembina    = $user && $user->role === 'pembina_ekskul';

        $tanggal  = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $kategori = $request->get('kategori', 'harian');

        if ($isPembina) {
            $kategori = 'ekskul';
        } elseif ($isGuru && $kategori === 'ekskul') {
            $kategori = 'harian';
        }

        $operasional      = $this->cekStatusOperasional($tanggal, $kategori, $request->get('ekskul_id'));
        $setting          = $operasional['setting'];
        $ekskuls          = collect();
        $kelasList        = collect();
        $selectedEkskulId = null;
        $selectedKelasId  = null;
        $siswas           = collect();
        $presensiMap      = [];
        $riwayatHariIni   = collect();
        $semuaSiswaAktif  = collect();

        // KEAMANAN: Jangan muat data siswa/NISN sedikit pun jika belum Login dan belum verifikasi PIN!
        if (!$aksesTerbuka) {
            return view('presensi.scanner', compact(
                'aksesTerbuka', 'operasional', 'setting', 'kategori', 'ekskuls', 'selectedEkskulId',
                'kelasList', 'selectedKelasId', 'siswas', 'presensiMap', 'riwayatHariIni', 'semuaSiswaAktif'
            ));
        }

        $ekskuls = $isPembina
            ? $user->getDaftarEkskulBinaan()
            : Ekskul::with('pembina')->where('status', 'Aktif')->orderBy('nama_ekskul', 'asc')->get();

        $allowedEkskulIds = $ekskuls->pluck('id')->toArray();
        $reqEkskul        = $request->get('ekskul_id');
        $selectedEkskulId = ($reqEkskul && in_array($reqEkskul, $allowedEkskulIds))
            ? $reqEkskul
            : ($ekskuls->first()->id ?? null);

        $operasional = $this->cekStatusOperasional($tanggal, $kategori, $selectedEkskulId);

        if ($isGuru) {
            $jadwalHariIniIds = $user->getKelasJadwalHariIniIds();
            $kelasList = Kelas::whereIn('id', $jadwalHariIniIds)->orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        } else {
            $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        }

        $allowedKelasIds = $kelasList->pluck('id')->toArray();
        $reqKelas        = $request->get('kelas_id');
        $selectedKelasId = ($reqKelas && in_array($reqKelas, $allowedKelasIds))
            ? $reqKelas
            : ($kelasList->first()->id ?? null);

        // Hanya muat kolom id, nama_siswa, dan kelas_id (Tanpa mengekspos NISN di HTML Datalist)
        if ($kategori === 'ekskul' && $selectedEkskulId) {
            $anggotaIds = EkskulAnggota::where('ekskul_id', $selectedEkskulId)->pluck('siswa_id');
            $semuaSiswaAktif = Siswa::with('kelas:id,nama_kelas')
                ->select('id', 'nama_siswa', 'kelas_id')
                ->whereIn('id', $anggotaIds)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
        } elseif ($isGuru) {
            $semuaSiswaAktif = Siswa::with('kelas:id,nama_kelas')
                ->select('id', 'nama_siswa', 'kelas_id')
                ->whereIn('kelas_id', $allowedKelasIds)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
        } else {
            $semuaSiswaAktif = Siswa::with('kelas:id,nama_kelas')
                ->select('id', 'nama_siswa', 'kelas_id')
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
        }

        if ($kategori === 'ekskul' && $selectedEkskulId) {
            $anggotaIds = EkskulAnggota::where('ekskul_id', $selectedEkskulId)->pluck('siswa_id');
            $siswas = Siswa::with('kelas')->whereIn('id', $anggotaIds)->where('status', 'Aktif')->orderBy('kelas_id', 'asc')->orderBy('nama_siswa', 'asc')->get();

            $presensis = Presensi::where('tanggal', $tanggal)->where('kategori', 'ekskul')->where('ekskul_id', $selectedEkskulId)->get();
            foreach ($presensis as $p) {
                $presensiMap[$p->siswa_id] = $p;
            }
        } elseif ($selectedKelasId) {
            $siswas = Siswa::where('kelas_id', $selectedKelasId)->where('status', 'Aktif')->orderBy('nama_siswa', 'asc')->get();

            $presensis = Presensi::where('kelas_id', $selectedKelasId)
                ->where('tanggal', $tanggal)
                ->where(function ($q) use ($kategori) {
                    if ($kategori === 'harian') $q->where('kategori', 'harian')->orWhereNull('kategori');
                    else $q->where('kategori', $kategori);
                })->get();

            foreach ($presensis as $p) {
                $presensiMap[$p->siswa_id] = $p;
            }
        }

        $riwayatHariIni = Presensi::with(['siswa', 'kelas', 'ekskul'])
            ->where('tanggal', $tanggal)
            ->when($isGuru, fn($q) => $q->whereIn('kelas_id', $allowedKelasIds))
            ->where(function ($q) use ($kategori) {
                if ($kategori === 'harian') $q->where('kategori', 'harian')->orWhereNull('kategori');
                else $q->where('kategori', $kategori);
            })
            ->when($kategori === 'ekskul' && $selectedEkskulId, fn($q) => $q->where('ekskul_id', $selectedEkskulId))
            ->orderBy('updated_at', 'desc')
            ->take(15)
            ->get();

        return view('presensi.scanner', compact(
            'aksesTerbuka', 'operasional', 'setting', 'kategori', 'ekskuls', 'selectedEkskulId',
            'kelasList', 'selectedKelasId', 'siswas', 'presensiMap', 'riwayatHariIni', 'semuaSiswaAktif'
        ));
    }

    /**
     * 2. PROSES AJAX SAAT BARCODE DISCAN ATAU DICARI NAMANYA
     */
    public function prosesScan(Request $request)
    {
        // 1. CEK GERBANG KEAMANAN SERVER (Wajib Login atau Sudah Verifikasi PIN)
        if (!$this->apakahAksesScannerTerbuka()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak! Silakan buka kunci PIN atau Login terlebih dahulu.',
            ], 403);
        }

        $kode     = trim((string) $request->kode_barcode);
        $kategori = $request->get('kategori', 'harian');
        $ekskulId = $request->get('ekskul_id') ? (int) $request->get('ekskul_id') : null;
        $metode   = $request->get('metode', 'Scan QR');
        $tanggal  = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $operasional = $this->cekStatusOperasional($tanggal, $kategori, $ekskulId);
        if (!$operasional['buka_scan']) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Scan Ditolak: ' . $operasional['alasan_scan'],
            ]);
        }

        // 2. Cari siswa berdasarkan NISN, Nomor Induk, Format "#ID - Nama", atau Nama Lengkap
        $siswa = null;
        if (preg_match('/^#(\d+)\s*-/', $kode, $matches)) {
            $siswa = Siswa::with('kelas')->where('id', (int) $matches[1])->where('status', 'Aktif')->first();
        } else {
            $siswa = Siswa::with('kelas')
                ->where('status', 'Aktif')
                ->where(function ($q) use ($kode) {
                    $q->where('nisn', $kode)
                      ->orWhere('nomor_induk', $kode)
                      ->orWhere('nama_siswa', $kode);
                })
                ->first();
        }

        if (!$siswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data Siswa tidak ditemukan atau sudah tidak aktif!',
            ]);
        }

        // 3. Validasi Hak Scan Berdasarkan Role & Jadwal Mengajar Hari Ini
        if (auth()->check()) {
            $userScan = auth()->user();

            if ($userScan->role === 'guru') {
                $jadwalKelasIds = $userScan->getKelasJadwalHariIniIds();
                if (!in_array($siswa->kelas_id, $jadwalKelasIds)) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Ditolak! ' . $siswa->nama_siswa . ' berasal dari ' . ($siswa->kelas->nama_kelas ?? '-') . ' (Bukan kelas perwalian / jadwal mengajar Anda hari ini)!',
                    ]);
                }
            }

            if ($userScan->role === 'pembina_ekskul' && $kategori !== 'ekskul') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Ditolak! Akun Pembina Ekskul hanya dapat melakukan scan pada sesi Ekstrakurikuler.',
                ]);
            }
        }

        // 4. Jika Mode Ekskul, pastikan siswa terdaftar sebagai anggota ekskul tersebut
        if ($kategori === 'ekskul') {
            if (!$ekskulId) {
                return response()->json(['status' => 'error', 'message' => 'Pilih kegiatan ekstrakurikuler terlebih dahulu!']);
            }
            $terdaftar = EkskulAnggota::where('ekskul_id', $ekskulId)->where('siswa_id', $siswa->id)->exists();
            if (!$terdaftar) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $siswa->nama_siswa . ' belum terdaftar sebagai anggota di ekstrakurikuler ini!',
                ]);
            }
        }

        // 5. Cek apakah sudah tercatat hadir hari ini di sesi yang sama (Anti Double-Scan)
        $kategoriNormal = empty($kategori) ? 'harian' : $kategori;
        $sudahAbsen = Presensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tanggal)
            ->where(function ($q) use ($kategoriNormal) {
                if ($kategoriNormal === 'harian') {
                    $q->where('kategori', 'harian')->orWhereNull('kategori')->orWhere('kategori', '');
                } else {
                    $q->where('kategori', $kategoriNormal);
                }
            })
            ->when($kategoriNormal === 'ekskul', fn($q) => $q->where('ekskul_id', $ekskulId))
            ->first();

        if ($sudahAbsen && $sudahAbsen->status === 'Hadir') {
            return response()->json([
                'status'  => 'warning',
                'message' => $siswa->nama_siswa . ' sudah tercatat HADIR sebelumnya pada pukul ' . substr($sudahAbsen->waktu_masuk, 0, 5) . ' WIB.',
            ]);
        }

        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $waktuMasuk = Carbon::now('Asia/Jakarta')->format('H:i:s');

        // 6. Simpan secara Atomik & Anti Double-Data
        $this->simpanSatuPresensiAman($siswa->id, $siswa->kelas_id, $tanggal, $kategoriNormal, $ekskulId, [
            'tahun_ajaran_id' => $tahunAktif->id ?? null,
            'waktu_masuk'     => $waktuMasuk,
            'status'          => 'Hadir',
            'metode'          => $metode,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Presensi berhasil dicatat!',
            'siswa'   => [
                'nama'   => $siswa->nama_siswa,
                'kelas'  => $siswa->kelas->nama_kelas ?? '-',
                'waktu'  => $waktuMasuk,
                'metode' => $metode,
            ],
        ]);
    }

    /**
     * 3. SIMPAN SAKIT / IZIN / ALFA DARI HALAMAN DEPAN
     */
    public function storeManualDepan(Request $request)
    {
        if (!$this->apakahAksesScannerTerbuka()) {
            abort(403, 'Akses ditolak! Silakan buka kunci PIN atau Login terlebih dahulu.');
        }

        $tanggal     = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $kategori    = $request->get('kategori', 'harian');
        $ekskulId    = $request->get('ekskul_id') ? (int) $request->get('ekskul_id') : null;
        $kelasId     = (int) $request->get('kelas_id');
        $statuses    = $request->input('status', []);
        $keterangans = $request->input('keterangan', []);
        $tahunAktif  = TahunAjaran::where('is_active', true)->first();

        $operasional = $this->cekStatusOperasional($tanggal, $kategori, $ekskulId);
        if (!$operasional['buka_manual']) {
            return redirect()->back()->with('error', 'Gagal: ' . $operasional['alasan_manual']);
        }

        foreach ($statuses as $siswaId => $st) {
            if (empty($st)) continue;

            $siswaObj = Siswa::find($siswaId);
            if (!$siswaObj) continue;

            $this->simpanSatuPresensiAman((int) $siswaId, (int) $siswaObj->kelas_id, $tanggal, $kategori, $ekskulId, [
                'tahun_ajaran_id' => $tahunAktif->id ?? null,
                'waktu_masuk'     => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'status'          => $st,
                'metode'          => $kategori === 'ekskul' ? 'Input Pembina' : 'Input Wali Kelas',
                'keterangan'      => $keterangans[$siswaId] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Data kehadiran manual berhasil disimpan!');
    }

    /**
     * 4. HALAMAN DASHBOARD ADMIN
     */
    public function index(Request $request)
    {
        $user            = auth()->user();
        $isGuru          = $user && $user->role === 'guru';
        $tanggal         = $request->get('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        $kelasList       = $user ? $user->getDaftarKelasAmpuan() : Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $tahunAktif      = TahunAjaran::where('is_active', true)->first();

        $allowedIds      = $kelasList->pluck('id')->toArray();
        $reqKelas        = $request->get('kelas_id');
        $selectedKelasId = ($reqKelas && in_array($reqKelas, $allowedIds)) ? $reqKelas : ($kelasList->first()->id ?? null);

        $operasional = $this->cekStatusOperasional($tanggal);
        $setting     = $operasional['setting'];
        $hariLiburs  = HariLibur::orderBy('tanggal_mulai', 'desc')->get();

        $siswas      = collect();
        $presensiMap = [];

        if ($selectedKelasId) {
            $siswas = Siswa::where('kelas_id', $selectedKelasId)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();

            $presensis = Presensi::where('kelas_id', $selectedKelasId)
                ->where('tanggal', $tanggal)
                ->where(function ($q) {
                    $q->where('kategori', 'harian')->orWhereNull('kategori');
                })
                ->get();

            foreach ($presensis as $p) {
                $presensiMap[$p->siswa_id] = $p;
            }
        }

        $sudahTercatat = count($presensiMap);
        $rekap = [
            'Hadir'        => collect($presensiMap)->where('status', 'Hadir')->count(),
            'Sakit'        => collect($presensiMap)->where('status', 'Sakit')->count(),
            'Izin'         => collect($presensiMap)->where('status', 'Izin')->count(),
            'Alfa'         => collect($presensiMap)->where('status', 'Alfa')->count(),
            'BelumTerscan' => max(0, $siswas->count() - $sudahTercatat),
        ];

        return view('presensi.index', compact(
            'tanggal', 'kelasList', 'selectedKelasId', 'siswas', 
            'presensiMap', 'tahunAktif', 'rekap', 'setting', 'hariLiburs', 'operasional'
        ));
    }

    /**
     * 5. UPDATE PENGATURAN JAM (KBM & SHALAT DZUHUR) & PIN AKSES GURU
     * (Jam Ekskul sudah otomatis mengikuti jadwal di menu Ekstrakurikuler)
     */
    public function updateSettingJam(Request $request)
    {
        // KEAMANAN: Hanya Superadmin & Admin TU yang boleh mengubah jam operasional & PIN
        if (!auth()->check() || !in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Akses Ditolak! Pengaturan jam & PIN hanya dapat diubah oleh Admin.');
        }

        $this->pastikanKolomPengaturanLengkap();

        $request->validate([
            'jam_buka'          => 'required',
            'jam_tutup'         => 'required',
            'jam_tutup_manual'  => 'required',
            'pin_akses'         => 'required|string|min:4|max:10',
        ]);

        $setting = PengaturanPresensi::first() ?? new PengaturanPresensi();
        $setting->jam_buka          = $request->jam_buka;
        $setting->jam_tutup         = $request->jam_tutup;
        $setting->jam_buka_shalat   = $request->jam_buka_shalat ?? '11:30';
        $setting->jam_tutup_shalat  = $request->jam_tutup_shalat ?? '12:30';
        $setting->jam_tutup_manual  = $request->jam_tutup_manual;
        $setting->pin_akses         = trim($request->pin_akses);
        $setting->libur_minggu      = $request->has('libur_minggu');
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan jam operasional & PIN keamanan berhasil diperbarui!');
    }

    public function storeHariLibur(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Akses Ditolak! Kalender hari libur hanya dapat dikelola oleh Admin.');
        }

        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'required|string|max:150',
        ]);

        HariLibur::create([
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => strip_tags(trim($request->keterangan)),
        ]);

        return redirect()->back()->with('success', 'Hari libur baru berhasil ditambahkan!');
    }

    public function destroyHariLibur($id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Akses Ditolak!');
        }

        HariLibur::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data hari libur berhasil dihapus!');
    }

    public function storeManual(Request $request)
    {
        $user       = auth()->user();
        $kelasId    = (int) $request->kelas_id;
        $tanggal    = $request->tanggal;
        $statuses   = $request->input('status', []);
        $keterangans= $request->input('keterangan', []);
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // Validasi Otorisasi Kelas untuk Guru
        if ($user && $user->role === 'guru') {
            if (!in_array($kelasId, $user->getKelasAmpuanIds())) {
                abort(403, 'Anda tidak berhak mengubah presensi kelas lain!');
            }
        }

        foreach ($statuses as $siswaId => $st) {
            if (empty($st)) {
                Presensi::where('siswa_id', $siswaId)
                    ->where('tanggal', $tanggal)
                    ->where(function ($q) {
                        $q->where('kategori', 'harian')->orWhereNull('kategori')->orWhere('kategori', '');
                    })
                    ->delete();
                continue;
            }

            $this->simpanSatuPresensiAman((int) $siswaId, $kelasId, $tanggal, 'harian', null, [
                'tahun_ajaran_id' => $tahunAktif->id ?? null,
                'waktu_masuk'     => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'status'          => $st,
                'metode'          => $user && $user->role === 'guru' ? 'Input Wali Kelas' : 'Koreksi Admin',
                'keterangan'      => $keterangans[$siswaId] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Data kehadiran siswa berhasil disimpan tanpa duplikasi!');
    }

    /**
     * 8. CETAK KARTU SISWA & QR CODE B2 LANDSCAPE
     */
    public function kartuQr(Request $request)
    {
        $kelas = null;

        if ($request->filled('siswa_id')) {
            $siswas = Siswa::with('kelas')->where('id', $request->siswa_id)->get();
            $judulCetak = 'Siswa: ' . ($siswas->first()->nama_siswa ?? '-');
        } elseif ($request->filled('ids')) {
            $idsArray = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $siswas = Siswa::with('kelas')->whereIn('id', $idsArray)->orderBy('nama_siswa', 'asc')->get();
            $judulCetak = count($siswas) . ' Siswa Terpilih';
        } elseif ($request->filled('kelas_id')) {
            $kelas = Kelas::with('waliKelas')->findOrFail($request->kelas_id);
            $siswas = Siswa::with('kelas')
                ->where('kelas_id', $request->kelas_id)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
            $judulCetak = $kelas->nama_kelas;
        } else {
            $siswas = Siswa::with('kelas')->where('status', 'Aktif')->orderBy('kelas_id', 'asc')->orderBy('nama_siswa', 'asc')->get();
            $judulCetak = 'Semua Siswa Aktif';
        }

        $kepalaMadrasah = \App\Models\Gtk::with('jabatan')
            ->whereHas('jabatan', function ($q) {
                $q->where('nama_jabatan', 'like', '%Kepala%');
            })
            ->latest()
            ->first();

        return view('presensi.kartu_qr', compact('kelas', 'siswas', 'judulCetak', 'kepalaMadrasah'));
    }

    /**
     * 9. REKAP & LAPORAN PRESENSI BULANAN
     * - Default Bulan mengikuti Bulan Pertama Tahun Ajaran Aktif (Juli untuk Ganjil, Januari untuk Genap)
     * - TTD Kiri: Mengetahui Kepala Madrasah | TTD Kanan: Wali Kelas / Guru Mapel / Pembina Ekskul
     */
    public function rekap(Request $request)
    {
        $this->pastikanKolomPengaturanLengkap();

        $user       = auth()->user();
        $isGuru    = $user && $user->role === 'guru';
        $isPembina = $user && $user->role === 'pembina_ekskul';
        $kelasList  = $user ? $user->getDaftarKelasAmpuan() : Kelas::with('waliKelas')->orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $allowedIds = $kelasList->pluck('id')->toArray();
        $reqKelas   = $request->get('kelas_id');
        $ekskulList = $isPembina
            ? $user->getDaftarEkskulBinaan()
            : Ekskul::with('pembina')->where('status', 'Aktif')->orderBy('nama_ekskul', 'asc')->get();
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // Tentukan Bulan & Tahun Default berdasarkan Tahun Ajaran Aktif (bukan bulan kalender berjalan!)
        $defaultBulan = 7; // Default Semester Ganjil = Juli (Bulan 7)
        $defaultTahun = (int) date('Y');

        if ($tahunAktif) {
            if (!empty($tahunAktif->bulan_mulai)) {
                $defaultBulan = (int) $tahunAktif->bulan_mulai;
            } elseif (stripos($tahunAktif->semester ?? '', 'Genap') !== false) {
                $defaultBulan = 1;
            } else {
                $defaultBulan = 7;
            }

            if (preg_match('/(\d{4})\s*\/\s*(\d{4})/', $tahunAktif->tahun_ajaran ?? '', $mTahun)) {
                $defaultTahun = ($defaultBulan >= 7) ? (int) $mTahun[1] : (int) $mTahun[2];
            }
        }

        $kategori = $request->get('kategori', 'harian');
            if ($isPembina) {
                $kategori = 'ekskul';
            } elseif ($isGuru && $kategori === 'ekskul') {
                $kategori = 'harian';
            }
        $allowedKelasIds  = $kelasList->pluck('id')->toArray();
        $reqKelas         = $request->get('kelas_id');
        $selectedKelasId  = ($reqKelas && in_array($reqKelas, $allowedKelasIds)) ? $reqKelas : ($kelasList->first()->id ?? null);
        $allowedEkskulIds = $ekskulList->pluck('id')->toArray();
        $reqEkskul        = $request->get('ekskul_id');
        $selectedEkskulId = ($reqEkskul && in_array($reqEkskul, $allowedEkskulIds)) ? $reqEkskul : ($ekskulList->first()->id ?? null);
        $bulan            = (int) $request->get('bulan', $defaultBulan);
        $tahun            = (int) $request->get('tahun', $defaultTahun);
        $selectedMapelId  = $request->get('kelas_mapel_id', '');

        $kelasTerpilih  = Kelas::with('waliKelas')->find($selectedKelasId);
        $ekskulTerpilih = Ekskul::with('pembina')->find($selectedEkskulId);
        $jumlahHari     = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        $daftarMapelKelas = \App\Models\KelasMapel::with(['mapel', 'guru'])
            ->where('kelas_id', $selectedKelasId)
            ->get();

        // Tentukan Penandatangan Kanan Bawah (Wali Kelas / Guru Mapel / Pembina Ekskul)
        // Sedangkan Penandatangan Kiri Bawah selalu dikunci untuk Kepala Madrasah ($kepalaMadrasah)
        $penandatanganGuru    = $kelasTerpilih->waliKelas ?? null;
        $jabatanPenandatangan = 'Wali Kelas ' . str_replace('Kelas ', '', ($kelasTerpilih->nama_kelas ?? ''));
        $mapelTerpilihObj     = null;

        if ($kategori === 'ekskul' && $ekskulTerpilih) {
            $penandatanganGuru    = $ekskulTerpilih->pembina;
            $jabatanPenandatangan = 'Pembina ' . $ekskulTerpilih->nama_ekskul;
        } elseif (!empty($selectedMapelId)) {
            $kmTerpilih = $daftarMapelKelas->where('id', $selectedMapelId)->first();
            if ($kmTerpilih && $kmTerpilih->guru) {
                $penandatanganGuru    = $kmTerpilih->guru;
                $mapelTerpilihObj     = $kmTerpilih->mapel;
                $jabatanPenandatangan = 'Guru ' . ($kmTerpilih->mapel->nama_mapel ?? 'Mata Pelajaran');
            }
        }

        // Penandatangan Kiri Bawah: Selalu Kepala Madrasah
        $kepalaMadrasah = \App\Models\Gtk::with('jabatan')
            ->whereHas('jabatan', function ($q) {
                $q->where('nama_jabatan', 'like', '%Kepala%');
            })
            ->latest()
            ->first();

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $namaBulanList[$bulan] ?? 'Juli';

        $ejaanTingkat = [
            1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam'
        ];
        $labelTingkat = $kelasTerpilih ? ($ejaanTingkat[$kelasTerpilih->tingkat] ?? '') : '';

        $tglAwal  = sprintf('%04d-%02d-01', $tahun, $bulan);
        $tglAkhir = sprintf('%04d-%02d-%02d', $tahun, $bulan, $jumlahHari);

        $siswas    = collect();
        $presensis = collect();

        if ($kategori === 'ekskul' && $ekskulTerpilih) {
            $anggotaIds = EkskulAnggota::where('ekskul_id', $ekskulTerpilih->id)->pluck('siswa_id');
            $siswas = Siswa::with('kelas')
                ->whereIn('id', $anggotaIds)
                ->where('status', 'Aktif')
                ->orderBy('kelas_id', 'asc')
                ->orderBy('nama_siswa', 'asc')
                ->get();

            $presensis = Presensi::where('kategori', 'ekskul')
                ->where('ekskul_id', $ekskulTerpilih->id)
                ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                ->get();
        } else {
            $presensis = Presensi::where('kelas_id', $selectedKelasId)
                ->where(function ($q) use ($kategori) {
                    if ($kategori === 'harian') {
                        $q->where('kategori', 'harian')->orWhereNull('kategori');
                    } else {
                        $q->where('kategori', $kategori);
                    }
                })
                ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                ->get();

            $riwayatSiswaIds = $presensis->pluck('siswa_id')->unique()->toArray();

            if ($selectedKelasId) {
                $siswas = Siswa::with('kelas')
                    ->where(function ($q) use ($selectedKelasId, $riwayatSiswaIds) {
                        $q->where(function ($sub) use ($selectedKelasId) {
                            $sub->where('kelas_id', $selectedKelasId)->where('status', 'Aktif');
                        });
                        if (!empty($riwayatSiswaIds)) {
                            $q->orWhereIn('id', $riwayatSiswaIds);
                        }
                    })
                    ->orderBy('nama_siswa', 'asc')
                    ->get();
            }
        }

        $matriks         = [];
        $grandTotalHadir = 0;
        $grandTotalS     = 0;
        $grandTotalI     = 0;
        $grandTotalA     = 0;

        foreach ($presensis as $p) {
            $hariKe = (int) Carbon::parse($p->tanggal)->format('j');
            $kode   = match ($p->status) {
                'Hadir' => '•',
                'Sakit' => 'S',
                'Izin'  => 'I',
                'Alfa'  => 'A',
                default => ''
            };
            $matriks[$p->siswa_id][$hariKe] = $kode;

            if ($p->status === 'Hadir') $grandTotalHadir++;
            elseif ($p->status === 'Sakit') $grandTotalS++;
            elseif ($p->status === 'Izin') $grandTotalI++;
            elseif ($p->status === 'Alfa') $grandTotalA++;
        }

        $grandTotalAbsen = $grandTotalS + $grandTotalI + $grandTotalA;

        $setting    = PengaturanPresensi::first();
        $hariLiburs = HariLibur::whereDate('tanggal_mulai', '<=', $tglAkhir)
                               ->whereDate('tanggal_selesai', '>=', $tglAwal)
                               ->get();

        $tanggalLibur = [];
        for ($d = 1; $d <= $jumlahHari; $d++) {
            $currDate = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
            $carbonD  = Carbon::parse($currDate);

            $liburKhusus = null;
            foreach ($hariLiburs as $hl) {
                if ($currDate >= $hl->tanggal_mulai && $currDate <= $hl->tanggal_selesai) {
                    $liburKhusus = $hl->keterangan;
                    break;
                }
            }

            if ($liburKhusus) {
                $tanggalLibur[$d] = [
                    'tipe'       => 'khusus',
                    'keterangan' => $liburKhusus,
                ];
            } elseif (($setting->libur_minggu ?? true) && $carbonD->isSunday()) {
                $tanggalLibur[$d] = [
                    'tipe'       => 'minggu',
                    'keterangan' => 'Minggu',
                ];
            }
        }

        $hariEfektif    = max(0, $jumlahHari - count($tanggalLibur));
        $totalL         = $siswas->where('jenis_kelamin', 'L')->count();
        $totalP         = $siswas->where('jenis_kelamin', 'P')->count();
        $pembagiEfektif = max(1, $siswas->count() * max(1, $hariEfektif));
        $persenAbsen    = round(($grandTotalAbsen / $pembagiEfektif) * 100, 1);
        $persenHadir    = round(max(0, 100 - $persenAbsen), 1);

        $dataView = compact(
            'kelasList', 'ekskulList', 'tahunAktif', 'kategori', 'selectedKelasId', 'selectedEkskulId',
            'kelasTerpilih', 'ekskulTerpilih', 'labelTingkat', 'bulan', 'tahun', 'namaBulan', 'namaBulanList',
            'jumlahHari', 'siswas', 'matriks', 'tanggalLibur', 'kepalaMadrasah',
            'daftarMapelKelas', 'selectedMapelId', 'penandatanganGuru', 'jabatanPenandatangan', 'mapelTerpilihObj',
            'grandTotalHadir', 'grandTotalS', 'grandTotalI', 'grandTotalA', 'grandTotalAbsen',
            'hariEfektif', 'totalL', 'totalP', 'persenAbsen', 'persenHadir'
        );

        if ($request->get('export') === 'pdf') {
            return view('presensi.cetak_pdf', $dataView);
        }

        if ($request->get('export') === 'excel') {
            return view('presensi.cetak_excel', $dataView);
        }

        return view('presensi.rekap', $dataView);
    }

    /**
     * Verifikasi PIN Keamanan di Sisi Server (Aman dari Inspect Element / View Source)
     */
    public function verifikasiPinServer(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|max:10',
        ]);

        $setting   = PengaturanPresensi::first();
        $pinResmi  = (string) ($setting->pin_akses ?? '1234');
        $pinInput  = trim((string) $request->pin);

        // Gunakan hash_equals untuk mencegah Timing Attack
        if (hash_equals($pinResmi, $pinInput)) {
            $request->session()->put('pin_presensi_terverifikasi', true);
            $request->session()->put('pin_verified_at', now()->timestamp);

            return response()->json([
                'status'  => 'success',
                'message' => 'Akses PIN berhasil diverifikasi oleh server!',
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'PIN Keamanan salah!',
        ], 422);
    }

    /**
     * Kunci ulang sesi PIN dari Server
     */
    public function kunciPinServer(Request $request)
    {
        $request->session()->forget(['pin_presensi_terverifikasi', 'pin_verified_at']);
        return redirect()->route('presensi.scanner')->with('success', 'Layar PIN berhasil dikunci kembali.');
    }
    /**
     * Mengecek apakah pengguna saat ini sudah Login ATAU sudah lolos verifikasi PIN Server
     */
    private function apakahAksesScannerTerbuka(): bool
    {
        return auth()->check() || session('pin_presensi_terverifikasi') === true;
    }

    /**
     * Mesin Penyimpanan Presensi Tunggal (Anti Double-Data / Anti Tumpang Tindih)
     */
    private function simpanSatuPresensiAman(int $siswaId, int $kelasId, string $tanggal, string $kategori, ?int $ekskulId, array $atribut): Presensi
    {
        $kategoriNormal = empty($kategori) ? 'harian' : $kategori;
        $ekskulNormal   = ($kategoriNormal === 'ekskul') ? $ekskulId : null;

        return \Illuminate\Support\Facades\DB::transaction(function () use ($siswaId, $kelasId, $tanggal, $kategoriNormal, $ekskulNormal, $atribut) {
            // Cari semua baris presensi siswa ini di tanggal & sesi yang sama (termasuk data lama yang kategorinya NULL)
            $existingRows = Presensi::where('siswa_id', $siswaId)
                ->where('tanggal', $tanggal)
                ->where(function ($q) use ($kategoriNormal) {
                    if ($kategoriNormal === 'harian') {
                        $q->where('kategori', 'harian')->orWhereNull('kategori')->orWhere('kategori', '');
                    } else {
                        $q->where('kategori', $kategoriNormal);
                    }
                })
                ->when($kategoriNormal === 'ekskul', function ($q) use ($ekskulNormal) {
                    $q->where('ekskul_id', $ekskulNormal);
                })
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->get();

            $payload = array_merge([
                'siswa_id'  => $siswaId,
                'kelas_id'  => $kelasId,
                'tanggal'   => $tanggal,
                'kategori'  => $kategoriNormal,
                'ekskul_id' => $ekskulNormal,
            ], $atribut);

            if ($existingRows->isNotEmpty()) {
                $utama = $existingRows->first();
                $utama->update($payload);

                // Jika ternyata ada baris duplikat sisa data lama, bersihkan otomatis!
                if ($existingRows->count() > 1) {
                    $duplikatIds = $existingRows->slice(1)->pluck('id')->toArray();
                    Presensi::whereIn('id', $duplikatIds)->delete();
                }

                return $utama;
            }

            return Presensi::create($payload);
        });
    }
}