@extends('layouts.frontend')

@section('title', $program->judul . ' — MIN 1 Ciamis')

@section('content')

    @php
        $miniCards = $program->mini_features_list;
        $pilars = $program->pilar_list_formatted;
        $kegiatanList = $program->kegiatan_list_data;

        $kataJudul = preg_split('/\s+/', trim($program->judul)) ?: [];
        $totalKata = count($kataJudul);
        if ($totalKata > 2) {
            $splitIndex = (int) ceil($totalKata / 2);
            $bagianHitam = implode(' ', array_slice($kataJudul, 0, $splitIndex));
            $bagianHijau = implode(' ', array_slice($kataJudul, $splitIndex));
        } else {
            $bagianHitam = $kataJudul[0] ?? $program->judul;
            $bagianHijau = $kataJudul[1] ?? '';
        }

        $bolehEdit = auth()->check() && in_array(
            strtolower((string) (auth()->user()->role ?? '')),
            ['superadmin', 'super_admin', 'admin'],
            true
        );
    @endphp

    <style>
        .sra-page-wrap {
            background: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

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
        .sra-breadcrumb-current {
            color: #0f172a;
            font-weight: 700;
        }

        /* 1. HERO SECTION */
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
            border: 1px solid #f1f5f9;
            border-left: 4px solid #0D6A56;
            padding: 20px 22px;
            border-radius: 0 14px 14px 0;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

        /* 2. PILAR SECTION */
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
            font-size: 2.2rem;
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            font-size: 1.15rem;
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

        /* 3. KEGIATAN & SHARE BAR */
        .sra-activities-section {
            padding: 64px 0 70px;
            background: #ffffff;
        }
        .sra-kegiatan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 42px;
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
        .sra-share-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        /* 4. BOTTOM SECTION */
        .sra-bottom-section {
            background: #fafcfb;
            border-top: 1px solid #e2e8f0;
            padding: 60px 0 70px;
        }
        .sra-bottom-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 24px;
            height: 100%;
        }
        .sra-related-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sra-related-item:hover {
            border-color: #0D6A56;
            transform: translateY(-2px);
        }
        .sra-related-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .sra-consult-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #a7f3d0;
            border-radius: 18px;
            padding: 20px;
        }

        /* Dark Mode */
        [data-bs-theme="dark"] .sra-page-wrap { background: #0b1519; color: #f1f5f9; }
        [data-bs-theme="dark"] .sra-breadcrumb-nav { background: #101c21; border-color: rgba(255, 255, 255, 0.08); }
        [data-bs-theme="dark"] .sra-breadcrumb-list a { color: #34d399; }
        [data-bs-theme="dark"] .sra-breadcrumb-current { color: #f1f5f9; }
        [data-bs-theme="dark"] .sra-hero-section,
        [data-bs-theme="dark"] .sra-activities-section { background: #0b1519; }
        [data-bs-theme="dark"] .sra-hero-title,
        [data-bs-theme="dark"] .sra-section-title,
        [data-bs-theme="dark"] .sra-kegiatan-title,
        [data-bs-theme="dark"] .sra-mini-feature-title,
        [data-bs-theme="dark"] .sra-pilar-card-title,
        [data-bs-theme="dark"] .sra-related-title { color: #f1f5f9; }
        [data-bs-theme="dark"] .sra-hero-title span,
        [data-bs-theme="dark"] .sra-section-subtitle,
        [data-bs-theme="dark"] .sra-mini-feature-icon { color: #34d399; }
        [data-bs-theme="dark"] .sra-quote-box { background: #132228; border-color: rgba(255, 255, 255, 0.08); border-left-color: #34d399; }
        [data-bs-theme="dark"] .sra-quote-text { color: #a7f3d0; }
        [data-bs-theme="dark"] .sra-hero-narrative,
        [data-bs-theme="dark"] .sra-section-desc,
        [data-bs-theme="dark"] .sra-kegiatan-desc,
        [data-bs-theme="dark"] .sra-mini-feature-desc,
        [data-bs-theme="dark"] .sra-pilar-card-desc,
        [data-bs-theme="dark"] .sra-image-caption { color: #94a3b8; }
        [data-bs-theme="dark"] .sra-mini-feature-card,
        [data-bs-theme="dark"] .sra-pilar-card,
        [data-bs-theme="dark"] .sra-kegiatan-card,
        [data-bs-theme="dark"] .sra-image-frame,
        [data-bs-theme="dark"] .sra-bottom-card,
        [data-bs-theme="dark"] .sra-share-bar { background: #132228; border-color: rgba(255, 255, 255, 0.08); }
        [data-bs-theme="dark"] .sra-related-item { background: #1a2f37; border-color: rgba(255, 255, 255, 0.08); }
        [data-bs-theme="dark"] .sra-consult-card { background: rgba(13, 106, 86, 0.16); border-color: rgba(52, 211, 153, 0.3); }
        [data-bs-theme="dark"] .sra-kegiatan-badge,
        [data-bs-theme="dark"] .sra-pilar-icon-box { background: rgba(52, 211, 153, 0.15); color: #34d399; border-color: rgba(52, 211, 153, 0.25); }
        [data-bs-theme="dark"] .sra-pilar-section,
        [data-bs-theme="dark"] .sra-bottom-section { background: #0e191e; border-color: rgba(255, 255, 255, 0.08); }
        [data-bs-theme="dark"] .sra-pilar-watermark { color: rgba(52, 211, 153, 0.08); }
        [data-bs-theme="dark"] .sra-section-divider { background: #34d399; }

        @media (max-width: 991.98px) {
            .sra-hero-grid { grid-template-columns: 1fr; gap: 36px; }
            .sra-hero-title { font-size: 2rem; }
            .sra-kegiatan-grid { grid-template-columns: repeat(2, 1fr); gap: 18px; }
        }
        @media (max-width: 767.98px) {
            .sra-kegiatan-grid { grid-template-columns: 1fr; gap: 16px; }
            .sra-section-title { font-size: 1.8rem; }
        }
    </style>

    <div class="sra-page-wrap">

        <!-- Breadcrumb -->
        <div class="sra-breadcrumb-nav">
            <div class="container">
                <ul class="sra-breadcrumb-list">
                    <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                    <li><a href="{{ route('program-unggulan.publik') }}"><i class="fas fa-star"></i> Program Unggulan</a></li>
                    <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                    <li class="sra-breadcrumb-current">{{ $program->judul }}</li>

                    @if ($bolehEdit)
                        <li class="ms-auto">
                            <a href="{{ route('program-unggulan.edit', $program->id) }}"
                               class="badge bg-dark text-white text-decoration-none px-10 py-6 radius-6">
                                ✎ Edit Program
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- 1. HERO SECTION -->
        <section class="sra-hero-section">
            <div class="container">
                <div class="sra-hero-grid">
                    <div>
                        <div class="sra-hero-badge">
                            <i class="{{ $program->icon }}"></i> {{ $program->kategori_badge }} • Unggulan Madrasah
                        </div>

                        <h1 class="sra-hero-title">
                            {{ $bagianHitam }}@if ($bagianHijau !== '') <span>{{ $bagianHijau }}</span>@endif
                        </h1>

                        @if (! empty($program->kutipan))
                            <div class="sra-quote-box">
                                <blockquote class="sra-quote-text">“{{ $program->kutipan }}”</blockquote>
                            </div>
                        @endif

                        <div class="sra-hero-narrative">
                            {!! $program->ringkasan_bersih ?: '<p>Program unggulan MIN 1 Ciamis dirancang untuk mendukung pembentukan akhlak mulia, prestasi akademik, dan pengembangan potensi peserta didik secara optimal.</p>' !!}
                        </div>

                        @if (count($miniCards) > 0)
                            <div class="sra-mini-features-grid">
                                @foreach ($miniCards as $mc)
                                    <div class="sra-mini-feature-card">
                                        <div class="sra-mini-feature-icon">
                                            <i class="{{ $mc['icon'] }}"></i>
                                        </div>
                                        <strong class="sra-mini-feature-title">{{ $mc['title'] }}</strong>
                                        @if (! empty($mc['desc']))
                                            <span class="sra-mini-feature-desc">{{ $mc['desc'] }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="sra-image-frame">
                            <img src="{{ $program->gambar_url }}" alt="{{ $program->judul }}" loading="lazy">
                            <div class="sra-image-caption">
                                <i class="fas fa-info-circle text-success"></i> Dokumentasi {{ $program->judul }} — MIN 1 Ciamis
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. PILAR SECTION (Hanya tampil jika pilar diisi) -->
        @if (count($pilars) > 0)
            <section class="sra-pilar-section">
                <div class="container">
                    <div class="sra-section-header">
                        <span class="sra-section-subtitle">Fondasi &amp; Nilai Keunggulan</span>
                        <h2 class="sra-section-title">{{ count($pilars) }} Pilar {{ $program->judul }}</h2>
                        <p class="sra-section-desc">
                            Pilar utama pelaksanaan program {{ $program->judul }} di lingkungan MIN 1 Ciamis.
                        </p>
                        <div class="sra-section-divider"></div>
                    </div>

                    <div class="sra-pilar-grid">
                        @foreach ($pilars as $idx => $pilar)
                            <div class="sra-pilar-card">
                                <div class="sra-pilar-watermark">0{{ $idx + 1 }}</div>
                                <div class="sra-pilar-icon-box">
                                    <i class="{{ $pilar['icon'] }}"></i>
                                </div>
                                <h3 class="sra-pilar-card-title">{{ $pilar['title'] }}</h3>
                                @if (! empty($pilar['desc']))
                                    <p class="sra-pilar-card-desc">{{ $pilar['desc'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- 3. KEGIATAN & BAR BAGIKAN -->
        <section class="sra-activities-section">
            <div class="container">
                @if (count($kegiatanList) > 0)
                    <div class="sra-section-header">
                        <span class="sra-section-subtitle">{{ $program->kegiatan_badge_formatted }}</span>
                        <h2 class="sra-section-title">{{ $program->kegiatan_judul_formatted }}</h2>
                        <p class="sra-section-desc">{{ $program->kegiatan_deskripsi_formatted }}</p>
                        <div class="sra-section-divider"></div>
                    </div>

                    <div class="sra-kegiatan-grid">
                        @foreach ($kegiatanList as $item)
                            <div class="sra-kegiatan-card">
                                <span class="sra-kegiatan-badge">{{ $item['badge'] }}</span>
                                <h3 class="sra-kegiatan-title">{{ $item['title'] }}</h3>
                                @if (! empty($item['desc']))
                                    <p class="sra-kegiatan-desc">{{ $item['desc'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Bar Navigasi & Bagikan -->
                <div class="sra-share-bar">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('program-unggulan.publik') }}" class="btn btn-outline-success btn-sm radius-8 fw-bold">
                            &larr; Semua Program Unggulan
                        </a>
                        <span class="badge bg-success-subtle text-success border">
                            {{ $program->kategori_badge }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="fs-7 fw-semibold">Bagikan:</span>
                        <a href="https://wa.me/?text={{ urlencode($program->judul . ' - ' . request()->fullUrl()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success radius-8" title="Bagikan ke WhatsApp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary radius-8" title="Bagikan ke Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <button type="button" id="btnSalinTautanProgram" class="btn btn-sm btn-outline-secondary radius-8" onclick="salinTautanProgram(this)">
                            <i class="fas fa-link"></i> Salin Tautan
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. BOTTOM SECTION (RINGKAS & BERSIH) -->
        <section class="sra-bottom-section">
            <div class="container">
                {{-- Hanya tampil jika memang ada program unggulan lain --}}
                @if ($programLain->isNotEmpty())
                    <div class="mb-5">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <h5 class="mb-0 fw-bold" style="font-family: 'Outfit', sans-serif;">
                                <i class="fas fa-star text-warning me-1"></i> Program Unggulan Lainnya
                            </h5>
                            <a href="{{ route('program-unggulan.publik') }}" class="text-success fw-bold text-decoration-none fs-7">
                                Lihat Semua &rarr;
                            </a>
                        </div>

                        <div class="row g-3">
                            @foreach ($programLain->take(3) as $pl)
                                <div class="col-lg-4 col-md-6">
                                    <a href="{{ route('program-unggulan.detail', $pl->slug) }}" class="sra-related-item h-100">
                                        <img src="{{ $pl->gambar_url }}" alt="{{ $pl->judul }}"
                                             class="flex-shrink-0" style="width: 68px; height: 54px; object-fit: cover; border-radius: 10px;">
                                        <div class="overflow-hidden">
                                            <span class="badge bg-success-subtle text-success fw-bold text-2xs mb-1">
                                                <i class="{{ $pl->icon }}"></i> {{ $pl->kategori_badge }}
                                            </span>
                                            <h6 class="sra-related-title text-truncate">{{ $pl->judul }}</h6>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 1 Banner CTA Horizontal (Konsultasi & PPDB) --}}
                <div style="background: linear-gradient(135deg, #06372D 0%, #0D6A56 100%); border-radius: 22px; padding: 32px 34px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 14px 32px rgba(6, 55, 45, 0.16);">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Logo MIN 1 Ciamis"
                             style="width: 58px; height: 58px; object-fit: contain; background: #ffffff; padding: 6px; border-radius: 14px; flex-shrink: 0;">
                        <div>
                            <h5 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: #ffffff; margin: 0 0 4px;">
                                Tertarik dengan Program {{ $program->judul }}?
                            </h5>
                            <p style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.88); margin: 0; max-width: 560px; line-height: 1.5;">
                                Konsultasikan program pendidikan bersama Tim Humas &amp; Kurikulum atau lihat informasi PPDB MIN 1 Ciamis.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('home') }}#kontak"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; border: 1.5px solid rgba(255, 255, 255, 0.65); color: #ffffff; font-weight: 700; font-size: 0.88rem; text-decoration: none;">
                            <i class="fas fa-comments"></i> Hubungi Kami
                        </a>
                        <a href="{{ route('home') }}#banner-ppdb"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 12px; background: #FFE15D; color: #06372D; font-weight: 800; font-size: 0.88rem; text-decoration: none;">
                            <i class="fas fa-user-plus"></i> Informasi PPDB
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <script>
        function salinTautanProgram(btn) {
            var labelAsli = btn.innerHTML;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(window.location.href).then(function () {
                    btn.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
                    setTimeout(function () { btn.innerHTML = labelAsli; }, 2200);
                }).catch(function () {
                    prompt('Salin tautan ini:', window.location.href);
                });
            } else {
                prompt('Salin tautan ini:', window.location.href);
            }
        }
    </script>

@endsection