@extends('layouts.admin')

@section('title', ($isGuru ?? false) ? 'Data Siswa Kelas Ampuan - MIN 1 Ciamis' : 'Data Siswa & Buku Mutasi - MIN 1 Ciamis')

@section('content')
@php
    $isGuru = $isGuru ?? (auth()->check() && auth()->user()->role === 'guru');
    $kelasAktifObj = $kelasList->where('id', $selectedKelasId ?? request('kelas_id'))->first();
@endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">
                {{ $isGuru ? 'Data Siswa ' . ($kelasAktifObj->nama_kelas ?? 'Kelas Ampuan') : 'Data Induk Siswa MIN 1 Ciamis' }}
            </h6>
            @if ($isGuru)
                <span class="text-xs text-secondary-light">
                    Daftar siswa aktif di kelas yang Anda ampu (Anda dapat memperbarui biodata/pasfoto & mencetak Kartu QR Siswa)
                </span>
            @endif
        </div>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Akademik & Kelas</li>
            <li>-</li>
            <li class="fw-medium text-primary-600">Data Siswa</li>
        </ul>
    </div>

    <!-- KARTU REKAP STATISTIK SISWA (L / P / JUMLAH) -->
    <div class="row gy-4 mb-24">
        <div class="col-sm-4">
            <div class="card px-24 py-16 radius-12 border h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">
                            Total Siswa {{ $kelasAktifObj ? $kelasAktifObj->nama_kelas : '(Jml)' }}
                        </span>
                        <h4 class="fw-bold text-primary-600 mb-0">{{ $siswas->count() }} <span class="text-sm fw-normal text-secondary-light">Siswa</span></h4>
                    </div>
                    <span class="w-48-px h-48-px bg-success-focus text-success-main rounded-circle d-flex justify-content-center align-items-center text-2xl">
                        <i class="ph-duotone ph-users-three"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card px-24 py-16 radius-12 border h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Siswa Laki-Laki (L)</span>
                        <h4 class="fw-bold text-info-main mb-0">{{ $totalL }} <span class="text-sm fw-normal text-secondary-light">Siswa</span></h4>
                    </div>
                    <span class="w-48-px h-48-px bg-info-focus text-info-main rounded-circle d-flex justify-content-center align-items-center text-2xl">
                        <i class="ph-duotone ph-student"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card px-24 py-16 radius-12 border h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Siswa Perempuan (P)</span>
                        <h4 class="fw-bold text-warning-main mb-0">{{ $totalP }} <span class="text-sm fw-normal text-secondary-light">Siswi</span></h4>
                    </div>
                    <span class="w-48-px h-48-px bg-warning-focus text-warning-main rounded-circle d-flex justify-content-center align-items-center text-2xl">
                        <i class="ph-duotone ph-user-focus"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- NAVIGASI 3 TAB (HANYA MUNCUL UNTUK SUPERADMIN & ADMIN TU)                 -->
    <!-- ========================================================================= -->
    @if (!$isGuru)
        <div class="card radius-12 mb-24">
            <div class="card-body p-12 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <ul class="nav nav-pills button-tab gap-2" id="siswaUtamaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ !in_array(request('tab'), ['masuk', 'keluar']) ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                                data-bs-toggle="pill" data-bs-target="#pane-siswa-aktif" type="button" role="tab">
                            <i class="ph-bold ph-student text-lg"></i> 1. Data Siswa Aktif ({{ $siswas->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') == 'masuk' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                                data-bs-toggle="pill" data-bs-target="#pane-mutasi-masuk" type="button" role="tab">
                            <i class="ph-bold ph-sign-in text-lg"></i> 2. Buku Mutasi Masuk ({{ $mutasiMasuk->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ request('tab') == 'keluar' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                                data-bs-toggle="pill" data-bs-target="#pane-mutasi-keluar" type="button" role="tab">
                            <i class="ph-bold ph-sign-out text-lg"></i> 3. Buku Mutasi Keluar ({{ $mutasiKeluar->count() }})
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    <div class="tab-content">
        <!-- ========================================================================= -->
        <!-- TAB 1: DATA SISWA AKTIF                                                   -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade {{ !in_array(request('tab'), ['masuk', 'keluar']) || $isGuru ? 'show active' : '' }}" id="pane-siswa-aktif" role="tabpanel">

            <!-- Baris Filter Kelas & Tombol Aksi Siswa Aktif -->
            <div class="card radius-12 mb-24">
                <div class="card-body p-16 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <form action="{{ route('siswa.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                        @if ($isGuru)
                            {{-- JIKA WALI KELAS MURNI (1 KELAS): Tampilkan Badge Kelas Tanpa Dropdown --}}
                            @if ($kelasList->count() <= 1)
                                <span class="badge bg-primary-50 text-primary-600 border border-primary px-14 py-8 radius-8 text-sm fw-bold d-inline-flex align-items-center gap-2">
                                    <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                    Kelas Diampu: {{ $kelasList->first()->nama_kelas ?? 'Belum Diplot' }}
                                </span>
                            @else
                                {{-- JIKA GURU MAPEL (> 1 KELAS): Tampilkan Dropdown Khusus Kelas yang Diajar --}}
                                <label class="text-sm fw-semibold text-primary-light">Pilih Kelas Ampuan:</label>
                                <select name="kelas_id" class="form-select form-select-sm radius-8 fw-bold border-primary" style="min-width: 190px;" onchange="this.form.submit()">
                                    <?php foreach ($kelasList as $kls): ?>
                                        <option value="{{ $kls->id }}" {{ ($selectedKelasId ?? request('kelas_id')) == $kls->id ? 'selected' : '' }}>
                                            {{ $kls->nama_kelas }}
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            @endif
                        @else
                            {{-- JIKA SUPERADMIN / ADMIN TU: Tampilkan Semua Kelas --}}
                            <label class="text-sm fw-semibold text-primary-light">Filter Kelas:</label>
                            <select name="kelas_id" class="form-select form-select-sm radius-8 fw-bold" style="min-width: 210px;" onchange="this.form.submit()">
                                <option value="">-- Tampilkan Semua Kelas --</option>
                                <?php foreach ($kelasList as $kls): ?>
                                    <option value="{{ $kls->id }}" {{ ($selectedKelasId ?? request('kelas_id')) == $kls->id ? 'selected' : '' }}>
                                        {{ $kls->nama_kelas }}
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        @endif

                        @if (!empty($selectedKelasId))
                            <a href="{{ route('presensi.kartuQr', ['kelas_id' => $selectedKelasId]) }}" target="_blank" class="btn btn-warning-600 btn-sm radius-8 px-12 py-6 d-flex align-items-center gap-1 fw-bold">
                                <i class="ph-bold ph-identification-card text-md"></i> Cetak Kartu 1 Kelas Ini
                            </a>
                        @endif
                    </form>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Tombol Cetak Kartu Terpilih (Bisa dipakai Guru & Admin) -->
                        <button type="button" id="btnBulkPrintKartu" onclick="cetakKartuTerpilih()" class="btn btn-warning-600 radius-8 px-14 py-8 d-none align-items-center gap-2 text-sm fw-bold">
                            <i class="ph-bold ph-identification-card text-lg"></i> Cetak Kartu Terpilih (<span id="countPrintSiswa">0</span>)
                        </button>

                        {{-- Tombol Hapus, Import Excel, & Tambah Siswa HANYA untuk Superadmin & Admin TU --}}
                        @if (!$isGuru)
                            <form action="{{ route('siswa.bulkDestroy') }}" method="POST" id="formBulkDeleteSiswa" class="d-inline">
                                @csrf
                                <div id="containerHiddenIdsSiswa"></div>
                                <button type="button" id="btnBulkDeleteSiswa" onclick="konfirmasiBulkDeleteSiswa()" class="btn btn-danger-600 radius-8 px-14 py-8 d-none align-items-center gap-2 text-sm fw-bold">
                                    <i class="ph-bold ph-trash text-lg"></i> Hapus Terpilih (<span id="countSelectedSiswa">0</span>)
                                </button>
                            </form>

                            <button type="button" class="btn btn-success-600 radius-8 px-14 py-8 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#importPasteModal">
                                <i class="ph-bold ph-file-xls text-lg"></i> Import Copy-Paste Excel
                            </button>

                            <button type="button" class="btn btn-primary-600 radius-8 px-14 py-8 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addSiswaModal">
                                <i class="ph-bold ph-user-plus text-lg"></i> + Tambah Siswa
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tabel Utama Data Siswa Aktif -->
            <div class="card basic-data-table radius-12">
                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="dataTableSiswa" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th scope="col" width="3%" class="text-center">
                                        <input class="form-check-input border-secondary cursor-pointer" type="checkbox" id="checkAllSiswa">
                                    </th>
                                    <th scope="col" width="4%">Urut</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">No. Induk / NISN</th>
                                    <th scope="col">Pasfoto & Nama Siswa</th>
                                    <th scope="col" class="text-center">L/P</th>
                                    <th scope="col">Tempat, Tgl Lahir</th>
                                    <th scope="col">Nama Orang Tua (Ayah / Ibu)</th>
                                    <th scope="col">Alamat & Pekerjaan</th>
                                    <th scope="col" class="text-center" width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siswas as $index => $item): ?>
                                    @php
                                        $fotoSiswaUrl = $item->foto
                                            ? asset('storage/' . $item->foto)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama_siswa) . '&background=dc2626&color=fff&bold=true';
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-item-siswa border-secondary cursor-pointer" type="checkbox" value="{{ $item->id }}">
                                        </td>
                                        <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                                        <td class="text-nowrap">
                                            <span class="bg-success-focus text-success-main px-10 py-4 rounded-pill fw-bold text-xs">
                                                {{ $item->kelas->nama_kelas ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <span class="text-xs d-block text-secondary-light">Induk: <b class="text-primary-light">{{ $item->nomor_induk ?: '-' }}</b></span>
                                            <span class="text-xs d-block text-secondary-light">NISN: <b class="text-info-main">{{ $item->nisn ?: '-' }}</b></span>
                                        </td>
                                        <td class="text-nowrap">
                                            <div class="d-flex align-items-center gap-10">
                                                <img src="{{ $fotoSiswaUrl }}" alt="Foto" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0 border">
                                                <div>
                                                    <span class="fw-bold text-primary-light d-block">{{ $item->nama_siswa }}</span>
                                                    @if ($item->foto)
                                                        <span class="text-xs text-success-600 fw-semibold">✓ Foto Kartu Siap</span>
                                                    @else
                                                        <span class="text-xs text-warning-main">Belum upload foto</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->jenis_kelamin == 'L')
                                                <span class="badge bg-info-focus text-info-main fw-bold">L</span>
                                            @else
                                                <span class="badge bg-warning-focus text-warning-main fw-bold">P</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap text-xs">
                                            <span class="fw-semibold text-primary-light d-block">{{ $item->tempat_lahir ?: '-' }}</span>
                                            <span class="text-secondary-light">
                                                {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap text-xs">
                                            <span class="d-block">Ayah: <b>{{ $item->nama_ayah ?: '-' }}</b></span>
                                            <span class="d-block">Ibu: <b>{{ $item->nama_ibu ?: '-' }}</b></span>
                                        </td>
                                        <td class="text-xs" style="min-width: 180px;">
                                            <span class="d-block text-primary-light">{{ $item->alamat_ortu ?: '-' }}</span>
                                            @if ($item->pekerjaan_ortu)
                                                <span class="text-secondary-light">Pekerjaan: {{ $item->pekerjaan_ortu }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center gap-8 justify-content-center">
                                                <a href="{{ route('presensi.kartuQr', ['siswa_id' => $item->id]) }}"
                                                   target="_blank"
                                                   class="bg-warning-focus text-warning-main bg-hover-warning-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                   title="Cetak / Download Kartu Siswa Ini">
                                                    <i class="ph-bold ph-identification-card text-lg"></i>
                                                </a>

                                                <button type="button"
                                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSiswaModal{{ $item->id }}"
                                                        title="Edit Biodata & Upload Foto">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </button>

                                                @if (!$isGuru)
                                                    <button type="button"
                                                            onclick="hapusSatuData('{{ route('siswa.destroy', $item->id) }}', '{{ addslashes($item->nama_siswa) }}')"
                                                            class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                            title="Hapus Siswa">
                                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if (!$isGuru)
            <!-- ========================================================================= -->
            <!-- TAB 2: BUKU MUTASI MASUK (KHUSUS ADMIN & SUPERADMIN)                      -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade {{ request('tab') == 'masuk' ? 'show active' : '' }}" id="pane-mutasi-masuk" role="tabpanel">
                <div class="card basic-data-table radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h6 class="text-md fw-bold mb-1">Buku Data Siswa Mutasi Masuk MIN 1 Ciamis</h6>
                            <span class="text-xs text-secondary-light">Setiap siswa yang diinput di sini <b>otomatis terdaftar sebagai Siswa Aktif</b> di Kelas Tujuan (Tab 1).</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('mutasi.bulkDestroy') }}" method="POST" id="formBulkMasuk" class="d-inline">
                                @csrf
                                <input type="hidden" name="tab" value="masuk">
                                <div id="hiddenIdsMasuk"></div>
                                <button type="button" id="btnBulkMasuk" onclick="konfirmasiHapusMutasi('masuk')" class="btn btn-danger-600 radius-8 px-14 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                                    <i class="ph-bold ph-trash"></i> Hapus Terpilih (<span id="countSelectedMasuk">0</span>)
                                </button>
                            </form>

                            <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addMutasiMasukModal">
                                <i class="ph-bold ph-plus-circle text-lg"></i> + Input Siswa Mutasi Masuk
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-24">
                        <div class="table-responsive">
                            <table class="table bordered-table mb-0" id="tableMutasiMasuk">
                                <thead>
                                    <tr>
                                        <th width="4%" class="text-center">
                                            <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllMasuk">
                                        </th>
                                        <th width="4%">Urt</th>
                                        <th>Induk & NISN</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">L/P</th>
                                        <th>Kelas Asal</th>
                                        <th>Tempat, Tgl Lahir</th>
                                        <th>Nama Orang Tua</th>
                                        <th>Sekolah Asal</th>
                                        <th>Tgl Masuk</th>
                                        <th>Diterima di Kelas</th>
                                        <th class="text-center" width="8%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mutasiMasuk as $index => $m): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input check-item-masuk border-secondary cursor-pointer" value="{{ $m->id }}">
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-nowrap">
                                                <span class="fw-semibold text-xs d-block">Induk: {{ $m->siswa->nomor_induk ?? '-' }}</span>
                                                <span class="text-xs text-secondary-light">NISN: {{ $m->siswa->nisn ?? '-' }}</span>
                                            </td>
                                            <td class="fw-bold text-primary-light text-nowrap">{{ $m->siswa->nama_siswa ?? '-' }}</td>
                                            <td class="text-center">{{ $m->siswa->jenis_kelamin ?? '-' }}</td>
                                            <td class="text-center fw-semibold">{{ $m->kelas_asal ?? '-' }}</td>
                                            <td class="text-xs text-nowrap">
                                                <span class="d-block fw-semibold">{{ $m->siswa->tempat_lahir ?? '-' }}</span>
                                                <span class="text-secondary-light">{{ $m->siswa->tanggal_lahir ?? '-' }}</span>
                                            </td>
                                            <td class="text-xs text-nowrap">
                                                <span class="d-block">Ayah: <b>{{ $m->siswa->nama_ayah ?? '-' }}</b></span>
                                                <span class="d-block">Ibu: <b>{{ $m->siswa->nama_ibu ?? '-' }}</b></span>
                                            </td>
                                            <td class="text-xs">
                                                <span class="fw-bold d-block">{{ $m->sekolah_asal_tujuan }}</span>
                                                <small class="text-secondary-light">{{ $m->alamat_sekolah ?? '' }}</small>
                                            </td>
                                            <td class="text-xs text-nowrap">
                                                {{ $m->tanggal_mutasi ? \Carbon\Carbon::parse($m->tanggal_mutasi)->translatedFormat('d F Y') : '-' }}
                                            </td>
                                            <td>
                                                <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-bold text-xs">
                                                    {{ $m->kelasTujuan->nama_kelas ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        onclick="hapusSatuData('{{ route('mutasi.destroy', $m->id) }}', 'Catatan Mutasi {{ addslashes($m->siswa->nama_siswa ?? '') }}')"
                                                        class="bg-danger-focus text-danger-600 w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center border-0"
                                                        title="Hapus Catatan Mutasi">
                                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 3: BUKU MUTASI KELUAR (KHUSUS ADMIN & SUPERADMIN)                     -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade {{ request('tab') == 'keluar' ? 'show active' : '' }}" id="pane-mutasi-keluar" role="tabpanel">
                <div class="card basic-data-table radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h6 class="text-md fw-bold mb-1">Buku Data Siswa Mutasi Keluar (Pindah Sekolah)</h6>
                            <span class="text-xs text-secondary-light">Siswa yang dicatat keluar di sini <b>otomatis dinonaktifkan dan hilang dari daftar kelas aktif</b> (Tab 1).</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('mutasi.bulkDestroy') }}" method="POST" id="formBulkKeluar" class="d-inline">
                                @csrf
                                <input type="hidden" name="tab" value="keluar">
                                <div id="hiddenIdsKeluar"></div>
                                <button type="button" id="btnBulkKeluar" onclick="konfirmasiHapusMutasi('keluar')" class="btn btn-danger-600 radius-8 px-14 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                                    <i class="ph-bold ph-trash"></i> Hapus Terpilih (<span id="countSelectedKeluar">0</span>)
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addMutasiKeluarModal">
                                <i class="ph-bold ph-sign-out text-lg"></i> + Catat Siswa Mutasi Keluar
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-24">
                        <div class="table-responsive">
                            <table class="table bordered-table mb-0" id="tableMutasiKeluar">
                                <thead>
                                    <tr>
                                        <th width="4%" class="text-center">
                                            <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllKeluar">
                                        </th>
                                        <th width="5%">No</th>
                                        <th>Induk & NISN</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">L/P</th>
                                        <th>Kelas Terakhir</th>
                                        <th>Sekolah Tujuan</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Alasan Pindah</th>
                                        <th class="text-center" width="8%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mutasiKeluar as $index => $mk): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input check-item-keluar border-secondary cursor-pointer" value="{{ $mk->id }}">
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-nowrap">
                                                <span class="fw-semibold text-xs d-block">Induk: {{ $mk->siswa->nomor_induk ?? '-' }}</span>
                                                <span class="text-xs text-secondary-light">NISN: {{ $mk->siswa->nisn ?? '-' }}</span>
                                            </td>
                                            <td class="fw-bold text-primary-light text-nowrap">{{ $mk->siswa->nama_siswa ?? '-' }}</td>
                                            <td class="text-center">{{ $mk->siswa->jenis_kelamin ?? '-' }}</td>
                                            <td><span class="badge bg-neutral-200 text-primary-light fw-bold">{{ $mk->kelas_asal ?? '-' }}</span></td>
                                            <td class="text-xs">
                                                <span class="fw-bold d-block">{{ $mk->sekolah_asal_tujuan }}</span>
                                                <small class="text-secondary-light">{{ $mk->alamat_sekolah ?? '' }}</small>
                                            </td>
                                            <td class="text-xs text-nowrap">
                                                {{ $mk->tanggal_mutasi ? \Carbon\Carbon::parse($mk->tanggal_mutasi)->translatedFormat('d F Y') : '-' }}
                                            </td>
                                            <td class="text-xs">{{ $mk->alasan_mutasi ?? '-' }}</td>
                                            <td class="text-center">
                                                <button type="button"
                                                        onclick="hapusSatuData('{{ route('mutasi.destroy', $mk->id) }}', 'Catatan Mutasi {{ addslashes($mk->siswa->nama_siswa ?? '') }}')"
                                                        class="bg-danger-focus text-danger-600 w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center border-0"
                                                        title="Hapus Catatan Mutasi">
                                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Form Tersembunyi Hapus Satuan -->
    <form id="formDeleteSingleGlobal" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- KUMPULAN MODAL EDIT SISWA                                                 -->
    <!-- ========================================================================= -->
    <?php foreach ($siswas as $item): ?>
        <div class="modal fade" id="editSiswaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5">Edit Data Siswa: {{ $item->nama_siswa }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('siswa.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Kelas / Rombel <span class="text-danger-600">*</span></label>
                                    @if ($isGuru)
                                        <input type="hidden" name="kelas_id" value="{{ $item->kelas_id }}">
                                        <input type="text" class="form-control radius-8 bg-neutral-100 fw-bold" value="{{ $item->kelas->nama_kelas ?? '-' }}" readonly>
                                    @else
                                        <select name="kelas_id" class="form-select radius-8" required>
                                            <?php foreach ($kelasList as $kls): ?>
                                                <option value="{{ $kls->id }}" {{ $item->kelas_id == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                            <?php endforeach; ?>
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Nomor Induk (NISM)</label>
                                    <input type="text" name="nomor_induk" class="form-control radius-8" value="{{ $item->nomor_induk }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">NISN</label>
                                    <input type="text" name="nisn" class="form-control radius-8" value="{{ $item->nisn }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Lengkap Siswa <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_siswa" class="form-control radius-8" value="{{ $item->nama_siswa }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Jenis Kelamin <span class="text-danger-600">*</span></label>
                                    <select name="jenis_kelamin" class="form-select radius-8" required>
                                        <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                        <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control radius-8" value="{{ $item->tempat_lahir }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control radius-8" value="{{ $item->tanggal_lahir }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control radius-8" value="{{ $item->nama_ayah }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control radius-8" value="{{ $item->nama_ibu }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Alamat Orang Tua</label>
                                    <input type="text" name="alamat_ortu" class="form-control radius-8" value="{{ $item->alamat_ortu }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Pekerjaan Orang Tua</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control radius-8" value="{{ $item->pekerjaan_ortu }}">
                                </div>

                                <div class="col-md-8 pt-12 border-top">
                                    <label class="form-label fw-semibold text-success-600 text-sm mb-6">
                                        <i class="ph-bold ph-camera"></i> Upload Pasfoto Siswa (Untuk Kartu Siswa QR)
                                    </label>
                                    <input type="file" name="foto" class="form-control radius-8 border-success" accept="image/jpeg, image/png, image/jpg, image/webp">
                                    @if ($item->foto)
                                        <div class="mt-8 d-flex align-items-center gap-8">
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="w-40-px h-40-px rounded object-fit-cover border">
                                            <span class="text-xs text-success-600 fw-semibold">✓ Pasfoto saat ini sudah tersimpan</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 pt-12 border-top">
                                    <label class="form-label fw-semibold text-sm mb-6">Status Siswa</label>
                                    @if ($isGuru)
                                        <input type="hidden" name="status" value="{{ $item->status }}">
                                        <input type="text" class="form-control radius-8 bg-neutral-100 fw-bold" value="{{ $item->status }}" readonly>
                                    @else
                                        <select name="status" class="form-select radius-8" required>
                                            <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Mutasi Keluar" {{ $item->status == 'Mutasi Keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                                            <option value="Lulus" {{ $item->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    @if (!$isGuru)
        <!-- ========================================================================= -->
        <!-- MODAL TAMBAH SISWA, IMPORT EXCEL, & MUTASI (KHUSUS ADMIN & SUPERADMIN)    -->
        <!-- ========================================================================= -->
        <div class="modal fade" id="addSiswaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5">Tambah Data Siswa Baru</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Pilih Kelas <span class="text-danger-600">*</span></label>
                                    <select name="kelas_id" class="form-select radius-8" required>
                                        <?php foreach ($kelasList as $kls): ?>
                                            <option value="{{ $kls->id }}" {{ ($selectedKelasId ?? request('kelas_id')) == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Nomor Induk (NISM)</label>
                                    <input type="text" name="nomor_induk" class="form-control radius-8" placeholder="Contoh: 111132070001241001">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">NISN</label>
                                    <input type="text" name="nisn" class="form-control radius-8" placeholder="10 Digit NISN">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Lengkap Siswa <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_siswa" class="form-control radius-8" placeholder="Nama lengkap sesuai Akta/EMIS" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Jenis Kelamin <span class="text-danger-600">*</span></label>
                                    <select name="jenis_kelamin" class="form-select radius-8" required>
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control radius-8" placeholder="Contoh: CIAMIS">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control radius-8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control radius-8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control radius-8">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Alamat Orang Tua</label>
                                    <input type="text" name="alamat_ortu" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Pekerjaan Orang Tua</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control radius-8">
                                </div>
                                <div class="col-md-12 pt-12 border-top">
                                    <label class="form-label fw-semibold text-success-600 text-sm mb-6">
                                        <i class="ph-bold ph-camera"></i> Upload Pasfoto Siswa (Untuk Kartu Siswa QR)
                                    </label>
                                    <input type="file" name="foto" class="form-control radius-8 border-success" accept="image/jpeg, image/png, image/jpg, image/webp">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8">Simpan Data Siswa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importPasteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5">Import Cepat Siswa (Copy-Paste dari Excel)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('siswa.importPaste') }}" method="POST">
                        @csrf
                        <div class="modal-body p-24">
                            <div class="mb-16">
                                <label class="form-label fw-semibold text-sm mb-6">Masukkan ke Kelas / Rombel <span class="text-danger-600">*</span></label>
                                <select name="kelas_id" class="form-select radius-8 fw-bold" required>
                                    <?php foreach ($kelasList as $kls): ?>
                                        <option value="{{ $kls->id }}" {{ ($selectedKelasId ?? request('kelas_id')) == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-8">
                                <label class="form-label fw-semibold text-sm mb-6">Paste Baris Data dari Excel di Bawah Ini <span class="text-danger-600">*</span></label>
                                <textarea name="raw_data" rows="8" class="form-control radius-8 font-monospace text-xs" placeholder="Blok baris siswa dari Excel lalu tekan Ctrl+V di sini..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success-600 radius-8 fw-bold">Import Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addMutasiMasukModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Input Siswa Mutasi Masuk Baru</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('mutasi.storeMasuk') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-24">
                            <h6 class="text-sm fw-bold text-success-600 mb-12 border-bottom pb-8">A. Biodata Siswa & Orang Tua</h6>
                            <div class="row gy-3 mb-20">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Nomor Induk</label>
                                    <input type="text" name="nomor_induk" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">NISN</label>
                                    <input type="text" name="nisn" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Jenis Kelamin (L/P) <span class="text-danger-600">*</span></label>
                                    <select name="jenis_kelamin" class="form-select radius-8" required>
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Lengkap Siswa <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_siswa" class="form-control radius-8" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-sm mb-6">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control radius-8">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-sm mb-6">Tgl Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Pekerjaan Orang Tua</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control radius-8">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Alamat Orang Tua</label>
                                    <input type="text" name="alamat_ortu" class="form-control radius-8">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-success-600 text-sm mb-6">Pasfoto Siswa</label>
                                    <input type="file" name="foto" class="form-control radius-8 border-success" accept="image/*">
                                </div>
                            </div>

                            <h6 class="text-sm fw-bold text-success-600 mb-12 border-bottom pb-8">B. Data Kepindahan (Mutasi Masuk)</h6>
                            <div class="row gy-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-sm mb-6">Kelas Asal</label>
                                    <input type="text" name="kelas_asal" class="form-control radius-8">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold text-sm mb-6">Sekolah Asal <span class="text-danger-600">*</span></label>
                                    <input type="text" name="sekolah_asal_tujuan" class="form-control radius-8" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Alamat Sekolah Asal</label>
                                    <input type="text" name="alamat_sekolah" class="form-control radius-8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tanggal Masuk <span class="text-danger-600">*</span></label>
                                    <input type="date" name="tanggal_mutasi" class="form-control radius-8" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Diterima di Kelas <span class="text-danger-600">*</span></label>
                                    <select name="diterima_di_kelas_id" class="form-select radius-8 fw-bold" required>
                                        <option value="" disabled selected>-- Pilih Rombel Tujuan --</option>
                                        <?php foreach ($kelasList as $kls): ?>
                                            <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan & Masukkan ke Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addMutasiKeluarModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Catat Siswa Mutasi Keluar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('mutasi.storeKeluar') }}" method="POST">
                        @csrf
                        <div class="modal-body p-24">
                            <div class="alert bg-danger-50 text-danger-600 border-danger-200 radius-8 p-12 mb-16 text-xs">
                                <b>Perhatian:</b> Siswa yang dipilih akan otomatis berstatus <b>Mutasi Keluar</b> dan dikeluarkan dari daftar siswa aktif di kelasnya.
                            </div>
                            <div class="mb-16">
                                <label class="form-label fw-semibold text-sm mb-6">Pilih Siswa yang Pindah <span class="text-danger-600">*</span></label>
                                <select name="siswa_id" class="form-select radius-8 fw-bold" required>
                                    <option value="" disabled selected>-- Cari / Pilih Nama Siswa Aktif --</option>
                                    <?php foreach ($siswaAktifSemua as $sa): ?>
                                        <option value="{{ $sa->id }}">{{ $sa->nama_siswa }} ({{ $sa->kelas->nama_kelas ?? '-' }})</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-16">
                                <label class="form-label fw-semibold text-sm mb-6">Tanggal Keluar / Pindah <span class="text-danger-600">*</span></label>
                                <input type="date" name="tanggal_mutasi" class="form-control radius-8" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-16">
                                <label class="form-label fw-semibold text-sm mb-6">Sekolah Tujuan <span class="text-danger-600">*</span></label>
                                <input type="text" name="sekolah_asal_tujuan" class="form-control radius-8" required>
                            </div>
                            <div class="mb-16">
                                <label class="form-label fw-semibold text-sm mb-6">Alamat Sekolah Tujuan</label>
                                <input type="text" name="alamat_sekolah" class="form-control radius-8">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold text-sm mb-6">Alasan Mutasi / Pindah</label>
                                <input type="text" name="alasan_mutasi" class="form-control radius-8">
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger-600 radius-8 fw-bold">Simpan Mutasi Keluar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    let tableSiswa = new DataTable('#dataTableSiswa', { columnDefs: [{ orderable: false, targets: [0, 9] }] });
    if (document.getElementById('tableMutasiMasuk')) {
        new DataTable('#tableMutasiMasuk', { columnDefs: [{ orderable: false, targets: [0, 11] }] });
    }
    if (document.getElementById('tableMutasiKeluar')) {
        new DataTable('#tableMutasiKeluar', { columnDefs: [{ orderable: false, targets: [0, 9] }] });
    }

    const checkAllSiswa  = document.getElementById('checkAllSiswa');
    const btnBulkSiswa   = document.getElementById('btnBulkDeleteSiswa');
    const btnPrintKartu  = document.getElementById('btnBulkPrintKartu');
    const countSiswaSpan = document.getElementById('countSelectedSiswa');
    const countPrintSpan = document.getElementById('countPrintSiswa');

    function updateBulkButtonSiswa() {
        const checkedBoxes = document.querySelectorAll('.check-item-siswa:checked');
        if (countSiswaSpan) countSiswaSpan.innerText = checkedBoxes.length;
        if (countPrintSpan) countPrintSpan.innerText = checkedBoxes.length;

        if (checkedBoxes.length > 0) {
            if (btnBulkSiswa) { btnBulkSiswa.classList.remove('d-none'); btnBulkSiswa.classList.add('d-inline-flex'); }
            if (btnPrintKartu) { btnPrintKartu.classList.remove('d-none'); btnPrintKartu.classList.add('d-inline-flex'); }
        } else {
            if (btnBulkSiswa) { btnBulkSiswa.classList.add('d-none'); btnBulkSiswa.classList.remove('d-inline-flex'); }
            if (btnPrintKartu) { btnPrintKartu.classList.add('d-none'); btnPrintKartu.classList.remove('d-inline-flex'); }
        }
    }

    if (checkAllSiswa) {
        checkAllSiswa.addEventListener('change', function () {
            document.querySelectorAll('.check-item-siswa').forEach(cb => cb.checked = checkAllSiswa.checked);
            updateBulkButtonSiswa();
        });
    }

    const checkAllMasuk  = document.getElementById('checkAllMasuk');
    const checkAllKeluar = document.getElementById('checkAllKeluar');

    function updateBulkMutasi(jenis) {
        const checked = document.querySelectorAll('.check-item-' + jenis + ':checked');
        const btn     = document.getElementById(jenis === 'masuk' ? 'btnBulkMasuk' : 'btnBulkKeluar');
        const span    = document.getElementById(jenis === 'masuk' ? 'countSelectedMasuk' : 'countSelectedKeluar');

        if (!btn || !span) return;
        span.innerText = checked.length;
        if (checked.length > 0) {
            btn.classList.remove('d-none');
            btn.classList.add('d-inline-flex');
        } else {
            btn.classList.add('d-none');
            btn.classList.remove('d-inline-flex');
        }
    }

    if (checkAllMasuk) {
        checkAllMasuk.addEventListener('change', function () {
            document.querySelectorAll('.check-item-masuk').forEach(cb => cb.checked = checkAllMasuk.checked);
            updateBulkMutasi('masuk');
        });
    }

    if (checkAllKeluar) {
        checkAllKeluar.addEventListener('change', function () {
            document.querySelectorAll('.check-item-keluar').forEach(cb => cb.checked = checkAllKeluar.checked);
            updateBulkMutasi('keluar');
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-siswa')) updateBulkButtonSiswa();
        if (e.target.classList.contains('check-item-masuk')) updateBulkMutasi('masuk');
        if (e.target.classList.contains('check-item-keluar')) updateBulkMutasi('keluar');
    });

    function cetakKartuTerpilih() {
        const checkedBoxes = document.querySelectorAll('.check-item-siswa:checked');
        if (checkedBoxes.length === 0) return;
        const ids = Array.from(checkedBoxes).map(cb => cb.value).join(',');
        window.open("{{ route('presensi.kartuQr') }}?ids=" + encodeURIComponent(ids), '_blank');
    }

    function konfirmasiBulkDeleteSiswa() {
        const checkedBoxes = document.querySelectorAll('.check-item-siswa:checked');
        if (checkedBoxes.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checkedBoxes.length + ' Siswa Terpilih?',
            text: 'Data siswa yang dicentang beserta pasfotonya akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Sekaligus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const container = document.getElementById('containerHiddenIdsSiswa');
                container.innerHTML = '';
                checkedBoxes.forEach(cb => {
                    container.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                document.getElementById('formBulkDeleteSiswa').submit();
            }
        });
    }

    function konfirmasiHapusMutasi(jenis) {
        const checked = document.querySelectorAll('.check-item-' + jenis + ':checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' Riwayat Mutasi?',
            text: 'Catatan mutasi yang dicentang akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const box  = document.getElementById(jenis === 'masuk' ? 'hiddenIdsMasuk' : 'hiddenIdsKeluar');
                const form = document.getElementById(jenis === 'masuk' ? 'formBulkMasuk' : 'formBulkKeluar');
                box.innerHTML = '';
                checked.forEach(cb => {
                    box.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                form.submit();
            }
        });
    }

    function hapusSatuData(url, nama) {
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: 'Data ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formDeleteSingleGlobal');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush