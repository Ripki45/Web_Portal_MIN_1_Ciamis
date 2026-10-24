@extends('layouts.admin')

@section('title', 'Data Induk Siswa')

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

    <!-- TABEL DATA SISWA & FILTER KELAS -->
    <div class="card basic-data-table radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between flex-wrap gap-3">
            
            <!-- Filter Kelas -->
            <form action="{{ route('siswa.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <label class="text-sm fw-semibold text-primary-light text-nowrap">Filter Kelas:</label>
                <select name="kelas_id" class="form-select form-select-sm radius-8 w-auto" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelasList as $kls): ?>
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                            {{ $kls->nama_kelas }}
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <!-- Tombol Aksi: Import Copy-Paste Excel & Tambah Manual -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-success-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#importPasteModal">
                    <i class="ph-bold ph-file-xls text-lg"></i> Import Copy-Paste Excel
                </button>
                <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addSiswaModal">
                    <i class="ph-bold ph-user-plus text-lg"></i> Tambah Siswa
                </button>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='25'>
                    <thead>
                        <tr>
                            <th scope="col" width="4%">Urt</th>
                            <th scope="col">No. Induk & NISN</th>
                            <th scope="col">Nama Siswa</th>
                            <th scope="col">L/P</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Tempat, Tgl Lahir</th>
                            <th scope="col">Orang Tua (Ayah / Ibu)</th>
                            <th scope="col" class="text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswas as $index => $item): ?>
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold text-primary-light d-block text-sm">{{ $item->nomor_induk ?? '-' }}</span>
                                    <span class="text-xs text-secondary-light">NISN: {{ $item->nisn ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary-light">{{ $item->nama_siswa }}</span>
                                </td>
                                <td>
                                    @if ($item->jenis_kelamin == 'L')
                                        <span class="bg-info-focus text-info-main px-10 py-2 rounded-pill fw-semibold text-xs">L</span>
                                    @else
                                        <span class="bg-warning-focus text-warning-main px-10 py-2 rounded-pill fw-semibold text-xs">P</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-xs">
                                        {{ $item->kelas->nama_kelas ?? 'Belum Masuk Kelas' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm text-primary-light d-block">{{ $item->tempat_lahir ?? '-' }}</span>
                                    <span class="text-xs text-secondary-light">
                                        {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm fw-medium text-primary-light d-block">Ayah: {{ $item->nama_ayah ?? '-' }}</span>
                                    <span class="text-xs text-secondary-light d-block">Ibu: {{ $item->nama_ibu ?? '-' }}</span>
                                    @if ($item->alamat_ortu)
                                        <small class="text-xs text-neutral-500 d-block text-truncate" style="max-width: 180px;" title="{{ $item->alamat_ortu }}">
                                            <i class="ph ph-map-pin"></i> {{ $item->alamat_ortu }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-8 justify-content-center">
                                        <!-- Tombol Edit -->
                                        <button type="button" 
                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editSiswaModal{{ $item->id }}"
                                                title="Edit Biodata">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="form-delete" data-nama="{{ $item->nama_siswa }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Hapus">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- KUMPULAN MODAL EDIT SISWA -->
    <?php foreach ($siswas as $item): ?>
        <div class="modal fade" id="editSiswaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                        <h1 class="modal-title fs-5">Edit Data Siswa: {{ $item->nama_siswa }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('siswa.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Induk</label>
                                    <input type="text" name="nomor_induk" class="form-control radius-8" value="{{ $item->nomor_induk }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">NISN</label>
                                    <input type="text" name="nisn" class="form-control radius-8" value="{{ $item->nisn }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Kelas Saat Ini <span class="text-danger-600">*</span></label>
                                    <select name="kelas_id" class="form-select radius-8" required>
                                        <?php foreach ($kelasList as $kls): ?>
                                            <option value="{{ $kls->id }}" {{ $item->kelas_id == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap Siswa <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_siswa" class="form-control radius-8" value="{{ $item->nama_siswa }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin (L/P) <span class="text-danger-600">*</span></label>
                                    <select name="jenis_kelamin" class="form-select radius-8" required>
                                        <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                        <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control radius-8" value="{{ $item->tempat_lahir }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control radius-8" value="{{ $item->tanggal_lahir }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control radius-8" value="{{ $item->nama_ayah }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control radius-8" value="{{ $item->nama_ibu }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat Orang Tua</label>
                                    <input type="text" name="alamat_ortu" class="form-control radius-8" value="{{ $item->alamat_ortu }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pekerjaan Ortu</label>
                                    <input type="text" name="pekerjaan_ortu" class="form-control radius-8" value="{{ $item->pekerjaan_ortu }}">
                                </div>
                                <input type="hidden" name="status" value="{{ $item->status }}">
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

    <!-- MODAL 1: TAMBAH SISWA MANUAL -->
    <div class="modal fade" id="addSiswaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5">Tambah Data Siswa Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Induk</label>
                                <input type="text" name="nomor_induk" class="form-control radius-8" placeholder="111132070001...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">NISN</label>
                                <input type="text" name="nisn" class="form-control radius-8" placeholder="10 Digit NISN">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pilih Kelas <span class="text-danger-600">*</span></label>
                                <select name="kelas_id" class="form-select radius-8" required>
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    <?php foreach ($kelasList as $kls): ?>
                                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap Siswa <span class="text-danger-600">*</span></label>
                                <input type="text" name="nama_siswa" class="form-control radius-8" placeholder="Sesuai Ijazah / Akta Kelahiran" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin <span class="text-danger-600">*</span></label>
                                <select name="jenis_kelamin" class="form-select radius-8" required>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control radius-8" placeholder="Contoh: CIAMIS">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control radius-8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control radius-8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control radius-8">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat Orang Tua</label>
                                <input type="text" name="alamat_ortu" class="form-control radius-8" placeholder="Contoh: DUSUN SUKAJAYA RT.031 RW.013">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Pekerjaan Ortu</label>
                                <input type="text" name="pekerjaan_ortu" class="form-control radius-8" placeholder="Contoh: ASN / Wiraswasta">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8">Simpan Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: SMART IMPORT COPY-PASTE DARI EXCEL -->
    <div class="modal fade" id="importPasteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5">Import Cepat (Copy-Paste dari Excel / Spreadsheet)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.importPaste') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="alert bg-success-50 text-success-600 border-success-200 radius-8 p-16 mb-20 text-sm">
                            <b>Cara Pakai Super Cepat:</b><br>
                            1. Pilih <b>Kelas Tujuan</b> di bawah ini (misal: Kelas 5-D).<br>
                            2. Buka file Excel/Google Sheets <i>Data Murid Tahun 2026-2027</i>, blok baris data siswanya saja dari <b>Kolom A (URT) sampai Kolom K (Pekerjaan Ortu)</b> tanpa judul kolomnya, lalu tekan <b>Ctrl + C</b>.<br>
                            3. Klik kotak di bawah ini dan tekan <b>Ctrl + V</b>, lalu klik <b>Import Sekarang</b>!
                        </div>

                        <div class="mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Masukkan ke Kelas Mana? <span class="text-danger-600">*</span></label>
                            <select name="kelas_id" class="form-select radius-8" required>
                                <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                                <?php foreach ($kelasList as $kls): ?>
                                    <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-12">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Paste Baris Data Excel di Sini <span class="text-danger-600">*</span></label>
                            <textarea name="raw_data" rows="8" class="form-control radius-8 font-monospace text-xs" placeholder="Contoh hasil paste:&#10;1	111132070001221010	3154121401	ANIPAN	L	Ciamis	28 November 2015	JAJA WIRJA	ENTIN	DUSUN SUKAJAYA RT.031 RW.013	" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8 d-flex align-items-center gap-2">
                            <i class="ph-bold ph-upload-simple"></i> Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let table = new DataTable('#dataTable');
</script>
@endpush