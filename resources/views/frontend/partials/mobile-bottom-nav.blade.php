{{-- Mobile Bottom Navigation Bar (Hanya Tampil di Layar HP) --}}
<nav class="mobile-bottom-nav" id="mobileBottomNav" aria-label="Navigasi Mobile">
    <a href="{{ route('home') }}" class="mobile-bottom-nav__item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('presensi.scanner') }}" class="mobile-bottom-nav__item {{ request()->routeIs('presensi.scanner') ? 'active' : '' }}">
        <i class="fas fa-qrcode"></i>
        <span>Presensi</span>
    </a>
    @auth
        <a href="{{ route('dashboard') }}" class="mobile-bottom-nav__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="mobile-bottom-nav__item {{ request()->routeIs('login') ? 'active' : '' }}">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login</span>
        </a>
    @endauth
    <a href="{{ route('dunia-ilmu.publik') }}" class="mobile-bottom-nav__item {{ request()->routeIs('dunia-ilmu.publik') ? 'active' : '' }}">
        <i class="fas fa-play-circle"></i>
        <span>Media</span>
    </a>
    <a href="{{ route('layanan-cepat.publik') }}" class="mobile-bottom-nav__item {{ request()->routeIs('layanan-cepat.publik') ? 'active' : '' }}">
        <i class="fas fa-concierge-bell"></i>
        <span>Layanan</span>
    </a>
</nav>
