@extends('layouts.admin')

@section('title', 'Kenaikan Kelas, Alumni & Pemeliharaan Sistem')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Pusat Pergantian Tahun Ajaran, Kenaikan Kelas & Pemeliharaan Sistem</h6>
            <span class="text-xs text-secondary-light">
                Dilengkapi <b>Sistem Kunci Berurutan (Kelas 6 $\rightarrow$ Kelas 1)</b> agar data siswa tidak pernah menumpang/bercampur
            </span>
        </div>
        <div>
            <span class="bg-success-focus text-success-main px-14 py-8 rounded-pill fw-bold text-xs">
                TA Aktif: {{ $tahunAktif->tahun_ajaran ?? '2026/2027' }} ({{ $tahunAktif->semester ?? 'Ganjil' }})
            </span>
        </div>
    </div>

    <!-- Navigasi 3 Tab Utama -->
    <div class="card radius-12 mb-24">
        <div class="card-body p-12">
            <ul class="nav nav-pills button-tab gap-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !in_array(request('tab'), ['alumni', 'magic']) ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                            data-bs-toggle="pill" data-bs-target="#tab-kenaikan" type="button">
                        <i class="ph-bold ph-trend-up text-lg"></i> 1. Kenaikan Kelas & Kelulusan Berurutan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'alumni' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                            data-bs-toggle="pill" data-bs-target="#tab-alumni" type="button">
                        <i class="ph-bold ph-graduation-cap text-lg"></i> 2. Arsip Siswa Lulus / Alumni ({{ $alumnis->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'magic' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                            data-bs-toggle="pill" data-bs-target="#tab-magic" type="button">
                        <i class="ph-bold ph-magic-wand text-lg"></i> 3. Tombol Ajaib Reset & Pemeliharaan Sistem
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content">
        <!-- ========================================================================= -->
        <!-- TAB 1: KENAIKAN KELAS BERURUTAN (DARI KELAS 6 TURUN KE KELAS 1)           -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade {{ !in_array(request('tab'), ['alumni', 'magic']) ? 'show active' : '' }}" id="tab-kenaikan">

            <!-- Panduan Urutan Kenaikan Kelas & Peta Status Ruang Kelas -->
            <div class="card radius-12 mb-24 border-primary">
                <div class="card-body p-20">
                    <h6 class="text-sm fw-bold text-primary-600 mb-8">
                        <i class="ph-bold ph-shield-check"></i> Peta Status Ruang Kelas (Aturan Wajib Urutan Atas ke Bawah: Kelas 6 $\rightarrow$ 5 $\rightarrow$ 4 $\rightarrow$ 3 $\rightarrow$ 2 $\rightarrow$ 1)
                    </h6>
                    <p class="text-xs text-secondary-light mb-16">
                        Agar siswa Kelas 1-A tidak menumpang/bercampur dengan siswa Kelas 2-A yang belum naik kelas, sistem <b>mengunci otomatis</b> kelas tujuan yang masih berisi siswa. Kosongkan mulai dari <b>Kelas 6 (Luluskan)</b> terlebih dahulu, lalu naikkan Kelas 5 ke Kelas 6, dan seterusnya.
                    </p>

                    <div class="row g-2">
                        <?php foreach ($kelasListDesc as $klsItem): ?>
                            @php
                                $kosong = $klsItem->siswas_count == 0;
                            @endphp
                            <div class="col-xxl-2 col-md-3 col-6">
                                <a href="{{ route('kenaikan.index', ['kelas_asal_id' => $klsItem->id]) }}"
                                   class="d-block p-12 radius-8 border text-decoration-none {{ $selectedKelasAsalId == $klsItem->id ? 'border-primary bg-primary-50' : ($kosong ? 'bg-success-focus border-success' : 'bg-white') }}">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="fw-black text-sm text-primary-light">{{ $klsItem->nama_kelas }}</span>
                                        <span class="badge {{ $kosong ? 'bg-success-600 text-white' : 'bg-warning-focus text-warning-main' }} text-xs">
                                            {{ $kosong ? '✓ Kosong (0)' : $klsItem->siswas_count . ' Siswa' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-secondary-light d-block">
                                        {{ $klsItem->tingkat == 6 ? 'Tahap 1: Luluskan' : 'Naik ke Tingkat ' . ($klsItem->tingkat + 1) }}
                                    </span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Form Eksekusi Kenaikan Kelas / Kelulusan -->
            @if ($kelasAsal)
                <form action="{{ route('kenaikan.proses') }}" method="POST" id="formProsesKenaikan">
                    @csrf
                    <input type="hidden" name="kelas_asal_id" value="{{ $kelasAsal->id }}">

                    <div class="card radius-12">
                        <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-12">
                                <label class="text-sm fw-bold text-nowrap">Pilih Kelas Asal:</label>
                                <select class="form-select form-select-sm radius-8 fw-bold border-primary" style="min-width: 220px;"
                                        onchange="window.location.href='{{ route('kenaikan.index') }}?kelas_asal_id=' + this.value">
                                    <?php foreach ($kelasListDesc as $klsOpt): ?>
                                        <option value="{{ $klsOpt->id }}" {{ $selectedKelasAsalId == $klsOpt->id ? 'selected' : '' }}>
                                            {{ $klsOpt->nama_kelas }} (Tingkat {{ $klsOpt->tingkat }} — {{ $klsOpt->siswas_count }} Siswa)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tujuan: Jika Kelas 6 = Luluskan ke Alumni | Jika Kelas 1-5 = Pilih Kelas Tingkat Atasnya -->
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                @if ($kelasAsal->tingkat == 6)
                                    <span class="bg-warning-focus text-warning-main px-14 py-8 radius-8 fw-bold text-xs">
                                        🎓 Kelas Akhir (Tingkat 6) $\rightarrow$ Tujuan: LULUSKAN KE ARSIP ALUMNI
                                    </span>
                                    <button type="submit" class="btn btn-success-600 radius-8 px-20 py-9 fw-bold text-sm"
                                            onclick="return confirm('Luluskan semua siswa yang dicentang ke Arsip Alumni? Ruang {{ $kelasAsal->nama_kelas }} akan menjadi kosong.')">
                                        <i class="ph-bold ph-graduation-cap me-1"></i> Luluskan Siswa Terpilih (<span id="countNaikSelected">{{ $siswaAsal->count() }}</span>)
                                    </button>
                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="text-sm fw-bold text-nowrap text-success-600">Naikkan ke Kelas Tujuan:</label>
                                        <select name="kelas_tujuan_id" class="form-select form-select-sm radius-8 fw-bold border-success" style="min-width: 250px;" required>
                                            <option value="" disabled selected>-- Pilih Kelas Tingkat {{ $kelasAsal->tingkat + 1 }} --</option>
                                            <?php foreach ($kelasTujuanList as $kt): ?>
                                                @php $masihIsi = $kt->siswas_count > 0; @endphp
                                                <option value="{{ $kt->id }}">
                                                    {{ $kt->nama_kelas }} — {{ $masihIsi ? '🔒 Masih Isi (' . $kt->siswas_count . ' Siswa - Kosongkan Dulu!)' : '✅ Siap Ditempati (0 Siswa)' }}
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-9 fw-bold text-sm">
                                        <i class="ph-bold ph-arrow-circle-up me-1"></i> Proses Naik Kelas (<span id="countNaikSelected">{{ $siswaAsal->count() }}</span>)
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-24">
                            <div class="mb-12 d-flex justify-content-between align-items-center">
                                <span class="text-xs text-secondary-light">
                                    💡 <b>Tips:</b> Semua siswa otomatis terceklis untuk naik kelas/lulus. Hilangkan ceklis pada siswa yang <b>Tinggal Kelas</b> (jika ada).
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="4%" class="text-center">
                                                <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllNaik" checked>
                                            </th>
                                            <th width="5%">No</th>
                                            <th>No. Induk / NISN</th>
                                            <th>Nama Lengkap Siswa</th>
                                            <th class="text-center">L/P</th>
                                            <th>Kelas Saat Ini</th>
                                            <th>Status Kenaikan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($siswaAsal as $idx => $s)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" class="form-check-input check-item-naik border-secondary cursor-pointer" checked>
                                                </td>
                                                <td>{{ $idx + 1 }}</td>
                                                <td class="text-xs">
                                                    <b>{{ $s->nisn ?: ($s->nomor_induk ?: '-') }}</b>
                                                </td>
                                                <td class="fw-bold text-primary-light">{{ $s->nama_siswa }}</td>
                                                <td class="text-center">{{ $s->jenis_kelamin }}</td>
                                                <td><span class="badge bg-info-focus text-info-main">{{ $kelasAsal->nama_kelas }}</span></td>
                                                <td>
                                                    <span class="badge bg-success-focus text-success-main">
                                                        {{ $kelasAsal->tingkat == 6 ? 'Siap Diluluskan (Alumni)' : 'Siap Naik ke Tingkat ' . ($kelasAsal->tingkat + 1) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-32 text-success-600 fw-bold">
                                                    ✅ Ruang {{ $kelasAsal->nama_kelas }} saat ini sudah kosong (0 Siswa) dan siap menerima kenaikan siswa dari tingkat di bawahnya!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: ARSIP SISWA LULUS / ALUMNI (DENGAN CEKLIS HAPUS PERMANEN)          -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade {{ request('tab') == 'alumni' ? 'show active' : '' }}" id="tab-alumni">
            <div class="card basic-data-table radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h6 class="text-md fw-bold mb-0">Arsip Data Siswa Lulus (Alumni MIN 1 Ciamis)</h6>
                        <span class="text-xs text-secondary-light">Data siswa Kelas VI yang telah diluluskan tersimpan di sini dan dapat dihapus kapan saja</span>
                    </div>

                    <form action="{{ route('kenaikan.bulkDeleteAlumni') }}" method="POST" id="formBulkAlumni" class="d-inline">
                        @csrf
                        <div id="hiddenIdsAlumni"></div>
                        <button type="button" id="btnBulkAlumni" onclick="konfirmasiHapusAlumniTerpilih()" class="btn btn-danger-600 radius-8 px-16 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                            <i class="ph-bold ph-trash text-lg"></i> Hapus Permanen Alumni Terpilih (<span id="countSelectedAlumni">0</span>)
                        </button>
                    </form>
                </div>

                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="tableAlumni">
                            <thead>
                                <tr>
                                    <th width="4%" class="text-center">
                                        <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllAlumni">
                                    </th>
                                    <th width="5%">No</th>
                                    <th>NISN / No. Induk</th>
                                    <th>Nama Lengkap Alumni</th>
                                    <th class="text-center">L/P</th>
                                    <th>Kelas Terakhir</th>
                                    <th>Tahun Lulus</th>
                                    <th>Nama Orang Tua</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alumnis as $idx => $al): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input check-item-alumni border-secondary cursor-pointer" value="{{ $al->id }}">
                                        </td>
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="text-xs"><b>{{ $al->nisn ?: ($al->nomor_induk ?: '-') }}</b></td>
                                        <td class="fw-bold text-primary-light">{{ $al->nama_siswa }}</td>
                                        <td class="text-center">{{ $al->jenis_kelamin }}</td>
                                        <td><span class="badge bg-neutral-200 text-primary-light fw-bold">{{ $al->kelas_terakhir ?: 'Kelas VI' }}</span></td>
                                        <td><span class="badge bg-success-focus text-success-main fw-bold">{{ $al->tahun_lulus ?: '-' }}</span></td>
                                        <td class="text-xs">{{ $al->nama_ayah ?: '-' }} / {{ $al->nama_ibu ?: '-' }}</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: PANEL "TOMBOL AJAIB" RESET & PEMELIHARAAN SISTEM                   -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade {{ request('tab') == 'magic' ? 'show active' : '' }}" id="tab-magic">
            <div class="row g-4">

                <!-- 1. Hapus Cache Sistem -->
                <div class="col-md-6 col-lg-4">
                    <div class="card radius-16 border h-100 shadow-sm">
                        <div class="card-body p-24 d-flex flex-column justify-content-between">
                            <div>
                                <span class="w-48-px h-48-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center text-2xl mb-16">
                                    <i class="ph-bold ph-broom"></i>
                                </span>
                                <h6 class="fw-bold mb-8">1. Bersihkan Cache Sistem</h6>
                                <p class="text-xs text-secondary-light mb-20">
                                    Menghapus file sampah <i>cache view, route,</i> dan konfigurasi agar performa aplikasi kembali ringan dan segar.
                                </p>
                            </div>
                            <form action="{{ route('kenaikan.magicAction') }}" method="POST">
                                @csrf
                                <input type="hidden" name="aksi" value="clear_cache">
                                <button type="submit" class="btn btn-info-600 w-100 radius-8 fw-bold py-10">
                                    ✨ Hapus Cache Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. Reset Kalender Hari Libur -->
                <div class="col-md-6 col-lg-4">
                    <div class="card radius-16 border h-100 shadow-sm">
                        <div class="card-body p-24 d-flex flex-column justify-content-between">
                            <div>
                                <span class="w-48-px h-48-px bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center text-2xl mb-16">
                                    <i class="ph-bold ph-calendar-x"></i>
                                </span>
                                <h6 class="fw-bold mb-8">2. Reset Data Hari Libur ({{ $statSistem['total_hari_libur'] }} Data)</h6>
                                <p class="text-xs text-secondary-light mb-20">
                                    Mengosongkan seluruh daftar hari libur tahun ajaran lama agar kalender siap diisi hari libur tahun ajaran baru.
                                </p>
                            </div>
                            <form action="{{ route('kenaikan.magicAction') }}" method="POST" onsubmit="return confirm('Kosongkan semua daftar hari libur?')">
                                @csrf
                                <input type="hidden" name="aksi" value="reset_hari_libur">
                                <button type="submit" class="btn btn-warning-600 w-100 radius-8 fw-bold py-10">
                                    🗓️ Reset Data Hari Libur
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 3. Reset Jam Scan Barcode ke Default -->
                <div class="col-md-6 col-lg-4">
                    <div class="card radius-16 border h-100 shadow-sm">
                        <div class="card-body p-24 d-flex flex-column justify-content-between">
                            <div>
                                <span class="w-48-px h-48-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center text-2xl mb-16">
                                    <i class="ph-bold ph-clock-counter-clockwise"></i>
                                </span>
                                <h6 class="fw-bold mb-8">3. Reset Jam Scan QR ke Default</h6>
                                <p class="text-xs text-secondary-light mb-20">
                                    Mengembalikan jam buka scan ke <b>07:15 - 09:00 WIB</b>, batas input Wali Kelas <b>14:00 WIB</b>, dan PIN akses ke <b>1234</b>.
                                </p>
                            </div>
                            <form action="{{ route('kenaikan.magicAction') }}" method="POST" onsubmit="return confirm('Kembalikan pengaturan jam scan dan PIN ke standar default?')">
                                @csrf
                                <input type="hidden" name="aksi" value="reset_jam_scan">
                                <button type="submit" class="btn btn-success-600 w-100 radius-8 fw-bold py-10">
                                    ⏰ Reset Jam Scan ke Default
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 4. Reset / Ganti Tahun Ajaran Baru -->
                <div class="col-md-6 col-lg-4">
                    <div class="card radius-16 border h-100 shadow-sm">
                        <div class="card-body p-24 d-flex flex-column justify-content-between">
                            <form action="{{ route('kenaikan.magicAction') }}" method="POST">
                                @csrf
                                <input type="hidden" name="aksi" value="reset_tahun_ajaran">
                                <span class="w-48-px h-48-px bg-primary-50 text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center text-2xl mb-16">
                                    <i class="ph-bold ph-calendar-plus"></i>
                                </span>
                                <h6 class="fw-bold mb-8">4. Set / Reset Tahun Ajaran Baru</h6>
                                <div class="row g-2 mb-16">
                                    <div class="col-7">
                                        <input type="text" name="tahun_ajaran_baru" class="form-control form-control-sm radius-8 fw-bold" value="{{ $tahunAktif->tahun_ajaran ?? '2026/2027' }}" placeholder="2027/2028" required>
                                    </div>
                                    <div class="col-5">
                                        <select name="semester_baru" class="form-select form-select-sm radius-8 fw-bold">
                                            <option value="Ganjil">Ganjil</option>
                                            <option value="Genap">Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-600 w-100 radius-8 fw-bold py-10">
                                    📚 Aktifkan Tahun Ajaran Ini
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 5. Hapus Semua Data Siswa yang Sudah Lulus -->
                <div class="col-md-6 col-lg-4">
                    <div class="card radius-16 border border-danger h-100 shadow-sm">
                        <div class="card-body p-24 d-flex flex-column justify-content-between">
                            <div>
                                <span class="w-48-px h-48-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center text-2xl mb-16">
                                    <i class="ph-bold ph-user-minus"></i>
                                </span>
                                <h6 class="fw-bold mb-8 text-danger-600">5. Bersihkan Semua Data Alumni ({{ $statSistem['total_alumni'] }} Siswa)</h6>
                                <p class="text-xs text-secondary-light mb-20">
                                    Menghapus permanen seluruh data siswa yang sudah berstatus <b>Lulus</b> beserta file pasfotonya dari penyimpanan server.
                                </p>
                            </div>
                            <form action="{{ route('kenaikan.magicAction') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen seluruh data siswa yang sudah Lulus?')">
                                @csrf
                                <input type="hidden" name="aksi" value="hapus_semua_alumni">
                                <button type="submit" class="btn btn-outline-danger w-100 radius-8 fw-bold py-10">
                                    🗑️ Hapus Semua Siswa Lulus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 6. HARD RESET (Hapus Semua Data) -->
                <div class="col-md-6 col-lg-4">
                    <div class="card radius-16 border border-danger bg-danger-50 h-100 shadow-sm">
                        <div class="card-body p-24 d-flex flex-column justify-content-between">
                            <form action="{{ route('kenaikan.magicAction') }}" method="POST" onsubmit="return confirm('PERINGATAN KERAS: Semua data siswa, kelas, ekskul, dan presensi akan dihapus ke setelan awal! Lanjutkan?')">
                                @csrf
                                <input type="hidden" name="aksi" value="hard_reset">
                                <span class="w-48-px h-48-px bg-danger-600 text-white rounded-circle d-inline-flex align-items-center justify-content-center text-2xl mb-16">
                                    <i class="ph-bold ph-warning-octagon"></i>
                                </span>
                                <h6 class="fw-bold mb-8 text-danger-600">6. Hard Reset (Hapus Semua Data)</h6>
                                <p class="text-xs text-secondary-light mb-12">
                                    Mengosongkan seluruh data Siswa, Kelas, Plotting Mapel, Ekskul, dan Presensi (Data GTK tetap aman). Ketik <b>RESET</b> di bawah:
                                </p>
                                <input type="text" name="konfirmasi_teks" class="form-control form-control-sm radius-8 border-danger mb-12 text-center fw-bold" placeholder="Ketik: RESET" required>
                                <button type="submit" class="btn btn-danger-600 w-100 radius-8 fw-bold py-10">
                                    ⚠️ Eksekusi Hard Reset
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // 1. Ceklis Kenaikan Kelas
    const checkAllNaik = document.getElementById('checkAllNaik');
    const spanCountNaik= document.getElementById('countNaikSelected');

    function updateCountNaik() {
        if (!spanCountNaik) return;
        spanCountNaik.innerText = document.querySelectorAll('.check-item-naik:checked').length;
    }

    if (checkAllNaik) {
        checkAllNaik.addEventListener('change', function () {
            document.querySelectorAll('.check-item-naik').forEach(cb => cb.checked = checkAllNaik.checked);
            updateCountNaik();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-naik')) updateCountNaik();
    });

    // 2. Ceklis Tabel Alumni
    let tblAlumni = new DataTable('#tableAlumni', {
        columnDefs: [{ orderable: false, targets: [0] }]
    });

    const checkAllAl = document.getElementById('checkAllAlumni');
    const btnBulkAl  = document.getElementById('btnBulkAlumni');
    const spanAl     = document.getElementById('countSelectedAlumni');

    function updateBulkAl() {
        const checked = document.querySelectorAll('.check-item-alumni:checked');
        spanAl.innerText = checked.length;
        if (checked.length > 0) {
            btnBulkAl.classList.remove('d-none');
            btnBulkAl.classList.add('d-inline-flex');
        } else {
            btnBulkAl.classList.add('d-none');
            btnBulkAl.classList.remove('d-inline-flex');
        }
    }

    if (checkAllAl) {
        checkAllAl.addEventListener('change', function () {
            document.querySelectorAll('.check-item-alumni').forEach(cb => cb.checked = checkAllAl.checked);
            updateBulkAl();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-alumni')) updateBulkAl();
    });

    function konfirmasiHapusAlumniTerpilih() {
        const checked = document.querySelectorAll('.check-item-alumni:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus Permanen ' + checked.length + ' Alumni?',
            text: 'Data alumni yang dicentang akan dihapus permanen dari database!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const box = document.getElementById('hiddenIdsAlumni');
                box.innerHTML = '';
                checked.forEach(cb => {
                    box.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                document.getElementById('formBulkAlumni').submit();
            }
        });
    }
</script>
@endpush