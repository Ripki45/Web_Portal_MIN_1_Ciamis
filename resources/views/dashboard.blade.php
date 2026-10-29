@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
@php
    use App\Models\Siswa;
    use App\Models\Gtk;
    use App\Models\Kelas;
    use App\Models\Ekskul;
    use App\Models\Presensi;
    use App\Models\TahunAjaran;
    use App\Models\PengaturanPresensi;
    use Carbon\Carbon;

    $user       = auth()->user();
    $now        = Carbon::now('Asia/Jakarta');
    $hariIni    = $now->format('Y-m-d');
    $tahunAktif = TahunAjaran::where('is_active', true)->first();
    $setting    = PengaturanPresensi::first();

    // 1. Statistik Utama
    $totalSiswaAktif = Siswa::where('status', 'Aktif')->count();
    $totalSiswaL     = Siswa::where('status', 'Aktif')->where('jenis_kelamin', 'L')->count();
    $totalSiswaP     = Siswa::where('status', 'Aktif')->where('jenis_kelamin', 'P')->count();
    $totalGtk        = Gtk::count();
    $totalKelas      = Kelas::count();
    $totalEkskul     = Ekskul::where('status', 'Aktif')->count();

    // 2. Statistik Presensi Hari Ini per Sesi
    $hadirKbmHariIni = Presensi::where('tanggal', $hariIni)
        ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
        ->where('status', 'Hadir')
        ->count();

    $absenKbmHariIni = Presensi::where('tanggal', $hariIni)
        ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
        ->whereIn('status', ['Sakit', 'Izin', 'Alfa'])
        ->count();

    $hadirShalatHariIni = Presensi::where('tanggal', $hariIni)
        ->where('kategori', 'shalat')
        ->where('status', 'Hadir')
        ->count();

    $hadirEkskulHariIni = Presensi::where('tanggal', $hariIni)
        ->where('kategori', 'ekskul')
        ->where('status', 'Hadir')
        ->count();

    $persenHadirKbm = $totalSiswaAktif > 0 ? round(($hadirKbmHariIni / $totalSiswaAktif) * 100, 1) : 0;

    // 3. Data Grafik 7 Hari Terakhir
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

    // 4. Riwayat 6 Scan Terbaru Hari Ini (Menggantikan "History of Bids")
    $riwayatScanTerbaru = Presensi::with(['siswa', 'kelas', 'ekskul'])
        ->where('tanggal', $hariIni)
        ->orderBy('updated_at', 'desc')
        ->take(6)
        ->get();

    // 5. Rekap Kehadiran Per Kelas Hari Ini (Menggantikan Tabel "Recent Exclusive NFTs")
    $daftarKelasMonitor = Kelas::with('waliKelas')
        ->withCount(['siswas' => function ($q) { $q->where('status', 'Aktif'); }])
        ->orderBy('tingkat', 'asc')
        ->orderBy('nama_kelas', 'asc')
        ->get();

    $presensiHariIniPerKelas = Presensi::where('tanggal', $hariIni)
        ->where(function ($q) { $q->where('kategori', 'harian')->orWhereNull('kategori'); })
        ->get()
        ->groupBy('kelas_id');
@endphp

    <!-- ===== BARIS 1: HERO BANNER + 4 STAT CARDS (ADAPTASI TEMPLATE NFT) ===== -->
    <div class="row gy-20-px">

        <!-- Hero Banner Kiri -->
        <div class="col-xxl-6 col-lg-6">
            <div style="background: linear-gradient(135deg, #064e3b 0%, #15803d 55%, #16a34a 100%);"
                 class="py-32 rounded-4 ps-32 pe-24 d-flex align-items-center h-100 position-relative overflow-hidden shadow-sm">
                <div class="d-flex align-items-center justify-content-between gap-3 flex-md-nowrap flex-wrap w-100">
                    <div class="max-w-440-px w-100">
                        <span class="py-6 px-12 bg-white bg-opacity-25 text-white fw-bold text-xs mb-12 rounded-pill d-inline-flex align-items-center gap-1">
                            <i class="ph-fill ph-shield-check"></i> {{ $user->label_role ?? 'Administrator' }} • TA {{ $tahunAktif->tahun_ajaran ?? '2026/2027' }} ({{ $tahunAktif->semester ?? 'Ganjil' }})
                        </span>
                        <h2 class="h4 fw-bold text-white mb-12 mt-8">
                            Selamat Datang, <br>
                            <span class="text-warning-400 text-decoration-underline">
                                {{ $user->name ?? 'Admin Madrasah' }}
                            </span>
                        </h2>
                        <p class="fw-normal text-sm text-white opacity-90 mb-28">
                            Portal Informasi berita, Manajemen Akademik, Buku Induk Siswa, & Presensi MIN 1 Ciamis.
                        </p>
                        {{-- <div class="d-flex align-items-center flex-wrap gap-10">
                            <a href="{{ route('presensi.scanner') }}" target="_blank" class="py-10 px-20 bg-warning-400 rounded-pill text-dark fw-bold text-sm d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="ph-bold ph-qr-code text-lg"></i> Buka Scanner QR
                            </a>
                            <a href="{{ route('presensi.rekap') }}" class="py-10 px-20 bg-white rounded-pill text-success-700 fw-bold text-sm d-inline-flex align-items-center gap-2">
                                <i class="ph-bold ph-printer text-lg"></i> Rekap Absen
                            </a>
                        </div> --}}
                    </div>
                    <div class="text-center d-lg-block d-none flex-shrink-0 pe-12">
                        <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="MIN 1 Ciamis" style="width: 115px; height: 115px; object-fit: contain; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.25));">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Stat Cards Kanan (2x2 Grid) -->
        <div class="col-xxl-6 col-lg-6">
            <div class="row gy-20-px">
                <!-- Card 1: Total Siswa Aktif -->
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-8 mb-16">
                            <span class="w-44-px h-44-px bg-primary-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-primary-600 flex-shrink-0">
                                <i class="ph-bold ph-student"></i>
                            </span>
                            <span class="badge rounded-pill bg-success-50 text-success-600 px-12 py-6 fw-bold text-xs">
                                L: {{ $totalSiswaL }} • P: {{ $totalSiswaP }}
                            </span>
                        </div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Total Siswa Aktif</span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ number_format($totalSiswaAktif) }} <small class="text-xs fw-normal text-secondary-light">Siswa</small></h2>
                            <a href="{{ route('siswa.index') }}" class="text-xs fw-bold text-primary-600">Detail &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Kehadiran KBM Hari Ini -->
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-8 mb-16">
                            <span class="w-44-px h-44-px bg-success-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-success-600 flex-shrink-0">
                                <i class="ph-bold ph-check-circle"></i>
                            </span>
                            <span class="badge rounded-pill bg-success-50 text-success-600 px-12 py-6 fw-bold text-xs d-inline-flex align-items-center gap-1">
                                <i class="ph-bold ph-trend-up"></i> {{ $persenHadirKbm }}%
                            </span>
                        </div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Hadir KBM Hari Ini</span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $hadirKbmHariIni }} <small class="text-xs fw-normal text-secondary-light">/ {{ $totalSiswaAktif }}</small></h2>
                            <span class="text-xs text-danger-600 fw-semibold">S/I/A: {{ $absenKbmHariIni }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Shalat Dzuhur & Ekskul Hari Ini -->
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-8 mb-16">
                            <span class="w-44-px h-44-px bg-warning-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-warning-600 flex-shrink-0">
                                <i class="ph-bold ph-mosque"></i>
                            </span>
                            <span class="badge rounded-pill bg-warning-50 text-warning-600 px-12 py-6 fw-bold text-xs">
                                Ekskul: {{ $hadirEkskulHariIni }} Hadir
                            </span>
                        </div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Presensi Shalat Dzuhur</span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $hadirShalatHariIni }} <small class="text-xs fw-normal text-secondary-light">Jamaah</small></h2>
                            <a href="{{ route('presensi.scanner', ['kategori' => 'shalat']) }}" target="_blank" class="text-xs fw-bold text-warning-600">Scan &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total GTK & Rombel Kelas -->
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-8 mb-16">
                            <span class="w-44-px h-44-px bg-info-focus d-flex align-items-center justify-content-center rounded-circle text-xl text-info-main flex-shrink-0">
                                <i class="ph-bold ph-chalkboard-teacher"></i>
                            </span>
                            <span class="badge rounded-pill bg-info-focus text-info-main px-12 py-6 fw-bold text-xs">
                                {{ $totalKelas }} Rombel • {{ $totalEkskul }} Ekskul
                            </span>
                        </div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Total Guru & Tendik (GTK)</span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $totalGtk }} <small class="text-xs fw-normal text-secondary-light">Pegawai</small></h2>
                            <a href="{{ route('gtk.index') }}" class="text-xs fw-bold text-info-main">Kelola &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== BARIS 2: GRAFIK KEHADIRAN 7 HARI + WIDGET JAM SESI LIVE ===== -->
    <div class="row gy-20-px mt-1">

        <!-- Kiri (8 Kolom): Grafik Statistik Kehadiran -->
        <div class="col-xxl-8 col-lg-7">
            <div class="bg-base border border-neutral-200 radius-16 p-24 h-100">
                <div class="d-flex align-items-start justify-content-between gap-16 mb-20 flex-wrap">
                    <div class="d-flex align-items-center gap-12">
                        <span class="w-44-px h-44-px bg-success-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-success-600 flex-shrink-0">
                            <i class="ph-bold ph-chart-bar"></i>
                        </span>
                        <div>
                            <h2 class="h6 fw-bold mb-0 text-lg">Statistik Kehadiran Siswa (7 Hari Terakhir)</h2>
                            <span class="text-secondary-light text-xs fw-normal">Perbandingan siswa Hadir vs Tidak Hadir (Sakit/Izin/Alfa)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1 bg-neutral-100 rounded-pill p-4">
                        <a href="{{ route('presensi.scanner', ['kategori' => 'harian']) }}" target="_blank" class="btn rounded-pill px-12 py-4 text-xs fw-bold bg-primary-600 text-white border-0">1. KBM</a>
                        <a href="{{ route('presensi.scanner', ['kategori' => 'shalat']) }}" target="_blank" class="btn rounded-pill px-12 py-4 text-xs fw-bold text-secondary-light border-0">2. Shalat</a>
                        <a href="{{ route('presensi.scanner', ['kategori' => 'ekskul']) }}" target="_blank" class="btn rounded-pill px-12 py-4 text-xs fw-bold text-secondary-light border-0">3. Ekskul</a>
                    </div>
                </div>

                <div class="row g-0 border-top border-bottom border-neutral-200 mb-16">
                    <div class="col-6 border-end border-neutral-200 py-12 px-8">
                        <div class="d-flex align-items-center justify-content-center gap-12">
                            <span class="w-40-px h-40-px bg-success-50 d-flex align-items-center justify-content-center rounded-circle text-lg text-success-600">
                                <i class="ph-bold ph-user-check"></i>
                            </span>
                            <div>
                                <h2 class="h5 fw-bold mb-0">{{ $hadirKbmHariIni }} Siswa</h2>
                                <span class="text-secondary-light text-xs fw-medium">Tercatat Hadir Hari Ini</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 py-12 px-8">
                        <div class="d-flex align-items-center justify-content-center gap-12">
                            <span class="w-40-px h-40-px bg-danger-50 d-flex align-items-center justify-content-center rounded-circle text-lg text-danger-600">
                                <i class="ph-bold ph-user-Minus"></i>
                            </span>
                            <div>
                                <h2 class="h5 fw-bold mb-0">{{ max(0, $totalSiswaAktif - $hadirKbmHariIni - $absenKbmHariIni) }} Siswa</h2>
                                <span class="text-secondary-light text-xs fw-medium">Belum Terscan Hari Ini</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tempat Render Grafik ApexCharts -->
                <div id="presensiMingguanChart" style="min-height: 250px;"></div>
            </div>
        </div>

        <!-- Kanan (4 Kolom): Widget Jam Live & Jadwal 3 Sesi Presensi (Adaptasi Kartu Countdown NFT) -->
        <div class="col-xxl-4 col-lg-5">
            <div class="bg-base border border-neutral-200 radius-16 p-24 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between gap-8 mb-20">
                        <div class="d-flex align-items-center gap-12">
                            <span class="w-48-px h-48-px bg-primary-50 text-primary-600 rounded-circle d-flex align-items-center justify-content-center text-2xl fw-bold">
                                <i class="ph-duotone ph-clock-countdown"></i>
                            </span>
                            <div>
                                <h2 class="h6 fw-bold mb-0 text-md">Waktu & Sesi Presensi</h2>
                                <span class="text-secondary-light text-xs">{{ $now->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>
                        <span class="badge bg-success-focus text-success-main px-10 py-6 rounded-pill text-xs fw-bold">
                            WIB Live
                        </span>
                    </div>

                    <!-- Kotak Jam Digital Live (Menggunakan style nft-count-box dari Template NFT) -->
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-20 p-16 bg-neutral-50 radius-12 border">
                        <div class="text-center flex-grow-1">
                            <span class="text-secondary-light text-xs d-block mb-4">JAM</span>
                            <span class="h4 fw-black text-primary-600 mb-0" id="dashJam">--</span>
                        </div>
                        <span class="h4 fw-bold text-secondary-light mb-0">:</span>
                        <div class="text-center flex-grow-1">
                            <span class="text-secondary-light text-xs d-block mb-4">MENIT</span>
                            <span class="h4 fw-black text-primary-600 mb-0" id="dashMenit">--</span>
                        </div>
                        <span class="h4 fw-bold text-secondary-light mb-0">:</span>
                        <div class="text-center flex-grow-1">
                            <span class="text-secondary-light text-xs d-block mb-4">DETIK</span>
                            <span class="h4 fw-black text-warning-600 mb-0" id="dashDetik">--</span>
                        </div>
                    </div>

                    <!-- Rincian Jam Buka 3 Sesi -->
                    <div class="mb-20">
                        <div class="d-flex align-items-center justify-content-between py-8 border-bottom">
                            <span class="text-xs fw-bold text-primary-light">🏫 1. Scan KBM Pagi</span>
                            <span class="badge bg-success-50 text-success-600 fw-bold text-xs">
                                {{ substr($setting->jam_buka ?? '07:15', 0, 5) }} - {{ substr($setting->jam_tutup ?? '09:00', 0, 5) }} WIB
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-8 border-bottom">
                            <span class="text-xs fw-bold text-primary-light">🕌 2. Scan Shalat Dzuhur</span>
                            <span class="badge bg-info-focus text-info-main fw-bold text-xs">
                                {{ substr($setting->jam_buka_shalat ?? '11:30', 0, 5) }} - {{ substr($setting->jam_tutup_shalat ?? '12:30', 0, 5) }} WIB
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-8 border-bottom">
                            <span class="text-xs fw-bold text-primary-light">⛺ 3. Scan Ekstrakurikuler</span>
                            <span class="badge bg-warning-focus text-warning-main fw-bold text-xs">
                                Sesuai Jadwal (13:00 - 16:30)
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-8">
                            <span class="text-xs fw-bold text-secondary-light">📝 Batas Input Wali Kelas</span>
                            <span class="text-xs fw-bold text-danger-600">
                                s/d {{ substr($setting->jam_tutup_manual ?? '14:00', 0, 5) }} WIB
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-10">
                    <a href="{{ route('presensi.index') }}" class="bg-primary-600 text-white rounded-pill px-16 py-10 fw-bold text-xs text-center flex-grow-1">
                        Atur Jam & Libur
                    </a>
                    <a href="{{ route('presensi.scanner', ['tab' => 'manual']) }}" target="_blank" class="bg-warning-600 text-white rounded-pill px-16 py-10 fw-bold text-xs text-center flex-grow-1">
                        Input Wali Kelas
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== BARIS 3: TABEL PROGRES KELAS HARI INI + RIWAYAT SCAN LIVE ===== -->
    <div class="row gy-20-px mt-1">

        <!-- Kiri (8 Kolom): Tabel Monitoring Progres Kehadiran Per Kelas -->
        <div class="col-xxl-8 col-lg-7">
            <div class="bg-base border border-neutral-200 radius-16 p-24 h-100">
                <div class="d-flex align-items-center justify-content-between gap-16 mb-20 flex-wrap">
                    <div class="d-flex align-items-center gap-12">
                        <span class="w-44-px h-44-px bg-primary-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-primary-600 flex-shrink-0">
                            <i class="ph-bold ph-buildings"></i>
                        </span>
                        <div>
                            <h2 class="h6 fw-bold mb-0 text-lg">Monitoring Kehadiran Rombel Kelas Hari Ini</h2>
                            <span class="text-secondary-light text-xs">Klik tombol Cek untuk melihat detail absen kelas</span>
                        </div>
                    </div>
                    <a href="{{ route('kelas.index') }}" class="text-xs fw-bold text-primary-600">Lihat Semua Kelas &rarr;</a>
                </div>

                <div class="table-responsive" style="max-height: 360px; overflow-y: auto;">
                    <table class="table table-hover bordered-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Wali Kelas</th>
                                <th class="text-center">Siswa</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">S/I/A</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftarKelasMonitor as $kls): ?>
                                @php
                                    $listP = $presensiHariIniPerKelas[$kls->id] ?? collect();
                                    $jmlH  = $listP->where('status', 'Hadir')->count();
                                    $jmlSIA= $listP->whereIn('status', ['Sakit', 'Izin', 'Alfa'])->count();
                                @endphp
                                <tr>
                                    <td class="fw-bold text-primary-light">{{ $kls->nama_kelas }}</td>
                                    <td class="text-xs">{{ $kls->waliKelas->nama_lengkap ?? '-' }}</td>
                                    <td class="text-center fw-bold">{{ $kls->siswas_count }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success-focus text-success-main fw-bold">{{ $jmlH }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning-focus text-warning-main fw-bold">{{ $jmlSIA }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('presensi.index', ['kelas_id' => $kls->id]) }}" class="btn btn-outline-primary btn-sm py-4 px-10 radius-8 text-xs fw-bold">
                                            Cek
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kanan (4 Kolom): Riwayat Scan Presensi Terbaru Hari Ini (Adaptasi "History of Bids") -->
        <div class="col-xxl-4 col-lg-5">
            <div class="bg-base border border-neutral-200 radius-16 p-24 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between gap-8 mb-20">
                        <div class="d-flex align-items-center gap-12">
                            <span class="w-44-px h-44-px bg-warning-100 d-flex align-items-center justify-content-center rounded-circle text-xl text-warning-600 flex-shrink-0">
                                <i class="ph-bold ph-qr-code"></i>
                            </span>
                            <div>
                                <h2 class="h6 fw-bold mb-0 text-lg">Aktivitas Scan Terbaru</h2>
                                <span class="text-secondary-light text-xs">6 Siswa terakhir yang tercatat hari ini</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-14">
                        @forelse ($riwayatScanTerbaru as $rw)
                            @php
                                $namaS = $rw->siswa->nama_siswa ?? 'Siswa';
                                $fotoS = ($rw->siswa && $rw->siswa->foto)
                                    ? asset('storage/' . $rw->siswa->foto)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($namaS) . '&background=16a34a&color=fff&bold=true';
                            @endphp
                            <div class="d-flex align-items-center justify-content-between gap-12 pb-10 border-bottom">
                                <div class="d-flex align-items-center gap-10">
                                    <img src="{{ $fotoS }}" alt="Foto" class="w-40-px h-40-px rounded-circle flex-shrink-0 object-fit-cover border">
                                    <div>
                                        <h6 class="text-xs fw-bold mb-0 text-primary-light">{{ $namaS }}</h6>
                                        <span class="text-xs text-secondary-light">
                                            {{ $rw->kelas->nama_kelas ?? '-' }} • {{ ucfirst($rw->kategori ?? 'Harian') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success-focus text-success-main text-xs fw-bold">
                                        {{ substr($rw->waktu_masuk, 0, 5) }} WIB
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-32 text-secondary-light text-xs">
                                Belum ada aktivitas scan presensi hari ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-16 pt-12 border-top">
                    <a href="{{ route('presensi.scanner') }}" target="_blank" class="text-primary-600 fw-bold text-xs d-inline-flex align-items-center gap-2">
                        Buka Layar Scanner Presensi <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // 1. Jam Digital Live WIB di Kotak Countdown
    function updateDashboardClock() {
        const now = new Date();
        const parts = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
        }).formatToParts(now);

        let h = '00', m = '00', s = '00';
        parts.forEach(p => {
            if (p.type === 'hour') h = p.value;
            if (p.type === 'minute') m = p.value;
            if (p.type === 'second') s = p.value;
        });

        document.getElementById('dashJam').innerText   = h;
        document.getElementById('dashMenit').innerText = m;
        document.getElementById('dashDetik').innerText = s;
    }
    setInterval(updateDashboardClock, 1000);
    updateDashboardClock();

    // 2. Grafik ApexCharts Tren Kehadiran 7 Hari Terakhir
    document.addEventListener("DOMContentLoaded", function () {
        const options = {
            chart: {
                type: 'bar',
                height: 260,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            series: [
                { name: 'Siswa Hadir', data: @json($data7Hadir) },
                { name: 'Sakit / Izin / Alfa', data: @json($data7Absen) }
            ],
            colors: ['#16a34a', '#f59e0b'],
            plotOptions: {
                bar: { borderRadius: 6, columnWidth: '45%' }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($label7Hari)
            },
            legend: { position: 'top' }
        };

        const chartEl = document.querySelector("#presensiMingguanChart");
        if (chartEl && typeof ApexCharts !== 'undefined') {
            new ApexCharts(chartEl, options).render();
        }
    });
</script>
@endpush