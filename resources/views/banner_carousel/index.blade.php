@extends('layouts.admin')

@section('title', 'Manajemen Hero Carousel (Teks Tengah & Foto) - MIN 1 Ciamis')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Outfit:wght@800;900&display=swap" rel="stylesheet">

    <style>
        .preview-aksara-kecil {
            font-family: 'Caveat', cursive;
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }
        .preview-aksara-besar {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 900;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }
        .preview-domain-line {
            font-family: 'Outfit', sans-serif;
            font-size: 0.74rem;
            font-style: italic;
            letter-spacing: 2.5px;
            color: #475569;
        }
    </style>

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Hero Carousel Utama</h6>
            <span class="text-xs text-secondary-light">
                Pilih mode <b>🎯 Teks Tengah Diperbesar (Tanpa Foto)</b> atau <b>🖼️ Teks Kiri + Foto Kanan</b> untuk setiap slide
            </span>
        </div>
        <a href="{{ url('/') }}#beranda" target="_blank" class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Hero di Halaman Depan
        </a>
    </div>

    <div class="row gy-4">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (5 KOLOM): FORM TAMBAH SLIDE HERO BARU                          -->
        <!-- ========================================================================= -->
        <div class="col-lg-5">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Slide Hero Carousel
                    </h6>
                </div>

                <form action="{{ route('banner-carousel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-20">

                        <!-- Asisten AI Generator Tagline Banner -->
                        <div class="p-12 radius-12 bg-warning-focus border border-warning mb-16 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="fw-bold text-xs text-warning-main d-block">✨ Asisten AI Copywriter Banner</span>
                                <span class="text-xs text-secondary-light">Buat paduan aksara &amp; slogan inspiratif secara instan</span>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm radius-8 fw-bold text-xs px-12 py-6" onclick="generateBannerAi()">
                                ⚡ Buat Tagline AI
                            </button>
                        </div>

                        <!-- Pilihan Tata Letak Slide -->
                        <div class="p-14 radius-12 bg-success-focus border border-success mb-16">
                            <label class="form-label fw-bold text-xs text-success-main mb-6 d-block">
                                1. Pilih Tata Letak / Mode Slide <span class="text-danger-600">*</span>
                            </label>
                            <select name="tipe_layout" class="form-select radius-8 fw-bold" onchange="toggleKotakFotoHero('Add', this.value)">
                                <option value="tanpa_foto">🎯 Teks Tengah Diperbesar (Tanpa Foto)</option>
                                <option value="dengan_foto" selected>🖼️ Teks di Kiri + Foto Kolase di Kanan</option>
                            </select>
                        </div>

                        <!-- Aksara Kecil, Aksara Besar & Domain -->
                        <div class="p-14 radius-12 bg-primary-50 border border-primary-200 mb-16">
                            <div class="row g-2 mb-12">
                                <div class="col-8">
                                    <label class="form-label fw-bold text-xs text-primary-600 mb-4">
                                        2. Aksara Kecil (Tulisan Sambung) <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text"
                                           name="aksara_kecil"
                                           id="inputAksaraKecil"
                                           class="form-control radius-8 fw-semibold"
                                           placeholder="Contoh: Selamat datang di"
                                           required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-bold text-xs text-primary-600 mb-4">Urutan Slide</label>
                                    <input type="number"
                                           name="urutan"
                                           class="form-control radius-8 fw-bold text-center"
                                           value="{{ $banners->count() + 1 }}"
                                           min="1"
                                           required>
                                </div>
                            </div>

                            <div class="mb-12">
                                <label class="form-label fw-bold text-xs text-primary-600 mb-4">
                                    3. Aksara Besar (Judul Utama Tebal) <span class="text-danger-600">*</span>
                                </label>
                                <input type="text"
                                       name="aksara_besar"
                                       id="inputAksaraBesar"
                                       class="form-control radius-8 fw-bold text-uppercase"
                                       placeholder="Contoh: MIN 1 CIAMIS"
                                       required>
                            </div>

                            <div>
                                <label class="form-label fw-bold text-xs text-secondary-light mb-4">
                                    4. Teks Baris Domain / Sub-Judul Renggang
                                </label>
                                <input type="text"
                                       name="teks_domain"
                                       id="inputTeksDomain"
                                       class="form-control form-control-sm radius-8 font-monospace text-xs"
                                       value="w w w . m i n 1 c i a m i s . s c h . i d"
                                       placeholder="w w w . m i n 1 c i a m i s . s c h . i d">
                            </div>
                        </div>

                        <!-- 2 Baris Keterangan Slogan (Tanpa Badge Hijau) -->
                        <div class="p-14 radius-12 bg-neutral-50 border mb-16">
                            <div class="mb-10">
                                <label class="form-label fw-bold text-xs mb-4">
                                    5. Keterangan Slogan Baris 1 <span class="text-danger-600">*</span>
                                </label>
                                <input type="text"
                                       name="keterangan_1"
                                       id="inputKeterangan1"
                                       class="form-control form-control-sm radius-8 fw-semibold"
                                       placeholder="Contoh: Bertaqwa ~ Berakhlaq Mulia ~ Unggul Prestasi"
                                       required>
                            </div>

                            <div>
                                <label class="form-label fw-bold text-xs mb-4">
                                    6. Keterangan Slogan Baris 2 (Opsional)
                                </label>
                                <input type="text"
                                       name="keterangan_2"
                                       id="inputKeterangan2"
                                       class="form-control form-control-sm radius-8 fw-semibold"
                                       placeholder="Contoh: Cakap Berteknologi ~ Berwawasan Global ~ Peduli Lingkungan">
                            </div>
                        </div>

                        <!-- Kotak Upload Foto (Otomatis Sembunyi Jika Pilih Mode Tanpa Foto) -->
                        <div id="boxFotoHeroAdd" class="mb-14">
                            <label class="form-label fw-bold text-xs mb-6">
                                7. Foto Utama / Kolase Siswa (Sisi Kanan Slide)
                            </label>
                            <div class="p-10 radius-12 bg-neutral-50 border text-center mb-10">
                                <img id="previewAddBanner"
                                     src="{{ asset('frontend/landing/hero-collage-students.png') }}"
                                     alt="Preview"
                                     class="w-100 radius-8 mb-8 border bg-white"
                                     style="height: 145px; object-fit: contain;"
                                     onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                                <input type="file"
                                       name="gambar"
                                       class="form-control form-control-sm radius-8"
                                       accept="image/*"
                                       onchange="tampilkanPreview(this, 'previewAddBanner')">
                            </div>

                            <div class="p-10 radius-8 bg-warning-focus border border-warning">
                                <div class="form-check form-switch switch-warning d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="tampilkan_stiker" id="chkStickerAdd" checked>
                                    <label class="form-check-label text-xs fw-bold" for="chkStickerAdd">
                                        🏅 Tampilkan Stiker "Tiada Hari Tanpa Prestasi" di Pojok Foto
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="p-12 radius-8 bg-neutral-50 border mb-16">
                            <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="chkActiveAdd" checked>
                                <label class="form-check-label text-xs fw-bold" for="chkActiveAdd">
                                    ✓ Aktifkan Slide Ini di Halaman Depan
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold shadow-sm">
                            <i class="ph-bold ph-floppy-disk me-1"></i> Simpan Slide Hero
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (7 KOLOM): DAFTAR SLIDE HERO TERPASANG                         -->
        <!-- ========================================================================= -->
        <div class="col-lg-7">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-md fw-bold mb-0">Daftar Slide Hero Terpasang ({{ $banners->count() }} Slide)</h6>
                        <span class="text-xs text-secondary-light">Klik <b>✎ Edit Slide</b> untuk mengubah teks atau mengganti mode Teks Tengah / Dengan Foto</span>
                    </div>
                </div>

                <div class="card-body p-20">
                    <div class="d-flex flex-column gap-3">
                        @forelse ($banners as $b)
                            @php
                                $isTanpaFoto = ($b->tipe_layout === 'tanpa_foto');
                            @endphp
                            <div class="card radius-12 border overflow-hidden shadow-sm">
                                <div class="row g-0 align-items-center">
                                    <!-- Pratinjau Tipografi Hero -->
                                    <div class="{{ $isTanpaFoto ? 'col-md-8 text-center' : 'col-md-7' }} p-20 bg-white">
                                        <div class="d-flex align-items-center {{ $isTanpaFoto ? 'justify-content-center' : '' }} gap-2 mb-8">
                                            <span class="bg-dark text-white px-10 py-2 rounded-pill text-xs fw-bold">
                                                Slide #{{ $b->urutan }}
                                            </span>
                                            <span class="bg-primary-50 text-primary-600 border border-primary-200 px-10 py-2 rounded-pill text-xs fw-bold">
                                                {{ $isTanpaFoto ? '🎯 Teks Tengah (Tanpa Foto)' : '🖼️ Teks Kiri + Foto Kanan' }}
                                            </span>
                                            <span class="{{ $b->is_active ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-10 py-2 rounded-pill text-xs fw-bold">
                                                {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>

                                        <div class="preview-aksara-kecil">{{ $b->aksara_kecil ?: 'Selamat datang di' }}</div>
                                        <div class="preview-aksara-besar mb-1">{{ $b->aksara_besar ?: $b->judul }}</div>
                                        <div class="preview-domain-line mb-12">{{ $b->teks_domain }}</div>

                                        <div class="p-10 radius-8 bg-neutral-50 border text-xs fst-italic fw-bold text-secondary-light" style="line-height: 1.45;">
                                            <div>{{ $b->keterangan_1 ?: $b->keterangan }}</div>
                                            @if (!empty($b->keterangan_2))
                                                <div>{{ $b->keterangan_2 }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Kanan: Info Foto / Aksi -->
                                    <div class="{{ $isTanpaFoto ? 'col-md-4' : 'col-md-5' }} p-16 bg-neutral-50 border-start text-center d-flex flex-column justify-content-center h-100">
                                        @if ($isTanpaFoto)
                                            <div class="py-16 px-10 mb-12 radius-8 border border-dashed bg-white text-secondary-light text-xs">
                                                <i class="ph-bold ph-text-align-center text-2xl d-block mb-1 text-primary-600"></i>
                                                <b>Mode Teks Tengah Tunggal</b><br>
                                                <span>Tampil besar di tengah layar tanpa gambar samping</span>
                                            </div>
                                        @else
                                            <img src="{{ $b->gambar_url }}"
                                                 alt="{{ $b->aksara_besar }}"
                                                 class="w-100 radius-8 border bg-white mb-12"
                                                 style="height: 140px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                                        @endif

                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <button type="button"
                                                    class="btn btn-sm btn-success-600 radius-8 px-14 py-6 text-xs fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBannerModal{{ $b->id }}">
                                                ✎ Edit Slide
                                            </button>
                                            <button type="button"
                                                    onclick="konfirmasiHapusBanner('{{ route('banner-carousel.destroy', $b->id) }}', 'Slide #{{ $b->urutan }} ({{ addslashes($b->aksara_besar) }})')"
                                                    class="btn btn-sm btn-outline-danger-600 radius-8 px-12 py-6 text-xs fw-bold">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-32 text-secondary-light">
                                Belum ada slide Hero Carousel.
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
    <!-- MODAL EDIT SLIDE HERO CAROUSEL                                            -->
    <!-- ========================================================================= -->
    @foreach ($banners as $b)
        @php
            $isEditTanpaFoto = ($b->tipe_layout === 'tanpa_foto');
        @endphp
        <div class="modal fade" id="editBannerModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Edit Slide Hero #{{ $b->urutan }}: {{ $b->aksara_besar }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('banner-carousel.update', $b->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-xs mb-4">1. Pilih Tata Letak / Mode Slide <span class="text-danger-600">*</span></label>
                                    <select name="tipe_layout" class="form-select radius-8 fw-bold border-success" onchange="toggleKotakFotoHero('Edit{{ $b->id }}', this.value)">
                                        <option value="tanpa_foto" {{ $isEditTanpaFoto ? 'selected' : '' }}>🎯 Teks Tengah Diperbesar (Tanpa Foto)</option>
                                        <option value="dengan_foto" {{ !$isEditTanpaFoto ? 'selected' : '' }}>🖼️ Teks di Kiri + Foto Kolase di Kanan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-xs mb-4">Urutan Slide</label>
                                    <input type="number" name="urutan" class="form-control radius-8 fw-bold text-center" value="{{ $b->urutan }}" min="1" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">2. Aksara Kecil (Tulisan Sambung) <span class="text-danger-600">*</span></label>
                                    <input type="text" name="aksara_kecil" class="form-control radius-8 fw-semibold" value="{{ $b->aksara_kecil }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">3. Aksara Besar (Judul Utama) <span class="text-danger-600">*</span></label>
                                    <input type="text" name="aksara_besar" class="form-control radius-8 fw-bold text-uppercase" value="{{ $b->aksara_besar }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold text-xs mb-4">4. Teks Baris Domain / Sub-Judul</label>
                                    <input type="text" name="teks_domain" class="form-control radius-8 font-monospace text-xs" value="{{ $b->teks_domain }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">5. Keterangan Slogan Baris 1 <span class="text-danger-600">*</span></label>
                                    <input type="text" name="keterangan_1" class="form-control radius-8 fw-semibold" value="{{ $b->keterangan_1 }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">6. Keterangan Slogan Baris 2</label>
                                    <input type="text" name="keterangan_2" class="form-control radius-8 fw-semibold" value="{{ $b->keterangan_2 }}">
                                </div>

                                <!-- Box Foto Edit (Otomatis sembunyi jika pilih Tanpa Foto) -->
                                <div class="col-12 {{ $isEditTanpaFoto ? 'd-none' : '' }}" id="boxFotoHeroEdit{{ $b->id }}">
                                    <div class="p-12 radius-12 bg-neutral-50 border text-center mb-10">
                                        <img id="previewEditBanner{{ $b->id }}"
                                             src="{{ $b->gambar_url }}"
                                             alt="Preview"
                                             class="radius-8 mb-8 border bg-white"
                                             style="height: 150px; max-width: 100%; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                                        <input type="file"
                                               name="gambar"
                                               class="form-control form-control-sm radius-8"
                                               accept="image/*"
                                               onchange="tampilkanPreview(this, 'previewEditBanner{{ $b->id }}')">
                                    </div>
                                    <div class="form-check form-switch switch-warning d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" role="switch" name="tampilkan_stiker" id="chkStickerEdit{{ $b->id }}" {{ $b->tampilkan_stiker ? 'checked' : '' }}>
                                        <label class="form-check-label text-xs fw-bold" for="chkStickerEdit{{ $b->id }}">🏅 Tampilkan Stiker "Tiada Hari Tanpa Prestasi"</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="chkActiveEdit{{ $b->id }}" {{ $b->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label text-xs fw-bold" for="chkActiveEdit{{ $b->id }}">✓ Tampilkan Slide Ini di Halaman Depan</label>
                                    </div>
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
    @endforeach

    <script>
        function toggleKotakFotoHero(suffix, nilai) {
            var box = document.getElementById('boxFotoHero' + suffix);
            if (!box) return;
            if (nilai === 'tanpa_foto') {
                box.classList.add('d-none');
            } else {
                box.classList.remove('d-none');
            }
        }

        function generateBannerAi() {
            Swal.fire({
                title: '✨ Buat Tagline Banner AI',
                input: 'text',
                inputLabel: 'Masukkan topik atau tema banner (contoh: Prestasi Sains & Tahfidz, Madrasah Digital, PPDB):',
                inputValue: 'Pendidikan Karakter Qurani dan Prestasi Unggul',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#64748b',
                confirmButtonText: '⚡ Generate AI',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: function (tema) {
                    return fetch("{{ route('banner-carousel.generateAi') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ tema: tema })
                    })
                    .then(function (res) {
                        if (!res.ok) throw new Error(res.statusText);
                        return res.json();
                    })
                    .catch(function (error) {
                        Swal.showValidationMessage('Gagal generate: ' + error);
                    });
                },
                allowOutsideClick: function () { return !Swal.isLoading(); }
            }).then(function (result) {
                if (result.isConfirmed && result.value && result.value.status === 'success') {
                    var d = result.value.data;
                    if (d.aksara_kecil) document.getElementById('inputAksaraKecil').value = d.aksara_kecil;
                    if (d.aksara_besar) document.getElementById('inputAksaraBesar').value = d.aksara_besar;
                    if (d.teks_domain) document.getElementById('inputTeksDomain').value = d.teks_domain;
                    if (d.deskripsi) document.getElementById('inputKeterangan1').value = d.deskripsi;
                    Swal.fire({
                        icon: 'success',
                        title: 'Tagline Banner Siap!',
                        text: 'Teks banner berhasil disusun oleh ' + (result.value.source || 'AI Agent') + '.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        function tampilkanPreview(input, targetImgId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById(targetImgId);
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function konfirmasiHapusBanner(url, label) {
            Swal.fire({
                title: 'Hapus ' + label + '?',
                text: 'Slide ini akan dihapus dari Hero Carousel halaman depan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function (res) {
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
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: @json(session('success')), timer: 2000, showConfirmButton: false });
            });
        </script>
    @endif

@endsection