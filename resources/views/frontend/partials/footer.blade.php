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
            <h3 style="margin-bottom: 16px; font-size: 1.3rem; font-family: 'Outfit', sans-serif;">
                Pencarian Website MIN 1 Ciamis
            </h3>
            <div class="search-input-box" style="display: flex; gap: 10px; margin-bottom: 18px">
                <input type="text" id="search-input-field"
                    placeholder="Ketik kata kunci (misal: PPDB, tahfidz, prestasi, panjalu)..."
                    style="flex: 1; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 1rem;" />
            </div>
            <div id="search-results-list" style="max-height: 360px; overflow-y: auto">
                <p style="color: #64748b; font-size: 0.9rem">
                    Mulai mengetik untuk menemukan warta, informasi program, atau profil madrasah...
                </p>
            </div>
        </div>
    </div>
</div>

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
    <div class="mobile-drawer-body" style="padding: 16px 20px">
        @foreach (\App\Models\NavMenu::getTree() as $mInduk)
            <a href="{{ $mInduk->url }}" class="mobile-nav-link" onclick="closeMobileDrawer()">
                <span>{{ $mInduk->nama_menu }}</span>
                <i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i>
            </a>
        @endforeach
        <div style="margin-top: 20px">
            <a href="{{ route('presensi.scanner') }}"
                style="display: block; text-align: center; background: #1b9c85; color: #fff; padding: 12px; border-radius: 9999px; font-weight: 800; text-decoration: none;">
                <i class="fas fa-qrcode"></i> Layar Presensi QR
            </a>
        </div>
    </div>
</div>

<!-- INTERACTIVE CONFETTI CANVAS & CELEBRATORY TOAST -->
<canvas id="confetti-canvas" width="1280" height="720"></canvas>
<div class="interactive-toast" id="interactive-toast">
    <i class="fas fa-trophy" style="color: #10b981; font-size: 1.1rem"></i>
    <span id="toast-message">🎉 Masya Allah! Ratusan Prestasi Juara telah diraih oleh siswa MIN 1 Ciamis!</span>
</div>

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
        var drawer = document.getElementById("mobile-nav-drawer");
        var overlay = document.getElementById("mobile-drawer-overlay");
        if (drawer) drawer.classList.add("active");
        if (overlay) overlay.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    function closeMobileDrawer() {
        var drawer = document.getElementById("mobile-nav-drawer");
        var overlay = document.getElementById("mobile-drawer-overlay");
        if (drawer) drawer.classList.remove("active");
        if (overlay) overlay.classList.remove("active");
        document.body.style.overflow = "";
    }

    function openPPDBModal() {
        var modal = document.getElementById("general-modal");
        var title = document.getElementById("modal-title");
        var content = document.getElementById("modal-content-area");
        if (modal && title && content) {
            title.innerHTML =
                '<i class="fas fa-user-graduate" style="color:#1B9C85;margin-right:8px;"></i> Informasi & Pendaftaran PPDB MIN 1 Ciamis';
            content.innerHTML =
                '<p style="line-height:1.7;">Penerimaan Peserta Didik Baru (PPDB) MIN 1 Ciamis Tahun Ajaran 2026/2027 membuka Jalur Prestasi, Tahfidz, dan Reguler. Silakan hubungi panitia PPDB melalui WhatsApp resmi atau datang langsung ke kampus MIN 1 Ciamis di Jl. Maparah No. 65, Kec. Panjalu.</p>';
            modal.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    }
</script>
