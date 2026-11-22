@extends('layouts.frontend')

@section('title', $program->judul . ' — MIN 1 Ciamis')

@section('content')

    <!-- =========================================================================
         STYLE KHUSUS DETAIL PROGRAM UNGGULAN (MIN 1 CIAMIS MODERN THEME)
         ========================================================================= -->
    <style>
        .sra-page-wrap {
            background: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        /* Breadcrumb Bar */
        .sra-breadcrumb-nav {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 0;
            font-size: 0.85rem;
        }
        .sra-breadcrumb-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .sra-breadcrumb-list a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .sra-breadcrumb-list a:hover {
            color: #1B9C85;
            text-decoration: underline;
        }

        /* ==========================================================================
           1. HERO SECTION (GRID 1.15 : 1 DENGAN MINI CARDS & OFFICIAL PHOTO)
           ========================================================================== */
        .sra-hero-section {
            padding: 60px 0 50px;
            background: #ffffff;
        }
        .sra-hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 45px;
            align-items: center;
        }
        .sra-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(13, 106, 86, 0.08);
            color: #0D6A56;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 6px 14px;
            border-radius: 50px;
            margin-bottom: 18px;
            border: 1px solid rgba(13, 106, 86, 0.18);
        }
        .sra-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.35rem;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 18px;
            font-weight: 800;
        }
        .sra-hero-title span {
            color: #0D6A56;
        }
        .sra-quote-box {
            background: #fafcfb;
            border-left: 4px solid #0D6A56;
            padding: 20px 22px;
            border-radius: 0 14px 14px 0;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border-top: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }
        .sra-quote-text {
            color: #1e293b;
            font-size: 1.02rem;
            line-height: 1.7;
            margin: 0;
            font-weight: 600;
            font-style: italic;
        }
        .sra-hero-narrative {
            color: #475569;
            font-size: 0.96rem;
            line-height: 1.8;
            margin-bottom: 24px;
            text-align: justify;
        }
        .sra-hero-narrative p {
            margin-bottom: 12px;
        }
        .sra-mini-features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .sra-mini-feature-card {
            background: #fafcfb;
            border: 1px solid #e2e8f0;
            border-top: 3px solid #0D6A56;
            padding: 16px;
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        .sra-mini-feature-card:hover {
            border-color: #0D6A56;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(13, 106, 86, 0.1);
        }
        .sra-mini-feature-icon {
            color: #0D6A56;
            font-size: 1.35rem;
            margin-bottom: 8px;
        }
        .sra-mini-feature-title {
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 800;
            display: block;
            margin-bottom: 4px;
            font-family: 'Outfit', sans-serif;
        }
        .sra-mini-feature-desc {
            color: #64748b;
            font-size: 0.84rem;
            line-height: 1.5;
            display: block;
        }

        .sra-image-frame {
            background: #ffffff;
            padding: 10px;
            border-radius: 22px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            position: relative;
        }
        .sra-image-frame img {
            width: 100%;
            height: auto;
            max-height: 420px;
            object-fit: cover;
            border-radius: 16px;
            display: block;
        }
        .sra-image-caption {
            padding: 12px 6px 4px;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* ==========================================================================
           2. 4 PILAR UTAMA PROGRAM (CARD GRID DENGAN WATERMARK ANGKA)
           ========================================================================== */
        .sra-pilar-section {
            padding: 70px 0;
            background: #fafcfb;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .sra-section-header {
            text-align: center;
            margin-bottom: 45px;
        }
        .sra-section-subtitle {
            color: #0D6A56;
            font-weight: 800;
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
        }
        .sra-section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.25rem;
            color: #0f172a;
            margin: 10px 0;
            font-weight: 800;
        }
        .sra-section-desc {
            color: #64748b;
            max-width: 680px;
            margin: 0 auto;
            font-size: 0.98rem;
            line-height: 1.6;
        }
        .sra-section-divider {
            width: 60px;
            height: 3px;
            background: #0D6A56;
            margin: 18px auto 0;
            border-radius: 3px;
        }

        .sra-pilar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }
        .sra-pilar-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            border-top: 4px solid #0D6A56;
            transition: all 0.28s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .sra-pilar-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(13, 106, 86, 0.12);
            border-color: #0D6A56;
        }
        .sra-pilar-watermark {
            position: absolute;
            top: 12px;
            right: 18px;
            font-family: 'Outfit', sans-serif;
            font-size: 3.2rem;
            font-weight: 900;
            color: rgba(13, 106, 86, 0.08);
            line-height: 1;
            pointer-events: none;
            user-select: none;
        }
        .sra-pilar-icon-box {
            background: rgba(13, 106, 86, 0.1);
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0D6A56;
            font-size: 1.45rem;
            margin-bottom: 18px;
        }
        .sra-pilar-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.18rem;
            color: #0f172a;
            margin-bottom: 10px;
            font-weight: 800;
        }
        .sra-pilar-card-desc {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.65;
            margin: 0;
        }

        /* ==========================================================================
           3. SECTION 3: AKTIVITAS & EDUKASI (GRID 3 KOLOM x 2 BARIS MODEL MIN 1)
           ========================================================================== */
        .sra-activities-section {
            padding: 70px 0 85px;
            background: #ffffff;
        }
        .sra-kegiatan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 45px;
        }
        .sra-kegiatan-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 26px 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02);
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }
        .sra-kegiatan-card:hover {
            transform: translateY(-4px);
            border-color: #0D6A56;
            box-shadow: 0 16px 32px rgba(13, 106, 86, 0.09);
        }
        .sra-kegiatan-badge {
            background: #e6f4f1;
            color: #0D6A56;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 14px;
            border: 1px solid rgba(13, 106, 86, 0.15);
        }
        .sra-kegiatan-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.08rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.45;
            margin-bottom: 10px;
        }
        .sra-kegiatan-desc {
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.65;
            margin: 0;
        }

        /* Bottom Section: Footer Actions & Madrasah Info */
        .sra-bottom-section {
            background: #fafcfb;
            border-top: 1px solid #e2e8f0;
            padding: 60px 0 70px;
        }

        /* Dark Mode Theme Compatibility */
        [data-bs-theme="dark"] .sra-page-wrap {
            background: #0b1519;
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .sra-breadcrumb-nav {
            background: #101c21;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .sra-breadcrumb-list a {
            color: #34d399;
        }
        [data-bs-theme="dark"] .sra-hero-section,
        [data-bs-theme="dark"] .sra-activities-section {
            background: #0b1519;
        }
        [data-bs-theme="dark"] .sra-hero-title,
        [data-bs-theme="dark"] .sra-section-title,
        [data-bs-theme="dark"] .sra-kegiatan-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .sra-hero-title span,
        [data-bs-theme="dark"] .sra-section-subtitle {
            color: #34d399;
        }
        [data-bs-theme="dark"] .sra-quote-box {
            background: #132228;
            border-left-color: #34d399;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .sra-quote-text {
            color: #a7f3d0;
        }
        [data-bs-theme="dark"] .sra-hero-narrative,
        [data-bs-theme="dark"] .sra-section-desc,
        [data-bs-theme="dark"] .sra-kegiatan-desc {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .sra-mini-feature-card,
        [data-bs-theme="dark"] .sra-pilar-card,
        [data-bs-theme="dark"] .sra-kegiatan-card {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .sra-kegiatan-badge {
            background: rgba(52, 211, 153, 0.15);
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.25);
        }
        [data-bs-theme="dark"] .sra-mini-feature-icon {
            color: #34d399;
        }
        [data-bs-theme="dark"] .sra-mini-feature-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .sra-image-frame {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.1);
        }
        [data-bs-theme="dark"] .sra-pilar-section,
        [data-bs-theme="dark"] .sra-bottom-section {
            background: #0e191e;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .sra-pilar-watermark {
            color: rgba(52, 211, 153, 0.08);
        }
        [data-bs-theme="dark"] .sra-pilar-card-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .sra-pilar-icon-box {
            background: rgba(52, 211, 153, 0.15);
            color: #34d399;
        }
        [data-bs-theme="dark"] .sra-section-divider {
            background: #34d399;
        }

        /* Responsive Mobile Layout */
        @media (max-width: 991.98px) {
            .sra-hero-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .sra-hero-title {
                font-size: 2rem;
            }
            .sra-mini-features-grid {
                grid-template-columns: 1fr;
            }
            .sra-kegiatan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }
        }
        @media (max-width: 767.98px) {
            .sra-kegiatan-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .sra-section-title {
                font-size: 1.85rem;
            }
        }
    </style>

    <div class="sra-page-wrap">

        <!-- ==========================================
             BREADCRUMB BAR
             ========================================== -->
        <div class="sra-breadcrumb-nav">
            <div class="container">
                <ul class="sra-breadcrumb-list">
                    <li>
                        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i></li>
                    <li>
                        <a href="{{ route('program-unggulan.publik') }}"><i class="fas fa-star"></i> Program Unggulan</a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i></li>
                    <li class="text-dark fw-bold">{{ $program->judul }}</li>

                    @auth
                        <li class="ms-auto">
                            <a href="{{ route('program-unggulan.edit', $program->id) }}" class="badge bg-dark text-white text-decoration-none px-10 py-6 radius-6">
                                ✎ Edit Program
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>

        <!-- =========================================================================
             1. SECTION 1: JUDUL & HERO PROGRAM UNGGULAN
             ========================================================================= -->
        <section class="sra-hero-section">
            <div class="container">
                <div class="sra-hero-grid">
                    <div>
                        <!-- Badge Hijau dengan Icon Font Awesome -->
                        <div class="sra-hero-badge">
                            <i class="{{ $program->icon }}"></i> {{ $program->kategori_badge }} • Unggulan Madrasah
                        </div>

                        <!-- Judul Program Unggulan Warna Hitam & Hijau -->
                        @php
                            $kataJudul = explode(' ', $program->judul);
                            $totalKata = count($kataJudul);
                            if ($totalKata > 2) {
                                $bagianHitam = implode(' ', array_slice($kataJudul, 0, (int) ceil($totalKata / 2)));
                                $bagianHijau = implode(' ', array_slice($kataJudul, (int) ceil($totalKata / 2)));
                            } else {
                                $bagianHitam = $kataJudul[0] ?? '';
                                $bagianHijau = $kataJudul[1] ?? '';
                            }
                        @endphp
                        <h1 class="sra-hero-title">
                            {{ $bagianHitam }} <span>{{ $bagianHijau }}</span>
                        </h1>

                        <!-- Blockquote Komitmen -->
                        @if (!empty($program->kutipan))
                            <div class="sra-quote-box">
                                <blockquote class="sra-quote-text">
                                    “{{ $program->kutipan }}”
                                </blockquote>
                            </div>
                        @else
                            <div class="sra-quote-box">
                                <blockquote class="sra-quote-text">
                                    “MIN 1 Ciamis berkomitmen menjadi tempat yang ideal bagi anak-anak peserta didik dalam mengembangkan segenap potensi diri secara optimal.”
                                </blockquote>
                            </div>
                        @endif

                        <!-- Keterangan / Narasi Program (Quill.js Rich HTML) -->
                        <div class="sra-hero-narrative">
                            {!! $program->ringkasan ?: '<p>Melalui program unggulan madrasah, kami menciptakan iklim belajar yang aman, nyaman, berkualitas tinggi, serta mendukung pembentukan akhlak mulia dan kecakapan masa depan santri.</p>' !!}
                        </div>

                        <!-- Card untuk 2 Kolom Mini Features -->
                        @php
                            $miniCards = $program->mini_features_list;
                        @endphp
                        <div class="sra-mini-features-grid">
                            @foreach ($miniCards as $mc)
                                <div class="sra-mini-feature-card">
                                    <div class="sra-mini-feature-icon">
                                        <i class="{{ $mc['icon'] ?? 'fas fa-check-circle' }}"></i>
                                    </div>
                                    <strong class="sra-mini-feature-title">{{ $mc['title'] ?? 'Keunggulan' }}</strong>
                                    <span class="sra-mini-feature-desc">
                                        {{ $mc['desc'] ?? '' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Frame Foto Dokumentasi Resmi -->
                    <div>
                        <div class="sra-image-frame">
                            <img src="{{ $program->gambar_url }}" alt="{{ $program->judul }}" loading="lazy">
                            <div class="sra-image-caption">
                                <i class="fas fa-info-circle text-success"></i> Dokumentasi Gerakan {{ $program->judul }} MIN 1 Ciamis
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =========================================================================
             2. SECTION 2: 4 PILAR PROGRAM
             ========================================================================= -->
        <section class="sra-pilar-section">
            <div class="container">
                <div class="sra-section-header">
                    <span class="sra-section-subtitle">Fondasi &amp; Nilai Keunggulan</span>
                    <h2 class="sra-section-title">4 Pilar {{ $program->judul }}</h2>
                    <p class="sra-section-desc">
                        Pilar penguatan lingkungan belajar yang mendukung hak anak, kesehatan jasmani-rohani, penguasaan ilmu, dan sinergi keluarga.
                    </p>
                    <div class="sra-section-divider"></div>
                </div>

                @php
                    $pilars = $program->pilar_list_formatted;
                @endphp

                <div class="sra-pilar-grid">
                    @foreach ($pilars as $idx => $pilar)
                        <div class="sra-pilar-card">
                            <div class="sra-pilar-watermark">0{{ $idx + 1 }}</div>
                            <div>
                                <div class="sra-pilar-icon-box">
                                    <i class="{{ $pilar['icon'] ?? 'fas fa-award' }}"></i>
                                </div>
                                <h3 class="sra-pilar-card-title">{{ $pilar['title'] }}</h3>
                                <p class="sra-pilar-card-desc">{{ $pilar['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- =========================================================================
             3. SECTION 3: AKTIVITAS & EDUKASI (GRID 3 KOLOM x 2 BARIS MODEL MIN 1)
             ========================================================================= -->
        <section class="sra-activities-section">
            <div class="container">
                <!-- Section Header Terpusat -->
                <div class="sra-section-header">
                    <span class="sra-section-subtitle">{{ $program->kegiatan_badge_formatted }}</span>
                    <h2 class="sra-section-title">{{ $program->kegiatan_judul_formatted }}</h2>
                    <p class="sra-section-desc">
                        {{ $program->kegiatan_deskripsi_formatted }}
                    </p>
                    <div class="sra-section-divider"></div>
                </div>

                <!-- Grid 6 Kartu Aktivitas & Edukasi (3 Kolom x 2 Baris) -->
                @php
                    $kegiatanList = $program->kegiatan_list_data;
                @endphp
                <div class="sra-kegiatan-grid">
                    @foreach ($kegiatanList as $item)
                        <div class="sra-kegiatan-card">
                            <span class="sra-kegiatan-badge">{{ $item['badge'] ?? 'Kegiatan' }}</span>
                            <h3 class="sra-kegiatan-title">{{ $item['title'] ?? '' }}</h3>
                            <p class="sra-kegiatan-desc">{{ $item['desc'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Tag & Tombol Bagikan -->
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between p-3 bg-light rounded-12 border">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('program-unggulan.publik') }}" class="btn btn-outline-success btn-sm radius-8 fw-bold">
                            &larr; Semua Program Unggulan
                        </a>
                        <span class="badge bg-white text-dark border">
                            {{ $program->kategori_badge }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="text-dark fs-7 fw-semibold">Bagikan:</span>
                        <div class="d-inline-flex gap-2">
                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ urlencode($program->judul . ' - ' . request()->fullUrl()) }}"
                                target="_blank" class="btn btn-sm btn-outline-success radius-8" title="Bagikan ke WhatsApp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                target="_blank" class="btn btn-sm btn-outline-primary radius-8" title="Bagikan ke Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <!-- Salin Tautan -->
                            <button type="button" class="btn btn-sm btn-light border radius-8" onclick="salinTautanSRA()">
                                <i class="fas fa-link"></i> Salin
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- =========================================================================
             4. BOTTOM SECTION: PROGRAM UNGGULAN LAINNYA, DATA MADRASAH & KONSULTASI
             ========================================================================= -->
        <section class="sra-bottom-section" style="background: #fafcfb; border-top: 1px solid #e2e8f0; padding: 60px 0 70px;">
            <div class="container">
                <div class="row g-4">
                    <!-- Kolom 1: Profil & PPDB -->
                    <div class="col-lg-4 col-md-6">
                        <div class="author-card h-100 p-24 bg-white border radius-16 shadow-2xs text-center d-flex flex-column justify-content-between" style="border-radius: 18px; padding: 24px;">
                            <div>
                                <div class="card-img mb-3 text-center">
                                    <img class="rounded-circle avatar-154 p-3 bg-white shadow-sm"
                                        src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Logo MIN 1 Ciamis"
                                        style="object-fit: contain; width: 90px; height: 90px;" />
                                </div>
                                <h5 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 800;">MIN 1 Ciamis</h5>
                                <p class="mb-4 fs-7 text-muted">
                                    Madrasah Ibtidaiyah Negeri 1 Ciamis — Membina generasi Qur'ani, berakhlak mulia, unggul prestasi sains, dan adaptif terhadap teknologi.
                                </p>
                            </div>
                            <a href="{{ route('home') }}#banner-ppdb" class="btn btn-success w-100 button-effect-1"
                                style="background: #0D6A56; border-color: #0D6A56; font-weight: 700; border-radius: 12px;">
                                Informasi Pendaftaran (PPDB)
                            </a>
                        </div>
                    </div>

                    <!-- Kolom 2: Program Unggulan Lainnya -->
                    <div class="col-lg-4 col-md-6">
                        <div class="p-24 bg-white border radius-16 shadow-2xs h-100 d-flex flex-column" style="border-radius: 18px; padding: 24px;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="fas fa-star text-warning" style="font-size: 1.2rem;"></i>
                                <h5 class="mb-0 fw-bold" style="font-family: 'Outfit', sans-serif;">Program Unggulan Lainnya</h5>
                            </div>

                            <div class="d-flex flex-column gap-3 flex-grow-1">
                                @forelse ($programLain as $pl)
                                    <a href="{{ route('program-unggulan.detail', $pl->slug) }}"
                                       class="d-flex align-items-center gap-3 p-10 bg-light rounded-12 border text-decoration-none hover-up" style="border-radius: 12px; padding: 10px;">
                                        <img src="{{ $pl->gambar_url }}" alt="{{ $pl->judul }}"
                                             class="rounded-8 flex-shrink-0" style="width: 58px; height: 46px; object-fit: cover; border-radius: 8px;">
                                        <div>
                                            <span class="badge bg-white text-success fw-bold text-2xs mb-1 border">
                                                <i class="{{ $pl->icon }}"></i> {{ $pl->kategori_badge }}
                                            </span>
                                            <h6 class="fs-7 fw-bold text-dark mb-0 text-truncate" style="max-width: 180px;">
                                                {{ $pl->judul }}
                                            </h6>
                                        </div>
                                    </a>
                                @empty
                                    <p class="fs-7 text-muted">Belum ada program lainnya.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 3: Konsultasi & Data Real-Time -->
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex flex-column gap-3 h-100">
                            <!-- Konsultasi Program -->
                            <div class="p-20 radius-16" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px solid #a7f3d0; border-radius: 18px; padding: 20px;">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #0D6A56; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 17px;">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Konsultasi Program</h6>
                                        <span class="text-xs text-muted">Tim Humas &amp; Kurikulum</span>
                                    </div>
                                </div>
                                <p class="fs-8 text-secondary-light mb-3">
                                    Tertarik berkonsultasi mengenai kurikulum, pendampingan ramah anak, atau program tahfidz untuk putra-putri Anda?
                                </p>
                                <a href="{{ route('home') }}#kontak" class="btn btn-outline-success btn-sm w-100 fw-bold radius-8 py-2">
                                    <i class="fas fa-paper-plane"></i> Hubungi Kami
                                </a>
                            </div>

                            <!-- Data Real-Time Madrasah (Odometer) -->
                            @if (!empty($statMadrasah))
                                <div class="p-20 bg-white border radius-16 shadow-2xs flex-grow-1" style="border-radius: 18px; padding: 20px;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-chart-line text-success"></i>
                                        <h6 class="mb-0 fw-bold" style="font-family: 'Outfit', sans-serif;">Data Real-Time Madrasah</h6>
                                    </div>
                                    <ul class="list-unstyled d-flex flex-wrap gap-2 ps-0 mb-0">
                                        @foreach ($statMadrasah as $labelStat => $angkaStat)
                                            <li class="carouselTicker__item mx-0">
                                                <a href="#" class="tag-item link-effect-2">
                                                    <span class="text">
                                                        <span class="text1">{{ $labelStat }}</span>
                                                        <span class="text2">{{ $labelStat }}</span>
                                                    </span>
                                                    <span class="number">
                                                        <span class="odometer text-nowrap"
                                                            data-count="{{ $angkaStat }}"></span>
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
            </div>
        </section>

    </div>

    <!-- Script Salin Tautan -->
    <script>
        function salinTautanSRA() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('✓ Tautan halaman program berhasil disalin ke clipboard!');
                }).catch(function() {
                    prompt('Salin tautan ini:', window.location.href);
                });
            } else {
                prompt('Salin tautan ini:', window.location.href);
            }
        }
    </script>

@endsection
