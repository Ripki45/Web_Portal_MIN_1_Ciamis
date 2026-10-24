@extends('layouts.admin')

@section('title', 'Master Tahun Ajaran & Semester')

@section('content')

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Master Tahun Ajaran & Semester</h6>
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
            <li class="fw-medium text-primary-600">Tahun Ajaran</li>
        </ul>
    </div>

    <div class="row gy-4">
        <!-- KOLOM KIRI: FORM TAMBAH TAHUN AJARAN -->
        <div class="col-lg-4">
            <div class="card radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Tambah Tahun Ajaran</h6>
                </div>
                <div class="card-body p-24">
                    <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-20">
                            <label for="tahun_ajaran" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Tahun Ajaran <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" 
                                   class="form-control radius-8" 
                                   placeholder="Contoh: 2026/2027" value="{{ old('tahun_ajaran') }}" required>
                        </div>

                        <div class="mb-20">
                            <label for="semester" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Semester <span class="text-danger-600">*</span>
                            </label>
                            <select name="semester" id="semester" class="form-select radius-8" required>
                                <option value="" disabled selected>-- Pilih Semester --</option>
                                <option value="Ganjil">Semester Ganjil (I)</option>
                                <option value="Genap">Semester Genap (II)</option>
                            </select>
                        </div>

                        <div class="mb-20">
                            <label for="keterangan" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Keterangan <span class="text-secondary-light fw-normal">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="2" 
                                      class="form-control radius-8" 
                                      placeholder="Catatan periode akademik...">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="mb-24">
                            <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1">
                                <label class="form-check-label fw-medium text-secondary-light cursor-pointer" for="is_active">
                                    Langsung Aktifkan Semester Ini
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-11 w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Tahun Ajaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TABEL DATA TAHUN AJARAN -->
        <div class="col-lg-8">
            <div class="card basic-data-table radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="text-lg fw-semibold mb-0">Daftar Tahun Ajaran & Semester</h6>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                            <thead>
                                <tr>
                                    <th scope="col" width="7%">No</th>
                                    <th scope="col">Tahun Ajaran</th>
                                    <th scope="col">Semester</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center" width="22%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tahunAjarans as $index => $item): ?>
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-bold text-primary-light">{{ $item->tahun_ajaran }}</span>
                                            @if ($item->keterangan)
                                                <small class="d-block text-secondary-light">{{ $item->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $item->semester }}</span>
                                        </td>
                                        <td>
                                            @if ($item->is_active)
                                                <span class="bg-success-focus text-success-main px-16 py-6 rounded-pill fw-semibold text-sm d-inline-flex align-items-center gap-1">
                                                    <i class="ph-fill ph-check-circle text-lg"></i> AKTIF
                                                </span>
                                            @else
                                                <span class="bg-neutral-200 text-secondary-light px-16 py-6 rounded-pill fw-medium text-sm">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center gap-10 justify-content-center">
                                                
                                                <!-- Tombol Aktifkan (Hanya muncul jika belum aktif) -->
                                                @if (!$item->is_active)
                                                    <form action="{{ route('tahun-ajaran.activate', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-success-600 radius-8 px-12 py-6 text-xs fw-semibold d-flex align-items-center gap-1"
                                                                title="Jadikan Tahun Ajaran Aktif">
                                                            <i class="ph-bold ph-power"></i> Aktifkan
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Tombol Edit -->
                                                <button type="button" 
                                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $item->id }}"
                                                        title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </button>

                                                <!-- Tombol Hapus (Terkunci jika sedang aktif) -->
                                                @if (!$item->is_active)
                                                    <form action="{{ route('tahun-ajaran.destroy', $item->id) }}" method="POST" class="form-delete" data-nama="TA {{ $item->tahun_ajaran }} ({{ $item->semester }})">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle"
                                                                title="Hapus">
                                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDIT TAHUN AJARAN -->
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content radius-16 bg-base">
                                                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                                                    <h1 class="modal-title fs-5">Edit Tahun Ajaran</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('tahun-ajaran.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-24">
                                                        <div class="mb-20">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                                Tahun Ajaran <span class="text-danger-600">*</span>
                                                            </label>
                                                            <input type="text" name="tahun_ajaran" class="form-control radius-8" value="{{ $item->tahun_ajaran }}" required>
                                                        </div>
                                                        <div class="mb-20">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                                Semester <span class="text-danger-600">*</span>
                                                            </label>
                                                            <select name="semester" class="form-select radius-8" required>
                                                                <option value="Ganjil" {{ $item->semester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil (I)</option>
                                                                <option value="Genap" {{ $item->semester == 'Genap' ? 'selected' : '' }}>Semester Genap (II)</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-20">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                                Keterangan
                                                            </label>
                                                            <textarea name="keterangan" rows="2" class="form-control radius-8">{{ $item->keterangan }}</textarea>
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
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let table = new DataTable('#dataTable');
</script>
@endpush