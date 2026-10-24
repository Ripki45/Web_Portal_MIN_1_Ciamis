@extends('layouts.admin')

@section('title', 'Data Guru & Tenaga Kependidikan (GTK)')

@section('content')

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Data Guru & Tenaga Kependidikan (GTK)</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Kepegawaian (GTK)</li>
            <li>-</li>
            <li class="fw-medium text-primary-600">Data GTK</li>
        </ul>
    </div>

    <!-- Form Pembungkus Bulk Delete -->
    <form action="{{ route('gtk.bulkDestroy') }}" method="POST" id="formBulkDeleteGtk">
        @csrf

        <div class="card basic-data-table radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h6 class="text-lg fw-semibold mb-0">Daftar Guru & Staf MIN 1 Ciamis</h6>
                    <span class="text-xs text-secondary-light">Tips: Klik tahan & geser tabel ke kanan/kiri menggunakan mouse</span>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Tombol Hapus Banyak (Muncul Otomatis Saat Ceklis Dipilih) -->
                    <button type="button" id="btnBulkDelete" onclick="konfirmasiBulkDelete()" class="btn btn-danger-600 radius-8 px-16 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                        <i class="ph-bold ph-trash text-lg"></i> Hapus Terpilih (<span id="countSelected">0</span>)
                    </button>

                    <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addGtkModal">
                        <i class="ph-bold ph-user-plus text-lg"></i> Tambah Data GTK
                    </button>
                </div>
            </div>

            <div class="card-body p-24">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                        <thead>
                            <tr>
                                <th scope="col" width="4%" class="text-center">
                                    <input class="form-check-input border-secondary cursor-pointer" type="checkbox" id="checkAllGtk">
                                </th>
                                <th scope="col" width="4%">No</th>
                                <th scope="col">Nama Lengkap & Profil</th>
                                <th scope="col">NIP / NUPTK</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Posisi / Penugasan</th>
                                <th scope="col">Tanda Tangan (TTD)</th>
                                <th scope="col">Status Pegawai</th>
                                <th scope="col" class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gtks as $index => $item): ?>
                                @php
                                    $avatarUrl = $item->foto 
                                        ? asset('storage/' . $item->foto) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama_lengkap) . '&background=16a34a&color=fff&bold=true';
                                    
                                    $stPegawai = $item->status_kepegawaian;
                                    $namaJab   = $item->jabatan->nama_jabatan ?? '-';
                                    $isKepala  = stripos($namaJab, 'Kepala Madrasah') !== false || stripos($namaJab, 'Kepala Sekolah') !== false;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <input class="form-check-input check-item-gtk border-secondary cursor-pointer" type="checkbox" name="ids[]" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-12">
                                            <img src="{{ $avatarUrl }}" alt="Foto" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0 border">
                                            <div>
                                                <span class="text-md fw-bold text-primary-light d-block text-nowrap">{{ $item->nama_lengkap }}</span>
                                                <span class="text-xs text-secondary-light text-nowrap">
                                                    {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    @if ($item->no_hp)
                                                        • {{ $item->no_hp }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        <span class="fw-semibold text-sm text-primary-light">{{ $item->nomor_induk ?? '-' }}</span>
                                    </td>
                                    <td class="text-nowrap">
                                        <span class="bg-info-focus text-info-main px-12 py-4 rounded-pill fw-semibold text-xs">
                                            {{ $namaJab }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        @if ($isKepala)
                                            <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-bold text-xs">
                                                ★ Pimpinan Tertinggi
                                            </span>
                                        @elseif ($item->posisi)
                                            <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-bold text-xs">
                                                {{ $item->posisi->nama_posisi }}
                                            </span>
                                        @elseif ($item->kelasWali)
                                            <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-bold text-xs">
                                                Wali {{ $item->kelasWali->nama_kelas }}
                                            </span>
                                        @else
                                            <span class="text-xs text-secondary-light fst-italic">Belum ditentukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->ttd)
                                            <div class="p-4 border radius-8 d-inline-block bg-white" title="Tanda Tangan Tersedia">
                                                <img src="{{ asset('storage/' . $item->ttd) }}" alt="TTD" style="height: 32px; max-width: 90px; object-fit: contain;">
                                            </div>
                                        @else
                                            <span class="bg-neutral-200 text-secondary-light px-10 py-4 rounded-pill text-xs">
                                                Belum Upload
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($stPegawai == 'PNS')
                                            <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-semibold text-xs">PNS</span>
                                        @elseif ($stPegawai == 'PPPK')
                                            <span class="bg-primary-50 text-primary-600 px-12 py-4 rounded-pill fw-semibold text-xs">PPPK</span>
                                        @elseif ($stPegawai == 'Honorer')
                                            <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-semibold text-xs">Honorer</span>
                                        @elseif ($stPegawai == 'Kontrak')
                                            <span class="bg-info-focus text-info-main px-12 py-4 rounded-pill fw-semibold text-xs">Kontrak</span>
                                        @elseif ($stPegawai == 'Magang')
                                            <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-semibold text-xs">Magang</span>
                                        @else
                                            <span class="bg-neutral-200 text-secondary-light px-12 py-4 rounded-pill fw-semibold text-xs">{{ $stPegawai ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-8 justify-content-center">
                                            <button type="button" 
                                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editGtkModal{{ $item->id }}"
                                                    title="Edit Data & Penugasan">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </button>

                                            <button type="button" 
                                                    onclick="hapusSatuGtk('{{ route('gtk.destroy', $item->id) }}', '{{ addslashes($item->nama_lengkap) }}')"
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Hapus">
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
    </form>

    <!-- Form Tersembunyi untuk Hapus 1 Data -->
    <form id="formDeleteSingleGtk" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- KUMPULAN MODAL EDIT GTK                                                   -->
    <!-- ========================================================================= -->
    <?php foreach ($gtks as $item): ?>
        @php
            $statusOptions = ['PNS', 'PPPK', 'Honorer', 'Magang', 'Kontrak', 'Lainnya'];
            $namaJabEdit   = $item->jabatan->nama_jabatan ?? '';
            $isKepalaEdit  = stripos($namaJabEdit, 'Kepala Madrasah') !== false || stripos($namaJabEdit, 'Kepala Sekolah') !== false;
        @endphp
        <div class="modal fade" id="editGtkModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                        <h1 class="modal-title fs-5">Edit Data GTK: {{ $item->nama_lengkap }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('gtk.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Nama Lengkap & Gelar <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" class="form-control radius-8" value="{{ $item->nama_lengkap }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        NIP / NUPTK / Nomor Induk
                                    </label>
                                    <input type="text" name="nomor_induk" class="form-control radius-8" value="{{ $item->nomor_induk }}" placeholder="Contoh: 197206051997032003">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jabatan <span class="text-danger-600">*</span>
                                    </label>
                                    <select name="jabatan_id" class="form-select radius-8 select-jabatan" data-target="#selectPosisiEdit{{ $item->id }}" required>
                                        <?php foreach ($jabatans as $jab): ?>
                                            <option value="{{ $jab->id }}" data-nama="{{ $jab->nama_jabatan }}" {{ $item->jabatan_id == $jab->id ? 'selected' : '' }}>
                                                {{ $jab->nama_jabatan }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Posisi / Penugasan (Dari Master Posisi)
                                    </label>
                                    <select name="posisi_id" id="selectPosisiEdit{{ $item->id }}" class="form-select radius-8" {{ $isKepalaEdit ? 'disabled' : '' }}>
                                        <option value="">-- {{ $isKepalaEdit ? 'Tidak Perlu Posisi (Jabatan Tertinggi)' : 'Pilih Posisi / Penugasan' }} --</option>
                                        <?php foreach ($posisis as $pos): ?>
                                            <option value="{{ $pos->id }}" data-jabatan="{{ $pos->jabatan_id }}" {{ $item->posisi_id == $pos->id ? 'selected' : '' }}>
                                                {{ $pos->nama_posisi }} ({{ $pos->jabatan->nama_jabatan ?? '' }})
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jenis Kelamin <span class="text-danger-600">*</span>
                                    </label>
                                    <select name="jenis_kelamin" class="form-select radius-8" required>
                                        <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                        <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Status Kepegawaian <span class="text-danger-600">*</span>
                                    </label>
                                    <select name="status_kepegawaian" class="form-select radius-8" required>
                                        <?php foreach ($statusOptions as $st): ?>
                                            <option value="{{ $st }}" {{ $item->status_kepegawaian == $st ? 'selected' : '' }}>{{ $st }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        No. HP / WhatsApp
                                    </label>
                                    <input type="text" name="no_hp" class="form-control radius-8" value="{{ $item->no_hp }}" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Alamat Lengkap
                                    </label>
                                    <input type="text" name="alamat" class="form-control radius-8" value="{{ $item->alamat }}" placeholder="Alamat domisili...">
                                </div>

                                <div class="col-md-6 pt-12 border-top">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Ganti Foto Profil <span class="text-secondary-light fw-normal">(Opsional)</span>
                                    </label>
                                    <input type="file" name="foto" class="form-control radius-8" accept="image/*">
                                    @if ($item->foto)
                                        <div class="mt-8 d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="w-40-px h-40-px rounded-circle object-fit-cover border">
                                            <small class="text-xs text-secondary-light">Foto saat ini</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 pt-12 border-top">
                                    <label class="form-label fw-semibold text-success-600 text-sm mb-8">
                                        <i class="ph-bold ph-signature"></i> Upload Scan Tanda Tangan (PNG)
                                    </label>
                                    <input type="file" name="ttd" class="form-control radius-8 border-success" accept="image/png, image/jpeg">
                                    @if ($item->ttd)
                                        <div class="mt-8 p-6 border radius-8 d-inline-flex align-items-center gap-8 bg-white">
                                            <img src="{{ asset('storage/' . $item->ttd) }}" alt="TTD" style="height: 38px; object-fit: contain;">
                                            <span class="text-xs text-success-600 fw-semibold">✓ TTD Aktif</span>
                                        </div>
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

    <!-- ========================================================================= -->
    <!-- MODAL TAMBAH GTK BARU                                                     -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="addGtkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5">Tambah Data GTK Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gtk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Nama Lengkap & Gelar <span class="text-danger-600">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" class="form-control radius-8" placeholder="Contoh: Ripki Ramdani, S.Pd" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    NIP / NUPTK / Nomor Induk
                                </label>
                                <input type="text" name="nomor_induk" class="form-control radius-8" placeholder="Masukkan NIP atau NUPTK">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Pilih Jabatan <span class="text-danger-600">*</span>
                                </label>
                                <select name="jabatan_id" class="form-select radius-8 select-jabatan" data-target="#selectPosisiAdd" required>
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    <?php foreach ($jabatans as $jab): ?>
                                        <option value="{{ $jab->id }}" data-nama="{{ $jab->nama_jabatan }}">{{ $jab->nama_jabatan }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Posisi / Penugasan (Sesuai Jabatan)
                                </label>
                                <select name="posisi_id" id="selectPosisiAdd" class="form-select radius-8">
                                    <option value="">-- Pilih Posisi / Penugasan --</option>
                                    <?php foreach ($posisis as $pos): ?>
                                        <option value="{{ $pos->id }}" data-jabatan="{{ $pos->jabatan_id }}">
                                            {{ $pos->nama_posisi }} ({{ $pos->jabatan->nama_jabatan ?? '' }})
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Jenis Kelamin <span class="text-danger-600">*</span>
                                </label>
                                <select name="jenis_kelamin" class="form-select radius-8" required>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Status Kepegawaian <span class="text-danger-600">*</span>
                                </label>
                                <select name="status_kepegawaian" class="form-select radius-8" required>
                                    <option value="" disabled selected>-- Pilih Status --</option>
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="Honorer">Honorer</option>
                                    <option value="Magang">Magang</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    No. HP / WhatsApp
                                </label>
                                <input type="text" name="no_hp" class="form-control radius-8" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Alamat Lengkap
                                </label>
                                <input type="text" name="alamat" class="form-control radius-8" placeholder="Alamat domisili...">
                            </div>

                            <div class="col-md-6 pt-12 border-top">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Foto Profil <span class="text-secondary-light fw-normal">(Opsional)</span>
                                </label>
                                <input type="file" name="foto" class="form-control radius-8" accept="image/*">
                            </div>

                            <div class="col-md-6 pt-12 border-top">
                                <label class="form-label fw-semibold text-success-600 text-sm mb-8">
                                    <i class="ph-bold ph-signature"></i> Scan Tanda Tangan <span class="text-secondary-light fw-normal">(PNG Transparan)</span>
                                </label>
                                <input type="file" name="ttd" class="form-control radius-8 border-success" accept="image/png, image/jpeg">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8">Simpan Data GTK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let table = new DataTable('#dataTable', {
        columnDefs: [{ orderable: false, targets: [0, 8] }]
    });

    // 1. Logika Ceklis Semua & Hitung Jumlah Terpilih
    const checkAll = document.getElementById('checkAllGtk');
    const btnBulk  = document.getElementById('btnBulkDelete');
    const countSpan= document.getElementById('countSelected');

    function updateBulkButton() {
        const checkedCount = document.querySelectorAll('.check-item-gtk:checked').length;
        countSpan.innerText = checkedCount;
        if (checkedCount > 0) {
            btnBulk.classList.remove('d-none');
            btnBulk.classList.add('d-flex');
        } else {
            btnBulk.classList.add('d-none');
            btnBulk.classList.remove('d-flex');
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.check-item-gtk').forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateBulkButton();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-gtk')) {
            updateBulkButton();
        }
    });

    function konfirmasiBulkDelete() {
        const jml = document.querySelectorAll('.check-item-gtk:checked').length;
        Swal.fire({
            title: 'Hapus ' + jml + ' Data GTK?',
            text: 'Data GTK yang dicentang beserta foto/TTD-nya akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Sekaligus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formBulkDeleteGtk').submit();
            }
        });
    }

    function hapusSatuGtk(url, nama) {
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formDeleteSingleGtk');
                form.action = url;
                form.submit();
            }
        });
    }

    // 2. Logika Pintar: Kunci Dropdown Posisi Jika Jabatan = Kepala Madrasah & Filter Sesuai Jabatan
    document.querySelectorAll('.select-jabatan').forEach(selectJab => {
        selectJab.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const namaJabatan    = (selectedOption.getAttribute('data-nama') || '').toLowerCase();
            const jabatanId      = this.value;
            const targetSelect   = document.querySelector(this.getAttribute('data-target'));

            if (!targetSelect) return;

            if (namaJabatan.includes('kepala madrasah') || namaJabatan.includes('kepala sekolah')) {
                targetSelect.value = '';
                targetSelect.disabled = true;
                targetSelect.options[0].text = '-- Tidak Perlu Posisi (Jabatan Tertinggi) --';
            } else {
                targetSelect.disabled = false;
                targetSelect.options[0].text = '-- Pilih Posisi / Penugasan --';
                Array.from(targetSelect.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    const optJabId = opt.getAttribute('data-jabatan');
                    opt.style.display = (!optJabId || optJabId === jabatanId) ? '' : 'none';
                });
            }
        });
    });
</script>
@endpush