@php
    $user = auth()->user();

    // Cek hak akses per grup utama untuk menampilkan/menyembunyikan Ikon Rail di sebelah kiri
    $bolehLihatGtk      = $user && ($user->canAccess('kepegawaian') || $user->hasRole('superadmin'));
    $bolehLihatAkademik = $user && (
        $user->canAccess('tahun_ajaran') ||
        $user->canAccess('kelas') ||
        $user->canAccess('siswa') ||
        $user->canAccess('mapel') ||
        $user->canAccess('jadwal') ||
        $user->canAccess('ekskul') ||
        $user->canAccess('kenaikan_reset')
    );
    $bolehLihatPresensi = $user && (
        $user->canAccess('presensi_scanner') ||
        $user->canAccess('presensi_monitoring') ||
        $user->canAccess('presensi_rekap')
    );
    $bolehLihatCms      = $user && $user->canAccess('cms');
    $bolehLihatSetting  = $user && $user->canAccess('pengaturan');
@endphp

<style>
    /* Styling khusus untuk Judul Kategori di atas Sub-Menu */
    .twin-menu-header {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #16a34a; /* Hijau MIN 1 Ciamis */
        padding: 10px 14px 8px 14px;
        margin-bottom: 6px;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .role-badge-sidebar {
        font-size: 10px;
        font-weight: 700;
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 6px;
        padding: 3px 8px;
        margin: 0 14px 10px 14px;
        display: inline-block;
    }
</style>

<!-- Mobile open button -->
<button type="button" class="twin-mobile-toggle" aria-label="Open menu"><i class="ph ph-list"></i></button>
<div class="twin-backdrop"></div>

<!-- ===================== Twin Sidebar ===================== -->
<aside class="twin-sidebar" aria-label="Primary navigation">

    <!-- Icon rail (Barisan Ikon Sebelah Kiri) -->
    <div class="twin-rail">
        <a href="{{ route('dashboard') }}" class="twin-rail__brand" aria-label="Beranda">
            <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="MIN 1 Ciamis">
        </a>

        <div class="twin-rail__nav">
            <!-- 1. Ikon Beranda (Semua Role Bisa Akses) -->
            <button type="button" class="rail-icon active" data-menu="dashboards" aria-label="Beranda">
                <i class="ph ph-house-simple"></i>
                <span class="rail-tip">Beranda</span>
            </button>

            <!-- 2. Ikon Manajemen GTK -->
            @if ($bolehLihatGtk)
                <button type="button" class="rail-icon" data-menu="gtk" aria-label="Manajemen GTK">
                    <i class="ph ph-users-three"></i>
                    <span class="rail-tip">Manajemen GTK</span>
                </button>
            @endif

            <!-- 3. Ikon Akademik & Kelas -->
            @if ($bolehLihatAkademik)
                <button type="button" class="rail-icon" data-menu="akademik" aria-label="Akademik & Kelas">
                    <i class="ph ph-graduation-cap"></i>
                    <span class="rail-tip">Akademik & Kelas</span>
                </button>
            @endif

            <!-- 4. Ikon Absensi / Presensi -->
            @if ($bolehLihatPresensi)
                <button type="button" class="rail-icon" data-menu="absensi" aria-label="Presensi">
                    <i class="ph ph-calendar-check"></i>
                    <span class="rail-tip">Presensi</span>
                </button>
            @endif

            <!-- 5. Ikon CMS / Konten Website Publik -->
            @if ($bolehLihatCms)
                <button type="button" class="rail-icon" data-menu="cms" aria-label="Konten Website">
                    <i class="ph ph-globe"></i>
                    <span class="rail-tip">Konten Website</span>
                </button>
            @endif
        </div>

        <div class="twin-rail__bottom">
            <!-- 6. Ikon Pengaturan -->
            @if ($bolehLihatSetting)
                <button type="button" class="rail-icon" data-menu="settings" aria-label="Pengaturan">
                    <i class="ph ph-gear-six"></i>
                    <span class="rail-tip">Pengaturan</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Menu panel (Daftar Sub-Menu Sebelah Kanan) -->
    <div class="twin-panel">
        <div class="twin-panel__head">
            <a href="{{ route('dashboard') }}" class="twin-panel__logo" aria-label="MIN 1 CIAMIS">MIN 1 CIAMIS</a>
        </div>

        <div class="twin-panel__body">
            @if ($user)
                <div class="role-badge-sidebar">
                    <i class="ph-fill ph-shield-check"></i> {{ $user->label_role }}
                </div>
            @endif

            <!-- 1. MENU BERANDA -->
            <ul class="twin-menu active" data-menu="dashboards">
                <li class="twin-menu-header">
                    <i class="ph-fill ph-house-simple"></i> BERANDA
                </li>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="ph ph-squares-four"></i><span>Dashboard Utama</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/') }}" target="_blank">
                        <i class="ph ph-arrow-square-out"></i><span>Lihat Web Publik</span>
                    </a>
                </li>
            </ul>

            <!-- 2. MENU MANAJEMEN GTK -->
            @if ($bolehLihatGtk)
                <ul class="twin-menu" data-menu="gtk">
                    <li class="twin-menu-header">
                        <i class="ph-fill ph-users-three"></i> MANAJEMEN GTK
                    </li>
                    @if ($user->canAccess('kepegawaian'))
                        <li>
                            <a href="{{ route('jabatan.index') }}">
                                <i class="ph ph-briefcase"></i><span>Master Jabatan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('posisi.index') }}">
                                <i class="ph ph-git-branch"></i><span>Master Posisi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/gtk') }}">
                                <i class="ph ph-list-bullets"></i><span>Data Guru & Tendik</span>
                            </a>
                        </li>
                    @endif

                    {{-- Menu Role & Hak Akses Khusus Superadmin --}}
                    @if ($user->hasRole('superadmin'))
                        <li>
                            <a href="{{ route('users.index') }}">
                                <i class="ph ph-shield-check"></i><span>Role & Hak Akses</span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif

            <!-- 3. MENU AKADEMIK & KELAS -->
            @if ($bolehLihatAkademik)
                <ul class="twin-menu" data-menu="akademik">
                    <li class="twin-menu-header">
                        <i class="ph-fill ph-graduation-cap"></i> AKADEMIK & KELAS
                    </li>
                    @if ($user->canAccess('tahun_ajaran'))
                        <li>
                            <a href="{{ route('tahun-ajaran.index') }}">
                                <i class="ph ph-calendar-check"></i><span>Tahun Ajaran</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('kelas'))
                        <li>
                            <a href="{{ route('kelas.index') }}">
                                <i class="ph ph-chalkboard-teacher"></i><span>Manajemen Kelas</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('siswa'))
                        <li>
                            <a href="{{ route('siswa.index') }}">
                                <i class="ph ph-student"></i><span>Data Siswa</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('mapel'))
                        <li>
                            <a href="{{ route('mapel.index') }}">
                                <i class="ph ph-books"></i><span>Mata Pelajaran</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('jadwal'))
                        <li>
                            <a href="#">
                                <i class="ph ph-calendar"></i><span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('ekskul'))
                        <li>
                            <a href="{{ route('ekskul.index') }}">
                                <i class="ph ph-trophy"></i><span>Ekstrakurikuler</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('kenaikan_reset'))
                        <li>
                            <a href="{{ route('kenaikan.index') }}">
                                <i class="ph ph-arrows-down-up"></i><span>Kenaikan & Reset Sistem</span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif

            <!-- 4. MENU PRESENSI / ABSENSI -->
            @if ($bolehLihatPresensi)
                <ul class="twin-menu" data-menu="absensi">
                    <li class="twin-menu-header">
                        <i class="ph-fill ph-calendar-check"></i> PRESENSI HARIAN
                    </li>
                    @if ($user->canAccess('presensi_scanner'))
                        <li>
                            <a href="{{ route('presensi.scanner') }}" target="_blank">
                                <i class="ph ph-qr-code"></i><span>Layar Scan Barcode</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('presensi_monitoring'))
                        <li>
                            <a href="{{ route('presensi.index') }}">
                                <i class="ph ph-check-square-offset"></i><span>Presensi Kelas Harian</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->canAccess('presensi_rekap'))
                        <li>
                            <a href="{{ route('presensi.rekap') }}">
                                <i class="ph ph-file-csv"></i><span>Rekap & Laporan</span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif

            <!-- 5. MENU KONTEN WEBSITE (CMS) -->
            @if ($bolehLihatCms)
                <ul class="twin-menu" data-menu="cms">
                    <li class="twin-menu-header">
                        <i class="ph-fill ph-globe"></i> KONTEN WEBSITE
                    </li>
                    <li>
                        <a href="#"><i class="ph ph-tree-structure"></i><span>Menu & Sub-Menu</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="ph ph-newspaper"></i><span>Berita & Artikel</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="ph ph-megaphone"></i><span>Pengumuman</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="ph ph-images"></i><span>Galeri Kegiatan</span></a>
                    </li>
                </ul>
            @endif

            <!-- 6. MENU PENGATURAN -->
            @if ($bolehLihatSetting)
                <ul class="twin-menu" data-menu="settings">
                    <li class="twin-menu-header">
                        <i class="ph-fill ph-gear-six"></i> PENGATURAN
                    </li>
                    <li>
                        <a href="#"><i class="ph ph-buildings"></i><span>Identitas Madrasah</span></a>
                    </li>
                </ul>
            @endif

        </div>
    </div>
</aside>

<script>
    (function () {
        function initTwinSidebar() {
            var sidebar = document.querySelector(".twin-sidebar");
            if (!sidebar) return;

            var icons = sidebar.querySelectorAll(".rail-icon[data-menu]");
            var menus = sidebar.querySelectorAll(".twin-menu");
            var collapseBtn = document.querySelector(".twin-collapse");
            var mobileToggle = document.querySelector(".twin-mobile-toggle");
            var backdrop = document.querySelector(".twin-backdrop");

            // Pindah menu saat ikon di kiri diklik
            icons.forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var key = btn.getAttribute("data-menu");

                    icons.forEach(function (i) { i.classList.remove("active"); });
                    menus.forEach(function (m) { m.classList.remove("active"); });

                    btn.classList.add("active");
                    var menu = sidebar.querySelector('.twin-menu[data-menu="' + key + '"]');
                    if (menu) menu.classList.add("active");

                    sidebar.classList.remove("is-collapsed");
                    document.body.classList.remove("twin-collapsed");
                });
            });

            // Collapse / expand panel menu
            if (collapseBtn) {
                collapseBtn.addEventListener("click", function () {
                    var collapsed = sidebar.classList.toggle("is-collapsed");
                    document.body.classList.toggle("twin-collapsed", collapsed);
                });
            }

            // Mobile off-canvas
            function openMobile() { sidebar.classList.add("is-open"); backdrop.classList.add("show"); }
            function closeMobile() { sidebar.classList.remove("is-open"); backdrop.classList.remove("show"); }
            if (mobileToggle) mobileToggle.addEventListener("click", openMobile);
            if (backdrop) backdrop.addEventListener("click", closeMobile);

            // --- Auto-activate menu sesuai URL Laravel saat ini ---
            var currentPath = window.location.pathname.toLowerCase();
            var activeLink = null;

            menus.forEach(function (menu) {
                menu.querySelectorAll("a[href]").forEach(function (a) {
                    a.parentElement.classList.remove("active");
                    var rawHref = a.getAttribute("href");
                    if (!rawHref || rawHref === "#" || a.getAttribute("target") === "_blank") return;

                    try {
                        var linkPath = new URL(rawHref, window.location.origin).pathname.toLowerCase();
                        if (linkPath === currentPath) {
                            activeLink = a;
                        } else if (linkPath !== "/" && linkPath !== "/dashboard" && currentPath.startsWith(linkPath + "/")) {
                            activeLink = a;
                        }
                    } catch (e) {}
                });
            });

            if (activeLink) {
                icons.forEach(function (i) { i.classList.remove("active"); });
                menus.forEach(function (m) { m.classList.remove("active"); });

                activeLink.parentElement.classList.add("active");

                var parentMenu = activeLink.closest(".twin-menu");
                if (parentMenu) {
                    parentMenu.classList.add("active");
                    var activeIcon = sidebar.querySelector(
                        '.rail-icon[data-menu="' + parentMenu.getAttribute("data-menu") + '"]'
                    );
                    if (activeIcon) activeIcon.classList.add("active");
                }
            }
        }

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", initTwinSidebar);
        } else {
            initTwinSidebar();
        }
    })();
</script>