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
    <a href="{{ route('home') }}#dunia-ilmu" class="mobile-bottom-nav__item" data-section="dunia-ilmu">
        <i class="fas fa-play-circle"></i>
        <span>Media</span>
    </a>
    <a href="{{ route('home') }}#layanan" class="mobile-bottom-nav__item" data-section="layanan">
        <i class="fas fa-concierge-bell"></i>
        <span>Layanan</span>
    </a>
</nav>
