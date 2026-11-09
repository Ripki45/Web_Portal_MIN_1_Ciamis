@extends('layouts.admin')

@section('title', 'Kelola Halaman Website (Pages) - MIN 1 Ciamis')

@section('content')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Halaman Statis Website (Visi-Misi, Sejarah, Profil, dll.)</h6>
            <span class="text-xs text-secondary-light">
                Setiap halaman yang dibuat dapat langsung dihubungkan ke <b>Menu & Sub-Menu Navbar</b> secara otomatis
            </span>
        </div>
        <a href="{{ route('nav-menu.index') }}" class="btn btn-outline-primary-600 radius-8 px-14 py-8 text-xs fw-bold">
            <i class="ph-bold ph-tree-structure me-1"></i> Kelola Struktur Menu Navbar
        </a>
    </div>

    <div class="row gy-4">
        <!-- FORM BUAT HALAMAN BARU -->
        <div class="col-lg-5">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600">
                        <i class="ph-bold ph-file-plus me-1"></i> Buat Halaman Baru
                    </h6>
                </div>
                <form action="{{ route('halaman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-20">
                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs text-success-600 mb-6">
                                🔗 Hubungkan Otomatis ke Menu Navbar (Opsional)
                            </label>
                            <select name="nav_menu_id" class="form-select form-select-sm radius-8 border-success">
                                <option value="">-- Jangan Hubungkan / Pilih Nanti --</option>
                                @foreach ($menus as $m)
                                    <option value="{{ $m->id }}">
                                        [Level {{ $m->level }}] {{ $m->nama_menu }} ({{ $m->url }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-xs text-secondary-light">Jika dipilih, URL menu tersebut otomatis diarahkan
                                ke halaman ini.</small>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">Judul Halaman <span
                                    class="text-danger-600">*</span></label>
                            <input type="text" name="judul" class="form-control radius-8 fw-bold"
                                placeholder="Contoh: Visi dan Misi MIN 1 Ciamis" required>
                        </div>

                        <div class="row g-2 mb-14">
                            <div class="col-6">
                                <label class="form-label fw-bold text-sm mb-6">Label Kategori</label>
                                <input type="text" name="kategori" class="form-control radius-8 text-xs"
                                    value="Profil Madrasah">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-sm mb-6">Gambar Sampul</label>
                                <input type="file" name="gambar" class="form-control form-control-sm radius-8"
                                    accept="image/*">
                            </div>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">Kalimat Sorotan / Kutipan Visi
                                (Blockquote)</label>
                            <textarea name="kutipan" rows="2" class="form-control radius-8 text-xs"
                                placeholder="Teks ini akan tampil di dalam kotak kutipan besar di atas artikel (Kosongkan jika tidak perlu)..."></textarea>
                        </div>

                        <div class="mb-20">
                            <label class="form-label fw-bold text-sm mb-6">Isi Konten Halaman <span
                                    class="text-danger-600">*</span></label>
                            <textarea name="isi_konten" rows="7" class="form-control radius-8 text-sm"
                                placeholder="Tuliskan isi halaman di sini (mendukung format HTML seperti <h4>, <p>, <ul>, <li>)..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold">
                            <i class="ph-bold ph-floppy-disk me-1"></i> Terbitkan Halaman
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL DAFTAR HALAMAN -->
        <div class="col-lg-7">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-md fw-bold mb-0">Daftar Halaman Aktif ({{ $halamans->count() }})</h6>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table align-middle mb-0">
                            <thead>
                                <tr class="bg-neutral-50">
                                    <th>Judul & URL Slug</th>
                                    <th>Terhubung ke Menu</th>
                                    <th class="text-center">Dibaca</th>
                                    <th class="text-center" width="22%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($halamans as $h)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-primary-light">{{ $h->judul }}</div>
                                            <a href="{{ route('halaman.show', $h->slug) }}" target="_blank"
                                                class="font-monospace text-xs text-primary-600">
                                                /halaman/{{ $h->slug }} &#8599;
                                            </a>
                                        </td>
                                        <td>
                                            @if ($h->navMenu)
                                                <span
                                                    class="bg-success-focus text-success-main border border-success px-10 py-3 rounded-pill text-xs fw-bold d-inline-block">
                                                    ✓ {{ $h->navMenu->nama_menu }}
                                                </span>
                                            @else
                                                <span class="text-xs text-secondary-light fst-italic">Belum ditautkan</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold text-xs">{{ $h->views }}x</td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <a href="{{ route('halaman.show', $h->slug) }}" target="_blank"
                                                    class="bg-info-focus text-info-main w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center"
                                                    title="Lihat Halaman">
                                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                                </a>
                                                <a href="{{ route('halaman.edit', $h->id) }}"
                                                    class="bg-success-focus text-success-600 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center"
                                                    title="Edit Isi Halaman">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </a>
                                                <form action="{{ route('halaman.destroy', $h->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus halaman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-danger-focus text-danger-600 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                        title="Hapus">
                                                        <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-24 text-secondary-light">Belum ada halaman
                                            yang dibuat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
