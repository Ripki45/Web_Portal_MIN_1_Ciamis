@extends('layouts.admin')

@section('title', 'Manajemen Berita & Kategori - MIN 1 Ciamis')

@section('content')

    @php
        \App\Models\KategoriBerita::pastikanTabelSiap();
        $kategoris =
            $kategoris ?? \App\Models\KategoriBerita::withCount('beritas')->orderBy('nama_kategori', 'asc')->get();
    @endphp

    <!-- Header & Tombol Aksi -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Pusat Publikasi Berita & Artikel Madrasah</h6>
            <span class="text-xs text-secondary-light">
                Kelola kategori berita, slug URL, dan artikel kegiatan lengkap dengan <b>Asisten Penulis Gemini AI</b>
            </span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button"
                class="btn btn-outline-success-600 radius-8 px-16 py-10 text-xs fw-bold d-inline-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalKelolaKategori">
                <i class="ph-bold ph-tag text-md"></i> 🏷️ Kelola Kategori ({{ $kategoris->count() }})
            </button>
            <a href="{{ url('/') }}#berita-terbaru" target="_blank"
                class="btn btn-outline-primary-600 radius-8 px-16 py-10 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat di Halaman Depan
            </a>
            <a href="{{ route('berita.create') }}"
                class="btn btn-primary-600 radius-8 px-20 py-10 text-xs fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="ph-bold ph-sparkle text-md"></i> + Tulis Artikel Baru (Studio AI)
            </a>
        </div>
    </div>

    <!-- 4 Kartu Ringkasan Statistik Berita -->
    <div class="row gy-3 mb-24">
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border shadow-sm p-16">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-xs text-secondary-light fw-semibold">Total Artikel</span>
                        <h5 class="fw-bold mb-0 mt-1">{{ $totalBerita }} Berita</h5>
                    </div>
                    <span
                        class="w-44-px h-44-px bg-primary-50 text-primary-600 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ph-bold ph-newspaper text-xl"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border shadow-sm p-16">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-xs text-secondary-light fw-semibold">Status Tayang Publik</span>
                        <h5 class="fw-bold mb-0 mt-1 text-success-600">{{ $totalTayang }} Tayang</h5>
                    </div>
                    <span
                        class="w-44-px h-44-px bg-success-focus text-success-main rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border shadow-sm p-16">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-xs text-secondary-light fw-semibold">Kategori Aktif</span>
                        <h5 class="fw-bold mb-0 mt-1 text-warning-main">{{ $kategoris->count() }} Kategori</h5>
                    </div>
                    <span
                        class="w-44-px h-44-px bg-warning-focus text-warning-main rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ph-bold ph-folders text-xl"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border shadow-sm p-16">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-xs text-secondary-light fw-semibold">Total Pembaca (Views)</span>
                        <h5 class="fw-bold mb-0 mt-1 text-info-main">{{ number_format($totalPembaca) }}x</h5>
                    </div>
                    <span
                        class="w-44-px h-44-px bg-info-focus text-info-main rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ph-bold ph-eye text-xl"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Berita -->
    <div class="card basic-data-table radius-12 border shadow-sm">
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h6 class="text-md fw-bold mb-0">Daftar Artikel & Berita Terdaftar</h6>
            <!-- Filter Cepat Kategori -->
            <form action="{{ route('berita.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="kategori" class="form-select form-select-sm radius-8 fw-semibold"
                    onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat->nama_kategori }}"
                            {{ request('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }} ({{ $kat->beritas_count }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table align-middle mb-0" id="tableBerita">
                    <thead>
                        <tr class="bg-neutral-50">
                            <th width="5%">No</th>
                            <th width="42%">Sampul, Judul & Slug URL</th>
                            <th>Kategori</th>
                            <th>Penulis & Tanggal</th>
                            <th class="text-center">Dibaca</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($beritas as $idx => $b)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}"
                                            class="radius-8 flex-shrink-0 border"
                                            style="width: 72px; height: 52px; object-fit: cover;">
                                        <div>
                                            @if ($b->is_featured)
                                                <span
                                                    class="bg-warning-focus text-warning-main border border-warning px-8 py-2 rounded-pill text-xs fw-bold d-inline-block mb-1">
                                                    ★ Headline Utama (Kartu Besar Kiri)
                                                </span>
                                            @endif
                                            <div class="fw-bold text-primary-light line-clamp-2">{{ $b->judul }}</div>
                                            <a href="{{ route('berita.show', $b->slug) }}" target="_blank"
                                                class="text-xs text-primary-600 font-monospace">
                                                /berita/{{ Str::limit($b->slug, 38) }} &#8599;
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    <span
                                        class="bg-info-focus text-info-main px-12 py-4 rounded-pill text-xs fw-bold d-inline-block">
                                        {{ $b->kategori }}
                                    </span>
                                </td>
                                <td class="text-xs">
                                    <div class="fw-bold text-primary-light">{{ $b->penulis }}</div>
                                    <span
                                        class="text-secondary-light">{{ $b->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </td>
                                <td class="text-center fw-bold text-xs">{{ number_format($b->views) }}x</td>
                                <td class="text-center text-nowrap">
                                    <span
                                        class="{{ $b->status === 'Tayang' ? 'bg-success-focus text-success-main border-success' : 'bg-warning-focus text-warning-main border-warning' }} border px-12 py-4 rounded-pill text-xs fw-bold d-inline-block">
                                        {{ $b->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('berita.show', $b->slug) }}" target="_blank"
                                            class="bg-info-focus text-info-main w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center"
                                            title="Baca di Website">
                                            <iconify-icon icon="lucide:eye"></iconify-icon>
                                        </a>
                                        <a href="{{ route('berita.edit', $b->id) }}"
                                            class="bg-success-focus text-success-600 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center"
                                            title="Edit di Studio">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        <button type="button"
                                            onclick="konfirmasiHapusBerita('{{ route('berita.destroy', $b->id) }}', '{{ addslashes($b->judul) }}')"
                                            class="bg-danger-focus text-danger-600 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                            title="Hapus Berita">
                                            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="formDeleteGlobal" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- MODAL CRUD KATEGORI BERITA & SLUG OTOMATIS                                -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalKelolaKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h1 class="modal-title fs-5 fw-bold">🏷️ Kelola Kategori Berita & Slug</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-24">
                    <!-- Form Tambah Kategori Baru -->
                    <form action="{{ route('kategori-berita.store') }}" method="POST"
                        class="p-16 radius-12 bg-neutral-50 border mb-20">
                        @csrf
                        <h6 class="text-sm fw-bold mb-12 text-primary-600">+ Tambah Kategori Baru</h6>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-xs fw-bold mb-4">Nama Kategori <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="nama_kategori" id="inputNamaKatBaru"
                                    class="form-control form-control-sm radius-8 fw-bold"
                                    placeholder="Misal: Lomba & Olimpiade"
                                    oninput="buatSlugOtomatis(this.value, 'inputSlugKatBaru')" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-xs fw-bold mb-4">Slug URL (Otomatis)</label>
                                <input type="text" name="slug" id="inputSlugKatBaru"
                                    class="form-control form-control-sm radius-8 font-monospace text-xs"
                                    placeholder="lomba-olimpiade">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-xs fw-bold mb-4">Warna Badge</label>
                                <select name="warna_badge" class="form-select form-select-sm radius-8 fw-semibold">
                                    <option value="bg-1">Ungu (bg-1)</option>
                                    <option value="bg-2">Biru (bg-2)</option>
                                    <option value="bg-3" selected>Hijau (bg-3)</option>
                                    <option value="bg-5">Kuning (bg-5)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary-600 btn-sm w-100 radius-8 py-8 fw-bold">
                                    + Simpan
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Daftar Kategori -->
                    <div class="table-responsive">
                        <table class="table bordered-table align-middle mb-0">
                            <thead>
                                <tr class="bg-neutral-50">
                                    <th>Nama Kategori</th>
                                    <th>Slug URL</th>
                                    <th class="text-center">Warna</th>
                                    <th class="text-center">Jml Berita</th>
                                    <th class="text-center" width="22%">Aksi Update / Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $kat)
                                    <tr>
                                        <td colspan="5" class="p-0 border-0">
                                            <form action="{{ route('kategori-berita.update', $kat->id) }}" method="POST"
                                                class="row g-2 align-items-center px-12 py-8 border-bottom m-0">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-4">
                                                    <input type="text" name="nama_kategori"
                                                        class="form-control form-control-sm radius-8 fw-bold"
                                                        value="{{ $kat->nama_kategori }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="slug"
                                                        class="form-control form-control-sm radius-8 font-monospace text-xs"
                                                        value="{{ $kat->slug }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="warna_badge"
                                                        class="form-select form-select-sm radius-8 text-xs">
                                                        <option value="bg-1"
                                                            {{ $kat->warna_badge == 'bg-1' ? 'selected' : '' }}>bg-1 (Ungu)
                                                        </option>
                                                        <option value="bg-2"
                                                            {{ $kat->warna_badge == 'bg-2' ? 'selected' : '' }}>bg-2 (Biru)
                                                        </option>
                                                        <option value="bg-3"
                                                            {{ $kat->warna_badge == 'bg-3' ? 'selected' : '' }}>bg-3
                                                            (Hijau)</option>
                                                        <option value="bg-5"
                                                            {{ $kat->warna_badge == 'bg-5' ? 'selected' : '' }}>bg-5
                                                            (Kuning)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <span
                                                        class="bg-primary-50 text-primary-600 px-8 py-2 rounded-pill text-xs fw-bold d-inline-block">
                                                        {{ $kat->beritas_count }}
                                                    </span>
                                                </div>
                                                <div class="col-md-2 d-flex justify-content-center gap-1">
                                                    <button type="submit"
                                                        class="btn btn-success-600 btn-sm radius-8 px-10 py-4 text-xs fw-bold"
                                                        title="Simpan Perubahan">
                                                        Simpan
                                                    </button>
                                                    <button type="button"
                                                        onclick="konfirmasiHapusKategori('{{ route('kategori-berita.destroy', $kat->id) }}', '{{ addslashes($kat->nama_kategori) }}')"
                                                        class="btn btn-danger-600 btn-sm radius-8 px-8 py-4 text-xs"
                                                        title="Hapus Kategori">
                                                        ✕
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        new DataTable('#tableBerita', {
            columnDefs: [{
                orderable: false,
                targets: [6]
            }]
        });

        function buatSlugOtomatis(teks, targetId) {
            const slug = teks.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById(targetId).value = slug;
        }

        function konfirmasiHapusBerita(url, judul) {
            Swal.fire({
                title: 'Hapus Artikel Berita?',
                text: '"' + judul + '" akan dihapus permanen dari portal website.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    const f = document.getElementById('formDeleteGlobal');
                    f.action = url;
                    f.submit();
                }
            });
        }

        function konfirmasiHapusKategori(url, nama) {
            Swal.fire({
                title: 'Hapus Kategori "' + nama + '"?',
                text: 'Kategori ini akan dihapus dari daftar pilihan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    const f = document.getElementById('formDeleteGlobal');
                    f.action = url;
                    f.submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 2200,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian!',
                text: @json(session('error'))
            });
        @endif
    </script>
@endpush
