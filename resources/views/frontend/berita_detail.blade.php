@extends('layouts.frontend')

@section('title', $berita->judul . ' — Berita MIN 1 Ciamis')

@section('content')

    <section class="sec-1-single-3 pb-70 overflow-hidden">
        <div class="position-relative block-banner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb list-unstyled d-flex flex-row gap-2 align-items-center m-0 ps-0 py-4">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                        class="text-600 fs-7 hover-dark">Beranda</a></li>
                                <li class="breadcrumb-item"><span class="text-600 fs-7">&rsaquo;</span></li>
                                <li class="breadcrumb-item"><span class="text-600 fs-7">{{ $berita->kategori }}</span></li>
                                <li class="breadcrumb-item"><span class="text-600 fs-7">&rsaquo;</span></li>
                                <li class="breadcrumb-item active text-dark fs-7 fw-semibold" aria-current="page">
                                    {{ Str::limit($berita->judul, 45) }}</li>
                            </ul>
                        </nav>

                        <div class="card-title">
                            <div class="article card-info d-flex flex-wrap align-items-center gap-2 mt-2">
                                <a href="#" class="badge bg-3 fs-8">{{ $berita->kategori }}</a>
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

                                <h3 class="w-100 mt-2 mb-3">{{ $berita->judul }}</h3>
                            </div>

                            <div class="border-top"></div>

                            <div class="bottom mt-auto d-flex flex-wrap align-items-center gap-2 pt-3">
                                <div class="author d-flex align-items-center gap-2">
                                    <img class="avatar avatar-md rounded-circle"
                                        src="{{ asset('backend/assets/images/icon_min.png') }}" alt="Penulis"
                                        style="width: 36px; height: 36px; object-fit: contain;" />
                                    <span class="fs-7 text-dark fw-semibold">{{ $berita->penulis }}</span>
                                </div>
                                <ul class="d-flex align-items-center gap-4 text-600 m-0 ps-3">
                                    <li>
                                        <p class="fs-8 m-0">{{ $berita->created_at->translatedFormat('d F Y') }}</p>
                                    </li>
                                </ul>
                                <div class="ms-md-auto d-flex align-items-center gap-3">
                                    <span class="readers d-flex align-items-center fs-8 text-600 gap-1">
                                        <span class="odometer text-nowrap fw-bold"
                                            data-count="{{ $berita->views }}"></span> kali dibaca
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row mt-5 g-4">
                <div class="col-lg-8">
                    <img class="rounded-8 w-100 mb-4" src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}"
                        style="max-height: 460px; object-fit: cover;" />

                    <div class="d-flex flex-column gap-3">
                        @if (!empty($berita->kutipan))
                            <blockquote class="blockquote my-2">
                                <p class="text-dark m-0 fs-22 fw-medium">"{{ $berita->kutipan }}"</p>
                                <p class="fs-7 mb-0 mt-2">— <span
                                        class="text-dark fw-semibold">{{ $berita->penulis }}</span></p>
                            </blockquote>
                        @endif

                        <div class="text-dark lh-lg">
                            {!! $berita->isi_konten !!}
                        </div>

                        <div class="border-top mt-5 mb-1"></div>

                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @foreach (explode(',', (string) $berita->tags) as $tag)
                                    @if (trim($tag) !== '')
                                        <span class="tag-item"><span>#{{ trim($tag) }}</span></span>
                                    @endif
                                @endforeach
                            </div>
                            <a href="{{ route('home') }}" class="btn btn-dark btn-sm button-effect-1">&larr; Kembali ke
                                Beranda</a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Kanan: Berita Terkait -->
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <h5 class="mb-0">Berita Terbaru Lainnya</h5>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        @foreach ($beritaTerkait as $bt)
                            <div class="hover-up">
                                <div class="article card-10 style-2">
                                    <div class="hover-effect-1">
                                        <a href="{{ route('berita.show', $bt->slug) }}" class="card-img">
                                            <img class="w-100 rounded-8" src="{{ $bt->gambar_url }}"
                                                alt="{{ $bt->judul }}" style="height: 75px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ route('berita.show', $bt->slug) }}">
                                            <h6 class="fs-7 mb-1 text-truncate-2">{{ $bt->judul }}</h6>
                                        </a>
                                        <span class="fs-8 text-600">{{ $bt->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
