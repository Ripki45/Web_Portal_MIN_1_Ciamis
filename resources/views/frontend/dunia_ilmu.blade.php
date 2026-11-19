@extends('layouts.frontend')

@section('title', 'Dunia Ilmu & Media Pembelajaran Interaktif — MIN 1 Ciamis')

@section('content')

    <style>
        .dunia-page-wrap {
            padding: 32px 0 84px;
            background: #f8fafc;
            min-height: 85vh;
        }

        /* Breadcrumb Bar */
        .dunia-breadcrumb-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .dunia-breadcrumb-bar a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .dunia-breadcrumb-bar a:hover {
            text-decoration: underline;
        }

        /* Hero Banner Dunia Ilmu */
        .dunia-hero-card {
            background: linear-gradient(135deg, #09332C 0%, #0D6A56 50%, #115E59 100%);
            border-radius: 28px;
            padding: 44px 36px;
            color: #ffffff;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(9, 51, 44, 0.18);
        }
        .dunia-hero-card::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 225, 93, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .dunia-hero-card::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 20%;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(27, 156, 133, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .dunia-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 225, 93, 0.18);
            border: 1px solid rgba(255, 225, 93, 0.4);
            color: #FFE15D;
            padding: 5px 16px;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .dunia-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.35rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 12px;
        }
        .dunia-hero-desc {
            font-size: 1.02rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 780px;
            line-height: 1.65;
            margin-bottom: 22px;
        }
        .dunia-hero-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .dunia-hero-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 7px 16px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #ffffff;
        }
        .dunia-hero-stat-pill i {
            color: #FFE15D;
        }

        /* ==========================================================================
           MODERN MOBILE-FIRST FILTER COMPONENT
           ========================================================================== */
        .dunia-filter-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 20px 24px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            margin-bottom: 30px;
            transition: all 0.25s ease;
        }

        /* Baris 1: Live Search & Sort */
        .dunia-filter-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }
        .dunia-search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }
        .dunia-search-input-wrap .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
        }
        .dunia-search-input {
            width: 100%;
            padding: 13px 44px 13px 46px;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 500;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .dunia-search-input:focus {
            outline: none;
            border-color: #1B9C85;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(27, 156, 133, 0.14);
        }
        .dunia-search-clear {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: #e2e8f0;
            border: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .dunia-search-clear:hover {
            background: #cbd5e1;
            color: #0f172a;
        }

        /* Sort Dropdown */
        .dunia-sort-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .dunia-sort-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
            display: none;
        }
        @media (min-width: 768px) {
            .dunia-sort-label {
                display: inline;
            }
        }
        .dunia-sort-select {
            padding: 12px 34px 12px 14px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px;
            transition: all 0.2s ease;
        }
        .dunia-sort-select:focus {
            outline: none;
            border-color: #1B9C85;
            background-color: #ffffff;
        }

        /* Baris 2: Carousel Chip Horizontal untuk KELAS (Mobile Touch-Friendly) */
        .dunia-kelas-filter-section {
            margin-bottom: 16px;
        }
        .dunia-filter-sublabel {
            font-size: 0.78rem;
            font-weight: 800;
            color: #0D6A56;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .dunia-chips-scroll {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 6px;
            margin-bottom: -6px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .dunia-chips-scroll::-webkit-scrollbar {
            display: none;
        }
        .dunia-chip-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #334155;
            border: 1.5px solid #e2e8f0;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            flex-shrink: 0;
        }
        .dunia-chip-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: translateY(-1px);
        }
        .dunia-chip-btn.active {
            background: linear-gradient(135deg, #0D6A56 0%, #1B9C85 100%);
            color: #ffffff;
            border-color: #0D6A56;
            box-shadow: 0 4px 14px rgba(13, 106, 86, 0.28);
        }
        .dunia-chip-btn .badge-count {
            background: rgba(255, 255, 255, 0.25);
            color: inherit;
            font-size: 0.72rem;
            padding: 2px 7px;
            border-radius: 9999px;
            margin-left: 2px;
        }
        .dunia-chip-btn:not(.active) .badge-count {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Baris 3: Filter Mapel & Tipe Media */
        .dunia-filter-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }
        .dunia-filter-row-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            flex: 1;
        }
        .dunia-select-pill {
            padding: 9px 34px 9px 14px;
            border-radius: 9999px;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: 0.83rem;
            font-weight: 700;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 13px;
            transition: all 0.2s ease;
            max-width: 260px;
        }
        .dunia-select-pill:focus {
            outline: none;
            border-color: #1B9C85;
            background-color: #ffffff;
        }

        /* Quick Type Radio Pills */
        .dunia-type-pills {
            display: inline-flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            gap: 3px;
        }
        .dunia-type-pill-btn {
            background: transparent;
            border: none;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.79rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .dunia-type-pill-btn.active {
            background: #ffffff;
            color: #0D6A56;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }

        /* Status Bar Hasil & Active Badges */
        .dunia-results-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .dunia-results-count {
            font-size: 0.92rem;
            color: #334155;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dunia-results-count strong {
            color: #0D6A56;
            font-weight: 800;
        }
        .dunia-active-filters-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .dunia-active-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.76rem;
            font-weight: 700;
        }
        .dunia-active-tag .tag-close {
            cursor: pointer;
            color: #059669;
            font-weight: 900;
            font-size: 11px;
        }
        .dunia-active-tag .tag-close:hover {
            color: #047857;
        }
        .dunia-reset-btn {
            background: none;
            border: none;
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            padding: 4px 8px;
            display: none;
            align-items: center;
            gap: 5px;
            transition: opacity 0.2s ease;
        }
        .dunia-reset-btn:hover {
            text-decoration: underline;
        }

        /* ==========================================================================
           GRID KARTU MEDIA DUNIA ILMU (MODERN CARD DESIGN)
           ========================================================================== */
        .dunia-media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 26px;
        }
        .dunia-item-card {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            position: relative;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        }
        .dunia-item-card:hover {
            transform: translateY(-8px);
            border-color: #1B9C85;
            box-shadow: 0 20px 40px rgba(27, 156, 133, 0.16);
            color: inherit;
        }

        /* Banner Atas Kartu dengan Gradasi Dinamis */
        .dunia-item-banner {
            position: relative;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 16px;
        }
        .dunia-item-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.15) 0%, rgba(0, 0, 0, 0.1) 100%);
            pointer-events: none;
        }
        .dunia-badge-kelas-top {
            position: absolute;
            top: 12px;
            left: 14px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(8px);
            color: #0f172a;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 9999px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .dunia-badge-tipe-top {
            position: absolute;
            top: 12px;
            right: 14px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: #FFE15D;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 4px 11px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 225, 93, 0.45);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.5px;
        }
        .dunia-badge-tipe-link {
            background: rgba(255, 255, 255, 0.9);
            color: #0f172a;
            border-color: rgba(255, 255, 255, 0.7);
        }

        /* Lingkaran Thumbnail Gambar */
        .dunia-thumb-box {
            width: 100px;
            height: 100px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.96);
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
            position: relative;
            z-index: 1;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .dunia-item-card:hover .dunia-thumb-box {
            transform: scale(1.08) rotate(2deg);
        }
        .dunia-thumb-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 12px;
        }

        /* Body Kartu */
        .dunia-item-body {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
        }
        .dunia-item-mapel-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            width: fit-content;
            margin-bottom: 10px;
        }
        .dunia-item-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.18rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }
        .dunia-item-card:hover .dunia-item-title {
            color: #0D6A56;
        }
        .dunia-item-desc {
            font-size: 0.86rem;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 18px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Footer Kartu & Action Button */
        .dunia-item-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }
        .dunia-item-views {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.76rem;
            color: #94a3b8;
            font-weight: 600;
        }
        .dunia-item-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.82rem;
            font-weight: 800;
            color: #0D6A56;
            transition: all 0.2s ease;
        }
        .dunia-item-card:hover .dunia-item-action-btn {
            color: #1B9C85;
            transform: translateX(4px);
        }

        /* Empty State */
        .dunia-empty-state {
            display: none;
            text-align: center;
            padding: 60px 24px;
            background: #ffffff;
            border-radius: 24px;
            border: 1.5px dashed #cbd5e1;
            margin-top: 20px;
        }
        .dunia-empty-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            border-radius: 20px;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        /* Dark Mode Theme Adjustments */
        [data-bs-theme="dark"] .dunia-page-wrap {
            background: #0b1519;
        }
        [data-bs-theme="dark"] .dunia-breadcrumb-bar {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .dunia-breadcrumb-bar a {
            color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-filter-card {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        [data-bs-theme="dark"] .dunia-search-input {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .dunia-search-input:focus {
            background: #1e3740;
            border-color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-sort-select,
        [data-bs-theme="dark"] .dunia-select-pill {
            background-color: #1a2f37;
            border-color: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .dunia-chip-btn {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .dunia-chip-btn:hover {
            background: #243f4a;
            color: #ffffff;
        }
        [data-bs-theme="dark"] .dunia-chip-btn.active {
            background: linear-gradient(135deg, #1B9C85 0%, #0D6A56 100%);
            color: #ffffff;
            border-color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-type-pills {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.1);
        }
        [data-bs-theme="dark"] .dunia-type-pill-btn {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .dunia-type-pill-btn.active {
            background: #243f4a;
            color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-filter-bottom {
            border-top-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .dunia-results-count {
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .dunia-results-count strong {
            color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-item-card {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.25);
        }
        [data-bs-theme="dark"] .dunia-item-card:hover {
            border-color: #34d399;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        [data-bs-theme="dark"] .dunia-item-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .dunia-item-card:hover .dunia-item-title {
            color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-item-desc {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .dunia-item-mapel-badge {
            background: #1a2f37;
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .dunia-item-footer {
            border-top-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .dunia-item-action-btn {
            color: #34d399;
        }
        [data-bs-theme="dark"] .dunia-empty-state {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* Mobile Optimization */
        @media (max-width: 767.98px) {
            .dunia-hero-card {
                padding: 30px 22px;
                border-radius: 20px;
                margin-bottom: 22px;
            }
            .dunia-hero-title {
                font-size: 1.7rem;
            }
            .dunia-hero-desc {
                font-size: 0.92rem;
                margin-bottom: 16px;
            }
            .dunia-filter-card {
                padding: 16px;
                border-radius: 18px;
            }
            .dunia-search-input-wrap {
                min-width: 100%;
            }
            .dunia-sort-wrap {
                width: 100%;
                justify-content: space-between;
            }
            .dunia-sort-select {
                width: 100%;
            }
            .dunia-filter-row-left {
                width: 100%;
            }
            .dunia-select-pill {
                max-width: 100%;
                width: 100%;
            }
            .dunia-type-pills {
                width: 100%;
                display: flex;
            }
            .dunia-type-pill-btn {
                flex: 1;
                text-align: center;
                padding: 7px 8px;
                font-size: 0.74rem;
            }
            .dunia-media-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>

    <div class="dunia-page-wrap">
        <div class="container">

            <!-- Breadcrumb Navigation -->
            <div class="dunia-breadcrumb-bar">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                <i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i>
                <a href="{{ route('home') }}#dunia-ilmu"><i class="fas fa-compass"></i> Dunia Ilmu</a>
                <i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i>
                <span>Katalog Media &amp; Aplikasi Pembelajaran</span>
            </div>

            <!-- Hero Section -->
            <div class="dunia-hero-card">
                <div class="dunia-hero-badge">
                    <i class="fas fa-sparkles"></i> PORTAL EDUKASI DIGITAL &amp; INTERAKTIF
                </div>
                <h1 class="dunia-hero-title">Dunia Ilmu MIN 1 Ciamis</h1>
                <p class="dunia-hero-desc">
                    Jelajahi petualangan belajar seru dengan aplikasi interaktif, simulasi sains, modul edukasi islami, kuis digital, dan media pembelajaran interaktif yang dirancang khusus untuk santri dari Kelas 1 hingga Kelas 6.
                </p>

                <div class="dunia-hero-stats">
                    <div class="dunia-hero-stat-pill">
                        <i class="fas fa-layer-group"></i>
                        <span><strong>{{ $totalSemua }}</strong> Media Edukasi</span>
                    </div>
                    <div class="dunia-hero-stat-pill">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Kelas 1 s/d Kelas 6</span>
                    </div>
                    <div class="dunia-hero-stat-pill">
                        <i class="fas fa-bolt"></i>
                        <span>Simulasi &amp; Interaktif TSX/JS</span>
                    </div>
                    <div class="dunia-hero-stat-pill">
                        <i class="fas fa-unlock-alt"></i>
                        <span>Akses Terbuka &amp; Gratis</span>
                    </div>
                </div>
            </div>

            <!-- =========================================================================
                 FILTER COMPONENT (MOBILE-FIRST OPTIMIZED)
                 ========================================================================= -->
            <div class="dunia-filter-card" id="filterContainer">

                <!-- Baris 1: Pencarian Cepat & Pengurutan -->
                <div class="dunia-filter-top">
                    <div class="dunia-search-input-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="inputCariDunia" class="dunia-search-input"
                               placeholder="Cari media, mata pelajaran, topik (contoh: Al-Qur'an, Fikih, PhET, Planet, Perkalian, Quizizz)..."
                               value="{{ request('q', '') }}"
                               autocomplete="off">
                        <button type="button" id="btnClearSearch" class="dunia-search-clear" title="Bersihkan pencarian">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="dunia-sort-wrap">
                        <span class="dunia-sort-label"><i class="fas fa-sort-amount-down"></i> Urutkan:</span>
                        <select id="selectUrutan" class="dunia-sort-select">
                            <option value="urutan_asc">Urutan Kurikulum</option>
                            <option value="views_desc">Paling Sering Dimainkan</option>
                            <option value="judul_asc">Nama Media (A - Z)</option>
                            <option value="terbaru">Media Terbaru</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 2: Carousel Chip Horizontal untuk KELAS (Touch-Friendly Horizontal Scroll) -->
                <div class="dunia-kelas-filter-section">
                    <div class="dunia-filter-sublabel">
                        <i class="fas fa-school"></i> Pilih Tingkat Kelas
                    </div>
                    <div class="dunia-chips-scroll" id="scrollChipKelas">
                        @php
                            $kelasAktif = request('kelas', 'semua');
                            $hitungPerKelas = [];
                            foreach ($medias as $m) {
                                $k = $m->kelas ?: 'Semua Kelas';
                                $hitungPerKelas[$k] = ($hitungPerKelas[$k] ?? 0) + 1;
                            }
                        @endphp

                        <button type="button" class="dunia-chip-btn {{ $kelasAktif === 'semua' ? 'active' : '' }}" data-kelas="semua">
                            <span>🏫 Semua Kelas</span>
                            <span class="badge-count">{{ $medias->count() }}</span>
                        </button>

                        @foreach ($daftarKelas as $kls)
                            @if ($kls !== 'Semua Kelas')
                                @php
                                    $jml = $hitungPerKelas[$kls] ?? 0;
                                @endphp
                                <button type="button" class="dunia-chip-btn {{ $kelasAktif === $kls ? 'active' : '' }}" data-kelas="{{ $kls }}">
                                    <span>{{ $kls }}</span>
                                    <span class="badge-count">{{ $jml }}</span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Baris 3: Filter Mata Pelajaran & Tipe Media -->
                <div class="dunia-filter-bottom">
                    <div class="dunia-filter-row-left">
                        <!-- Dropdown Mapel -->
                        <select id="selectMapel" class="dunia-select-pill">
                            <option value="semua">📚 Semua Mata Pelajaran</option>
                            @foreach ($daftarMapel as $mapel)
                                <option value="{{ $mapel }}" {{ request('mapel') === $mapel ? 'selected' : '' }}>
                                    {{ $mapel }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Tipe Sumber Media -->
                        <div class="dunia-type-pills" id="groupTypePills">
                            <button type="button" class="dunia-type-pill-btn {{ !request('tipe') || request('tipe') === 'semua' ? 'active' : '' }}" data-tipe="semua">
                                Semua Format
                            </button>
                            <button type="button" class="dunia-type-pill-btn {{ request('tipe') === 'kode_interaktif' ? 'active' : '' }}" data-tipe="kode_interaktif">
                                ⚡ Interaktif
                            </button>
                            <button type="button" class="dunia-type-pill-btn {{ request('tipe') === 'link' ? 'active' : '' }}" data-tipe="link">
                                🔗 Web Edukasi
                            </button>
                        </div>
                    </div>

                    <button type="button" id="btnResetFilter" class="dunia-reset-btn">
                        <i class="fas fa-undo-alt"></i> Reset Filter
                    </button>
                </div>

            </div>

            <!-- Baris Informasi Hasil & Filter Aktif -->
            <div class="dunia-results-bar">
                <div class="dunia-results-count">
                    <i class="fas fa-layer-group text-success"></i>
                    <span>Menampilkan <strong id="countTampil">{{ $medias->count() }}</strong> dari {{ $totalSemua }} media pembelajaran</span>
                </div>

                <div class="dunia-active-filters-wrap" id="wrapActiveFilters">
                    <!-- Badge filter aktif disisipkan lewat JS -->
                </div>
            </div>

            <!-- =========================================================================
                 GRID KARTU MEDIA PEMBELAJARAN
                 ========================================================================= -->
            <div class="dunia-media-grid" id="gridMediaDunia">
                @foreach ($medias as $media)
                    @php
                        $kls = $media->kelas ?: 'Semua Kelas';
                        $mpl = $media->mapel ?: 'Umum / Lintas Mapel';
                        $isKode = $media->tipe_sumber === 'kode_interaktif';
                    @endphp
                    <a href="{{ $media->url_tujuan }}"
                       target="{{ $isKode ? '_self' : '_blank' }}"
                       @if(!$isKode) rel="noopener noreferrer" @endif
                       class="dunia-item-card"
                       data-id="{{ $media->id }}"
                       data-judul="{{ strtolower($media->judul) }}"
                       data-deskripsi="{{ strtolower($media->deskripsi ?? '') }}"
                       data-kelas="{{ $kls }}"
                       data-mapel="{{ $mpl }}"
                       data-tipe="{{ $media->tipe_sumber }}"
                       data-urutan="{{ $media->urutan }}"
                       data-views="{{ (int) $media->views }}"
                       title="Buka {{ $media->judul }}">

                        <!-- Banner Atas Warna Gradasi -->
                        <div class="dunia-item-banner {{ $media->warna_gradasi }}">
                            <!-- Badge Kelas -->
                            <span class="dunia-badge-kelas-top">
                                <i class="fas fa-user-graduate"></i> {{ $kls }}
                            </span>

                            <!-- Badge Tipe -->
                            @if ($isKode)
                                <span class="dunia-badge-tipe-top">
                                    <i class="fas fa-bolt"></i> APP INTERAKTIF
                                </span>
                            @else
                                <span class="dunia-badge-tipe-top dunia-badge-tipe-link">
                                    <i class="fas fa-external-link-alt"></i> WEB EDUKASI
                                </span>
                            @endif

                            <!-- Thumbnail Gambar -->
                            <div class="dunia-thumb-box">
                                <img src="{{ $media->gambar_url }}" alt="{{ $media->judul }}" class="dunia-thumb-img" loading="lazy">
                            </div>
                        </div>

                        <!-- Konten Kartu -->
                        <div class="dunia-item-body">
                            <div>
                                <span class="dunia-item-mapel-badge">
                                    <i class="fas fa-book-open"></i> {{ $mpl }}
                                </span>

                                <h3 class="dunia-item-title">{{ $media->judul }}</h3>

                                @if (!empty($media->deskripsi))
                                    <p class="dunia-item-desc">{{ $media->deskripsi }}</p>
                                @else
                                    <p class="dunia-item-desc">Media pembelajaran digital untuk mendukung pemahaman santri.</p>
                                @endif
                            </div>

                            <div class="dunia-item-footer">
                                <span class="dunia-item-views">
                                    <i class="far fa-eye"></i> {{ number_format($media->views ?? 1, 0, ',', '.') }} tayangan
                                </span>

                                <span class="dunia-item-action-btn">
                                    <span>{{ $isKode ? 'Mainkan Sekarang' : 'Buka Tautan' }}</span>
                                    <i class="fas {{ $isKode ? 'fa-play-circle' : 'fa-arrow-up-right-from-square' }}"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Empty State Jika Tidak Ada Hasil Filter -->
            <div class="dunia-empty-state" id="emptyStateDunia">
                <div class="dunia-empty-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.35rem; color: #0f172a; margin-bottom: 8px;">
                    Media Tidak Ditemukan
                </h4>
                <p style="font-size: 0.92rem; color: #64748b; max-width: 520px; margin: 0 auto 20px;" id="emptyStatePesan">
                    Tidak ada media pembelajaran yang cocok dengan filter atau kata kunci yang Anda masukkan.
                </p>
                <button type="button" class="btn btn-outline-success radius-8 px-20 py-8 fw-bold text-xs" onclick="resetSemuaFilterDunia()">
                    <i class="fas fa-redo"></i> Reset Filter &amp; Tampilkan Semua Media
                </button>
            </div>

            <!-- Banner Promosi Interaktif MIN 1 Ciamis -->
            <div style="margin-top: 50px; background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-radius: 24px; border: 1.5px solid #bbf7d0; padding: 28px 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 8px 24px rgba(13, 106, 86, 0.06);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 54px; height: 54px; border-radius: 16px; background: linear-gradient(135deg, #0D6A56 0%, #1B9C85 100%); color: #FFE15D; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(13, 106, 86, 0.25);">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 800; margin: 0 0 4px; color: #09332C;">
                            Punya Rekomendasi Media Edukasi / Simulasi Lainnya?
                        </h4>
                        <p style="font-size: 0.88rem; color: #475569; margin: 0; max-width: 640px;">
                            Bapak/Ibu Guru dan santri dapat merekomendasikan aplikasi edukatif baru untuk ditambahkan ke repositori Dunia Ilmu MIN 1 Ciamis melalui Tim Kurikulum &amp; IT.
                        </p>
                    </div>
                </div>
                <a href="{{ route('home') }}#kontak" class="btn btn-primary" style="background: #0D6A56; border-color: #0D6A56; border-radius: 12px; font-weight: 700; font-size: 0.88rem; padding: 11px 24px; box-shadow: 0 4px 12px rgba(13, 106, 86, 0.2);">
                    <i class="fas fa-paper-plane"></i> Hubungi Tim IT
                </a>
            </div>

        </div>
    </div>

    <!-- SCRIPT FILTER REAL-TIME & INTERAKTIF -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('inputCariDunia');
            var btnClearSearch = document.getElementById('btnClearSearch');
            var selectUrutan = document.getElementById('selectUrutan');
            var selectMapel = document.getElementById('selectMapel');
            var groupTypePills = document.getElementById('groupTypePills');
            var scrollChipKelas = document.getElementById('scrollChipKelas');
            var gridMedia = document.getElementById('gridMediaDunia');
            var emptyState = document.getElementById('emptyStateDunia');
            var countElem = document.getElementById('countTampil');
            var wrapActiveFilters = document.getElementById('wrapActiveFilters');
            var btnResetFilter = document.getElementById('btnResetFilter');

            var filterState = {
                q: (searchInput.value || '').trim().toLowerCase(),
                kelas: '{{ request('kelas', 'semua') }}',
                mapel: '{{ request('mapel', 'semua') }}',
                tipe: '{{ request('tipe', 'semua') }}',
                sort: 'urutan_asc'
            };

            // Handler Search Input
            searchInput.addEventListener('input', function() {
                filterState.q = this.value.trim().toLowerCase();
                btnClearSearch.style.display = this.value.length > 0 ? 'flex' : 'none';
                terapkanFilter();
            });

            btnClearSearch.addEventListener('click', function() {
                searchInput.value = '';
                filterState.q = '';
                this.style.display = 'none';
                searchInput.focus();
                terapkanFilter();
            });

            if (searchInput.value.length > 0) {
                btnClearSearch.style.display = 'flex';
            }

            // Handler Chip Kelas
            scrollChipKelas.addEventListener('click', function(e) {
                var btn = e.target.closest('.dunia-chip-btn');
                if (!btn) return;
                var kls = btn.getAttribute('data-kelas');
                filterState.kelas = kls;

                scrollChipKelas.querySelectorAll('.dunia-chip-btn').forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');

                // Smooth scroll chip into center of screen on mobile
                btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                terapkanFilter();
            });

            // Handler Select Mapel
            selectMapel.addEventListener('change', function() {
                filterState.mapel = this.value;
                terapkanFilter();
            });

            // Handler Tipe Media Pills
            groupTypePills.addEventListener('click', function(e) {
                var btn = e.target.closest('.dunia-type-pill-btn');
                if (!btn) return;
                var tipe = btn.getAttribute('data-tipe');
                filterState.tipe = tipe;

                groupTypePills.querySelectorAll('.dunia-type-pill-btn').forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
                terapkanFilter();
            });

            // Handler Sort Select
            selectUrutan.addEventListener('change', function() {
                filterState.sort = this.value;
                terapkanUrutan();
            });

            btnResetFilter.addEventListener('click', function() {
                resetSemuaFilterDunia();
            });

            window.resetSemuaFilterDunia = function() {
                filterState.q = '';
                filterState.kelas = 'semua';
                filterState.mapel = 'semua';
                filterState.tipe = 'semua';
                filterState.sort = 'urutan_asc';

                searchInput.value = '';
                btnClearSearch.style.display = 'none';
                selectMapel.value = 'semua';
                selectUrutan.value = 'urutan_asc';

                scrollChipKelas.querySelectorAll('.dunia-chip-btn').forEach(function(b) {
                    if (b.getAttribute('data-kelas') === 'semua') {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });

                groupTypePills.querySelectorAll('.dunia-type-pill-btn').forEach(function(b) {
                    if (b.getAttribute('data-tipe') === 'semua') {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });

                terapkanFilter();
            };

            function terapkanFilter() {
                var cards = Array.from(gridMedia.querySelectorAll('.dunia-item-card'));
                var tampilCount = 0;

                cards.forEach(function(card) {
                    var judul = card.getAttribute('data-judul') || '';
                    var deskripsi = card.getAttribute('data-deskripsi') || '';
                    var kelas = card.getAttribute('data-kelas') || '';
                    var mapel = card.getAttribute('data-mapel') || '';
                    var tipe = card.getAttribute('data-tipe') || '';

                    // Match Text Query
                    var matchQ = true;
                    if (filterState.q) {
                        matchQ = judul.indexOf(filterState.q) !== -1 ||
                                 deskripsi.indexOf(filterState.q) !== -1 ||
                                 mapel.toLowerCase().indexOf(filterState.q) !== -1 ||
                                 kelas.toLowerCase().indexOf(filterState.q) !== -1;
                    }

                    // Match Kelas
                    var matchKelas = true;
                    if (filterState.kelas !== 'semua') {
                        matchKelas = (kelas === filterState.kelas);
                    }

                    // Match Mapel
                    var matchMapel = true;
                    if (filterState.mapel !== 'semua') {
                        matchMapel = (mapel === filterState.mapel);
                    }

                    // Match Tipe
                    var matchTipe = true;
                    if (filterState.tipe !== 'semua') {
                        matchTipe = (tipe === filterState.tipe);
                    }

                    if (matchQ && matchKelas && matchMapel && matchTipe) {
                        card.style.display = 'flex';
                        tampilCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                countElem.textContent = tampilCount;

                if (tampilCount === 0) {
                    gridMedia.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    gridMedia.style.display = 'grid';
                    emptyState.style.display = 'none';
                }

                updateActiveFilterTags();
                terapkanUrutan();
            }

            function terapkanUrutan() {
                var cards = Array.from(gridMedia.querySelectorAll('.dunia-item-card'));
                cards.sort(function(a, b) {
                    if (filterState.sort === 'views_desc') {
                        var vA = parseInt(a.getAttribute('data-views') || '0', 10);
                        var vB = parseInt(b.getAttribute('data-views') || '0', 10);
                        return vB - vA;
                    } else if (filterState.sort === 'judul_asc') {
                        var jA = a.getAttribute('data-judul') || '';
                        var jB = b.getAttribute('data-judul') || '';
                        return jA.localeCompare(jB);
                    } else if (filterState.sort === 'terbaru') {
                        var idA = parseInt(a.getAttribute('data-id') || '0', 10);
                        var idB = parseInt(b.getAttribute('data-id') || '0', 10);
                        return idB - idA;
                    } else {
                        // urutan_asc
                        var uA = parseInt(a.getAttribute('data-urutan') || '9999', 10);
                        var uB = parseInt(b.getAttribute('data-urutan') || '9999', 10);
                        return uA - uB;
                    }
                });

                cards.forEach(function(card) {
                    gridMedia.appendChild(card);
                });
            }

            function updateActiveFilterTags() {
                wrapActiveFilters.innerHTML = '';
                var isAnyFilterActive = false;

                if (filterState.q) {
                    isAnyFilterActive = true;
                    wrapActiveFilters.appendChild(createTag('Cari: "' + filterState.q + '"', function() {
                        searchInput.value = '';
                        filterState.q = '';
                        btnClearSearch.style.display = 'none';
                        terapkanFilter();
                    }));
                }

                if (filterState.kelas !== 'semua') {
                    isAnyFilterActive = true;
                    wrapActiveFilters.appendChild(createTag('Kelas: ' + filterState.kelas, function() {
                        filterState.kelas = 'semua';
                        scrollChipKelas.querySelectorAll('.dunia-chip-btn').forEach(function(b) {
                            if (b.getAttribute('data-kelas') === 'semua') b.classList.add('active');
                            else b.classList.remove('active');
                        });
                        terapkanFilter();
                    }));
                }

                if (filterState.mapel !== 'semua') {
                    isAnyFilterActive = true;
                    wrapActiveFilters.appendChild(createTag('Mapel: ' + filterState.mapel, function() {
                        filterState.mapel = 'semua';
                        selectMapel.value = 'semua';
                        terapkanFilter();
                    }));
                }

                if (filterState.tipe !== 'semua') {
                    isAnyFilterActive = true;
                    var labelTipe = filterState.tipe === 'kode_interaktif' ? 'Aplikasi Interaktif' : 'Web Edukasi';
                    wrapActiveFilters.appendChild(createTag('Format: ' + labelTipe, function() {
                        filterState.tipe = 'semua';
                        groupTypePills.querySelectorAll('.dunia-type-pill-btn').forEach(function(b) {
                            if (b.getAttribute('data-tipe') === 'semua') b.classList.add('active');
                            else b.classList.remove('active');
                        });
                        terapkanFilter();
                    }));
                }

                btnResetFilter.style.display = isAnyFilterActive ? 'inline-flex' : 'none';
            }

            function createTag(text, onRemove) {
                var span = document.createElement('span');
                span.className = 'dunia-active-tag';
                span.innerHTML = text + ' <span class="tag-close">&times;</span>';
                span.querySelector('.tag-close').addEventListener('click', function(e) {
                    e.stopPropagation();
                    onRemove();
                });
                return span;
            }

            // Inisialisasi awal
            terapkanFilter();
        });
    </script>
@endsection
