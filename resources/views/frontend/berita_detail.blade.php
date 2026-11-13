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
                                    <span class="text-600 fs-7">{{ $berita->kategori }}</span>
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

                        <!-- Block Subscribe (Dummy Bawaan Template) -->
                        <div
                            class="block-subscribe h-100 d-flex flex-column flex-lg-row gap-3 justify-content-between bg-200 wow img-custom-anim-top">
                            <div>
                                <div class="block-title d-flex align-items-center gap-1 fs-7 text-600">
                                    <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M4.75 7.75C4.75 6.64543 5.64543 5.75 6.75 5.75H17.25C18.3546 5.75 19.25 6.64543 19.25 7.75V16.25C19.25 17.3546 18.3546 18.25 17.25 18.25H6.75C5.64543 18.25 4.75 17.3546 4.75 16.25V7.75Z"
                                            stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M5.5 6.5L12 12.25L18.5 6.5" stroke="#0E0E0F" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span class="fs-7 fw-regular">Newsletter</span>
                                </div>
                                <div class="title">
                                    <h5 class="mb-0">
                                        Stay Informed with <br class="d-none d-lg-block">
                                        Top Headlines
                                    </h5>
                                </div>
                            </div>
                            <div class="decorate-1 mix-blend-multiply bottom-0"
                                data-background="{{ asset('assets/imgs/template/decorate-3.png') }}"></div>
                            <form action="#" class="position-relative">
                                <div class="d-flex flex-wrap flex-md-nowrap gap-2 align-items-center mb-3">
                                    <input class="form-control" type="text" placeholder="Your email address">
                                    <button class="btn btn-dark button-effect-1" type="button">Subscribe</button>
                                </div>
                                <input type="checkbox" id="subscribe">
                                <label for="subscribe" class="text-600 fs-8">By clicking the button, you are agreeing with
                                    our <a href="#" class="text-dark">Term & Conditions</a></label>
                            </form>
                        </div>

                        <!-- Form Leave a Comment (Dummy Bawaan Template) -->
                        <h4 class="mt-5 mb-2 pb-4 pt-3">Leave a comment</h4>
                        <div class="row wow img-custom-anim-top g-3 border-top">
                            <div class="col-md-6 col-lg-4">
                                <div class="input-group d-flex align-items-center">
                                    <div class="icon-input ps-3">
                                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none">
                                            <path class="stroke-dark"
                                                d="M12 11.25C13.7949 11.25 15.25 9.79493 15.25 8C15.25 6.20507 13.7949 4.75 12 4.75C10.2051 4.75 8.75 6.20507 8.75 8C8.75 9.79493 10.2051 11.25 12 11.25Z"
                                                stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path class="stroke-dark"
                                                d="M6.84723 19.25H17.1522C18.2941 19.25 19.1737 18.2681 18.6405 17.2584C17.856 15.7731 16.0677 14 11.9997 14C7.93174 14 6.1434 15.7731 5.35897 17.2584C4.8257 18.2681 5.70531 19.25 6.84723 19.25Z"
                                                stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <input type="text" class="form-control ms-0" name="name"
                                        placeholder="Your name *" aria-label="username">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="input-group d-flex align-items-center">
                                    <div class="icon-input ps-3">
                                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M4.75 7.75C4.75 6.64543 5.64543 5.75 6.75 5.75H17.25C18.3546 5.75 19.25 6.64543 19.25 7.75V16.25C19.25 17.3546 18.3546 18.25 17.25 18.25H6.75C5.64543 18.25 4.75 17.3546 4.75 16.25V7.75Z"
                                                stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M5.5 6.5L12 12.25L18.5 6.5" stroke="#0E0E0F" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <input type="text" class="form-control ms-0" name="email"
                                        placeholder="info@webmail.com" aria-label="email">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group d-flex align-items-center">
                                    <div class="icon-input ps-3">
                                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none">
                                            <path class="stroke-dark"
                                                d="M6.75 19.25H17.25C18.3546 19.25 19.25 18.3546 19.25 17.25V9.75001L12 4.75L4.75 9.75001V17.25C4.75 18.3546 5.64544 19.25 6.75 19.25Z"
                                                stroke="#111827" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path class="stroke-dark"
                                                d="M9.75 15.7495C9.75 14.6449 10.6455 13.7495 11.75 13.7495H12.25C13.3546 13.7495 14.25 14.6449 14.25 15.7495V19.2495H9.75V15.7495Z"
                                                stroke="#111827" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <input type="text" class="form-control ms-0" name="website" placeholder="Website"
                                        aria-label="website">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group d-flex">
                                    <div
                                        class="icon-input pt-2 ps-3 align-items-start border border-end-0 rounded-1 rounded-end-0">
                                        <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="18"
                                            height="18" viewBox="0 0 18 18" fill="none">
                                            <path class="stroke-dark"
                                                d="M5.5 2.14844H3C1.89543 2.14844 1 3.04387 1 4.14844V14.6484C1 15.753 1.89543 16.6484 3 16.6484H13.5C14.6046 16.6484 15.5 15.753 15.5 14.6484V12.1484"
                                                stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path class="fill-dark"
                                                d="M17.3285 1.20344L16.4448 0.319749C16.0185 -0.106583 15.3248 -0.106583 14.8984 0.319749L7.82915 7.38907C7.76373 7.45449 7.71914 7.53782 7.70096 7.62854L7.2591 9.83772C7.22839 9.99137 7.27647 10.1502 7.38729 10.261C7.47605 10.3498 7.59561 10.3983 7.71864 10.3983C7.74923 10.3983 7.77997 10.3953 7.81053 10.3892L10.0197 9.94732C10.1104 9.92917 10.1938 9.88455 10.2592 9.81913L17.3285 2.74984C17.3285 2.74984 17.3286 2.74984 17.3286 2.74981C17.7549 2.32351 17.7549 1.6298 17.3285 1.20344ZM9.69678 9.05607L8.31606 9.33225L8.59224 7.95153L14.3461 2.19754L15.4507 3.30214L9.69678 9.05607ZM16.6658 2.0871L16.1135 2.6394L15.0089 1.53479L15.5612 0.982524C15.6221 0.921601 15.7212 0.92157 15.7821 0.982493L16.6658 1.86618C16.7267 1.92707 16.7267 2.0262 16.6658 2.0871Z"
                                                fill="black" />
                                        </svg>
                                    </div>
                                    <textarea class="form-control" name="message" placeholder="Your message...." aria-label="With textarea"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-dark rounded-8 gap-2 button-effect-1">
                                    Submit comment
                                    <svg class="dark-mode-invert ms-2" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none">
                                        <path class="stroke-white"
                                            d="M21.1059 12.2562H0.5V11.7443H21.1059H22.313L21.4594 10.8907L17.0558 6.48705L17.4177 6.12508L23.2929 12.0002L17.4177 17.8754L17.0558 17.5134L21.4594 13.1098L22.313 12.2562H21.1059Z"
                                            stroke="white" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Daftar Komentar (Dummy Bawaan Template) -->
                        <h4 id="comments" class="mt-5 mb-2 pt-3 wow img-custom-anim-top">Comments</h4>
                        <div
                            class="d-flex flex-wrap flex-lg-nowrap gap-4 align-items-start pt-4 border-top wow img-custom-anim-top">
                            <img class="rounded-16 avatar-xl"
                                src="{{ asset('assets/imgs/template/author/author-18.png') }}" alt="magzin">
                            <div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <h6 class="mb-0 fs-6 text-900">Tromas H. Hendson</h6>
                                    <span class="mb-0 fs-6 text-500">June 9, 2025</span>
                                </div>
                                <p class="py-3 m-0 text-500">Variations in the floor plan, window location, and
                                    interstitial outdoor spaces enhance this material homogeneity. The goal was to produce a
                                    unified whole using a modern design language, where attention to materiality and detail
                                    is evident. All flats have two sides and are in close proximity to the outside world.
                                </p>
                                <a href="#" class="d-inline-flex text-600 hover-dark">
                                    <span class="bg-200 fs-8 rounded-8 py-2 px-3"> Reply </span>
                                </a>
                            </div>
                        </div>
                        <div class="border-top"></div>
                        <div
                            class="d-flex flex-wrap flex-lg-nowrap gap-4 align-items-start pt-4 ms-5 ps-5 wow img-custom-anim-top">
                            <img class="rounded-16 avatar-xl"
                                src="{{ asset('assets/imgs/template/author/author-15.png') }}" alt="magzin">
                            <div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <h6 class="mb-0 fs-6 text-900">Rosalina D.</h6>
                                    <span class="mb-0 fs-6 text-500">June 10, 2025</span>
                                </div>
                                <p class="py-3 m-0 text-500">Variations in the floor plan, window location, and
                                    interstitial outdoor spaces enhance this material homogeneity. The goal was to produce a
                                    unified whole using a modern design language, where attention to materiality and detail
                                    is evident. All flats have two sides and are in close proximity to the outside world.
                                </p>
                                <a href="#" class="d-inline-flex text-600 hover-dark">
                                    <span class="bg-200 fs-8 rounded-8 py-2 px-3"> Reply </span>
                                </a>
                            </div>
                        </div>
                        <div
                            class="d-flex flex-wrap flex-lg-nowrap gap-4 align-items-start border-top pt-4 wow img-custom-anim-top">
                            <img class="rounded-16 avatar-xl"
                                src="{{ asset('assets/imgs/template/author/author-9.png') }}" alt="magzin">
                            <div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <h6 class="mb-0 fs-6 text-900">Miranda H. Halim</h6>
                                    <span class="mb-0 fs-6 text-500">June 9, 2025</span>
                                </div>
                                <p class="py-3 m-0 text-500">Variations in the floor plan, window location, and
                                    interstitial outdoor spaces enhance this material homogeneity. The goal was to produce a
                                    unified whole using a modern design language, where attention to materiality and detail
                                    is evident. All flats have two sides and are in close proximity to the outside world.
                                </p>
                                <a href="#" class="d-inline-flex text-600 hover-dark">
                                    <span class="bg-200 fs-8 rounded-8 py-2 px-3"> Reply </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Sidebar -->
                <div class="col-lg-4">
                    <div class="row">
                        <!-- Author Card -->
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="author-card">
                                <div class="card-img mb-4 text-center">
                                    <img class="rounded-circle avatar-154"
                                        src="{{ asset('assets/imgs/template/author/author-16.png') }}"
                                        alt="{{ $berita->penulis }}" />
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="mb-3">{{ $berita->penulis }}</h5>
                                    <p class="mb-4">Step into a space where thoughts bloom, stories breathe, and
                                        imagination roams free. Here, I write not just to share—but to connect, to wander,
                                        and to wonder.</p>
                                    <p class="text-dark mb-0">Follow me</p>
                                    <div class="d-inline-flex group-social-icons mt-2">
                                        <a href="#" class="icon-shape icon-46">
                                            <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg"
                                                width="10" height="17" viewBox="0 0 10 17" fill="none">
                                                <path
                                                    d="M8.84863 9.20312H6.5415V16.0938H3.46533V9.20312H0.942871V6.37305H3.46533V4.18896C3.46533 1.72803 4.94189 0.34375 7.1875 0.34375C8.26416 0.34375 9.40234 0.559082 9.40234 0.559082V2.98926H8.14111C6.91064 2.98926 6.5415 3.72754 6.5415 4.52734V6.37305H9.2793L8.84863 9.20312Z"
                                                    fill="black" />
                                            </svg>
                                        </a>
                                        <a href="#" class="icon-shape icon-46">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="icon-shape icon-46">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                                <path
                                                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                                            </svg>
                                        </a>
                                        <a href="#" class="icon-shape icon-46">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-behance" viewBox="0 0 16 16">
                                                <path
                                                    d="M4.654 3c.461 0 .887.035 1.278.14.39.07.711.216.996.391s.497.426.641.747c.14.32.216.711.216 1.137 0 .496-.106.922-.356 1.242-.215.32-.566.606-.997.817.606.176 1.067.496 1.348.922s.461.957.461 1.563c0 .496-.105.922-.285 1.278a2.3 2.3 0 0 1-.782.887c-.32.215-.711.39-1.137.496a5.3 5.3 0 0 1-1.278.176L0 12.803V3zm-.285 3.978c.39 0 .71-.105.957-.285.246-.18.355-.497.355-.887 0-.216-.035-.426-.105-.567a1 1 0 0 0-.32-.355 1.8 1.8 0 0 0-.461-.176c-.176-.035-.356-.035-.567-.035H2.17v2.31c0-.005 2.2-.005 2.2-.005zm.105 4.193c.215 0 .426-.035.606-.07.176-.035.356-.106.496-.216s.25-.215.356-.39c.07-.176.14-.391.14-.641 0-.496-.14-.852-.426-1.102-.285-.215-.676-.32-1.137-.32H2.17v2.734h2.305zm6.858-.035q.428.427 1.278.426c.39 0 .746-.106 1.032-.286q.426-.32.53-.64h1.74c-.286.851-.712 1.457-1.278 1.848-.566.355-1.243.566-2.06.566a4.1 4.1 0 0 1-1.527-.285 2.8 2.8 0 0 1-1.137-.782 2.85 2.85 0 0 1-.712-1.172c-.175-.461-.25-.957-.25-1.528 0-.531.07-1.032.25-1.493.18-.46.426-.852.747-1.207.32-.32.711-.606 1.137-.782a4 4 0 0 1 1.493-.285c.606 0 1.137.105 1.598.355.46.25.817.532 1.102.958.285.39.496.851.641 1.348.07.496.105.996.07 1.563h-5.15c0 .58.21 1.11.496 1.396m2.24-3.732c-.25-.25-.642-.391-1.103-.391-.32 0-.566.07-.781.176s-.356.25-.496.39a.96.96 0 0 0-.25.497c-.036.175-.07.32-.07.46h3.196c-.07-.526-.25-.882-.497-1.132zm-3.127-3.728h3.978v.957h-3.978z" />
                                            </svg>
                                        </a>
                                    </div>
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
                                                        src="{{ asset('assets/imgs/other/img-other-1.png') }}"
                                                        alt="magzin">
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

                    <!-- Popular Tags & Sidebar Slider (Dummy Bawaan Template) -->
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="d-flex align-items-center gap-2 mt-5 mb-3">
                                <svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M0.582044 11.7285C8.79451 13.4712 10.252 14.8614 12.125 22.7372C13.8067 14.8768 15.2308 13.4992 23.4018 11.8279C15.1894 10.0852 13.7319 8.69503 11.8589 0.81924C10.1769 8.67956 8.75306 10.0571 0.582044 11.7285Z"
                                        fill="#0E0E0F" />
                                </svg>
                                <h5 class="mb-0">Popular tags</h5>
                            </div>
                            <ul class="list-unstyled d-flex flex-wrap gap-3 ps-0">
                                <li class="carouselTicker__item mx-0">
                                    <a href="#" class="tag-item link-effect-2">
                                        <span class="text">
                                            <span class="text1">Pendidikan</span>
                                            <span class="text2">Pendidikan</span>
                                        </span>
                                        <span class="number">
                                            <span class="odometer text-nowrap" data-count="68"></span>
                                        </span>
                                    </a>
                                </li>
                                <li class="carouselTicker__item mx-0">
                                    <a href="#" class="tag-item link-effect-2">
                                        <span class="text">
                                            <span class="text1">Madrasah</span>
                                            <span class="text2">Madrasah</span>
                                        </span>
                                        <span class="number">
                                            <span class="odometer text-nowrap" data-count="85"></span>
                                        </span>
                                    </a>
                                </li>
                                <li class="carouselTicker__item mx-0">
                                    <a href="#" class="tag-item link-effect-2">
                                        <span class="text">
                                            <span class="text1">Prestasi</span>
                                            <span class="text2">Prestasi</span>
                                        </span>
                                        <span class="number">
                                            <span class="odometer text-nowrap" data-count="120"></span>
                                        </span>
                                    </a>
                                </li>
                                <li class="carouselTicker__item mx-0">
                                    <a href="#" class="tag-item link-effect-2">
                                        <span class="text">
                                            <span class="text1">Pramuka</span>
                                            <span class="text2">Pramuka</span>
                                        </span>
                                        <span class="number">
                                            <span class="odometer text-nowrap" data-count="89"></span>
                                        </span>
                                    </a>
                                </li>
                                <li class="carouselTicker__item mx-0">
                                    <a href="#" class="tag-item link-effect-2">
                                        <span class="text">
                                            <span class="text1">Kegiatan</span>
                                            <span class="text2">Kegiatan</span>
                                        </span>
                                        <span class="number">
                                            <span class="odometer text-nowrap" data-count="52"></span>
                                        </span>
                                    </a>
                                </li>
                                <li class="carouselTicker__item mx-0">
                                    <a href="#" class="tag-item link-effect-2">
                                        <span class="text">
                                            <span class="text1">Teknologi</span>
                                            <span class="text2">Teknologi</span>
                                        </span>
                                        <span class="number">
                                            <span class="odometer text-nowrap" data-count="115"></span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-lg-12 col-12">
                            <div class="swiper slider-1 mt-5 rounded-16 overflow-hidden">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('assets/imgs/page/img-84.png') }}" alt="magzin" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('assets/imgs/page/img-85.png') }}" alt="magzin" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('assets/imgs/page/img-84.png') }}" alt="magzin" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img class="rounded-16 overflow-hidden cover-image"
                                            src="{{ asset('assets/imgs/page/img-85.png') }}" alt="magzin" />
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
                                            <img src="{{ asset('assets/imgs/other/img-other-9.png') }}" alt="magzin"
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
                            <div class="card-body mt-4">
                                <a href="#" class="hover-underline">
                                    <h6 class="card-title mb-0">The Rise of Mindful Living in a Digital World</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Banner Ads (Dummy Bawaan Template) -->
    <div class="bg-50 py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 mx-lg-auto">
                    <div class="block-ads bg-200">
                        <div class="banner-ads" data-background="{{ asset('assets/imgs/page/bg-archive1.png') }}">
                            <img src="{{ asset('assets/imgs/template/logo/logo-gradient-sm.svg') }}" alt="magzin">
                            <h4 class="mt-3 fs-5">
                                Modern Magazine & Blog <br class="d-none d-lg-block">
                                with <span>Outstanding</span> performance
                            </h4>
                            <div class="d-flex flex-wrap align-items-center gap-lg-4 gap-3">
                                <div class="tag-ads">
                                    <img src="{{ asset('assets/imgs/template/icons/icon-1.svg') }}" alt="magzin">
                                    <div class="tag-ads__content">
                                        <h6 class="fw-medium fs-7 m-0">Google</h6>
                                        <p class="fs-10 m-0">PageSpeed Insights</p>
                                    </div>
                                </div>
                                <div class="tag-ads">
                                    <img src="{{ asset('assets/imgs/template/icons/icon-2.svg') }}" alt="magzin">
                                    <div class="tag-ads__content">
                                        <h6 class="fw-medium fs-7 m-0">Core Web Vitals</h6>
                                        <p class="fs-10 m-0">Assessment Passed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
