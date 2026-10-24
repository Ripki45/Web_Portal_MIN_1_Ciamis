@extends('layouts.admin')

@section('title', 'Master Mata Pelajaran')

@section('content')

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Master Mata Pelajaran</h6>
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
            <li class="fw-medium text-primary-600">Mata Pelajaran</li>
        </ul>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 1: KARTU KELAS (KLIK UNTUK MENGISI / MELIHAT MAPEL PER KELAS)      -->
    <!-- ========================================================================= -->
    <div class="mb-24">
        <div class="d-flex align-items-center justify-content-between mb-12">
            <span class="text-sm fw-bold text-primary-light">
                <i class="ph-bold ph-squares-four text-success-600 me-1"></i> Kartu Distribusi Mata Pelajaran per Kelas:
            </span>
            <span class="text-xs text-secondary-light">Klik tombol panah pada kartu untuk mengatur mata pelajaran di kelas tersebut</span>
        </div>

        <div class="row g-3">
            <?php foreach ($kelasList as $kls): ?>
                <div class="col-xxl-2 col-lg-3 col-md-4 col-6">
                    <div class="card radius-12 border shadow-sm h-100 hover-border-success">
                        <div class="card-body p-16 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-8">
                                    <h5 class="fw-black text-primary-light mb-0">{{ str_replace('Kelas ', '', $kls->nama_kelas) }}</h5>
                                    <span class="badge bg-success-focus text-success-main text-xs">
                                        {{ $kls->kelasMapels->count() }} Mapel
                                    </span>
                                </div>
                                <span class="text-xs text-secondary-light d-block text-truncate mb-12" title="{{ $kls->waliKelas->nama_lengkap ?? '-' }}">
                                    Wali: <b>{{ $kls->waliKelas->nama_lengkap ?? 'Belum diset' }}</b>
                                </span>
                            </div>

                            <div class="pt-8 border-top d-flex align-items-center justify-content-between">
                                <a href="{{ route('kelas.show', $kls->id) }}" class="text-xs fw-bold text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                    Atur Mapel Kelas <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: TABEL MASTER MATA PELAJARAN (DENGAN CEKLIS & INFO KELAS AKTIF)  -->
    <!-- ========================================================================= -->
    <div class="card basic-data-table radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-md fw-bold mb-0">Daftar Katalog Mata Pelajaran Madrasah</h6>
            <span class="text-xs text-secondary-light">Total: <b>{{ $mapels->count() }} Mata Pelajaran</b></span>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTableMapel" data-page-length='25'>
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">
                                <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllMasterMapel">
                            </th>
                            <th width="5%">No</th>
                            <th width="10%">Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kelompok Kurikulum</th>
                            <th>Tingkat</th>
                            <th>Diterapkan di Kelas & Guru Pengampu</th>
                            <th class="text-center" width="10%">Aksi</th>
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
                                <td class="fw-bold text-primary-light text-nowrap">{{ $mp->nama_mapel }}</td>
                                <td>
                                    <span class="bg-info-focus text-info-main px-10 py-4 rounded-pill text-xs fw-semibold">
                                        {{ $mp->kelompok ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="text-xs fw-semibold text-secondary-light">{{ $mp->tingkat ?? 'Semua Tingkat' }}</td>
                                <td style="min-width: 240px;">
                                    @if ($mp->kelasMapels->isEmpty())
                                        <span class="text-xs text-secondary-light fst-italic">Belum diterapkan ke kelas</span>
                                    @else
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($mp->kelasMapels as $km): ?>
                                                <span class="badge bg-success-focus text-success-main border border-success-200 text-xs"
                                                      title="Pengajar: {{ $km->guru->nama_lengkap ?? '-' }}">
                                                    {{ str_replace('Kelas ', '', $km->kelas->nama_kelas ?? '-') }}
                                                    ({{ \Illuminate\Support\Str::words($km->guru->nama_lengkap ?? '-', 2, '') }})
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-8 justify-content-center">
                                        <button type="button"
                                                class="bg-success-focus text-success-600 bg-hover-success-200 w-34-px h-34-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editMapelModal{{ $mp->id }}"
                                                title="Edit Mapel">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </button>

                                        <button type="button"
                                                onclick="hapusSatuMapel('{{ route('mapel.destroy', $mp->id) }}', '{{ addslashes($mp->nama_mapel) }}')"
                                                class="bg-danger-focus text-danger-600 bg-hover-danger-200 w-34-px h-34-px rounded-circle d-flex align-items-center justify-content-center border-0"
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

    <form id="formDeleteSingleMapel" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- MODAL TERAPKAN BANYAK MAPEL KE BANYAK KELAS (DENGAN TABEL CEKLIS)         -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="plottingCepatMapelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h1 class="modal-title fs-5 fw-bold">Terapkan Mata Pelajaran & Guru ke Kelas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('kelas.storePlottingMapel') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-sm mb-6">1. Pilih Guru Pengajar <span class="text-danger-600">*</span></label>
                                <select name="gtk_id" class="form-select radius-8 fw-bold" required>
                                    <option value="" disabled selected>-- Pilih Guru Wali Kelas / Guru Mapel --</option>
                                    <?php foreach ($gtks as $g): ?>
                                        <option value="{{ $g->id }}">
                                            {{ $g->nama_lengkap }} — [{{ $g->posisi->nama_posisi ?? $g->jabatan->nama_jabatan ?? 'GTK' }}]
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-sm mb-6">2. Ceklis Mata Pelajaran yang Diajar <span class="text-danger-600">*</span></label>
                                <div class="border radius-12 overflow-hidden" style="max-height: 220px; overflow-y: auto;">
                                    <table class="table bordered-table mb-0 table-sm">
                                        <thead class="bg-neutral-100" style="position: sticky; top: 0;">
                                            <tr>
                                                <th width="8%" class="text-center">Pilih</th>
                                                <th width="18%">Kode</th>
                                                <th>Nama Mata Pelajaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($mapels as $mp): ?>
                                                <tr class="cursor-pointer" onclick="if(event.target.tagName!=='INPUT'){const c=this.querySelector('input');c.checked=!c.checked;}">
                                                    <td class="text-center">
                                                        <input type="checkbox" name="mapel_ids[]" value="{{ $mp->id }}" class="form-check-input border-secondary">
                                                    </td>
                                                    <td><span class="badge bg-primary-50 text-primary-600 fw-bold">{{ $mp->kode_mapel }}</span></td>
                                                    <td class="fw-bold">{{ $mp->nama_mapel }}</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-sm mb-6">3. Ceklis Kelas Tempat Mapel Diterapkan <span class="text-danger-600">*</span></label>
                                <div class="row g-2 p-12 border radius-8 bg-neutral-50" style="max-height: 150px; overflow-y: auto;">
                                    <?php foreach ($kelasList as $kls): ?>
                                        <div class="col-md-3 col-6">
                                            <label class="d-flex align-items-center gap-2 p-6 bg-white border radius-8 cursor-pointer">
                                                <input type="checkbox" name="kelas_ids[]" value="{{ $kls->id }}" class="form-check-input m-0">
                                                <span class="text-xs fw-bold">Kelas {{ str_replace('Kelas ', '', $kls->nama_kelas) }}</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info-600 radius-8 fw-bold">Simpan ke Kelas Terpilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Master Mapel -->
    <div class="modal fade" id="addMapelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h1 class="modal-title fs-5 fw-bold">Tambah Master Mata Pelajaran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mapel.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-sm mb-6">Kode Mapel <span class="text-danger-600">*</span></label>
                                <input type="text" name="kode_mapel" class="form-control radius-8" placeholder="Contoh: MTK" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold text-sm mb-6">Nama Mata Pelajaran <span class="text-danger-600">*</span></label>
                                <input type="text" name="nama_mapel" class="form-control radius-8" placeholder="Contoh: Matematika" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Kelompok Mapel</label>
                                <select name="kelompok" class="form-select radius-8">
                                    <option value="PAI & Bahasa Arab">PAI & Bahasa Arab</option>
                                    <option value="Wajib / Umum" selected>Wajib / Umum</option>
                                    <option value="Muatan Lokal">Muatan Lokal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Peruntukan Tingkat</label>
                                <select name="tingkat" class="form-select radius-8">
                                    <option value="Semua Tingkat (1-6)">Semua Tingkat (1-6)</option>
                                    <option value="Fase A (Kelas 1-2)">Fase A (Kelas 1-2)</option>
                                    <option value="Fase B (Kelas 3-4)">Fase B (Kelas 3-4)</option>
                                    <option value="Fase C (Kelas 5-6)">Fase C (Kelas 5-6)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Mapel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                                    <input type="text" name="kode_mapel" class="form-control radius-8" value="{{ $mp->kode_mapel }}" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Mata Pelajaran <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_mapel" class="form-control radius-8" value="{{ $mp->nama_mapel }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Kelompok Mapel</label>
                                    <input type="text" name="kelompok" class="form-control radius-8" value="{{ $mp->kelompok }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Peruntukan Tingkat</label>
                                    <input type="text" name="tingkat" class="form-control radius-8" value="{{ $mp->tingkat }}">
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
        columnDefs: [{ orderable: false, targets: [0, 7] }]
    });

    const checkAllMaster = document.getElementById('checkAllMasterMapel');
    const btnBulkMaster  = document.getElementById('btnBulkDeleteMasterMapel');
    const spanCountMaster= document.getElementById('countSelectedMasterMapel');

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