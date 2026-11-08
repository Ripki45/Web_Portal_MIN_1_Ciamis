<div class="popup-search d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col-10 mx-auto">
                <div class="popup-search-content position-relative">
                    <a href="#" class="close-popup position-absolute top-0 end-0 m-3">
                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none">
                            <path d="M17.25 6.75L6.75 17.25" stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M6.75 6.75L17.25 17.25" stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>
                    <h5 class="mb-4">Cari Berita & Informasi Madrasah</h5>
                    <form action="{{ route('home') }}" method="GET" class="d-flex flex-wrap flex-lg-nowrap gap-2">
                        <input class="form-control" type="text" name="q"
                            placeholder="Ketik kata kunci berita, ekskul, atau pengumuman...">
                        <button class="btn btn-dark button-effect-1" type="submit">Cari</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popup-search-overlay"></div>

<div class="sidebar-left">
    <div class="header-sidebar d-flex align-items-center justify-content-between py-3">
        <a href="{{ route('home') }}" class="sidebar-brand fw-bold fs-4 dark-mode-invert">
            <img src="{{ asset('frontend/assets/imgs/template/logo/logo-dark.svg') }}" alt="MIN 1 Ciamis" />
        </a>
        <a href="#" class="close-sidebar">
            <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none">
                <path d="M17.25 6.75L6.75 17.25" stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M6.75 6.75L17.25 17.25" stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </a>
    </div>

    @php
        $mobileMenus = \App\Models\NavMenu::getTree();
    @endphp
    <ul class="sidebar-nav list-unstyled ps-0">
        @foreach ($mobileMenus as $induk)
            @if ($induk->activeChildren->isEmpty())
                <li class="nav-item">
                    <a class="nav-link mb-2" href="{{ $induk->url }}"
                        target="{{ $induk->target }}">{{ $induk->nama_menu }}</a>
                </li>
            @else
                <li class="nav-item collapse">
                    <a class="nav-link mb-2 collapse-toggle" href="#">{{ $induk->nama_menu }}</a>
                    <ul class="collapse-menu d-flex flex-column gap-1 list-unstyled">
                        @foreach ($induk->activeChildren as $anak)
                            <li>
                                <a class="collapse-item fw-semibold" href="{{ $anak->url }}"
                                    target="{{ $anak->target }}">
                                    {{ $anak->nama_menu }}
                                </a>
                            </li>
                            @foreach ($anak->activeChildren as $cucu)
                                <li class="ps-3">
                                    <a class="collapse-item fs-8" href="{{ $cucu->url }}"
                                        target="{{ $cucu->target }}">
                                        &rarr; {{ $cucu->nama_menu }}
                                    </a>
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>

    <div class="sidebar-footer">
        <h6 class="my-2">Akses Cepat Sistem</h6>
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('presensi.scanner') }}" class="btn btn-dark w-100 button-effect-1">Scanner Presensi
                QR</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline-dark w-100">Buka Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-dark w-100">Login Guru / Admin</a>
            @endauth
        </div>
    </div>

    <div class="text-center mt-4">
        <p class="fs-7">
            {{ date('Y') }} Hak Cipta &copy; <span class="text-dark fw-bold">MIN 1 Ciamis</span>.
        </p>
    </div>
</div>
<div class="sidebar-overlay"></div>
