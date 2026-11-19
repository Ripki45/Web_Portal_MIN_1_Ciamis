@extends('layouts.frontend')

@section('title', 'Direktori Layanan Cepat & Integrasi Sistem — MIN 1 Ciamis')

@section('content')

    <style>
        .layanan-page-wrap {
            padding: 36px 0 84px;
            background: #f8fafc;
            min-height: 80vh;
        }

        /* Hero Banner Layanan */
        .layanan-hero-card {
            background: linear-gradient(135deg, #09332C 0%, #0D6A56 50%, #115E59 100%);
            border-radius: 28px;
            padding: 44px 36px;
            color: #ffffff;
            margin-bottom: 36px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(9, 51, 44, 0.18);
        }
        .layanan-hero-card::after {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(255, 225, 93, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .layanan-hero-badge {
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
        .layanan-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 12px;
        }
        .layanan-hero-desc {
            font-size: 1.02rem;
            color: rgba(255, 255, 255, 0.88);
            max-width: 740px;
            line-height: 1.65;
            margin: 0;
        }

        /* Breadcrumb Bar */
        .layanan-breadcrumb-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
        }
        .layanan-breadcrumb-bar a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
        }
        .layanan-breadcrumb-bar a:hover {
            text-decoration: underline;
        }

        /* Kotak Pencarian Real-time */
        .layanan-search-container {
            background: #ffffff;
            border-radius: 20px;
            padding: 16px 20px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            margin-bottom: 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .layanan-search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }
        .layanan-search-input-wrap i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }
        .layanan-search-input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 500;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .layanan-search-input:focus {
            outline: none;
            border-color: #1B9C85;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(27, 156, 133, 0.15);
        }
        .layanan-count-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Grid Kartu Layanan */
        .layanan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        .layanan-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 22px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
        }
        .layanan-card:hover {
            transform: translateY(-6px);
            border-color: #1B9C85;
            box-shadow: 0 18px 36px rgba(27, 156, 133, 0.14);
            color: inherit;
        }
        .layanan-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .layanan-card-icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: linear-gradient(135deg, #1B9C85 0%, #0D6A56 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(13, 106, 86, 0.22);
            transition: transform 0.25s ease;
        }
        .layanan-card:hover .layanan-card-icon {
            transform: scale(1.08) rotate(4deg);
        }
        .layanan-badge-target {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .layanan-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }
        .layanan-card:hover .layanan-card-title {
            color: #1B9C85;
        }
        .layanan-card-url {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: monospace;
            word-break: break-all;
        }
        .layanan-card-footer {
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1B9C85;
        }
        .layanan-card-footer i {
            transition: transform 0.2s ease;
        }
        .layanan-card:hover .layanan-card-footer i {
            transform: translate(3px, -3px);
        }

        /* Empty State */
        .layanan-empty-state {
            background: #ffffff;
            border-radius: 24px;
            padding: 60px 24px;
            text-align: center;
            border: 2px dashed #cbd5e1;
            margin: 20px 0;
            display: none;
        }
        .layanan-empty-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #fef2f2;
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 16px;
        }

        /* Dark Mode Support */
        [data-bs-theme="dark"] .layanan-page-wrap {
            background: #0f172a !important;
        }
        [data-bs-theme="dark"] .layanan-search-container,
        [data-bs-theme="dark"] .layanan-card,
        [data-bs-theme="dark"] .layanan-empty-state {
            background: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        [data-bs-theme="dark"] .layanan-search-input {
            background: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .layanan-card-title {
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .layanan-card:hover .layanan-card-title {
            color: #34d399 !important;
        }
        [data-bs-theme="dark"] .layanan-card-url,
        [data-bs-theme="dark"] .layanan-breadcrumb-bar {
            color: #94a3b8 !important;
        }
        [data-bs-theme="dark"] .layanan-card-footer {
            border-top-color: rgba(255, 255, 255, 0.08) !important;
            color: #34d399 !important;
        }
        [data-bs-theme="dark"] .layanan-badge-target {
            background: rgba(255, 255, 255, 0.06) !important;
            color: #cbd5e1 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        @media (max-width: 768px) {
            .layanan-hero-card {
                padding: 30px 22px;
                border-radius: 20px;
            }
            .layanan-hero-title {
                font-size: 1.75rem;
            }
            .layanan-hero-desc {
                font-size: 0.92rem;
            }
            .layanan-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="layanan-page-wrap">
        <div class="container">

            <!-- Breadcrumb Navigation -->
            <div class="layanan-breadcrumb-bar">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                <i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i>
                <a href="{{ route('home') }}#layanan-cepat">Layanan Cepat</a>
                <i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i>
                <span>Direktori Lengkap</span>
            </div>

            <!-- Hero Section -->
            <div class="layanan-hero-card">
                <div class="layanan-hero-badge">
                    <i class="fas fa-bolt"></i> PORTAL LAYANAN &amp; AKSES CEPAT
                </div>
                <h1 class="layanan-hero-title">Direktori Layanan &amp; Integrasi Digital</h1>
                <p class="layanan-hero-desc">
                    Akses mudah ke berbagai platform administrasi kependidikan, rapor digital madrasah, presensi harian, pangkalan data nasional, serta portal resmi kementerian dalam satu pintu.
                </p>
            </div>

            <!-- Search Bar & Counter -->
            <div class="layanan-search-container">
                <div class="layanan-search-input-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="inputCariLayanan" class="layanan-search-input"
                           placeholder="Ketik nama layanan atau kata kunci (contoh: EMIS, Rapor RDM, Scanner Presensi, Dapodik, BOS)..."
                           onkeyup="filterLayanan(this.value)">
                </div>
                <div class="layanan-count-pill">
                    <i class="fas fa-layer-group"></i>
                    <span>Menampilkan <strong id="angkaHasil">{{ $layananCepats->count() }}</strong> dari {{ $layananCepats->count() }} Layanan</span>
                </div>
            </div>

            <!-- Grid Layanan Cepat -->
            <div class="layanan-grid" id="gridDaftarLayanan">
                @foreach ($layananCepats as $layanan)
                    @php
                        $host = parse_url($layanan->link_url, PHP_URL_HOST);
                        $domainLabel = $host ?: (str_starts_with($layanan->link_url, '/') ? 'Portal Internal MIN 1 Ciamis' : $layanan->link_url);
                    @endphp
                    <a href="{{ $layanan->link_url }}" target="{{ $layanan->target }}"
                       @if ($layanan->target === '_blank') rel="noopener noreferrer" @endif
                       class="layanan-card"
                       data-nama="{{ strtolower($layanan->nama_layanan) }}"
                       data-url="{{ strtolower($layanan->link_url) }}"
                       title="Buka {{ $layanan->nama_layanan }}">
                        <div>
                            <div class="layanan-card-top">
                                <div class="layanan-card-icon">
                                    <i class="{{ $layanan->icon }}"></i>
                                </div>
                                <span class="layanan-badge-target">
                                    @if ($layanan->target === '_blank')
                                        <i class="fas fa-external-link-alt"></i> Eksternal
                                    @else
                                        <i class="fas fa-link"></i> Internal
                                    @endif
                                </span>
                            </div>

                            <h3 class="layanan-card-title">{{ $layanan->nama_layanan }}</h3>
                            <div class="layanan-card-url">
                                <i class="fas fa-globe-americas"></i>
                                <span>{{ $domainLabel }}</span>
                            </div>

                            @if (!empty($layanan->keterangan))
                                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 16px; line-height: 1.5;">
                                    {{ $layanan->keterangan }}
                                </p>
                            @endif
                        </div>

                        <div class="layanan-card-footer">
                            <span>Kunjungi Layanan</span>
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Empty State Pencarian -->
            <div class="layanan-empty-state" id="emptyStateLayanan">
                <div class="layanan-empty-icon">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.35rem; color: #0f172a; margin-bottom: 8px;">
                    Layanan Tidak Ditemukan
                </h4>
                <p style="font-size: 0.92rem; color: #64748b; max-width: 520px; margin: 0 auto 20px;">
                    Tidak ada layanan yang cocok dengan kata kunci "<strong id="teksKataKunci"></strong>". Silakan periksa kembali ejaan atau klik tombol di bawah untuk menampilkan seluruh layanan.
                </p>
                <button type="button" class="btn btn-outline-success radius-8 px-20 py-8 fw-bold text-xs" onclick="resetPencarianLayanan()">
                    <i class="fas fa-redo"></i> Tampilkan Semua Layanan
                </button>
            </div>

            <!-- Info Kontak Bantuan -->
            <div style="margin-top: 48px; background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; padding: 24px 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800; margin: 0 0 2px; color: #0f172a;">
                            Butuh Bantuan Akses Sistem atau Link Rusak?
                        </h4>
                        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
                            Hubungi Tim IT &amp; Tata Usaha MIN 1 Ciamis jika Anda mengalami kendala saat mengakses portal layanan di atas.
                        </p>
                    </div>
                </div>
                <a href="{{ route('home') }}#kontak" class="btn btn-primary" style="background: #0D6A56; border-color: #0D6A56; border-radius: 12px; font-weight: 700; font-size: 0.88rem; padding: 10px 22px;">
                    <i class="fas fa-envelope"></i> Hubungi Kami
                </a>
            </div>

        </div>
    </div>

    <script>
        function filterLayanan(query) {
            var q = query.trim().toLowerCase();
            var cards = document.querySelectorAll('.layanan-card');
            var emptyBox = document.getElementById('emptyStateLayanan');
            var countElem = document.getElementById('angkaHasil');
            var keywordElem = document.getElementById('teksKataKunci');
            var matched = 0;

            cards.forEach(function(card) {
                var nama = card.getAttribute('data-nama') || '';
                var url = card.getAttribute('data-url') || '';

                if (!q || nama.indexOf(q) !== -1 || url.indexOf(q) !== -1) {
                    card.style.display = 'flex';
                    matched++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (countElem) countElem.innerText = matched;

            if (matched === 0) {
                if (emptyBox) emptyBox.style.display = 'block';
                if (keywordElem) keywordElem.innerText = query;
            } else {
                if (emptyBox) emptyBox.style.display = 'none';
            }
        }

        function resetPencarianLayanan() {
            var input = document.getElementById('inputCariLayanan');
            if (input) input.value = '';
            filterLayanan('');
        }
    </script>

@endsection
