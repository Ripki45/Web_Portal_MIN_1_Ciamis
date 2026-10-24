@extends('layouts.admin')

@section('title', 'Master Jabatan GTK')

@section('content')

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Master Jabatan GTK</h6>
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
            <li class="fw-medium text-primary-600">Master Jabatan</li>
        </ul>
    </div>

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success bg-success-100 text-success-600 border-success-600 border-start-width-4-px px-24 py-13 mb-24 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="solar:check-circle-bold" class="icon text-xl"></iconify-icon>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-4">
        <!-- KOLOM KIRI: FORM TAMBAH JABATAN -->
        <div class="col-lg-4">
            <div class="card radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Tambah Jabatan Baru</h6>
                </div>
                <div class="card-body p-24">
                    <form action="{{ route('jabatan.store') }}" method="POST">
                        @csrf
                        <div class="mb-20">
                            <label for="nama_jabatan" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Nama Jabatan <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="nama_jabatan" id="nama_jabatan" 
                                   class="form-control radius-8 @error('nama_jabatan') is-invalid @enderror" 
                                   placeholder="Contoh: Wali Kelas / Guru Mapel" value="{{ old('nama_jabatan') }}" required>
                            @error('nama_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-20">
                            <label for="keterangan" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Keterangan <span class="text-secondary-light fw-normal">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3" 
                                      class="form-control radius-8" 
                                      placeholder="Deskripsi singkat tugas/jabatan...">{{ old('keterangan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-11 w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Jabatan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TABEL DATA JABATAN -->
        <div class="col-lg-8">
            <div class="card basic-data-table radius-12">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="text-lg fw-semibold mb-0">Daftar Jabatan GTK</h6>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                            <thead>
                                <tr>
                                    <th scope="col" width="8%">No</th>
                                    <th scope="col">Nama Jabatan</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col" class="text-center" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jabatans as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold text-primary-light">{{ $item->nama_jabatan }}</span>
                                        </td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center gap-10 justify-content-center">
                                                <!-- Tombol Edit (Trigger Modal) -->
                                                <button type="button" 
                                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $item->id }}"
                                                        title="Edit">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('jabatan.destroy', $item->id) }}" method="POST" class="form-delete" data-nama="{{ $item->nama_jabatan }}">
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

                                    <!-- MODAL EDIT JABATAN -->
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content radius-16 bg-base">
                                                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                                                    <h1 class="modal-title fs-5">Edit Jabatan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('jabatan.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-24">
                                                        <div class="mb-20">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                                Nama Jabatan <span class="text-danger-600">*</span>
                                                            </label>
                                                            <input type="text" name="nama_jabatan" class="form-control radius-8" value="{{ $item->nama_jabatan }}" required>
                                                        </div>
                                                        <div class="mb-20">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                                Keterangan
                                                            </label>
                                                            <textarea name="keterangan" rows="3" class="form-control radius-8">{{ $item->keterangan }}</textarea>
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
                                @endforeach
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
    // Inisialisasi DataTable bawaan template Admina
    let table = new DataTable('#dataTable');
</script>
@endpush