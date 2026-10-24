@extends('layouts.admin')

@section('title', 'Buku Induk & Data Siswa')

@section('content')

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Data Induk Siswa MIN 1 Ciamis</h6>
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
                        <span class="text-secondary-light text-sm fw-medium d-block mb-1">Total Siswa (Jml)</span>
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

    <!-- Baris Filter Kelas & Tombol Aksi -->
    <div class="card radius-12 mb-24">
        <div class="card-body p-16 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <!-- Filter Kelas -->
            <form action="{{ route('siswa.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                <label class="text-sm fw-semibold text-primary-light">Filter Kelas:</label>
                <select name="kelas_id" class="form-select form-select-sm radius-8 fw-bold" style="min-width: 210px;" onchange="this.form.submit()">
                    <option value="">-- Tampilkan Semua Kelas --</option>
                    <?php foreach ($kelasList as $kls): ?>
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                            {{ $kls->nama_kelas }}
                        </option>
                    <?php endforeach; ?>
                </select>
                @if (request('kelas_id'))
                    <a href="{{ route('presensi.kartuQr', ['kelas_id' => request('kelas_id')]) }}" target="_blank" class="btn btn-warning-600 btn-sm radius-8 px-12 py-6 d-flex align-items-center gap-1 fw-bold">
                        <i class="ph-bold ph-identification-card text-md"></i> Cetak Kartu 1 Kelas Ini
                    </a>
                @endif
            </form>

            <!-- Tombol Cetak Kartu Terpilih, Hapus Terpilih, Import Excel, & Tambah Siswa -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Tombol Cetak Kartu Siswa yang Dicentang -->
                <button type="button" id="btnBulkPrintKartu" onclick="cetakKartuTerpilih()" class="btn btn-warning-600 radius-8 px-14 py-8 d-none align-items-center gap-2 text-sm fw-bold">
                    <i class="ph-bold ph-identification-card text-lg"></i> Cetak Kartu Terpilih (<span id="countPrintSiswa">0</span>)
                </button>

                <!-- Form Hapus Banyak Siswa -->
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
            </div>
        </div>
    </div>

    <!-- Tabel Utama Data Siswa -->
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
                                        <!-- Tombol Cetak Kartu Satuan (Khusus Siswa Ini Saja) -->
                                        <a href="{{ route('presensi.kartuQr', ['siswa_id' => $item->id]) }}"
                                           target="_blank"
                                           class="bg-warning-focus text-warning-main bg-hover-warning-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                           title="Cetak / Download Kartu Siswa Ini">
                                            <i class="ph-bold ph-identification-card text-lg"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <button type="button"
                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSiswaModal{{ $item->id }}"
                                                title="Edit Biodata & Upload Foto">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button"
                                                onclick="hapusSatuSiswa('{{ route('siswa.destroy', $item->id) }}', '{{ addslashes($item->nama_siswa) }}')"
                                                class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                title="Hapus Siswa">
                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Tersembunyi Hapus Satuan -->
    <form id="formDeleteSingleSiswa" method="POST" style="display: none;">
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
                                    <select name="kelas_id" class="form-select radius-8" required>
                                        <?php foreach ($kelasList as $kls): ?>
                                            <option value="{{ $kls->id }}" {{ $item->kelas_id == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        <?php endforeach; ?>
                                    </select>
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
                                    <select name="status" class="form-select radius-8" required>
                                        <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Mutasi Keluar" {{ $item->status == 'Mutasi Keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                                        <option value="Lulus" {{ $item->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    </select>
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

    <!-- ========================================================================= -->
    <!-- MODAL TAMBAH SISWA BARU                                                   -->
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
                                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
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
                                <small class="text-xs text-secondary-light d-block mt-4">Disarankan pasfoto rasio 3x4, ukuran maks 2MB.</small>
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

    <!-- ========================================================================= -->
    <!-- MODAL SMART COPY-PASTE DARI EXCEL                                         -->
    <!-- ========================================================================= -->
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
                                    <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-8">
                            <label class="form-label fw-semibold text-sm mb-6">Paste Baris Data dari Excel di Bawah Ini <span class="text-danger-600">*</span></label>
                            <textarea name="raw_data" rows="8" class="form-control radius-8 font-monospace text-xs" placeholder="Blok baris siswa dari Excel (No Induk, NISN, Nama, L/P, Tempat Lahir, Tgl Lahir, Ayah, Ibu, Alamat, Pekerjaan) lalu tekan Ctrl+V di sini..." required></textarea>
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

@endsection

@push('scripts')
<script>
    let tableSiswa = new DataTable('#dataTableSiswa', {
        columnDefs: [{ orderable: false, targets: [0, 9] }]
    });

    // Ceklis Hapus Banyak & Cetak Kartu Pilihan Siswa
    const checkAllSiswa  = document.getElementById('checkAllSiswa');
    const btnBulkSiswa   = document.getElementById('btnBulkDeleteSiswa');
    const btnPrintKartu  = document.getElementById('btnBulkPrintKartu');
    const countSiswaSpan = document.getElementById('countSelectedSiswa');
    const countPrintSpan = document.getElementById('countPrintSiswa');

    function updateBulkButtonSiswa() {
        const checkedBoxes = document.querySelectorAll('.check-item-siswa:checked');
        countSiswaSpan.innerText = checkedBoxes.length;
        countPrintSpan.innerText = checkedBoxes.length;

        if (checkedBoxes.length > 0) {
            btnBulkSiswa.classList.remove('d-none');
            btnBulkSiswa.classList.add('d-inline-flex');
            btnPrintKartu.classList.remove('d-none');
            btnPrintKartu.classList.add('d-inline-flex');
        } else {
            btnBulkSiswa.classList.add('d-none');
            btnBulkSiswa.classList.remove('d-inline-flex');
            btnPrintKartu.classList.add('d-none');
            btnPrintKartu.classList.remove('d-inline-flex');
        }
    }

    if (checkAllSiswa) {
        checkAllSiswa.addEventListener('change', function () {
            document.querySelectorAll('.check-item-siswa').forEach(cb => {
                cb.checked = checkAllSiswa.checked;
            });
            updateBulkButtonSiswa();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-siswa')) {
            updateBulkButtonSiswa();
        }
    });

    // Fungsi Cetak Kartu untuk Beberapa Siswa yang Dicentang
    function cetakKartuTerpilih() {
        const checkedBoxes = document.querySelectorAll('.check-item-siswa:checked');
        if (checkedBoxes.length === 0) return;

        const ids = Array.from(checkedBoxes).map(cb => cb.value).join(',');
        const url = "{{ route('presensi.kartuQr') }}?ids=" + encodeURIComponent(ids);
        window.open(url, '_blank');
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

    function hapusSatuSiswa(url, nama) {
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: 'Data siswa ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formDeleteSingleSiswa');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush