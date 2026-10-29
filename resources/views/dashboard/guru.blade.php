@extends('layouts.admin')

@section('title', 'Dashboard Guru & Wali Kelas - MIN 1 Ciamis')

@section('content')

    {{-- Bar Pintasan Mode Intip Role (Hanya Muncul untuk Superadmin) --}}
    @if ($user->role === 'superadmin')
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 bg-white border border-primary radius-12 px-16 py-10 mb-20 shadow-sm">
            <span class="text-xs fw-bold text-primary-600 d-flex align-items-center gap-2">
                <i class="ph-fill ph-eye text-lg"></i> Mode Pratinjau Superadmin (Anda sedang melihat tampilan Dashboard Guru):
            </span>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dashboard', ['preview_role' => 'superadmin']) }}" class="btn btn-outline-secondary btn-sm radius-8 text-xs fw-bold py-4 px-12">👑 Dashboard Admin</a>
                <a href="{{ route('dashboard', ['preview_role' => 'guru']) }}" class="btn btn-primary-600 btn-sm radius-8 text-xs fw-bold py-4 px-12">👨‍🏫 Dashboard Guru</a>
                <a href="{{ route('dashboard', ['preview_role' => 'pembina_ekskul']) }}" class="btn btn-outline-warning btn-sm radius-8 text-xs fw-bold py-4 px-12">⛺ Dashboard Pembina</a>
            </div>
        </div>
    @endif

    <!-- ===== BARIS 1: HERO BANNER GURU + 4 STAT CARDS KELAS DIAMPU ===== -->
    <div class="row gy-20-px">

        <!-- Hero Banner Kiri + Pemilih Kelas Ampuan -->
        <div class="col-xxl-6 col-lg-6">
            <div style="background: linear-gradient(135deg, #064e3b 0%, #15803d 55%, #16a34a 100%);"
                 class="py-28 rounded-4 ps-32 pe-24 d-flex align-items-center h-100 position-relative overflow-hidden shadow-sm">
                <div class="d-flex align-items-center justify-content-between gap-3 flex-md-nowrap flex-wrap w-100">
                    <div class="max-w-440-px w-100">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-12">
                            <span class="py-6 px-12 bg-white bg-opacity-25 text-white fw-bold text-xs rounded-pill d-inline-flex align-items-center gap-1">
                                <i class="ph-fill ph-chalkboard-teacher"></i> Ruang Guru • {{ $kelasTerpilih->nama_kelas ?? 'Belum Ada Kelas' }}
                            </span>

                            @if ($daftarKelas->count() > 1)
                                <form action="{{ route('dashboard') }}" method="GET" class="d-inline-block">
                                    @if ($user->role === 'superadmin')
                                        <input type="hidden" name="preview_role" value="guru">
                                    @endif
                                    <input type="hidden" name="tahun_ajaran_id" value="{{ $taTerpilih->id ?? '' }}">
                                    <input type="hidden" name="minggu_awal" value="{{ $selectedSenin }}">
                                    <select name="kelas_id" onchange="this.form.submit()" class="form-select form-select-sm radius-8 fw-bold bg-warning-400 text-dark border-0 py-4 px-10 text-xs cursor-pointer">
                                        <?php foreach ($daftarKelas as $kls): ?>
                                            <option value="{{ $kls->id }}" {{ ($kelasTerpilih->id ?? '') == $kls->id ? 'selected' : '' }}>
                                                Pindah: {{ $kls->nama_kelas }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            @endif
                        </div>

                        <h2 class="h4 fw-bold text-white mb-8">
                            Selamat Bertugas,<br>
                            <span class="text-warning-400 text-decoration-underline">
                                {{ $user->name }}
                            </span>
                        </h2>
                        <p class="fw-normal text-xs text-white opacity-90 mb-20">
                            Wali Kelas: <b>{{ $kelasTerpilih->waliKelas->nama_lengkap ?? '-' }}</b> • Tahun Ajaran <b>{{ $taTerpilih->tahun_ajaran ?? '-' }} ({{ $taTerpilih->semester ?? '-' }})</b>
                        </p>
                        <div class="d-flex align-items-center flex-wrap gap-10">
                            <a href="{{ route('presensi.scanner', ['kelas_id' => $kelasTerpilih->id ?? '', 'tab' => 'manual']) }}" target="_blank" class="py-10 px-18 bg-warning-400 rounded-pill text-dark fw-bold text-xs d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="ph-bold ph-pencil-simple-line text-md"></i> Input Absen Kelas
                            </a>
                            <a href="{{ route('presensi.rekap', ['kelas_id' => $kelasTerpilih->id ?? '']) }}" class="py-10 px-18 bg-white rounded-pill text-success-700 fw-bold text-xs d-inline-flex align-items-center gap-2">
                                <i class="ph-bold ph-printer text-md"></i> Cetak Rekap {{ $kelasTerpilih->nama_kelas ?? '' }}
                            </a>
                        </div>
                    </div>
                    <div class="text-center d-lg-block d-none flex-shrink-0 pe-12">
                        <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="MIN 1 Ciamis" style="width: 110px; height: 110px; object-fit: contain; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.25));">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Stat Cards Kanan (Khusus Kelas yang Diampu) -->
        <div class="col-xxl-6 col-lg-6">
            <div class="row gy-20-px">
                <!-- Card 1: Total Siswa Kelas yang Diampu -->
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
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">
                            Total Siswa {{ $kelasTerpilih->nama_kelas ?? 'Kelas' }}
                        </span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $totalSiswaKelas }} <small class="text-xs fw-normal text-secondary-light">Siswa</small></h2>
                            <a href="#tabelMonitoringSiswa" class="text-xs fw-bold text-primary-600">Detail &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Jumlah Hadir KBM Hari Ini -->
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
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $hadirKbmHariIni }} <small class="text-xs fw-normal text-secondary-light">/ {{ $totalSiswaKelas }}</small></h2>
                            <span class="text-xs text-danger-600 fw-bold">S/I/A: {{ $absenKbmHariIni }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Presensi Shalat Dzuhur Kelas Ini -->
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-8 mb-16">
                            <span class="w-44-px h-44-px bg-warning-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-warning-600 flex-shrink-0">
                                <i class="ph-bold ph-mosque"></i>
                            </span>
                            <span class="badge rounded-pill bg-warning-50 text-warning-600 px-12 py-6 fw-bold text-xs">
                                Dari {{ $totalSiswaKelas }} Siswa
                            </span>
                        </div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Presensi Shalat Dzuhur</span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $hadirShalatHariIni }} <small class="text-xs fw-normal text-secondary-light">Jamaah</small></h2>
                            <a href="{{ route('presensi.scanner', ['kategori' => 'shalat', 'kelas_id' => $kelasTerpilih->id ?? '']) }}" target="_blank" class="text-xs fw-bold text-warning-600">Scan &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Jumlah Siswa Aktif Ikut Ekstrakurikuler -->
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-8 mb-16">
                            <span class="w-44-px h-44-px bg-info-focus d-flex align-items-center justify-content-center rounded-circle text-xl text-info-main flex-shrink-0">
                                <i class="ph-bold ph-trophy"></i>
                            </span>
                            <span class="badge rounded-pill bg-info-focus text-info-main px-12 py-6 fw-bold text-xs">
                                Hadir Latihan: {{ $hadirEkskulHariIni }}
                            </span>
                        </div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Siswa Aktif Ekstrakurikuler</span>
                        <div class="d-flex align-items-end justify-content-between gap-8">
                            <h2 class="h4 fw-bold mb-0 text-2xl">{{ $jumlahSiswaIkutEkskul }} <small class="text-xs fw-normal text-secondary-light">Siswa</small></h2>
                            <span class="text-xs text-secondary-light fw-semibold">dari {{ $totalSiswaKelas }} Siswa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== BARIS 2: GRAFIK KEHADIRAN DENGAN FILTER MINGGUAN & SEMESTER + WAKTU SESI ===== -->
    <div class="row gy-20-px mt-1">

        <!-- Kiri (8 Kolom): Grafik Statistik + Filter Rentang Minggu & Tahun Ajaran -->
        <div class="col-xxl-8 col-lg-7">
            <div class="bg-base border border-neutral-200 radius-16 p-24 h-100">
                <div class="d-flex align-items-start justify-content-between gap-12 mb-16 flex-wrap">
                    <div class="d-flex align-items-center gap-12">
                        <span class="w-44-px h-44-px bg-success-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-success-600 flex-shrink-0">
                            <i class="ph-bold ph-chart-bar"></i>
                        </span>
                        <div>
                            <h2 class="h6 fw-bold mb-0 text-lg">Statistik Kehadiran Siswa ({{ $kelasTerpilih->nama_kelas ?? '' }})</h2>
                            <span class="text-secondary-light text-xs">Perbandingan siswa Hadir vs Tidak Hadir (Sakit/Izin/Alfa) per Minggu</span>
                        </div>
                    </div>

                    <!-- Tab Sesi Grafik Interaktif (1. KBM | 2. Shalat | 3. Ekskul) -->
                    <div class="d-flex align-items-center gap-1 bg-neutral-100 rounded-pill p-4">
                        <button type="button" onclick="gantiSesiGrafik('harian', this)" class="btn-sesi-grafik btn rounded-pill px-12 py-4 text-xs fw-bold bg-primary-600 text-white border-0">1. KBM</button>
                        <button type="button" onclick="gantiSesiGrafik('shalat', this)" class="btn-sesi-grafik btn rounded-pill px-12 py-4 text-xs fw-bold text-secondary-light border-0">2. Shalat</button>
                        <button type="button" onclick="gantiSesiGrafik('ekskul', this)" class="btn-sesi-grafik btn rounded-pill px-12 py-4 text-xs fw-bold text-secondary-light border-0">3. Ekskul</button>
                    </div>
                </div>

                <!-- Baris Filter: Rentang Mingguan (Senin-Sabtu) & Tahun Ajaran/Semester -->
                <form action="{{ route('dashboard') }}" method="GET" class="row g-2 align-items-center bg-neutral-50 p-12 radius-12 border mb-16">
                    @if ($user->role === 'superadmin')
                        <input type="hidden" name="preview_role" value="guru">
                    @endif
                    <input type="hidden" name="kelas_id" value="{{ $kelasTerpilih->id ?? '' }}">

                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-secondary-light mb-4">
                            📅 Filter Rentang Mingguan (Senin – Sabtu):
                        </label>
                        <select name="minggu_awal" class="form-select form-select-sm radius-8 fw-bold border-primary" onchange="this.form.submit()">
                            <?php foreach ($daftarMinggu as $tglSenin => $labelMgg): ?>
                                <option value="{{ $tglSenin }}" {{ $selectedSenin == $tglSenin ? 'selected' : '' }}>
                                    {{ $labelMgg }}
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-xs fw-bold text-secondary-light mb-4">
                            🎓 Filter Tahun Ajaran & Semester:
                        </label>
                        <select name="tahun_ajaran_id" class="form-select form-select-sm radius-8 fw-bold border-success" onchange="this.form.submit()">
                            <?php foreach ($tahunAjarans as $ta): ?>
                                <option value="{{ $ta->id }}" {{ ($taTerpilih->id ?? '') == $ta->id ? 'selected' : '' }}>
                                    TA {{ $ta->tahun_ajaran }} — Semester {{ $ta->semester }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <!-- Ringkasan Cepat Hari Ini -->
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
                                <i class="ph-bold ph-user-minus"></i>
                            </span>
                            <div>
                                <h2 class="h5 fw-bold mb-0">{{ max(0, $totalSiswaKelas - $hadirKbmHariIni - $absenKbmHariIni) }} Siswa</h2>
                                <span class="text-secondary-light text-xs fw-medium">Belum Terscan Hari Ini</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tempat Render Grafik ApexCharts -->
                <div id="grafikGuruMingguan" style="min-height: 250px;"></div>
            </div>
        </div>

        <!-- Kanan (4 Kolom): Waktu & Sesi Presensi (Dipertahankan Sesuai Rancangan) -->
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
                        <div class="d-flex align-items-center justify-content-between py-8 border-bottom">
                            <span class="text-xs fw-bold text-secondary-light">📝 Batas Input Wali Kelas</span>
                            <span class="text-xs fw-bold text-danger-600">
                                s/d {{ substr($setting->jam_tutup_manual ?? '14:00', 0, 5) }} WIB
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-8">
                            <span class="text-xs fw-bold text-primary-600">🔑 PIN Akses Wali Kelas</span>
                            <span class="badge bg-primary-50 text-primary-600 fw-extrabold text-xs px-10 py-4">
                                {{ $setting->pin_akses ?? '1234' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-10">
                    <a href="{{ route('presensi.scanner', ['kelas_id' => $kelasTerpilih->id ?? '']) }}" target="_blank" class="bg-primary-600 text-white rounded-pill px-16 py-10 fw-bold text-xs text-center flex-grow-1">
                        Layar Scanner QR
                    </a>
                    <a href="{{ route('presensi.scanner', ['kelas_id' => $kelasTerpilih->id ?? '', 'tab' => 'manual']) }}" target="_blank" class="bg-warning-600 text-white rounded-pill px-16 py-10 fw-bold text-xs text-center flex-grow-1">
                        Input Sakit/Izin
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== BARIS 3: TABEL MONITORING NAMA SISWA (TOTAL H, S, I, A) + AKTIVITAS SCAN TERBARU ===== -->
    <div class="row gy-20-px mt-1" id="tabelMonitoringSiswa">

        <!-- Kiri (8 Kolom): Tabel Monitoring Kehadiran Siswa Kelas (Total Hadir, Sakit, Izin, Alfa) -->
        <div class="col-xxl-8 col-lg-7">
            <div class="bg-base border border-neutral-200 radius-16 p-24 h-100">
                <div class="d-flex align-items-center justify-content-between gap-16 mb-20 flex-wrap">
                    <div class="d-flex align-items-center gap-12">
                        <span class="w-44-px h-44-px bg-primary-50 d-flex align-items-center justify-content-center rounded-circle text-xl text-primary-600 flex-shrink-0">
                            <i class="ph-bold ph-users-three"></i>
                        </span>
                        <div>
                            <h2 class="h6 fw-bold mb-0 text-lg">
                                Monitoring Rekap Kehadiran Siswa — {{ $kelasTerpilih->nama_kelas ?? '' }}
                            </h2>
                            <span class="text-secondary-light text-xs">
                                Akumulasi Total Hadir, Sakit, Izin, dan Alfa pada TA {{ $taTerpilih->tahun_ajaran ?? '' }} ({{ $taTerpilih->semester ?? '' }})
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('presensi.rekap', ['kelas_id' => $kelasTerpilih->id ?? '']) }}" class="btn btn-primary-600 btn-sm radius-8 text-xs fw-bold px-14 py-8">
                        <i class="ph-bold ph-table"></i> Buka Buku Absen Lengkap
                    </a>
                </div>

                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-hover bordered-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th width="6%" class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">Status Hari Ini</th>
                                <th class="text-center">Total Hadir</th>
                                <th class="text-center">Total Sakit</th>
                                <th class="text-center">Total Izin</th>
                                <th class="text-center">Total Alfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $idx => $s)
                                @php
                                    $rk = $rekapSiswaKelas[$s->id] ?? ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0, 'hari_ini' => null];
                                    $pHariIni = $rk['hari_ini'];
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                    <td>
                                        <span class="fw-bold text-primary-light d-block">{{ $s->nama_siswa }}</span>
                                        <span class="text-xs text-secondary-light">NISN: {{ $s->nisn ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($pHariIni)
                                            @if ($pHariIni->status === 'Hadir')
                                                <span class="badge bg-success-focus text-success-main text-xs fw-bold">
                                                    ✓ Hadir ({{ substr($pHariIni->waktu_masuk, 0, 5) }})
                                                </span>
                                            @elseif ($pHariIni->status === 'Sakit')
                                                <span class="badge bg-warning-focus text-warning-main text-xs fw-bold">Sakit</span>
                                            @elseif ($pHariIni->status === 'Izin')
                                                <span class="badge bg-info-focus text-info-main text-xs fw-bold">Izin</span>
                                            @else
                                                <span class="badge bg-danger-focus text-danger-main text-xs fw-bold">Alfa</span>
                                            @endif
                                        @else
                                            <span class="badge bg-neutral-100 text-secondary-light text-xs">Belum Scan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-50 text-success-600 fw-extrabold px-10 py-5">{{ $rk['hadir'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning-50 text-warning-600 fw-extrabold px-10 py-5">{{ $rk['sakit'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-focus text-info-main fw-extrabold px-10 py-5">{{ $rk['izin'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $rk['alfa'] >= 3 ? 'bg-danger-600 text-white' : 'bg-danger-focus text-danger-main' }} fw-extrabold px-10 py-5">
                                            {{ $rk['alfa'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-24 text-secondary-light">
                                        Belum ada data siswa aktif di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kanan (4 Kolom): Aktivitas Scan Terbaru Kelas Ini (Dipertahankan Sesuai Rancangan) -->
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
                                <span class="text-secondary-light text-xs">Siswa {{ $kelasTerpilih->nama_kelas ?? '' }} yang tercatat hari ini</span>
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
                                            {{ $rw->status }} • Sesi {{ ucfirst($rw->kategori ?? 'Harian') }}
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
                                Belum ada siswa {{ $kelasTerpilih->nama_kelas ?? '' }} yang melakukan scan hari ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-16 pt-12 border-top">
                    <a href="{{ route('presensi.scanner', ['kelas_id' => $kelasTerpilih->id ?? '']) }}" target="_blank" class="text-primary-600 fw-bold text-xs d-inline-flex align-items-center gap-2">
                        Buka Layar Scanner Presensi <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // 1. Jam Digital Live WIB
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

    // 2. Grafik ApexCharts Mingguan dengan Pergantian Sesi (KBM / Shalat / Ekskul)
    const semuaDataGrafik = @json($grafikData);
    const labelHariMinggu = @json($labelHariGrafik);
    let chartGuruInstance = null;

    document.addEventListener("DOMContentLoaded", function () {
        const options = {
            chart: {
                type: 'bar',
                height: 255,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            series: [
                { name: 'Siswa Hadir', data: semuaDataGrafik.harian.hadir },
                { name: 'Sakit / Izin / Alfa', data: semuaDataGrafik.harian.absen }
            ],
            colors: ['#16a34a', '#f59e0b'],
            plotOptions: {
                bar: { borderRadius: 6, columnWidth: '45%' }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: labelHariMinggu
            },
            legend: { position: 'top' }
        };

        const chartEl = document.querySelector("#grafikGuruMingguan");
        if (chartEl && typeof ApexCharts !== 'undefined') {
            chartGuruInstance = new ApexCharts(chartEl, options);
            chartGuruInstance.render();
        }
    });

    function gantiSesiGrafik(kategori, btnEl) {
        document.querySelectorAll('.btn-sesi-grafik').forEach(b => {
            b.classList.remove('bg-primary-600', 'text-white');
            b.classList.add('text-secondary-light');
        });
        btnEl.classList.add('bg-primary-600', 'text-white');
        btnEl.classList.remove('text-secondary-light');

        if (chartGuruInstance && semuaDataGrafik[kategori]) {
            chartGuruInstance.updateSeries([
                { name: 'Siswa Hadir', data: semuaDataGrafik[kategori].hadir },
                { name: 'Sakit / Izin / Alfa', data: semuaDataGrafik[kategori].absen }
            ]);
        }
    }
</script>
@endpush