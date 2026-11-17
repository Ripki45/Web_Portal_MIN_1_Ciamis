@php
    $navMenus = \App\Models\NavMenu::getTree();
@endphp

<!-- SITE HEADER / FLOATING PILL NAVBAR MIN 1 CIAMIS -->
<header class="site-header scrolled">
    <div class="header-container">
        <!-- Brand Logo & School Badge -->
        <div class="site-branding">
            <a href="{{ route('home') }}" class="site-branding-link" aria-label="MIN 1 CIAMIS">
                <img src="{{ asset('frontend/assets/imgs/logo_w_name.jpg') }}" alt="Logo MIN 1 CIAMIS" class="site-logo" />
            </a>
        </div>

        <!-- Navigation Menu (Dinamis 3 Level dari Database NavMenu) -->
        <nav class="main-navigation" aria-label="Menu Utama">
            <ul class="nav-menu">
                @foreach ($navMenus as $induk)
                    @php
                        $punyaAnak = $induk->activeChildren->isNotEmpty();
                    @endphp

                    @if (!$punyaAnak)
                        <!-- LEVEL 1: MENU TUNGGAL -->
                        <li class="menu-item {{ request()->is(ltrim($induk->url, '/')) ? 'active' : '' }}">
                            <a href="{{ $induk->url }}" target="{{ $induk->target }}" class="menu-link">
                                {{ $induk->nama_menu }}
                            </a>
                        </li>
                    @else
                        <!-- LEVEL 1: INDUK MENU DENGAN DROPDOWN -->
                        <li class="menu-item has-sub">
                            <a href="{{ $induk->url }}" target="{{ $induk->target }}" class="menu-link">
                                {{ $induk->nama_menu }} <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                @foreach ($induk->activeChildren as $anak)
                                    @php
                                        $punyaCucu = $anak->activeChildren->isNotEmpty();
                                    @endphp

                                    @if (!$punyaCucu)
                                        <!-- LEVEL 2: ANAK MENU -->
                                        <li class="menu-item">
                                            <a href="{{ $anak->url }}" target="{{ $anak->target }}"
                                                class="menu-link">
                                                {{ $anak->nama_menu }}
                                            </a>
                                        </li>
                                    @else
                                        <!-- LEVEL 2 + LEVEL 3: ANAK MENU YANG PUNYA CUCU MENU -->
                                        <li class="menu-item has-cucu">
                                            <a href="{{ $anak->url }}" target="{{ $anak->target }}"
                                                class="menu-link">
                                                <span>{{ $anak->nama_menu }}</span>
                                                <i class="fas fa-chevron-right"
                                                    style="font-size: 0.7rem; color: #179545;"></i>
                                            </a>
                                            <ul class="cucu-menu">
                                                @foreach ($anak->activeChildren as $cucu)
                                                    <li class="menu-item">
                                                        <a href="{{ $cucu->url }}" target="{{ $cucu->target }}"
                                                            class="menu-link">
                                                            {{ $cucu->nama_menu }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <!-- Right Socials, Search & Login Portal inside Navbar -->
        <div class="header-right-tools">
            <a href="{{ route('presensi.scanner') }}" class="header-social-item" title="Layar Scan QR Presensi">
                <i class="fas fa-qrcode"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="header-social-item" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://youtube.com" target="_blank" class="header-social-item" title="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
            <button class="header-search-circle-btn search-toggle-btn" id="search-open-btn" aria-label="Cari di situs">
                <i class="fas fa-search"></i>
            </button>

            <!-- Tombol Ganti Mode Siang / Malam -->
            <button type="button" class="dark-light-switcher header-theme-toggle-btn" aria-label="Ganti Mode Gelap / Terang" title="Ganti Mode Gelap / Terang">
                <i class="fas fa-sun svg-dark" style="color: #F59E0B; font-size: 0.95rem;"></i>
                <i class="fas fa-moon svg-white" style="color: #38BDF8; font-size: 0.95rem; display: none;"></i>
            </button>

            @auth
                <a href="{{ route('dashboard') }}" class="header-btn-ppdb" style="margin-left: 4px;">
                    <i class="fas fa-th-large"></i> <span class="btn-dashboard-text">Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="header-btn-ppdb" style="margin-left: 4px;">
                    <i class="fas fa-sign-in-alt"></i> <span class="btn-dashboard-text">Login</span>
                </a>
            @endauth

            <button class="mobile-toggle-btn" id="mobile-menu-btn" aria-label="Buka Menu Navigasi"
                style="margin-left: 4px" onclick="openMobileDrawer()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>
