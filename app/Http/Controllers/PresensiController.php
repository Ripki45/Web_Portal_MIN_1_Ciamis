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
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Mengecek Status Operasional (Dibedakan antara Jam Scan QR & Jam Input Wali Kelas)
     */
    private function cekStatusOperasional($tanggalCek = null, $kategori = 'harian')
    {
        $now     = Carbon::now('Asia/Jakarta');
        $tanggal = $tanggalCek ?: $now->format('Y-m-d');
        $waktu   = $now->format('H:i:s');

        $setting = PengaturanPresensi::firstOrCreate([], [
            'jam_buka'         => '07:15:00',
            'jam_tutup'        => '09:00:00',
            'jam_tutup_manual' => '14:00:00',
            'pin_akses'        => '1234',
            'libur_minggu'     => true,
        ]);

        if (!$setting->jam_tutup_manual) {
            $setting->update(['jam_tutup_manual' => '14:00:00', 'pin_akses' => '1234']);
        }

        // Khusus Sesi Shalat Dzuhur & Ekstrakurikuler: Buka sepanjang hari agar bisa scan siang/sore
        if ($kategori === 'shalat' || $kategori === 'ekskul') {
            $labelSesi = $kategori === 'shalat' ? 'Sesi Shalat Dzuhur Berjamaah Aktif' : 'Sesi Presensi Ekstrakurikuler Aktif';
            return [
                'buka'          => true,
                'buka_scan'     => true,
                'buka_manual'   => true,
                'alasan'        => $labelSesi,
                'alasan_scan'   => $labelSesi,
                'alasan_manual' => $labelSesi,
                'setting'       => $setting,
            ];
        }

        // 1. Cek Hari Minggu (Khusus KBM Harian)
        if ($setting->libur_minggu && Carbon::parse($tanggal)->isSunday()) {
            return [
                'buka'          => false,
                'buka_scan'     => false,
                'buka_manual'   => false,
                'alasan'        => 'Hari Minggu (Libur Rutin)',
                'alasan_scan'   => 'Hari Minggu (Libur Rutin)',
                'alasan_manual' => 'Hari Minggu (Libur Rutin)',
                'setting'       => $setting,
            ];
        }

        // 2. Cek Kalender Hari Libur
        $hariLibur = HariLibur::whereDate('tanggal_mulai', '<=', $tanggal)
                              ->whereDate('tanggal_selesai', '>=', $tanggal)
                              ->first();
        if ($hariLibur) {
            $msg = 'Hari Libur: ' . $hariLibur->keterangan;
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

        // 3. Cek Jam Scan QR Siswa vs Jam Batas Input Manual Wali Kelas
        $buka        = Carbon::parse($setting->jam_buka)->format('H:i:s');
        $tutupScan   = Carbon::parse($setting->jam_tutup)->format('H:i:s');
        $tutupManual = Carbon::parse($setting->jam_tutup_manual)->format('H:i:s');

        $bukaScan   = ($waktu >= $buka && $waktu <= $tutupScan);
        $bukaManual = ($waktu >= $buka && $waktu <= $tutupManual);

        $alasanScan = $bukaScan
            ? 'Scan QR Dibuka (' . substr($buka, 0, 5) . ' - ' . substr($tutupScan, 0, 5) . ' WIB)'
            : 'Scan QR Ditutup (Buka Pukul ' . substr($buka, 0, 5) . ' - ' . substr($tutupScan, 0, 5) . ' WIB)';

        $alasanManual = $bukaManual
            ? 'Input Wali Kelas Dibuka (s/d ' . substr($tutupManual, 0, 5) . ' WIB)'
            : 'Input Wali Kelas Ditutup (Batas Pukul ' . substr($tutupManual, 0, 5) . ' WIB)';

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
     * 1. HALAMAN DEPAN SCANNER + INPUT WALI KELAS (MENDUKUNG MULTI-SESI: HARIAN, SHALAT, EKSKUL)
     */
    public function scanner(Request $request)
    {
        $tanggal          = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $kategori         = $request->get('kategori', 'harian'); // 'harian', 'shalat', 'ekskul'
        $ekskuls          = Ekskul::with('pembina')->where('status', 'Aktif')->orderBy('nama_ekskul', 'asc')->get();
        $selectedEkskulId = $request->get('ekskul_id', $ekskuls->first()->id ?? null);

        $operasional = $this->cekStatusOperasional($tanggal, $kategori);
        $setting     = $operasional['setting'];

        $kelasList       = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $selectedKelasId = $request->get('kelas_id', $kelasList->first()->id ?? null);

        // Daftar siswa aktif untuk Pencarian Nama (Siswa Lupa Kartu QR)
        if ($kategori === 'ekskul' && $selectedEkskulId) {
            $anggotaIds = EkskulAnggota::where('ekskul_id', $selectedEkskulId)->pluck('siswa_id');
            $semuaSiswaAktif = Siswa::with('kelas')
                ->whereIn('id', $anggotaIds)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
        } else {
            $semuaSiswaAktif = Siswa::with('kelas')
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
        }

        $siswas      = collect();
        $presensiMap = [];

        if ($kategori === 'ekskul' && $selectedEkskulId) {
            $anggotaIds = EkskulAnggota::where('ekskul_id', $selectedEkskulId)->pluck('siswa_id');
            $siswas = Siswa::with('kelas')
                ->whereIn('id', $anggotaIds)
                ->where('status', 'Aktif')
                ->orderBy('kelas_id', 'asc')
                ->orderBy('nama_siswa', 'asc')
                ->get();

            $presensis = Presensi::where('tanggal', $tanggal)
                ->where('kategori', 'ekskul')
                ->where('ekskul_id', $selectedEkskulId)
                ->get();

            foreach ($presensis as $p) {
                $presensiMap[$p->siswa_id] = $p;
            }
        } elseif ($selectedKelasId) {
            $siswas = Siswa::where('kelas_id', $selectedKelasId)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();

            $presensis = Presensi::where('kelas_id', $selectedKelasId)
                ->where('tanggal', $tanggal)
                ->where(function ($q) use ($kategori) {
                    if ($kategori === 'harian') {
                        $q->where('kategori', 'harian')->orWhereNull('kategori');
                    } else {
                        $q->where('kategori', $kategori);
                    }
                })
                ->get();

            foreach ($presensis as $p) {
                $presensiMap[$p->siswa_id] = $p;
            }
        }

        $riwayatHariIni = Presensi::with(['siswa', 'kelas', 'ekskul'])
            ->where('tanggal', $tanggal)
            ->where(function ($q) use ($kategori) {
                if ($kategori === 'harian') {
                    $q->where('kategori', 'harian')->orWhereNull('kategori');
                } else {
                    $q->where('kategori', $kategori);
                }
            })
            ->when($kategori === 'ekskul' && $selectedEkskulId, function ($q) use ($selectedEkskulId) {
                $q->where('ekskul_id', $selectedEkskulId);
            })
            ->orderBy('updated_at', 'desc')
            ->take(15)
            ->get();

        return view('presensi.scanner', compact(
            'operasional', 'setting', 'kategori', 'ekskuls', 'selectedEkskulId',
            'kelasList', 'selectedKelasId', 'siswas', 'presensiMap', 'riwayatHariIni', 'semuaSiswaAktif'
        ));
    }

    /**
     * 2. PROSES AJAX SAAT BARCODE DISCAN ATAU DICARI NAMANYA
     */
    public function prosesScan(Request $request)
    {
        $kategori = $request->input('kategori', 'harian');
        $ekskulId = $request->input('ekskul_id');
        $siswaId  = $request->input('siswa_id'); // <-- Variabel yang sebelumnya sempat hilang

        $operasional = $this->cekStatusOperasional(null, $kategori);

        if (!$operasional['buka_scan']) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal! ' . $operasional['alasan_scan'],
            ]);
        }

        $kode = trim($request->kode_barcode ?? '');

        // Jika QR Code berisi teks biodata lengkap (format EMIS), ekstrak angka NISN / Induknya
        if (preg_match('/NISN\s*:\s*([0-9]+)/i', $kode, $matches)) {
            $kode = trim($matches[1]);
        } elseif (preg_match('/INDUK\s*:\s*([0-9]+)/i', $kode, $matches)) {
            $kode = trim($matches[1]);
        }

        $query = Siswa::with('kelas')->where('status', 'Aktif');
        if (!empty($siswaId)) {
            $siswa = $query->where('id', $siswaId)->first();
        } else {
            if (empty($kode)) {
                return response()->json(['status' => 'error', 'message' => 'Kode barcode kosong!']);
            }
            $siswa = $query->where(function ($q) use ($kode) {
                $q->where('nisn', $kode)->orWhere('nomor_induk', $kode);
            })->first();
        }

        if (!$siswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data siswa tidak ditemukan!',
            ]);
        }

        // Validasi Khusus Ekstrakurikuler: Cek apakah siswa terdaftar di anggota ekskul
        $labelSesi = 'Hadir';
        if ($kategori === 'shalat') {
            $labelSesi = 'Shalat Dzuhur';
        } elseif ($kategori === 'ekskul') {
            if (empty($ekskulId)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Pilih kegiatan Ekstrakurikuler terlebih dahulu!',
                ]);
            }
            $eksObj = Ekskul::find($ekskulId);
            $terdaftar = EkskulAnggota::where('ekskul_id', $ekskulId)
                ->where('siswa_id', $siswa->id)
                ->exists();

            if (!$terdaftar) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Ditolak! ' . $siswa->nama_siswa . ' belum terdaftar di anggota ekskul ' . ($eksObj->nama_ekskul ?? '') . '!',
                ]);
            }
            $labelSesi = 'Ekskul ' . ($eksObj->nama_ekskul ?? '');
        }

        $now        = Carbon::now('Asia/Jakarta');
        $tanggal    = $now->format('Y-m-d');
        $waktuSkrg  = $now->format('H:i:s');
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $sudahAbsen = Presensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tanggal)
            ->where(function ($q) use ($kategori) {
                if ($kategori === 'harian') {
                    $q->where('kategori', 'harian')->orWhereNull('kategori');
                } else {
                    $q->where('kategori', $kategori);
                }
            })
            ->when($kategori === 'ekskul', function ($q) use ($ekskulId) {
                $q->where('ekskul_id', $ekskulId);
            })
            ->first();

        if ($sudahAbsen) {
            return response()->json([
                'status'  => 'warning',
                'message' => $siswa->nama_siswa . ' sudah tercatat (' . $sudahAbsen->status . ') pukul ' . $sudahAbsen->waktu_masuk,
                'siswa'   => [
                    'nama'  => $siswa->nama_siswa,
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                ]
            ]);
        }

        Presensi::create([
            'siswa_id'        => $siswa->id,
            'kelas_id'        => $siswa->kelas_id,
            'tahun_ajaran_id' => $tahunAktif->id ?? null,
            'tanggal'         => $tanggal,
            'kategori'        => $kategori,
            'ekskul_id'       => $kategori === 'ekskul' ? $ekskulId : null,
            'waktu_masuk'     => $waktuSkrg,
            'status'          => 'Hadir',
            'metode'          => $request->metode ?? 'Scan Kamera HP',
            'keterangan'      => $kategori !== 'harian' ? $labelSesi : null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil! ' . $siswa->nama_siswa . ' tercatat HADIR (' . $labelSesi . ').',
            'siswa'   => [
                'nama'   => $siswa->nama_siswa,
                'kelas'  => $siswa->kelas->nama_kelas ?? '-',
                'waktu'  => $waktuSkrg,
                'status' => 'Hadir',
                'metode' => $request->metode ?? 'Scan Kamera HP',
                'sesi'   => $labelSesi,
            ]
        ]);
    }

    /**
     * 3. SIMPAN SAKIT / IZIN / ALFA DARI HALAMAN DEPAN (MENGIKUTI JAM BATAS MANUAL GURU)
     */
    public function storeManualDepan(Request $request)
    {
        $kategori    = $request->input('kategori', 'harian');
        $ekskulId    = $request->input('ekskul_id');
        $operasional = $this->cekStatusOperasional(null, $kategori);

        if (!$operasional['buka_manual']) {
            return redirect()->back()->with('error', 'Gagal menyimpan! ' . $operasional['alasan_manual']);
        }

        $request->validate([
            'status' => 'nullable|array',
        ]);

        $now        = Carbon::now('Asia/Jakarta');
        $tanggal    = $now->format('Y-m-d');
        $waktuSkrg  = $now->format('H:i:s');
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $disimpan = 0;
        if ($request->filled('status')) {
            foreach ($request->status as $siswaId => $statusPilihan) {
                if (empty($statusPilihan)) continue;

                $siswaObj = Siswa::find($siswaId);
                if (!$siswaObj) continue;

                $ket = $request->keterangan[$siswaId] ?? null;

                Presensi::updateOrCreate(
                    [
                        'siswa_id'  => $siswaId,
                        'tanggal'   => $tanggal,
                        'kategori'  => $kategori,
                        'ekskul_id' => $kategori === 'ekskul' ? $ekskulId : null,
                    ],
                    [
                        'kelas_id'        => $siswaObj->kelas_id,
                        'tahun_ajaran_id' => $tahunAktif->id ?? null,
                        'waktu_masuk'     => $waktuSkrg,
                        'status'          => $statusPilihan,
                        'metode'          => 'Input Wali Kelas',
                        'keterangan'      => $ket,
                    ]
                );
                $disimpan++;
            }
        }

        return redirect()->route('presensi.scanner', [
            'kategori'  => $kategori,
            'kelas_id'  => $request->kelas_id,
            'ekskul_id' => $ekskulId,
            'tab'       => 'manual'
        ])->with('success', $disimpan . ' Status kehadiran siswa berhasil disimpan!');
    }

    /**
     * 4. HALAMAN DASHBOARD ADMIN
     */
    public function index(Request $request)
    {
        $tanggal         = $request->get('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        $kelasList       = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $tahunAktif      = TahunAjaran::where('is_active', true)->first();
        $selectedKelasId = $request->get('kelas_id', $kelasList->first()->id ?? null);

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
     * 5. UPDATE PENGATURAN JAM & PIN AKSES GURU (OLEH ADMIN)
     */
    public function updateSettingJam(Request $request)
    {
        $request->validate([
            'jam_buka'         => 'required',
            'jam_tutup'        => 'required',
            'jam_tutup_manual' => 'required',
            'pin_akses'        => 'required|string|min:4|max:10',
        ]);

        $setting = PengaturanPresensi::first();
        $setting->update([
            'jam_buka'         => $request->jam_buka,
            'jam_tutup'        => $request->jam_tutup,
            'jam_tutup_manual' => $request->jam_tutup_manual,
            'pin_akses'        => trim($request->pin_akses),
            'libur_minggu'     => $request->has('libur_minggu'),
        ]);

        return redirect()->back()->with('success', 'Pengaturan Jam Operasional & PIN Keamanan berhasil diperbarui!');
    }

    public function storeHariLibur(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'required|string|max:150',
        ]);

        HariLibur::create($request->only(['tanggal_mulai', 'tanggal_selesai', 'keterangan']));

        return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan ke kalender!');
    }

    public function destroyHariLibur($id)
    {
        HariLibur::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data hari libur berhasil dihapus!');
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal'  => 'required|date',
            'status'   => 'nullable|array',
        ]);

        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        if ($request->filled('status')) {
            foreach ($request->status as $siswaId => $statusKehadiran) {
                if (empty($statusKehadiran)) {
                    Presensi::where('siswa_id', $siswaId)
                        ->where('tanggal', $request->tanggal)
                        ->where(function ($q) {
                            $q->where('kategori', 'harian')->orWhereNull('kategori');
                        })
                        ->delete();
                    continue;
                }

                $ket = $request->keterangan[$siswaId] ?? null;

                Presensi::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'tanggal'  => $request->tanggal,
                        'kategori' => 'harian',
                    ],
                    [
                        'kelas_id'        => $request->kelas_id,
                        'tahun_ajaran_id' => $tahunAktif->id ?? null,
                        'waktu_masuk'     => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                        'status'          => $statusKehadiran,
                        'metode'          => 'Admin Dashboard',
                        'keterangan'      => $ket,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui oleh Admin!');
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
     * 9. REKAP & LAPORAN PRESENSI BULANAN (HARIAN, SHALAT DZUHUR, & EKSKUL)
     */
    public function rekap(Request $request)
    {
        $kelasList  = Kelas::with('waliKelas')->orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $ekskulList = Ekskul::with('pembina')->where('status', 'Aktif')->orderBy('nama_ekskul', 'asc')->get();
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $kategori         = $request->get('kategori', 'harian');
        $selectedKelasId  = $request->get('kelas_id', $kelasList->first()->id ?? null);
        $selectedEkskulId = $request->get('ekskul_id', $ekskulList->first()->id ?? null);
        $bulan            = (int) $request->get('bulan', Carbon::now('Asia/Jakarta')->month);
        $tahun            = (int) $request->get('tahun', Carbon::now('Asia/Jakarta')->year);
        $selectedMapelId  = $request->get('kelas_mapel_id', '');

        $kelasTerpilih  = Kelas::with('waliKelas')->find($selectedKelasId);
        $ekskulTerpilih = Ekskul::with('pembina')->find($selectedEkskulId);
        $jumlahHari     = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        $daftarMapelKelas = \App\Models\KelasMapel::with(['mapel', 'guru'])
            ->where('kelas_id', $selectedKelasId)
            ->get();

        $penandatanganGuru    = $kelasTerpilih->waliKelas ?? null;
        $jabatanPenandatangan = 'Guru ' . ($kelasTerpilih->nama_kelas ?? 'Kelas');
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
        $namaBulan = $namaBulanList[$bulan] ?? 'Agustus';

        $ejaanTingkat = [
            1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam'
        ];
        $labelTingkat = $kelasTerpilih ? ($ejaanTingkat[$kelasTerpilih->tingkat] ?? '') : '';

        $tglAwal  = sprintf('%04d-%02d-01', $tahun, $bulan);
        $tglAkhir = sprintf('%04d-%02d-%02d', $tahun, $bulan, $jumlahHari);

        $siswas = collect();
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
            if ($selectedKelasId) {
                $siswas = Siswa::where('kelas_id', $selectedKelasId)
                    ->where('status', 'Aktif')
                    ->orderBy('nama_siswa', 'asc')
                    ->get();
            }

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
        }

        $matriks = [];
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
        }

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

        $dataView = compact(
            'kelasList', 'ekskulList', 'tahunAktif', 'kategori', 'selectedKelasId', 'selectedEkskulId',
            'kelasTerpilih', 'ekskulTerpilih', 'labelTingkat', 'bulan', 'tahun', 'namaBulan', 'namaBulanList',
            'jumlahHari', 'siswas', 'matriks', 'tanggalLibur', 'kepalaMadrasah',
            'daftarMapelKelas', 'selectedMapelId', 'penandatanganGuru', 'jabatanPenandatangan', 'mapelTerpilihObj'
        );

        if ($request->get('export') === 'pdf') {
            return view('presensi.cetak_pdf', $dataView);
        }

        if ($request->get('export') === 'excel') {
            return view('presensi.cetak_excel', $dataView);
        }

        return view('presensi.rekap', $dataView);
    }
}