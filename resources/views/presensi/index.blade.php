@extends('layouts.admin')

@php
    $user          = auth()->user();
    $isGuru        = $user && $user->role === 'guru';
    $kelasAktifObj = $kelasList->where('id', $selectedKelasId)->first() ?? $kelasList->first();
@endphp

@section('title', $isGuru ? 'Presensi Harian ' . ($kelasAktifObj->nama_kelas ?? 'Kelas') : 'Manajemen & Pengaturan Presensi')

@section('content')

    <!-- Header & Tombol Aksi Cepat -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">
                {{ $isGuru ? 'Input & Monitoring Presensi Siswa — ' . ($kelasAktifObj->nama_kelas ?? '') : 'Monitoring & Pengaturan Presensi Siswa' }}
            </h6>
            <span class="text-xs {{ $operasional['buka'] ? 'text-success-600' : 'text-danger-600' }} fw-bold">
                ● Status Saat Ini: {{ $operasional['alasan'] }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Tombol Setting Jam & Hari Libur HANYA untuk Superadmin & Admin TU --}}
            @if (!$isGuru)
                <button type="button" class="btn btn-warning-600 radius-8 px-14 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalSettingJam">
                    <i class="ph-bold ph-clock"></i> Atur Jam ({{ substr($setting->jam_buka, 0, 5) }} - {{ substr($setting->jam_tutup, 0, 5) }})
                </button>
                <button type="button" class="btn btn-danger-600 radius-8 px-14 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalHariLibur">
                    <i class="ph-bold ph-calendar-x"></i> Kalender Hari Libur ({{ $hariLiburs->count() }})
                </button>
            @endif

            @if ($selectedKelasId)
                <a href="{{ route('presensi.kartuQr', ['kelas_id' => $selectedKelasId]) }}" target="_blank" class="btn btn-outline-primary-600 radius-8 px-14 py-9 d-flex align-items-center gap-2 text-sm fw-semibold">
                    <i class="ph-bold ph-identification-card"></i> Cetak Kartu QR {{ $kelasAktifObj->nama_kelas ?? '' }}
                </a>
            @endif

            <a href="{{ route('presensi.scanner', ['kelas_id' => $selectedKelasId]) }}" target="_blank" class="btn btn-success-600 radius-8 px-14 py-9 d-flex align-items-center gap-2 text-sm fw-semibold">
                <i class="ph-bold ph-qr-code"></i> Buka Halaman Scanner
            </a>
        </div>
    </div>

    <!-- FILTER KELAS & TANGGAL + RINGKASAN STATUS -->
    <div class="card radius-12 mb-24">
        <div class="card-body p-20">
            <form action="{{ route('presensi.index') }}" method="GET" class="row gy-3 align-items-end">
                <div class="col-md-3">
                    @if ($isGuru && $kelasList->count() <= 1)
                        {{-- Jika Wali Kelas Murni (1 Kelas): Kunci Kelas Tanpa Dropdown --}}
                        <label class="form-label fw-semibold text-sm mb-6">Kelas Diampu:</label>
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                        <div class="form-control radius-8 bg-primary-50 text-primary-600 border-primary fw-bold d-flex align-items-center gap-2">
                            <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                            <span>{{ $kelasAktifObj->nama_kelas ?? 'Belum Ada Kelas' }}</span>
                        </div>
                    @else
                        {{-- Jika Guru Mapel (> 1 Kelas) atau Admin/Superadmin: Tampilkan Dropdown --}}
                        <label class="form-label fw-semibold text-sm mb-6">
                            {{ $isGuru ? 'Pilih Kelas Ampuan:' : 'Pilih Kelas:' }}
                        </label>
                        <select name="kelas_id" class="form-select radius-8 fw-bold" onchange="this.form.submit()">
                            <?php foreach ($kelasList as $kls): ?>
                                <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>
                                    {{ $kls->nama_kelas }}
                                </option>
                            <?php endforeach; ?>
                        </select>
                    @endif
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-sm mb-6">Tanggal Presensi:</label>
                    <input type="date" name="tanggal" class="form-control radius-8 fw-semibold" value="{{ $tanggal }}" onchange="this.form.submit()">
                </div>

                <div class="col-md-6">
                    <div class="d-flex flex-wrap align-items-center gap-2 justify-content-md-end">
                        <span class="bg-neutral-200 text-secondary-light px-12 py-6 radius-8 text-xs fw-bold">Belum Terscan: {{ $rekap['BelumTerscan'] }}</span>
                        <span class="bg-success-focus text-success-main px-12 py-6 radius-8 text-xs fw-bold">Hadir: {{ $rekap['Hadir'] }}</span>
                        <span class="bg-warning-focus text-warning-main px-12 py-6 radius-8 text-xs fw-bold">Sakit: {{ $rekap['Sakit'] }}</span>
                        <span class="bg-info-focus text-info-main px-12 py-6 radius-8 text-xs fw-bold">Izin: {{ $rekap['Izin'] }}</span>
                        <span class="bg-danger-focus text-danger-main px-12 py-6 radius-8 text-xs fw-bold">Alfa: {{ $rekap['Alfa'] }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL REKAP & INPUT KEHADIRAN -->
    <div class="card radius-12">
        <form action="{{ route('presensi.storeManual') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h6 class="text-md fw-bold mb-0">
                    Data Kehadiran Siswa {{ $kelasAktifObj->nama_kelas ?? '' }} ({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }})
                </h6>
                <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-9 text-sm fw-bold d-flex align-items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                    {{ $isGuru ? 'Simpan Kehadiran Kelas' : 'Simpan Koreksi Admin' }}
                </button>
            </div>

            <div class="card-body p-24">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>NISN / Induk</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">L/P</th>
                                <th>Status & Waktu</th>
                                <th class="text-center" width="18%">Ubah Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $idx => $s)
                                @php
                                    $absen = $presensiMap[$s->id] ?? null;
                                    $st    = $absen ? $absen->status : '';
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="text-xs fw-semibold">{{ $s->nisn ?? $s->nomor_induk ?? '-' }}</td>
                                    <td class="fw-bold text-primary-light">{{ $s->nama_siswa }}</td>
                                    <td class="text-center">{{ $s->jenis_kelamin }}</td>
                                    <td>
                                        @if (!$absen)
                                            <span class="bg-neutral-200 text-secondary-light px-12 py-4 rounded-pill text-xs fw-bold">
                                                Belum Terscan
                                            </span>
                                        @else
                                            @php
                                                $badgeSt = match($absen->status) {
                                                    'Hadir' => 'bg-success-focus text-success-main',
                                                    'Sakit' => 'bg-warning-focus text-warning-main',
                                                    'Izin'  => 'bg-info-focus text-info-main',
                                                    'Alfa'  => 'bg-danger-focus text-danger-main',
                                                    default => 'bg-neutral-200 text-secondary-light'
                                                };
                                            @endphp
                                            <span class="{{ $badgeSt }} px-12 py-4 rounded-pill text-xs fw-bold">
                                                {{ $absen->status }} — {{ substr($absen->waktu_masuk, 0, 5) }} ({{ $absen->metode }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <select name="status[{{ $s->id }}]" class="form-select form-select-sm radius-8 fw-semibold">
                                            <option value="" {{ $st == '' ? 'selected' : '' }}>Belum Terscan</option>
                                            <option value="Hadir" {{ $st == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="Sakit" {{ $st == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="Izin" {{ $st == 'Izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="Alfa" {{ $st == 'Alfa' ? 'selected' : '' }}>Alfa</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="keterangan[{{ $s->id }}]" class="form-control form-control-sm radius-8" value="{{ $absen->keterangan ?? '' }}" placeholder="Catatan...">
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
        </form>
    </div>

    {{-- MODAL PENGATURAN JAM & HARI LIBUR HANYA DIRENDER UNTUK ADMIN / SUPERADMIN --}}
    @if (!$isGuru)
        <!-- MODAL 1: PENGATURAN JAM BUKA, SHALAT DZUHUR, BATAS WALI KELAS, & PIN AKSES -->
        <div class="modal fade" id="modalSettingJam" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Pengaturan Waktu & PIN Keamanan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('presensi.updateSettingJam') }}" method="POST">
                        @csrf
                        <div class="modal-body p-24">
                            {{-- 1. PRESENSI KBM PAGI --}}
                            <div class="mb-20">
                                <h6 class="fw-bold text-sm text-success-600 mb-12">
                                    🏫 1. Presensi KBM Pagi
                                </h6>
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-sm">Jam Buka Scan KBM (WIB)</label>
                                        <input type="time" name="jam_buka" class="form-control radius-8 fw-bold" value="{{ substr($setting->jam_buka ?? '07:15', 0, 5) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-sm">Jam Tutup Scan KBM (WIB)</label>
                                        <input type="time" name="jam_tutup" class="form-control radius-8 fw-bold" value="{{ substr($setting->jam_tutup ?? '09:00', 0, 5) }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. PRESENSI SHALAT DZUHUR --}}
                            <div class="mb-20 pt-16 border-top">
                                <h6 class="fw-bold text-sm text-info-main mb-12">
                                    🕌 2. Presensi Shalat Dzuhur Berjamaah
                                </h6>
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-sm">Jam Buka Scan Shalat (WIB)</label>
                                        <input type="time" name="jam_buka_shalat" class="form-control radius-8 fw-bold" value="{{ substr($setting->jam_buka_shalat ?? '11:30', 0, 5) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-sm">Jam Tutup Scan Shalat (WIB)</label>
                                        <input type="time" name="jam_tutup_shalat" class="form-control radius-8 fw-bold" value="{{ substr($setting->jam_tutup_shalat ?? '12:30', 0, 5) }}" required>
                                    </div>
                                </div>
                                <small class="text-xs text-secondary-light d-block mt-8">
                                    * Catatan: Jam buka presensi <b>Ekstrakurikuler</b> otomatis mengikuti jadwal latihan masing-masing di menu Ekstrakurikuler (Default: 13:00 - 16:30 WIB).
                                </small>
                            </div>

                            {{-- 3. PENGATURAN TAMBAHAN & PIN --}}
                            <div class="mb-20 pt-16 border-top">
                                <h6 class="fw-bold text-sm text-primary-600 mb-12">
                                    🔐 3. Batas Input Manual & PIN Layar Scan
                                </h6>
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-sm">Batas Input Wali Kelas (WIB)</label>
                                        <input type="time" name="jam_tutup_manual" class="form-control radius-8 fw-bold" value="{{ substr($setting->jam_tutup_manual ?? '14:00', 0, 5) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-sm">PIN Keamanan Layar Scan</label>
                                        <input type="text" name="pin_akses" class="form-control radius-8 fw-bold text-center bg-warning-50 border-warning" value="{{ $setting->pin_akses ?? '1234' }}" minlength="4" maxlength="10" required>
                                    </div>
                                </div>
                            </div>

                            {{-- PENGATURAN LIBUR MINGGU --}}
                            <div class="form-switch switch-primary d-flex align-items-center gap-3 pt-16 border-top">
                                <input class="form-check-input" type="checkbox" role="switch" name="libur_minggu" id="libur_minggu" {{ ($setting->libur_minggu ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium text-sm" for="libur_minggu">
                                    Kunci Otomatis Setiap Hari Minggu
                                    <span class="d-block text-xs text-secondary-light fw-normal">
                                        Semua sesi presensi dinonaktifkan pada hari Minggu.
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="submit" class="btn btn-primary-600 radius-8 w-100 fw-bold">
                                Simpan Pengaturan Presensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL 2: KALENDER HARI LIBUR NASIONAL / KHUSUS MADRASAH -->
        <div class="modal fade" id="modalHariLibur" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Kalender Hari Libur Madrasah & Tanggal Merah</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-24">
                        <form action="{{ route('presensi.storeHariLibur') }}" method="POST" class="row gy-3 align-items-end mb-24 pb-24 border-bottom">
                            @csrf
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-xs">Mulai Tanggal</label>
                                <input type="date" name="tanggal_mulai" class="form-control form-control-sm radius-8" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-xs">Sampai Tanggal</label>
                                <input type="date" name="tanggal_selesai" class="form-control form-control-sm radius-8" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-xs">Keterangan Hari Libur</label>
                                <input type="text" name="keterangan" class="form-control form-control-sm radius-8" placeholder="Contoh: Libur Hari Raya / Rapat Guru" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-danger-600 btn-sm radius-8 w-100 py-8 fw-bold">+ Tambah</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table bordered-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal Libur</th>
                                        <th>Keterangan</th>
                                        <th class="text-center" width="10%">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hariLiburs as $libur): ?>
                                        <tr>
                                            <td class="fw-bold text-danger-600 text-sm">
                                                {{ \Carbon\Carbon::parse($libur->tanggal_mulai)->translatedFormat('d M Y') }}
                                                @if ($libur->tanggal_mulai != $libur->tanggal_selesai)
                                                    s/d {{ \Carbon\Carbon::parse($libur->tanggal_selesai)->translatedFormat('d M Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $libur->keterangan }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('presensi.destroyHariLibur', $libur->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger radius-8">Hapus</button>
                                                </form>
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
    @endif

@endsection