@extends('layouts.admin')

@section('title', 'Manajemen Program Unggulan - MIN 1 Ciamis')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .program-thumb-preview {
            width: 76px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .program-icon-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .section-pill-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
        }
        .section-pill-chip.filled {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }
    </style>

    <!-- Header Halaman -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Program Unggulan Madrasah</h6>
            <span class="text-xs text-secondary-light">
                Kelola daftar program unggulan MIN 1 Ciamis beserta halaman detail, pilar, dan daftar kegiatannya.
            </span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('program-unggulan.publik') }}" target="_blank"
                class="btn btn-outline-primary-600 radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-arrow-square-out text-md"></i> Katalog Publik
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
        <div class="card-header border-bottom bg-base py-16 px-20 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                    <i class="ph-bold ph-list-dashes text-lg"></i> Daftar Program Unggulan
                </h6>
                <span class="badge bg-primary-50 text-primary-600 radius-6 px-10 py-4 text-xs fw-bold">
                    {{ $totalProgram }} Item
                </span>
            </div>

            @if ($totalProgram > 0)
                <div style="min-width: 240px;">
                    <input type="text" id="cariTabelProgram" class="form-control form-control-sm radius-8"
                        placeholder="Cari judul, kategori, atau PJ...">
                </div>
            @endif
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelProgramUnggulan">
                    <thead class="table-light text-2xs text-secondary-light text-uppercase">
                        <tr>
                            <th class="ps-20 py-12 text-center" style="width: 70px;">Urut</th>
                            <th class="py-12" style="width: 96px;">Sampul</th>
                            <th class="py-12">Program &amp; Ringkasan</th>
                            <th class="py-12" style="width: 210px;">Kelengkapan Konten</th>
                            <th class="py-12" style="width: 160px;">Pengelola / PJ</th>
                            <th class="py-12 text-center" style="width: 100px;">Status</th>
                            <th class="pe-20 py-12 text-end" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $item)
                            @php
                                $jmlMini = count($item->mini_features_list);
                                $jmlPilar = count($item->pilar_list_formatted);
                                $jmlKeg = count($item->kegiatan_list_data);
                                $jmlPoin = count($item->poin_list);
                            @endphp
                            <tr class="baris-program"
                                data-search="{{ strtolower($item->judul.' '.$item->kategori_badge.' '.$item->penulis.' '.$item->ringkasan_polos) }}">
                                <!-- Urutan -->
                                <td class="ps-20 py-14 text-center">
                                    <span class="badge bg-light text-dark border fw-bold radius-6 px-8 py-4">
                                        #{{ $item->urutan }}
                                    </span>
                                </td>

                                <!-- Sampul -->
                                <td class="py-14">
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="program-thumb-preview" loading="lazy">
                                </td>

                                <!-- Nama, Badge, dan Ringkasan Polos -->
                                <td class="py-14">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                                        <span class="program-icon-badge">
                                            <i class="{{ $item->icon }}"></i> {{ $item->kategori_badge }}
                                        </span>
                                        <small class="text-secondary-light text-2xs">
                                            <i class="ph-bold ph-eye"></i> {{ number_format($item->views, 0, ',', '.') }} views
                                        </small>
                                    </div>
                                    <h6 class="text-sm fw-bold mb-1">
                                        {{ $item->judul }}
                                    </h6>
                                    @if ($item->ringkasan_polos !== '')
                                        <p class="text-xs text-secondary-light mb-0 text-truncate" style="max-width: 380px;" title="{{ $item->ringkasan_polos }}">
                                            {{ $item->ringkasan_polos }}
                                        </p>
                                    @else
                                        <span class="text-2xs text-muted fst-italic">Belum ada ringkasan</span>
                                    @endif
                                </td>

                                <!-- Indikator Kelengkapan Section -->
                                <td class="py-14">
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="section-pill-chip {{ $jmlMini > 0 ? 'filled' : '' }}" title="Mini Card Section 1">
                                            Mini: {{ $jmlMini }}/2
                                        </span>
                                        <span class="section-pill-chip {{ $jmlPilar > 0 ? 'filled' : '' }}" title="Pilar Section 2">
                                            Pilar: {{ $jmlPilar }}/4
                                        </span>
                                        <span class="section-pill-chip {{ $jmlKeg > 0 ? 'filled' : '' }}" title="Kartu Kegiatan Section 3">
                                            Kegiatan: {{ $jmlKeg }}/6
                                        </span>
                                        <span class="section-pill-chip {{ $jmlPoin > 0 ? 'filled' : '' }}" title="Poin Ceklis Katalog">
                                            Ceklis: {{ $jmlPoin }}
                                        </span>
                                    </div>
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
                                        <button type="submit" class="border-0 bg-transparent p-0" title="Klik untuk ubah status tayang">
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
                                        @if ($item->is_active)
                                            <a href="{{ route('program-unggulan.detail', $item->slug) }}" target="_blank"
                                               class="btn btn-outline-success btn-sm radius-6 px-8 py-4"
                                               title="Lihat Halaman Publik">
                                                <i class="ph-bold ph-eye text-sm"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('program-unggulan.edit', $item->id) }}"
                                           class="btn btn-outline-primary-600 btn-sm radius-6 px-8 py-4"
                                           title="Edit Program">
                                            <i class="ph-bold ph-pencil-simple text-sm"></i>
                                        </a>

                                        <form action="{{ route('program-unggulan.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus program &quot;{{ addslashes($item->judul) }}&quot;?');"
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

    <script>
        (function () {
            var inputCari = document.getElementById('cariTabelProgram');
            if (!inputCari) return;

            inputCari.addEventListener('input', function () {
                var q = this.value.trim().toLowerCase();
                document.querySelectorAll('#tabelProgramUnggulan tbody tr.baris-program').forEach(function (row) {
                    var teks = row.getAttribute('data-search') || '';
                    row.style.display = teks.indexOf(q) !== -1 ? '' : 'none';
                });
            });
        })();
    </script>

@endsection