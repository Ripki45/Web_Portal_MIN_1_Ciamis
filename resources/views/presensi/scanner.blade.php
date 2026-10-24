<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Presensi Harian - MIN 1 Ciamis</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { background-color: #f0fdf4; min-height: 100vh; }
        .scanner-header { background: linear-gradient(135deg, #15803d, #16a34a); color: #fff; }
        #reader { width: 100%; border-radius: 16px; overflow: hidden; border: 3px solid #16a34a; background: #000; }
        .mode-switch-box { background: #ffffff; border: 2px solid #bbf7d0; border-radius: 12px; }
        .clock-badge { background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.3); border-radius: 10px; padding: 8px 16px; }
        
        /* Layar Kunci PIN Keamanan */
        #pinLockOverlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(8px); z-index: 9999;
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .pin-card {
            background: #fff; border-radius: 20px; max-width: 400px; width: 100%;
            padding: 32px 24px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .pin-input {
            letter-spacing: 12px; font-size: 28px; font-weight: 800;
            text-align: center; border: 2px solid #16a34a; border-radius: 12px; height: 60px;
        }
    </style>
</head>
<body>

    <!-- ========================================================================= -->
    <!-- FITUR 1: LAYAR KUNCI PIN PETUGAS / GURU (DIINGAT 30 HARI DI BROWSER HP)   -->
    <!-- ========================================================================= -->
    @guest
    <div id="pinLockOverlay" style="display: none;">
        <div class="pin-card">
            <div class="w-64-px h-64-px bg-success-focus text-success-600 rounded-circle d-inline-flex align-items-center justify-content-center text-3xl mb-16">
                <i class="ph-duotone ph-shield-check"></i>
            </div>
            <h5 class="fw-bold text-dark mb-4">PIN Akses Presensi</h5>
            <p class="text-xs text-secondary mb-20">Masukkan PIN Keamanan Guru/Petugas MIN 1 Ciamis. Perangkat ini akan diingat selama 30 hari.</p>
            
            <form id="formVerifyPin">
                <input type="password" id="inputPinPetugas" class="form-control pin-input mb-16" placeholder="••••" inputmode="numeric" required autofocus>
                <button type="submit" class="btn btn-success-600 w-100 py-12 radius-12 fw-bold text-md">
                    Buka Portal Presensi
                </button>
            </form>
            <div class="mt-16">
                <a href="{{ route('login') }}" class="text-xs text-secondary hover-text-primary">Login sebagai Super Admin</a>
            </div>
        </div>
    </div>
    @endguest

    <!-- Header Atas dengan Jam Live WIB -->
    <div class="scanner-header py-16 px-24 shadow-sm mb-24">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-12">
                <span class="w-48-px h-48-px bg-white text-success-600 rounded-circle d-flex justify-content-center align-items-center text-2xl fw-bold">
                    <i class="ph-bold ph-qr-code"></i>
                </span>
                <div>
                    <h5 class="mb-0 text-white fw-bold">PRESENSI HARIAN SISWA — MIN 1 CIAMIS</h5>
                    <span class="text-xs text-white opacity-90">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }} | 
                        Scan QR: <b>{{ substr($setting->jam_buka, 0, 5) }} - {{ substr($setting->jam_tutup, 0, 5) }}</b> • 
                        Batas Wali Kelas: <b>{{ substr($setting->jam_tutup_manual ?? '14:00:00', 0, 5) }} WIB</b>
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="clock-badge text-white fw-bold text-md d-flex align-items-center gap-2">
                    <i class="ph-bold ph-clock"></i>
                    <span id="liveClockWib">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }}</span> WIB
                </div>

                @auth
                    <a href="{{ route('presensi.index') }}" class="btn btn-light radius-8 fw-semibold text-sm">
                        Dashboard Admin
                    </a>
                @else
                    <button type="button" onclick="kunciUlangPin()" class="btn btn-outline-light radius-8 fw-semibold text-sm" title="Kunci Layar PIN">
                        <i class="ph-bold ph-lock-key"></i> Kunci PIN
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <div class="container pb-40">

        <!-- Navigasi Tab: 1. Scan Kartu Hadir & 2. Input Wali Kelas -->
        <div class="card radius-16 border-0 shadow-sm mb-24">
            <div class="card-body p-16 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <ul class="nav nav-pills button-tab gap-2" id="presensiDepanTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') != 'manual' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold" 
                                data-bs-toggle="pill" data-bs-target="#pane-scan" type="button" role="tab">
                            <i class="ph-bold ph-qr-code text-lg"></i> 1. Scan Kartu QR Siswa
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') == 'manual' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold" 
                                data-bs-toggle="pill" data-bs-target="#pane-manual" type="button" role="tab">
                            <i class="ph-bold ph-notepad text-lg"></i> 2. Input Sakit / Izin / Alfa (Wali Kelas)
                        </button>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $operasional['buka_scan'] ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-12 py-6 rounded-pill fw-bold text-xs">
                        {{ $operasional['alasan_scan'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <!-- ========================================================================= -->
            <!-- TAB 1: SCANNER KAMERA BELAKANG + CARI NAMA SISWA LUPA KARTU               -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade {{ request('tab') != 'manual' ? 'show active' : '' }}" id="pane-scan" role="tabpanel">
                <div class="row gy-4">
                    <div class="col-lg-5">
                        <div class="card radius-16 border-0 shadow-sm">
                            <div class="card-body p-24">
                                @if ($operasional['buka_scan'])
                                    <!-- Toggle Switch Kamera HP vs Alat Scanner -->
                                    <div class="mode-switch-box p-14 mb-16">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fw-bold text-primary-light d-block text-sm" id="labelModeTitle">
                                                    <i class="ph-fill ph-camera text-success-600"></i> Mode Kamera Belakang HP (Aktif)
                                                </span>
                                                <small class="text-xs text-secondary-light" id="labelModeDesc">
                                                    Matikan toggle jika menggunakan Alat Tembak Barcode USB
                                                </small>
                                            </div>
                                            <div class="form-switch switch-success">
                                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="toggleModeScanner" checked style="width: 48px; height: 24px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Box Kamera HP -->
                                    <div id="boxKameraHp" class="mb-20">
                                        <div id="reader"></div>
                                    </div>

                                    <!-- Box Alat Tembak Barcode USB -->
                                    <div id="boxAlatScanner" class="mb-20" style="display: none;">
                                        <div class="text-center py-20 px-16 bg-neutral-50 radius-12 border">
                                            <i class="ph-duotone ph-barcode text-5xl text-success-600 mb-8"></i>
                                            <h6 class="fw-bold mb-8 text-sm">Mode Alat Tembak Barcode Aktif</h6>
                                            <form id="formLaserScanner">
                                                <input type="text" id="inputKodeBarcode" class="form-control text-center fw-bold radius-8 border-success" placeholder="Tempelkan alat scanner / ketik NISN..." autocomplete="off">
                                            </form>
                                        </div>
                                    </div>

                                    <!-- FITUR 2: SOLUSI SISWA LUPA BAWA KARTU QR (CARI NAMA OTOMATIS) -->
                                    <div class="p-16 radius-12 bg-warning-50 border border-warning-200">
                                        <label class="form-label fw-bold text-xs text-warning-600 mb-6 d-flex align-items-center gap-1">
                                            <i class="ph-bold ph-magnifying-glass"></i> Siswa Lupa Bawa Kartu? Cari Namanya di Sini:
                                        </label>
                                        <div class="d-flex gap-2">
                                            <input type="text" id="inputCariNama" list="listSiswaAktif" class="form-control form-control-sm radius-8 bg-white" placeholder="Ketik nama siswa (misal: ANIPAN)...">
                                            <datalist id="listSiswaAktif">
                                                <?php foreach ($semuaSiswaAktif as$sa): ?>
                                                    <option value="{{ $sa->nisn ?: $sa->nomor_induk }}">{{ $sa->nama_siswa }} — {{$sa->kelas->nama_kelas ?? '' }}</option>
                                                <?php endforeach; ?>
                                            </datalist>
                                            <button type="button" id="btnHadirkanNama" class="btn btn-warning-600 btn-sm radius-8 px-16 fw-bold text-nowrap">
                                                Hadirkan
                                            </button>
                                        </div>
                                    </div>

                                @else
                                    <div class="text-center py-40">
                                        <i class="ph-duotone ph-lock-key text-6xl text-danger-600 mb-12"></i>
                                        <h6 class="fw-bold text-primary-light">Waktu Scan Kartu QR Sudah Ditutup</h6>
                                        <p class="text-xs text-secondary-light mb-0">
                                            {{ $operasional['alasan_scan'] }}.<br>
                                            Bagi Bapak/Ibu Wali Kelas yang ingin menginput siswa Sakit/Izin/Alfa, silakan klik <b>Tab 2</b> di atas (Buka s/d {{ substr($setting->jam_tutup_manual ?? '14:00:00', 0, 5) }} WIB).
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Real-Time Hari Ini -->
                    <div class="col-lg-7">
                        <div class="card radius-16 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-16 px-24 d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0 text-md">Riwayat Presensi Masuk Hari Ini</h6>
                                <span class="text-xs text-secondary-light"><i class="ph-fill ph-speaker-high text-success-600"></i> Suara Panggilan Aktif</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Waktu (WIB)</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyRiwayat">
                                            <?php foreach ($riwayatHariIni as$item): ?>
                                                <tr>
                                                    <td class="fw-bold text-success-600">{{ $item->waktu_masuk }}</td>
                                                    <td class="fw-bold text-primary-light">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                                    <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                                    <td>
                                                        <span class="bg-success-focus text-success-main px-10 py-2 rounded-pill text-xs fw-semibold">
                                                            {{ $item->status }} ({{$item->metode }})
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 2: INPUT WALI KELAS + TOMBOL SAPU BERSIH "BELUM TERSCAN"              -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade {{ request('tab') == 'manual' ? 'show active' : '' }}" id="pane-manual" role="tabpanel">
                <div class="card radius-16 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Input Kehadiran Kelas (Wali Kelas)</h6>
                            <small class="text-xs text-secondary-light">
                                Status saat ini: <b class="{{ $operasional['buka_manual'] ? 'text-success-600' : 'text-danger-600' }}">{{ $operasional['alasan_manual'] }}</b>
                            </small>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <!-- FITUR 4: TOMBOL SAPU BERSIH YANG BELUM TERSCAN -->
                            @if ($operasional['buka_manual'])
                                <button type="button" onclick="setSemuaBelumTerscan('Hadir')" class="btn btn-outline-success-600 btn-sm radius-8 fw-bold text-xs">
                                    ✓ Set Semua Belum Terscan -> Hadir
                                </button>
                                <button type="button" onclick="setSemuaBelumTerscan('Alfa')" class="btn btn-outline-danger-600 btn-sm radius-8 fw-bold text-xs">
                                    ✕ Set Semua Belum Terscan -> Alfa
                                </button>
                            @endif

                            <!-- Pilih Kelas -->
                            <form action="{{ route('presensi.scanner') }}" method="GET" class="d-flex align-items-center gap-2 ms-md-2">
                                <input type="hidden" name="tab" value="manual">
                                <select name="kelas_id" class="form-select form-select-sm radius-8 w-auto fw-bold border-success" onchange="this.form.submit()">
                                    <?php foreach ($kelasList as$kls): ?>
                                        <option value="{{ $kls->id }}" {{ $selectedKelasId ==$kls->id ? 'selected' : '' }}>
                                            {{ $kls->nama_kelas }}
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>

                    <form action="{{ route('presensi.storeManualDepan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">

                        <div class="card-body p-24">
                            <div class="table-responsive">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama Siswa</th>
                                            <th>L/P</th>
                                            <th>Status Saat Ini</th>
                                            <th class="text-center">Ubah Status</th>
                                            <th>Keterangan (Sakit/Izin)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($siswas as $idx =>$s): ?>
                                            @php
                                                $absen = $presensiMap[$s->id] ?? null;
                                                $st    = $absen ? $absen->status : '';
                                            @endphp
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td class="fw-bold text-primary-light">{{ $s->nama_siswa }}</td>
                                                <td>{{ $s->jenis_kelamin }}</td>
                                                <td>
                                                    @if (!$absen)
                                                        <span class="bg-neutral-200 text-secondary-light px-12 py-4 rounded-pill text-xs fw-bold">
                                                            Belum Terscan
                                                        </span>
                                                    @elseif ($st == 'Hadir')
                                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill text-xs fw-bold">
                                                            Hadir ({{ $absen->waktu_masuk }})
                                                        </span>
                                                    @elseif ($st == 'Sakit')
                                                        <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill text-xs fw-bold">Sakit</span>
                                                    @elseif ($st == 'Izin')
                                                        <span class="bg-info-focus text-info-main px-12 py-4 rounded-pill text-xs fw-bold">Izin</span>
                                                    @else
                                                        <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill text-xs fw-bold">Alfa</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <select name="status[{{ $s->id }}]" class="form-select form-select-sm radius-8 fw-semibold select-status-manual" data-existing="{{ $st }}" {{ !$operasional['buka_manual'] ? 'disabled' : '' }}>
                                                        <option value="" {{ $st == '' ? 'selected' : '' }}>-- Belum Terscan --</option>
                                                        <option value="Hadir" {{ $st == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                        <option value="Sakit" {{ $st == 'Sakit' ? 'selected' : '' }}>Sakit (S)</option>
                                                        <option value="Izin" {{ $st == 'Izin' ? 'selected' : '' }}>Izin (I)</option>
                                                        <option value="Alfa" {{ $st == 'Alfa' ? 'selected' : '' }}>Alfa (A)</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="keterangan[{{ $s->id }}]" class="form-control form-control-sm radius-8" value="{{ $absen->keterangan ?? '' }}" placeholder="Alasan sakit/izin..." {{ !$operasional['buka_manual'] ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            @if ($operasional['buka_manual'])
                                <div class="d-flex justify-content-end mt-20">
                                    <button type="submit" class="btn btn-primary-600 radius-8 px-24 py-11 fw-bold d-flex align-items-center gap-2">
                                        <i class="ph-bold ph-floppy-disk"></i> Simpan Data Presensi Kelas
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('backend/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script>
        // =========================================================================
        // 1. LOGIKA PIN KEAMANAN GURU (DIINGAT 30 HARI DI LOCALSTORAGE HP)
        // =========================================================================
        const SERVER_PIN = "{{ $setting->pin_akses ?? '1234' }}";
        const STORAGE_KEY = "min1_pin_verified_until";
        const pinOverlay = document.getElementById('pinLockOverlay');

        function cekPinTersimpan() {
            if (!pinOverlay) return; // Jika Super Admin sudah login, lewati PIN
            const savedData = localStorage.getItem(STORAGE_KEY);
            if (savedData) {
                const parsed = JSON.parse(savedData);
                if (parsed.pin === SERVER_PIN && new Date().getTime() < parsed.expire) {
                    pinOverlay.style.display = 'none';
                    return;
                }
            }
            pinOverlay.style.display = 'flex';
        }

        function kunciUlangPin() {
            localStorage.removeItem(STORAGE_KEY);
            location.reload();
        }

        const formPin = document.getElementById('formVerifyPin');
        if (formPin) {
            formPin.addEventListener('submit', function(e) {
                e.preventDefault();
                const inputVal = document.getElementById('inputPinPetugas').value.trim();
                if (inputVal === SERVER_PIN) {
                    // Simpan selama 30 hari di HP Guru
                    const expireTime = new Date().getTime() + (30 * 24 * 60 * 60 * 1000);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify({ pin: SERVER_PIN, expire: expireTime }));
                    pinOverlay.style.display = 'none';
                    Swal.fire({ icon: 'success', title: 'Akses Dibuka!', text: 'Perangkat ini diingat selama 30 hari.', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'PIN Salah!', text: 'Silakan masukkan PIN Keamanan Madrasah yang benar.', timer: 1800, showConfirmButton: false });
                    document.getElementById('inputPinPetugas').value = '';
                }
            });
        }
        cekPinTersimpan();

        // =========================================================================
        // 2. JAM DIGITAL LIVE WIB
        // =========================================================================
        setInterval(() => {
            const now = new Date();
            const wib = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
            }).format(now).replace(/\./g, ':');
            document.getElementById('liveClockWib').innerText = wib;
        }, 1000);

        // =========================================================================
        // 3. TOMBOL SAPU BERSIH "BELUM TERSCAN" -> HADIR / ALFA
        // =========================================================================
        function setSemuaBelumTerscan(targetStatus) {
            let diubah = 0;
            document.querySelectorAll('.select-status-manual').forEach(select => {
                if (select.value === '') {
                    select.value = targetStatus;
                    diubah++;
                }
            });
            Swal.fire({
                icon: 'info',
                title: diubah + ' Siswa Diubah ke ' + targetStatus,
                text: 'Jangan lupa klik tombol "Simpan Data Presensi Kelas" di bagian bawah tabel.',
                timer: 2500,
                showConfirmButton: false
            });
        }

        // =========================================================================
        // 4. SUARA BEEP + PANGGILAN NAMA BAHASA INDONESIA & KAMERA BELAKANG
        // =========================================================================
        function panggilSuaraSiswa(teks) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const msg = new SpeechSynthesisUtterance(teks);
                msg.lang = 'id-ID';
                msg.rate = 1.05;
                window.speechSynthesis.speak(msg);
            }
        }

        function playBeep(isSuccess = true) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.type = isSuccess ? 'sine' : 'sawtooth';
                osc.frequency.value = isSuccess ? 880 : 300;
                gain.gain.value = 0.2;
                osc.start();
                setTimeout(() => { osc.stop(); ctx.close(); }, isSuccess ? 180 : 350);
            } catch (e) {}
        }

        @if ($operasional['buka_scan'])
            let html5Qrcode = null;
            let isProcessing = false;
            let currentMode = 'Scan Kamera HP';

            function kirimScanKeServer(kodeBarcode, metodeCustom = null) {
                if (isProcessing) return;
                isProcessing = true;

                fetch("{{ route('presensi.prosesScan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ kode_barcode: kodeBarcode, metode: metodeCustom || currentMode })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        playBeep(true);
                        panggilSuaraSiswa("Hadir, " + data.siswa.nama.toLowerCase());

                        const tbody = document.getElementById('tbodyRiwayat');
                        const row = `<tr>
                            <td class="fw-bold text-success-600">${data.siswa.waktu}</td>
                            <td class="fw-bold text-primary-light">${data.siswa.nama}</td>
                            <td>${data.siswa.kelas}</td>
                            <td><span class="bg-success-focus text-success-main px-10 py-2 rounded-pill text-xs fw-semibold">Hadir (${data.siswa.metode})</span></td>
                        </tr>`;
                        tbody.insertAdjacentHTML('afterbegin', row);

                        Swal.fire({ icon: 'success', title: 'Hadir!', text: data.siswa.nama + ' (' + data.siswa.kelas + ')', timer: 1400, showConfirmButton: false });
                    } else {
                        playBeep(false);
                        Swal.fire({ icon: data.status === 'warning' ? 'warning' : 'error', title: 'Perhatian', text: data.message, timer: 2000, showConfirmButton: false });
                    }
                    setTimeout(() => { isProcessing = false; }, 2000);
                })
                .catch(() => { isProcessing = false; });
            }

            // Langsung buka Kamera Belakang (environment) secara otomatis
            function startCameraScanner() {
                html5Qrcode = new Html5Qrcode("reader");
                html5Qrcode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 240, height: 240 } },
                    (decodedText) => { kirimScanKeServer(decodedText); },
                    () => {}
                ).catch(() => {
                    // Fallback jika dibuka di Laptop yang hanya punya 1 webcam depan
                    html5Qrcode.start(
                        { facingMode: "user" },
                        { fps: 10, qrbox: { width: 240, height: 240 } },
                        (decodedText) => { kirimScanKeServer(decodedText); },
                        () => {}
                    );
                });
            }

            function stopCameraScanner() {
                if (html5Qrcode && html5Qrcode.isScanning) {
                    html5Qrcode.stop().then(() => html5Qrcode.clear());
                }
            }

            const toggleSwitch = document.getElementById('toggleModeScanner');
            const boxKamera    = document.getElementById('boxKameraHp');
            const boxAlat      = document.getElementById('boxAlatScanner');
            const inputBarcode = document.getElementById('inputKodeBarcode');

            toggleSwitch.addEventListener('change', function() {
                if (this.checked) {
                    currentMode = 'Scan Kamera HP';
                    boxAlat.style.display = 'none';
                    boxKamera.style.display = 'block';
                    startCameraScanner();
                } else {
                    currentMode = 'Alat Scanner';
                    stopCameraScanner();
                    boxKamera.style.display = 'none';
                    boxAlat.style.display = 'block';
                    inputBarcode.focus();
                }
            });

            document.getElementById('formLaserScanner').addEventListener('submit', function(e) {
                e.preventDefault();
                const kode = inputBarcode.value.trim();
                if (kode) { kirimScanKeServer(kode, 'Alat Scanner'); inputBarcode.value = ''; inputBarcode.focus(); }
            });

            // Tombol Hadirkan dari Pencarian Nama (Siswa Lupa Bawa Kartu)
            document.getElementById('btnHadirkanNama').addEventListener('click', function() {
                const kode = document.getElementById('inputCariNama').value.trim();
                if (kode) {
                    kirimScanKeServer(kode, 'Cari Nama (Lupa Kartu)');
                    document.getElementById('inputCariNama').value = '';
                }
            });

            document.addEventListener('DOMContentLoaded', function() { startCameraScanner(); });
        @endif
    </script>
</body>
</html>