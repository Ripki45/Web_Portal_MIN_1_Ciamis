@extends('layouts.admin')

@section('title', 'Data Guru & Tendik')

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
            <li class="fw-medium">Manajemen GTK</li>
            <li>-</li>
            <li class="fw-medium text-primary-600">Data Guru & Tendik</li>
        </ul>
    </div>

    <!-- TABEL DATA GTK -->
    <div class="card basic-data-table radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h6 class="text-lg fw-semibold mb-0">Daftar Guru & Tendik MIN 1 Ciamis</h6>
            
            <!-- Tombol Buka Modal Tambah GTK -->
            <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addGtkModal">
                <i class="ph-bold ph-user-plus text-lg"></i> Tambah GTK Baru
            </button>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Profil & Nama Lengkap</th>
                            <th scope="col">NIP</th>
                            <th scope="col">L/P</th>
                            <th scope="col">No. Telepon</th>
                            <th scope="col">Status</th>
                            <th scope="col">Jabatan & Posisi</th>
                            <th scope="col" class="text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gtks as $index =>$item): ?>
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama_lengkap) . '&background=16a34a&color=fff&bold=true' }}" 
                                             alt="Foto" 
                                             class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden object-fit-cover">
                                        <div class="flex-grow-1">
                                            <span class="text-md mb-0 fw-semibold text-primary-light d-block">{{ $item->nama_lengkap }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Scan Tanda Tangan <span class="text-secondary-light fw-normal">(PNG Transparan)</span>
                                        </label>
                                        <input type="file" name="ttd" class="form-control radius-8" accept="image/png, image/jpeg">
                                        <small class="text-xs text-secondary-light">Gunakan gambar PNG tanpa background agar rapi di laporan.</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary-light fw-medium">{{ $item->nomor_induk ?? '-' }}</span>
                                </td>
                                <td>{{ $item->jenis_kelamin }}</td>
                                <td>{{ $item->no_hp ?? '-' }}</td>
                                <td>
                                    @if ($item->status_kepegawaian == 'PNS')
                                        <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">PNS</span>
                                    @elseif ($item->status_kepegawaian == 'PPPK')
                                        <span class="bg-info-focus text-info-main px-12 py-4 rounded-pill fw-medium text-sm">PPPK</span>
                                    @else
                                        <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-medium text-sm">Honorer</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold text-primary-light d-block">{{ $item->jabatan->nama_jabatan ?? '-' }}</span>
                                    <span class="text-sm text-secondary-light">{{ $item->posisi->nama_posisi ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <!-- Tombol Edit -->
                                        <button type="button" 
                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editGtkModal{{ $item->id }}"
                                                title="Edit">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('gtk.destroy', $item->id) }}" method="POST" class="form-delete" data-nama="{{ $item->nama_lengkap }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                    title="Hapus">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL EDIT GTK -->
                            <div class="modal fade" id="editGtkModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content radius-16 bg-base">
                                        <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                                            <h1 class="modal-title fs-5">Edit Data GTK</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('gtk.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-24">
                                                <div class="row gy-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap & Gelar <span class="text-danger-600">*</span></label>
                                                        <input type="text" name="nama_lengkap" class="form-control radius-8" value="{{ $item->nama_lengkap }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">NIP / NUPTK / NIK <span class="text-secondary-light fw-normal">(Opsional)</span></label>
                                                        <input type="text" name="nomor_induk" class="form-control radius-8" value="{{ $item->nomor_induk }}" placeholder="Kosongkan jika belum ada">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin <span class="text-danger-600">*</span></label>
                                                        <select name="jenis_kelamin" class="form-select radius-8" required>
                                                            <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                                            <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Telepon / WA</label>
                                                        <input type="text" name="no_hp" class="form-control radius-8" value="{{ $item->no_hp }}" placeholder="Contoh: 081234567890">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status Kepegawaian <span class="text-danger-600">*</span></label>
                                                        <select name="status_kepegawaian" class="form-select radius-8" required>
                                                            <option value="PNS" {{ $item->status_kepegawaian == 'PNS' ? 'selected' : '' }}>PNS</option>
                                                            <option value="PPPK" {{ $item->status_kepegawaian == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                                            <option value="Honorer" {{ $item->status_kepegawaian == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Ganti Foto Profil <span class="text-secondary-light fw-normal">(Opsional)</span></label>
                                                        <input type="file" name="foto" class="form-control radius-8" accept="image/*">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                            Scan Tanda Tangan (PNG Transparan)
                                                        </label>
                                                        <input type="file" name="ttd" class="form-control radius-8" accept="image/png, image/jpeg">
                                                        @if ($item->ttd)
                                                            <div class="mt-8 p-6 border radius-8 d-inline-block bg-neutral-50">
                                                                <img src="{{ asset('storage/' . $item->ttd) }}" alt="TTD" style="height: 40px; object-fit: contain;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jabatan <span class="text-danger-600">*</span></label>
                                                        <select name="jabatan_id" class="form-select radius-8 select-jabatan" data-target="#posisi_edit_{{ $item->id }}" required>
                                                            <option value="" disabled>-- Pilih Jabatan --</option>
                                                            <?php foreach ($jabatans as$jab): ?>
                                                                <option value="{{ $jab->id }}" {{ $item->jabatan_id == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Posisi / Penugasan</label>
                                                        <select name="posisi_id" id="posisi_edit_{{ $item->id }}" class="form-select radius-8">
                                                            <option value="">-- Pilih Posisi --</option>
                                                            <?php foreach ($posisis as$pos): ?>
                                                                @if ($pos->jabatan_id ==$item->jabatan_id)
                                                                    <option value="{{ $pos->id }}" {{ $item->posisi_id == $pos->id ? 'selected' : '' }}>{{ $pos->nama_posisi }}</option>
                                                                @endif
                                                            <?php endforeach; ?>
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
                            <!-- END MODAL EDIT -->
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH GTK BARU -->
    <div class="modal fade" id="addGtkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5">Tambah Guru / Tendik Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gtk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap & Gelar <span class="text-danger-600">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control radius-8" placeholder="Contoh: Ahmad Fauzi, S.Pd.I." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">NIP / NUPTK / NIK <span class="text-secondary-light fw-normal">(Opsional)</span></label>
                                <input type="text" name="nomor_induk" class="form-control radius-8" placeholder="Kosongkan jika belum ada">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin <span class="text-danger-600">*</span></label>
                                <select name="jenis_kelamin" class="form-select radius-8" required>
                                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Telepon / WA</label>
                                <input type="text" name="no_hp" class="form-control radius-8" placeholder="Contoh: 081234567890">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status Kepegawaian <span class="text-danger-600">*</span></label>
                                <select name="status_kepegawaian" class="form-select radius-8" required>
                                    <option value="" disabled selected>-- Pilih Status --</option>
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="Honorer">Honorer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Foto Profil <span class="text-secondary-light fw-normal">(Opsional)</span></label>
                                <input type="file" name="foto" class="form-control radius-8" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jabatan <span class="text-danger-600">*</span></label>
                                <select name="jabatan_id" class="form-select radius-8 select-jabatan" data-target="#posisi_add" required>
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    <?php foreach ($jabatans as$jab): ?>
                                        <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Posisi / Penugasan</label>
                                <select name="posisi_id" id="posisi_add" class="form-select radius-8">
                                    <option value="">-- Pilih Jabatan Terlebih Dahulu --</option>
                                </select>
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
    let table = new DataTable('#dataTable');

    // Script Dependent Dropdown (Pilih Jabatan -> Muncul Posisi Sesuai Jabatan)
    document.querySelectorAll('.select-jabatan').forEach(function(selectElem) {
        selectElem.addEventListener('change', function() {
            let jabatanId = this.value;
            let targetSelector = this.getAttribute('data-target');
            let posisiDropdown = document.querySelector(targetSelector);

            posisiDropdown.innerHTML = '<option value="">Memuat posisi...</option>';

            fetch(`/posisi/by-jabatan/${jabatanId}`)
                .then(response => response.json())
                .then(data => {
                    posisiDropdown.innerHTML = '<option value="">-- Pilih Posisi --</option>';
                    if (data.length > 0) {
                        data.forEach(function(posisi) {
                            posisiDropdown.innerHTML += `<option value="${posisi.id}">${posisi.nama_posisi}</option>`;
                        });
                    } else {
                        posisiDropdown.innerHTML = '<option value="">Tidak ada posisi untuk jabatan ini</option>';
                    }
                })
                .catch(error => {
                    posisiDropdown.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        });
    });
</script>
@endpush