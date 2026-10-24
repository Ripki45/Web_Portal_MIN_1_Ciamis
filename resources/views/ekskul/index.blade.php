@extends('layouts.admin')

@section('title', 'Manajemen Ekstrakurikuler - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Ekstrakurikuler Madrasah</h6>
            <span class="text-xs text-secondary-light">Kelola kegiatan Pramuka, Keagamaan, Olahraga (Voli, Futsal), Kesenian, & Daftar Anggotanya</span>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Form Hapus Banyak Ekskul -->
            <form action="{{ route('ekskul.bulkDestroy') }}" method="POST" id="formBulkDeleteEkskul" class="d-inline">
                @csrf
                <div id="hiddenIdsEkskul"></div>
                <button type="button" id="btnBulkEkskul" onclick="konfirmasiHapusBanyakEkskul()" class="btn btn-danger-600 radius-8 px-14 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                    <i class="ph-bold ph-trash text-lg"></i> Hapus Terpilih (<span id="countSelectedEkskul">0</span>)
                </button>
            </form>

            <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addEkskulModal">
                <i class="ph-bold ph-plus-circle text-lg"></i> + Tambah Ekstrakurikuler
            </button>
        </div>
    </div>

    <!-- Kartu Ringkasan Ekstrakurikuler -->
    @if ($ekskuls->isNotEmpty())
        <div class="row g-3 mb-24">
            <?php foreach ($ekskuls as $eks): ?>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="card radius-16 border shadow-sm h-100 hover-border-success">
                        <div class="card-body p-20 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-12">
                                    <h5 class="fw-black text-primary-light mb-0">{{ $eks->nama_ekskul }}</h5>
                                    <span class="badge {{ $eks->status == 'Aktif' ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} text-xs">
                                        {{ $eks->status }}
                                    </span>
                                </div>
                                <span class="text-xs text-secondary-light d-block mb-4">
                                    Pembina: <b class="text-primary-light">{{ $eks->pembina->nama_lengkap ?? 'Belum Ditentukan' }}</b>
                                </span>
                                <span class="text-xs text-secondary-light d-block mb-16">
                                    Jadwal: <b>{{ $eks->hari_latihan ?: '-' }} {{ $eks->jam_latihan ? '(' . $eks->jam_latihan . ')' : '' }}</b>
                                </span>
                            </div>

                            <div class="pt-12 border-top d-flex align-items-center justify-content-between">
                                <span class="badge bg-info-focus text-info-main fw-bold text-xs px-10 py-6">
                                    {{ $eks->anggotas->count() }} Anggota Terdaftar
                                </span>
                                <a href="{{ route('ekskul.show', $eks->id) }}" class="btn btn-primary-600 btn-sm radius-8 px-12 py-6 d-inline-flex align-items-center gap-1 fw-bold text-xs">
                                    Kelola Anggota <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    @endif

    <!-- Tabel Daftar Ekstrakurikuler (Dengan Ceklis Hapus Massal) -->
    <div class="card basic-data-table radius-12">
        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTableEkskul">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">
                                <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllEkskul">
                            </th>
                            <th width="5%">No</th>
                            <th>Nama Ekstrakurikuler</th>
                            <th>Pembina / Pelatih</th>
                            <th>TTD Pembina</th>
                            <th>Jadwal Latihan</th>
                            <th>Jumlah Anggota</th>
                            <th>Status</th>
                            <th class="text-center" width="14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ekskuls as $idx => $eks): ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input check-item-ekskul border-secondary cursor-pointer" value="{{ $eks->id }}">
                                </td>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold text-primary-light">{{ $eks->nama_ekskul }}</td>
                                <td>
                                    <span class="fw-semibold d-block">{{ $eks->pembina->nama_lengkap ?? 'Belum Ditentukan' }}</span>
                                    <span class="text-xs text-secondary-light">NIP: {{ $eks->pembina->nomor_induk ?? '-' }}</span>
                                </td>
                                <td>
                                    @if ($eks->pembina && $eks->pembina->ttd)
                                        <span class="badge bg-success-focus text-success-main text-xs">✓ TTD Siap</span>
                                    @else
                                        <span class="badge bg-neutral-200 text-secondary-light text-xs">Belum Upload</span>
                                    @endif
                                </td>
                                <td class="text-xs fw-semibold">
                                    {{ $eks->hari_latihan ?: '-' }}
                                    @if ($eks->jam_latihan)
                                        <span class="d-block text-secondary-light">{{ $eks->jam_latihan }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('ekskul.show', $eks->id) }}" class="badge bg-primary-50 text-primary-600 fw-bold px-12 py-6">
                                        {{ $eks->anggotas->count() }} Siswa (Lihat Daftar)
                                    </a>
                                </td>
                                <td>
                                    <span class="badge {{ $eks->status == 'Aktif' ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }}">
                                        {{ $eks->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-6 justify-content-center">
                                        <a href="{{ route('ekskul.show', $eks->id) }}"
                                           class="bg-primary-50 text-primary-600 w-34-px h-34-px rounded-circle d-flex align-items-center justify-content-center"
                                           title="Kelola Daftar Anggota">
                                            <i class="ph-bold ph-users-three"></i>
                                        </a>

                                        <button type="button"
                                                class="bg-success-focus text-success-600 w-34-px h-34-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editEkskulModal{{ $eks->id }}"
                                                title="Edit Ekskul">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </button>

                                        <button type="button"
                                                onclick="hapusSatuEkskul('{{ route('ekskul.destroy', $eks->id) }}', '{{ addslashes($eks->nama_ekskul) }}')"
                                                class="bg-danger-focus text-danger-600 w-34-px h-34-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                title="Hapus Ekskul">
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

    <form id="formDeleteSingleEkskul" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Tambah Ekskul -->
    <div class="modal fade" id="addEkskulModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h1 class="modal-title fs-5 fw-bold">Tambah Ekstrakurikuler Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('ekskul.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-sm mb-6">Nama Ekstrakurikuler <span class="text-danger-600">*</span></label>
                                <input type="text" name="nama_ekskul" class="form-control radius-8" placeholder="Contoh: Pramuka / Futsal / Voli / Keagamaan" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-sm mb-6">Pilih Pembina / Pelatih (Dari Master GTK)</label>
                                <select name="pembina_id" class="form-select radius-8">
                                    <option value="">-- Pilih Pembina Ekskul --</option>
                                    <?php foreach ($gtks as $g): ?>
                                        <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Hari Latihan</label>
                                <input type="text" name="hari_latihan" class="form-control radius-8" placeholder="Contoh: Jumat / Sabtu">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Jam Latihan</label>
                                <input type="text" name="jam_latihan" class="form-control radius-8" placeholder="Contoh: 14:00 - 16:00 WIB">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-sm mb-6">Status</label>
                                <select name="status" class="form-select radius-8">
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Ekstrakurikuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Ekskul -->
    <?php foreach ($ekskuls as $eks): ?>
        <div class="modal fade" id="editEkskulModal{{ $eks->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Edit Ekstrakurikuler: {{ $eks->nama_ekskul }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('ekskul.update', $eks->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ekstrakurikuler <span class="text-danger-600">*</span></label>
                                    <input type="text" name="nama_ekskul" class="form-control radius-8" value="{{ $eks->nama_ekskul }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-sm mb-6">Pembina / Pelatih</label>
                                    <select name="pembina_id" class="form-select radius-8">
                                        <option value="">-- Belum Ditentukan --</option>
                                        <?php foreach ($gtks as $g): ?>
                                            <option value="{{ $g->id }}" {{ $eks->pembina_id == $g->id ? 'selected' : '' }}>{{ $g->nama_lengkap }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Hari Latihan</label>
                                    <input type="text" name="hari_latihan" class="form-control radius-8" value="{{ $eks->hari_latihan }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Jam Latihan</label>
                                    <input type="text" name="jam_latihan" class="form-control radius-8" value="{{ $eks->jam_latihan }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-sm mb-6">Status</label>
                                    <select name="status" class="form-select radius-8">
                                        <option value="Aktif" {{ $eks->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ $eks->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
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
    let tableEks = new DataTable('#dataTableEkskul', {
        columnDefs: [{ orderable: false, targets: [0, 8] }]
    });

    const checkAllEks = document.getElementById('checkAllEkskul');
    const btnBulkEks  = document.getElementById('btnBulkEkskul');
    const spanCountEks= document.getElementById('countSelectedEkskul');

    function updateBulkEks() {
        const checked = document.querySelectorAll('.check-item-ekskul:checked');
        spanCountEks.innerText = checked.length;
        if (checked.length > 0) {
            btnBulkEks.classList.remove('d-none');
            btnBulkEks.classList.add('d-inline-flex');
        } else {
            btnBulkEks.classList.add('d-none');
            btnBulkEks.classList.remove('d-inline-flex');
        }
    }

    if (checkAllEks) {
        checkAllEks.addEventListener('change', function () {
            document.querySelectorAll('.check-item-ekskul').forEach(cb => cb.checked = checkAllEks.checked);
            updateBulkEks();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-ekskul')) updateBulkEks();
    });

    function konfirmasiHapusBanyakEkskul() {
        const checked = document.querySelectorAll('.check-item-ekskul:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' Ekstrakurikuler?',
            text: 'Data ekstrakurikuler beserta daftar anggotanya akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const box = document.getElementById('hiddenIdsEkskul');
                box.innerHTML = '';
                checked.forEach(cb => {
                    box.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                document.getElementById('formBulkDeleteEkskul').submit();
            }
        });
    }

    function hapusSatuEkskul(url, nama) {
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: 'Data ekstrakurikuler ini akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const f = document.getElementById('formDeleteSingleEkskul');
                f.action = url;
                f.submit();
            }
        });
    }
</script>
@endpush