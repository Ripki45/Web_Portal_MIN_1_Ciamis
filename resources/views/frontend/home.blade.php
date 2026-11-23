@extends('layouts.frontend')

@section('title', 'Beranda - MIN 1 CIAMIS | Madrasah Ibtidaiyah Negeri 1 Ciamis')

@section('content')

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/home-custom.css') }}">

    @php
        \App\Models\BannerCarousel::pastikanTabelSiap();
        \App\Models\Berita::pastikanTabelSiap();
        \App\Models\DuniaIlmu::pastikanTabelSiap();

        $heroSlides = \App\Models\BannerCarousel::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $fotoBanners = $heroSlides->take(4)->values();

        $beritaSlider = \App\Models\Berita::where('status', 'Tayang')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get()
            ->values();

        $semuaBeritaGrid = \App\Models\Berita::where('status', 'Tayang')
            ->orderBy('is_featured', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $gtks = $gtks ?? \App\Models\Gtk::with(['jabatan', 'posisi'])
            ->orderBy('id', 'asc')
            ->get();

        $sp = \App\Models\SambutanProfil::ambilData();

        $bannerPpdb = $bannerPpdb ?? \App\Models\BannerPpdb::ambilData();

        $layananCepats = $layananCepats ?? \App\Models\LayananCepat::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $listPrograms = isset($programUnggulans) && $programUnggulans->isNotEmpty()
            ? $programUnggulans->take(3)
            : \App\Models\ProgramUnggulan::where('is_active', true)
                ->orderBy('urutan', 'asc')
                ->take(3)
                ->get();

        $duniaIlmus = \App\Models\DuniaIlmu::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $opsiKelasDunia = \App\Models\DuniaIlmu::ambilDaftarKelas();
        $opsiMapelDunia = \App\Models\DuniaIlmu::ambilDaftarMapel();

        $teksRingkas = static function (?string $raw, int $limit = 140): string {
            $plain = trim((string) preg_replace(
                '/\s+/',
                ' ',
                html_entity_decode(strip_tags((string) $raw), ENT_QUOTES, 'UTF-8')
            ));

            return \Illuminate\Support\Str::limit($plain, $limit);
        };
    @endphp

    {{-- 1. HERO SLIDER SECTION --}}
    <section class="hero-slider-section" id="beranda">
        <div class="hero-animated-grid-wrap">
            <div class="hero-animated-grid-bg"></div>
        </div>

        <div class="hero-slider-track">
            @foreach ($heroSlides as $idx => $slide)
                @php $isModeTengah = ($slide->tipe_layout === 'tanpa_foto'); @endphp

                <div class="hero-slide {{ $idx === 0 ? 'active' : '' }}" id="hero-slide-{{ $idx + 1 }}">
                    @if ($isModeTengah)
                        <div class="hero-slide-centered-single">
                            <span class="hero-script-heading">{{ $slide->aksara_kecil ?: 'Selamat datang di' }}</span>
                            <h1 class="hero-bold-title">{{ $slide->aksara_besar ?: $slide->judul }}</h1>

                            @if (! empty($slide->teks_domain))
                                <div class="hero-domain-line">{{ $slide->teks_domain }}</div>
                            @endif

                            <div class="hero-slogan-lines">
                                <span>{{ $slide->keterangan_1 ?: $slide->keterangan }}</span>
                                @if (! empty($slide->keterangan_2))
                                    <span>{{ $slide->keterangan_2 }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="hero-slide-flex-container">
                            <div class="hero-slide-left-text">
                                <span class="hero-script-heading">{{ $slide->aksara_kecil ?: 'Program Unggulan' }}</span>
                                <h2 class="hero-bold-title">{{ $slide->aksara_besar ?: $slide->judul }}</h2>

                                @if (! empty($slide->teks_domain))
                                    <div class="hero-domain-line">{{ $slide->teks_domain }}</div>
                                @endif

                                <div class="hero-slogan-strip">
                                    <div class="hero-slogan-lines hero-slogan-clean">
                                        <span>{{ $slide->keterangan_1 ?: $slide->keterangan }}</span>
                                        @if (! empty($slide->keterangan_2))
                                            <span>{{ $slide->keterangan_2 }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="hero-collage-wrap">
                                <img src="{{ $slide->gambar_url }}"
                                     alt="{{ $slide->aksara_besar }}"
                                     class="hero-collage-main-img"
                                     style="border-radius: 28px; border: 4px solid #fff; box-shadow: 0 16px 36px rgba(15, 23, 42, 0.14);"
                                     onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';" />

                                @if ($slide->tampilkan_stiker)
                                    <img src="{{ asset('frontend/landing/hero-sticker-thtp.png') }}"
                                         alt="Tiada Hari Tanpa Prestasi"
                                         class="hero-sticker-thtp"
                                         onerror="this.style.display='none';" />
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <button type="button" class="slider-arrow prev" aria-label="Slide Sebelumnya"><i class="fas fa-chevron-left"></i></button>
        <button type="button" class="slider-arrow next" aria-label="Slide Berikutnya"><i class="fas fa-chevron-right"></i></button>

        <div class="slider-dots-container">
            @foreach ($heroSlides as $idx => $slide)
                <span class="slider-dot {{ $idx === 0 ? 'active' : '' }}"></span>
            @endforeach
        </div>
    </section>

    {{-- 2. SAMBUTAN KEPALA MADRASAH & SEKILAS MIN 1 CIAMIS --}}
    <section class="section sekilas-section" id="profil">
        <div class="sekilas-gradasi-garis"></div>
        <div class="container" style="position: relative; z-index: 2">
            <div class="sekilas-grid">

                <div class="sekilas-card-container">
                    <div class="sekilas-card-decor-gradient"></div>
                    <div class="sekilas-card-frame">
                        <div class="sekilas-card-img-wrapper">
                            <img src="{{ $sp->foto_kepsek_url }}" alt="{{ $sp->nama_kepsek }}" class="sekilas-card-img"
                                style="object-position: top center;"
                                onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';" />

                            <div style="position: absolute; bottom: 18px; left: 18px; right: 18px; background: rgba(15, 23, 42, 0.86); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1.5px solid rgba(255, 225, 93, 0.55); border-radius: 20px; padding: 14px 18px; color: #ffffff; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28);">
                                <div style="display: inline-block; background: #1B9C85; color: #FFE15D; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 4px;">
                                    {{ $sp->jabatan_kepsek }}
                                </div>
                                <div style="font-family: 'Outfit', sans-serif; font-size: 1.12rem; font-weight: 800; color: #ffffff; line-height: 1.25;">
                                    {{ $sp->nama_kepsek }}
                                </div>
                                @if (! empty($sp->nip_kepsek))
                                    <div style="font-size: 0.76rem; color: #cbd5e1; margin-top: 2px; font-family: monospace;">
                                        NIP. {{ $sp->nip_kepsek }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sekilas-content-wrap">
                    <div class="sekilas-eyebrow">
                        <span class="sekilas-eyebrow-line"></span>
                        <span class="sekilas-eyebrow-text">SAMBUTAN &amp; INFORMASI RESMI MADRASAH</span>
                    </div>

                    <h2 class="sekilas-title" id="judulUtamaSekilas">
                        <span class="title-italic" id="teksKiriJudulSekilas">SAMBUTAN</span>
                        <span class="title-highlight" id="teksKananJudulSekilas">KEPALA MADRASAH</span>
                    </h2>

                    <div class="sekilas-tabs-nav">
                        <button type="button" class="sekilas-tab-btn active" data-tab="sambutan"
                            onclick="switchSekilasTabCustom('sambutan', 'SAMBUTAN', 'KEPALA MADRASAH')">SAMBUTAN</button>
                        <button type="button" class="sekilas-tab-btn" data-tab="profil"
                            onclick="switchSekilasTabCustom('profil', 'PROFIL', 'MIN 1 CIAMIS')">PROFIL</button>
                        <button type="button" class="sekilas-tab-btn" data-tab="visi"
                            onclick="switchSekilasTabCustom('visi', 'VISI', 'MIN 1 CIAMIS')">VISI</button>
                        <button type="button" class="sekilas-tab-btn" data-tab="misi"
                            onclick="switchSekilasTabCustom('misi', 'MISI', 'MIN 1 CIAMIS')">MISI</button>
                        <button type="button" class="sekilas-tab-btn" data-tab="motto"
                            onclick="switchSekilasTabCustom('motto', 'MOTTO', 'MIN 1 CIAMIS')">MOTTO</button>
                    </div>

                    <div class="sekilas-tab-pane" id="sekilas-pane-sambutan" style="display: block">
                        <div class="sekilas-content-tag">KATA SAMBUTAN KEPALA MADRASAH</div>
                        <h3 class="sekilas-quote">“{{ $sp->sambutan_kutipan }}”</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->sambutan_isi }}</div>

                        <div style="display: flex; align-items: center; gap: 12px; margin-top: 18px; padding-top: 14px; border-top: 1px dashed #cbd5e1;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: #EDF6EE; color: #1B9C85; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.95rem;">{{ $sp->nama_kepsek }}</div>
                                <div style="font-size: 0.8rem; color: #1B9C85; font-weight: 700;">{{ $sp->jabatan_kepsek }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="sekilas-tab-pane" id="sekilas-pane-profil" style="display: none">
                        <div class="sekilas-content-tag">PROFIL MADRASAH</div>
                        <h3 class="sekilas-quote">“{{ $sp->profil_kutipan }}”</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->profil_isi }}</div>

                        <div class="sekilas-features-grid">
                            @foreach ($sp->profil_poin ?? [] as $poin)
                                <div class="sekilas-feature-pill">
                                    <i class="{{ $poin['icon'] ?? 'fas fa-check-circle' }}"></i>
                                    <span>{{ $poin['text'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="sekilas-tab-pane" id="sekilas-pane-visi" style="display: none">
                        <div class="sekilas-content-tag">VISI MADRASAH</div>
                        <h3 class="sekilas-quote">“{{ $sp->visi_kutipan }}”</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->visi_isi }}</div>
                    </div>

                    <div class="sekilas-tab-pane" id="sekilas-pane-misi" style="display: none">
                        <div class="sekilas-content-tag">MISI MADRASAH</div>
                        <h3 class="sekilas-quote">“{{ $sp->misi_kutipan }}”</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->misi_isi }}</div>

                        <div class="sekilas-features-grid">
                            @foreach ($sp->misi_poin ?? [] as $poin)
                                <div class="sekilas-feature-pill">
                                    <i class="{{ $poin['icon'] ?? 'fas fa-star' }}"></i>
                                    <span>{{ $poin['text'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="sekilas-tab-pane" id="sekilas-pane-motto" style="display: none">
                        <div class="sekilas-content-tag">MOTTO MADRASAH</div>
                        <h3 class="sekilas-quote">“{{ $sp->motto_kutipan }}”</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->motto_isi }}</div>

                        <div class="motto-trophy-showcase" onclick="triggerPrestasiConfetti(event)"
                            style="cursor: pointer; display: flex; align-items: center; gap: 18px; background: linear-gradient(135deg, #09332C 0%, #115E59 100%); padding: 18px 22px; border-radius: 20px; margin-top: 18px; color: #fff;"
                            title="Klik untuk merayakan!">
                            <div style="font-size: 2.4rem; color: #ffe15d; flex-shrink: 0;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <div style="font-size: 1.2rem; font-weight: 800; color: #ffe15d; font-family: 'Outfit', sans-serif;">
                                    {{ $sp->total_prestasi }}+ Prestasi Juara
                                </div>
                                <p style="margin: 4px 0 0; font-size: 0.86rem; color: rgba(255, 255, 255, 0.9); line-height: 1.45;">
                                    Perolehan piala &amp; medali kejuaraan tingkat Kabupaten Ciamis, Priangan Timur, Provinsi, hingga Nasional.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 3. BANNER PPDB & LAYANAN CEPAT --}}
    @if ($bannerPpdb->is_active || $layananCepats->isNotEmpty())
        <section class="hero-bottom-features-section" id="ppdb">
            <div id="banner-ppdb" style="position: relative; top: -80px;"></div>
            <div class="container">

                @if ($bannerPpdb->is_active)
                    @if ($bannerPpdb->tipe_link === 'popup')
                        <div class="ppdb-landscape-banner-wrap" onclick="openPPDBModal()" title="{{ $bannerPpdb->judul }}">
                            <img src="{{ $bannerPpdb->gambar_url }}" alt="{{ $bannerPpdb->judul }}" class="ppdb-landscape-banner-img" loading="lazy" />
                        </div>
                    @else
                        <a href="{{ $bannerPpdb->link_url }}" target="_blank" rel="noopener noreferrer"
                           class="ppdb-landscape-banner-wrap d-block text-decoration-none" title="{{ $bannerPpdb->judul }}">
                            <img src="{{ $bannerPpdb->gambar_url }}" alt="{{ $bannerPpdb->judul }}" class="ppdb-landscape-banner-img" loading="lazy" />
                        </a>
                    @endif
                @endif

                @if ($layananCepats->isNotEmpty())
                    <div class="layanan-cepat-card" id="layanan-cepat">
                        <div class="layanan-cepat-bg-wave" aria-hidden="true"></div>
                        <div class="layanan-cepat-header">
                            <h2 class="layanan-cepat-title">Layanan Cepat</h2>
                            <p class="layanan-cepat-subtitle">Akses halaman penting website madrasah dengan lebih cepat.</p>
                        </div>

                        <div class="layanan-cepat-grid-custom" id="gridLayananCepat">
                            @foreach ($layananCepats->take(6) as $layanan)
                                <a href="{{ $layanan->link_url }}" target="{{ $layanan->target }}"
                                   @if ($layanan->target === '_blank') rel="noopener noreferrer" @endif
                                   class="layanan-cepat-item" title="{{ $layanan->nama_layanan }}">
                                    <div class="lc-icon"><i class="{{ $layanan->icon }}"></i></div>
                                    <span class="lc-label">{{ $layanan->nama_layanan }}</span>
                                </a>
                            @endforeach
                        </div>

                        <div class="btn-layanan-more-wrap">
                            <a href="{{ route('layanan-cepat.publik') }}" class="btn-layanan-more"
                               title="Buka Seluruh Direktori Layanan Cepat MIN 1 Ciamis">
                                <span>Lihat Selengkapnya ({{ $layananCepats->count() }} Layanan)</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </section>
    @endif

    {{-- 4. CAROUSEL SOROTAN 4 BERITA TERBARU --}}
    @if ($beritaSlider->isNotEmpty())
        <section class="sec-9-home-1 pb-5" id="carousel-banner">
            <div class="custom-container position-relative">
                <div class="block-card-swiper" id="gallery-background">
                    <div class="container">
                        <div class="row align-items-stretch g-5">
                            <div class="col-lg-6">
                                <div class="swiper gallery-left overflow-y-visible">
                                    <div class="swiper-wrapper">
                                        @foreach ($beritaSlider as $idx => $itemBerita)
                                            @php
                                                $bgFoto = isset($fotoBanners[$idx])
                                                    ? $fotoBanners[$idx]->gambar_url
                                                    : ($fotoBanners->first()->gambar_url ?? $itemBerita->gambar_url);
                                            @endphp
                                            <div class="swiper-slide w-100" data-bg="{{ $bgFoto }}">
                                                <div class="article">
                                                    <div class="card-body">
                                                        <a href="{{ route('berita.show', $itemBerita->slug) }}" class="badge bg-2 fs-8 mb-3">
                                                            {{ $itemBerita->kategori }}
                                                        </a>
                                                        <a href="{{ route('berita.show', $itemBerita->slug) }}" class="hover-underline">
                                                            <h5 class="card-title mb-0 text-white changeless">{{ $itemBerita->judul }}</h5>
                                                        </a>
                                                        <p class="card-text text-white mb-0 fs-7 mt-3 changeless text-truncate-2">
                                                            {{ $teksRingkas($itemBerita->ringkasan ?: $itemBerita->isi_konten, 140) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-8 ms-lg-auto align-self-stretch">
                                <div class="swiper gallery-thumbs h-100">
                                    <div class="swiper-wrapper">
                                        @foreach ($beritaSlider as $idx => $itemBerita)
                                            @php
                                                $thumbFoto = isset($fotoBanners[$idx])
                                                    ? $fotoBanners[$idx]->gambar_url
                                                    : $itemBerita->gambar_url;
                                            @endphp
                                            <div class="swiper-slide w-100">
                                                <div class="card-swiper-thumb">
                                                    <div class="thumb changeless">
                                                        <img src="{{ $thumbFoto }}" alt="{{ $itemBerita->judul }}" loading="lazy"
                                                             style="width: 85px; height: 68px; object-fit: cover; border-radius: 10px;">
                                                    </div>
                                                    <div class="card-content">
                                                        <h6 class="text-white mb-0 changeless text-truncate-2">{{ $itemBerita->judul }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 5. DIREKTORI GURU & TENAGA PENDIDIK --}}
    <section class="section guru-tendik-section" id="guru">
        <div class="container">
            <div class="guru-tendik-card">
                <div class="guru-header-row">
                    <div class="guru-header-left">
                        <div class="guru-badge-tag">
                            <i class="fas fa-chalkboard-teacher"></i> DIREKTORI GURU ({{ $gtks->count() }} PENDIDIK)
                        </div>
                        <h2 class="guru-main-title">Guru dan Tenaga Pendidik</h2>
                        <p class="guru-sub-desc">Kenali pendidik yang mendampingi proses belajar siswa MIN 1 Ciamis setiap hari.</p>
                    </div>
                    <div class="guru-header-right">
                        <div class="guru-nav-controls">
                            <button type="button" class="guru-nav-btn" id="guruSliderPrev" aria-label="Geser ke kiri">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="guru-nav-btn" id="guruSliderNext" aria-label="Geser ke kanan">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="guru-slider-viewport" id="guruSliderViewport">
                    <div class="guru-slider-track" id="guruSliderTrack">
                        @forelse ($gtks as $guru)
                            @php
                                $fotoGuru = ! empty($guru->foto)
                                    ? asset('storage/'.$guru->foto)
                                    : 'https://ui-avatars.com/api/?name='.urlencode($guru->nama_lengkap).'&background=0D6A56&color=fff&size=300';
                                $peranGuru = $guru->posisi->nama_posisi ?? ($guru->jabatan->nama_jabatan ?? 'Tenaga Pendidik');
                            @endphp
                            <div class="guru-card" title="{{ $guru->nama_lengkap }}">
                                <div class="guru-card-banner">
                                    <div class="guru-photo-box">
                                        <img src="{{ $fotoGuru }}" alt="{{ $guru->nama_lengkap }}" class="guru-photo-img" loading="lazy">
                                    </div>
                                </div>
                                <div class="guru-card-body">
                                    <h4 class="guru-name">{{ $guru->nama_lengkap }}</h4>
                                    <p class="guru-role">{{ $peranGuru }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada data Guru &amp; Tendik.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. PRESTASI SISWA & DATA MADRASAH --}}
    <section class="section stats-section" id="prestasi">
        <div class="stats-bg-decor"></div>
        <div class="container" style="position: relative; z-index: 2">
            <div class="section-header">
                <span class="section-subtitle white">Capaian Gemilang</span>
                <h2 class="section-title white">Prestasi Siswa &amp; Data Madrasah</h2>
                <p class="section-desc" style="color: #cbd5e1;">Rekap Capaian Kejuaraan &amp; Statistik Akademik MIN 1 Ciamis</p>
                <div class="section-divider"></div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-number" data-target="{{ $totalSiswa ?? 120 }}">{{ $totalSiswa ?? 120 }}</div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-number" data-target="{{ $totalGuru ?? 35 }}">{{ $totalGuru ?? 35 }}</div>
                    <div class="stat-label">Guru &amp; Tendik</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-school"></i></div>
                    <div class="stat-number" data-target="{{ $totalKelas ?? 18 }}">{{ $totalKelas ?? 18 }}</div>
                    <div class="stat-label">Rombongan Belajar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-futbol"></i></div>
                    <div class="stat-number" data-target="{{ $totalEkskul ?? 10 }}">{{ $totalEkskul ?? 10 }}</div>
                    <div class="stat-label">Ekstrakurikuler</div>
                </div>
            </div>

            <div class="total-prestasi-banner" id="total-prestasi-banner" onclick="triggerPrestasiConfetti(event)">
                <div class="piala-3d-floating-wrapper left">
                    <div class="piala-3d-ambient-glow"></div>
                    <div class="piala-3d-interactive piala-float-anim-1">
                        <img src="{{ asset('frontend/landing/piala-kanan.png') }}" alt="Piala 3D" class="piala-3d-asset-img" onerror="this.style.display='none';" />
                    </div>
                    <div class="piala-3d-floor-shadow"></div>
                </div>

                <div class="total-content">
                    <div class="title">Total Perolehan Prestasi Siswa</div>
                    <div class="number" id="total-prestasi-counter" data-target="{{ $sp->total_prestasi ?? 237 }}">{{ $sp->total_prestasi ?? 237 }}</div>
                    <div class="sub">
                        Prestasi Akademik, Tahfidz, Olahraga &amp; Seni
                        <span style="display: inline-block; background: rgba(255, 225, 93, 0.25); color: #ffe15d; padding: 4px 14px; border-radius: 9999px; font-size: 11.5px; font-weight: 800; margin-left: 8px;">
                            🎉 Klik untuk Rayakan
                        </span>
                    </div>
                </div>

                <div class="piala-3d-floating-wrapper right">
                    <div class="piala-3d-ambient-glow"></div>
                    <div class="piala-3d-interactive piala-float-anim-2">
                        <img src="{{ asset('frontend/landing/piala-kanan.png') }}" alt="Piala 3D" class="piala-3d-asset-img" onerror="this.style.display='none';" />
                    </div>
                    <div class="piala-3d-floor-shadow"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. PROGRAM UNGGULAN MIN 1 CIAMIS --}}
    <section class="programs-section" id="program">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Pendidikan Berkualitas</span>
                <h2 class="section-title">Program Unggulan MIN 1 Ciamis</h2>
                <p class="section-desc">Mengembangkan potensi intelektual, spiritual, dan emosional santri secara berimbang</p>
                <div class="section-divider"></div>
            </div>

            <div class="programs-grid">
                @forelse ($listPrograms as $prog)
                    <div class="program-card-modern" style="cursor: pointer;" onclick="window.location.href='{{ route('program-unggulan.detail', $prog->slug) }}'">
                        <div class="program-card-banner">
                            <img src="{{ $prog->gambar_url }}" alt="{{ $prog->judul }}" loading="lazy" />
                            <span class="program-card-badge">
                                <i class="{{ $prog->icon }}"></i> {{ $prog->kategori_badge }}
                            </span>
                        </div>
                        <div class="program-card-body d-flex flex-column justify-content-between">
                            <div>
                                <h3 class="program-card-title">{{ $prog->judul }}</h3>
                                @if ($prog->ringkasan_polos !== '')
                                    <p class="program-card-desc">{{ \Illuminate\Support\Str::limit($prog->ringkasan_polos, 155) }}</p>
                                @endif

                                @php $poinHome = $prog->poin_list; @endphp
                                @if (! empty($poinHome))
                                    <ul class="program-card-features">
                                        @foreach (array_slice($poinHome, 0, 3) as $poin)
                                            <li><i class="fas fa-check-circle"></i> {{ $poin }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="pt-3 border-top mt-3 d-flex align-items-center justify-content-between" style="border-top-color: #f1f5f9 !important;">
                                <span style="font-size: 0.82rem; font-weight: 700; color: #0D6A56; display: inline-flex; align-items: center; gap: 6px;">
                                    Pelajari Program <i class="fas fa-arrow-right"></i>
                                </span>
                                <span style="font-size: 0.74rem; color: #94a3b8; font-weight: 600;">
                                    <i class="far fa-eye"></i> {{ number_format($prog->views, 0, ',', '.') }}x dilihat
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 w-100 col-12 text-muted">
                        Belum ada program unggulan aktif.
                    </div>
                @endforelse
            </div>

            <div style="text-align: center; margin-top: 42px;">
                <a href="{{ route('program-unggulan.publik') }}" class="guru-view-all-btn" style="display: inline-flex; text-decoration: none; padding: 12px 28px; font-size: 0.92rem;">
                    <span>Lihat Seluruh Program Unggulan</span>
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- 8. DUNIA ILMU MIN 1 CIAMIS --}}
    <section class="section guru-tendik-section dunia-ilmu-section" id="dunia-ilmu">
        <div id="layanan" style="position: absolute; top: -80px;"></div>
        <div class="container">
            <div class="guru-tendik-card">

                <div class="guru-header-row" style="margin-bottom: 18px !important;">
                    <div class="guru-header-left">
                        <div class="guru-badge-tag">
                            <i class="fas fa-compass"></i> DUNIA ILMU MADRASAH ({{ $duniaIlmus->count() }} MEDIA)
                        </div>
                        <h2 class="guru-main-title">Dunia Ilmu MIN 1 Ciamis</h2>
                        <p class="guru-sub-desc">
                            Pilih <b>Kelas</b> dan <b>Mata Pelajaran</b> untuk mengakses aplikasi pembelajaran interaktif dan modul digital santri.
                        </p>
                    </div>

                    <div class="guru-header-right">
                        <a href="{{ route('dunia-ilmu.publik') }}" class="guru-view-all-btn">
                            <span>Katalog Lengkap</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <div class="guru-nav-controls">
                            <button type="button" class="guru-nav-btn" id="duniaIlmuPrev" aria-label="Geser ke kiri">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="guru-nav-btn" id="duniaIlmuNext" aria-label="Geser ke kanan">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="dunia-filter-bar">
                    <div class="filter-title" style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 800; color: #0d6a56;">
                        <i class="fas fa-filter"></i> Filter Cepat:
                    </div>

                    <select id="filterHomeKelas" onchange="saringKartuDuniaHome()"
                        style="padding: 8px 14px; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; font-weight: 700; color: #0f172a; background: #f8fafc; cursor: pointer;">
                        <option value="semua">Semua Kelas</option>
                        @foreach ($opsiKelasDunia as $kls)
                            @if ($kls !== 'Semua Kelas')
                                <option value="{{ $kls }}">{{ $kls }}</option>
                            @endif
                        @endforeach
                    </select>

                    <select id="filterHomeMapel" onchange="saringKartuDuniaHome()"
                        style="padding: 8px 14px; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; font-weight: 700; color: #0f172a; background: #f8fafc; cursor: pointer;">
                        <option value="semua">Semua Mata Pelajaran</option>
                        @foreach ($opsiMapelDunia as $mpl)
                            <option value="{{ $mpl }}">{{ $mpl }}</option>
                        @endforeach
                    </select>

                    <button type="button" class="btn-reset-filter" onclick="resetFilterDuniaHome()"
                        style="background: none; border: none; color: #64748b; font-size: 0.8rem; font-weight: 700; cursor: pointer; margin-left: auto;">
                        <i class="fas fa-redo-alt"></i> Tampilkan Semua
                    </button>
                </div>

                <div class="guru-slider-viewport" id="duniaIlmuViewport">
                    <div class="guru-slider-track" id="duniaIlmuTrack">
                        @foreach ($duniaIlmus as $media)
                            @php
                                $klsMedia = $media->kelas ?: 'Semua Kelas';
                                $mplMedia = $media->mapel ?: 'Umum / Lintas Mapel';
                            @endphp
                            <a href="{{ $media->url_tujuan }}"
                               target="{{ $media->tipe_sumber === 'link' ? '_blank' : '_self' }}"
                               @if ($media->tipe_sumber === 'link') rel="noopener noreferrer" @endif
                               class="guru-card dunia-media-card item-dunia-home"
                               data-kelas="{{ $klsMedia }}" data-mapel="{{ $mplMedia }}"
                               title="Buka {{ $media->judul }}">
                                <div class="guru-card-banner {{ $media->warna_gradasi }}">
                                    <span style="position: absolute; top: 10px; left: 12px; background: rgba(255, 255, 255, 0.92); color: #0f172a; font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                        {{ $klsMedia }}
                                    </span>

                                    @if ($media->tipe_sumber === 'kode_interaktif')
                                        <span style="position: absolute; top: 10px; right: 12px; background: rgba(15, 23, 42, 0.82); color: #FFE15D; font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; border: 1px solid rgba(255, 225, 93, 0.4);">
                                            ⚡ APP INTERAKTIF
                                        </span>
                                    @endif

                                    <div class="guru-photo-box" style="padding: 14px;">
                                        <img src="{{ $media->gambar_url }}" alt="{{ $media->judul }}" class="guru-photo-img" style="object-fit: contain;" loading="lazy">
                                    </div>
                                </div>
                                <div class="guru-card-body">
                                    <h4 class="guru-name">{{ $media->judul }}</h4>
                                    <p class="guru-role">{{ $mplMedia }}</p>
                                    <div class="dunia-link-action">
                                        <span>{{ $media->tipe_sumber === 'kode_interaktif' ? 'Mainkan Aplikasi' : 'Buka Media' }}</span>
                                        <i class="fas {{ $media->tipe_sumber === 'kode_interaktif' ? 'fa-play-circle' : 'fa-external-link-alt' }}"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 9. KABAR MADRASAH --}}
    <section class="section section-bg-alt" id="berita">
        <div class="container">
            <div class="section-header" style="margin-bottom: 35px;">
                <span class="section-subtitle">Warta &amp; Informasi Terkini</span>
                <h2 class="section-title">Kabar Madrasah</h2>
                <p class="section-desc">Berita, pengumuman, dan liputan kegiatan terbaru seputar MIN 1 Ciamis</p>
                <div class="section-divider"></div>
            </div>

            <div class="news-grid" id="news-cards-grid">
                @foreach ($semuaBeritaGrid as $item)
                    <div class="news-card" data-category="{{ $item->kategori }}"
                         onclick="window.location.href='{{ route('berita.show', $item->slug) }}'">
                        <div class="news-thumb-wrap">
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="news-thumb" loading="lazy" />
                            <span class="news-category-badge">{{ $item->kategori }}</span>
                        </div>
                        <div class="news-body">
                            <div class="news-date" style="font-size: 0.82rem; color: #64748b; margin-bottom: 8px;">
                                <i class="far fa-calendar-alt"></i> {{ $item->created_at->translatedFormat('d F Y') }}
                                &bull; {{ number_format($item->views, 0, ',', '.') }}x dibaca
                            </div>
                            <h3 class="news-title">{{ $item->judul }}</h3>
                            <p class="news-excerpt" style="font-size: 0.9rem; color: #475569; margin-bottom: 16px;">
                                {{ $teksRingkas($item->ringkasan ?: $item->isi_konten, 120) }}
                            </p>
                            <a href="{{ route('berita.show', $item->slug) }}" class="news-read-more" style="margin-top: auto;">
                                <span>Baca Selengkapnya</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 42px;">
                <a href="{{ route('berita.publik') }}" class="guru-view-all-btn" style="display: inline-flex; text-decoration: none; padding: 12px 30px; font-size: 0.95rem; font-weight: 700;">
                    <span>Lihat Seluruh Kabar Madrasah</span>
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- 10. MUTIARA HIKMAH ISLAMI --}}
    <section class="mutiara-section" id="mutiara-hikmah">
        <div class="container">
            <div class="mutiara-card">
                <div class="mutiara-quote-wrap">
                    <div class="mutiara-badge">
                        <i class="fas fa-moon"></i>
                        <span>Mutiara Hikmah &amp; Motivasi Hari Ini</span>
                    </div>
                    <div class="mutiara-quote-text" id="mutiara-text">
                        "Barangsiapa menempuh suatu jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan menuju surga."
                    </div>
                    <div class="mutiara-author" id="mutiara-author">— HR. Muslim</div>
                </div>
                <div class="mutiara-actions">
                    <button type="button" class="mutiara-btn-shuffle" id="mutiara-shuffle-btn" onclick="shuffleMutiaraHikmah()">
                        <i class="fas fa-random"></i><span>Ganti Kutipan</span>
                    </button>
                    <button type="button" class="mutiara-btn-copy" id="mutiara-copy-btn" onclick="copyMutiaraQuote()">
                        <i class="far fa-copy"></i><span>Salin</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        function switchSekilasTabCustom(tabKey, judulKiri, judulKanan) {
            document.querySelectorAll('.sekilas-tab-btn').forEach(function (btn) {
                btn.classList.toggle('active', btn.getAttribute('data-tab') === tabKey);
            });
            document.querySelectorAll('.sekilas-tab-pane').forEach(function (pane) {
                pane.style.display = pane.id === 'sekilas-pane-' + tabKey ? 'block' : 'none';
            });
            var elKiri = document.getElementById('teksKiriJudulSekilas');
            var elKanan = document.getElementById('teksKananJudulSekilas');
            if (elKiri) elKiri.innerText = judulKiri;
            if (elKanan) elKanan.innerText = judulKanan;
        }

        function saringKartuDuniaHome() {
            var pilihKelas = document.getElementById('filterHomeKelas').value;
            var pilihMapel = document.getElementById('filterHomeMapel').value;

            document.querySelectorAll('.item-dunia-home').forEach(function (card) {
                var kls = card.getAttribute('data-kelas');
                var mpl = card.getAttribute('data-mapel');

                var cocokKelas = pilihKelas === 'semua' || kls === pilihKelas || kls === 'Semua Kelas';
                var cocokMapel = pilihMapel === 'semua' || mpl === pilihMapel;

                card.style.display = (cocokKelas && cocokMapel) ? 'flex' : 'none';
            });
        }

        function resetFilterDuniaHome() {
            document.getElementById('filterHomeKelas').value = 'semua';
            document.getElementById('filterHomeMapel').value = 'semua';
            saringKartuDuniaHome();
        }
    </script>

@endsection