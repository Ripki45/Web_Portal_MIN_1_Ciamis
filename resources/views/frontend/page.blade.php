@extends('layouts.frontend')

@section('title', $halaman->judul . ' — MIN 1 Ciamis')

@section('content')

    <style>
        .page-detail-wrap {
            background: #ffffff;
            color: #1e293b;
            padding-bottom: 70px;
        }

        /* Breadcrumb Bar */
        .page-breadcrumb-nav {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 0;
            margin-bottom: 30px;
        }
        .page-breadcrumb-list {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
            padding: 0;
            list-style: none;
            flex-wrap: wrap;
        }
        .page-breadcrumb-list a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .page-breadcrumb-list a:hover {
            text-decoration: underline;
        }

        /* Header Judul Halaman */
        .page-header-card {
            margin-bottom: 30px;
        }
        .page-badge-category {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(13, 106, 86, 0.1);
            color: #0D6A56;
            padding: 5px 16px;
            border-radius: 9999px;
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
        }
        .page-main-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 16px;
        }
        .page-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 14px;
            margin-bottom: 28px;
        }
        .page-meta-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 0.84rem;
            color: #64748b;
        }
        .page-meta-author {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Kutipan / Blockquote (Model min1kotamalang.sch.id) */
        .page-quote-box {
            background: #fafcfb;
            border-left: 4px solid #0D6A56;
            padding: 22px 24px;
            border-radius: 0 14px 14px 0;
            margin-bottom: 26px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border-top: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }
        .page-quote-text {
            color: #334155;
            font-size: 1.15rem;
            line-height: 1.7;
            margin: 0;
            font-style: italic;
            font-weight: 500;
        }

        /* Banner Gambar Resmi Halaman (Framed Model) */
        .page-featured-frame {
            background: #ffffff;
            padding: 14px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }
        .page-featured-img {
            width: 100%;
            max-height: 440px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        /* Typography Konten Artikel */
        .page-article-content {
            font-size: 1.02rem;
            line-height: 1.8;
            color: #334155;
        }
        .page-article-content h3,
        .page-article-content h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 32px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .page-article-content p {
            margin-bottom: 18px;
            text-align: justify;
        }
        .page-article-content ul,
        .page-article-content ol {
            padding-left: 24px;
            margin-bottom: 22px;
        }
        .page-article-content li {
            margin-bottom: 10px;
            line-height: 1.7;
        }
        .page-article-content strong {
            color: #0f172a;
        }

        /* Dark Mode Theme */
        [data-bs-theme="dark"] .page-detail-wrap {
            background: #0b1519;
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .page-breadcrumb-nav {
            background: #101c21;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .page-breadcrumb-list a {
            color: #34d399;
        }
        [data-bs-theme="dark"] .page-main-title {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .page-meta-row {
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .page-meta-author {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .page-meta-left {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .page-quote-box {
            background: #132228;
            border-left-color: #34d399;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .page-quote-text {
            color: #a7f3d0;
        }
        [data-bs-theme="dark"] .page-featured-frame {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.1);
        }
        [data-bs-theme="dark"] .page-article-content h3,
        [data-bs-theme="dark"] .page-article-content h4 {
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .page-article-content p,
        [data-bs-theme="dark"] .page-article-content li {
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .page-article-content strong {
            color: #ffffff;
        }
    </style>

    <div class="page-detail-wrap">

        <!-- ==========================================
             BREADCRUMB BAR
             ========================================== -->
        <div class="page-breadcrumb-nav">
            <div class="container">
                <ul class="page-breadcrumb-list">
                    <li>
                        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i></li>
                    @if ($halaman->navMenu && $halaman->navMenu->parent)
                        <li>
                            <span class="text-muted">{{ $halaman->navMenu->parent->nama_menu }}</span>
                        </li>
                        <li><i class="fas fa-chevron-right text-xs" style="font-size: 10px;"></i></li>
                    @endif
                    <li class="text-dark fw-bold">{{ $halaman->judul }}</li>

                    @auth
                        <li class="ms-auto">
                            <a href="{{ route('halaman.edit', $halaman->id) }}" class="badge bg-dark text-white text-decoration-none px-10 py-6 radius-6">
                                ✎ Edit Halaman
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="row g-5">

                <!-- KOLOM KIRI (8 KOLOM): KONTEN HALAMAN UTAMA -->
                <div class="col-lg-8">
                    <!-- Header Judul & Meta -->
                    <div class="page-header-card">
                        <span class="page-badge-category">
                            <i class="fas fa-bookmark"></i> {{ $halaman->kategori ?: 'Profil Madrasah' }}
                        </span>

                        <h1 class="page-main-title">{{ $halaman->judul }}</h1>

                        <div class="page-meta-row">
                            <div class="page-meta-left">
                                <div class="page-meta-author">
                                    <img class="avatar avatar-md rounded-circle"
                                         src="{{ asset('backend/assets/images/icon_min.png') }}" alt="MIN 1 Ciamis"
                                         style="width: 32px; height: 32px; object-fit: contain;" />
                                    <span>{{ $halaman->penulis }}</span>
                                </div>
                                <span><i class="far fa-calendar-alt"></i> {{ $halaman->updated_at->translatedFormat('d F Y') }}</span>
                                <span><i class="far fa-clock"></i> {{ $halaman->waktu_baca }}</span>
                            </div>

                            <div>
                                <span class="d-flex align-items-center fs-8 text-muted gap-1">
                                    <i class="far fa-eye text-success"></i>
                                    <span><span class="odometer text-nowrap" data-count="{{ $halaman->views }}"></span> kali dibaca</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Kutipan / Tagline / Visi Madrasah -->
                    @if (!empty($halaman->kutipan))
                        <div class="page-quote-box">
                            <p class="page-quote-text">
                                "{{ $halaman->kutipan }}"
                            </p>
                            <p class="fs-7 mb-0 mt-2 text-muted">— <span class="text-dark fw-semibold">MIN 1 Ciamis</span></p>
                        </div>
                    @endif

                    <!-- Banner Foto Sampul Halaman -->
                    @if ($halaman->gambar)
                        <div class="page-featured-frame">
                            <img class="page-featured-img" src="{{ asset('storage/' . $halaman->gambar) }}"
                                 alt="{{ $halaman->judul }}" loading="lazy" />
                        </div>
                    @endif

                    <!-- Isi Konten Artikel Dinamis -->
                    <div class="page-article-content content-body-madrasah">
                        {!! $halaman->isi_konten !!}
                    </div>

                    <div class="border-top mt-5 pt-4"></div>

                    <!-- Tag & Tombol Bagikan -->
                    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('home') }}" class="btn btn-outline-success btn-sm radius-8 fw-bold">
                                &larr; Beranda
                            </a>
                            <span class="badge bg-light text-dark border">
                                {{ $halaman->kategori }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="text-dark fs-7 fw-semibold">Bagikan:</span>
                            <div class="d-inline-flex gap-2">
                                <a href="https://wa.me/?text={{ urlencode($halaman->judul . ' - ' . request()->fullUrl()) }}"
                                    target="_blank" class="btn btn-sm btn-outline-success radius-8" title="Bagikan ke WhatsApp">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" class="btn btn-sm btn-outline-primary radius-8" title="Bagikan ke Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light border radius-8" onclick="salinTautanHalamanStatis()">
                                    <i class="fas fa-link"></i> Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (4 KOLOM): SIDEBAR MADRASAH & NAVIGASI -->
                <div class="col-lg-4">
                    <div class="row g-4">

                        <!-- Kartu Profil Madrasah -->
                        <div class="col-12">
                            <div class="author-card" style="border-radius: 20px;">
                                <div class="card-img mb-3 text-center">
                                    <img class="rounded-circle avatar-154 p-3 bg-white shadow-sm"
                                        src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Logo MIN 1 Ciamis"
                                        style="object-fit: contain;" />
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="mb-2" style="font-family: 'Outfit', sans-serif; font-weight: 800;">MIN 1 Ciamis</h5>
                                    <p class="mb-4 fs-7 text-muted">
                                        Madrasah Ibtidaiyah Negeri 1 Ciamis — Membina generasi Qur'ani, berakhlak mulia, unggul prestasi sains, dan adaptif terhadap perkembangan teknologi.
                                    </p>
                                    <a href="{{ route('presensi.scanner') }}" class="btn btn-success w-100 button-effect-1"
                                        style="background: #0D6A56; border-color: #0D6A56; font-weight: 700; border-radius: 12px;">
                                        Buka Scanner Presensi QR
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Menu Seinduk / Halaman Terkait Lainnya -->
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="fas fa-layer-group text-success"></i>
                                <h5 class="mb-0 fw-bold" style="font-family: 'Outfit', sans-serif;">Halaman Informasi Terkait</h5>
                            </div>

                            <div class="d-flex flex-column gap-3">
                                @if (isset($menuSeinduk) && $menuSeinduk->isNotEmpty())
                                    @foreach ($menuSeinduk as $ms)
                                        @if ($ms->halaman && $ms->halaman->id !== $halaman->id)
                                            <a href="{{ route('halaman.show', $ms->halaman->slug) }}"
                                               class="d-flex align-items-center justify-content-between p-12 bg-white rounded-12 shadow-2xs border text-decoration-none hover-up">
                                                <div>
                                                    <h6 class="fs-7 fw-bold text-dark mb-1">{{ $ms->nama_menu }}</h6>
                                                    <span class="fs-8 text-muted">{{ $ms->halaman->waktu_baca }}</span>
                                                </div>
                                                <i class="fas fa-chevron-right text-muted fs-8"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif

                                @forelse ($halamanLain as $hl)
                                    <a href="{{ route('halaman.show', $hl->slug) }}"
                                       class="d-flex align-items-center justify-content-between p-12 bg-white rounded-12 shadow-2xs border text-decoration-none hover-up">
                                        <div>
                                            <h6 class="fs-7 fw-bold text-dark mb-1">{{ $hl->judul }}</h6>
                                            <span class="fs-8 text-muted"><i class="far fa-eye"></i> {{ $hl->views }}x dibaca</span>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted fs-8"></i>
                                    </a>
                                @empty
                                    <p class="fs-7 text-muted">Belum ada halaman terkait lainnya.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Data Real-Time Madrasah (Odometer) -->
                        @if (!empty($statMadrasah))
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fas fa-chart-line text-success"></i>
                                    <h5 class="mb-0 fw-bold" style="font-family: 'Outfit', sans-serif;">Data Real-Time Madrasah</h5>
                                </div>
                                <ul class="list-unstyled d-flex flex-wrap gap-2 ps-0">
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
    </div>

    <!-- Script Salin Tautan -->
    <script>
        function salinTautanHalamanStatis() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('✓ Tautan halaman berhasil disalin ke clipboard!');
                }).catch(function() {
                    prompt('Salin tautan ini:', window.location.href);
                });
            } else {
                prompt('Salin tautan ini:', window.location.href);
            }
        }
    </script>

@endsection
