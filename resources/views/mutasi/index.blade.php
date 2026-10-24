@extends('layouts.admin')

@section('title', 'Kenaikan Kelas & Mutasi Siswa')

@section('content')

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Kenaikan Kelas & Mutasi Siswa</h6>
            @if ($tahunAktif)
                <span class="text-sm text-secondary-light">
                    Tahun Pelajaran Aktif: <b class="text-success-600">{{ $tahunAktif->tahun_ajaran }} ({{ $tahunAktif->semester }})</b>
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
            <li class="fw-medium text-primary-600">Kenaikan & Mutasi</li>
        </ul>
    </div>

    <!-- CARD UTAMA DENGAN 3 TAB -->
    <div class="card radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <ul class="nav border-gradient-tab nav-pills d-inline-flex gap-2" id="mutasiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center gap-2 px-20" id="kenaikan-tab" data-bs-toggle="pill" data-bs-target="#tab-kenaikan" type="button" role="tab">
                        <i class="ph-bold ph-trend-up text-lg"></i> 1. Kenaikan Kelas / Pindah Rombel
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2 px-20" id="masuk-tab" data-bs-toggle="pill" data-bs-target="#tab-masuk" type="button" role="tab">
                        <i class="ph-bold ph-sign-in text-lg"></i> 2. Data Mutasi Masuk ({{ $mutasiMasuk->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2 px-20" id="keluar-tab" data-bs-toggle="pill" data-bs-target="#tab-keluar" type="button" role="tab">
                        <i class="ph-bold ph-sign-out text-lg"></i> 3. Data Mutasi Keluar ({{ $mutasiKeluar->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-24">
            <div class="tab-content" id="mutasiTabContent">

                <!-- ========================================================= -->
                <!-- TAB 1: KENAIKAN KELAS & KELULUSAN MASSAL (TANPA INPUT ULANG) -->
                <!-- ========================================================= -->
                <div class="tab-pane fade show active" id="tab-kenaikan" role="tabpanel">
                    <div class="alert bg-success-50 text-success-600 border-success-200 radius-8 p-16 mb-24 text-sm">
                        <b><i class="ph-bold ph-info"></i> Fitur Kenaikan Kelas Otomatis:</b> Pilih <b>Kelas Asal</b> di bawah ini, centang siswa yang naik kelas, lalu pilih <b>Kelas Tujuan</b> (misal dari Kelas 3-B ke Kelas 4-B). Data siswa otomatis berpindah tanpa perlu input atau import ulang!
                    </div>

                    <!-- Langkah 1: Pilih Kelas Asal -->
                    <form action="{{ route('mutasi.index') }}" method="GET" class="row gy-3 align-items-end mb-24 pb-24 border-bottom">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pilih Kelas Asal yang Akan Dinaikkan/Dipindahkan:</label>
                            <select name="asal_kelas_id" class="form-select radius-8" onchange="this.form.submit()">
                                <option value="">-- Pilih Kelas Asal --</option>
                                <?php foreach ($kelasList as $kls): ?>
                                    <option value="{{ $kls->id }}" {{ request('asal_kelas_id') == $kls->id ? 'selected' : '' }}>
                                        {{ $kls->nama_kelas }} (Tingkat {{ $kls->tingkat }})
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>

                    <!-- Langkah 2: Tabel Checklist Siswa & Pilihan Kelas Tujuan -->
                    @if (request()->filled('asal_kelas_id'))
                        <form action="{{ route('mutasi.kenaikan') }}" method="POST">
                            @csrf
                            <div class="row gy-3 align-items-end mb-20 p-16 radius-12 bg-neutral-50 border">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pilih Jenis Proses <span class="text-danger-600">*</span></label>
                                    <select name="jenis_aksi" id="jenis_aksi" class="form-select radius-8" required>
                                        <option value="naik_kelas">Naik / Pindah ke Kelas Lain</option>
                                        <option value="lulus">Luluskan Siswa (Khusus Kelas 6)</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="box_tujuan_kelas">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pindahkan ke Kelas Tujuan <span class="text-danger-600">*</span></label>
                                    <select name="tujuan_kelas_id" class="form-select radius-8">
                                        <option value="">-- Pilih Kelas Tujuan --</option>
                                        <?php foreach ($kelasList as $kls): ?>
                                            @if ($kls->id != request('asal_kelas_id'))
                                                <option value="{{ $kls->id }}">{{ $kls->nama_kelas }} (Tingkat {{ $kls->tingkat }})</option>
                                            @endif
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-11 w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="ph-bold ph-paper-plane-Right"></i> Proses Siswa Terpilih
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="6%" class="text-center">
                                                <input type="checkbox" id="checkAllSiswa" class="form-check-input cursor-pointer" checked>
                                            </th>
                                            <th width="6%">No</th>
                                            <th>No. Induk & NISN</th>
                                            <th>Nama Siswa</th>
                                            <th>L/P</th>
                                            <th>Kelas Saat Ini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($siswaKenaikan->isEmpty())
                                            <tr>
                                                <td colspan="6" class="text-center py-24 text-secondary-light">
                                                    Tidak ada siswa aktif di kelas ini.
                                                </td>
                                            </tr>
                                        @else
                                            <?php foreach ($siswaKenaikan as $idx => $s): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" class="form-check-input check-item cursor-pointer" checked>
                                                    </td>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>
                                                        <span class="fw-medium text-sm">{{ $s->nomor_induk ?? '-' }}</span>
                                                        <small class="d-block text-secondary-light">NISN: {{ $s->nisn ?? '-' }}</small>
                                                    </td>
                                                    <td class="fw-bold text-primary-light">{{ $s->nama_siswa }}</td>
                                                    <td>{{ $s->jenis_kelamin }}</td>
                                                    <td>
                                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-xs">
                                                            {{ $s->kelas->nama_kelas ?? '-' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- ========================================================= -->
                <!-- TAB 2: DATA SISWA MUTASI MASUK (SESUAI FORMAT EXCEL)      -->
                <!-- ========================================================= -->
                <div class="tab-pane fade" id="tab-masuk" role="tabpanel">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-20">
                        <div>
                            <h6 class="text-md fw-bold mb-1">Buku Data Siswa Mutasi Masuk MIN 1 Ciamis</h6>
                            <span class="text-xs text-secondary-light">Siswa yang diinput di sini otomatis masuk ke Daftar Siswa di Kelas Tujuan.</span>
                        </div>
                        <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addMutasiMasukModal">
                            <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Mutasi Masuk
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="tableMutasiMasuk">
                            <thead>
                                <tr>
                                    <th width="4%">Urt</th>
                                    <th>Induk & NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>L/P</th>
                                    <th>Kelas Asal</th>
                                    <th>Tempat, Tgl Lahir</th>
                                    <th>Nama Orang Tua (Ayah/Ibu)</th>
                                    <th>Sekolah Asal</th>
                                    <th>Tgl Masuk</th>
                                    <th>Diterima di Kelas</th>
                                    <th class="text-center" width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mutasiMasuk as $index => $m): ?>
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold text-sm d-block">{{ $m->siswa->nomor_induk ?? '-' }}</span>
                                            <span class="text-xs text-secondary-light">NISN: {{ $m->siswa->nisn ?? '-' }}</span>
                                        </td>
                                        <td class="fw-bold text-primary-light">{{ $m->siswa->nama_siswa ?? '-' }}</td>
                                        <td>{{ $m->siswa->jenis_kelamin ?? '-' }}</td>
                                        <td class="text-center fw-semibold">{{ $m->kelas_asal ?? '-' }}</td>
                                        <td>
                                            <span class="text-sm d-block">{{ $m->siswa->tempat_lahir ?? '-' }}</span>
                                            <span class="text-xs text-secondary-light">{{ $m->siswa->tanggal_lahir ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs d-block"><b>Ayah:</b> {{ $m->siswa->nama_ayah ?? '-' }}</span>
                                            <span class="text-xs d-block"><b>Ibu:</b> {{ $m->siswa->nama_ibu ?? '-' }}</span>
                                            <span class="text-xs text-secondary-light d-block"><b>Pekerjaan:</b> {{ $m->siswa->pekerjaan_ortu ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-sm d-block">{{ $m->sekolah_asal_tujuan }}</span>
                                            <small class="text-xs text-secondary-light">{{ $m->alamat_sekolah ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ $m->tanggal_mutasi ? \Carbon\Carbon::parse($m->tanggal_mutasi)->translatedFormat('d F Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-xs">
                                                {{ $m->kelasTujuan->nama_kelas ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('mutasi.destroy', $m->id) }}" method="POST" class="form-delete" data-nama="Catatan Mutasi {{ $m->siswa->nama_siswa ?? '' }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" title="Hapus Catatan Mutasi">
                                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- TAB 3: DATA SISWA MUTASI KELUAR                           -->
                <!-- ========================================================= -->
                <div class="tab-pane fade" id="tab-keluar" role="tabpanel">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-20">
                        <div>
                            <h6 class="text-md fw-bold mb-1">Buku Data Siswa Mutasi Keluar</h6>
                            <span class="text-xs text-secondary-light">Daftar siswa yang pindah dari MIN 1 Ciamis ke sekolah lain.</span>
                        </div>
                        <button type="button" class="btn btn-danger-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addMutasiKeluarModal">
                            <i class="ph-bold ph-sign-out text-lg"></i> Catat Siswa Mutasi Keluar
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="tableMutasiKeluar">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Induk & NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>L/P</th>
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
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold text-sm d-block">{{ $mk->siswa->nomor_induk ?? '-' }}</span>
                                            <span class="text-xs text-secondary-light">NISN: {{ $mk->siswa->nisn ?? '-' }}</span>
                                        </td>
                                        <td class="fw-bold text-primary-light">{{ $mk->siswa->nama_siswa ?? '-' }}</td>
                                        <td>{{ $mk->siswa->jenis_kelamin ?? '-' }}</td>
                                        <td>{{ $mk->kelas_asal ?? '-' }}</td>
                                        <td>
                                            <span class="fw-semibold text-sm d-block">{{ $mk->sekolah_asal_tujuan }}</span>
                                            <small class="text-xs text-secondary-light">{{ $mk->alamat_sekolah ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ $mk->tanggal_mutasi ? \Carbon\Carbon::parse($mk->tanggal_mutasi)->translatedFormat('d F Y') : '-' }}
                                        </td>
                                        <td>{{ $mk->alasan_mutasi ?? '-' }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('mutasi.destroy', $mk->id) }}" method="POST" class="form-delete" data-nama="Catatan Mutasi {{ $mk->siswa->nama_siswa ?? '' }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                </button>
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

    <!-- MODAL TAMBAH MUTASI MASUK (SESUAI KOLOM EXCEL) -->
    <div class="modal fade" id="addMutasiMasukModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5">Input Siswa Mutasi Masuk Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mutasi.storeMasuk') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <h6 class="text-sm fw-bold text-success-600 mb-12 border-bottom pb-8">A. Biodata Siswa & Orang Tua</h6>
                        <div class="row gy-3 mb-20">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-sm mb-6">Nomor Induk</label>
                                <input type="text" name="nomor_induk" class="form-control radius-8" placeholder="111132070001...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-sm mb-6">NISN</label>
                                <input type="text" name="nisn" class="form-control radius-8" placeholder="014773857">
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
                                <input type="text" name="nama_siswa" class="form-control radius-8" placeholder="Contoh: Ismatul Azizzah" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-sm mb-6">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control radius-8" placeholder="Ciamis">
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
                                <input type="text" name="pekerjaan_ortu" class="form-control radius-8" placeholder="Pedagang / Buruh / Wiraswasta">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-sm mb-6">Alamat Orang Tua</label>
                                <input type="text" name="alamat_ortu" class="form-control radius-8" placeholder="Contoh: Dusun Bunisakti RT 048 RW 020 Desa Maparah">
                            </div>
                        </div>

                        <h6 class="text-sm fw-bold text-success-600 mb-12 border-bottom pb-8">B. Data Kepindahan (Mutasi Masuk)</h6>
                        <div class="row gy-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-sm mb-6">Kelas Asal</label>
                                <input type="text" name="kelas_asal" class="form-control radius-8" placeholder="Contoh: 6 atau 2">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-sm mb-6">Sekolah Asal <span class="text-danger-600">*</span></label>
                                <input type="text" name="sekolah_asal_tujuan" class="form-control radius-8" placeholder="Contoh: MIS DARUSSALAM" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-sm mb-6">Alamat Sekolah Asal</label>
                                <input type="text" name="alamat_sekolah" class="form-control radius-8" placeholder="Opsional">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Tanggal Masuk <span class="text-danger-600">*</span></label>
                                <input type="date" name="tanggal_mutasi" class="form-control radius-8" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Diterima di Kelas <span class="text-danger-600">*</span></label>
                                <select name="diterima_di_kelas_id" class="form-select radius-8" required>
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
                        <button type="submit" class="btn btn-primary-600 radius-8">Simpan Mutasi Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH MUTASI KELUAR -->
    <div class="modal fade" id="addMutasiKeluarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5">Catat Siswa Mutasi Keluar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mutasi.storeKeluar') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="mb-16">
                            <label class="form-label fw-semibold text-sm mb-6">Pilih Siswa yang Pindah <span class="text-danger-600">*</span></label>
                            <select name="siswa_id" class="form-select radius-8" required>
                                <option value="" disabled selected>-- Cari / Pilih Nama Siswa --</option>
                                <?php foreach ($siswaAktif as $sa): ?>
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
                            <input type="text" name="sekolah_asal_tujuan" class="form-control radius-8" placeholder="Nama MI / SD Tujuan" required>
                        </div>
                        <div class="mb-16">
                            <label class="form-label fw-semibold text-sm mb-6">Alamat Sekolah Tujuan</label>
                            <input type="text" name="alamat_sekolah" class="form-control radius-8" placeholder="Opsional">
                        </div>
                        <div class="mb-16">
                            <label class="form-label fw-semibold text-sm mb-6">Alasan Mutasi / Pindah</label>
                            <input type="text" name="alasan_mutasi" class="form-control radius-8" placeholder="Contoh: Mengikuti perpindahan orang tua">
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger-600 radius-8">Simpan Mutasi Keluar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let tableMasuk = new DataTable('#tableMutasiMasuk');
    let tableKeluar = new DataTable('#tableMutasiKeluar');

    // Script Check All untuk Kenaikan Kelas
    const checkAll = document.getElementById('checkAllSiswa');
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.check-item').forEach(cb => cb.checked = this.checked);
        });
    }

    // Sembunyikan dropdown Kelas Tujuan jika memilih aksi "Luluskan Siswa"
    const jenisAksi = document.getElementById('jenis_aksi');
    const boxTujuan = document.getElementById('box_tujuan_kelas');
    if (jenisAksi && boxTujuan) {
        jenisAksi.addEventListener('change', function() {
            boxTujuan.style.display = (this.value === 'lulus') ? 'none' : 'block';
        });
    }
</script>
@endpush