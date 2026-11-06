@extends('layouts.frontend')

@section('title', 'Beranda — Portal Resmi MIN 1 Ciamis')

@section('content')

    <!-- 1. HEADER SAMBUTAN SINGKAT -->
    <section class="sec-1-home-1 pt-4 pb-3" data-background="{{ asset('frontend/assets/imgs/page/bg-home1-sec1.png') }}">
        <div class="container">
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <h1 class="ds-2 lh-0 mb-2 text-anime-style-2">Selamat Datang di MIN 1 Ciamis</h1>
                    <p class="fs-5 mt-0 text-anime-style-1">Membangun Generasi Islami, Berprestasi, dan Berwawasan Digital
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. CAROUSEL BANNER UTAMA (4 SLIDE SOROTAN KEGIATAN) -->
    <section class="sec-9-home-1" id="carousel-banner">
        <div class="custom-container position-relative">
            <div class="block-card-swiper" id="gallery-background">
                <div class="container">
                    <div class="row align-items-stretch g-5">
                        <div class="col-lg-6">
                            <div class="swiper gallery-left overflow-y-visible">
                                <div class="swiper-wrapper">
                                    @php
                                        $dummyBanners = [
                                            [
                                                'kategori' => 'Kegiatan Siswa',
                                                'judul' => 'Gerakan Literasi Pagi Menghidupkan Budaya Baca',
                                                'ket' =>
                                                    'Pembiasaan membaca, diskusi singkat, dan dokumentasi karya siswa MIN 1 Ciamis.',
                                                'bg' => 'frontend/assets/imgs/page/bg-home1-sec9-1.png',
                                                'thumb' => 'frontend/assets/imgs/page/img-36.png',
                                            ],
                                            [
                                                'kategori' => 'Program Madrasah',
                                                'judul' => 'Pembelajaran Berbasis Teknologi & Karakter Islami',
                                                'ket' =>
                                                    'Integrasi kurikulum madrasah dengan inovasi pembelajaran digital interaktif.',
                                                'bg' => 'frontend/assets/imgs/page/bg-home1-sec9-2.png',
                                                'thumb' => 'frontend/assets/imgs/page/img-37.png',
                                            ],
                                            [
                                                'kategori' => 'Ekstrakurikuler',
                                                'judul' => 'Pembinaan Pramuka & Bakat Olahraga Siswa',
                                                'ket' =>
                                                    'Membangun kedisiplinan, kemandirian, dan sportivitas peserta didik sejak dini.',
                                                'bg' => 'frontend/assets/imgs/page/bg-home1-sec9-3.png',
                                                'thumb' => 'frontend/assets/imgs/page/img-36.png',
                                            ],
                                            [
                                                'kategori' => 'Prestasi Madrasah',
                                                'judul' => 'Raih Prestasi Akademik & Kompetisi Sains Madrasah',
                                                'ket' =>
                                                    'Komitmen pendampingan intensif bagi siswa dalam ajang olimpiade tingkat kabupaten hingga nasional.',
                                                'bg' => 'frontend/assets/imgs/page/bg-home1-sec9-2.png',
                                                'thumb' => 'frontend/assets/imgs/page/img-38.png',
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($dummyBanners as $bn)
                                        <div class="swiper-slide w-100" data-bg="{{ asset($bn['bg']) }}">
                                            <div class="article">
                                                <div class="card-body">
                                                    <a href="#" class="badge bg-2 fs-8 mb-3">{{ $bn['kategori'] }}</a>
                                                    <h5 class="card-title mb-0 text-white changeless">{{ $bn['judul'] }}
                                                    </h5>
                                                    <p class="card-text text-white mb-0 fs-7 mt-3 changeless">
                                                        {{ $bn['ket'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-8 ms-lg-auto align-self-stretch">
                            <div class="swiper gallery-thumbs h-100">
                                <div class="swiper-wrapper">
                                    @foreach ($dummyBanners as $bn)
                                        <div class="swiper-slide w-100">
                                            <div class="card-swiper-thumb">
                                                <div class="thumb changeless">
                                                    <img src="{{ asset($bn['thumb']) }}" alt="Banner">
                                                </div>
                                                <div class="card-content">
                                                    <h6 class="text-white mb-0 changeless">{{ $bn['judul'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. TICKER DATA REAL-TIME MADRASAH (DARI DATABASE) -->
    <div class="sec-2-home-1 bg-100 mask-image py-4">
        <div class="carouselTicker carouselTicker-left position-relative z-1">
            <ul class="carouselTicker__list d-flex gap-3">
                <li class="carouselTicker__item mx-0">
                    <a href="#" class="tag-item link-effect-2">
                        <span class="text"><span class="text1">Siswa Aktif</span><span class="text2">Siswa
                                Aktif</span></span>
                        <span class="number"><span class="odometer text-nowrap"
                                data-count="{{ $totalSiswa }}"></span></span>
                    </a>
                </li>
                <li class="carouselTicker__item mx-0">
                    <a href="#direktori-guru" class="tag-item link-effect-2">
                        <span class="text"><span class="text1">Guru & Staf GTK</span><span class="text2">Guru & Staf
                                GTK</span></span>
                        <span class="number"><span class="odometer text-nowrap"
                                data-count="{{ $totalGuru }}"></span></span>
                    </a>
                </li>
                <li class="carouselTicker__item mx-0">
                    <a href="#" class="tag-item link-effect-2">
                        <span class="text"><span class="text1">Rombongan Belajar</span><span class="text2">Rombongan
                                Belajar</span></span>
                        <span class="number"><span class="odometer text-nowrap"
                                data-count="{{ $totalKelas }}"></span></span>
                    </a>
                </li>
                <li class="carouselTicker__item mx-0">
                    <a href="#ekstrakurikuler" class="tag-item link-effect-2">
                        <span class="text"><span class="text1">Ekstrakurikuler</span><span
                                class="text2">Ekstrakurikuler</span></span>
                        <span class="number"><span class="odometer text-nowrap"
                                data-count="{{ $totalEkskul }}"></span></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 4. DIREKTORI GURU & TENAGA PENDIDIK (OTOMATIS DARI MASTER GTK) -->
    <section class="sec-5-home-1 sec-padding" id="direktori-guru"
        data-background="{{ asset('frontend/assets/imgs/page/bg-home1-sec5.png') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 text-anime-style-2">Direktori Guru & Tenaga Kependidikan</h5>
                            <p class="fs-7 ms-3 mb-0 d-none d-lg-block text-anime-style-1">Pendidik yang mendampingi proses
                                belajar siswa setiap hari</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row py-5">
                <div class="col-md-12 col-8 mx-auto position-relative">
                    <div class="swiper slider-4 rounded-16">
                        <div class="swiper-wrapper">
                            @forelse ($gtks as $guru)
                                @php
                                    $fotoGuru = !empty($guru->foto)
                                        ? asset('storage/' . $guru->foto)
                                        : 'https://ui-avatars.com/api/?name=' .
                                            urlencode($guru->nama_lengkap) .
                                            '&background=15803d&color=fff&size=400';
                                @endphp
                                <div class="swiper-slide">
                                    <div class="author-grid-wrap h-100">
                                        <div class="hover-effect-1">
                                            <div class="card-img-top">
                                                <a href="#">
                                                    <img src="{{ $fotoGuru }}" alt="{{ $guru->nama_lengkap }}"
                                                        class="author-image-avator w-100 h-100"
                                                        style="height: 320px; object-fit: cover;">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="author-sticky-block-left-bottom">
                                            <a href="#">
                                                <h6 class="fs-7 mb-0">{{ $guru->nama_lengkap }}</h6>
                                            </a>
                                            <p class="fs-8 m-0">
                                                {{ $guru->posisi->nama_posisi ?? ($guru->jabatan->nama_jabatan ?? 'Tenaga Pendidik') }}
                                            </p>
                                        </div>
                                        <div class="author-sticky-corner-left-top"></div>
                                        <div class="author-sticky-corner-right-bottom"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">Belum ada data GTK.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 swiper-btn">
                        <div class="swiper-btn-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path d="M10.25 6.75L4.75 12L10.25 17.25" stroke="#0e0e0f" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M19.25 12H5" stroke="#0e0e0f" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="swiper-btn-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path d="M13.75 6.75L19.25 12L13.75 17.25" stroke="#0e0e0f" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M19 12H4.75" stroke="#0e0e0f" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
