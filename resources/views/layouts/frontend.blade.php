<!doctype html>
<html lang="id" prefix="og: https://ogp.me/ns#">

<head>
    <!-- Deteksi Mode Tema Lebih Awal (Cegah FOUC Layar Putih) -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Beranda - MIN 1 CIAMIS — Madrasah Ibtidaiyah Negeri 1 Ciamis')</title>
    <meta name="description"
        content="Official Website MIN 1 Ciamis (Madrasah Ibtidaiyah Negeri 1 Ciamis) - Membentuk Generasi Qur'ani, Berakhlak Mulia, Unggul Prestasi, dan Berwawasan Lingkungan di Kabupaten Ciamis.">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/assets/images/icon_min.png') }}">

    <!-- Google Fonts: Caveat, Dancing Script, Outfit, Plus Jakarta Sans, Poppins, Rubik -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Dancing+Script:wght@700&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Rubik:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome 6.5.1 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Magzin Libs & Main CSS (Untuk Carousel Berita, Latest News & Detail Halaman single-3) -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/bootstrap-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/carouselTicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">

    <!-- Landing Page Custom CSS MIN 1 Ciamis -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/landing-min1.css') }}">

    <!-- Jembatan CSS (Penyelaras Magzin & Landing Page + Support Cucu Menu Level 3) -->
    <style>
        /* Dukungan Sub-Dropdown Level 3 (Cucu Menu) pada Floating Pill Navbar */
        .sub-menu .menu-item.has-cucu {
            position: relative;
        }

        .sub-menu .menu-item.has-cucu>.menu-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sub-menu .cucu-menu {
            position: absolute;
            top: 0;
            left: calc(100% + 4px);
            min-width: 230px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-top: 3px solid #F59E0B;
            box-shadow: 0 20px 42px -8px rgba(15, 23, 42, 0.18);
            border-radius: 16px;
            padding: 8px 0;
            list-style: none;
            opacity: 0;
            visibility: hidden;
            transform: translateX(8px);
            transition: all 0.2s ease;
            z-index: 99999;
        }

        .sub-menu .menu-item.has-cucu:hover>.cucu-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        /* Jaga Jarak Halaman Dalam (Detail Berita & Halaman Statis) dari Floating Navbar */
        .sec-1-single-3 {
            padding-top: 24px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar Floating Pill MIN 1 Ciamis (Terhubung Database 3 Level) -->
    @include('frontend.partials.header')

    <!-- Konten Utama -->
    <main>
        @yield('content')
    </main>

    <!-- Footer & Modal Interaktif Landing Page -->
    @include('frontend.partials.footer')

    <!-- Magzin Libs JS -->
    <script src="{{ asset('frontend/assets/js/vendors/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/aos.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/headhesive.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/smart-stick-nav.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/gsap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/ScrollToPlugin.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/Splitetext.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/howler.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/jquery.carouselTicker.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/jquery.odometer.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/jquery.appear.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/color-modes.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/gsap-custom.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/image-hover-effects.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    <!-- Landing Page Interactive Script MIN 1 Ciamis -->
    <script src="{{ asset('frontend/assets/js/landing-min1.js') }}"></script>

    {{-- Mobile Bottom Navigation Bar --}}
    @include('frontend.partials.mobile-bottom-nav')

    @stack('scripts')
</body>

</html>
