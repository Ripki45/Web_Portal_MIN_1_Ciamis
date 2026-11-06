<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Portal Resmi MIN 1 Ciamis — Madrasah Hebat Bermartabat')</title>

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/imgs/template/logo/favicon.svg') }}">

    <!-- Libs CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/bootstrap-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/carouselTicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/odometer.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="loader" class="loader">
            <div class="loader-container">
                <div class="loader-icon">
                    <img src="{{ asset('frontend/assets/imgs/template/logo/logo-gradient.svg') }}" alt="Preloader">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader-end -->

    <!-- Navbar & Topbar -->
    @include('frontend.partials.header')

    <!-- Popup Search & Offcanvas Sidebar -->
    @include('frontend.partials.sidebar')

    <!-- Konten Utama Halaman Depan -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.partials.footer')

    <!-- Scroll top -->
    <div class="btn-scroll-top">
        <svg class="progress-square svg-content" width="100%" height="100%" viewBox="0 0 40 40">
            <path d="M20 1a19 19 0 1 1 0 38 19 19 0 0 1 0-38" />
        </svg>
    </div>

    <!-- Libs JS -->
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

    <!-- Template JS -->
    <script src="{{ asset('frontend/assets/js/gsap-custom.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendors/image-hover-effects.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
