@extends('layouts.admin')

@section('title', 'Master Mata Pelajaran - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Master Mata Pelajaran & Kurikulum MIN 1 Ciamis</h6>
            <span class="text-xs text-secondary-light">Kelola kamus mata pelajaran madrasah (Penugasan guru & kelas diatur di menu Manajemen Kelas)</span>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Tombol Hapus Banyak Mapel (Muncul Saat Ceklis) -->
            <form action="{{ route('mapel.bulkDestroy') }}" method="POST" id="formBulkDeleteMapel" class="d-inline">
                @csrf
                <div id="containerHiddenIdsMapel"></div>
                <button type="button" id="btnBulkDeleteMasterMapel" onclick="konfirmasiHapusBanyakMapel()" class="btn btn-danger-600 radius-8 px-14 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                    <i class="ph-bold ph-trash text-lg"></i> Hapus Terpilih (<span id="countSelectedMasterMapel">0</span>)
                </button>
            </form>

            <span class="badge bg-primary-50 text-primary-600 fw-bold px-14 py-9 radius-8 text-xs">
                Total: {{ $mapels->count() }} Mata Pelajaran
            </span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- LAYOUT BERDAMPINGAN: KIRI (FORM TAMBAH MAPEL) | KANAN (TABEL RAMPING)     -->
    <!-- ========================================================================= -->
    <div class="row gy-4">

        <!-- SISI KIRI (4 KOLOM): FORM TAMBAH MATA PELAJARAN BARU -->
        <div class="col-lg-4">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Mata Pelajaran
                    </h6>
                </div>
                <form action="{{ route('mapel.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-20">
                        <div class="mb-16">
                            <label class="form-label fw-bold text-sm mb-6">Kode Mapel <span class="text-danger-600">*</span></label>
                            <input type="text" name="kode_mapel" class="form-control radius-8 fw-bold" placeholder="Contoh: MTK / QH / BING" required>
                            <small class="text-xs text-secondary-light">Gunakan singkatan unik (maks 10 karakter).</small>
                        </div>

                        <div class="mb-16">
                            <label class="form-label fw-bold text-sm mb-6">Nama Mata Pelajaran <span class="text-danger-600">*</span></label>
                            <input type="text" name="nama_mapel" class="form-control radius-8 fw-semibold" placeholder="Contoh: Matematika / Al-Qur'an Hadis" required>
                        </div>

                        <div class="mb-16">
                            <label class="form-label fw-semibold text-sm mb-6">Kelompok Kurikulum</label>
                            <select name="kelompok" class="form-select radius-8">
                                <option value="Wajib / Umum" selected>Wajib / Umum</option>
                                <option value="PAI & Bahasa Arab">PAI & Bahasa Arab</option>
                                <option value="Muatan Lokal">Muatan Lokal / Pilihan</option>
                            </select>
                        </div>

                        <div class="mb-20">
                            <label class="form-label fw-semibold text-sm mb-6">Peruntukan Tingkat</label>
                            <select name="tingkat" class="form-select radius-8">
                                <option value="Semua Tingkat (1-6)" selected>Semua Tingkat (1-6)</option>
                                <option value="Fase A (Kelas 1-2)">Fase A (Kelas 1-2)</option>
                                <option value="Fase B (Kelas 3-4)">Fase B (Kelas 3-4)</option>
                                <option value="Fase C (Kelas 5-6)">Fase C (Kelas 5-6)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Mata Pelajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SISI KANAN (8 KOLOM): TABEL DAFTAR MATA PELAJARAN YANG RAMPING & BERSIH -->
        <div class="col-lg-8">
            <div class="card basic-data-table radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="text-md fw-bold mb-0">Daftar Katalog Mata Pelajaran</h6>
                    <span class="text-xs text-secondary-light">Ceklis baris untuk menghapus beberapa mapel sekaligus</span>
                </div>

                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="dataTableMapel" data-page-length='15'>
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">
                                        <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllMasterMapel">
                                    </th>
                                    <th width="6%">No</th>
                                    <th width="14%">Kode</th>
                                    <th>Nama Mata Pelajaran</th>
                                    <th>Kelompok</th>
                                    <th>Tingkat</th>
                                    <th class="text-center" width="14%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mapels as $idx => $mp): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input check-item-master-mapel border-secondary cursor-pointer" value="{{ $mp->id }}">
                                        </td>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary-50 text-primary-600 fw-bold px-10 py-6">{{ $mp->kode_mapel }}</span>
                                        </td>
                                        <td class="fw-bold text-primary-light">{{ $mp->nama_mapel }}</td>
                                        <td>
                                            <span class="bg-info-focus text-info-main px-10 py-4 rounded-pill text-xs fw-semibold">
                                                {{ $mp->kelompok ?? 'Wajib / Umum' }}
                                            </span>
                                        </td>
                                        <td class="text-xs fw-semibold text-secondary-light">{{ $mp->tingkat ?? 'Semua Tingkat (1-6)' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center gap-8 justify-content-center">
                                                <button type="button"
                                                        class="bg-success-focus text-success-600 bg-hover-success-200 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editMapelModal{{ $mp->id }}"
                                                        title="Edit Mapel">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>

                                                <button type="button"
                                                        onclick="hapusSatuMapel('{{ route('mapel.destroy', $mp->id) }}', '{{ addslashes($mp->nama_mapel) }}')"
                                                        class="bg-danger-focus text-danger-600 bg-hover-danger-200 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                        title="Hapus Mapel">
                                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
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
        </div>

    </div>

    <form id="formDeleteSingleMapel" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Edit Master Mapel -->
    <?php foreach ($mapels as $mp): ?>
        <div class="modal fade" id="editMapelModal{{ $mp->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Edit Mata Pelajaran: {{ $mp->nama_mapel }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('mapel.update', $mp->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-sm mb-6">Kode Mapel <span class="text-danger-600">*</span></label>
                                    <input type="text" name="kode_mapel" class="form-control radius-8 fw-bold" value="{{ $mp->kode_mapel }}" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Mata Pelajaran <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_mapel" class="form-control radius-8 fw-bold" value="{{ $mp->nama_mapel }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Kelompok Kurikulum</label>
                                    <select name="kelompok" class="form-select radius-8">
                                        <option value="Wajib / Umum" {{ $mp->kelompok == 'Wajib / Umum' ? 'selected' : '' }}>Wajib / Umum</option>
                                        <option value="PAI & Bahasa Arab" {{ $mp->kelompok == 'PAI & Bahasa Arab' ? 'selected' : '' }}>PAI & Bahasa Arab</option>
                                        <option value="Muatan Lokal" {{ $mp->kelompok == 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal / Pilihan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Peruntukan Tingkat</label>
                                    <select name="tingkat" class="form-select radius-8">
                                        <option value="Semua Tingkat (1-6)" {{ $mp->tingkat == 'Semua Tingkat (1-6)' ? 'selected' : '' }}>Semua Tingkat (1-6)</option>
                                        <option value="Fase A (Kelas 1-2)" {{ $mp->tingkat == 'Fase A (Kelas 1-2)' ? 'selected' : '' }}>Fase A (Kelas 1-2)</option>
                                        <option value="Fase B (Kelas 3-4)" {{ $mp->tingkat == 'Fase B (Kelas 3-4)' ? 'selected' : '' }}>Fase B (Kelas 3-4)</option>
                                        <option value="Fase C (Kelas 5-6)" {{ $mp->tingkat == 'Fase C (Kelas 5-6)' ? 'selected' : '' }}>Fase C (Kelas 5-6)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

@endsection

@push('scripts')
<script>
    let tableMapel = new DataTable('#dataTableMapel', {
        columnDefs: [{ orderable: false, targets: [0, 6] }]
    });

    const checkAllMaster  = document.getElementById('checkAllMasterMapel');
    const btnBulkMaster   = document.getElementById('btnBulkDeleteMasterMapel');
    const spanCountMaster = document.getElementById('countSelectedMasterMapel');

    function updateBulkMasterMapel() {
        const checked = document.querySelectorAll('.check-item-master-mapel:checked');
        spanCountMaster.innerText = checked.length;
        if (checked.length > 0) {
            btnBulkMaster.classList.remove('d-none');
            btnBulkMaster.classList.add('d-inline-flex');
        } else {
            btnBulkMaster.classList.add('d-none');
            btnBulkMaster.classList.remove('d-inline-flex');
        }
    }

    if (checkAllMaster) {
        checkAllMaster.addEventListener('change', function () {
            document.querySelectorAll('.check-item-master-mapel').forEach(cb => cb.checked = checkAllMaster.checked);
            updateBulkMasterMapel();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-master-mapel')) updateBulkMasterMapel();
    });

    function konfirmasiHapusBanyakMapel() {
        const checked = document.querySelectorAll('.check-item-master-mapel:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' Mata Pelajaran?',
            text: 'Data mata pelajaran yang dicentang akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Sekaligus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const box = document.getElementById('containerHiddenIdsMapel');
                box.innerHTML = '';
                checked.forEach(cb => {
                    box.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                document.getElementById('formBulkDeleteMapel').submit();
            }
        });
    }

    function hapusSatuMapel(url, nama) {
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: 'Mata pelajaran ini akan dihapus dari master!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const f = document.getElementById('formDeleteSingleMapel');
                f.action = url;
                f.submit();
            }
        });
    }
</script>
@endpush