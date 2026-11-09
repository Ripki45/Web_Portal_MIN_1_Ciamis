@extends('layouts.frontend')

@section('title', $halaman->judul . ' — MIN 1 Ciamis')

@section('content')

    <!-- Single 3 Section 1: Detail Halaman Statis / Profil Madrasah -->
    <section class="sec-1-single-3 pb-70 overflow-hidden">
        <div class="position-relative block-banner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Breadcrumb Otomatis -->
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb list-unstyled d-flex flex-row gap-2 align-items-center m-0 ps-0 py-4">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}" class="text-600 fs-7 hover-dark">Beranda</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <span class="icon-shape icon-xxs">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 15 15" fill="none">
                                            <path d="M6.125 4.5625L9.5625 7.84375L6.125 11.125" stroke="#626568"
                                                stroke-width="0.9375" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </li>
                                @if ($halaman->navMenu && $halaman->navMenu->parent)
                                    <li class="breadcrumb-item text-600 fs-7">
                                        {{ $halaman->navMenu->parent->nama_menu }}
                                    </li>
                                    <li class="breadcrumb-item">
                                        <span class="icon-shape icon-xxs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                viewBox="0 0 15 15" fill="none">
                                                <path d="M6.125 4.5625L9.5625 7.84375L6.125 11.125" stroke="#626568"
                                                    stroke-width="0.9375" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active text-dark fs-7 fw-semibold" aria-current="page">
                                    {{ $halaman->judul }}
                                </li>
                            </ul>
                        </nav>

                        <!-- Judul & Meta Info -->
                        <div class="card-title">
                            <div class="article card-info d-flex flex-wrap align-items-center gap-2 mt-2">
                                <a href="#" class="badge bg-1 fs-8">{{ $halaman->kategori ?: 'Profil Madrasah' }}</a>
                                <a href="#" class="badge bg-2 fs-8">MIN 1 Ciamis</a>
                                <ul class="d-flex align-items-center text-600 m-0 ps-3">
                                    <li>
                                        <p class="fs-8 m-0">{{ $halaman->waktu_baca }}</p>
                                    </li>
                                </ul>

                                @auth
                                    <a href="{{ route('halaman.edit', $halaman->id) }}"
                                        class="btn btn-dark btn-sm ms-auto py-1 px-3 fs-8">
                                        ✎ Edit Halaman Ini
                                    </a>
                                @endauth

                                <h3 class="w-100 mt-2 mb-3">{{ $halaman->judul }}</h3>
                            </div>

                            <div class="border-top"></div>

                            <div class="bottom mt-auto d-flex flex-wrap align-items-center gap-2 pt-3">
                                <div class="author d-flex align-items-center gap-2">
                                    <img class="avatar avatar-md rounded-circle"
                                        src="{{ asset('backend/assets/images/icon_min.png') }}" alt="MIN 1 Ciamis"
                                        style="width: 36px; height: 36px; object-fit: contain;" />
                                    <span class="fs-7 text-dark fw-semibold">{{ $halaman->penulis }}</span>
                                </div>
                                <ul class="d-flex align-items-center gap-4 text-600 m-0 ps-3">
                                    <li>
                                        <p class="fs-8 m-0">Diperbarui:
                                            {{ $halaman->updated_at->translatedFormat('d F Y') }}</p>
                                    </li>
                                </ul>
                                <div class="ms-md-auto d-flex align-items-center gap-3">
                                    <span class="readers d-flex align-items-center fs-8 text-600 gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.186 10.3224C15.734 13.039 12.9803 14.7266 10.001 14.7266C7.01977 14.7266 4.26612 13.039 2.81407 10.3224C2.70224 10.1114 2.70224 9.88843 2.81407 9.67767C4.26612 6.96107 7.01977 5.27366 10.001 5.27366C12.9803 5.27366 15.7339 6.96107 17.186 9.67767C17.2998 9.88843 17.2998 10.1114 17.186 10.3224ZM10.001 12.2707C11.2025 12.2707 12.18 11.2522 12.18 9.99993C12.18 8.7477 11.2025 7.72912 10.001 7.72912C8.79769 7.72912 7.82002 8.7477 7.82002 9.99993C7.82002 11.2522 8.79773 12.2707 10.001 12.2707Z"
                                                fill="#626568" />
                                        </svg>
                                        <span><span class="odometer text-nowrap" data-count="{{ $halaman->views }}"></span>
                                            kali dibaca</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama & Sidebar Kanan -->
        <div class="container">
            <div class="row mt-5 g-4">
                <!-- KOLOM KIRI (8 KOLOM): ISI HALAMAN -->
                <div class="col-lg-8">
                    @if ($halaman->gambar)
                        <img class="rounded-8 w-100 mb-4" src="{{ asset('storage/' . $halaman->gambar) }}"
                            alt="{{ $halaman->judul }}" style="max-height: 440px; object-fit: cover;" />
                    @endif

                    <div class="d-flex flex-column gap-3 content-body-madrasah">
                        <!-- Kotak Blockquote (Cocok untuk Kalimat Visi / Moto Madrasah) -->
                        @if (!empty($halaman->kutipan))
                            <blockquote class="blockquote my-2">
                                <p class="text-dark m-0 fs-22 fw-medium">
                                    "{{ $halaman->kutipan }}"
                                </p>
                                <p class="fs-7 mb-0 mt-2">— <span class="text-dark fw-semibold">MIN 1 Ciamis</span></p>
                            </blockquote>
                        @endif

                        <!-- Isi Konten Dinamis (HTML / Rich Text) -->
                        <div class="text-dark lh-lg">
                            {!! $halaman->isi_konten !!}
                        </div>

                        <div class="border-top mt-5 mb-1"></div>

                        <!-- Tag & Tombol Bagikan -->
                        <div class="d-flex flex-wrap gap-4 align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('home') }}" class="tag-item">
                                    <span>&larr; Kembali ke Beranda</span>
                                </a>
                                <span class="tag-item">
                                    <span>{{ $halaman->kategori }}</span>
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-dark fs-7">Bagikan:</span>
                                <div class="d-inline-flex group-social-icons mt-0 rounded-8">
                                    <a href="https://wa.me/?text={{ urlencode($halaman->judul . ' - ' . request()->fullUrl()) }}"
                                        target="_blank" class="icon-shape icon-46" title="Bagikan ke WhatsApp">
                                        <span class="fw-bold fs-7">WA</span>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                        target="_blank" class="icon-shape icon-46" title="Bagikan ke Facebook">
                                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="10"
                                            height="17" viewBox="0 0 10 17" fill="none">
                                            <path
                                                d="M8.84863 9.20312H6.5415V16.0938H3.46533V9.20312H0.942871V6.37305H3.46533V4.18896C3.46533 1.72803 4.94189 0.34375 7.1875 0.34375C8.26416 0.34375 9.40234 0.559082 9.40234 0.559082V2.98926H8.14111C6.91064 2.98926 6.5415 3.72754 6.5415 4.52734V6.37305H9.2793L8.84863 9.20312Z"
                                                fill="black"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (4 KOLOM): SIDEBAR PROFIL & MENU TERKAIT -->
                <div class="col-lg-4">
                    <div class="row">
                        <!-- Kartu Identitas Madrasah (Adaptasi dari .author-card di single-3.html) -->
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="author-card">
                                <div class="card-img mb-3 text-center">
                                    <img class="rounded-circle avatar-154 p-3 bg-white shadow-sm"
                                        src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Logo MIN 1 Ciamis"
                                        style="object-fit: contain;" />
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="mb-2">MIN 1 Ciamis</h5>
                                    <p class="mb-4 fs-7">
                                        Madrasah Ibtidaiyah Negeri 1 Ciamis — Mewujudkan pendidikan dasar Islam yang unggul,
                                        berkarakter, dan adaptif terhadap perkembangan teknologi.
                                    </p>
                                    <a href="{{ route('presensi.scanner') }}" class="btn btn-dark w-100 button-effect-1">
                                        Buka Scanner Presensi QR
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Menu Satu Induk / Halaman Lainnya -->
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="d-flex align-items-center gap-2 mt-5 mb-3">
                                <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M0.582044 11.7285C8.79451 13.4712 10.252 14.8614 12.125 22.7372C13.8067 14.8768 15.2308 13.4992 23.4018 11.8279C15.1894 10.0852 13.7319 8.69503 11.8589 0.81924C10.1769 8.67956 8.75306 10.0571 0.582044 11.7285Z"
                                        fill="#0E0E0F" />
                                </svg>
                                <h5 class="mb-0">Informasi & Halaman Lainnya</h5>
                            </div>

                            <div class="d-flex flex-column gap-3">
                                @forelse ($halamanLain as $hl)
                                    <div class="hover-up">
                                        <div class="article card-10 style-2">
                                            <div class="card-body ps-0">
                                                <a href="{{ route('halaman.show', $hl->slug) }}">
                                                    <h6 class="fs-7 mb-1 text-truncate-2">{{ $hl->judul }}</h6>
                                                </a>
                                                <div class="d-flex align-items-center text-600">
                                                    <span class="fs-8">{{ $hl->kategori }}</span>
                                                    <ul class="ps-4 m-0">
                                                        <li><span class="fs-8">{{ $hl->views }}x dibaca</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="fs-7 text-600">Belum ada halaman terkait lainnya.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Statistik Real-Time Madrasah (Adaptasi dari Popular Tags Odometer) -->
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="d-flex align-items-center gap-2 mt-5 mb-3">
                                <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M0.582044 11.7285C8.79451 13.4712 10.252 14.8614 12.125 22.7372C13.8067 14.8768 15.2308 13.4992 23.4018 11.8279C15.1894 10.0852 13.7319 8.69503 11.8589 0.81924C10.1769 8.67956 8.75306 10.0571 0.582044 11.7285Z"
                                        fill="#0E0E0F" />
                                </svg>
                                <h5 class="mb-0">Data Real-Time Madrasah</h5>
                            </div>
                            <ul class="list-unstyled d-flex flex-wrap gap-2 ps-0">
                                @foreach ($statMadrasah as $labelStat => $angkaStat)
                                    <li class="carouselTicker__item mx-0">
                                        <a href="#" class="tag-item link-effect-2">
                                            <span class="text">
                                                <span class="text1">{{ $labelStat }}</span>
                                                <span class="text2">{{ $labelStat }}</span>
                                            </span>
                                            <span class="number">
                                                <span class="odometer text-nowrap"
                                                    data-count="{{ $angkaStat }}"></span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
