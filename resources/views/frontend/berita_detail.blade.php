@extends('layouts.frontend')

@section('title', $berita->judul . ' — Berita MIN 1 Ciamis')

@section('content')

    <!--Single 3 Section 1-->
    <section class="sec-1-single-3 pb-70 overflow-hidden">
        <div class="position-relative block-banner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('berita.publik') }}" class="text-600 fs-7 hover-dark">Kabar Madrasah</a>
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('berita.publik', ['kategori' => $berita->kategori]) }}" class="text-600 fs-7 hover-dark">{{ $berita->kategori }}</a>
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
                                <li class="breadcrumb-item active text-dark fs-7" aria-current="page">
                                    {{ Str::limit($berita->judul, 45) }}
                                </li>
                            </ul>
                        </nav>

                        <div class="card-title">
                            <div class="article card-info d-flex flex-wrap align-items-center gap-2 mt-4">
                                <a href="#" class="badge bg-1 fs-8">{{ $berita->kategori }}</a>
                                <a href="#" class="badge bg-2 fs-8">MIN 1 Ciamis</a>
                                <ul class="d-flex align-items-center text-600 m-0 ps-3">
                                    <li>
                                        <p class="fs-8 m-0">{{ $berita->waktu_baca }}</p>
                                    </li>
                                </ul>

                                @auth
                                    <a href="{{ route('berita.edit', $berita->id) }}"
                                        class="btn btn-dark btn-sm ms-auto py-1 px-3 fs-8">
                                        ✎ Edit Berita Ini
                                    </a>
                                @endauth

                                <h3 class="w-100">{{ $berita->judul }}</h3>
                            </div>

                            <div class="border-top"></div>

                            <div class="bottom mt-auto d-flex flex-wrap align-items-center gap-2 pt-4">
                                <a href="#" class="author d-flex align-items-center gap-2">
                                    <img class="avatar avatar-md rounded-circle"
                                        src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Penulis"
                                        style="width: 36px; height: 36px; object-fit: contain;" />
                                    <span class="fs-7 text-dark fw-regular">{{ $berita->penulis }}</span>
                                </a>
                                <ul class="d-flex align-items-center gap-4 text-600 m-0 ps-3">
                                    <li>
                                        <p class="fs-8 m-0">{{ $berita->created_at->translatedFormat('d M, Y') }}</p>
                                    </li>
                                </ul>
                                <div class="ms-md-auto ms-5 d-flex align-items-center gap-3 me-5">
                                    <a href="#comments" class="comment d-flex align-items-center fs-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2.50018 5.43423C2.50018 4.26961 3.44494 3.3255 4.61035 3.3255H15.39C16.5554 3.3255 17.5002 4.26961 17.5002 5.43422V13.1078C17.5002 14.2724 16.5554 15.2165 15.39 15.2165H6.3295L3.41902 17.3786C3.24443 17.5083 3.01159 17.5285 2.81722 17.4309C2.62285 17.3333 2.50018 17.1345 2.50018 16.9171V5.43423ZM4.61035 4.47571C4.08062 4.47571 3.65118 4.90485 3.65118 5.43423V15.7729L5.79569 14.1799C5.89495 14.1062 6.01534 14.0663 6.13902 14.0663H15.39C15.9197 14.0663 16.3492 13.6372 16.3492 13.1078V5.43422C16.3492 4.90485 15.9197 4.47571 15.39 4.47571H4.61035Z"
                                                fill="#626568" />
                                        </svg>
                                        <span><span class="odometer text-nowrap" data-count="3"></span> Comments</span>
                                    </a>
                                    <a href="#" class="readers d-flex align-items-center fs-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.186 10.3224C15.734 13.039 12.9803 14.7266 10.001 14.7266C7.01977 14.7266 4.26612 13.039 2.81407 10.3224C2.70224 10.1114 2.70224 9.88843 2.81407 9.67767C4.26612 6.96107 7.01977 5.27366 10.001 5.27366C12.9803 5.27366 15.7339 6.96107 17.186 9.67767C17.2998 9.88843 17.2998 10.1114 17.186 10.3224ZM18.1135 9.13905C16.4744 6.07185 13.366 4.16669 10.001 4.16669C6.63409 4.16669 3.52561 6.07185 1.88652 9.13905C1.59341 9.68631 1.59341 10.3137 1.88652 10.8606C3.52561 13.9278 6.63409 15.8334 10.001 15.8334C13.366 15.8334 16.4744 13.9278 18.1135 10.8606C18.4066 10.3138 18.4066 9.68631 18.1135 9.13905ZM10.001 12.2707C11.2025 12.2707 12.18 11.2522 12.18 9.99993C12.18 8.7477 11.2025 7.72912 10.001 7.72912C8.79769 7.72912 7.82002 8.7477 7.82002 9.99993C7.82002 11.2522 8.79773 12.2707 10.001 12.2707ZM10.001 6.62215C8.21147 6.62215 6.75752 8.13757 6.75752 9.99997C6.75752 11.8628 8.21151 13.3776 10.001 13.3776C11.7886 13.3776 13.2425 11.8627 13.2425 9.99997C13.2425 8.13757 11.7886 6.62215 10.001 6.62215Z"
                                                fill="#626568" />
                                        </svg>
                                        <span><span class="odometer text-nowrap" data-count="{{ $berita->views }}"></span>
                                            views</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row mt-5 g-4">
                <!-- Kolom Kiri: Konten Utama, Share, Newsletter, & Komentar -->
                <div class="col-lg-8">
                    <img class="rounded-8 w-100 mb-4" src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}"
                        style="max-height: 460px; object-fit: cover;" />

                    <div class="d-flex flex-column gap-3">
                        @if (!empty($berita->kutipan))
                            <blockquote class="blockquote">
                                <p class="text-dark m-0 fs-22 fw-medium">{{ $berita->kutipan }}</p>
                                <p class="fs-7 mb-0">By <span class="text-dark">{{ $berita->penulis }}</span></p>
                            </blockquote>
                        @endif

                        <div class="text-dark lh-lg">
                            {!! $berita->isi_konten !!}
                        </div>

                        <div class="border-top mt-5 mb-1"></div>

                        <!-- Tags & Social Share -->
                        <div class="d-flex flex-wrap gap-4 align-items-center justify-content-between mb-4">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @php
                                    $tagsArray = array_filter(array_map('trim', explode(',', (string) $berita->tags)));
                                @endphp
                                @forelse ($tagsArray as $tag)
                                    <a href="#" class="tag-item">
                                        <span>{{ $tag }}</span>
                                    </a>
                                @empty
                                    <a href="#" class="tag-item"><span>Pendidikan</span></a>
                                    <a href="#" class="tag-item"><span>Madrasah</span></a>
                                    <a href="#" class="tag-item"><span>MIN 1 Ciamis</span></a>
                                @endforelse
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-dark">Share:</span>
                                <div class="d-inline-flex group-social-icons mt-0 rounded-8">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank" class="icon-shape icon-46">
                                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="10"
                                            height="17" viewBox="0 0 10 17" fill="none">
                                            <path
                                                d="M8.84863 9.20312H6.5415V16.0938H3.46533V9.20312H0.942871V6.37305H3.46533V4.18896C3.46533 1.72803 4.94189 0.34375 7.1875 0.34375C8.26416 0.34375 9.40234 0.559082 9.40234 0.559082V2.98926H8.14111C6.91064 2.98926 6.5415 3.72754 6.5415 4.52734V6.37305H9.2793L8.84863 9.20312Z"
                                                fill="black"></path>
                                        </svg>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}"
                                        target="_blank" class="icon-shape icon-46">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                            <path
                                                d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                        target="_blank" class="icon-shape icon-46">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                            <path
                                                d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#" class="icon-shape icon-46">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-behance" viewBox="0 0 16 16">
                                            <path
                                                d="M4.654 3c.461 0 .887.035 1.278.14.39.07.711.216.996.391s.497.426.641.747c.14.32.216.711.216 1.137 0 .496-.106.922-.356 1.242-.215.32-.566.606-.997.817.606.176 1.067.496 1.348.922s.461.957.461 1.563c0 .496-.105.922-.285 1.278a2.3 2.3 0 0 1-.782.887c-.32.215-.711.39-1.137.496a5.3 5.3 0 0 1-1.278.176L0 12.803V3zm-.285 3.978c.39 0 .71-.105.957-.285.246-.18.355-.497.355-.887 0-.216-.035-.426-.105-.567a1 1 0 0 0-.32-.355 1.8 1.8 0 0 0-.461-.176c-.176-.035-.356-.035-.567-.035H2.17v2.31c0-.005 2.2-.005 2.2-.005zm.105 4.193c.215 0 .426-.035.606-.07.176-.035.356-.106.496-.216s.25-.215.356-.39c.07-.176.14-.391.14-.641 0-.496-.14-.852-.426-1.102-.285-.215-.676-.32-1.137-.32H2.17v2.734h2.305zm6.858-.035q.428.427 1.278.426c.39 0 .746-.106 1.032-.286q.426-.32.53-.64h1.74c-.286.851-.712 1.457-1.278 1.848-.566.355-1.243.566-2.06.566a4.1 4.1 0 0 1-1.527-.285 2.8 2.8 0 0 1-1.137-.782 2.85 2.85 0 0 1-.712-1.172c-.175-.461-.25-.957-.25-1.528 0-.531.07-1.032.25-1.493.18-.46.426-.852.747-1.207.32-.32.711-.606 1.137-.782a4 4 0 0 1 1.493-.285c.606 0 1.137.105 1.598.355.46.25.817.532 1.102.958.285.39.496.851.641 1.348.07.496.105.996.07 1.563h-5.15c0 .58.21 1.11.496 1.396m2.24-3.732c-.25-.25-.642-.391-1.103-.391-.32 0-.566.07-.781.176s-.356.25-.496.39a.96.96 0 0 0-.25.497c-.036.175-.07.32-.07.46h3.196c-.07-.526-.25-.882-.497-1.132zm-3.127-3.728h3.978v.957h-3.978z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Sidebar -->
                <div class="col-lg-4">
                    <div class="row">
                        @php
                            $penulisNama = trim($berita->penulis ?: 'Tim Humas MIN 1 Ciamis');
                            $authorGtk = null;

                            // 1. Relasi user -> gtk jika berita memiliki user_id
                            if ($berita->user && $berita->user->gtk) {
                                $authorGtk = $berita->user->gtk;
                            }

                            // 2. Karena nama penulis di dashboard masih diketik manual, cocokkan dengan nama GTK di database
                            if (! $authorGtk && ! empty($penulisNama) && ! str_contains(strtolower($penulisNama), 'humas')) {
                                $authorGtk = \App\Models\Gtk::where('nama_lengkap', 'like', '%' . $penulisNama . '%')
                                    ->orWhereRaw('? LIKE CONCAT("%", nama_lengkap, "%")', [$penulisNama])
                                    ->first();
                            }

                            // 3. Tentukan URL Foto Penulis
                            if ($authorGtk && ! empty($authorGtk->foto)) {
                                $authorFotoUrl = asset('storage/' . $authorGtk->foto);
                            } elseif (str_contains(strtolower($penulisNama), 'humas') || str_contains(strtolower($penulisNama), 'madrasah') || str_contains(strtolower($penulisNama), 'min 1')) {
                                $authorFotoUrl = asset('backend/assets/images/icon_min.png');
                            } else {
                                $authorFotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($penulisNama) . '&background=0D6A56&color=fff&bold=true&size=200';
                            }

                            $authorRole = $authorGtk?->jabatan?->nama_jabatan
                                ?? $authorGtk?->posisi?->nama_posisi
                                ?? (str_contains(strtolower($penulisNama), 'humas') ? 'Tim Publikasi & Media' : 'Penulis Kabar Madrasah');
                        @endphp

                        <!-- Author Card -->
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="author-card p-4 rounded-4 text-center d-flex flex-column align-items-center justify-content-center"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                                <div class="card-img mb-3 d-flex justify-content-center align-items-center mx-auto">
                                    <div class="position-relative d-inline-block">
                                        <img class="rounded-circle shadow-sm"
                                            src="{{ $authorFotoUrl }}"
                                            alt="{{ $penulisNama }}"
                                            style="width: 110px; height: 110px; object-fit: cover; object-position: center; border: 4px solid #ffffff; display: block; margin: 0 auto;" />
                                        @if(str_contains(strtolower($penulisNama), 'humas') || $authorGtk)
                                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2 border-white"
                                                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;"
                                                title="Penulis Terverifikasi">
                                                <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body p-0 text-center w-100">
                                    <span class="badge rounded-pill px-3 py-1 mb-2 fw-semibold"
                                        style="background-color: #ecfdf5; color: #059669; font-size: 0.78rem; border: 1px solid #a7f3d0;">
                                        {{ $authorRole }}
                                    </span>
                                    <h5 class="mb-1 text-dark fw-bold text-center" style="font-size: 1.15rem; line-height: 1.35;">
                                        {{ $penulisNama }}
                                    </h5>
                                    <p class="text-muted small mb-0 text-center">
                                        <i class="fas fa-school text-emerald me-1" style="color: #059669;"></i> MIN 1 Ciamis
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Trending / Berita Terkait -->
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="d-flex align-items-center gap-2 mt-5 mt-lg-5 mt-md-0 mb-3">
                                <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M0.582044 11.7285C8.79451 13.4712 10.252 14.8614 12.125 22.7372C13.8067 14.8768 15.2308 13.4992 23.4018 11.8279C15.1894 10.0852 13.7319 8.69503 11.8589 0.81924C10.1769 8.67956 8.75306 10.0571 0.582044 11.7285Z"
                                        fill="#0E0E0F" />
                                </svg>
                                <h5 class="mb-0">Berita Terbaru Lainnya</h5>
                            </div>
                            <div class="d-flex flex-wrap gap-3">
                                @forelse ($beritaTerkait as $bt)
                                    <div class="hover-up w-100">
                                        <div class="article card-10 style-2">
                                            <div class="hover-effect-1">
                                                <a href="{{ route('berita.show', $bt->slug) }}" class="card-img">
                                                    <img class="w-100" src="{{ $bt->gambar_url }}"
                                                        alt="{{ $bt->judul }}"
                                                        style="height: 75px; object-fit: cover;">
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                <a href="{{ route('berita.show', $bt->slug) }}">
                                                    <h6 class="fs-7 mb-2 text-truncate-2">{{ $bt->judul }}</h6>
                                                </a>
                                                <div class="d-flex align-items-center text-600">
                                                    <span
                                                        class="fs-8">{{ $bt->created_at->translatedFormat('d M, Y') }}</span>
                                                    <ul class="ps-4 m-0">
                                                        <li>
                                                            <span
                                                                class="fs-8">{{ $bt->waktu_baca ?? '5 mins read' }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="hover-up">
                                        <div class="article card-10 style-2">
                                            <div class="hover-effect-1">
                                                <a href="#" class="card-img">
                                                    <img class="w-100"
                                                        src="{{ asset('frontend/assets/imgs/other/img-other-1.png') }}"
                                                        alt="MIN 1 Ciamis">
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                <a href="#">
                                                    <h6 class="fs-7 mb-2 text-truncate-2">Stay Informed with Global
                                                        Headlines</h6>
                                                </a>
                                                <div class="d-flex align-items-center text-600">
                                                    <span class="fs-8">16 Jul, 2025</span>
                                                    <ul class="ps-4 m-0">
                                                        <li><span class="fs-8">6 mins read</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Popular Tags & Sidebar Slider -->
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="d-flex align-items-center gap-2 mt-5 mb-3">
                                <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M0.582044 11.7285C8.79451 13.4712 10.252 14.8614 12.125 22.7372C13.8067 14.8768 15.2308 13.4992 23.4018 11.8279C15.1894 10.0852 13.7319 8.69503 11.8589 0.81924C10.1769 8.67956 8.75306 10.0571 0.582044 11.7285Z"
                                        fill="#0E0E0F" />
                                </svg>
                                <h5 class="mb-0">Tag & Kategori Populer</h5>
                            </div>

                            @php
                                $popularTagsList = collect();

                                // 1. Ambil dari Kategori Berita asli (dengan jumlah berita yang tayang)
                                if (isset($kategoriPopuler) && $kategoriPopuler->isNotEmpty()) {
                                    foreach ($kategoriPopuler as $kp) {
                                        if ($kp->beritas_count > 0) {
                                            $popularTagsList->push([
                                                'name' => $kp->nama_kategori,
                                                'count' => $kp->beritas_count,
                                                'url' => route('berita.publik', ['kategori' => $kp->nama_kategori]),
                                            ]);
                                        }
                                    }
                                }

                                // 2. Ambil dari kolom tags berita (ekstraksi kata kunci asli)
                                $allTagsRaw = \App\Models\Berita::where('status', 'Tayang')->whereNotNull('tags')->pluck('tags');
                                $tagsCounted = [];
                                foreach ($allTagsRaw as $tagStr) {
                                    $parts = explode(',', $tagStr);
                                    foreach ($parts as $p) {
                                        $t = trim($p);
                                        if ($t !== '' && strlen($t) > 2) {
                                            $tagsCounted[$t] = ($tagsCounted[$t] ?? 0) + 1;
                                        }
                                    }
                                }
                                arsort($tagsCounted);
                                foreach ($tagsCounted as $tagName => $cnt) {
                                    if (! $popularTagsList->contains('name', $tagName)) {
                                        $popularTagsList->push([
                                            'name' => $tagName,
                                            'count' => $cnt,
                                            'url' => route('berita.publik', ['q' => $tagName]),
                                        ]);
                                    }
                                }

                                // 3. Fallback jika data berita baru sedikit
                                if ($popularTagsList->isEmpty()) {
                                    $popularTagsList = collect([
                                        ['name' => 'Pendidikan', 'count' => 1, 'url' => route('berita.publik', ['q' => 'Pendidikan'])],
                                        ['name' => 'Madrasah', 'count' => 1, 'url' => route('berita.publik', ['q' => 'Madrasah'])],
                                        ['name' => 'Prestasi', 'count' => 1, 'url' => route('berita.publik', ['q' => 'Prestasi'])],
                                        ['name' => 'Ramah Anak', 'count' => 1, 'url' => route('berita.publik', ['q' => 'Ramah Anak'])],
                                    ]);
                                }
                            @endphp

                            <ul class="list-unstyled d-flex flex-wrap gap-3 ps-0">
                                @foreach ($popularTagsList->take(8) as $tagItem)
                                    <li class="carouselTicker__item mx-0">
                                        <a href="{{ $tagItem['url'] }}" class="tag-item link-effect-2" title="Lihat berita dengan topik {{ $tagItem['name'] }}">
                                            <span class="text">
                                                <span class="text1">{{ $tagItem['name'] }}</span>
                                                <span class="text2">{{ $tagItem['name'] }}</span>
                                            </span>
                                            <span class="number">
                                                <span class="odometer text-nowrap" data-count="{{ $tagItem['count'] }}"></span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="swiper slider-1 mt-5 rounded-16 overflow-hidden">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('frontend/assets/imgs/page/img-84.png') }}" alt="MIN 1 Ciamis" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('frontend/assets/imgs/page/img-85.png') }}" alt="MIN 1 Ciamis" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('frontend/assets/imgs/page/img-84.png') }}" alt="MIN 1 Ciamis" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('frontend/assets/imgs/page/img-85.png') }}" alt="MIN 1 Ciamis" />
                                    </div>
                                </div>
                                <div class="swiper-pagination mb-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Related Post (Recommended for You)-->
    <section class="related-post sec-padding bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-12">
                    <h5 class="mb-0">Recommended for You</h5>
                </div>
                @forelse ($beritaTerkait->take(4) as $bt)
                    <div class="col-md-6 col-lg-3">
                        <div class="article card-5">
                            <div class="post-link">
                                <div class="hover-effect-1">
                                    <div class="position-relative card-img-top thumbnail">
                                        <a href="{{ route('berita.show', $bt->slug) }}">
                                            <img src="{{ $bt->gambar_url }}" alt="{{ $bt->judul }}"
                                                class="cover-image" style="height: 220px; object-fit: cover;">
                                        </a>
                                        <a href="{{ route('berita.show', $bt->slug) }}"
                                            class="badge bg-1 fs-8">{{ $bt->kategori }}</a>
                                    </div>
                                </div>
                                <div class="card-corner white no-border">
                                    <a href="{{ route('berita.show', $bt->slug) }}" class="arrow-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M13.75 6.75L19.25 12L13.75 17.25" stroke="#0E0E0F" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19 12H4.75" stroke="#0E0E0F" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    <div class="curve-one"></div>
                                    <div class="curve-two"></div>
                                </div>
                            </div>
                            <div class="card-body mt-4">
                                <a href="{{ route('berita.show', $bt->slug) }}" class="hover-underline">
                                    <h6 class="card-title mb-0">{{ $bt->judul }}</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-6 col-lg-3">
                        <div class="article card-5">
                            <div class="post-link">
                                <div class="hover-effect-1">
                                    <div class="position-relative card-img-top thumbnail">
                                        <a href="#">
                                            <img src="{{ asset('frontend/assets/imgs/other/img-other-9.png') }}" alt="MIN 1 Ciamis"
                                                class="cover-image">
                                        </a>
                                        <a href="#" class="badge bg-1 fs-8">Culture</a>
                                    </div>
                                </div>
                                <div class="card-corner white no-border">
                                    <a href="#" class="arrow-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M13.75 6.75L19.25 12L13.75 17.25" stroke="#0E0E0F" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19 12H4.75" stroke="#0E0E0F" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    <div class="curve-one"></div>
                                    <div class="curve-two"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Banner Resmi MIN 1 Ciamis -->
    <div class="bg-50 py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 mx-lg-auto">
                    <div class="block-ads bg-200">
                        <div class="banner-ads" data-background="{{ asset('frontend/assets/imgs/page/bg-archive1.png') }}">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Logo MIN 1 Ciamis" style="height: 38px; width: auto; object-fit: contain;">
                                <span class="text-white fw-bold fs-6" style="letter-spacing: 0.5px;">MIN 1 CIAMIS</span>
                            </div>
                            <h4 class="mt-3 fs-5">
                                Madrasah Unggul, Ramah Anak <br class="d-none d-lg-block">
                                dengan <span>Pendidikan Berkarakter</span> Islami
                            </h4>
                            <div class="d-flex flex-wrap align-items-center gap-lg-4 gap-3">
                                <div class="tag-ads">
                                    <i class="fas fa-medal text-warning fs-5"></i>
                                    <div class="tag-ads__content">
                                        <h6 class="fw-medium fs-7 m-0">Terakreditasi A</h6>
                                        <p class="fs-10 m-0">Unggul & Berprestasi</p>
                                    </div>
                                </div>
                                <div class="tag-ads">
                                    <i class="fas fa-child text-info fs-5"></i>
                                    <div class="tag-ads__content">
                                        <h6 class="fw-medium fs-7 m-0">Sekolah Ramah Anak</h6>
                                        <p class="fs-10 m-0">Aman, Nyaman & Peduli</p>
                                    </div>
                                </div>
                                <a href="{{ route('home') }}#kontak" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 ms-lg-auto d-inline-flex align-items-center gap-2" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.35);">
                                    <span>Hubungi Kami</span>
                                    <i class="fas fa-arrow-right fs-11"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
