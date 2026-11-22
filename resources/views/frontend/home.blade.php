@extends('layouts.frontend')

@section('title', 'Beranda - MIN 1 CIAMIS | Madrasah Ibtidaiyah Negeri 1 Ciamis')
<style>
    /* =========================================================================
       HARMONISASI TAMPILAN RESMI LANDING PAGE MIN 1 CIAMIS (DESKTOP & MOBILE)
       Design Tokens:
       - Primary Green: #1B9C85 (Dark: #0D6A56, Soft: #EDF6EE)
       - Accent Gold: #F59E0B / #FFE15D
       - Dark Slate: #0F172A / #1E293B
       - Text Body: #334155 / #475569
       - Fonts: 'Outfit' (Headings), 'Plus Jakarta Sans' (Pills/Buttons),
                'Caveat'/'Dancing Script' (Script), 'Rubik'/'Poppins' (Body)
       ========================================================================= */

    /* -------------------------------------------------------------------------
       1. HERO SLIDER SECTION (FLUID TYPOGRAPHY & ADAPTIVE HEIGHT)
       ------------------------------------------------------------------------- */
    .hero-slider-section {
        position: relative !important;
        overflow: hidden !important;
        background: #F8FAFC !important;
    }

    .hero-slider-track {
        height: 520px !important;
        transition: height 0.3s ease !important;
    }

    /* Mode 1: Teks di Tengah (Center Single) */
    .hero-slide-centered-single {
        width: 100% !important;
        max-width: 980px !important;
        margin: 0 auto !important;
        padding: 0 28px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        position: relative !important;
        z-index: 4 !important;
    }

    .hero-slide-centered-single .hero-script-heading {
        font-family: 'Caveat', 'Dancing Script', cursive !important;
        font-size: clamp(1.8rem, 3.4vw, 2.7rem) !important;
        font-weight: 700 !important;
        color: #1B9C85 !important;
        line-height: 1.2 !important;
        margin-bottom: 2px !important;
        letter-spacing: -0.5px !important;
    }

    .hero-slide-centered-single .hero-bold-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: clamp(2.3rem, 5vw, 4.2rem) !important;
        font-weight: 800 !important;
        letter-spacing: -0.5px !important;
        margin-bottom: 12px !important;
        line-height: 1.1 !important;
        color: #0F172A !important;
        text-transform: uppercase !important;
    }

    .hero-slide-centered-single .hero-domain-line {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: clamp(0.85rem, 1.6vw, 1.15rem) !important;
        letter-spacing: clamp(1.5px, 0.4vw, 3.8px) !important;
        margin-bottom: 20px !important;
        color: #1B9C85 !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }

    .hero-slide-centered-single .hero-slogan-lines {
        font-family: 'Rubik', 'Poppins', sans-serif !important;
        font-size: clamp(0.88rem, 1.25vw, 1.05rem) !important;
        line-height: 1.7 !important;
        color: #475569 !important;
        max-width: 740px !important;
        margin: 0 auto !important;
        padding-top: 14px !important;
        border-top: 1.5px dashed rgba(27, 156, 133, 0.35) !important;
    }

    /* Mode 2: Teks di Kiri + Foto di Kanan */
    .hero-slide-flex-container {
        width: 100% !important;
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 36px !important;
        display: grid !important;
        grid-template-columns: 1.1fr 1fr !important;
        gap: 36px !important;
        align-items: center !important;
    }

    .hero-slide-left-text .hero-script-heading {
        font-family: 'Caveat', 'Dancing Script', cursive !important;
        font-size: clamp(1.8rem, 3vw, 2.5rem) !important;
        font-weight: 700 !important;
        color: #1B9C85 !important;
        line-height: 1.2 !important;
        margin-bottom: 2px !important;
    }

    .hero-slide-left-text .hero-bold-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: clamp(2.1rem, 4.4vw, 3.6rem) !important;
        font-weight: 800 !important;
        letter-spacing: -0.5px !important;
        line-height: 1.1 !important;
        color: #0F172A !important;
        margin-bottom: 10px !important;
        text-transform: uppercase !important;
    }

    .hero-slogan-clean {
        border-left: 3.5px solid #1B9C85 !important;
        padding-left: 14px !important;
        margin-top: 8px !important;
        color: #475569 !important;
        line-height: 1.65 !important;
        font-size: clamp(0.88rem, 1.2vw, 1rem) !important;
    }

    .hero-collage-wrap {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .hero-collage-main-img {
        max-height: 380px !important;
        width: 100% !important;
        object-fit: cover !important;
        border-radius: 24px !important;
    }

    /* Responsive Hero on Tablet & Mobile */
    @media (max-width: 991px) {
        .hero-slider-track {
            height: 480px !important;
        }
        .hero-slide-flex-container {
            grid-template-columns: 1fr !important;
            text-align: center !important;
            gap: 20px !important;
            padding: 0 24px !important;
        }
        .hero-slogan-clean {
            border-left: none !important;
            padding-left: 0 !important;
        }
        .hero-collage-wrap {
            max-width: 380px !important;
            margin: 0 auto !important;
        }
        .hero-collage-main-img {
            max-height: 260px !important;
        }
    }

    @media (max-width: 576px) {
        .hero-slider-track {
            height: auto !important;
            min-height: 400px !important;
            padding: 24px 0 56px 0 !important;
        }
        .hero-slide {
            position: relative !important;
            height: auto !important;
            min-height: 380px !important;
            display: none !important;
        }
        .hero-slide.active {
            display: flex !important;
        }
        .hero-slide-centered-single {
            padding: 0 16px !important;
        }
        .hero-slide-centered-single .hero-script-heading {
            font-size: 1.85rem !important;
        }
        .hero-slide-centered-single .hero-bold-title {
            font-size: 2.15rem !important;
            margin-bottom: 8px !important;
        }
        .hero-slide-centered-single .hero-domain-line {
            font-size: 0.85rem !important;
            letter-spacing: 1.8px !important;
            margin-bottom: 12px !important;
        }
        .hero-slide-centered-single .hero-slogan-lines {
            font-size: 0.82rem !important;
            padding-top: 10px !important;
            line-height: 1.6 !important;
        }
        .hero-slider-section .slider-arrow {
            width: 36px !important;
            height: 36px !important;
            font-size: 0.85rem !important;
            opacity: 0.75 !important;
        }
        .hero-slider-section .slider-arrow.prev { left: 8px !important; }
        .hero-slider-section .slider-arrow.next { right: 8px !important; }
        .slider-dots-container {
            bottom: 12px !important;
            padding: 4px 10px !important;
            gap: 6px !important;
        }
        .slider-dot { width: 8px !important; height: 8px !important; }
        .slider-dot.active { width: 22px !important; }
    }

    /* -------------------------------------------------------------------------
       2. SAMBUTAN & SEKILAS MADRASAH (#profil)
       ------------------------------------------------------------------------- */
    .sekilas-section {
        padding: 65px 0 !important;
    }
    .sekilas-grid {
        gap: 48px !important;
    }
    .sekilas-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: clamp(1.8rem, 3.2vw, 2.5rem) !important;
        font-weight: 800 !important;
        line-height: 1.2 !important;
        margin-bottom: 20px !important;
    }
    .sekilas-quote {
        font-family: 'Outfit', sans-serif !important;
        font-size: clamp(1.05rem, 1.8vw, 1.35rem) !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        line-height: 1.45 !important;
        margin-bottom: 12px !important;
    }
    .sekilas-text {
        font-size: clamp(0.88rem, 1.1vw, 0.96rem) !important;
        line-height: 1.75 !important;
        color: #475569 !important;
    }

    /* Horizontal swipeable tabs on mobile */
    @media (max-width: 768px) {
        .sekilas-section {
            padding: 40px 0 !important;
        }
        .sekilas-tabs-nav {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            padding: 4px 2px 10px 2px !important;
            gap: 8px !important;
            margin-bottom: 18px !important;
            scrollbar-width: none !important;
        }
        .sekilas-tabs-nav::-webkit-scrollbar {
            display: none !important;
        }
        .sekilas-tab-btn {
            flex-shrink: 0 !important;
            white-space: nowrap !important;
            padding: 8px 18px !important;
            font-size: 11.5px !important;
        }
        .sekilas-card-frame {
            border-radius: 24px !important;
            padding: 12px !important;
        }
        .sekilas-card-img-wrapper {
            height: 330px !important;
            border-radius: 20px !important;
        }
    }

    /* -------------------------------------------------------------------------
       3. BANNER PPDB & LAYANAN CEPAT (#ppdb)
       ------------------------------------------------------------------------- */
    .hero-bottom-features-section {
        margin: 32px 0 54px 0 !important;
    }
    .ppdb-landscape-banner-wrap {
        width: 100%;
        margin-bottom: 20px;
        border-radius: 20px;
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
        object-fit: cover !important;
        display: block !important;
    }

    #layanan-cepat.layanan-cepat-card {
        padding: 28px 32px !important;
        border-radius: 24px !important;
        background: linear-gradient(135deg, #064E3B 0%, #0D6A56 50%, #1B9C85 100%) !important;
        box-shadow: 0 16px 36px rgba(13, 106, 86, 0.22) !important;
    }
    #layanan-cepat .layanan-cepat-header {
        margin-bottom: 18px !important;
    }
    #layanan-cepat .layanan-cepat-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: 1.6rem !important;
        font-weight: 800 !important;
        color: #FFFFFF !important;
        margin: 0 0 4px 0 !important;
    }
    #layanan-cepat .layanan-cepat-subtitle {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.88rem !important;
        color: #E2E8F0 !important;
    }
    .layanan-cepat-grid-custom {
        display: grid !important;
        grid-template-columns: repeat(6, 1fr) !important;
        gap: 12px !important;
    }
    .layanan-cepat-grid-custom .lc-extra-item {
        display: none !important;
    }
    .layanan-cepat-grid-custom.expanded .lc-extra-item {
        display: flex !important;
        animation: fadeInLayanan 0.35s ease forwards;
    }
    @keyframes fadeInLayanan {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    #layanan-cepat .layanan-cepat-item {
        background: rgba(255, 255, 255, 0.94) !important;
        padding: 14px 10px !important;
        min-height: 84px !important;
        border-radius: 16px !important;
        gap: 6px !important;
        text-decoration: none !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.2s ease !important;
    }
    #layanan-cepat .layanan-cepat-item:hover {
        transform: translateY(-4px) !important;
        background: #FFFFFF !important;
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.15) !important;
    }
    #layanan-cepat .lc-icon {
        width: 32px !important;
        height: 32px !important;
        color: #1B9C85 !important;
        font-size: 1.15rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    #layanan-cepat .lc-label {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.78rem !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        line-height: 1.25 !important;
    }

    /* Tombol Lihat Selengkapnya Layanan Cepat */
    .btn-layanan-more-wrap {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin-top: 22px !important;
        position: relative !important;
        z-index: 5 !important;
    }
    .btn-layanan-more {
        background: rgba(255, 255, 255, 0.16) !important;
        color: #FFFFFF !important;
        border: 1.5px solid rgba(255, 255, 255, 0.42) !important;
        padding: 9px 24px !important;
        border-radius: 50px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        cursor: pointer !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12) !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        outline: none !important;
    }
    .btn-layanan-more:hover {
        background: #FFFFFF !important;
        color: #0D6A56 !important;
        border-color: #FFFFFF !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.22) !important;
    }
    .btn-layanan-more:active {
        transform: translateY(0) !important;
    }
    .btn-layanan-more i {
        font-size: 0.8rem !important;
        transition: transform 0.25s ease !important;
    }

    @media (max-width: 991px) {
        .layanan-cepat-grid-custom {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
        }
    }
    @media (max-width: 576px) {
        .hero-bottom-features-section {
            margin: 18px 0 36px 0 !important;
        }
        .ppdb-landscape-banner-wrap {
            border-radius: 14px;
            margin-bottom: 14px;
        }
        #layanan-cepat.layanan-cepat-card {
            padding: 18px 14px !important;
            border-radius: 18px !important;
        }
        #layanan-cepat .layanan-cepat-title {
            font-size: 1.25rem !important;
        }
        #layanan-cepat .layanan-cepat-subtitle {
            font-size: 0.74rem !important;
        }
        .layanan-cepat-grid-custom {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 8px !important;
        }
        #layanan-cepat .layanan-cepat-item {
            padding: 10px 6px !important;
            min-height: 70px !important;
            border-radius: 12px !important;
        }
        #layanan-cepat .lc-icon {
            width: 24px !important;
            height: 24px !important;
            font-size: 0.95rem !important;
        }
        #layanan-cepat .lc-label {
            font-size: 0.68rem !important;
        }
    }

    /* -------------------------------------------------------------------------
       4. CAROUSEL SOROTAN BERITA (#carousel-banner)
       ------------------------------------------------------------------------- */
    .sec-9-home-1 {
        padding-top: 10px !important;
        padding-bottom: 45px !important;
    }
    .block-card-swiper {
        padding: 70px 0 !important;
        border-radius: 24px !important;
    }
    @media (max-width: 768px) {
        .block-card-swiper {
            padding: 32px 14px !important;
            border-radius: 18px !important;
        }
        .block-card-swiper .card-swiper-thumb .thumb {
            width: 72px !important;
            height: 58px !important;
            min-width: 72px !important;
            min-height: 58px !important;
        }
    }

    /* -------------------------------------------------------------------------
       5. DIREKTORI GURU & TENDIK (#guru)
       ------------------------------------------------------------------------- */
    .guru-tendik-section {
        padding: 55px 0 !important;
    }
    .guru-main-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: clamp(1.6rem, 2.8vw, 2.2rem) !important;
        font-weight: 800 !important;
        color: #0F172A !important;
    }
    .guru-card {
        border-radius: 20px !important;
        overflow: hidden !important;
        border: 1px solid #E2E8F0 !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease !important;
    }
    .guru-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08) !important;
    }

    /* -------------------------------------------------------------------------
       RESET & HARMONISASI SECTION HEADERS (.section-title, .section-subtitle, etc.)
       Mengatasi bentrokan class .section-title dengan main.css (Magzin template)
       ------------------------------------------------------------------------- */
    .section-header {
        text-align: center !important;
        max-width: 760px !important;
        margin: 0 auto 40px auto !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .section-title {
        display: block !important;
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: clamp(1.8rem, 3.2vw, 2.5rem) !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        text-align: center !important;
        margin: 0 auto 12px auto !important;
        position: relative !important;
        width: 100% !important;
        line-height: 1.25 !important;
    }
    .section-title.white {
        color: #FFFFFF !important;
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    .section-subtitle {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.88rem !important;
        font-weight: 800 !important;
        color: #1B9C85 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        margin-bottom: 8px !important;
        display: inline-block !important;
        text-align: center !important;
    }
    .section-subtitle.white {
        color: #4EBAA6 !important;
    }
    .section-desc {
        font-family: 'Rubik', 'Poppins', sans-serif !important;
        font-size: clamp(0.9rem, 1.2vw, 1.05rem) !important;
        color: #64748B !important;
        line-height: 1.65 !important;
        text-align: center !important;
        margin: 0 auto !important;
        max-width: 650px !important;
    }
    .section-divider {
        width: 60px !important;
        height: 4px !important;
        background-color: #1B9C85 !important;
        margin: 14px auto 0 auto !important;
        border-radius: 9999px !important;
    }

    /* Penyelarasan Khusus Kabar Madrasah Header */
    .news-header-row {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        margin-bottom: 35px !important;
    }
    .news-header-row .section-title {
        text-align: center !important;
        margin: 0 auto !important;
    }

    /* -------------------------------------------------------------------------
       6. PRESTASI SISWA (#prestasi)
       ------------------------------------------------------------------------- */
    .stats-section {
        padding: 65px 0 !important;
    }
    .stat-card {
        border-radius: 20px !important;
        padding: 24px 18px !important;
    }
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }
        .stat-card {
            padding: 16px 12px !important;
        }
        .stat-number {
            font-size: 1.8rem !important;
        }
    }

    /* -------------------------------------------------------------------------
       7. PROGRAM UNGGULAN (#program)
       ------------------------------------------------------------------------- */
    .programs-section {
        padding: 65px 0 !important;
    }
    .program-card-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: 1.2rem !important;
        font-weight: 800 !important;
        color: #0F172A !important;
    }

    /* -------------------------------------------------------------------------
       8. DUNIA ILMU MADRASAH (#dunia-ilmu)
       ------------------------------------------------------------------------- */
    .dunia-ilmu-section {
        padding: 55px 0 !important;
    }
    .dunia-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        padding: 12px 18px;
        border-radius: 18px;
        margin-bottom: 22px;
    }
    @media (max-width: 768px) {
        .dunia-filter-bar {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
            padding: 12px !important;
        }
        .dunia-filter-bar .filter-title {
            grid-column: 1 / -1 !important;
        }
        .dunia-filter-bar select {
            width: 100% !important;
            font-size: 0.8rem !important;
            padding: 8px 10px !important;
        }
        .dunia-filter-bar .btn-reset-filter {
            grid-column: 1 / -1 !important;
            text-align: center !important;
            margin: 4px auto 0 !important;
        }
    }

    /* -------------------------------------------------------------------------
       9. KABAR MADRASAH (#berita)
       ------------------------------------------------------------------------- */
    .news-card {
        border-radius: 20px !important;
        overflow: hidden !important;
        border: 1px solid #E2E8F0 !important;
        display: flex !important;
        flex-direction: column !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease !important;
    }
    .news-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08) !important;
    }
    .news-thumb {
        width: 100% !important;
        aspect-ratio: 16 / 10 !important;
        object-fit: cover !important;
    }
    .news-title {
        font-family: 'Outfit', sans-serif !important;
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        line-height: 1.4 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    .news-excerpt {
        display: -webkit-box !important;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }

    /* -------------------------------------------------------------------------
       10. MUTIARA HIKMAH (#mutiara-hikmah)
       ------------------------------------------------------------------------- */
    .mutiara-section {
        padding: 50px 0 70px 0 !important;
    }
    .mutiara-card {
        border-radius: 24px !important;
        padding: 38px 32px !important;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12) !important;
    }
    .mutiara-quote-text {
        font-size: clamp(1.05rem, 2.2vw, 1.35rem) !important;
        line-height: 1.65 !important;
    }
    @media (max-width: 576px) {
        .mutiara-card {
            padding: 24px 18px !important;
            border-radius: 18px !important;
        }
        .mutiara-actions {
            display: flex !important;
            gap: 10px !important;
            width: 100% !important;
        }
        .mutiara-actions button {
            flex: 1 !important;
            justify-content: center !important;
            padding: 8px 12px !important;
            font-size: 0.8rem !important;
        }
    }

    /* =========================================================================
       DUKUNGAN MODE MALAM & MODE SIANG (DARK & LIGHT MODE UNTUK MIN 1 CIAMIS)
       ========================================================================= */
    /* 1. Global & Background */
    [data-bs-theme="dark"] body {
        background-color: #0B1317 !important;
        color: #CBD5E1 !important;
    }
    [data-bs-theme="dark"] main {
        background-color: #0B1317 !important;
    }

    /* Icon Switching Automation */
    [data-bs-theme="dark"] .dark-light-switcher .svg-dark {
        display: none !important;
    }
    [data-bs-theme="dark"] .dark-light-switcher .svg-white {
        display: inline-flex !important;
    }
    :root:not([data-bs-theme="dark"]) .dark-light-switcher .svg-dark,
    [data-bs-theme="light"] .dark-light-switcher .svg-dark {
        display: inline-flex !important;
    }
    :root:not([data-bs-theme="dark"]) .dark-light-switcher .svg-white,
    [data-bs-theme="light"] .dark-light-switcher .svg-white {
        display: none !important;
    }

    /* 2. Floating Navbar & Controls */
    [data-bs-theme="dark"] .site-header {
        background: transparent !important;
    }
    [data-bs-theme="dark"] .header-container {
        background: rgba(15, 23, 42, 0.9) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="dark"] .nav-menu .menu-link {
        color: #E2E8F0 !important;
    }
    [data-bs-theme="dark"] .nav-menu .menu-link:hover,
    [data-bs-theme="dark"] .nav-menu .menu-item.active > .menu-link {
        color: #2DD4BF !important;
        background: rgba(45, 212, 191, 0.12) !important;
    }
    [data-bs-theme="dark"] .sub-menu,
    [data-bs-theme="dark"] .sub-menu .cucu-menu {
        background: rgba(19, 34, 40, 0.98) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 20px 42px rgba(0, 0, 0, 0.55) !important;
    }
    [data-bs-theme="dark"] .sub-menu .menu-link {
        color: #CBD5E1 !important;
    }
    [data-bs-theme="dark"] .sub-menu .menu-link:hover {
        color: #2DD4BF !important;
        background: rgba(45, 212, 191, 0.12) !important;
    }
    [data-bs-theme="dark"] .header-social-item,
    [data-bs-theme="dark"] .header-search-circle-btn,
    [data-bs-theme="dark"] .header-theme-toggle-btn {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.14) !important;
        color: #E2E8F0 !important;
    }
    [data-bs-theme="dark"] .header-social-item:hover,
    [data-bs-theme="dark"] .header-search-circle-btn:hover,
    [data-bs-theme="dark"] .header-theme-toggle-btn:hover {
        background: rgba(45, 212, 191, 0.2) !important;
        color: #2DD4BF !important;
        border-color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .mobile-toggle-btn {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.14) !important;
        color: #E2E8F0 !important;
    }

    /* 3. Section Titles & Dividers */
    [data-bs-theme="dark"] .section-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .section-subtitle {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .section-desc {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .section-bg-alt {
        background-color: #0D171C !important;
    }

    /* 4. Hero Slider */
    [data-bs-theme="dark"] .hero-slider-section {
        background: #0B1317 !important;
    }
    [data-bs-theme="dark"] .hero-animated-grid-bg {
        opacity: 0.05 !important;
    }
    [data-bs-theme="dark"] .hero-slide-centered-single .hero-bold-title,
    [data-bs-theme="dark"] .hero-slide-left-text .hero-bold-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .hero-slide-centered-single .hero-script-heading,
    [data-bs-theme="dark"] .hero-slide-left-text .hero-script-heading {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .hero-slide-centered-single .hero-domain-line,
    [data-bs-theme="dark"] .hero-slide-left-text .hero-domain-line {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .hero-slide-centered-single .hero-slogan-lines,
    [data-bs-theme="dark"] .hero-slide-left-text .hero-desc {
        color: #94A3B8 !important;
        border-top-color: rgba(45, 212, 191, 0.25) !important;
    }
    [data-bs-theme="dark"] .hero-slider-nav-btn {
        background: rgba(19, 34, 40, 0.9) !important;
        color: #E2E8F0 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
    }
    [data-bs-theme="dark"] .hero-slider-nav-btn:hover {
        background: #1B9C85 !important;
        color: #FFFFFF !important;
    }
    [data-bs-theme="dark"] .hero-slider-dot {
        background: rgba(255, 255, 255, 0.2) !important;
    }
    [data-bs-theme="dark"] .hero-slider-dot.active {
        background: #2DD4BF !important;
    }

    /* 5. Profil & Sambutan */
    [data-bs-theme="dark"] .sekilas-section {
        background: #0D171C !important;
    }
    [data-bs-theme="dark"] .sekilas-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .title-italic {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .title-highlight {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .sekilas-quote {
        color: #F1F5F9 !important;
    }
    [data-bs-theme="dark"] .sekilas-text {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .sekilas-tab-btn {
        background: rgba(255, 255, 255, 0.06) !important;
        color: #94A3B8 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .sekilas-tab-btn:hover {
        color: #2DD4BF !important;
        background: rgba(45, 212, 191, 0.12) !important;
    }
    [data-bs-theme="dark"] .sekilas-tab-btn.active {
        background: #1B9C85 !important;
        color: #FFFFFF !important;
        border-color: #1B9C85 !important;
    }
    [data-bs-theme="dark"] .sekilas-card-frame {
        background: #162B28 !important;
        border-color: rgba(45, 212, 191, 0.3) !important;
    }
    [data-bs-theme="dark"] .sekilas-feature-pill {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        color: #E2E8F0 !important;
    }
    [data-bs-theme="dark"] .sekilas-feature-pill i {
        color: #2DD4BF !important;
    }

    /* 6. Layanan Cepat */
    [data-bs-theme="dark"] #layanan-cepat.layanan-cepat-card {
        background: linear-gradient(135deg, #032119 0%, #064E3B 50%, #0A5A4A 100%) !important;
        border: 1.5px solid rgba(45, 212, 191, 0.25) !important;
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.45) !important;
    }
    [data-bs-theme="dark"] #layanan-cepat .layanan-cepat-item {
        background: rgba(15, 23, 42, 0.88) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] #layanan-cepat .layanan-cepat-item:hover {
        background: rgba(27, 156, 133, 0.35) !important;
        border-color: #2DD4BF !important;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35) !important;
    }
    [data-bs-theme="dark"] #layanan-cepat .lc-label {
        color: #F1F5F9 !important;
    }
    [data-bs-theme="dark"] #layanan-cepat .lc-icon {
        color: #2DD4BF !important;
    }

    /* 7. Carousel Berita */
    [data-bs-theme="dark"] .sec-9-home-1 {
        background: #0B1317 !important;
    }
    [data-bs-theme="dark"] .block-card-swiper {
        background: #111E24 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.45) !important;
    }
    [data-bs-theme="dark"] .block-card-swiper .title h3,
    [data-bs-theme="dark"] .block-card-swiper .title a {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .block-card-swiper .card-swiper-desc,
    [data-bs-theme="dark"] .block-card-swiper .card-swiper-desc p {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .block-card-swiper .slider-nav-thumb .card-swiper-item {
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
    }
    [data-bs-theme="dark"] .block-card-swiper .slider-nav-thumb .card-swiper-item.swiper-slide-thumb-active {
        background: rgba(45, 212, 191, 0.12) !important;
        border-color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .block-card-swiper .slider-nav-thumb .card-swiper-item .content-title {
        color: #E2E8F0 !important;
    }

    /* 8. Guru & Tendik */
    [data-bs-theme="dark"] .guru-tendik-section {
        background: #0D171C !important;
    }
    [data-bs-theme="dark"] .guru-tendik-card {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35) !important;
    }
    [data-bs-theme="dark"] .guru-main-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .guru-sub-desc {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .guru-card {
        background: #172A32 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .guru-card:hover {
        border-color: #2DD4BF !important;
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4) !important;
    }
    [data-bs-theme="dark"] .guru-name {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .guru-role {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .guru-nav-btn {
        background: #1B2F38 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        color: #F1F5F9 !important;
    }
    [data-bs-theme="dark"] .guru-nav-btn:hover {
        background: #1B9C85 !important;
        color: #FFFFFF !important;
    }

    /* 9. Prestasi Siswa */
    [data-bs-theme="dark"] .stats-section {
        background: linear-gradient(135deg, #020A06 0%, #05140C 100%) !important;
    }
    [data-bs-theme="dark"] .stat-card {
        background: rgba(255, 255, 255, 0.04) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .stat-card:hover {
        background: rgba(45, 212, 191, 0.1) !important;
        border-color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .total-prestasi-banner {
        background: linear-gradient(135deg, rgba(3, 24, 20, 0.98) 0%, rgba(7, 48, 44, 0.96) 50%, rgba(3, 24, 20, 0.98) 100%) !important;
        border-color: rgba(251, 191, 36, 0.45) !important;
    }

    /* 10. Program Unggulan */
    [data-bs-theme="dark"] .programs-section {
        background: #0B1317 !important;
    }
    [data-bs-theme="dark"] .program-card-modern {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.3) !important;
    }
    [data-bs-theme="dark"] .program-card-modern:hover {
        border-color: #2DD4BF !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45) !important;
    }
    [data-bs-theme="dark"] .program-card-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .program-card-desc {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .program-card-features li {
        color: #CBD5E1 !important;
    }
    [data-bs-theme="dark"] .program-card-features li i {
        color: #2DD4BF !important;
    }

    /* 11. Dunia Ilmu Madrasah */
    [data-bs-theme="dark"] .dunia-ilmu-section {
        background: #0D171C !important;
    }
    [data-bs-theme="dark"] .dunia-filter-bar {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25) !important;
    }
    [data-bs-theme="dark"] .dunia-filter-bar .filter-title {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .dunia-filter-bar select {
        background: #1A2F37 !important;
        color: #F1F5F9 !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    [data-bs-theme="dark"] .dunia-filter-bar .btn-reset-filter {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .dunia-filter-bar .btn-reset-filter:hover {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .dunia-media-card {
        background: #172A32 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .dunia-media-card:hover {
        border-color: #2DD4BF !important;
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4) !important;
    }
    [data-bs-theme="dark"] .dunia-link-action {
        border-top-color: rgba(255, 255, 255, 0.08) !important;
        color: #2DD4BF !important;
    }

    /* 12. Kabar Madrasah */
    [data-bs-theme="dark"] #berita.section-bg-alt {
        background: #0B1317 !important;
    }
    [data-bs-theme="dark"] .news-card {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.3) !important;
    }
    [data-bs-theme="dark"] .news-card:hover {
        border-color: #2DD4BF !important;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.45) !important;
    }
    [data-bs-theme="dark"] .news-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .news-date {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .news-excerpt {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .news-read-more {
        color: #2DD4BF !important;
    }

    /* 13. Mutiara Hikmah */
    [data-bs-theme="dark"] .mutiara-section {
        background: #0D171C !important;
    }
    [data-bs-theme="dark"] .mutiara-card {
        background: linear-gradient(135deg, #05261F 0%, #0C3D33 100%) !important;
        border-color: rgba(251, 191, 36, 0.35) !important;
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.45) !important;
    }
    [data-bs-theme="dark"] .mutiara-quote-text {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .mutiara-author {
        color: #FDE047 !important;
    }

    /* 14. Mobile Drawer */
    [data-bs-theme="dark"] .mobile-drawer {
        background: #0F172A !important;
        box-shadow: -10px 0 40px rgba(0, 0, 0, 0.6) !important;
    }
    [data-bs-theme="dark"] .mobile-drawer-header {
        border-bottom-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .mobile-drawer-header span {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] #mobile-drawer-close {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .mobile-theme-row {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .mobile-theme-title {
        color: #E2E8F0 !important;
    }
    [data-bs-theme="dark"] .mobile-theme-btn {
        background: #1E293B !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    [data-bs-theme="dark"] .mobile-nav-link {
        background: #172A32 !important;
        color: #F8FAFC !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
    }
    [data-bs-theme="dark"] .mobile-nav-link:hover,
    [data-bs-theme="dark"] .mobile-nav-link:active,
    [data-bs-theme="dark"] .mobile-nav-link.open {
        background: rgba(45, 212, 191, 0.15) !important;
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .mobile-sub-menu {
        background: #111E24 !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
    }
    [data-bs-theme="dark"] .mobile-sub-link {
        color: #CBD5E1 !important;
        border-bottom-color: rgba(255, 255, 255, 0.05) !important;
    }
    [data-bs-theme="dark"] .mobile-sub-link:hover,
    [data-bs-theme="dark"] .mobile-sub-link:active,
    [data-bs-theme="dark"] .mobile-sub-link.active-toggle {
        color: #2DD4BF !important;
        background: rgba(45, 212, 191, 0.1) !important;
    }
    [data-bs-theme="dark"] .mobile-nested-menu {
        background: #0A1317 !important;
        border-left-color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .mobile-nested-link {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .mobile-nested-link:hover {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .mobile-drawer-body a[style*="background: #f1f5f9"] {
        background: #1E293B !important;
        color: #F8FAFC !important;
    }

    /* 15. Mobile Bottom Nav */
    [data-bs-theme="dark"] .mobile-bottom-nav {
        background: rgba(15, 23, 42, 0.95) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.5) !important;
    }
    [data-bs-theme="dark"] .mobile-bottom-nav__item {
        color: #94A3B8 !important;
    }
    [data-bs-theme="dark"] .mobile-bottom-nav__item.active {
        color: #2DD4BF !important;
    }
    [data-bs-theme="dark"] .mobile-bottom-nav__item.active i {
        background: rgba(45, 212, 191, 0.18) !important;
        color: #2DD4BF !important;
    }

    /* 16. Footer */
    [data-bs-theme="dark"] .site-footer {
        background: #060C0F !important;
        border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .footer-bottom {
        border-top-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* 17. Search Modal & Catalog Modals */
    [data-bs-theme="dark"] .search-modal-content {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .search-input {
        background: #1A2F37 !important;
        color: #F8FAFC !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    [data-bs-theme="dark"] .dunia-modal-box {
        background: #132228 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    [data-bs-theme="dark"] .dunia-modal-header {
        border-bottom-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-bs-theme="dark"] .dunia-modal-title {
        color: #F8FAFC !important;
    }
    [data-bs-theme="dark"] .dunia-modal-filter-select {
        background: #1A2F37 !important;
        color: #F1F5F9 !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
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
    <!-- 1. HERO SLIDER SECTION (MENDUKUNG MODE TEKS TENGAH BESAR & MODE FOTO)     -->
    <!-- ========================================================================= -->
    @php
        \App\Models\BannerCarousel::pastikanTabelSiap();
        $heroSlides = \App\Models\BannerCarousel::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    @endphp

    

    <section class="hero-slider-section" id="beranda">
        <div class="hero-animated-grid-wrap">
            <div class="hero-animated-grid-bg"></div>
        </div>

        <div class="hero-slider-track">
            @foreach ($heroSlides as $idx => $slide)
                @php
                    $isModeTengah = ($slide->tipe_layout === 'tanpa_foto');
                @endphp

                <div class="hero-slide {{ $idx === 0 ? 'active' : '' }}" id="hero-slide-{{ $idx + 1 }}">
                    @if ($isModeTengah)
                        <!-- MODE 1: SLIDE TUNGGAL TANPA FOTO (TEKS DI TENGAH & DIPERBESAR) -->
                        <div class="hero-slide-centered-single">
                            <span class="hero-script-heading">{{ $slide->aksara_kecil ?: 'Selamat datang di' }}</span>
                            <h1 class="hero-bold-title">{{ $slide->aksara_besar ?: $slide->judul }}</h1>

                            @if (!empty($slide->teks_domain))
                                <div class="hero-domain-line">{{ $slide->teks_domain }}</div>
                            @endif

                            <div class="hero-slogan-lines">
                                <span>{{ $slide->keterangan_1 ?: $slide->keterangan }}</span>
                                @if (!empty($slide->keterangan_2))
                                    <span>{{ $slide->keterangan_2 }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- MODE 2: SLIDE TEKS DI KIRI + FOTO KOLASE DI KANAN (TANPA BADGE HIJAU) -->
                        <div class="hero-slide-flex-container">
                            <div class="hero-slide-left-text">
                                <span class="hero-script-heading">{{ $slide->aksara_kecil ?: 'Program Unggulan' }}</span>
                                <h2 class="hero-bold-title">{{ $slide->aksara_besar ?: $slide->judul }}</h2>

                                @if (!empty($slide->teks_domain))
                                    <div class="hero-domain-line">{{ $slide->teks_domain }}</div>
                                @endif

                                <div class="hero-slogan-strip">
                                    <div class="hero-slogan-lines hero-slogan-clean">
                                        <span>{{ $slide->keterangan_1 ?: $slide->keterangan }}</span>
                                        @if (!empty($slide->keterangan_2))
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

        <button class="slider-arrow prev" aria-label="Slide Sebelumnya"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-arrow next" aria-label="Slide Berikutnya"><i class="fas fa-chevron-right"></i></button>

        <div class="slider-dots-container">
            @foreach ($heroSlides as $idx => $slide)
                <span class="slider-dot {{ $idx === 0 ? 'active' : '' }}"></span>
            @endforeach
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
    <!-- 3. BANNER IKLAN PPDB (LANDSCAPE TETAP) & LAYANAN CEPAT (DINAMIS DARI DB)  -->
    <!-- ========================================================================= -->
    @php
        $bannerPpdb = $bannerPpdb ?? \App\Models\BannerPpdb::ambilData();
        $layananCepats = $layananCepats ?? \App\Models\LayananCepat::where('is_active', true)->orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();
    @endphp

    @if ($bannerPpdb->is_active || $layananCepats->isNotEmpty())
    <section class="hero-bottom-features-section" id="ppdb">
        <div class="container">

            @if ($bannerPpdb->is_active)
                <!-- 1. BANNER IKLAN PPDB (FULL LANDSCAPE GAMBAR — SAMA DI LAPTOP & HP) -->
                @if ($bannerPpdb->tipe_link === 'popup')
                    <div class="ppdb-landscape-banner-wrap" onclick="openPPDBModal()"
                        title="{{ $bannerPpdb->judul }}" style="cursor: pointer;">
                        <img src="{{ $bannerPpdb->gambar_url }}" alt="{{ $bannerPpdb->judul }}"
                            class="ppdb-landscape-banner-img" />
                    </div>
                @else
                    <a href="{{ $bannerPpdb->link_url }}" target="_blank" rel="noopener noreferrer"
                        class="ppdb-landscape-banner-wrap d-block text-decoration-none"
                        title="{{ $bannerPpdb->judul }}">
                        <img src="{{ $bannerPpdb->gambar_url }}" alt="{{ $bannerPpdb->judul }}"
                            class="ppdb-landscape-banner-img" />
                    </a>
                @endif
            @endif

            @if ($layananCepats->isNotEmpty())
                <!-- 2. LAYANAN CEPAT (DINAMIS DARI DATABASE: 6 AKSES UTAMA + SISANYA EXPANDABLE) -->
                <div class="layanan-cepat-card" id="layanan-cepat">
                    <div class="layanan-cepat-bg-wave" aria-hidden="true"></div>
                    <div class="layanan-cepat-header">
                        <h2 class="layanan-cepat-title">Layanan Cepat</h2>
                        <p class="layanan-cepat-subtitle">Akses halaman penting website madrasah dengan lebih cepat.</p>
                    </div>

                    <div class="layanan-cepat-grid-custom" id="gridLayananCepat">
                        @foreach ($layananCepats as $idx => $layanan)
                            @php
                                $isExtra = $idx >= 6;
                            @endphp
                            <a href="{{ $layanan->link_url }}" target="{{ $layanan->target }}"
                               @if ($layanan->target === '_blank') rel="noopener noreferrer" @endif
                               class="layanan-cepat-item {{ $isExtra ? 'lc-extra-item' : '' }}"
                               title="{{ $layanan->nama_layanan }}">
                                <div class="lc-icon"><i class="{{ $layanan->icon }}"></i></div>
                                <span class="lc-label">{{ $layanan->nama_layanan }}</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- Tombol Menuju Halaman Lengkap Layanan Cepat -->
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
            <div class="section-header" style="text-align: center;">
                <span class="section-subtitle white" style="display: block; text-align: center; color: #4ebaa6;">Capaian Gemilang</span>
                <h2 class="section-title white" style="color: #ffffff !important; background: transparent !important; background-color: transparent !important; border: none !important; text-align: center !important;">Prestasi Siswa &amp; Data Madrasah</h2>
                <p class="section-desc" style="color: #cbd5e1; text-align: center; margin: 0 auto;">Rekap Capaian Kejuaraan &amp; Statistik Akademik MIN 1
                    Ciamis</p>
                <div class="section-divider" style="margin: 14px auto 0 auto;"></div>
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
            <div class="section-header" style="text-align: center;">
                <span class="section-subtitle" style="display: block; text-align: center;">Pendidikan Berkualitas</span>
                <h2 class="section-title" style="text-align: center;">Program Unggulan MIN 1 Ciamis</h2>
                <p class="section-desc" style="text-align: center; margin: 0 auto;">Mengembangkan potensi intelektual, spiritual, dan emosional santri secara berimbang
                </p>
                <div class="section-divider" style="margin: 14px auto 0 auto;"></div>
            </div>

            <div class="programs-grid">
                @php
                    $listPrograms = isset($programUnggulans) && $programUnggulans->isNotEmpty()
                        ? $programUnggulans->take(3)
                        : \App\Models\ProgramUnggulan::where('is_active', true)->orderBy('urutan', 'asc')->take(3)->get();
                @endphp

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
                                <p class="program-card-desc">{{ $prog->ringkasan }}</p>
                                @php
                                    $poinHome = $prog->poin_list;
                                @endphp
                                @if (!empty($poinHome))
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
                                    <i class="far fa-clock"></i> {{ $prog->waktu_baca }}
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

            <!-- Tombol Lihat Selengkapnya Menuju Halaman Direktori Program Unggulan -->
            <div style="text-align: center; margin-top: 42px;">
                <a href="{{ route('program-unggulan.publik') }}" class="guru-view-all-btn" style="display: inline-flex; text-decoration: none; padding: 12px 28px; font-size: 0.92rem;">
                    <span>Lihat Seluruh Program Unggulan</span>
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
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

                <!-- Bar Filter Langsung: Pilih Kelas & Pilih Mata Pelajaran -->
                <div class="dunia-filter-bar">
                    <div class="filter-title"
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

                    <button type="button" class="btn-reset-filter" onclick="resetFilterDuniaHome()"
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
            <div class="section-header" style="margin-bottom: 35px; text-align: center;">
                <span class="section-subtitle" style="display: block; text-align: center;">Warta &amp; Informasi Terkini</span>
                <h2 class="section-title" style="text-align: center;">Kabar Madrasah</h2>
                <p class="section-desc" style="text-align: center; margin: 0 auto;">Berita, pengumuman, dan liputan kegiatan terbaru seputar MIN 1 Ciamis</p>
                <div class="section-divider" style="margin: 14px auto 0 auto;"></div>
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

            <!-- Tombol Lihat Selengkapnya Menuju Halaman Kabar Madrasah -->
            <div style="text-align: center; margin-top: 42px;">
                <a href="{{ route('berita.publik') }}" class="guru-view-all-btn" style="display: inline-flex; text-decoration: none; padding: 12px 30px; font-size: 0.95rem; font-weight: 700;">
                    <span>Lihat Seluruh Kabar Madrasah</span>
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
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
