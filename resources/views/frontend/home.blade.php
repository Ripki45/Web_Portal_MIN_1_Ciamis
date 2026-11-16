@extends('layouts.frontend')

@section('title', 'Beranda - MIN 1 CIAMIS | Madrasah Ibtidaiyah Negeri 1 Ciamis')
<style>
    /* =========================================================================
       1. BANNER IKLAN PPDB LANDSCAPE (TETAP PROPORSIONAL DI LAPTOP & HP)
       ========================================================================= */
    .ppdb-landscape-banner-wrap {
        width: 100%;
        margin-bottom: 18px;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
        border: 2px solid #ffffff;
        background: #1e3a8a;
        line-height: 0;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ppdb-landscape-banner-wrap:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
    }

    .ppdb-landscape-banner-img {
        width: 100% !important;
        height: auto !important;
        aspect-ratio: 16 / 5.6;
        object-fit: fill !important;
        display: block !important;
    }

    /* =========================================================================
       2. LAYANAN CEPAT VERSI RAMPING & ELEGAN (TIDAK BONGSOR DI HP MAUPUN LAPTOP)
       ========================================================================= */
    #layanan-cepat.layanan-cepat-card {
        padding: 24px 28px !important;
        border-radius: 22px !important;
    }

    #layanan-cepat .layanan-cepat-header {
        margin-bottom: 16px !important;
    }

    #layanan-cepat .layanan-cepat-title {
        font-size: 1.55rem !important;
        margin: 0 0 4px 0 !important;
    }

    #layanan-cepat .layanan-cepat-subtitle {
        font-size: 0.85rem !important;
        opacity: 0.9;
    }

    .layanan-cepat-grid-custom {
        position: relative !important;
        z-index: 2 !important;
        display: grid !important;
        grid-template-columns: repeat(6, 1fr) !important;
        gap: 12px !important;
    }

    #layanan-cepat .layanan-cepat-item {
        padding: 12px 8px !important;
        min-height: 82px !important;
        border-radius: 14px !important;
        gap: 6px !important;
    }

    #layanan-cepat .lc-icon {
        width: 30px !important;
        height: 30px !important;
        font-size: 1.1rem !important;
    }

    #layanan-cepat .lc-label {
        font-size: 0.76rem !important;
        line-height: 1.2 !important;
    }

    /* Item ke-7 dst disembunyikan sampai klik Lihat Selengkapnya */
    .layanan-cepat-item.lc-extra-item {
        display: none !important;
    }

    .layanan-cepat-grid-custom.expanded .layanan-cepat-item.lc-extra-item {
        display: flex !important;
        animation: lcFadeIn 0.25s ease-out;
    }

    @keyframes lcFadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-layanan-more-wrap {
        position: relative;
        z-index: 2;
        text-align: center;
        margin-top: 14px;
    }

    .btn-layanan-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 225, 93, 0.14);
        border: 1px solid rgba(255, 225, 93, 0.5);
        color: #FFE15D;
        padding: 7px 18px;
        border-radius: 9999px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.76rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-layanan-more:hover {
        background: #FFE15D;
        color: #09332C;
    }

    /* Tampilan Tablet */
    @media (max-width: 991px) {
        .layanan-cepat-grid-custom {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
        }
    }

    /* Tampilan HP: Ramping 3 Kolom x 2 Baris (6 Akses Muat Satu Layar dengan Manis) */
    @media (max-width: 576px) {
        .ppdb-landscape-banner-wrap {
            border-radius: 12px;
            margin-bottom: 14px;
        }

        #layanan-cepat.layanan-cepat-card {
            padding: 18px 14px !important;
            border-radius: 18px !important;
        }

        #layanan-cepat .layanan-cepat-header {
            margin-bottom: 12px !important;
            text-align: left;
        }

        #layanan-cepat .layanan-cepat-title {
            font-size: 1.2rem !important;
            margin-bottom: 2px !important;
        }

        #layanan-cepat .layanan-cepat-subtitle {
            font-size: 0.74rem !important;
            line-height: 1.35 !important;
        }

        .layanan-cepat-grid-custom {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 8px !important;
        }

        #layanan-cepat .layanan-cepat-item {
            padding: 10px 6px !important;
            min-height: 68px !important;
            border-radius: 12px !important;
            gap: 5px !important;
        }

        #layanan-cepat .lc-icon {
            width: 24px !important;
            height: 24px !important;
            font-size: 0.95rem !important;
        }

        #layanan-cepat .lc-label {
            font-size: 0.68rem !important;
            font-weight: 600 !important;
            line-height: 1.15 !important;
        }

        .btn-layanan-more-wrap {
            margin-top: 12px;
        }

        .btn-layanan-more {
            padding: 6px 14px;
            font-size: 0.72rem;
        }
    }
</style>
@section('content')

    @php
        \App\Models\BannerCarousel::pastikanTabelSiap();
        \App\Models\Berita::pastikanTabelSiap();
        \App\Models\KategoriBerita::pastikanTabelSiap();

        $fotoBanners = \App\Models\BannerCarousel::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->take(4)
            ->get()
            ->values();

        $beritaSlider = \App\Models\Berita::where('status', 'Tayang')->orderBy('id', 'desc')->take(4)->get()->values();

        $semuaBeritaGrid = \App\Models\Berita::where('status', 'Tayang')
            ->orderBy('is_featured', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $beritaUtama = $semuaBeritaGrid->first();
        $beritaSamping = $semuaBeritaGrid->slice(1, 2);
        $warnaKategori = \App\Models\KategoriBerita::pluck('warna_badge', 'nama_kategori')->toArray();

        $gtks =
            $gtks ??
            \App\Models\Gtk::with(['jabatan', 'posisi'])
                ->orderBy('id', 'asc')
                ->get();
    @endphp

    <!-- ========================================================================= -->
    <!-- 1. HERO SLIDER SECTION (MOVING GRID & 3 SLIDES)                           -->
    <!-- ========================================================================= -->
    <section class="hero-slider-section" id="beranda">
        <div class="hero-animated-grid-wrap">
            <div class="hero-animated-grid-bg"></div>
        </div>

        <div class="hero-slider-track">
            <!-- Slide 1: Selamat Datang di MIN 1 Ciamis -->
            <div class="hero-slide active" id="hero-slide-1">
                <div class="hero-slide-flex-container">
                    <div class="hero-slide-left-text">
                        <span class="hero-script-heading">Selamat datang di</span>
                        <h1 class="hero-bold-title">MIN 1 CIAMIS</h1>
                        <div class="hero-domain-line">w w w . m i n 1 c i a m i s . s c h . i d</div>

                        <div class="hero-slogan-strip">
                            <div class="hero-slogan-lines">
                                <span>Bertaqwa ~ Berakhlaq Mulia ~ Unggul Prestasi</span>
                                <span>Cakap Berteknologi ~ Berwawasan Global ~ Peduli Lingkungan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Program Tahfidz & Sains -->
            <div class="hero-slide" id="hero-slide-2">
                <div class="hero-slide-flex-container">
                    <div class="hero-slide-left-text">
                        <span class="hero-script-heading">Program Unggulan</span>
                        <h2 class="hero-bold-title">TAHFIDZ &amp; SAINS</h2>
                        <div class="hero-domain-line">t a h f i d z . m i n 1 c i a m i s . s c h . i d</div>
                        <div class="hero-slogan-strip">
                            <div class="hero-slogan-badge-wrap">
                                <div class="hero-slogan-badge">
                                    <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Emblem" />
                                    <span class="hero-slogan-badge-text">TAHFIDZ</span>
                                </div>
                            </div>
                            <div class="hero-slogan-lines">
                                <span>Target Hafalan Juz 30 ~ Bimbingan Tartil Terarah</span>
                                <span>Inkubasi Juara KSM Sains ~ Karakter Islami Qur'ani</span>
                            </div>
                        </div>
                    </div>

                    <div class="hero-collage-wrap">
                        <img src="{{ isset($fotoBanners[1]) ? $fotoBanners[1]->gambar_url : asset('frontend/assets/imgs/page/img-5.png') }}"
                            alt="Tahfidz & Sains" class="hero-collage-main-img"
                            style="border-radius: 28px; border: 4px solid #fff; box-shadow: 0 16px 36px rgba(15, 23, 42, 0.14);" />
                    </div>
                </div>
            </div>

            <!-- Slide 3: PPDB Online 2026/2027 -->
            <div class="hero-slide" id="hero-slide-3">
                <div class="hero-slide-flex-container">
                    <div class="hero-slide-left-text">
                        <span class="hero-script-heading">Penerimaan Siswa Baru</span>
                        <h2 class="hero-bold-title">PPDB 2026/2027</h2>
                        <div class="hero-domain-line">p p d b . m i n 1 c i a m i s . s c h . i d</div>
                        <div class="hero-slogan-strip">
                            <div class="hero-slogan-badge-wrap">
                                <div class="hero-slogan-badge">
                                    <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Emblem" />
                                    <span class="hero-slogan-badge-text">PPDB</span>
                                </div>
                            </div>
                            <div class="hero-slogan-lines">
                                <span>Madrasah Ramah Anak ~ Bebas Perundungan (Anti Bullying)</span>
                                <span>Lingkungan Belajar Asri &amp; Sejuk di Panjalu Ciamis</span>
                            </div>
                        </div>
                    </div>

                    <div class="hero-collage-wrap">
                        <img src="{{ isset($fotoBanners[2]) ? $fotoBanners[2]->gambar_url : asset('frontend/assets/imgs/page/img-6.png') }}"
                            alt="PPDB MIN 1 Ciamis" class="hero-collage-main-img"
                            style="border-radius: 28px; border: 4px solid #fff; box-shadow: 0 16px 36px rgba(15, 23, 42, 0.14);" />
                    </div>
                </div>
            </div>
        </div>

        <button class="slider-arrow prev" aria-label="Slide Sebelumnya"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-arrow next" aria-label="Slide Berikutnya"><i class="fas fa-chevron-right"></i></button>

        <div class="slider-dots-container">
            <span class="slider-dot active"></span>
            <span class="slider-dot"></span>
            <span class="slider-dot"></span>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. SAMBUTAN KEPALA MADRASAH & SEKILAS MIN 1 CIAMIS (5 TAB TERPADU)        -->
    <!-- ========================================================================= -->
    @php
        $sp = \App\Models\SambutanProfil::ambilData();
    @endphp
    <section class="section sekilas-section" id="profil">
        <div class="sekilas-gradasi-garis"></div>
        <div class="container" style="position: relative; z-index: 2">
            <div class="sekilas-grid">

                <!-- SISI KIRI: FOTO KEPALA MADRASAH + FLOATING BADGE IDENTITAS -->
                <div class="sekilas-card-container">
                    <div class="sekilas-card-decor-gradient"></div>
                    <div class="sekilas-card-frame">
                        <div class="sekilas-card-img-wrapper">
                            <img src="{{ $sp->foto_kepsek_url }}" alt="{{ $sp->nama_kepsek }}" class="sekilas-card-img"
                                style="object-position: top center;"
                                onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';" />

                            <!-- Kartu Identitas Kepala Madrasah di Bawah Foto -->
                            <div
                                style="position: absolute; bottom: 18px; left: 18px; right: 18px; background: rgba(15, 23, 42, 0.86); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1.5px solid rgba(255, 225, 93, 0.55); border-radius: 20px; padding: 14px 18px; color: #ffffff; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28);">
                                <div
                                    style="display: inline-block; background: #1B9C85; color: #FFE15D; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 4px;">
                                    {{ $sp->jabatan_kepsek }}
                                </div>
                                <div
                                    style="font-family: 'Outfit', sans-serif; font-size: 1.12rem; font-weight: 800; color: #ffffff; line-height: 1.25;">
                                    {{ $sp->nama_kepsek }}
                                </div>
                                @if (!empty($sp->nip_kepsek))
                                    <div
                                        style="font-size: 0.76rem; color: #cbd5e1; margin-top: 2px; font-family: monospace;">
                                        NIP. {{ $sp->nip_kepsek }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SISI KANAN: 5 PILL TABS (SAMBUTAN, PROFIL, VISI, MISI, MOTTO) -->
                <div class="sekilas-content-wrap">
                    <div class="sekilas-eyebrow">
                        <span class="sekilas-eyebrow-line"></span>
                        <span class="sekilas-eyebrow-text">SAMBUTAN &amp; INFORMASI RESMI MADRASAH</span>
                    </div>

                    <h2 class="sekilas-title" id="judulUtamaSekilas">
                        <span class="title-italic" id="teksKiriJudulSekilas">SAMBUTAN</span>
                        <span class="title-highlight" id="teksKananJudulSekilas">KEPALA MADRASAH</span>
                    </h2>

                    <!-- Navigasi 5 Tab -->
                    <div class="sekilas-tabs-nav">
                        <button type="button" class="sekilas-tab-btn active" data-tab="sambutan"
                            onclick="switchSekilasTabCustom('sambutan', 'SAMBUTAN', 'KEPALA MADRASAH')">
                            SAMBUTAN
                        </button>
                        <button type="button" class="sekilas-tab-btn" data-tab="profil"
                            onclick="switchSekilasTabCustom('profil', 'PROFIL', 'MIN 1 CIAMIS')">
                            PROFIL
                        </button>
                        <button type="button" class="sekilas-tab-btn" data-tab="visi"
                            onclick="switchSekilasTabCustom('visi', 'VISI', 'MIN 1 CIAMIS')">
                            VISI
                        </button>
                        <button type="button" class="sekilas-tab-btn" data-tab="misi"
                            onclick="switchSekilasTabCustom('misi', 'MISI', 'MIN 1 CIAMIS')">
                            MISI
                        </button>
                        <button type="button" class="sekilas-tab-btn" data-tab="motto"
                            onclick="switchSekilasTabCustom('motto', 'MOTTO', 'MIN 1 CIAMIS')">
                            MOTTO
                        </button>
                    </div>

                    <!-- TAB 1: SAMBUTAN KEPALA MADRASAH (AKTIF DEFAULT) -->
                    <div class="sekilas-tab-pane" id="sekilas-pane-sambutan" style="display: block">
                        <div class="sekilas-content-tag">KATA SAMBUTAN KEPALA MADRASAH</div>
                        <h3 class="sekilas-quote">"{{ $sp->sambutan_kutipan }}"</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->sambutan_isi }}</div>

                        <div
                            style="display: flex; align-items: center; gap: 12px; margin-top: 18px; padding-top: 14px; border-top: 1px dashed #cbd5e1;">
                            <div
                                style="width: 42px; height: 42px; border-radius: 50%; background: #EDF6EE; color: #1B9C85; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">{{ $sp->nama_kepsek }}
                                </div>
                                <div style="font-size: 0.8rem; color: #1B9C85; font-weight: 700;">
                                    {{ $sp->jabatan_kepsek }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: PROFIL + 4 POIN KEUNGGULAN DARI DATABASE -->
                    <div class="sekilas-tab-pane" id="sekilas-pane-profil" style="display: none">
                        <div class="sekilas-content-tag">PROFIL MADRASAH</div>
                        <h3 class="sekilas-quote">"{{ $sp->profil_kutipan }}"</h3>
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

                    <!-- TAB 3: VISI -->
                    <div class="sekilas-tab-pane" id="sekilas-pane-visi" style="display: none">
                        <div class="sekilas-content-tag">VISI MADRASAH</div>
                        <h3 class="sekilas-quote">"{{ $sp->visi_kutipan }}"</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->visi_isi }}</div>
                    </div>

                    <!-- TAB 4: MISI + 4 POIN MISI DARI DATABASE -->
                    <div class="sekilas-tab-pane" id="sekilas-pane-misi" style="display: none">
                        <div class="sekilas-content-tag">MISI MADRASAH</div>
                        <h3 class="sekilas-quote">"{{ $sp->misi_kutipan }}"</h3>
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

                    <!-- TAB 5: MOTTO & PIALA 3D -->
                    <div class="sekilas-tab-pane" id="sekilas-pane-motto" style="display: none">
                        <div class="sekilas-content-tag">MOTTO MADRASAH</div>
                        <h3 class="sekilas-quote">"{{ $sp->motto_kutipan }}"</h3>
                        <div class="sekilas-text" style="white-space: pre-line;">{{ $sp->motto_isi }}</div>

                        <div class="motto-trophy-showcase" onclick="triggerPrestasiConfetti(event)"
                            style="cursor: pointer; display: flex; align-items: center; gap: 18px; background: linear-gradient(135deg, #09332C 0%, #115E59 100%); padding: 18px 22px; border-radius: 20px; margin-top: 18px; color: #fff;"
                            title="Klik untuk merayakan!">
                            <div style="font-size: 2.4rem; color: #ffe15d; flex-shrink: 0;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <div
                                    style="font-size: 1.2rem; font-weight: 800; color: #ffe15d; font-family: 'Outfit', sans-serif;">
                                    {{ $sp->total_prestasi }}+ Prestasi Juara
                                </div>
                                <p
                                    style="margin: 4px 0 0; font-size: 0.86rem; color: rgba(255, 255, 255, 0.9); line-height: 1.45;">
                                    Perolehan piala &amp; medali kejuaraan tingkat Kabupaten Ciamis, Priangan Timur,
                                    Provinsi, hingga Nasional.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>



    <!-- ========================================================================= -->
    <!-- 3. BANNER IKLAN PPDB (LANDSCAPE TETAP) & LAYANAN CEPAT (6 AKSES + MORE)   -->
    <!-- ========================================================================= -->
    <section class="hero-bottom-features-section" id="ppdb" style="margin-top: 10px;">
        <div class="container">

            @php
                // Dummy Banner Iklan PPDB Landscape (Tampil identik di Laptop & HP)
                $dummyBannerPpdb =
                    'data:image/svg+xml;utf8,' .
                    rawurlencode('
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 580" width="100%" height="100%">
                        <defs>
                            <linearGradient id="ppdbGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#FFFFFF"/>
                                <stop offset="55%" stop-color="#F8FAFC"/>
                                <stop offset="100%" stop-color="#DBEAFE"/>
                            </linearGradient>
                        </defs>
                        <rect width="1600" height="580" fill="url(#ppdbGrad)"/>
                        <circle cx="1380" cy="290" r="340" fill="#1E3A8A" opacity="0.08"/>
                        <path d="M1280,580 C1340,420 1480,380 1600,380 L1600,580 Z" fill="#F59E0B"/>
                        <text x="85" y="135" font-family="Arial, sans-serif" font-size="68" font-weight="900" fill="#1E3A8A">PENERIMAAN</text>
                        <text x="85" y="210" font-family="Arial, sans-serif" font-size="68" font-weight="900" fill="#F59E0B">PESERTA DIDIK BARU</text>
                        <rect x="85" y="240" width="370" height="54" rx="12" fill="#1E3A8A"/>
                        <text x="270" y="276" font-family="Arial, sans-serif" font-size="25" font-weight="bold" fill="#FFFFFF" text-anchor="middle">TAHUN AJARAN 2026/2027</text>
                        <text x="85" y="345" font-family="Arial, sans-serif" font-size="28" font-style="italic" fill="#334155">"Membentuk Generasi Qur\'ani, Berakhlak Mulia &amp; Unggul Prestasi"</text>
                        <rect x="85" y="395" width="330" height="68" rx="14" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>
                        <text x="250" y="438" font-family="Arial, sans-serif" font-size="23" font-weight="bold" fill="#1E3A8A" text-anchor="middle">✓ KURIKULUM MERDEKA</text>
                        <rect x="435" y="395" width="330" height="68" rx="14" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>
                        <text x="600" y="438" font-family="Arial, sans-serif" font-size="23" font-weight="bold" fill="#1E3A8A" text-anchor="middle">✓ PRESTASI UNGGUL</text>
                        <rect x="785" y="395" width="330" height="68" rx="14" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="2"/>
                        <text x="950" y="438" font-family="Arial, sans-serif" font-size="23" font-weight="bold" fill="#1E3A8A" text-anchor="middle">✓ FASILITAS LENGKAP</text>
                        <rect x="1180" y="70" width="330" height="56" rx="28" fill="#1E3A8A"/>
                        <text x="1345" y="107" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#FFE15D" text-anchor="middle">KUOTA TERBATAS!</text>
                        <rect x="1160" y="160" width="370" height="270" rx="24" fill="#1B9C85"/>
                        <text x="1345" y="280" font-family="Arial, sans-serif" font-size="42" font-weight="900" fill="#FFFFFF" text-anchor="middle">MIN 1 CIAMIS</text>
                        <text x="1345" y="335" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#FFE15D" text-anchor="middle">KLIK DAFTAR SEKARANG →</text>
                    </svg>
                ');

                // Jika Admin mengunggah foto Slide #4 di menu Carousel Banner, otomatis pakai foto tersebut sebagai Banner Iklan PPDB
                $bannerIklanPpdbUrl =
                    isset($fotoBanners[3]) && !str_starts_with($fotoBanners[3]->gambar, 'frontend/')
                        ? $fotoBanners[3]->gambar_url
                        : $dummyBannerPpdb;
            @endphp

            <!-- 1. BANNER IKLAN PPDB (FULL LANDSCAPE GAMBAR — SAMA DI LAPTOP & HP) -->
            <div class="ppdb-landscape-banner-wrap" onclick="openPPDBModal()"
                title="Klik untuk informasi pendaftaran PPDB MIN 1 Ciamis">
                <img src="{{ $bannerIklanPpdbUrl }}" alt="Banner Iklan PPDB MIN 1 Ciamis"
                    class="ppdb-landscape-banner-img" />
            </div>

            <!-- 2. LAYANAN CEPAT (TAMPIL 6 AKSES UTAMA + TOMBOL LIHAT SELENGKAPNYA) -->
            <div class="layanan-cepat-card" id="layanan-cepat">
                <div class="layanan-cepat-bg-wave" aria-hidden="true"></div>
                <div class="layanan-cepat-header">
                    <h2 class="layanan-cepat-title">Layanan Cepat</h2>
                    <p class="layanan-cepat-subtitle">Akses halaman penting website madrasah dengan lebih cepat.</p>
                </div>

                <div class="layanan-cepat-grid-custom" id="gridLayananCepat">
                    <!-- 6 AKSES UTAMA (TAMPIL LANGSUNG) -->
                    <a href="{{ route('presensi.scanner') }}" class="layanan-cepat-item">
                        <div class="lc-icon"><i class="fas fa-qrcode"></i></div>
                        <span class="lc-label">Scanner QR Presensi</span>
                    </a>
                    <a href="https://emis.kemenag.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item">
                        <div class="lc-icon"><i class="fas fa-server"></i></div>
                        <span class="lc-label">EMIS Madrasah</span>
                    </a>
                    <a href="https://rdm.kemenag.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item">
                        <div class="lc-icon"><i class="fas fa-file-signature"></i></div>
                        <span class="lc-label">Rapor Digital (RDM)</span>
                    </a>
                    <a href="https://dapo.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item">
                        <div class="lc-icon"><i class="fas fa-id-card"></i></div>
                        <span class="lc-label">Dapodik</span>
                    </a>
                    <a href="https://vervalpd.data.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item">
                        <div class="lc-icon"><i class="fas fa-user-check"></i></div>
                        <span class="lc-label">Verval Peserta Didik</span>
                    </a>
                    <a href="https://bos.kemenag.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item">
                        <div class="lc-icon"><i class="fas fa-coins"></i></div>
                        <span class="lc-label">Portal BOS</span>
                    </a>

                    <!-- AKSES TAMBAHAN (MUNCUL SAAT KLIK LIHAT SELENGKAPNYA) -->
                    <a href="https://snpmb.bppp.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item lc-extra-item">
                        <div class="lc-icon"><i class="fas fa-graduation-cap"></i></div>
                        <span class="lc-label">Portal SNPMB</span>
                    </a>
                    <a href="https://pddikti.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item lc-extra-item">
                        <div class="lc-icon"><i class="fas fa-university"></i></div>
                        <span class="lc-label">PDDikti</span>
                    </a>
                    <a href="https://referensi.data.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item lc-extra-item">
                        <div class="lc-icon"><i class="fas fa-database"></i></div>
                        <span class="lc-label">Data Pokok Pendidikan</span>
                    </a>
                    <a href="https://simpatika.kemenag.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item lc-extra-item">
                        <div class="lc-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <span class="lc-label">SIMPATIKA Kemenag</span>
                    </a>
                    <a href="https://pusaka.kemenag.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item lc-extra-item">
                        <div class="lc-icon"><i class="fas fa-mosque"></i></div>
                        <span class="lc-label">PUSAKA Kemenag</span>
                    </a>
                    <a href="https://buku.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer"
                        class="layanan-cepat-item lc-extra-item">
                        <div class="lc-icon"><i class="fas fa-book-reader"></i></div>
                        <span class="lc-label">E-Perpustakaan SIBI</span>
                    </a>
                </div>

                <!-- Tombol Lihat Selengkapnya -->
                <div class="btn-layanan-more-wrap">
                    <button type="button" class="btn-layanan-more" id="btnToggleLayanan"
                        onclick="toggleLayananCepat()">
                        <span id="teksToggleLayanan">Lihat Selengkapnya</span>
                        <i class="fas fa-chevron-down" id="ikonToggleLayanan"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>



    <!-- ========================================================================= -->
    <!-- 4. CAROUSEL SOROTAN 4 BERITA TERBARU + FOTO BANNER (KOMPONEN MAGZIN)      -->
    <!-- ========================================================================= -->
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
                                                    : $fotoBanners->first()->gambar_url ?? $itemBerita->gambar_url;
                                            @endphp
                                            <div class="swiper-slide w-100" data-bg="{{ $bgFoto }}">
                                                <div class="article">
                                                    <div class="card-body">
                                                        <a href="{{ route('berita.show', $itemBerita->slug) }}"
                                                            class="badge bg-2 fs-8 mb-3">
                                                            {{ $itemBerita->kategori }}
                                                        </a>
                                                        <a href="{{ route('berita.show', $itemBerita->slug) }}"
                                                            class="hover-underline">
                                                            <h5 class="card-title mb-0 text-white changeless">
                                                                {{ $itemBerita->judul }}</h5>
                                                        </a>
                                                        <p
                                                            class="card-text text-white mb-0 fs-7 mt-3 changeless text-truncate-2">
                                                            {{ $itemBerita->ringkasan ?: Str::limit(strip_tags($itemBerita->isi_konten), 140) }}
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
                                                        <img src="{{ $thumbFoto }}" alt="{{ $itemBerita->judul }}"
                                                            style="width: 85px; height: 68px; object-fit: cover; border-radius: 10px;">
                                                    </div>
                                                    <div class="card-content">
                                                        <h6 class="text-white mb-0 changeless text-truncate-2">
                                                            {{ $itemBerita->judul }}</h6>
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

    <!-- ========================================================================= -->
    <!-- 5. DIREKTORI GURU & TENAGA PENDIDIK (DINAMIS DARI DATABASE MASTER GTK)    -->
    <!-- ========================================================================= -->
    <section class="section guru-tendik-section" id="guru">
        <div class="container">
            <div class="guru-tendik-card">
                <div class="guru-header-row">
                    <div class="guru-header-left">
                        <div class="guru-badge-tag">
                            <i class="fas fa-chalkboard-teacher"></i> DIREKTORI GURU ({{ $gtks->count() }} PENDIDIK)
                        </div>
                        <h2 class="guru-main-title">Guru dan Tenaga Pendidik</h2>
                        <p class="guru-sub-desc">Kenali pendidik yang mendampingi proses belajar siswa MIN 1 Ciamis setiap
                            hari.</p>
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
                                $fotoGuru = !empty($guru->foto)
                                    ? asset('storage/' . $guru->foto)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($guru->nama_lengkap) .
                                        '&background=0D6A56&color=fff&size=300';
                                $peranGuru =
                                    $guru->posisi->nama_posisi ?? ($guru->jabatan->nama_jabatan ?? 'Tenaga Pendidik');
                            @endphp
                            <div class="guru-card" title="{{ $guru->nama_lengkap }}">
                                <div class="guru-card-banner">
                                    <div class="guru-photo-box">
                                        <img src="{{ $fotoGuru }}" alt="{{ $guru->nama_lengkap }}"
                                            class="guru-photo-img" loading="lazy">
                                    </div>
                                </div>
                                <div class="guru-card-body">
                                    <h4 class="guru-name">{{ $guru->nama_lengkap }}</h4>
                                    <p class="guru-role">{{ $peranGuru }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada data Guru & Tendik.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. PRESTASI SISWA & PIALA 3D MENGAWANG + EFEK CONFETTI                    -->
    <!-- ========================================================================= -->
    <section class="section stats-section" id="prestasi">
        <div class="stats-bg-decor"></div>
        <div class="container" style="position: relative; z-index: 2">
            <div class="section-header">
                <span class="section-subtitle white" style="color: var(--primary-ligh)t">Capaian Gemilang</span>
                <h2 class="section-title white">Prestasi Siswa &amp; Data Madrasah</h2>
                <p class="section-desc" style="color: #cbd5e1">Rekap Capaian Kejuaraan &amp; Statistik Akademik MIN 1
                    Ciamis</p>
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
                        <img src="{{ asset('frontend/landing/piala-kanan.png') }}" alt="Piala 3D"
                            class="piala-3d-asset-img" onerror="this.style.display='none';" />
                    </div>
                    <div class="piala-3d-floor-shadow"></div>
                </div>

                <div class="total-content">
                    <div class="title">Total Perolehan Prestasi Siswa</div>
                    <div class="number" id="total-prestasi-counter" data-target="237">237</div>
                    <div class="sub">
                        Prestasi Akademik, Tahfidz, Olahraga &amp; Seni
                        <span
                            style="display: inline-block; background: rgba(255, 225, 93, 0.25); color: #ffe15d; padding: 4px 14px; border-radius: 9999px; font-size: 11.5px; font-weight: 800; margin-left: 8px;">
                            🎉 Klik untuk Rayakan
                        </span>
                    </div>
                </div>

                <div class="piala-3d-floating-wrapper right">
                    <div class="piala-3d-ambient-glow"></div>
                    <div class="piala-3d-interactive piala-float-anim-2">
                        <img src="{{ asset('frontend/landing/piala-kanan.png') }}" alt="Piala 3D"
                            class="piala-3d-asset-img" onerror="this.style.display='none';" />
                    </div>
                    <div class="piala-3d-floor-shadow"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. PROGRAM UNGGULAN & DUNIA ILMU MIN 1 CIAMIS                             -->
    <!-- ========================================================================= -->
    <section class="programs-section" id="program">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Pendidikan Berkualitas</span>
                <h2 class="section-title">Program Unggulan MIN 1 Ciamis</h2>
                <p class="section-desc">Mengembangkan potensi intelektual, spiritual, dan emosional santri secara berimbang
                </p>
                <div class="section-divider"></div>
            </div>

            <div class="programs-grid">
                <div class="program-card-modern">
                    <div class="program-card-banner">
                        <img src="{{ asset('frontend/assets/imgs/page/img-4.png') }}" alt="Tahfidzul Qur'an" />
                        <span class="program-card-badge"><i class="fas fa-quran"></i> Karakter Qur'ani</span>
                    </div>
                    <div class="program-card-body">
                        <h3 class="program-card-title">Tahfidz &amp; Tartil Qur'an</h3>
                        <p class="program-card-desc">Program pembiasaan hafalan surat pilihan dan Juz 30 dengan bimbingan
                            ustadz/ustadzah bersertifikasi makharijul huruf.</p>
                        <ul class="program-card-features">
                            <li><i class="fas fa-check-circle"></i> Target Hafalan Juz Amma (Juz 30)</li>
                            <li><i class="fas fa-check-circle"></i> Pembiasaan Dhuha &amp; Dhuhur Berjamaah</li>
                            <li><i class="fas fa-check-circle"></i> Munaqosyah &amp; Wisuda Tahfidz</li>
                        </ul>
                    </div>
                </div>

                <div class="program-card-modern">
                    <div class="program-card-banner">
                        <img src="{{ asset('frontend/assets/imgs/page/img-5.png') }}" alt="Club Sains Madrasah" />
                        <span class="program-card-badge"><i class="fas fa-flask"></i> Sains &amp; Riset</span>
                    </div>
                    <div class="program-card-body">
                        <h3 class="program-card-title">Club Sains Madrasah (KSM)</h3>
                        <p class="program-card-desc">Inkubasi talenta sains dan matematika terintegrasi nilai-nilai
                            keislaman untuk mencetak juara olimpiade sains madrasah.</p>
                        <ul class="program-card-features">
                            <li><i class="fas fa-check-circle"></i> Bimbingan Matematika Terintegrasi</li>
                            <li><i class="fas fa-check-circle"></i> Praktikum Sains &amp; Lingkungan Hidup</li>
                            <li><i class="fas fa-check-circle"></i> Mentoring Juara KSM &amp; OSN</li>
                        </ul>
                    </div>
                </div>

                <div class="program-card-modern">
                    <div class="program-card-banner">
                        <img src="{{ asset('frontend/assets/imgs/page/img-6.png') }}" alt="Madrasah Digital" />
                        <span class="program-card-badge"><i class="fas fa-laptop"></i> Digitalisasi</span>
                    </div>
                    <div class="program-card-body">
                        <h3 class="program-card-title">Madrasah Digital &amp; Literasi</h3>
                        <p class="program-card-desc">Pemanfaatan media pembelajaran digital interaktif, asesmen berbasis
                            komputer, dan penguatan literasi 3 bahasa.</p>
                        <ul class="program-card-features">
                            <li><i class="fas fa-check-circle"></i> Media Pembelajaran Interaktif</li>
                            <li><i class="fas fa-check-circle"></i> Presensi QR Code &amp; Rapor Digital</li>
                            <li><i class="fas fa-check-circle"></i> Pembiasaan Literasi Pagi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- DUNIA ILMU MIN 1 CIAMIS (DINAMIS DARI DATABASE: LINK & APP TSX/JS)        -->
    <!-- ========================================================================= -->
    @php
        \App\Models\DuniaIlmu::pastikanTabelSiap();
        $duniaIlmus = \App\Models\DuniaIlmu::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $opsiKelasDunia = \App\Models\DuniaIlmu::ambilDaftarKelas();
        $opsiMapelDunia = \App\Models\DuniaIlmu::ambilDaftarMapel();

        $duniaDatabaseJs = $duniaIlmus
            ->map(function ($m) {
                return [
                    'id' => 'media-' . $m->id,
                    'nama' => $m->judul,
                    'kelas' => $m->kelas ?: 'Semua Kelas',
                    'mapel' => $m->mapel ?: 'Umum / Lintas Mapel',
                    'label' => ($m->mapel ?: 'Media') . ' • ' . ($m->kelas ?: 'Semua Kelas'),
                    'avatar' => $m->gambar_url,
                    'url' => $m->url_tujuan,
                    'tipe' => $m->tipe_sumber,
                    'deskripsi' => $m->deskripsi ?: $m->kategori_label,
                ];
            })
            ->values();
    @endphp

    <section class="section guru-tendik-section dunia-ilmu-section" id="dunia-ilmu">
        <div id="layanan" style="position: absolute; top: -80px;"></div>
        <div class="container">
            <div class="guru-tendik-card">

                <!-- Header Row -->
                <div class="guru-header-row" style="margin-bottom: 18px !important;">
                    <div class="guru-header-left">
                        <div class="guru-badge-tag">
                            <i class="fas fa-compass"></i> DUNIA ILMU MADRASAH ({{ $duniaIlmus->count() }} MEDIA)
                        </div>
                        <h2 class="guru-main-title">Dunia Ilmu MIN 1 Ciamis</h2>
                        <p class="guru-sub-desc">
                            Pilih <b>Kelas</b> dan <b>Mata Pelajaran</b> untuk mengakses aplikasi pembelajaran interaktif
                            dan modul digital santri.
                        </p>
                    </div>

                    <div class="guru-header-right">
                        <button type="button" class="guru-view-all-btn" onclick="bukaModalKatalogDuniaIlmu()">
                            <span>Katalog Lengkap</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
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

                <!-- Bar Filter Langsung: Pilih Kelas & Pilih Mata Pelajaran -->
                <div
                    style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; background: #ffffff; border: 1.5px solid #e2e8f0; padding: 12px 16px; border-radius: 18px; margin-bottom: 22px;">
                    <div
                        style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 800; color: #0d6a56;">
                        <i class="fas fa-filter"></i> Filter Cepat:
                    </div>

                    <select id="filterHomeKelas" onchange="saringKartuDuniaHome()"
                        style="padding: 8px 14px; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; font-weight: 700; color: #0f172a; background: #f8fafc; cursor: pointer;">
                        <option value="semua">🏫 Semua Kelas</option>
                        @foreach ($opsiKelasDunia as $kls)
                            @if ($kls !== 'Semua Kelas')
                                <option value="{{ $kls }}">{{ $kls }}</option>
                            @endif
                        @endforeach
                    </select>

                    <select id="filterHomeMapel" onchange="saringKartuDuniaHome()"
                        style="padding: 8px 14px; border-radius: 9999px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; font-weight: 700; color: #0f172a; background: #f8fafc; cursor: pointer;">
                        <option value="semua">📚 Semua Mata Pelajaran</option>
                        @foreach ($opsiMapelDunia as $mpl)
                            <option value="{{ $mpl }}">{{ $mpl }}</option>
                        @endforeach
                    </select>

                    <button type="button" onclick="resetFilterDuniaHome()"
                        style="background: none; border: none; color: #64748b; font-size: 0.8rem; font-weight: 700; cursor: pointer; margin-left: auto;">
                        <i class="fas fa-redo-alt"></i> Tampilkan Semua
                    </button>
                </div>

                <!-- Slider Track Dinamis -->
                <div class="guru-slider-viewport" id="duniaIlmuViewport">
                    <div class="guru-slider-track" id="duniaIlmuTrack">
                        @foreach ($duniaIlmus as $media)
                            @php
                                $klsMedia = $media->kelas ?: 'Semua Kelas';
                                $mplMedia = $media->mapel ?: 'Umum / Lintas Mapel';
                            @endphp
                            <a href="{{ $media->url_tujuan }}"
                                target="{{ $media->tipe_sumber === 'link' ? '_blank' : '_self' }}"
                                rel="noopener noreferrer" class="guru-card dunia-media-card item-dunia-home"
                                data-kelas="{{ $klsMedia }}" data-mapel="{{ $mplMedia }}"
                                title="Buka {{ $media->judul }}">
                                <div class="guru-card-banner {{ $media->warna_gradasi }}">
                                    <!-- Badge Kelas di Kiri Atas -->
                                    <span
                                        style="position: absolute; top: 10px; left: 12px; background: rgba(255, 255, 255, 0.92); color: #0f172a; font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                        {{ $klsMedia }}
                                    </span>

                                    @if ($media->tipe_sumber === 'kode_interaktif')
                                        <span
                                            style="position: absolute; top: 10px; right: 12px; background: rgba(15, 23, 42, 0.82); color: #FFE15D; font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 9999px; border: 1px solid rgba(255, 225, 93, 0.4);">
                                            ⚡ APP INTERAKTIF
                                        </span>
                                    @endif

                                    <div class="guru-photo-box" style="padding: 14px;">
                                        <img src="{{ $media->gambar_url }}" alt="{{ $media->judul }}"
                                            class="guru-photo-img" style="object-fit: contain;" loading="lazy">
                                    </div>
                                </div>
                                <div class="guru-card-body">
                                    <h4 class="guru-name">{{ $media->judul }}</h4>
                                    <p class="guru-role">{{ $mplMedia }}</p>
                                    <div class="dunia-link-action">
                                        <span>{{ $media->tipe_sumber === 'kode_interaktif' ? 'Mainkan Aplikasi' : 'Buka Media' }}</span>
                                        <i
                                            class="fas {{ $media->tipe_sumber === 'kode_interaktif' ? 'fa-play-circle' : 'fa-external-link-alt' }}"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 8. KABAR MADRASAH (GRID BERITA DARI DATABASE + FILTER KATEGORI)           -->
    <!-- ========================================================================= -->
    <section class="section section-bg-alt" id="berita">
        <div class="container">
            <div class="news-header-row" style="margin-bottom: 24px">
                <div>
                    <span class="section-subtitle">Warta &amp; Informasi Terkini</span>
                    <h2 class="section-title">Kabar Madrasah</h2>
                </div>
            </div>

            <div class="news-grid" id="news-cards-grid">
                @foreach ($semuaBeritaGrid as $item)
                    <div class="news-card" data-category="{{ $item->kategori }}"
                        onclick="window.location.href='{{ route('berita.show', $item->slug) }}'">
                        <div class="news-thumb-wrap">
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="news-thumb" />
                            <span class="news-category-badge">{{ $item->kategori }}</span>
                        </div>
                        <div class="news-body">
                            <div class="news-date" style="font-size: 0.82rem; color: #64748b; margin-bottom: 8px;">
                                <i class="far fa-calendar-alt"></i> {{ $item->created_at->translatedFormat('d F Y') }}
                                &bull; {{ $item->views }}x dibaca
                            </div>
                            <h3 class="news-title">{{ $item->judul }}</h3>
                            <p class="news-excerpt" style="font-size: 0.9rem; color: #475569; margin-bottom: 16px;">
                                {{ Str::limit($item->ringkasan ?: strip_tags($item->isi_konten), 120) }}
                            </p>
                            <a href="{{ route('berita.show', $item->slug) }}" class="news-read-more"
                                style="margin-top: auto;">
                                <span>Baca Selengkapnya</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 9. MUTIARA HIKMAH ISLAMI INTERAKTIF                                       -->
    <!-- ========================================================================= -->
    <section class="mutiara-section" id="mutiara-hikmah">
        <div class="container">
            <div class="mutiara-card">
                <div class="mutiara-quote-wrap">
                    <div class="mutiara-badge">
                        <i class="fas fa-moon"></i>
                        <span>Mutiara Hikmah &amp; Motivasi Hari Ini</span>
                    </div>
                    <div class="mutiara-quote-text" id="mutiara-text">
                        "Barangsiapa menempuh suatu jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan
                        menuju surga."
                    </div>
                    <div class="mutiara-author" id="mutiara-author">— HR. Muslim</div>
                </div>
                <div class="mutiara-actions">
                    <button type="button" class="mutiara-btn-shuffle" id="mutiara-shuffle-btn"
                        onclick="shuffleMutiaraHikmah()">
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
            document.querySelectorAll(".sekilas-tab-btn").forEach(function(btn) {
                btn.classList.toggle("active", btn.getAttribute("data-tab") === tabKey);
            });
            document.querySelectorAll(".sekilas-tab-pane").forEach(function(pane) {
                pane.style.display = (pane.id === "sekilas-pane-" + tabKey) ? "block" : "none";
            });
            var elKiri = document.getElementById("teksKiriJudulSekilas");
            var elKanan = document.getElementById("teksKananJudulSekilas");
            if (elKiri) elKiri.innerText = judulKiri;
            if (elKanan) elKanan.innerText = judulKanan;
        }
        // Sinkronkan katalog modal Dunia Ilmu dengan database Laravel
        window.DUNIA_ILMU_ITEMS = @json($duniaDatabaseJs);
        window.OPSI_KELAS_DB = @json($opsiKelasDunia);
        window.OPSI_MAPEL_DB = @json($opsiMapelDunia);

        function saringKartuDuniaHome() {
            var pilihKelas = document.getElementById('filterHomeKelas').value;
            var pilihMapel = document.getElementById('filterHomeMapel').value;
            var cards = document.querySelectorAll('.item-dunia-home');

            cards.forEach(function(card) {
                var kls = card.getAttribute('data-kelas');
                var mpl = card.getAttribute('data-mapel');

                var cocokKelas = (pilihKelas === 'semua') ? true : (kls === pilihKelas ? true : kls ===
                    'Semua Kelas');
                var cocokMapel = (pilihMapel === 'semua') ? true : (mpl === pilihMapel);

                if (cocokKelas && cocokMapel) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function resetFilterDuniaHome() {
            document.getElementById('filterHomeKelas').value = 'semua';
            document.getElementById('filterHomeMapel').value = 'semua';
            saringKartuDuniaHome();
        }

        function bukaModalKatalogDuniaIlmu() {
            var modalOverlay = document.getElementById('general-modal');
            var modalTitle = document.getElementById('modal-title');
            var modalContent = document.getElementById('modal-content-area');
            if (!modalOverlay) return;

            modalTitle.innerHTML =
                '<i class="fas fa-compass" style="color:#0D6A56;margin-right:8px;"></i> Dunia Ilmu MIN 1 Ciamis';

            var opsiKlsHtml = '<option value="semua">🏫 Semua Kelas</option>';
            window.OPSI_KELAS_DB.forEach(function(k) {
                if (k !== 'Semua Kelas') {
                    opsiKlsHtml += '<option value="' + k + '">' + k + '</option>';
                }
            });

            var opsiMplHtml = '<option value="semua">📚 Semua Mata Pelajaran</option>';
            window.OPSI_MAPEL_DB.forEach(function(m) {
                opsiMplHtml += '<option value="' + m + '">' + m + '</option>';
            });

            modalContent.innerHTML =
                '<div style="display:flex;flex-direction:column;gap:16px;">' +
                '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;background:#f8fafc;border:1px solid #e2e8f0;padding:14px;border-radius:16px;">' +
                '<input type="text" id="cariNamaMediaModal" placeholder="🔍 Ketik judul media..." oninput="renderGridModalDunia()" style="padding:9px 14px;border-radius:9999px;border:1.5px solid #cbd5e1;font-size:0.86rem;outline:none;">' +
                '<select id="pilihKelasModal" onchange="renderGridModalDunia()" style="padding:9px 14px;border-radius:9999px;border:1.5px solid #cbd5e1;font-size:0.86rem;font-weight:700;">' +
                opsiKlsHtml + '</select>' +
                '<select id="pilihMapelModal" onchange="renderGridModalDunia()" style="padding:9px 14px;border-radius:9999px;border:1.5px solid #cbd5e1;font-size:0.86rem;font-weight:700;">' +
                opsiMplHtml + '</select>' +
                '</div>' +
                '<div id="wadahGridModalDunia" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;max-height:420px;overflow-y:auto;padding:4px;"></div>' +
                '</div>';

            renderGridModalDunia();
            modalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function renderGridModalDunia() {
            var q = (document.getElementById('cariNamaMediaModal')?.value ?? '').toLowerCase();
            var kls = document.getElementById('pilihKelasModal')?.value ?? 'semua';
            var mpl = document.getElementById('pilihMapelModal')?.value ?? 'semua';
            var box = document.getElementById('wadahGridModalDunia');
            if (!box) return;

            var hasil = window.DUNIA_ILMU_ITEMS.filter(function(item) {
                var cocokQ = item.nama.toLowerCase().includes(q) ? true : item.mapel.toLowerCase().includes(q);
                var cocokKls = (kls === 'semua') ? true : (item.kelas === kls ? true : item.kelas ===
                    'Semua Kelas');
                var cocokMpl = (mpl === 'semua') ? true : (item.mapel === mpl);
                return cocokQ && cocokKls && cocokMpl;
            });

            if (hasil.length === 0) {
                box.innerHTML =
                    '<div style="grid-column:1/-1;text-align:center;padding:32px;color:#64748b;">Tidak ada media untuk filter Kelas &amp; Mata Pelajaran ini.</div>';
                return;
            }

            box.innerHTML = hasil.map(function(item) {
                return '<a href="' + item.url + '" target="' + (item.tipe === 'link' ? '_blank' : '_self') +
                    '" style="background:#fff;border:1.5px solid #e2e8f0;border-radius:16px;padding:16px;display:flex;flex-direction:column;justify-content:space-between;gap:10px;text-decoration:none;color:inherit;">' +
                    '<div style="display:flex;align-items:center;gap:12px;">' +
                    '<img src="' + item.avatar +
                    '" style="width:46px;height:46px;border-radius:12px;object-fit:contain;border:1px solid #e2e8f0;padding:4px;">' +
                    '<div>' +
                    '<span style="display:inline-block;background:#ecfdf5;color:#059669;font-size:10px;font-weight:800;padding:2px 8px;border-radius:9999px;margin-bottom:3px;">' +
                    item.kelas + '</span>' +
                    '<div style="font-weight:800;font-size:0.95rem;color:#0f172a;line-height:1.25;">' + item.nama +
                    '</div>' +
                    '<div style="font-size:0.78rem;color:#0d6a56;font-weight:700;">' + item.mapel + '</div>' +
                    '</div>' +
                    '</div>' +
                    '<p style="font-size:0.8rem;color:#64748b;margin:0;line-height:1.4;">' + item.deskripsi +
                    '</p>' +
                    '<div style="border-top:1px solid #f1f5f9;padding-top:8px;display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;font-weight:800;color:#0d6a56;">' +
                    '<span>' + (item.tipe === 'kode_interaktif' ? '⚡ Aplikasi Interaktif' : '🔗 Tautan Web') +
                    '</span>' +
                    '<span>Buka &rarr;</span>' +
                    '</div>' +
                    '</a>';
            }).join('');
        }

        function toggleLayananCepat() {
            var grid = document.getElementById('gridLayananCepat');
            var teks = document.getElementById('teksToggleLayanan');
            var ikon = document.getElementById('ikonToggleLayanan');

            if (!grid) return;

            grid.classList.toggle('expanded');
            if (grid.classList.contains('expanded')) {
                teks.innerText = 'Sembunyikan Sebagian';
                ikon.className = 'fas fa-chevron-up';
            } else {
                teks.innerText = 'Lihat Selengkapnya';
                ikon.className = 'fas fa-chevron-down';
            }
        }
    </script>

@endsection
