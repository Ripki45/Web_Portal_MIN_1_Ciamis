@extends('layouts.admin')

@section('title', 'Manajemen Menu & Sub-Menu Website - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Menu & Sub-Menu Website (3 Level)</h6>
            <span class="text-xs text-secondary-light">
                Kelola <b>Induk Menu</b> (Menu Tunggal / Dropdown), <b>Anak Menu</b> (Sub-Menu), dan <b>Cucu Menu</b>
                (Sub-Sub Menu)
            </span>
        </div>
        <a href="{{ url('/') }}" target="_blank"
            class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Navbar Halaman Depan
        </a>
    </div>

    <div class="row gy-4">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (4 KOLOM): FORM DINAMIS BERDASARKAN TINGKATAN MENU              -->
        <!-- ========================================================================= -->
        <div class="col-lg-4">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2" id="judulFormKiri">
                        <i class="ph-bold ph-plus-circle text-lg"></i> Buat Menu Navigasi Baru
                    </h6>
                </div>

                <form action="{{ route('nav-menu.store') }}" method="POST" id="formTambahMenu">
                    @csrf
                    <div class="card-body p-20">

                        <!-- LANGKAH 1: PILIH JENIS / TINGKATAN MENU -->
                        <div class="mb-16">
                            <label class="form-label fw-bold text-sm mb-6">
                                1. Pilih Tingkatan Menu <span class="text-danger-600">*</span>
                            </label>
                            <select name="level" id="selectLevelTambah"
                                class="form-select radius-8 fw-bold border-primary" onchange="aturTampilanIndukTambah()">
                                <option value="1">📁 Level 1 — Induk Menu (Menu Utama)</option>
                                <option value="2">↳ Level 2 — Anak Menu (Sub-Menu)</option>
                                <option value="3">↳↳ Level 3 — Cucu Menu (Sub-Sub Menu)</option>
                            </select>
                            <small id="infoLevelTambah" class="text-xs text-secondary-light d-block mt-4">
                                Tampil di barisan utama atas. Jika tidak punya anak, otomatis menjadi <b>Menu Tunggal</b>.
                            </small>
                        </div>

                        <!-- MUNCUL JIKA PILIH LEVEL 2 (ANAK MENU): PILIH INDUKNYA -->
                        <div class="mb-16 p-12 radius-8 bg-success-focus border border-success d-none"
                            id="boxPilihIndukTambah">
                            <label class="form-label fw-bold text-xs text-success-main mb-6">
                                📌 Masukkan ke Induk Menu Mana? <span class="text-danger-600">*</span>
                            </label>
                            <select name="induk_id" id="selectIndukTambah"
                                class="form-select form-select-sm radius-8 fw-semibold">
                                <option value="">-- Pilih Induk Menu (Level 1) --</option>
                                @foreach ($listInduk as $induk)
                                    <option value="{{ $induk->id }}">📁 {{ $induk->nama_menu }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- MUNCUL JIKA PILIH LEVEL 3 (CUCU MENU): PILIH ANAK MENUNYA -->
                        <div class="mb-16 p-12 radius-8 bg-warning-focus border border-warning d-none"
                            id="boxPilihAnakTambah">
                            <label class="form-label fw-bold text-xs text-warning-main mb-6">
                                📌 Masukkan ke Anak Menu Mana? <span class="text-danger-600">*</span>
                            </label>
                            <select name="anak_id" id="selectAnakTambah"
                                class="form-select form-select-sm radius-8 fw-semibold">
                                <option value="">-- Pilih Anak Menu (Level 2) --</option>
                                @foreach ($listAnak as $anak)
                                    <option value="{{ $anak->id }}">
                                        {{ $anak->parent->nama_menu ?? 'Induk' }} &rarr; {{ $anak->nama_menu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- LANGKAH 2: NAMA MENU -->
                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">
                                2. Nama / Label Menu <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="nama_menu" id="inputNamaMenuTambah"
                                class="form-control radius-8 fw-semibold" placeholder="Contoh: Profil Madrasah" required>
                        </div>

                        <!-- LANGKAH 3: LINK / URL -->
                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">3. Link / URL Tujuan</label>
                            <input type="text" name="url" id="inputUrlTambah"
                                class="form-control radius-8 font-monospace text-xs" value="#"
                                placeholder="Contoh: / atau #sambutan atau https://...">
                            <small class="text-xs text-secondary-light">Biarkan <code>#</code> jika menu ini hanya sebagai
                                pembuka dropdown.</small>
                        </div>

                        <!-- LANGKAH 4: URUTAN & TARGET -->
                        <div class="row g-2 mb-16">
                            <div class="col-6">
                                <label class="form-label fw-bold text-sm mb-6">Urutan Tampil</label>
                                <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                                    value="{{ $indukMenus->count() + 1 }}" min="1" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-sm mb-6">Cara Buka Link</label>
                                <select name="target" class="form-select radius-8 fw-semibold">
                                    <option value="_self">Tab Sama</option>
                                    <option value="_blank">Tab Baru</option>
                                </select>
                            </div>
                        </div>

                        <!-- TOGGLE OPSI -->
                        <div class="p-12 radius-8 bg-neutral-50 border mb-20">
                            <div class="form-check form-switch switch-warning d-flex align-items-center gap-2 mb-8"
                                id="wrapBadgePetirTambah">
                                <input class="form-check-input" type="checkbox" role="switch" name="badge_new"
                                    id="addBadgeNew">
                                <label class="form-check-label text-xs fw-semibold" for="addBadgeNew">
                                    ⚡ Beri Ikon Petir (Sorotan Menu Utama)
                                </label>
                            </div>
                            <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                    id="addIsActive" checked>
                                <label class="form-check-label text-xs fw-semibold" for="addIsActive">
                                    ✓ Aktifkan & Tampilkan di Website
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Menu Navigasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (8 KOLOM): TABEL POHON SILSILAH (INDUK -> ANAK -> CUCU)        -->
        <!-- ========================================================================= -->
        <div class="col-lg-8">
            <div class="card radius-12 border shadow-sm">
                <div
                    class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h6 class="text-md fw-bold mb-0">Struktur Hierarki Navbar Saat Ini</h6>
                        <span class="text-xs text-secondary-light">Klik tombol <b>+ Anak</b> atau <b>+ Cucu</b> pada baris
                            tabel untuk menambah sub-menu dengan cepat</span>
                    </div>
                </div>

                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table align-middle mb-0">
                            <thead>
                                <tr class="bg-neutral-50">
                                    <th>Susunan Menu (Induk / Anak / Cucu)</th>
                                    <th>Tipe & Posisi</th>
                                    <th>Link Tujuan</th>
                                    <th class="text-center" width="8%">Urut</th>
                                    <th class="text-center" width="10%">Status</th>
                                    <th class="text-center" width="18%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $semuaMenuFlat = collect(); @endphp
                                @forelse ($indukMenus as $induk)
                                    @php
                                        $semuaMenuFlat->push($induk);
                                        $punyaAnak = $induk->children->isNotEmpty();
                                    @endphp

                                    <!-- ================= BARIS LEVEL 1: INDUK MENU ================= -->
                                    <tr class="bg-primary-50 bg-opacity-25">
                                        <td class="fw-bold text-primary-600">
                                            <i
                                                class="ph-fill {{ $punyaAnak ? 'ph-folder-open' : 'ph-link-simple' }} me-1"></i>
                                            {{ $induk->nama_menu }}
                                            @if ($induk->badge_new)
                                                <span
                                                    class="bg-warning-focus text-warning-main px-8 py-2 rounded-pill text-xs ms-1 d-inline-block">⚡</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <span
                                                class="bg-primary-600 text-white px-10 py-3 rounded-pill text-xs fw-bold d-inline-block">
                                                {{ $punyaAnak ? 'Induk (Dropdown)' : 'Induk (Menu Tunggal)' }}
                                            </span>
                                        </td>
                                        <td class="font-monospace text-xs">{{ $induk->url }}</td>
                                        <td class="text-center fw-bold">{{ $induk->urutan }}</td>
                                        <td class="text-center">
                                            <span
                                                class="{{ $induk->is_active ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-10 py-3 rounded-pill text-xs fw-bold d-inline-block">
                                                {{ $induk->is_active ? 'Aktif' : 'Off' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <button type="button"
                                                    onclick="pintasTambahAnak('{{ $induk->id }}', '{{ addslashes($induk->nama_menu) }}')"
                                                    class="bg-primary-50 text-primary-600 border border-primary px-8 py-4 rounded-pill text-xs fw-bold"
                                                    title="Tambah Anak Menu di bawah {{ $induk->nama_menu }}">
                                                    + Anak
                                                </button>
                                                <button type="button"
                                                    class="bg-success-focus text-success-600 w-30-px h-30-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMenuModal{{ $induk->id }}" title="Edit Menu">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>
                                                <button type="button"
                                                    onclick="konfirmasiHapusMenu('{{ route('nav-menu.destroy', $induk->id) }}', '{{ addslashes($induk->nama_menu) }}')"
                                                    class="bg-danger-focus text-danger-600 w-30-px h-30-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                    title="Hapus Menu">
                                                    <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- ================= BARIS LEVEL 2: ANAK MENU ================= -->
                                    @foreach ($induk->children as $anak)
                                        @php
                                            $semuaMenuFlat->push($anak);
                                            $punyaCucu = $anak->children->isNotEmpty();
                                        @endphp
                                        <tr>
                                            <td class="ps-28 fw-semibold text-primary-light">
                                                <span class="text-success-600 fw-bold me-1">├──</span>
                                                {{ $anak->nama_menu }}
                                            </td>
                                            <td class="text-nowrap">
                                                <span
                                                    class="bg-success-focus text-success-main border border-success px-10 py-2 rounded-pill text-xs fw-bold d-inline-block">
                                                    Anak dari: {{ $induk->nama_menu }}
                                                </span>
                                            </td>
                                            <td class="font-monospace text-xs">{{ $anak->url }}</td>
                                            <td class="text-center fw-semibold">{{ $anak->urutan }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="{{ $anak->is_active ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-10 py-3 rounded-pill text-xs fw-bold d-inline-block">
                                                    {{ $anak->is_active ? 'Aktif' : 'Off' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <button type="button"
                                                        onclick="pintasTambahCucu('{{ $anak->id }}', '{{ addslashes($anak->nama_menu) }}')"
                                                        class="bg-warning-focus text-warning-main border border-warning px-8 py-4 rounded-pill text-xs fw-bold"
                                                        title="Tambah Cucu Menu di bawah {{ $anak->nama_menu }}">
                                                        + Cucu
                                                    </button>
                                                    <button type="button"
                                                        class="bg-success-focus text-success-600 w-30-px h-30-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editMenuModal{{ $anak->id }}"
                                                        title="Edit Anak Menu">
                                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                                    </button>
                                                    <button type="button"
                                                        onclick="konfirmasiHapusMenu('{{ route('nav-menu.destroy', $anak->id) }}', '{{ addslashes($anak->nama_menu) }}')"
                                                        class="bg-danger-focus text-danger-600 w-30-px h-30-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                        title="Hapus Anak Menu">
                                                        <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- ================= BARIS LEVEL 3: CUCU MENU ================= -->
                                        @foreach ($anak->children as $cucu)
                                            @php $semuaMenuFlat->push($cucu); @endphp
                                            <tr>
                                                <td class="ps-48 text-sm text-secondary-light">
                                                    <span
                                                        class="text-warning-main fw-bold me-1">&nbsp;&nbsp;&nbsp;&nbsp;└──</span>
                                                    {{ $cucu->nama_menu }}
                                                </td>
                                                <td class="text-nowrap">
                                                    <span
                                                        class="bg-warning-focus text-warning-main border border-warning px-10 py-2 rounded-pill text-xs fw-bold d-inline-block">
                                                        Cucu dari: {{ $anak->nama_menu }}
                                                    </span>
                                                </td>
                                                <td class="font-monospace text-xs">{{ $cucu->url }}</td>
                                                <td class="text-center text-xs">{{ $cucu->urutan }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="{{ $cucu->is_active ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-10 py-3 rounded-pill text-xs fw-bold d-inline-block">
                                                        {{ $cucu->is_active ? 'Aktif' : 'Off' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                                        <button type="button"
                                                            class="bg-success-focus text-success-600 w-30-px h-30-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editMenuModal{{ $cucu->id }}"
                                                            title="Edit Cucu Menu">
                                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                                        </button>
                                                        <button type="button"
                                                            onclick="konfirmasiHapusMenu('{{ route('nav-menu.destroy', $cucu->id) }}', '{{ addslashes($cucu->nama_menu) }}')"
                                                            class="bg-danger-focus text-danger-600 w-30-px h-30-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                            title="Hapus Cucu Menu">
                                                            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-24 text-secondary-light">Belum ada data
                                            menu navigasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form id="formDeleteMenuGlobal" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- MODAL EDIT MENU (DENGAN PILIHAN LEVEL -> INDUK / ANAK OTOMATIS)           -->
    <!-- ========================================================================= -->
    @foreach ($semuaMenuFlat as $item)
        <div class="modal fade" id="editMenuModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Edit Menu: {{ $item->nama_menu }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('nav-menu.update', $item->id) }}" method="POST"
                        id="formEditMenu{{ $item->id }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">

                            <div class="mb-14">
                                <label class="form-label fw-bold text-sm mb-6">Tingkatan Menu</label>
                                <select name="level" id="editLevel{{ $item->id }}"
                                    class="form-select radius-8 fw-bold border-primary"
                                    onchange="aturTampilanIndukEdit({{ $item->id }})">
                                    <option value="1" {{ $item->level == 1 ? 'selected' : '' }}>📁 Level 1 — Induk
                                        Menu (Menu Utama)</option>
                                    <option value="2" {{ $item->level == 2 ? 'selected' : '' }}>↳ Level 2 — Anak Menu
                                        (Sub-Menu)</option>
                                    <option value="3" {{ $item->level == 3 ? 'selected' : '' }}>↳↳ Level 3 — Cucu
                                        Menu (Sub-Sub Menu)</option>
                                </select>
                            </div>

                            <!-- PILIH INDUK (MUNCUL JIKA LEVEL 2) -->
                            <div class="mb-14 p-12 radius-8 bg-success-focus border border-success {{ $item->level == 2 ? '' : 'd-none' }}"
                                id="boxEditInduk{{ $item->id }}">
                                <label class="form-label fw-bold text-xs text-success-main mb-6">Pilih Induk Menu (Level
                                    1)</label>
                                <select name="induk_id" class="form-select form-select-sm radius-8 fw-semibold">
                                    @foreach ($listInduk as $induk)
                                        @if ($induk->id !== $item->id)
                                            <option value="{{ $induk->id }}"
                                                {{ $item->parent_id == $induk->id ? 'selected' : '' }}>
                                                📁 {{ $induk->nama_menu }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- PILIH ANAK MENU (MUNCUL JIKA LEVEL 3) -->
                            <div class="mb-14 p-12 radius-8 bg-warning-focus border border-warning {{ $item->level == 3 ? '' : 'd-none' }}"
                                id="boxEditAnak{{ $item->id }}">
                                <label class="form-label fw-bold text-xs text-warning-main mb-6">Pilih Anak Menu (Level
                                    2)</label>
                                <select name="anak_id" class="form-select form-select-sm radius-8 fw-semibold">
                                    @foreach ($listAnak as $anak)
                                        @if ($anak->id !== $item->id)
                                            <option value="{{ $anak->id }}"
                                                {{ $item->parent_id == $anak->id ? 'selected' : '' }}>
                                                {{ $anak->parent->nama_menu ?? 'Induk' }} &rarr; {{ $anak->nama_menu }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-14">
                                <label class="form-label fw-bold text-sm mb-6">Nama Menu <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="nama_menu" class="form-control radius-8 fw-bold"
                                    value="{{ $item->nama_menu }}" required>
                            </div>

                            <div class="mb-14">
                                <label class="form-label fw-bold text-sm mb-6">Link / URL Tujuan</label>
                                <input type="text" name="url" class="form-control radius-8 font-monospace text-xs"
                                    value="{{ $item->url }}">
                            </div>

                            <div class="row g-2 mb-14">
                                <div class="col-6">
                                    <label class="form-label fw-bold text-sm mb-6">Urutan</label>
                                    <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                                        value="{{ $item->urutan }}" min="1" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-sm mb-6">Target</label>
                                    <select name="target" class="form-select radius-8">
                                        <option value="_self" {{ $item->target == '_self' ? 'selected' : '' }}>Tab Sama
                                        </option>
                                        <option value="_blank" {{ $item->target == '_blank' ? 'selected' : '' }}>Tab Baru
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="p-12 radius-8 bg-neutral-50 border">
                                <div class="form-check form-switch switch-warning d-flex align-items-center gap-2 mb-8">
                                    <input class="form-check-input" type="checkbox" role="switch" name="badge_new"
                                        id="editBadge{{ $item->id }}" {{ $item->badge_new ? 'checked' : '' }}>
                                    <label class="form-check-label text-xs fw-semibold"
                                        for="editBadge{{ $item->id }}">⚡ Tampilkan Ikon Petir</label>
                                </div>
                                <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                        id="editActive{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label text-xs fw-semibold"
                                        for="editActive{{ $item->id }}">✓ Menu Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="button"
                                onclick="konfirmasiUpdateMenu('formEditMenu{{ $item->id }}', '{{ addslashes($item->nama_menu) }}')"
                                class="btn btn-primary-600 radius-8 fw-bold">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script>
        // 1. Tampilkan/Sembunyikan Dropdown Induk atau Anak di Form Kiri
        function aturTampilanIndukTambah() {
            const level = document.getElementById('selectLevelTambah').value;
            const boxInduk = document.getElementById('boxPilihIndukTambah');
            const boxAnak = document.getElementById('boxPilihAnakTambah');
            const info = document.getElementById('infoLevelTambah');
            const badgeBox = document.getElementById('wrapBadgePetirTambah');

            boxInduk.classList.add('d-none');
            boxAnak.classList.add('d-none');

            if (level === '1') {
                info.innerHTML =
                    'Tampil di barisan utama atas. Jika tidak punya anak, otomatis menjadi <b>Menu Tunggal</b>.';
                badgeBox.classList.remove('d-none');
            } else if (level === '2') {
                boxInduk.classList.remove('d-none');
                info.innerHTML = 'Tampil sebagai daftar <b>Dropdown</b> di bawah Induk Menu yang Anda pilih.';
                badgeBox.classList.add('d-none');
            } else if (level === '3') {
                boxAnak.classList.remove('d-none');
                info.innerHTML = 'Tampil sebagai <b>Sub-Dropdown Kesamping</b> di bawah Anak Menu yang Anda pilih.';
                badgeBox.classList.add('d-none');
            }
        }

        // 2. Tampilkan/Sembunyikan Dropdown Induk atau Anak di Modal Edit
        function aturTampilanIndukEdit(id) {
            const level = document.getElementById('editLevel' + id).value;
            const boxInduk = document.getElementById('boxEditInduk' + id);
            const boxAnak = document.getElementById('boxEditAnak' + id);

            boxInduk.classList.add('d-none');
            boxAnak.classList.add('d-none');

            if (level === '2') {
                boxInduk.classList.remove('d-none');
            } else if (level === '3') {
                boxAnak.classList.remove('d-none');
            }
        }

        // 3. Tombol Pintas "+ Anak" dari baris Tabel
        function pintasTambahAnak(indukId, namaInduk) {
            document.getElementById('selectLevelTambah').value = '2';
            aturTampilanIndukTambah();
            document.getElementById('selectIndukTambah').value = indukId;
            const inputNama = document.getElementById('inputNamaMenuTambah');
            inputNama.focus();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Menambahkan Anak Menu di bawah: ' + namaInduk,
                showConfirmButton: false,
                timer: 2000
            });
        }

        // 4. Tombol Pintas "+ Cucu" dari baris Tabel
        function pintasTambahCucu(anakId, namaAnak) {
            document.getElementById('selectLevelTambah').value = '3';
            aturTampilanIndukTambah();
            document.getElementById('selectAnakTambah').value = anakId;
            const inputNama = document.getElementById('inputNamaMenuTambah');
            inputNama.focus();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Menambahkan Cucu Menu di bawah: ' + namaAnak,
                showConfirmButton: false,
                timer: 2000
            });
        }

        // 5. SweetAlert Konfirmasi Update Menu
        function konfirmasiUpdateMenu(formId, nama) {
            const form = document.getElementById(formId);
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            Swal.fire({
                title: 'Simpan Perubahan Menu "' + nama + '"?',
                text: 'Struktur menu di halaman depan akan langsung diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    form.submit();
                }
            });
        }

        // 6. SweetAlert Konfirmasi Hapus Menu
        function konfirmasiHapusMenu(url, nama) {
            Swal.fire({
                title: 'Hapus Menu "' + nama + '"?',
                text: 'Jika menu ini memiliki Anak atau Cucu Menu di bawahnya, seluruh turunannya juga akan ikut dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    const f = document.getElementById('formDeleteMenuGlobal');
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
                timer: 2000,
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
