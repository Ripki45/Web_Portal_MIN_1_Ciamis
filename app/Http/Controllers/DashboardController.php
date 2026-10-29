<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Ekskul;
use App\Models\EkskulAnggota;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use App\Models\PengaturanPresensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->role ?: 'superadmin';

        // Fitur Khusus Superadmin: Bisa Mengintip Dashboard Guru / Pembina Tanpa Logout
        $previewRole = $request->get('preview_role', $role);
        if ($role !== 'superadmin') {
            $previewRole = $role;
        }

        if ($previewRole === 'guru') {
            return $this->dashboardGuru($request, $user, $previewRole);
        }

        if ($previewRole === 'pembina_ekskul') {
            return $this->dashboardPembina($request, $user, $previewRole);
        }

        return $this->dashboardAdmin($request, $user, $previewRole);
    }

    /**
     * 1. KAMAR DASHBOARD KHUSUS GURU (WALI KELAS / GURU MAPEL)
     */
    private function dashboardGuru(Request $request, $user, $previewRole)
    {
        $now          = Carbon::now('Asia/Jakarta');
        $hariIni      = $now->format('Y-m-d');
        $setting      = PengaturanPresensi::first();
        $tahunAjarans = TahunAjaran::orderBy('id', 'desc')->get();
        $tahunAktif   = TahunAjaran::where('is_active', true)->first() ?? $tahunAjarans->first();

        // Filter Tahun Ajaran (Default: Tahun Ajaran Aktif)
        $selectedTaId = $request->get('tahun_ajaran_id', $tahunAktif->id ?? null);
        $taTerpilih   = TahunAjaran::find($selectedTaId) ?? $tahunAktif;

        // Deteksi Kelas yang Diampu oleh Guru (Wali Kelas + Guru Mapel)
        $kelasAmpuanIds = [];
        if ($user->gtk_id) {
            $waliIds  = Kelas::where('wali_kelas_id', $user->gtk_id)->pluck('id')->toArray();
            $mapelIds = KelasMapel::where('guru_id', $user->gtk_id)->pluck('kelas_id')->toArray();
            $kelasAmpuanIds = array_unique(array_merge($waliIds, $mapelIds));
        }

        // Jika Superadmin sedang mode preview atau Guru belum di-plot, tampilkan semua kelas
        if (empty($kelasAmpuanIds) || $user->role === 'superadmin') {
            $daftarKelas = Kelas::with('waliKelas')->orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        } else {
            $daftarKelas = Kelas::with('waliKelas')->whereIn('id', $kelasAmpuanIds)->orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        }

        $selectedKelasId = $request->get('kelas_id', $daftarKelas->first()->id ?? null);
        $kelasTerpilih   = $daftarKelas->where('id', $selectedKelasId)->first() ?? $daftarKelas->first();

        // Ambil Siswa Aktif di Kelas Terpilih
        $siswas   = collect();
        $siswaIds = [];
        if ($kelasTerpilih) {
            $siswas = Siswa::where('kelas_id', $kelasTerpilih->id)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa', 'asc')
                ->get();
            $siswaIds = $siswas->pluck('id')->toArray();
        }

        // --- A. 4 KARTU STATISTIK ATAS KHUSUS KELAS DIAMPU ---
        $totalSiswaKelas = $siswas->count();
        $totalSiswaL     = $siswas->where('jenis_kelamin', 'L')->count();
        $totalSiswaP     = $siswas->where('jenis_kelamin', 'P')->count();

        $presensiHariIni = Presensi::whereIn('siswa_id', $siswaIds)
            ->where('tanggal', $hariIni)
            ->get();

        $presensiKbmHariIni = $presensiHariIni->filter(function ($p) {
            return empty($p->kategori) || $p->kategori === 'harian';
        });

        $hadirKbmHariIni = $presensiKbmHariIni->where('status', 'Hadir')->count();
        $absenKbmHariIni = $presensiKbmHariIni->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
        $persenHadirKbm  = $totalSiswaKelas > 0 ? round(($hadirKbmHariIni / $totalSiswaKelas) * 100, 1) : 0;

        $hadirShalatHariIni = $presensiHariIni->where('kategori', 'shalat')->where('status', 'Hadir')->count();

        // Jumlah siswa kelas ini yang aktif ikut Ekstrakurikuler
        $jumlahSiswaIkutEkskul = EkskulAnggota::whereIn('siswa_id', $siswaIds)
            ->distinct('siswa_id')
            ->count('siswa_id');
        $hadirEkskulHariIni = $presensiHariIni->where('kategori', 'ekskul')->where('status', 'Hadir')->count();

        // --- B. FILTER RENTANG MINGGUAN (SENIN - SABTU) UNTUK GRAFIK ---
        $daftarMinggu = [];
        for ($w = -8; $w <= 2; $w++) {
            $senin = $now->copy()->startOfWeek(Carbon::MONDAY)->addWeeks($w);
            $sabtu = $senin->copy()->addDays(5); // Senin s/d Sabtu (6 Hari Kerja)
            $keyMinggu = $senin->format('Y-m-d');
            $labelMinggu = $senin->translatedFormat('d') . ' - ' . $sabtu->translatedFormat('d F Y');
            if ($w === 0) {
                $labelMinggu .= ' (Minggu Ini)';
            }
            $daftarMinggu[$keyMinggu] = $labelMinggu;
        }

        $defaultSenin  = $now->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $selectedSenin = $request->get('minggu_awal', $defaultSenin);
        if (!array_key_exists($selectedSenin, $daftarMinggu)) {
            $selectedSenin = $defaultSenin;
        }

        $startWeek = Carbon::parse($selectedSenin);
        $labelHariGrafik = [];
        $grafikData = [
            'harian' => ['hadir' => [], 'absen' => []],
            'shalat' => ['hadir' => [], 'absen' => []],
            'ekskul' => ['hadir' => [], 'absen' => []],
        ];

        // Loop 6 Hari (Senin s/d Sabtu) pada minggu yang dipilih
        for ($d = 0; $d < 6; $d++) {
            $tglCek = $startWeek->copy()->addDays($d);
            $tglStr = $tglCek->format('Y-m-d');
            $labelHariGrafik[] = $tglCek->translatedFormat('D, d M');

            $dataTgl = Presensi::whereIn('siswa_id', $siswaIds)
                ->where('tanggal', $tglStr)
                ->when($taTerpilih, function ($q) use ($taTerpilih) {
                    $q->where(function ($sub) use ($taTerpilih) {
                        $sub->where('tahun_ajaran_id', $taTerpilih->id)->orWhereNull('tahun_ajaran_id');
                    });
                })
                ->get();

            // 1. KBM Harian
            $kbmTgl = $dataTgl->filter(fn($item) => empty($item->kategori) || $item->kategori === 'harian');
            $grafikData['harian']['hadir'][] = $kbmTgl->where('status', 'Hadir')->count();
            $grafikData['harian']['absen'][] = $kbmTgl->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();

            // 2. Shalat Dzuhur
            $shalatTgl = $dataTgl->where('kategori', 'shalat');
            $grafikData['shalat']['hadir'][] = $shalatTgl->where('status', 'Hadir')->count();
            $grafikData['shalat']['absen'][] = $shalatTgl->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();

            // 3. Ekskul
            $ekskulTgl = $dataTgl->where('kategori', 'ekskul');
            $grafikData['ekskul']['hadir'][] = $ekskulTgl->where('status', 'Hadir')->count();
            $grafikData['ekskul']['absen'][] = $ekskulTgl->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
        }

        // --- C. TABEL MONITORING SISWA (TOTAL HADIR, SAKIT, IZIN, ALFA) ---
        $semuaPresensiKelas = Presensi::whereIn('siswa_id', $siswaIds)
            ->where(function ($q) {
                $q->where('kategori', 'harian')->orWhereNull('kategori');
            })
            ->when($taTerpilih, function ($q) use ($taTerpilih) {
                $q->where(function ($sub) use ($taTerpilih) {
                    $sub->where('tahun_ajaran_id', $taTerpilih->id)->orWhereNull('tahun_ajaran_id');
                });
            })
            ->get()
            ->groupBy('siswa_id');

        $statusHariIniMap = $presensiKbmHariIni->keyBy('siswa_id');

        $rekapSiswaKelas = [];
        foreach ($siswas as $s) {
            $riwayatS = $semuaPresensiKelas[$s->id] ?? collect();
            $rekapSiswaKelas[$s->id] = [
                'hadir'     => $riwayatS->where('status', 'Hadir')->count(),
                'sakit'     => $riwayatS->where('status', 'Sakit')->count(),
                'izin'      => $riwayatS->where('status', 'Izin')->count(),
                'alfa'      => $riwayatS->where('status', 'Alfa')->count(),
                'hari_ini'  => $statusHariIniMap[$s->id] ?? null,
            ];
        }

        // --- D. 6 AKTIVITAS SCAN TERBARU KHUSUS KELAS INI ---
        $riwayatScanTerbaru = Presensi::with(['siswa', 'kelas', 'ekskul'])
            ->whereIn('siswa_id', $siswaIds)
            ->where('tanggal', $hariIni)
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        return view('dashboard.guru', compact(
            'user', 'previewRole', 'now', 'setting', 'tahunAjarans', 'taTerpilih',
            'daftarKelas', 'kelasTerpilih', 'siswas', 'totalSiswaKelas', 'totalSiswaL', 'totalSiswaP',
            'hadirKbmHariIni', 'absenKbmHariIni', 'persenHadirKbm', 'hadirShalatHariIni',
            'jumlahSiswaIkutEkskul', 'hadirEkskulHariIni', 'daftarMinggu', 'selectedSenin',
            'labelHariGrafik', 'grafikData', 'rekapSiswaKelas', 'riwayatScanTerbaru'
        ));
    }

    /**
     * 2. KAMAR DASHBOARD KHUSUS SUPERADMIN & ADMIN TU (SKALA MADRASAH)
     */
    private function dashboardAdmin(Request $request, $user, $previewRole)
    {
        $now        = Carbon::now('Asia/Jakarta');
        $hariIni    = $now->format('Y-m-d');
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $setting    = PengaturanPresensi::first();

        $totalSiswaAktif = Siswa::where('status', 'Aktif')->count();
        $totalSiswaL     = Siswa::where('status', 'Aktif')->where('jenis_kelamin', 'L')->count();
        $totalSiswaP     = Siswa::where('status', 'Aktif')->where('jenis_kelamin', 'P')->count();
        $totalGtk        = Gtk::count();
        $totalKelas      = Kelas::count();
        $totalEkskul     = Ekskul::where('status', 'Aktif')->count();

        $hadirKbmHariIni = Presensi::where('tanggal', $hariIni)
            ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
            ->where('status', 'Hadir')->count();

        $absenKbmHariIni = Presensi::where('tanggal', $hariIni)
            ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
            ->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();

        $hadirShalatHariIni = Presensi::where('tanggal', $hariIni)
            ->where('kategori', 'shalat')->where('status', 'Hadir')->count();

        $hadirEkskulHariIni = Presensi::where('tanggal', $hariIni)
            ->where('kategori', 'ekskul')->where('status', 'Hadir')->count();

        $persenHadirKbm = $totalSiswaAktif > 0 ? round(($hadirKbmHariIni / $totalSiswaAktif) * 100, 1) : 0;

        $label7Hari = [];
        $data7Hadir = [];
        $data7Absen = [];
        for ($i = 6; $i >= 0; $i--) {
            $tglLoop = $now->copy()->subDays($i)->format('Y-m-d');
            $label7Hari[] = Carbon::parse($tglLoop)->translatedFormat('d M');
            $data7Hadir[] = Presensi::where('tanggal', $tglLoop)
                ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
                ->where('status', 'Hadir')->count();
            $data7Absen[] = Presensi::where('tanggal', $tglLoop)
                ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
                ->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
        }

        $riwayatScanTerbaru = Presensi::with(['siswa', 'kelas', 'ekskul'])
            ->where('tanggal', $hariIni)
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        $daftarKelasMonitor = Kelas::with('waliKelas')
            ->withCount(['siswas' => function ($q) { $q->where('status', 'Aktif'); }])
            ->orderBy('tingkat', 'asc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $presensiHariIniPerKelas = Presensi::where('tanggal', $hariIni)
            ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
            ->get()
            ->groupBy('kelas_id');

        return view('dashboard.admin', compact(
            'user', 'previewRole', 'now', 'tahunAktif', 'setting',
            'totalSiswaAktif', 'totalSiswaL', 'totalSiswaP', 'totalGtk', 'totalKelas', 'totalEkskul',
            'hadirKbmHariIni', 'absenKbmHariIni', 'hadirShalatHariIni', 'hadirEkskulHariIni', 'persenHadirKbm',
            'label7Hari', 'data7Hadir', 'data7Absen', 'riwayatScanTerbaru', 'daftarKelasMonitor', 'presensiHariIniPerKelas'
        ));
    }

    /**
     * 3. KAMAR DASHBOARD KHUSUS PEMBINA EKSTRAKURIKULER
     */
    private function dashboardPembina(Request $request, $user, $previewRole)
    {
        $now        = Carbon::now('Asia/Jakarta');
        $hariIni    = $now->format('Y-m-d');
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        if ($user->gtk_id && $user->role !== 'superadmin') {
            $daftarEkskul = Ekskul::with('pembina')->where('pembina_id', $user->gtk_id)->get();
        } else {
            $daftarEkskul = Ekskul::with('pembina')->orderBy('nama_ekskul', 'asc')->get();
        }

        if ($daftarEkskul->isEmpty()) {
            $daftarEkskul = Ekskul::with('pembina')->orderBy('nama_ekskul', 'asc')->get();
        }

        $selectedEkskulId = $request->get('ekskul_id', $daftarEkskul->first()->id ?? null);
        $ekskulTerpilih   = $daftarEkskul->where('id', $selectedEkskulId)->first() ?? $daftarEkskul->first();

        $anggotaIds = $ekskulTerpilih
            ? EkskulAnggota::where('ekskul_id', $ekskulTerpilih->id)->pluck('siswa_id')->toArray()
            : [];

        $siswas = Siswa::with('kelas')->whereIn('id', $anggotaIds)->where('status', 'Aktif')->orderBy('nama_siswa', 'asc')->get();
        $totalAnggota = $siswas->count();
        $totalL       = $siswas->where('jenis_kelamin', 'L')->count();
        $totalP       = $siswas->where('jenis_kelamin', 'P')->count();

        $presensiHariIni = $ekskulTerpilih
            ? Presensi::where('kategori', 'ekskul')->where('ekskul_id', $ekskulTerpilih->id)->where('tanggal', $hariIni)->get()
            : collect();

        $hadirHariIni = $presensiHariIni->where('status', 'Hadir')->count();
        $absenHariIni = $presensiHariIni->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();

        $semuaPresensiEkskul = $ekskulTerpilih
            ? Presensi::where('kategori', 'ekskul')->where('ekskul_id', $ekskulTerpilih->id)->get()->groupBy('siswa_id')
            : collect();

        $rekapAnggota = [];
        foreach ($siswas as $s) {
            $rw = $semuaPresensiEkskul[$s->id] ?? collect();
            $rekapAnggota[$s->id] = [
                'hadir' => $rw->where('status', 'Hadir')->count(),
                'sakit' => $rw->where('status', 'Sakit')->count(),
                'izin'  => $rw->where('status', 'Izin')->count(),
                'alfa'  => $rw->where('status', 'Alfa')->count(),
            ];
        }

        return view('dashboard.pembina', compact(
            'user', 'previewRole', 'now', 'tahunAktif', 'daftarEkskul', 'ekskulTerpilih',
            'siswas', 'totalAnggota', 'totalL', 'totalP', 'hadirHariIni', 'absenHariIni', 'rekapAnggota'
        ));
    }
}