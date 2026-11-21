@extends('layouts.admin')

@section('title', 'Manajemen Program Unggulan - MIN 1 Ciamis')

@section('content')

    <!-- Font Awesome 6.5.1 untuk Preview Ikon Nyata di Dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .program-thumb-preview {
            width: 72px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .program-icon-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.74rem;
            font-weight: 700;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
    </style>

    <!-- Header Halaman -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Program Unggulan Madrasah</h6>
            <span class="text-xs text-secondary-light">
                Kelola program-program unggulan madrasah (Sekolah Ramah Anak, Tahfidz, KSM Sains, Literasi Digital) beserta konten halaman detailnya.
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/') }}#program" target="_blank"
                class="btn btn-outline-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat di Beranda
            </a>
            <a href="{{ route('program-unggulan.create') }}"
                class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-plus-circle text-md"></i> Tambah Program Baru
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Flash Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show radius-8 mb-24" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-bold ph-check-circle text-lg"></i>
                <span class="fw-semibold text-sm">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Kartu Statistik Ringkas -->
    <div class="row g-3 mb-24">
        <div class="col-md-4">
            <div class="card radius-12 border p-16 bg-base shadow-sm d-flex flex-row align-items-center gap-3">
                <div class="w-48-px h-48-px radius-10 bg-primary-50 text-primary-600 d-flex align-items-center justify-content-center text-xl flex-shrink-0">
                    <i class="ph-bold ph-star"></i>
                </div>
                <div>
                    <span class="text-xs text-secondary-light d-block fw-semibold">Total Program Terdaftar</span>
                    <h5 class="fw-bold mb-0 text-primary-600">{{ $totalProgram }} Program</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card radius-12 border p-16 bg-base shadow-sm d-flex flex-row align-items-center gap-3">
                <div class="w-48-px h-48-px radius-10 bg-success-50 text-success-600 d-flex align-items-center justify-content-center text-xl flex-shrink-0">
                    <i class="ph-bold ph-check-circle"></i>
                </div>
                <div>
                    <span class="text-xs text-secondary-light d-block fw-semibold">Program Aktif di Web</span>
                    <h5 class="fw-bold mb-0 text-success-600">{{ $totalAktif }} Aktif</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card radius-12 border p-16 bg-base shadow-sm d-flex flex-row align-items-center gap-3">
                <div class="w-48-px h-48-px radius-10 bg-warning-50 text-warning-600 d-flex align-items-center justify-content-center text-xl flex-shrink-0">
                    <i class="ph-bold ph-eye"></i>
                </div>
                <div>
                    <span class="text-xs text-secondary-light d-block fw-semibold">Total Tayangan Pembaca</span>
                    <h5 class="fw-bold mb-0 text-warning-600">{{ number_format($programs->sum('views'), 0, ',', '.') }} Views</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Program Unggulan -->
    <div class="card radius-12 border shadow-sm">
        <div class="card-header border-bottom bg-base py-16 px-20 d-flex align-items-center justify-content-between">
            <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                <i class="ph-bold ph-list-dashes text-lg"></i> Daftar Program Unggulan MIN 1 Ciamis
            </h6>
            <span class="badge bg-primary-50 text-primary-600 radius-6 px-10 py-4 text-xs fw-bold">
                {{ $totalProgram }} Item
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-2xs text-secondary-light text-uppercase">
                        <tr>
                            <th class="ps-20 py-12" style="width: 60px;">Urutan</th>
                            <th class="py-12" style="width: 100px;">Sampul</th>
                            <th class="py-12">Nama &amp; Ringkasan Program</th>
                            <th class="py-12" style="width: 220px;">Poin Keunggulan</th>
                            <th class="py-12" style="width: 150px;">Pengelola / PJ</th>
                            <th class="py-12 text-center" style="width: 100px;">Status</th>
                            <th class="pe-20 py-12 text-end" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $item)
                            <tr>
                                <!-- Urutan -->
                                <td class="ps-20 py-14 text-center">
                                    <span class="badge bg-light text-dark fw-bold radius-4 px-8 py-4">
                                        #{{ $item->urutan }}
                                    </span>
                                </td>

                                <!-- Sampul -->
                                <td class="py-14">
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="program-thumb-preview" loading="lazy">
                                </td>

                                <!-- Nama, Badge, dan Ringkasan -->
                                <td class="py-14">
                                    <div class="d-flex align-items-center gap-2 mb-4">
                                        <span class="program-icon-badge">
                                            <i class="{{ $item->icon }}"></i> {{ $item->kategori_badge }}
                                        </span>
                                        <small class="text-secondary-light text-2xs">
                                            <i class="ph-bold ph-eye"></i> {{ number_format($item->views, 0, ',', '.') }} views
                                        </small>
                                    </div>
                                    <h6 class="text-sm fw-bold mb-1 text-dark">
                                        {{ $item->judul }}
                                    </h6>
                                    @if ($item->ringkasan)
                                        <p class="text-xs text-secondary-light mb-0 text-truncate" style="max-width: 360px;" title="{{ $item->ringkasan }}">
                                            {{ $item->ringkasan }}
                                        </p>
                                    @endif
                                </td>

                                <!-- Poin Keunggulan -->
                                <td class="py-14">
                                    @php
                                        $poins = $item->poin_list;
                                    @endphp
                                    @if (!empty($poins))
                                        <ul class="list-unstyled mb-0 text-2xs text-secondary-light">
                                            @foreach (array_slice($poins, 0, 3) as $p)
                                                <li class="d-flex align-items-start gap-1 mb-1">
                                                    <i class="ph-bold ph-check-circle text-success-main mt-1"></i>
                                                    <span class="text-truncate" style="max-width: 190px;">{{ $p }}</span>
                                                </li>
                                            @endforeach
                                            @if (count($poins) > 3)
                                                <li class="text-primary-600 fw-bold">+{{ count($poins) - 3 }} poin lainnya</li>
                                            @endif
                                        </ul>
                                    @else
                                        <span class="text-2xs text-muted fst-italic">Belum ada poin</span>
                                    @endif
                                </td>

                                <!-- Penulis / PJ -->
                                <td class="py-14">
                                    <span class="text-xs fw-semibold text-secondary-light d-block">
                                        <i class="ph-bold ph-user"></i> {{ $item->penulis }}
                                    </span>
                                </td>

                                <!-- Toggle Status Aktif -->
                                <td class="py-14 text-center">
                                    <form action="{{ route('program-unggulan.toggle-status', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="border-0 bg-transparent p-0" title="Klik untuk ubah status aktif">
                                            @if ($item->is_active)
                                                <span class="badge bg-success text-white radius-6 px-10 py-4 text-2xs fw-bold">
                                                    <i class="ph-bold ph-check"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-muted radius-6 px-10 py-4 text-2xs fw-bold">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="pe-20 py-14 text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('program-unggulan.edit', $item->id) }}"
                                           class="btn btn-outline-primary-600 btn-sm radius-6 px-8 py-4"
                                           title="Edit Program">
                                            <i class="ph-bold ph-pencil-simple text-sm"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('program-unggulan.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus program \'{{ addslashes($item->judul) }}\'?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm radius-6 px-8 py-4" title="Hapus Program">
                                                <i class="ph-bold ph-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-36 text-secondary-light">
                                    <i class="ph-bold ph-star text-4xl mb-2 d-block text-muted"></i>
                                    Belum ada data program unggulan. Silakan klik tombol <strong>Tambah Program Baru</strong>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
