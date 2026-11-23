<!-- FOOTER RESMI MIN 1 CIAMIS -->
<footer class="site-footer" id="kontak">
    <div class="container">
        <div class="footer-grid">
            <!-- Col 1: Brand & Profil Singkat -->
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <img src="{{ asset('frontend/landing/MIN 1.png') }}" alt="Logo MIN 1 CIAMIS"
                        style="height: 52px; width: auto; object-fit: contain"
                        onerror="this.onerror=null; this.src='{{ asset('backend/assets/images/icon_min.png') }}';" />
                    <div>
                        <h3
                            style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin: 0; font-family: 'Outfit', sans-serif;">
                            MIN 1 CIAMIS
                        </h3>
                        <p
                            style="font-size: 0.8rem; color: #1b9c85; margin: 0; font-weight: 700; text-transform: uppercase;">
                            Kemenag Kab. Ciamis
                        </p>
                    </div>
                </div>
                <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6; margin-bottom: 20px;">
                    Madrasah Ibtidaiyah Negeri 1 Ciamis adalah madrasah unggulan berakhlak mulia, berprestasi, ramah
                    anak, dan peduli lingkungan di Panjalu Ciamis.
                </p>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Alamat Madrasah:</strong><br />
                            Jl. Maparah No. 65, Desa Maparah, Kec. Panjalu, Kab. Ciamis, Jawa Barat 46264
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Col 2: Jam Pelayanan & Link Cepat -->
            <div>
                <h3 class="footer-widget-title">Jam Operasional</h3>
                <table class="op-hours-table" style="margin-bottom: 22px">
                    <tbody>
                        <tr>
                            <td class="day">Senin – Kamis</td>
                            <td>07.00 – 14.30 WIB</td>
                        </tr>
                        <tr>
                            <td class="day">Jumat</td>
                            <td>07.00 – 11.30 WIB</td>
                        </tr>
                        <tr>
                            <td class="day">Sabtu</td>
                            <td>07.00 – 13.00 WIB</td>
                        </tr>
                        <tr>
                            <td class="day">Minggu / Libur</td>
                            <td><span style="color: #ef4444; font-weight: 600">Libur Nasional</span></td>
                        </tr>
                    </tbody>
                </table>

                <h3 class="footer-widget-title" style="margin-top: 14px">Tautan Cepat</h3>
                <div style="display: flex; flex-direction: column; gap: 6px; font-size: 0.88rem;">
                    <a href="{{ route('berita.publik') }}" style="color: #cbd5e1; text-decoration: none">
                        <i class="fas fa-chevron-right"
                            style="color: #1b9c85; font-size: 0.75rem; margin-right: 6px"></i> Kabar &amp; Berita Madrasah
                    </a>
                    <a href="{{ route('home') }}#profil" style="color: #cbd5e1; text-decoration: none">
                        <i class="fas fa-chevron-right"
                            style="color: #1b9c85; font-size: 0.75rem; margin-right: 6px"></i> Profil Madrasah
                    </a>
                    <a href="{{ route('home') }}#program" style="color: #cbd5e1; text-decoration: none">
                        <i class="fas fa-chevron-right"
                            style="color: #1b9c85; font-size: 0.75rem; margin-right: 6px"></i> Program Tahfidz &amp;
                        Sains
                    </a>
                    <a href="{{ route('home') }}#ppdb" style="color: #cbd5e1; text-decoration: none">
                        <i class="fas fa-chevron-right"
                            style="color: #1b9c85; font-size: 0.75rem; margin-right: 6px"></i> Pendaftaran PPDB
                        2026/2027
                    </a>
                    <a href="{{ route('presensi.scanner') }}" style="color: #cbd5e1; text-decoration: none">
                        <i class="fas fa-chevron-right"
                            style="color: #1b9c85; font-size: 0.75rem; margin-right: 6px"></i> Scanner Presensi QR
                    </a>
                </div>
            </div>

            <!-- Col 3: Hubungi Kami & Media Sosial -->
            <div>
                <h3 class="footer-widget-title">Hubungi Kami</h3>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fab fa-whatsapp"></i>
                        <div>
                            <strong>WhatsApp PPDB / Info:</strong><br />
                            <a href="https://wa.me/6282123456789" target="_blank"
                                style="color: #5eead4; text-decoration: none">+62 821-2345-6789</a>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <strong>Telepon Kantor:</strong><br />
                            (0265) 791234
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email Resmi:</strong><br />
                            info@min1ciamis.sch.id
                        </div>
                    </li>
                </ul>

                <div style="margin-top: 16px">
                    <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 10px; font-weight: 600;">
                        Media Sosial Resmi:
                    </div>
                    <div class="footer-social-links">
                        <a href="https://facebook.com" target="_blank" title="Facebook MIN 1 Ciamis"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com" target="_blank" title="Instagram MIN 1 Ciamis"><i
                                class="fab fa-instagram"></i></a>
                        <a href="https://youtube.com" target="_blank" title="YouTube Channel MIN 1 Ciamis"><i
                                class="fab fa-youtube"></i></a>
                        <a href="https://wa.me/6282123456789" target="_blank" title="WhatsApp MIN 1 Ciamis"><i
                                class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-bottom-footer">
        <div class="container"
            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                Copyright &copy; {{ date('Y') }} <strong>MIN 1 CIAMIS</strong> (Madrasah Ibtidaiyah Negeri 1
                Ciamis). Hak Cipta Dilindungi.
            </div>
            <div style="font-size: 0.8rem; color: #64748b">
                Kementerian Agama Republik Indonesia &bull; Kab. Ciamis, Jawa Barat
            </div>
        </div>
    </div>
</footer>

<!-- FLOATING ACTIONS -->
<div class="floating-actions">
    <a href="https://wa.me/6282123456789" target="_blank" class="float-btn whatsapp" title="Chat WhatsApp MIN 1 Ciamis">
        <i class="fab fa-whatsapp"></i>
    </a>
    <button class="float-btn back-to-top visible" id="back-to-top-btn" title="Kembali ke Atas"
        aria-label="Kembali ke Atas">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<!-- GENERAL POPUP MODAL -->
<div class="modal-overlay" id="general-modal">
    <div class="modal-dialog">
        <button class="modal-close-btn" id="modal-close-btn" aria-label="Tutup Dialog">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-body">
            <h2 id="modal-title"
                style="margin-bottom: 20px; font-size: 1.6rem; color: var(--text-dark); line-height: 1.3;">
                Informasi MIN 1 Ciamis
            </h2>
            <div id="modal-content-area"></div>
        </div>
    </div>
</div>

<!-- VIDEO LIGHTBOX MODAL -->
<div class="modal-overlay" id="video-modal">
    <div class="modal-dialog video-lightbox-dialog">
        <button class="modal-close-btn" id="video-close-btn" aria-label="Tutup Video">
            <i class="fas fa-times"></i>
        </button>
        <div class="video-modal-body" style="aspect-ratio: 16/9; width: 100%;">
            <iframe id="youtube-video-frame" src="" style="width: 100%; height: 100%;"
                title="Video Profil MIN 1 Ciamis" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture;"
                allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- SEARCH MODAL -->
<div class="modal-overlay" id="search-modal">
    <div class="modal-dialog search-dialog">
        <button class="modal-close-btn" id="search-close-btn" aria-label="Tutup Pencarian">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-body">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 6px;">
                <h3 style="margin-bottom: 0; font-size: 1.25rem; font-family: 'Outfit', sans-serif;">
                    Pencarian Cerdas MIN 1 Ciamis
                </h3>
                <span style="font-size: 0.72rem; font-weight: 700; color: #0D6A56; background: rgba(13, 106, 86, 0.12); border: 1px solid rgba(13, 106, 86, 0.25); padding: 3px 9px; border-radius: 9999px;">
                    ✨ Semantic AI Vector Search
                </span>
            </div>
            <div class="search-input-box" style="display: flex; gap: 10px; margin-bottom: 18px">
                <input type="text" id="search-input-field"
                    placeholder="Tanyakan apa saja (misal: syarat daftar siswa baru, sholat dhuha, info guru)..."
                    style="flex: 1; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem;" />
            </div>
            <div id="search-results-list" style="max-height: 380px; overflow-y: auto">
                <p style="color: #64748b; font-size: 0.88rem; text-align: center; padding: 20px 10px;">
                    Mulai mengetik pertanyaan atau kata kunci untuk menemukan informasi madrasah secara kontekstual...
                </p>
            </div>
        </div>
    </div>
</div>

<!-- MOBILE NAVIGATION DRAWER STYLES -->
<style>
    .mobile-drawer {
        position: fixed;
        top: 0;
        left: 0;
        width: 320px;
        max-width: 86vw;
        height: 100%;
        background: #FFFFFF;
        z-index: 2100;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        transform: translateX(-100%);
        transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
    }
    .mobile-drawer.active {
        transform: translateX(0);
    }
    .mobile-drawer-overlay {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 2099;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .mobile-drawer-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .mobile-nav-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 13px 18px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        color: #0f172a;
        background: transparent;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        text-align: left;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .mobile-nav-link:hover, .mobile-nav-link:active {
        background: rgba(27, 156, 133, 0.06);
        color: #1b9c85;
    }
    .mobile-nav-link.active-toggle {
        background: rgba(27, 156, 133, 0.08);
        color: #1b9c85;
        border-bottom-color: transparent;
    }
    .mobile-submenu-arrow {
        font-size: 0.75rem;
        color: #94a3b8;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s ease;
    }
    .mobile-nav-link.active-toggle .mobile-submenu-arrow {
        transform: rotate(180deg) !important;
        color: #1b9c85 !important;
    }
    .mobile-sub-menu {
        display: none;
        background: #f8fafc;
        border-radius: 10px;
        margin: 4px 0 8px;
        padding: 6px 4px;
        border: 1px solid rgba(226, 232, 240, 0.9);
    }
    .mobile-sub-menu.open {
        display: block !important;
    }
    .mobile-sub-link {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        padding: 11px 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        color: #334155;
        background: transparent;
        border: none;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        text-decoration: none;
        text-align: left;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .mobile-sub-link.has-nested-sub {
        justify-content: space-between;
    }
    .mobile-sub-link:last-child {
        border-bottom: none;
    }
    .mobile-sub-link:hover, .mobile-sub-link:active {
        color: #1b9c85;
        background: rgba(27, 156, 133, 0.07);
        padding-left: 22px;
    }
    .mobile-sub-link.active-toggle {
        color: #1b9c85;
        background: rgba(27, 156, 133, 0.07);
    }
    .mobile-nested-arrow {
        font-size: 0.7rem;
        color: #94a3b8;
        transition: transform 0.25s ease;
    }
    .mobile-sub-link.active-toggle .mobile-nested-arrow {
        transform: rotate(180deg) !important;
        color: #1b9c85 !important;
    }
    .mobile-nested-menu {
        display: none;
        background: #ffffff;
        padding: 4px 0 4px 8px;
        border-left: 2.5px solid #1b9c85;
        margin: 4px 10px 6px 18px;
        border-radius: 0 8px 8px 0;
    }
    .mobile-nested-menu.open {
        display: block !important;
    }
    .mobile-nested-link {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        font-size: 0.83rem;
        color: #475569;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .mobile-nested-link:hover, .mobile-nested-link:active {
        color: #1b9c85;
        font-weight: 600;
        background: rgba(27, 156, 133, 0.06);
    }
</style>

<!-- MOBILE NAVIGATION DRAWER -->
<div class="mobile-drawer-overlay" id="mobile-drawer-overlay" onclick="closeMobileDrawer()"></div>
<div class="mobile-drawer" id="mobile-nav-drawer">
    <div class="mobile-drawer-header"
        style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 10px">
            <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Logo"
                style="height: 36px; width: auto" />
            <span style="font-weight: 800; font-family: 'Outfit', sans-serif; font-size: 1.05rem; color: #0f172a;">MIN
                1 CIAMIS</span>
        </div>
        <button id="mobile-drawer-close" onclick="closeMobileDrawer()"
            style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="mobile-drawer-body" style="padding: 12px 16px 80px;">
        <!-- Pilihan Mode Siang / Malam -->
        <div class="mobile-theme-row" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; margin-bottom: 14px; background: rgba(27, 156, 133, 0.08); border-radius: 12px; border: 1px solid rgba(27, 156, 133, 0.2);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-circle-half-stroke" style="color: #1b9c85; font-size: 1rem;"></i>
                <span class="mobile-theme-title" style="font-weight: 700; font-size: 0.86rem; font-family: 'Plus Jakarta Sans', sans-serif;">Mode Tampilan</span>
            </div>
            <button type="button" class="dark-light-switcher mobile-theme-btn" aria-label="Ganti Tema" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 6px 14px; border-radius: 50px; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center;">
                <span class="svg-dark" style="display: flex; align-items: center; gap: 6px; color: #b45309;">
                    <i class="fas fa-sun" style="color: #f59e0b;"></i> Mode Siang
                </span>
                <span class="svg-white" style="display: none; align-items: center; gap: 6px; color: #38bdf8;">
                    <i class="fas fa-moon" style="color: #38bdf8;"></i> Mode Malam
                </span>
            </button>
        </div>
        @foreach (\App\Models\NavMenu::getTree() as $mInduk)
            @php
                $punyaAnak = $mInduk->activeChildren->isNotEmpty();
            @endphp

            @if (!$punyaAnak)
                <a href="{{ $mInduk->url }}" target="{{ $mInduk->target }}" class="mobile-nav-link" onclick="closeMobileDrawer()">
                    <span>{{ $mInduk->nama_menu }}</span>
                    <i class="fas fa-arrow-right" style="font-size: 0.75rem; opacity: 0.4;"></i>
                </a>
            @else
                <div class="mobile-nav-group">
                    <button type="button" class="mobile-nav-link has-sub" onclick="toggleMobileSubmenu(this, event)">
                        <span>{{ $mInduk->nama_menu }}</span>
                        <i class="fas fa-chevron-down mobile-submenu-arrow"></i>
                    </button>
                    <div class="mobile-sub-menu">
                        @foreach ($mInduk->activeChildren as $mAnak)
                            @php
                                $punyaCucu = $mAnak->activeChildren->isNotEmpty();
                            @endphp

                            @if (!$punyaCucu)
                                <a href="{{ $mAnak->url }}" target="{{ $mAnak->target }}" class="mobile-sub-link" onclick="closeMobileDrawer()">
                                    <span>{{ $mAnak->nama_menu }}</span>
                                </a>
                            @else
                                <div class="mobile-nested-group">
                                    <button type="button" class="mobile-sub-link has-nested-sub" onclick="toggleMobileNestedSubmenu(this, event)">
                                        <span>{{ $mAnak->nama_menu }}</span>
                                        <i class="fas fa-chevron-down mobile-nested-arrow"></i>
                                    </button>
                                    <div class="mobile-nested-menu">
                                        @foreach ($mAnak->activeChildren as $mCucu)
                                            <a href="{{ $mCucu->url }}" target="{{ $mCucu->target }}" class="mobile-nested-link" onclick="closeMobileDrawer()">
                                                <i class="fas fa-angle-right" style="font-size: 0.65rem; margin-right: 6px; color: #94a3b8;"></i>
                                                <span>{{ $mCucu->nama_menu }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('presensi.scanner') }}"
                style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #1b9c85; color: #fff; padding: 12px 16px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(27, 156, 133, 0.25);">
                <i class="fas fa-qrcode"></i> Layar Presensi QR
            </a>
            @auth
                <a href="{{ route('dashboard') }}"
                    style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #0f172a; color: #fff; padding: 12px 16px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-th-large"></i> Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #f1f5f9; color: #0f172a; padding: 12px 16px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-sign-in-alt"></i> Login Guru / Admin
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- INTERACTIVE CONFETTI CANVAS & CELEBRATORY TOAST -->
<canvas id="confetti-canvas" width="1280" height="720"></canvas>
<div class="interactive-toast" id="interactive-toast">
    <i class="fas fa-trophy" style="color: #10b981; font-size: 1.1rem"></i>
    <span id="toast-message">🎉 Masya Allah! Ratusan Prestasi Juara telah diraih oleh siswa MIN 1 Ciamis!</span>
</div>

@php
    $footerPpdb = $bannerPpdb ?? \App\Models\BannerPpdb::ambilData();
    $footerWaClean = ! empty($footerPpdb->popup_wa_nomor) ? preg_replace('/[^0-9]/', '', $footerPpdb->popup_wa_nomor) : '';
    if (str_starts_with($footerWaClean, '0')) {
        $footerWaClean = '62'.substr($footerWaClean, 1);
    }
    $footerWaLink = ! empty($footerWaClean)
        ? 'https://wa.me/'.$footerWaClean.'?text='.urlencode($footerPpdb->popup_wa_teks ?: 'Halo Panitia PPDB MIN 1 Ciamis')
        : '';
@endphp

<style>
    .ppdb-popup-text {
        line-height: 1.7;
        color: #334155;
        font-size: 0.94rem;
    }
    .ppdb-popup-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }
    [data-bs-theme="dark"] .ppdb-popup-text {
        color: #cbd5e1;
    }
    [data-bs-theme="dark"] .ppdb-popup-actions {
        border-top-color: rgba(255, 255, 255, 0.12);
    }
</style>

{{-- TEMPLATE KONTEN POPUP MODAL PPDB (WAJIB DI LUAR TAG SCRIPT) --}}
<template id="ppdb-modal-content-template">
    @if ($footerPpdb->popup_gambar_url)
        <div style="margin-bottom: 16px; text-align: center;">
            <img src="{{ $footerPpdb->popup_gambar_url }}" alt="Brosur PPDB"
                 style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12);" />
        </div>
    @endif

    <div style="margin-bottom: 14px;">
        @if (! empty($footerPpdb->popup_badge))
            <span style="display:inline-block; background: #E0F2FE; color: #0369A1; font-weight: 800; font-size: 0.75rem; padding: 4px 12px; border-radius: 9999px; letter-spacing: 0.5px; margin-bottom: 10px;">
                {{ $footerPpdb->popup_badge }}
            </span>
        @endif
        <div class="ppdb-popup-text">{!! nl2br(e($footerPpdb->popup_konten)) !!}</div>
    </div>

    <div class="ppdb-popup-actions">
        @if (! empty($footerPpdb->popup_tombol_url) && $footerPpdb->popup_tombol_url !== '#')
            <a href="{{ $footerPpdb->popup_tombol_url }}" target="_blank" rel="noopener noreferrer"
               style="display: inline-flex; align-items: center; gap: 8px; background: #1B9C85; color: #ffffff; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.88rem; text-decoration: none; box-shadow: 0 4px 12px rgba(27,156,133,0.3);">
                <i class="fas fa-edit"></i> {{ $footerPpdb->popup_tombol_teks ?: 'Daftar Online Sekarang' }}
            </a>
        @endif
        @if (! empty($footerWaLink))
            <a href="{{ $footerWaLink }}" target="_blank" rel="noopener noreferrer"
               style="display: inline-flex; align-items: center; gap: 8px; background: #25D366; color: #ffffff; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.88rem; text-decoration: none; box-shadow: 0 4px 12px rgba(37,211,102,0.3);">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp Panitia
            </a>
        @endif
    </div>
</template>

<script>
    function switchSekilasTab(tabKey) {
        document.querySelectorAll(".sekilas-tab-btn").forEach(function(btn) {
            btn.classList.toggle("active", btn.getAttribute("data-tab") === tabKey);
        });
        document.querySelectorAll(".sekilas-tab-pane").forEach(function(pane) {
            pane.style.display = pane.id === "sekilas-pane-" + tabKey ? "block" : "none";
        });
    }

    function openMobileDrawer() {
        var drawer = document.getElementById("mobile-nav-drawer") || document.getElementById("mobile-drawer");
        var overlay = document.getElementById("mobile-drawer-overlay");
        if (drawer) drawer.classList.add("active");
        if (overlay) overlay.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    function closeMobileDrawer() {
        var drawer = document.getElementById("mobile-nav-drawer") || document.getElementById("mobile-drawer");
        var overlay = document.getElementById("mobile-drawer-overlay");
        if (drawer) drawer.classList.remove("active");
        if (overlay) overlay.classList.remove("active");
        document.body.style.overflow = "";
    }

    function toggleMobileSubmenu(btn, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var group = btn.closest(".mobile-nav-group") || btn.parentElement;
        var subMenu = group ? group.querySelector(".mobile-sub-menu") : btn.nextElementSibling;
        if (!subMenu) return;

        var isCurrentlyOpen = subMenu.classList.contains("open") || subMenu.style.display === "block";

        var drawerBody = btn.closest(".mobile-drawer-body");
        if (drawerBody) {
            drawerBody.querySelectorAll(".mobile-sub-menu").forEach(function(menu) {
                if (menu !== subMenu) {
                    menu.classList.remove("open");
                    menu.style.display = "none";
                    var prevBtn = menu.previousElementSibling;
                    if (prevBtn) prevBtn.classList.remove("active-toggle");
                }
            });
        }

        if (isCurrentlyOpen) {
            subMenu.classList.remove("open");
            subMenu.style.display = "none";
            btn.classList.remove("active-toggle");
        } else {
            subMenu.classList.add("open");
            subMenu.style.display = "block";
            btn.classList.add("active-toggle");
        }
    }

    function toggleMobileNestedSubmenu(btn, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var group = btn.closest(".mobile-nested-group") || btn.parentElement;
        var nestedMenu = group ? group.querySelector(".mobile-nested-menu") : btn.nextElementSibling;
        if (!nestedMenu) return;

        var isCurrentlyOpen = nestedMenu.classList.contains("open") || nestedMenu.style.display === "block";

        if (isCurrentlyOpen) {
            nestedMenu.classList.remove("open");
            nestedMenu.style.display = "none";
            btn.classList.remove("active-toggle");
        } else {
            nestedMenu.classList.add("open");
            nestedMenu.style.display = "block";
            btn.classList.add("active-toggle");
        }
    }

    window.openPPDBModal = function() {
        var modal = document.getElementById("general-modal");
        var title = document.getElementById("modal-title");
        var content = document.getElementById("modal-content-area");
        var tpl = document.getElementById("ppdb-modal-content-template");

        if (modal && title && content && tpl) {
            title.textContent = "";
            var icon = document.createElement("i");
            icon.className = "fas fa-user-graduate";
            icon.style.color = "#1B9C85";
            icon.style.marginRight = "8px";
            title.appendChild(icon);
            title.appendChild(document.createTextNode(@json($footerPpdb->popup_judul)));

            content.innerHTML = tpl.innerHTML;
            modal.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    };

    @if ($footerPpdb->is_active_popup && $footerPpdb->auto_popup)
        document.addEventListener("DOMContentLoaded", function() {
            if (!sessionStorage.getItem("min1_auto_popup_ppdb_seen")) {
                setTimeout(function() {
                    if (typeof window.openPPDBModal === "function") {
                        window.openPPDBModal();
                        sessionStorage.setItem("min1_auto_popup_ppdb_seen", "1");
                    }
                }, 1000);
            }
        });
    @endif
</script>
