@extends('layouts.frontend')

@section('title', 'Kabar Madrasah & Warta Kegiatan — MIN 1 Ciamis')

@section('content')

    <style>
        .kabar-page-wrap {
            padding: 32px 0 84px;
            background: #f8fafc;
            min-height: 85vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        /* Breadcrumb Bar */
        .kabar-breadcrumb-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .kabar-breadcrumb-bar a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .kabar-breadcrumb-bar a:hover {
            color: #1B9C85;
            text-decoration: underline;
        }

        /* Hero Banner Kabar Madrasah */
        .kabar-hero-card {
            background: linear-gradient(135deg, #06372D 0%, #0D6A56 50%, #15803D 100%);
            border-radius: 28px;
            padding: 46px 36px;
            color: #ffffff;
            margin-bottom: 34px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(6, 55, 45, 0.18);
        }
        .kabar-hero-card::before {
            content: '';
            position: absolute;
            top: -75px;
            right: -75px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 225, 93, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .kabar-hero-card::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 15%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(45, 212, 191, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .kabar-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 225, 93, 0.18);
            border: 1px solid rgba(255, 225, 93, 0.45);
            color: #FFE15D;
            padding: 5px 18px;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .kabar-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.45rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 14px;
        }
        .kabar-hero-desc {
            font-size: 1.02rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 820px;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .kabar-hero-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .kabar-hero-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 7px 18px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #ffffff;
        }
        .kabar-hero-stat-pill i {
            color: #FFE15D;
        }

        /* =========================================================================
           FEATURED STORY BENTO BOX (BERITA UTAMA & TRENDING POPULER)
           ========================================================================= */
        .kabar-bento-grid {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 24px;
            margin-bottom: 40px;
        }

        /* Kartu Berita Utama */
        .kabar-lead-card {
            background: #ffffff;
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .kabar-lead-card:hover {
            transform: translateY(-4px);
            border-color: #0D6A56;
            box-shadow: 0 18px 36px rgba(13, 106, 86, 0.12);
        }
        .kabar-lead-thumb-wrap {
            position: relative;
            width: 100%;
            height: 290px;
            overflow: hidden;
            background: #0f172a;
        }
        .kabar-lead-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
            display: block;
        }
        .kabar-lead-card:hover .kabar-lead-thumb {
            transform: scale(1.05);
        }
        .kabar-lead-badge-float {
            position: absolute;
            top: 16px;
            left: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 2;
        }
        .kabar-lead-pill-primary {
            background: #0D6A56;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.75rem;
            padding: 5px 14px;
            border-radius: 50px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .kabar-lead-pill-time {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.72rem;
            padding: 5px 12px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .kabar-lead-body {
            padding: 26px 28px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .kabar-meta-row {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        .kabar-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .kabar-lead-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 12px;
            transition: color 0.2s ease;
        }
        .kabar-lead-title a {
            color: inherit;
            text-decoration: none;
        }
        .kabar-lead-title a:hover {
            color: #0D6A56;
        }
        .kabar-lead-excerpt {
            color: #475569;
            font-size: 0.94rem;
            line-height: 1.65;
            margin-bottom: 20px;
        }
        .kabar-lead-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kabar-lead-author {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: #334155;
        }
        .kabar-lead-author img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: contain;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .kabar-btn-read-main {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #0D6A56;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.84rem;
            padding: 8px 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .kabar-btn-read-main:hover {
            background: #1B9C85;
            color: #ffffff;
            transform: translateX(3px);
        }

        /* Kartu Trending Populer (Right Column) */
        .kabar-popular-box {
            background: #ffffff;
            border-radius: 22px;
            padding: 26px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
        }
        .kabar-popular-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            margin-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }
        .kabar-popular-header-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .kabar-popular-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex-grow: 1;
        }
        .kabar-popular-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 8px;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        .kabar-popular-item:hover {
            background: #f8fafc;
            transform: translateX(3px);
        }
        .kabar-popular-rank {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            font-weight: 900;
            color: #cbd5e1;
            min-width: 32px;
            text-align: center;
            line-height: 1;
        }
        .kabar-popular-item:nth-child(1) .kabar-popular-rank {
            color: #f59e0b;
        }
        .kabar-popular-item:nth-child(2) .kabar-popular-rank {
            color: #0D6A56;
        }
        .kabar-popular-item:nth-child(3) .kabar-popular-rank {
            color: #3b82f6;
        }
        .kabar-popular-thumb {
            width: 72px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .kabar-popular-info {
            flex-grow: 1;
            min-width: 0;
        }
        .kabar-popular-badge {
            font-size: 0.7rem;
            font-weight: 700;
            color: #0D6A56;
            margin-bottom: 4px;
            display: block;
        }
        .kabar-popular-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .kabar-popular-meta {
            font-size: 0.74rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* =========================================================================
           BILIK FILTER & PENCARIAN KABAR MADRASAH
           ========================================================================= */
        .kabar-filter-bar {
            background: #ffffff;
            border-radius: 18px;
            padding: 18px 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 34px;
        }
        .kabar-search-form {
            position: relative;
            width: 100%;
        }
        .kabar-search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
        }
        .kabar-search-input {
            width: 100%;
            padding: 12px 18px 12px 42px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.92rem;
            font-weight: 500;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .kabar-search-input:focus {
            outline: none;
            border-color: #0D6A56;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(13, 106, 86, 0.1);
        }
        .kabar-category-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .kabar-category-pills::-webkit-scrollbar {
            display: none;
        }
        .kabar-cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .kabar-cat-pill:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .kabar-cat-pill.active {
            background: #0D6A56;
            color: #ffffff;
            border-color: #0D6A56;
            box-shadow: 0 4px 12px rgba(13, 106, 86, 0.25);
        }
        .kabar-cat-count {
            background: rgba(0, 0, 0, 0.08);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.72rem;
        }
        .kabar-cat-pill.active .kabar-cat-count {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* Sort dropdown */
        .kabar-sort-select {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.86rem;
            font-weight: 600;
            color: #334155;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .kabar-sort-select:focus {
            outline: none;
            border-color: #0D6A56;
        }

        /* =========================================================================
           MAIN NEWS CARDS GRID (3 KOLOM MODERN DENGAN HOVER LIFT)
           ========================================================================= */
        .kabar-news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 26px;
            margin-bottom: 45px;
        }
        .kabar-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .kabar-card:hover {
            transform: translateY(-5px);
            border-color: #0D6A56;
            box-shadow: 0 16px 36px rgba(13, 106, 86, 0.1);
        }
        .kabar-card-thumb-wrap {
            position: relative;
            width: 100%;
            height: 215px;
            overflow: hidden;
            background: #0f172a;
        }
        .kabar-card-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .kabar-card:hover .kabar-card-thumb {
            transform: scale(1.06);
        }
        .kabar-card-cat-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(13, 106, 86, 0.92);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.74rem;
            padding: 4px 12px;
            border-radius: 50px;
            letter-spacing: 0.3px;
            z-index: 2;
        }
        .kabar-card-time-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            z-index: 2;
        }
        .kabar-card-body {
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .kabar-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.18rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }
        .kabar-card-title a {
            color: inherit;
            text-decoration: none;
        }
        .kabar-card-title a:hover {
            color: #0D6A56;
        }
        .kabar-card-excerpt {
            color: #475569;
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 18px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .kabar-card-footer {
            margin-top: auto;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kabar-card-author {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }
        .kabar-card-author img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: contain;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .kabar-btn-read {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #0D6A56;
            font-weight: 700;
            font-size: 0.82rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .kabar-btn-read:hover {
            color: #1B9C85;
            transform: translateX(4px);
        }

        /* Empty State */
        .kabar-empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #ffffff;
            border-radius: 24px;
            border: 1px dashed #cbd5e1;
            margin-bottom: 40px;
        }
        .kabar-empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(13, 106, 86, 0.08);
            color: #0D6A56;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 16px;
        }

        /* Pojok Humas Callout */
        .kabar-humas-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #a7f3d0;
            border-radius: 20px;
            padding: 30px;
            margin-top: 30px;
        }

        /* Dark Mode Theme Compatibility */
        [data-bs-theme="dark"] .kabar-page-wrap {
            background: #0b1519;
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .kabar-breadcrumb-bar a {
            color: #34d399;
        }
        [data-bs-theme="dark"] .kabar-lead-card,
        [data-bs-theme="dark"] .kabar-popular-box,
        [data-bs-theme="dark"] .kabar-filter-bar,
        [data-bs-theme="dark"] .kabar-card,
        [data-bs-theme="dark"] .kabar-empty-state {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .kabar-lead-title,
        [data-bs-theme="dark"] .kabar-popular-header-title,
        [data-bs-theme="dark"] .kabar-popular-title,
        [data-bs-theme="dark"] .kabar-card-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .kabar-lead-title a:hover,
        [data-bs-theme="dark"] .kabar-card-title a:hover {
            color: #34d399;
        }
        [data-bs-theme="dark"] .kabar-lead-excerpt,
        [data-bs-theme="dark"] .kabar-card-excerpt,
        [data-bs-theme="dark"] .kabar-card-author,
        [data-bs-theme="dark"] .kabar-meta-row {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .kabar-lead-footer,
        [data-bs-theme="dark"] .kabar-card-footer,
        [data-bs-theme="dark"] .kabar-popular-header {
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .kabar-search-input,
        [data-bs-theme="dark"] .kabar-sort-select {
            background: #0e191e;
            border-color: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .kabar-cat-pill {
            background: #0e191e;
            color: #94a3b8;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .kabar-cat-pill:hover {
            background: #162a32;
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .kabar-cat-pill.active {
            background: #34d399;
            color: #0b1519;
            border-color: #34d399;
        }
        [data-bs-theme="dark"] .kabar-cat-pill.active .kabar-cat-count {
            background: rgba(11, 21, 25, 0.2);
            color: #0b1519;
        }
        [data-bs-theme="dark"] .kabar-btn-read {
            color: #34d399;
        }
        [data-bs-theme="dark"] .kabar-popular-item:hover {
            background: #0e191e;
        }
        [data-bs-theme="dark"] .kabar-humas-card {
            background: #112521;
            border-color: rgba(52, 211, 153, 0.25);
        }

        /* Responsive Mobile adjustments */
        @media (max-width: 991.98px) {
            .kabar-bento-grid {
                grid-template-columns: 1fr;
            }
            .kabar-hero-title {
                font-size: 2.05rem;
            }
            .kabar-lead-thumb-wrap {
                height: 230px;
            }
        }
        @media (max-width: 575.98px) {
            .kabar-news-grid {
                grid-template-columns: 1fr;
            }
            .kabar-hero-card {
                padding: 32px 20px;
                border-radius: 20px;
            }
            .kabar-filter-bar {
                padding: 16px;
            }
        }
    </style>

    <div class="kabar-page-wrap">
        <div class="container">

            <!-- ==========================================
                 1. BREADCRUMB BAR
                 ========================================== -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <nav class="kabar-breadcrumb-bar mb-0" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <span>/</span>
                    <span class="text-dark fw-bold">Kabar Madrasah</span>
                    @if ($kategoriAktif !== 'semua')
                        <span>/</span>
                        <span class="text-success fw-bold">{{ $kategoriAktif }}</span>
                    @endif
                </nav>

                @auth
                    <a href="{{ route('berita.create') }}" class="btn btn-sm btn-dark radius-8 px-14 py-6 fw-bold">
                        <i class="fas fa-magic me-1 text-warning"></i> + Tulis Kabar Baru (AI Studio)
                    </a>
                @endauth
            </div>

            <!-- ==========================================
                 2. HERO BANNER KABAR MADRASAH
                 ========================================== -->
            <div class="kabar-hero-card">
                <div class="position-relative" style="z-index: 2;">
                    <div class="kabar-hero-badge">
                        <i class="fas fa-newspaper"></i> Warta Resmi &amp; Jurnalistik • MIN 1 Ciamis
                    </div>
                    <h1 class="kabar-hero-title">Kabar Madrasah &amp; Liputan Terkini</h1>
                    <p class="kabar-hero-desc">
                        Pusat informasi aktual seputar dinamika madrasah, prestasi peserta didik, inovasi pembelajaran, pembiasaan karakter Islami, dan pengumuman resmi MIN 1 Ciamis.
                    </p>
                    <div class="kabar-hero-stats">
                        <div class="kabar-hero-stat-pill">
                            <i class="fas fa-file-alt"></i>
                            <span><b>{{ $totalBerita }}</b> Kabar Tayang</span>
                        </div>
                        <div class="kabar-hero-stat-pill">
                            <i class="fas fa-eye"></i>
                            <span><b>{{ number_format($totalPembaca) }}x</b> Kali Dibaca</span>
                        </div>
                        <div class="kabar-hero-stat-pill">
                            <i class="fas fa-tags"></i>
                            <span><b>{{ $kategoris->count() }}</b> Kategori Warta</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 3. BENTO SHOWCASE: BERITA UTAMA & TRENDING
                 (Hanya muncul di halaman 1 saat tidak sedang mencari)
                 ========================================== -->
            @if ($beritaUtama && empty($q) && $kategoriAktif === 'semua' && request()->get('page', 1) == 1)
                <div class="kabar-bento-grid">
                    <!-- Kolom Kiri: Berita Utama / Sorotan -->
                    <div class="kabar-lead-card">
                        <div class="kabar-lead-thumb-wrap">
                            <img src="{{ $beritaUtama->gambar_url }}" alt="{{ $beritaUtama->judul }}" class="kabar-lead-thumb" loading="lazy">
                            <div class="kabar-lead-badge-float">
                                <span class="kabar-lead-pill-primary">
                                    <i class="fas fa-star me-1 text-warning"></i> Sorotan Utama
                                </span>
                                <span class="kabar-lead-pill-time">
                                    <i class="far fa-clock"></i> {{ $beritaUtama->waktu_baca }}
                                </span>
                            </div>
                        </div>
                        <div class="kabar-lead-body">
                            <div class="kabar-meta-row">
                                <span class="kabar-meta-item">
                                    <i class="far fa-calendar-alt text-success"></i> {{ $beritaUtama->created_at->translatedFormat('d F Y') }}
                                </span>
                                <span class="kabar-meta-item">
                                    <i class="fas fa-tag text-success"></i> {{ $beritaUtama->kategori }}
                                </span>
                                <span class="kabar-meta-item">
                                    <i class="far fa-eye text-success"></i> {{ $beritaUtama->views }}x dibaca
                                </span>
                            </div>
                            <h2 class="kabar-lead-title">
                                <a href="{{ route('berita.show', $beritaUtama->slug) }}">
                                    {{ $beritaUtama->judul }}
                                </a>
                            </h2>
                            <p class="kabar-lead-excerpt">
                                {{ Str::limit($beritaUtama->ringkasan ?: strip_tags($beritaUtama->isi_konten), 190) }}
                            </p>
                            <div class="kabar-lead-footer">
                                <div class="kabar-lead-author">
                                    <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="{{ $beritaUtama->penulis }}">
                                    <span>{{ $beritaUtama->penulis }}</span>
                                </div>
                                <a href="{{ route('berita.show', $beritaUtama->slug) }}" class="kabar-btn-read-main">
                                    <span>Baca Berita Utama</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Kabar Populer & Terbanyak Dibaca -->
                    <div class="kabar-popular-box">
                        <div class="kabar-popular-header">
                            <h3 class="kabar-popular-header-title">
                                <i class="fas fa-fire text-danger"></i> Kabar Terpopuler
                            </h3>
                            <span class="badge bg-light text-muted text-2xs fw-bold border">Top Dibaca</span>
                        </div>

                        <div class="kabar-popular-list">
                            @forelse ($beritaPopuler as $idx => $pop)
                                <a href="{{ route('berita.show', $pop->slug) }}" class="kabar-popular-item">
                                    <div class="kabar-popular-rank">#0{{ $idx + 1 }}</div>
                                    <img src="{{ $pop->gambar_url }}" alt="{{ $pop->judul }}" class="kabar-popular-thumb" loading="lazy">
                                    <div class="kabar-popular-info">
                                        <span class="kabar-popular-badge">{{ $pop->kategori }}</span>
                                        <h4 class="kabar-popular-title">{{ $pop->judul }}</h4>
                                        <div class="kabar-popular-meta">
                                            <span><i class="far fa-calendar-alt"></i> {{ $pop->created_at->translatedFormat('d M') }}</span>
                                            <span>&bull;</span>
                                            <span><i class="far fa-eye"></i> {{ $pop->views }}x</span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted text-xs mb-0">Belum ada statistik warta.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- ==========================================
                 4. BILIK FILTER & PENCARIAN WARTA
                 ========================================== -->
            <div class="kabar-filter-bar">
                <form action="{{ route('berita.publik') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-center mb-3">
                        <!-- Input Pencarian -->
                        <div class="col-lg-8 col-md-7">
                            <div class="kabar-search-form">
                                <i class="fas fa-search kabar-search-icon"></i>
                                <input type="text" name="q" value="{{ $q }}"
                                    class="kabar-search-input"
                                    placeholder="Cari judul berita, topik kegiatan, prestasi, atau kata kunci..."
                                    autocomplete="off">
                            </div>
                        </div>

                        <!-- Dropdown Sorting -->
                        <div class="col-lg-4 col-md-5">
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-xs fw-bold text-muted text-nowrap d-none d-sm-inline">Urutkan:</label>
                                <select name="sort" class="kabar-sort-select w-100" onchange="this.form.submit()">
                                    <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>✨ Terbaru &amp; Unggulan</option>
                                    <option value="populer" {{ $sort === 'populer' ? 'selected' : '' }}>🔥 Paling Banyak Dibaca</option>
                                    <option value="terlama" {{ $sort === 'terlama' ? 'selected' : '' }}>⏳ Warta Terdahulu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Kategori (Pill Buttons) -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                        <div class="kabar-category-pills">
                            <a href="{{ route('berita.publik', array_merge(request()->except('kategori', 'page'), ['kategori' => 'semua'])) }}"
                               class="kabar-cat-pill {{ $kategoriAktif === 'semua' ? 'active' : '' }}">
                                <i class="fas fa-th-large"></i> Semua Warta
                                <span class="kabar-cat-count">{{ $totalBerita }}</span>
                            </a>

                            @foreach ($kategoris as $kat)
                                <a href="{{ route('berita.publik', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat->nama_kategori])) }}"
                                   class="kabar-cat-pill {{ $kategoriAktif === $kat->nama_kategori ? 'active' : '' }}">
                                    <span>{{ $kat->nama_kategori }}</span>
                                    <span class="kabar-cat-count">{{ $kat->beritas_count }}</span>
                                </a>
                            @endforeach
                        </div>

                        @if ($q !== '' || $kategoriAktif !== 'semua' || $sort !== 'terbaru')
                            <a href="{{ route('berita.publik') }}" class="btn btn-sm btn-outline-danger radius-8 px-12 py-4 text-xs fw-bold ms-auto">
                                <i class="fas fa-times-circle me-1"></i> Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ==========================================
                 5. MAIN NEWS GRID
                 ========================================== -->
            @if ($beritas->count() > 0)
                <div class="kabar-news-grid">
                    @foreach ($beritas as $item)
                        <article class="kabar-card">
                            <div class="kabar-card-thumb-wrap">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="kabar-card-thumb" loading="lazy">
                                <span class="kabar-card-cat-badge">{{ $item->kategori }}</span>
                                <span class="kabar-card-time-badge">
                                    <i class="far fa-clock"></i> {{ $item->waktu_baca }}
                                </span>
                            </div>
                            <div class="kabar-card-body">
                                <div class="kabar-meta-row mb-2">
                                    <span class="kabar-meta-item">
                                        <i class="far fa-calendar-alt text-success"></i> {{ $item->created_at->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="kabar-meta-item">
                                        <i class="far fa-eye text-success"></i> {{ $item->views }}x dibaca
                                    </span>
                                </div>
                                <h3 class="kabar-card-title">
                                    <a href="{{ route('berita.show', $item->slug) }}">
                                        {{ $item->judul }}
                                    </a>
                                </h3>
                                <p class="kabar-card-excerpt">
                                    {{ Str::limit($item->ringkasan ?: strip_tags($item->isi_konten), 125) }}
                                </p>
                                <div class="kabar-card-footer">
                                    <div class="kabar-card-author">
                                        <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="{{ $item->penulis }}">
                                        <span>{{ Str::limit($item->penulis, 20) }}</span>
                                    </div>
                                    <a href="{{ route('berita.show', $item->slug) }}" class="kabar-btn-read">
                                        <span>Baca Warta</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- ==========================================
                     6. PAGINATION
                     ========================================== -->
                <div class="d-flex justify-content-center mt-4 mb-5">
                    {{ $beritas->links('pagination::bootstrap-5') }}
                </div>
            @else
                <!-- ==========================================
                     EMPTY STATE (TIDAK DITEMUKAN)
                     ========================================== -->
                <div class="kabar-empty-state">
                    <div class="kabar-empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="fw-bold mb-2" style="font-family: 'Outfit', sans-serif;">Kabar Tidak Ditemukan</h3>
                    <p class="text-muted text-sm max-w-md mx-auto mb-4">
                        Tidak ada berita atau warta yang cocok dengan pencarian <b>"{{ $q }}"</b>
                        @if ($kategoriAktif !== 'semua')
                            pada kategori <b>"{{ $kategoriAktif }}"</b>
                        @endif.
                    </p>
                    <a href="{{ route('berita.publik') }}" class="btn btn-success radius-10 px-24 py-10 fw-bold"
                        style="background: #0D6A56; border-color: #0D6A56;">
                        <i class="fas fa-undo me-1"></i> Tampilkan Seluruh Warta
                    </a>
                </div>
            @endif

            <!-- ==========================================
                 7. POJOK HUMAS & SINERGI JURNALISTIK
                 ========================================== -->
            <div class="kabar-humas-card">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #0D6A56; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Pojok Humas &amp; Redaksi Warta Madrasah</h5>
                                <span class="text-xs text-muted">Biro Hubungan Masyarakat &amp; Publikasi MIN 1 Ciamis</span>
                            </div>
                        </div>
                        <p class="text-sm text-secondary-light mb-0" style="line-height: 1.65;">
                            Punya informasi kegiatan kelas, karya literasi siswa, dokumentasi prestasi kejuaraan, atau kabar inspiratif seputar madrasah? Sampaikan kepada tim humas kami untuk diliput dan dipublikasikan di portal resmi.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('home') }}#kontak" class="btn btn-outline-success radius-10 px-20 py-10 fw-bold">
                            <i class="fas fa-paper-plane me-1"></i> Hubungi Tim Humas
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
