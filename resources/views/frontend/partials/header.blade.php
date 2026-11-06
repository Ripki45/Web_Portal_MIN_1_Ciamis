<header>
    <div class="topbar d-none d-lg-block">
        <div class="overflow-hidden">
            <div class="d-flex justify-content-between align-items-center">
                <div class="left">
                    <ul class="list-unstyled d-inline-flex align-items-center gap-3 mb-0">
                        <!-- Pengumuman Penting Madrasah -->
                        <li>
                            <a href="#pengumuman" class="btn btn-sm fs-7 border-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21"
                                    fill="none">
                                    <g opacity="0.5">
                                        <path
                                            d="M10 16.5417C13.3368 16.5417 16.0417 13.8367 16.0417 10.5C16.0417 7.16327 13.3368 4.45833 10 4.45833C6.66332 4.45833 3.95837 7.16327 3.95837 10.5C3.95837 13.8367 6.66332 16.5417 10 16.5417Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M12.7083 10.5C12.7083 14.25 11.0355 16.5417 9.99996 16.5417C8.96446 16.5417 7.29163 14.25 7.29163 10.5C7.29163 6.74999 8.96446 4.45833 9.99996 4.45833C11.0355 4.45833 12.7083 6.74999 12.7083 10.5Z"
                                            stroke="white" stroke-width="1.25" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M4.16663 10.5H9.99996H15.8333" stroke="white" stroke-width="1.25"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                </svg>
                                <span class="text-white dark-mode-invert">Info Madrasah</span>
                            </a>
                        </li>
                        <li class="mw-350px">
                            <div class="swiper swiper-topbar">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="#" class="text-white fs-7 text-nowrap dark-mode-invert">Selamat
                                            Datang di Portal Informasi Resmi MIN 1 Ciamis</a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="{{ route('presensi.scanner') }}"
                                            class="text-white fs-7 text-nowrap dark-mode-invert">Layanan Presensi QR
                                            Siswa & Ekstrakurikuler Aktif Setiap Hari Kerja</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="switch-btn">
                            <button type="button" class="btn btn-sm fs-7 switch-btn-prev border-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16" fill="none">
                                    <path d="M6.83335 4.5L3.16669 8L6.83335 11.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.8333 8H3.33331" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <button type="button" class="btn btn-sm fs-7 switch-btn-next border-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16" fill="none">
                                    <path d="M9.16669 4.5L12.8334 8L9.16669 11.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.6667 8H3.16669" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="right">
                    <ul class="list-unstyled d-flex align-items-center ps-0 mb-0">
                        <li>
                            <span class="d-flex align-items-center gap-1">
                                <a href="{{ route('presensi.scanner') }}"
                                    class="link-effect-1 text-white fs-7 text-nowrap dark-mode-invert">
                                    <span>Scanner Presensi QR</span>
                                </a>
                            </span>
                        </li>
                        <li class="ps-4"></li>
                        <li class="border-end-700"></li>
                        <li class="pe-4"></li>
                        <li>
                            <div class="d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="15"
                                    viewBox="0 0 14 15" fill="none">
                                    <path
                                        d="M11.0943 1.85763H10.5967V1.35416C10.5967 1.06651 10.3635 0.833328 10.0759 0.833328C9.78823 0.833328 9.55505 1.06651 9.55505 1.35416V1.85763H4.44495V1.35416C4.44495 1.06651 4.21177 0.833328 3.92412 0.833328C3.63646 0.833328 3.40328 1.06651 3.40328 1.35416V1.85763H2.90575C1.48567 1.85763 0.330353 3.01295 0.330353 4.433V11.5913C0.330353 13.0114 1.48567 14.1667 2.90575 14.1667H11.0943C12.5144 14.1667 13.6697 13.0114 13.6697 11.5913V4.433C13.6697 3.01295 12.5144 1.85763 11.0943 1.85763Z"
                                        fill="white" />
                                </svg>
                                <span class="text-white fs-7 text-nowrap dark-mode-invert">
                                    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </li>
                        <li class="pe-4"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg style-1">
        <div class="d-flex align-items-center">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img class="dark-mode-invert" src="{{ asset('frontend/assets/imgs/template/logo/logo-dark.svg') }}"
                    alt="MIN 1 Ciamis">
            </a>
        </div>

        <div class="navbar-collapse d-none d-lg-block" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link link-effect-1" href="{{ route('home') }}"><span>Beranda</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle link-effect-1" href="#"><span>Profil Madrasah</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#sambutan">Sambutan Kepala Madrasah</a></li>
                        <li><a class="dropdown-item" href="#direktori-guru">Direktori Guru & Staf</a></li>
                        <li><a class="dropdown-item" href="#program-unggulan">Program Unggulan</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-effect-1" href="#berita-terbaru"><span>Berita & Informasi</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-effect-1" href="#ekstrakurikuler"><span>Ekstrakurikuler</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-effect-1" href="#prestasi"><span>Prestasi</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-effect-1" href="#galeri"><span>Galeri Foto</span></a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="search-btn fs-7 d-none d-md-flex link-effect-2">
                <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                    viewBox="0 0 32 32" fill="none">
                    <path
                        d="M25.6667 25.6667L20.6667 20.6667M6.33337 14.6667C6.33337 10.0643 10.0643 6.33337 14.6667 6.33337C19.2691 6.33337 23 10.0643 23 14.6667C23 19.2691 19.2691 23 14.6667 23C10.0643 23 6.33337 19.2691 6.33337 14.6667Z"
                        stroke="#0E0E0F" stroke-width="1.74463" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text">
                    <span class="text1">Cari</span>
                    <span class="text2">Cari</span>
                </span>
            </div>

            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-dark btn-sm button-effect-1 d-none d-md-inline-flex">
                    Dashboard ({{ auth()->user()->name }})
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-dark btn-sm button-effect-1 d-none d-md-inline-flex">
                    Login Portal
                </a>
            @endauth

            <div class="group-btn-right d-flex align-items-center">
                <div class="dark-light-switcher">
                    <svg class="svg-white dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" viewBox="0 0 20 20" fill="none">
                        <path
                            d="M10.0018 0.666664C4.85543 0.666664 0.666656 4.85406 0.666656 10.0004C0.666656 15.1468 4.85543 19.3356 10.0018 19.3356C15.1482 19.3356 19.337 15.1468 19.337 10.0004C19.337 4.85406 15.1482 0.666664 10.0018 0.666664ZM10.7212 2.13629C14.7526 2.4982 17.8982 5.87269 17.8982 10.0004C17.8982 14.1282 14.7526 17.5027 10.7212 17.8646V2.13629Z"
                            fill="#75787D" />
                    </svg>
                    <svg class="svg-dark dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" viewBox="0 0 20 20" fill="none">
                        <path
                            d="M10.0018 0.666992C4.85543 0.666992 0.666656 4.85439 0.666656 10.0008C0.666656 15.1471 4.85543 19.3359 10.0018 19.3359C15.1482 19.3359 19.337 15.1471 19.337 10.0008C19.337 4.85439 15.1482 0.666992 10.0018 0.666992ZM10.7212 2.13662C14.7526 2.49852 17.8982 5.87302 17.8982 10.0008C17.8982 14.1285 14.7526 17.503 10.7212 17.8649V2.13662Z"
                            fill="#0E0E0F" />
                    </svg>
                </div>
                <a href="#" class="navbar-toggler">
                    <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                        viewBox="0 0 26 26" fill="none">
                        <path
                            d="M6.5 5.19999C6.5 4.48205 7.08206 3.89999 7.8 3.89999H24.7C25.4179 3.89999 26 4.48205 26 5.19999C26 5.91794 25.4179 6.49999 24.7 6.49999H7.8C7.08206 6.49999 6.5 5.91789 6.5 5.19999ZM24.7 11.7H1.3C0.582055 11.7 0 12.2821 0 13C0 13.7179 0.582055 14.3 1.3 14.3H24.7C25.4179 14.3 26 13.7179 26 13C26 12.2821 25.4179 11.7 24.7 11.7ZM24.7 19.5H13C12.2821 19.5 11.7 20.082 11.7 20.8C11.7 21.5179 12.2821 22.1 13 22.1H24.7C25.4179 22.1 26 21.5179 26 20.8C26 20.082 25.4179 19.5 24.7 19.5Z"
                            fill="#0E0E0F" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>
</header>
