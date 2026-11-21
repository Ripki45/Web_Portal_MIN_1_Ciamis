@extends('layouts.frontend')

@section('title', 'Katalog Program Unggulan — MIN 1 Ciamis')

@section('content')

    <style>
        .program-page-wrap {
            padding: 36px 0 84px;
            background: #f8fafc;
            min-height: 85vh;
        }

        /* Breadcrumb Bar */
        .program-breadcrumb-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .program-breadcrumb-bar a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .program-breadcrumb-bar a:hover {
            text-decoration: underline;
        }

        /* Hero Banner Program Unggulan (Modern Emerald with Gold Ambient Glow) */
        .program-hero-card {
            background: linear-gradient(135deg, #06372D 0%, #0D6A56 50%, #15803D 100%);
            border-radius: 28px;
            padding: 46px 36px;
            color: #ffffff;
            margin-bottom: 34px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(6, 55, 45, 0.18);
        }
        .program-hero-card::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 225, 93, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .program-hero-card::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 15%;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(45, 212, 191, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .program-hero-badge {
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
        .program-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.45rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 14px;
        }
        .program-hero-desc {
            font-size: 1.02rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 820px;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .program-hero-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .program-hero-stat-pill {
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
        .program-hero-stat-pill i {
            color: #FFE15D;
        }

        /* ==========================================================================
           FILTER & SEARCH COMPONENT (MODERN & TOUCH-FRIENDLY)
           ========================================================================== */
        .program-filter-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 20px 24px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            margin-bottom: 32px;
        }
        .program-filter-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .program-search-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }
        .program-search-wrap .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
        }
        .program-search-input {
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
        .program-search-input:focus {
            outline: none;
            border-color: #1B9C85;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(27, 156, 133, 0.14);
        }
        .program-search-clear {
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
        }

        /* Horizontal Category Chips Carousel */
        .program-chips-scroll {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 4px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .program-chips-scroll::-webkit-scrollbar {
            display: none;
        }
        .program-chip-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f1f5f9;
            color: #334155;
            border: 1.5px solid #e2e8f0;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.83rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            flex-shrink: 0;
        }
        .program-chip-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .program-chip-btn.active {
            background: linear-gradient(135deg, #0D6A56 0%, #15803D 100%);
            color: #ffffff;
            border-color: #0D6A56;
            box-shadow: 0 4px 14px rgba(13, 106, 86, 0.25);
        }
        .program-chip-btn .chip-count {
            background: rgba(255, 255, 255, 0.25);
            font-size: 0.7rem;
            padding: 2px 7px;
            border-radius: 9999px;
        }
        .program-chip-btn:not(.active) .chip-count {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Results Bar */
        .program-results-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .program-results-count {
            font-size: 0.92rem;
            color: #334155;
            font-weight: 600;
        }
        .program-results-count strong {
            color: #0D6A56;
            font-weight: 800;
        }

        /* ==========================================================================
           GRID KARTU PROGRAM UNGGULAN (MODERN SHOWCASE CARD)
           ========================================================================== */
        .program-showcase-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        .program-showcase-card {
            background: #ffffff;
            border-radius: 26px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.32s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
            position: relative;
        }
        .program-showcase-card:hover {
            transform: translateY(-8px);
            border-color: #1B9C85;
            box-shadow: 0 22px 45px rgba(27, 156, 133, 0.16);
        }

        /* Banner Sampul */
        .program-card-thumb-wrap {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: #0f172a;
        }
        .program-card-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .program-showcase-card:hover .program-card-thumb-img {
            transform: scale(1.06);
        }
        .program-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.1) 0%, rgba(15, 23, 42, 0.65) 100%);
            pointer-events: none;
        }
        .program-card-badge-floating {
            position: absolute;
            top: 14px;
            left: 16px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(8px);
            color: #065f46;
            font-size: 0.74rem;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 9999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 2;
        }
        .program-card-badge-floating i {
            color: #0D6A56;
        }
        .program-card-order-pill {
            position: absolute;
            top: 14px;
            right: 16px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: #FFE15D;
            border: 1px solid rgba(255, 225, 93, 0.4);
            font-size: 0.72rem;
            font-weight: 800;
            padding: 4px 11px;
            border-radius: 9999px;
            z-index: 2;
        }

        /* Body Kartu */
        .program-card-content {
            padding: 26px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
        }
        .program-card-main-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 10px;
            transition: color 0.2s ease;
        }
        .program-showcase-card:hover .program-card-main-title {
            color: #0D6A56;
        }
        .program-card-summary {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 18px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Poin Fitur Ceklis */
        .program-features-list {
            list-style: none;
            padding: 0;
            margin: 0 0 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .program-features-list li {
            font-size: 0.82rem;
            color: #334155;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-weight: 600;
            line-height: 1.45;
        }
        .program-features-list li i {
            color: #0D6A56;
            font-size: 0.92rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Kutipan Tagline Mini */
        .program-quote-pill {
            background: #f0fdf4;
            border-left: 3px solid #0D6A56;
            padding: 8px 14px;
            border-radius: 0 10px 10px 0;
            font-size: 0.8rem;
            color: #065f46;
            font-style: italic;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Footer Kartu & Tombol Selengkapnya */
        .program-card-footer-meta {
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .program-card-author-info {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .program-card-btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .program-showcase-card:hover .program-card-btn-action {
            background: linear-gradient(135deg, #0D6A56 0%, #15803D 100%);
            color: #ffffff;
            border-color: #0D6A56;
            transform: translateX(3px);
            box-shadow: 0 4px 12px rgba(13, 106, 86, 0.25);
        }

        /* Empty State */
        .program-empty-state {
            display: none;
            text-align: center;
            padding: 60px 24px;
            background: #ffffff;
            border-radius: 24px;
            border: 1.5px dashed #cbd5e1;
            margin-top: 20px;
        }
        .program-empty-icon {
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

        /* Dark Mode Theme */
        [data-bs-theme="dark"] .program-page-wrap {
            background: #0b1519;
        }
        [data-bs-theme="dark"] .program-breadcrumb-bar {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .program-breadcrumb-bar a {
            color: #34d399;
        }
        [data-bs-theme="dark"] .program-filter-card {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        [data-bs-theme="dark"] .program-search-input {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .program-search-input:focus {
            background: #1e3740;
            border-color: #34d399;
        }
        [data-bs-theme="dark"] .program-chip-btn {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .program-chip-btn:hover {
            background: #243f4a;
            color: #ffffff;
        }
        [data-bs-theme="dark"] .program-chip-btn.active {
            background: linear-gradient(135deg, #1B9C85 0%, #0D6A56 100%);
            color: #ffffff;
            border-color: #34d399;
        }
        [data-bs-theme="dark"] .program-results-count {
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .program-results-count strong {
            color: #34d399;
        }
        [data-bs-theme="dark"] .program-showcase-card {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }
        [data-bs-theme="dark"] .program-showcase-card:hover {
            border-color: #34d399;
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.45);
        }
        [data-bs-theme="dark"] .program-card-main-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .program-showcase-card:hover .program-card-main-title {
            color: #34d399;
        }
        [data-bs-theme="dark"] .program-card-summary {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .program-features-list li {
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .program-features-list li i {
            color: #34d399;
        }
        [data-bs-theme="dark"] .program-quote-pill {
            background: rgba(13, 106, 86, 0.2);
            color: #a7f3d0;
            border-left-color: #34d399;
        }
        [data-bs-theme="dark"] .program-card-footer-meta {
            border-top-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .program-card-author-info {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .program-card-btn-action {
            background: #1a2f37;
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.3);
        }
        [data-bs-theme="dark"] .program-empty-state {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            .program-hero-card {
                padding: 30px 22px;
                border-radius: 20px;
                margin-bottom: 22px;
            }
            .program-hero-title {
                font-size: 1.8rem;
            }
            .program-hero-desc {
                font-size: 0.92rem;
            }
            .program-filter-card {
                padding: 16px;
                border-radius: 18px;
            }
            .program-showcase-grid {
                grid-template-columns: 1fr;
                gap: 22px;
            }
        }
    </style>

    <div class="program-page-wrap">
        <div class="container">

            <!-- Breadcrumb Navigation -->
            <div class="program-breadcrumb-bar">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                <i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i>
                <a href="{{ route('home') }}#program"><i class="fas fa-star"></i> Program Unggulan</a>
                <i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i>
                <span>Direktori Lengkap</span>
            </div>

            <!-- Hero Section -->
            <div class="program-hero-card">
                <div class="program-hero-badge">
                    <i class="fas fa-award"></i> PROGRAM UNGGULAN &amp; PRESTASI MADRASAH
                </div>
                <h1 class="program-hero-title">Katalog Program Unggulan MIN 1 Ciamis</h1>
                <p class="program-hero-desc">
                    Mewujudkan ekosistem pendidikan dasar Islam yang seimbang dan adaptif: mengakar kuat dalam nilai Qur'ani, unggul dalam penguasaan sains dan literasi digital, serta menumbuhkan potensi fitrah santri dalam lingkungan belajar Sekolah Ramah Anak yang penuh kasih sayang.
                </p>

                <div class="program-hero-stats">
                    <div class="program-hero-stat-pill">
                        <i class="fas fa-layer-group"></i>
                        <span><strong>{{ $totalSemua }}</strong> Program Unggulan</span>
                    </div>
                    <div class="program-hero-stat-pill">
                        <i class="fas fa-child-reaching"></i>
                        <span>Sekolah Ramah Anak</span>
                    </div>
                    <div class="program-hero-stat-pill">
                        <i class="fas fa-quran"></i>
                        <span>Tahfidz Juz 30</span>
                    </div>
                    <div class="program-hero-stat-pill">
                        <i class="fas fa-flask"></i>
                        <span>Sains &amp; Riset KSM</span>
                    </div>
                    <div class="program-hero-stat-pill">
                        <i class="fas fa-laptop-code"></i>
                        <span>Madrasah Digital</span>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Component -->
            <div class="program-filter-card">
                <div class="program-filter-top">
                    <div class="program-search-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="inputCariProgram" class="program-search-input"
                               placeholder="Cari program unggulan (contoh: Sekolah Ramah Anak, Tahfidz, KSM Sains, Literasi, Adiwiyata)..."
                               value="{{ request('q', '') }}"
                               autocomplete="off">
                        <button type="button" id="btnClearSearchProgram" class="program-search-clear" title="Bersihkan">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Category Filter Chips -->
                <div class="program-chips-scroll" id="scrollChipKategori">
                    <button type="button" class="program-chip-btn active" data-kategori="semua">
                        <span>⭐ Semua Program</span>
                        <span class="chip-count">{{ $programs->count() }}</span>
                    </button>

                    @foreach ($daftarKategori as $kat)
                        @php
                            $jmlKat = $programs->where('kategori_badge', $kat)->count();
                        @endphp
                        <button type="button" class="program-chip-btn" data-kategori="{{ $kat }}">
                            <span>{{ $kat }}</span>
                            <span class="chip-count">{{ $jmlKat }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Results Counter -->
            <div class="program-results-bar">
                <div class="program-results-count">
                    <i class="fas fa-check-double text-success"></i>
                    <span>Menampilkan <strong id="countProgramTampil">{{ $programs->count() }}</strong> dari {{ $totalSemua }} Program Unggulan</span>
                </div>
            </div>

            <!-- Showcase Grid Program Unggulan -->
            <div class="program-showcase-grid" id="gridProgramUnggulan">
                @foreach ($programs as $prog)
                    @php
                        $poinArr = $prog->poin_list;
                    @endphp
                    <div class="program-showcase-card"
                         data-id="{{ $prog->id }}"
                         data-judul="{{ strtolower($prog->judul) }}"
                         data-kategori="{{ $prog->kategori_badge }}"
                         data-ringkasan="{{ strtolower($prog->ringkasan ?? '') }}">

                        <div>
                            <!-- Banner Sampul -->
                            <div class="program-card-thumb-wrap">
                                <img src="{{ $prog->gambar_url }}" alt="{{ $prog->judul }}" class="program-card-thumb-img" loading="lazy">
                                <div class="program-card-overlay"></div>

                                <span class="program-card-badge-floating">
                                    <i class="{{ $prog->icon }}"></i> {{ $prog->kategori_badge }}
                                </span>

                                <span class="program-card-order-pill">
                                    Unggulan #{{ $prog->urutan }}
                                </span>
                            </div>

                            <!-- Body Kartu -->
                            <div class="program-card-content">
                                <div>
                                    <h3 class="program-card-main-title">{{ $prog->judul }}</h3>
                                    <p class="program-card-summary">{{ $prog->ringkasan }}</p>

                                    @if (!empty($prog->kutipan))
                                        <div class="program-quote-pill">
                                            "{{ $prog->kutipan }}"
                                        </div>
                                    @endif

                                    @if (!empty($poinArr))
                                        <ul class="program-features-list">
                                            @foreach (array_slice($poinArr, 0, 4) as $fitur)
                                                <li>
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>{{ $fitur }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <div class="program-card-footer-meta">
                                    <div class="program-card-author-info">
                                        <i class="fas fa-user-shield text-success"></i>
                                        <span>{{ Str::limit($prog->penulis, 24) }}</span>
                                    </div>

                                    <a href="{{ route('program-unggulan.detail', $prog->slug) }}" class="program-card-btn-action">
                                        <span>Pelajari Detail</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Empty State Jika Tidak Ditemukan -->
            <div class="program-empty-state" id="emptyStateProgram">
                <div class="program-empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.35rem; color: #0f172a; margin-bottom: 8px;">
                    Program Tidak Ditemukan
                </h4>
                <p style="font-size: 0.92rem; color: #64748b; max-width: 520px; margin: 0 auto 20px;">
                    Tidak ada program unggulan yang cocok dengan kata kunci atau filter yang Anda pilih.
                </p>
                <button type="button" class="btn btn-outline-success radius-8 px-20 py-8 fw-bold text-xs" onclick="resetPencarianProgram()">
                    <i class="fas fa-redo"></i> Tampilkan Semua Program
                </button>
            </div>

            <!-- Bottom Call-To-Action (Konsultasi & Pendaftaran PPDB) -->
            <div style="margin-top: 56px; background: linear-gradient(135deg, #09332C 0%, #0D6A56 100%); border-radius: 26px; padding: 36px 38px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 24px; box-shadow: 0 16px 36px rgba(9, 51, 44, 0.2);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 56px; height: 56px; border-radius: 18px; background: rgba(255, 225, 93, 0.2); border: 1.5px solid rgba(255, 225, 93, 0.4); color: #FFE15D; display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.3rem; font-weight: 800; color: #ffffff; margin: 0 0 6px;">
                            Ingin Putra-Putri Anda Mengikuti Program Unggulan Kami?
                        </h4>
                        <p style="font-size: 0.92rem; color: rgba(255, 255, 255, 0.88); margin: 0; max-width: 680px; line-height: 1.55;">
                            Daftarkan putra-putri Anda pada Penerimaan Peserta Didik Baru (PPDB) MIN 1 Ciamis atau konsultasikan minat &amp; bakat bersama tim madrasah.
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}#kontak" class="btn btn-outline-light" style="border-radius: 12px; font-weight: 700; font-size: 0.88rem; padding: 11px 22px;">
                        <i class="fas fa-comments"></i> Tanya Kami
                    </a>
                    <a href="{{ route('home') }}#banner-ppdb" class="btn btn-warning" style="background: #FFE15D; border-color: #FFE15D; color: #09332C; border-radius: 12px; font-weight: 800; font-size: 0.88rem; padding: 11px 24px;">
                        <i class="fas fa-user-plus"></i> Informasi PPDB
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT FILTER REAL-TIME & INSTANT CHIPS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('inputCariProgram');
            var btnClear = document.getElementById('btnClearSearchProgram');
            var chipsScroll = document.getElementById('scrollChipKategori');
            var grid = document.getElementById('gridProgramUnggulan');
            var emptyState = document.getElementById('emptyStateProgram');
            var countElem = document.getElementById('countProgramTampil');

            var filterState = {
                q: (searchInput.value || '').trim().toLowerCase(),
                kategori: 'semua'
            };

            searchInput.addEventListener('input', function() {
                filterState.q = this.value.trim().toLowerCase();
                btnClear.style.display = this.value.length > 0 ? 'flex' : 'none';
                terapkanFilter();
            });

            btnClear.addEventListener('click', function() {
                searchInput.value = '';
                filterState.q = '';
                this.style.display = 'none';
                searchInput.focus();
                terapkanFilter();
            });

            chipsScroll.addEventListener('click', function(e) {
                var btn = e.target.closest('.program-chip-btn');
                if (!btn) return;
                var kat = btn.getAttribute('data-kategori');
                filterState.kategori = kat;

                chipsScroll.querySelectorAll('.program-chip-btn').forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');

                btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                terapkanFilter();
            });

            window.resetPencarianProgram = function() {
                filterState.q = '';
                filterState.kategori = 'semua';
                searchInput.value = '';
                btnClear.style.display = 'none';

                chipsScroll.querySelectorAll('.program-chip-btn').forEach(function(b) {
                    if (b.getAttribute('data-kategori') === 'semua') b.classList.add('active');
                    else b.classList.remove('active');
                });

                terapkanFilter();
            };

            function terapkanFilter() {
                var cards = grid.querySelectorAll('.program-showcase-card');
                var matched = 0;

                cards.forEach(function(card) {
                    var judul = card.getAttribute('data-judul') || '';
                    var ringkasan = card.getAttribute('data-ringkasan') || '';
                    var kategori = card.getAttribute('data-kategori') || '';

                    var matchQ = true;
                    if (filterState.q) {
                        matchQ = judul.indexOf(filterState.q) !== -1 ||
                                 ringkasan.indexOf(filterState.q) !== -1 ||
                                 kategori.toLowerCase().indexOf(filterState.q) !== -1;
                    }

                    var matchKat = true;
                    if (filterState.kategori !== 'semua') {
                        matchKat = (kategori === filterState.kategori);
                    }

                    if (matchQ && matchKat) {
                        card.style.display = 'flex';
                        matched++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                countElem.textContent = matched;

                if (matched === 0) {
                    grid.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    grid.style.display = 'grid';
                    emptyState.style.display = 'none';
                }
            }
        });
    </script>
@endsection
