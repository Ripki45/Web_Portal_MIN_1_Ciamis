@extends('layouts.admin')

@section('title', 'Manajemen Banner PPDB & Popup Modal - MIN 1 Ciamis')

@section('content')

    <!-- Font Awesome 6.5.1 untuk Preview Ikon & Modal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .ppdb-preview-card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 2px dashed #cbd5e1;
            transition: all 0.25s ease;
        }
        .ppdb-preview-card:hover {
            border-color: #0d9488;
            box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.12);
        }
        .ppdb-banner-img-preview {
            width: 100%;
            height: auto;
            aspect-ratio: 1600 / 580;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }
        .mock-modal-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
            padding: 24px;
            position: relative;
        }
        .mock-modal-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>

    <!-- Header & Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Banner PPDB &amp; Popup Modal Interaktif</h6>
            <span class="text-xs text-secondary-light">
                Kustomisasi gambar banner landscape PPDB, target link, serta popup modal pengumuman pendaftaran siswa baru.
            </span>
        </div>
        <a href="{{ url('/') }}#ppdb" target="_blank"
            class="btn btn-outline-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Hasil di Halaman Depan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show radius-8 mb-24" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-bold ph-check-circle text-lg"></i>
                <span class="fw-semibold text-sm">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('banner-ppdb.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <!-- ========================================================================= -->
            <!-- KOLOM KIRI (6 KOLOM): PENGATURAN BANNER IKLAN PPDB (LANDSCAPE)            -->
            <!-- ========================================================================= -->
            <div class="col-lg-6">
                <div class="card radius-12 border shadow-sm h-100">
                    <div class="card-header border-bottom bg-base py-16 px-20 d-flex align-items-center justify-content-between">
                        <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                            <i class="ph-bold ph-image text-lg"></i> 1. Banner Iklan PPDB (Landscape)
                        </h6>
                        <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-xs px-10 py-4 radius-6 fw-bold">
                            Rasio 1600 x 580 px
                        </span>
                    </div>

                    <div class="card-body p-20">

                        <!-- Toggle Status Banner Aktif -->
                        <div class="p-14 radius-12 bg-light border mb-20 d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fw-bold text-sm text-dark d-block" for="is_active_banner">
                                    Tampilkan Banner PPDB di Beranda
                                </label>
                                <span class="text-xs text-secondary-light">Jika dinonaktifkan, seksi banner PPDB tidak akan muncul di landing page.</span>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active_banner" name="is_active" value="1"
                                    {{ $bannerPpdb->is_active ? 'checked' : '' }} style="width: 2.7em; height: 1.4em;">
                            </div>
                        </div>

                        <!-- Preview Banner PPDB -->
                        <div class="mb-20">
                            <label class="form-label fw-bold text-xs text-secondary-light mb-6 d-block">
                                PREVIEW BANNER PPDB SAAT INI:
                            </label>
                            <div class="ppdb-preview-card p-10 text-center">
                                <img src="{{ $bannerPpdb->gambar_url }}" alt="Preview Banner PPDB" id="previewImgBanner" class="ppdb-banner-img-preview">
                            </div>
                        </div>

                        <!-- Upload File Banner -->
                        <div class="mb-16">
                            <label class="form-label fw-bold text-xs mb-4">Ganti Gambar Banner Landscape</label>
                            <input type="file" name="gambar" id="inputFotoBanner" class="form-control radius-8" accept="image/*"
                                onchange="previewUploadBanner(this)">
                            <div class="form-text text-xs text-secondary-light mt-1">
                                Format: JPG, PNG, WEBP, atau SVG. Rekomendasi resolusi: <b>1600 x 580 pixel</b> (Maks 5MB).
                            </div>
                        </div>

                        @if (!empty($bannerPpdb->gambar))
                            <div class="form-check mb-16">
                                <input class="form-check-input" type="checkbox" name="hapus_gambar" value="1" id="hapus_gambar">
                                <label class="form-check-label text-xs fw-semibold text-danger" for="hapus_gambar">
                                    Hapus gambar kustom ini (kembalikan ke desain banner SVG default)
                                </label>
                            </div>
                        @endif

                        <!-- Aksi Saat Banner Diklik -->
                        <div class="p-14 radius-12 bg-primary-50 border border-primary-200 mb-16">
                            <label class="form-label fw-bold text-xs text-primary-600 mb-8 d-block">
                                Aksi Saat Banner Diklik Pengunjung:
                            </label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe_link" id="tipe_link_popup" value="popup"
                                        {{ $bannerPpdb->tipe_link === 'popup' ? 'checked' : '' }} onchange="toggleTipeLink(this.value)">
                                    <label class="form-check-label text-xs fw-bold text-dark" for="tipe_link_popup">
                                        🎯 Buka Popup Modal PPDB (Rekomendasi - Tampil interaktif di layar)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe_link" id="tipe_link_url" value="url"
                                        {{ $bannerPpdb->tipe_link === 'url' ? 'checked' : '' }} onchange="toggleTipeLink(this.value)">
                                    <label class="form-check-label text-xs fw-bold text-dark" for="tipe_link_url">
                                        🔗 Buka Tautan Eksternal (Google Form / WhatsApp / Website Luar)
                                    </label>
                                </div>
                            </div>

                            <div class="mt-12" id="kotakLinkUrl" style="{{ $bannerPpdb->tipe_link === 'url' ? 'display:block;' : 'display:none;' }}">
                                <label class="form-label fw-bold text-xs text-secondary-light mb-4">Masukkan URL Tujuan:</label>
                                <input type="text" name="link_url" class="form-control radius-8"
                                    value="{{ old('link_url', $bannerPpdb->link_url) }}" placeholder="https://forms.gle/... atau https://wa.me/...">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- KOLOM KANAN (6 KOLOM): PENGATURAN POPUP MODAL & BROSUR PPDB               -->
            <!-- ========================================================================= -->
            <div class="col-lg-6">
                <div class="card radius-12 border shadow-sm h-100">
                    <div class="card-header border-bottom bg-base py-16 px-20 d-flex align-items-center justify-content-between">
                        <h6 class="text-md fw-bold mb-0 text-success-main d-flex align-items-center gap-2">
                            <i class="ph-bold ph-megaphone-simple text-lg"></i> 2. Popup Modal &amp; Info Pendaftaran
                        </h6>
                        <span class="badge bg-success-focus text-success-main border border-success text-xs px-10 py-4 radius-6 fw-bold">
                            Custom Popup
                        </span>
                    </div>

                    <div class="card-body p-20">

                        <!-- Toggle Status Popup & Auto-Show -->
                        <div class="row g-2 mb-20">
                            <div class="col-sm-6">
                                <div class="p-12 radius-12 bg-light border h-100 d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="form-check-label fw-bold text-xs text-dark d-block" for="is_active_popup">
                                            Aktifkan Popup Modal
                                        </label>
                                        <span class="text-xs text-secondary-light">Bisa dibuka via klik banner</span>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active_popup" name="is_active_popup" value="1"
                                            {{ $bannerPpdb->is_active_popup ? 'checked' : '' }} style="width: 2.4em; height: 1.3em;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-12 radius-12 bg-warning-focus border border-warning h-100 d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="form-check-label fw-bold text-xs text-warning-main d-block" for="auto_popup">
                                            ⚡ Muncul Otomatis
                                        </label>
                                        <span class="text-xs text-secondary-light">Pop-up langsung saat buka web</span>
                                    </div>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="auto_popup" name="auto_popup" value="1"
                                            {{ $bannerPpdb->auto_popup ? 'checked' : '' }} style="width: 2.4em; height: 1.3em;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Badge Label & Judul Modal -->
                        <div class="row g-2 mb-14">
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-xs mb-4">Badge / Label Atas</label>
                                <input type="text" name="popup_badge" id="inputBadge" class="form-control radius-8 fw-semibold"
                                    value="{{ old('popup_badge', $bannerPpdb->popup_badge) }}" placeholder="Contoh: PPDB ONLINE T.A 2026/2027"
                                    onkeyup="updateMockModal()">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-bold text-xs mb-4">Judul Popup Modal <span class="text-danger-600">*</span></label>
                                <input type="text" name="popup_judul" id="inputJudulModal" class="form-control radius-8 fw-bold"
                                    value="{{ old('popup_judul', $bannerPpdb->popup_judul) }}" placeholder="Informasi & Pendaftaran PPDB MIN 1 Ciamis"
                                    required onkeyup="updateMockModal()">
                            </div>
                        </div>

                        <!-- Foto Brosur / Flyer PPDB -->
                        <div class="mb-14 p-12 radius-12 bg-light border">
                            <label class="form-label fw-bold text-xs text-dark mb-4 d-block">
                                🖼️ Brosur / Flyer PPDB di Dalam Modal (Opsional)
                            </label>
                            @if (!empty($bannerPpdb->popup_gambar))
                                <div class="mb-10 text-center">
                                    <img src="{{ $bannerPpdb->popup_gambar_url }}" alt="Brosur PPDB"
                                         style="max-height: 140px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                </div>
                                <div class="form-check mb-8">
                                    <input class="form-check-input" type="checkbox" name="hapus_popup_gambar" value="1" id="hapus_popup_gambar">
                                    <label class="form-check-label text-xs text-danger fw-semibold" for="hapus_popup_gambar">
                                        Hapus foto brosur saat ini
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="popup_gambar" class="form-control radius-8" accept="image/*">
                            <div class="form-text text-xs text-secondary-light mt-1">Upload flyer resmi madrasah jika ada (Maks 5MB).</div>
                        </div>

                        <!-- Isi Uraian Konten Popup -->
                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-4">Isi Konten / Pengumuman PPDB <span class="text-danger-600">*</span></label>
                            <textarea name="popup_konten" id="inputKontenModal" rows="6" class="form-control radius-8"
                                placeholder="Jelaskan mengenai jadwal pendaftaran, syarat berkas, dan keunggulan madrasah..."
                                onkeyup="updateMockModal()">{{ old('popup_konten', $bannerPpdb->popup_konten) }}</textarea>
                        </div>

                        <!-- Tombol Aksi 1: Pendaftaran Online -->
                        <div class="p-12 radius-12 bg-primary-50 border border-primary-200 mb-14">
                            <span class="fw-bold text-xs text-primary-600 d-block mb-8">📝 Tombol Aksi Utama (Formulir Online)</span>
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label class="form-label text-xs fw-semibold mb-2">Teks Tombol</label>
                                    <input type="text" name="popup_tombol_teks" class="form-control radius-8"
                                        value="{{ old('popup_tombol_teks', $bannerPpdb->popup_tombol_teks) }}" placeholder="Daftar Online Sekarang">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label text-xs fw-semibold mb-2">URL Tujuan Formulir</label>
                                    <input type="text" name="popup_tombol_url" class="form-control radius-8"
                                        value="{{ old('popup_tombol_url', $bannerPpdb->popup_tombol_url) }}" placeholder="https://forms.gle/... atau https://wa.me/...">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi 2: WhatsApp Panitia PPDB -->
                        <div class="p-12 radius-12 bg-success-focus border border-success mb-14">
                            <span class="fw-bold text-xs text-success-main d-block mb-8">💬 Tombol WhatsApp Panitia PPDB</span>
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label class="form-label text-xs fw-semibold mb-2">Nomor WhatsApp</label>
                                    <input type="text" name="popup_wa_nomor" class="form-control radius-8"
                                        value="{{ old('popup_wa_nomor', $bannerPpdb->popup_wa_nomor) }}" placeholder="082215650442">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label text-xs fw-semibold mb-2">Pesan Pembuka Otomatis</label>
                                    <input type="text" name="popup_wa_teks" class="form-control radius-8"
                                        value="{{ old('popup_wa_teks', $bannerPpdb->popup_wa_teks) }}" placeholder="Halo Panitia PPDB MIN 1 Ciamis...">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- SUBMIT BAR (STICKY BUTTON DI BAWAH)                                       -->
            <!-- ========================================================================= -->
            <div class="col-12">
                <div class="card radius-12 border bg-base shadow-sm p-16 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ph-bold ph-shield-check text-xl text-primary-600"></i>
                        <span class="text-xs text-secondary-light">
                            Perubahan akan langsung meng-update tampilan halaman depan secara atomik dengan cache otomatis dibersihkan.
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary-600 radius-8 px-24 py-10 fw-bold d-inline-flex align-items-center gap-2 text-sm">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Pengaturan Banner &amp; Popup PPDB
                    </button>
                </div>
            </div>

        </div>
    </form>

    <script>
        function previewUploadBanner(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImgBanner').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleTipeLink(val) {
            var box = document.getElementById('kotakLinkUrl');
            if (val === 'url') {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }
    </script>

@endsection
