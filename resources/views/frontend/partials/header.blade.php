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

        @php
            $navMenus = \App\Models\NavMenu::getTree();
        @endphp
        <div class="navbar-collapse d-none d-lg-block" id="navbarNav">
            <ul class="navbar-nav">
                @foreach ($navMenus as $induk)
                    @php
                        $punyaAnak = $induk->activeChildren->isNotEmpty();
                    @endphp

                    @if (!$punyaAnak)
                        <!-- LEVEL 1: MENU TUNGGAL TANPA DROPDOWN -->
                        <li class="nav-item {{ $induk->badge_new ? 'position-relative' : '' }}">
                            @if ($induk->badge_new)
                                <span class="badge-new">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                        viewBox="0 0 10 10" fill="none">
                                        <path
                                            d="M2.18102 5.83336H4.02138L3.64143 9.42543C3.63976 9.43626 3.6393 9.46831 3.6393 9.47914C3.6393 9.76624 3.87306 10 4.16016 10C4.3235 10 4.47929 9.92165 4.58267 9.78294L8.22266 4.58336C8.28972 4.49371 8.3268 4.38293 8.3268 4.27086C8.3268 3.98376 8.09311 3.75 7.80602 3.75H5.55351L5.9285 0.582047C5.92972 0.569992 5.93056 0.532913 5.93056 0.520859C5.93102 0.233765 5.69725 0 5.41016 0C5.24681 0 5.09102 0.0783539 4.98436 0.221252L1.7643 5C1.69724 5.08957 1.66016 5.20042 1.66016 5.3125C1.66016 5.59959 1.89392 5.83336 2.18102 5.83336Z"
                                            fill="#0E0E0F" />
                                    </svg>
                                </span>
                            @endif
                            <a class="nav-link link-effect-1" href="{{ $induk->url }}"
                                target="{{ $induk->target }}">
                                <span>{{ $induk->nama_menu }}</span>
                            </a>
                        </li>
                    @else
                        <!-- LEVEL 1: INDUK MENU DENGAN DROPDOWN -->
                        <li class="nav-item dropdown {{ $induk->badge_new ? 'position-relative' : '' }}">
                            <a class="nav-link dropdown-toggle link-effect-1" href="{{ $induk->url }}"
                                target="{{ $induk->target }}">
                                <span>{{ $induk->nama_menu }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($induk->activeChildren as $anak)
                                    @php
                                        $punyaCucu = $anak->activeChildren->isNotEmpty();
                                    @endphp

                                    @if (!$punyaCucu)
                                        <!-- LEVEL 2: ANAK MENU BIASA -->
                                        <li>
                                            <a class="dropdown-item" href="{{ $anak->url }}"
                                                target="{{ $anak->target }}">
                                                {{ $anak->nama_menu }}
                                            </a>
                                        </li>
                                    @else
                                        <!-- LEVEL 2: ANAK MENU YANG PUNYA CUCU (LEVEL 3) -->
                                        <li class="nav-item-has-child">
                                            <a class="dropdown-item has-child" href="{{ $anak->url }}"
                                                target="{{ $anak->target }}">
                                                {{ $anak->nama_menu }}
                                            </a>
                                            <ul class="sub-menu">
                                                @foreach ($anak->activeChildren as $cucu)
                                                    <!-- LEVEL 3: CUCU MENU -->
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $cucu->url }}"
                                                            target="{{ $cucu->target }}">
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
