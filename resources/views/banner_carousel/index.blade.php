@extends('layouts.admin')

@section('title', 'Manajemen Foto Banner Carousel - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Foto Latar Carousel Banner</h6>
            <span class="text-xs text-secondary-light">
                Cukup unggah <b>Foto Banner</b> saja di sini — teks judul dan berita yang bergerak di dalamnya otomatis
                diambil dari <b>4 Berita Terbaru</b>
            </span>
        </div>
        <a href="{{ url('/') }}#carousel-banner" target="_blank"
            class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Hasil di Halaman Depan
        </a>
    </div>

    <div class="row gy-4">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (4 KOLOM): FORM UPLOAD FOTO BANNER SAJA                         -->
        <!-- ========================================================================= -->
        <div class="col-lg-4">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                        <i class="ph-bold ph-upload-simple text-lg"></i> Unggah Foto Banner Baru
                    </h6>
                </div>

                <form action="{{ route('banner-carousel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-20">

                        <!-- Preview & Upload Foto -->
                        <div class="mb-16">
                            <label class="form-label fw-bold text-sm mb-6">
                                Pilih File Foto Banner <span class="text-danger-600">*</span>
                            </label>
                            <div class="p-12 radius-12 bg-neutral-50 border text-center mb-6">
                                <img id="previewAddBanner"
                                    src="{{ asset('frontend/assets/imgs/page/bg-home1-sec9-1.png') }}" alt="Preview Banner"
                                    class="w-100 radius-8 mb-10 border" style="height: 170px; object-fit: cover;">
                                <input type="file" name="gambar" class="form-control form-control-sm radius-8"
                                    accept="image/*" onchange="tampilkanPreview(this, 'previewAddBanner')" required>
                            </div>
                            <small class="text-xs text-secondary-light d-block">
                                Format JPG, PNG, atau WEBP (Rekomendasi ukuran mendatar <b>1280 x 720 px</b>, maks. 4 MB).
                            </small>
                        </div>

                        <!-- Urutan Slide -->
                        <div class="mb-16">
                            <label class="form-label fw-bold text-sm mb-6">Urutan Slide Banner (1 - 4)</label>
                            <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                                value="{{ $banners->count() + 1 }}" min="1" required>
                            <small class="text-xs text-secondary-light d-block mt-4">
                                Slide #1 akan menjadi latar untuk Berita Terbaru ke-1, Slide #2 untuk Berita ke-2, dst.
                            </small>
                        </div>

                        <!-- Status Aktif -->
                        <div class="p-12 radius-8 bg-neutral-50 border mb-20">
                            <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                    id="chkAddBannerActive" checked>
                                <label class="form-check-label text-xs fw-semibold" for="chkAddBannerActive">
                                    ✓ Aktifkan Foto Banner Ini
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold">
                            <i class="ph-bold ph-floppy-disk me-1"></i> Upload & Simpan Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (8 KOLOM): GALERI 4 FOTO BANNER TERPASANG                      -->
        <!-- ========================================================================= -->
        <div class="col-lg-8">
            <div class="card radius-12 border shadow-sm">
                <div
                    class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-md fw-bold mb-0">Daftar Foto Banner Terpasang ({{ $banners->count() }} Foto)</h6>
                        <span class="text-xs text-secondary-light">4 foto aktif pertama otomatis dipasangkan dengan 4 Berita
                            Terbaru di halaman depan</span>
                    </div>
                </div>

                <div class="card-body p-24">
                    <div class="row g-3">
                        @forelse ($banners as $b)
                            <div class="col-md-6">
                                <div class="card radius-12 border h-100 overflow-hidden shadow-sm">
                                    <div class="position-relative">
                                        <img src="{{ $b->gambar_url }}" alt="Banner Slide {{ $b->urutan }}"
                                            class="w-100" style="height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 m-12">
                                            <span
                                                class="bg-dark text-white px-12 py-4 rounded-pill text-xs fw-bold shadow-sm">
                                                Foto Latar Slide #{{ $b->urutan }}
                                            </span>
                                        </div>
                                        <div class="position-absolute top-0 end-0 m-12">
                                            <span
                                                class="{{ $b->is_active ? 'bg-success-600' : 'bg-danger-600' }} text-white px-10 py-4 rounded-pill text-xs fw-bold shadow-sm">
                                                {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="card-body p-14 d-flex align-items-center justify-content-between bg-neutral-50">
                                        <span class="text-xs fw-semibold text-secondary-light">
                                            Urutan Tampil: <b>Slide {{ $b->urutan }}</b>
                                        </span>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-success-600 radius-8 px-12 py-4 text-xs fw-bold"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editBannerModal{{ $b->id }}">
                                                ✎ Ganti Foto
                                            </button>
                                            <button type="button"
                                                onclick="konfirmasiHapusBanner('{{ route('banner-carousel.destroy', $b->id) }}', 'Slide #{{ $b->urutan }}')"
                                                class="btn btn-sm btn-outline-danger-600 radius-8 px-10 py-4 text-xs fw-bold">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-32 text-secondary-light">
                                Belum ada foto banner. Silakan unggah melalui form di sebelah kiri.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form id="formDeleteBannerGlobal" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- MODAL GANTI FOTO / URUTAN BANNER                                          -->
    <!-- ========================================================================= -->
    @foreach ($banners as $b)
        <div class="modal fade" id="editBannerModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Ganti Foto Banner Slide #{{ $b->urutan }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('banner-carousel.update', $b->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="mb-16 text-center">
                                <img id="previewEditBanner{{ $b->id }}" src="{{ $b->gambar_url }}"
                                    alt="Preview" class="w-100 radius-8 mb-10 border"
                                    style="height: 180px; object-fit: cover;">
                                <input type="file" name="gambar" class="form-control form-control-sm radius-8"
                                    accept="image/*"
                                    onchange="tampilkanPreview(this, 'previewEditBanner{{ $b->id }}')">
                                <small class="text-xs text-secondary-light d-block mt-4">Pilih foto baru jika ingin
                                    mengganti gambar latar ini.</small>
                            </div>

                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Urutan Slide</label>
                                <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                                    value="{{ $b->urutan }}" min="1" required>
                            </div>

                            <div class="p-12 radius-8 bg-neutral-50 border">
                                <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                        id="chkEditBanner{{ $b->id }}" {{ $b->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label text-xs fw-semibold"
                                        for="chkEditBanner{{ $b->id }}">
                                        ✓ Tampilkan Foto Banner Ini
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function tampilkanPreview(input, targetImgId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById(targetImgId);
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function konfirmasiHapusBanner(url, label) {
            Swal.fire({
                title: 'Hapus Foto Banner ' + label + '?',
                text: 'Foto ini akan dihapus dari daftar slide halaman depan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(res) {
                if (res.isConfirmed) {
                    var f = document.getElementById('formDeleteBannerGlobal');
                    f.action = url;
                    f.submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

@endsection
