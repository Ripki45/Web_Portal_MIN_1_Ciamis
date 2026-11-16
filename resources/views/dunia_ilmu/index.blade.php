@extends('layouts.admin')

@section('title', 'Dunia Ilmu MIN 1 Ciamis (Filter Kelas & Mapel) - Dashboard')

@section('content')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Dunia Ilmu MIN 1 Ciamis (Berbasis Kelas &amp; Mata Pelajaran)</h6>
            <span class="text-xs text-secondary-light">
                Kelola media pembelajaran digital (<b>File Aplikasi .TSX / .JS</b> maupun <b>Link Web</b>) yang
                dikelompokkan berdasarkan <b>Kelas</b> dan <b>Mata Pelajaran</b>
            </span>
        </div>
        <a href="{{ url('/') }}#dunia-ilmu" target="_blank"
            class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat di Halaman Depan
        </a>
    </div>

    <div class="row gy-4">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (5 KOLOM): FORM TAMBAH MEDIA BARU (PILIH KELAS & MAPEL DB)      -->
        <!-- ========================================================================= -->
        <div class="col-lg-5">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600">+ Tambah Media Pembelajaran Baru</h6>
                </div>

                <form action="{{ route('dunia-ilmu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-20">

                        <!-- Pilihan Tipe Sumber Media -->
                        <div class="mb-14 p-12 radius-12 bg-primary-50 border border-primary-200">
                            <label class="form-label fw-bold text-xs text-primary-600 mb-6 d-block">
                                1. Pilih Jenis Sumber Media Pembelajaran <span class="text-danger-600">*</span>
                            </label>
                            <select name="tipe_sumber" id="selectTipeSumberAdd" class="form-select radius-8 fw-bold"
                                onchange="toggleSumberMedia('Add', this.value)">
                                <option value="kode_interaktif">⚛️ Upload File / Kode .TSX, .JS, .HTML (AI Studio)</option>
                                <option value="link">🔗 Tautan / Link Website Eksternal (PhET, Wordwall, dll.)</option>
                            </select>
                        </div>

                        <!-- Judul Media -->
                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-4">Judul Media Pembelajaran <span
                                    class="text-danger-600">*</span></label>
                            <input type="text" name="judul" class="form-control radius-8 fw-bold"
                                placeholder="Contoh: Petualangan PJOK Gerak Dasar" required>
                        </div>

                        <!-- Pilihan Kelas & Mata Pelajaran dari Database -->
                        <div class="row g-2 mb-14">
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-xs mb-4">Pilih Kelas (Database) <span
                                        class="text-danger-600">*</span></label>
                                <select name="kelas" class="form-select form-select-sm radius-8 fw-bold border-success"
                                    required>
                                    @foreach ($daftarKelas as $kls)
                                        <option value="{{ $kls }}">{{ $kls }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-bold text-xs mb-4">Mata Pelajaran (Database) <span
                                        class="text-danger-600">*</span></label>
                                <select name="mapel" class="form-select form-select-sm radius-8 fw-bold border-primary"
                                    required>
                                    @foreach ($daftarMapel as $mpl)
                                        <option value="{{ $mpl }}">{{ $mpl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- BOX A: KHUSUS UPLOAD FILE .TSX / .JSX / .JS / .HTML -->
                        <div id="boxKodeAdd" class="mb-16 p-14 radius-12 bg-neutral-50 border">
                            <label class="form-label fw-bold text-xs text-success-600 mb-6 d-block">
                                📂 Unggah File (.tsx, .jsx, .js, atau .html) dari AI Studio:
                            </label>
                            <input type="file" name="file_kode" class="form-control form-control-sm radius-8 mb-10"
                                accept=".tsx,.jsx,.js,.ts,.html,.txt"
                                onchange="bacaFileKodeKeEditor(this, 'textareaKodeAdd')">

                            <label class="form-label fw-bold text-xs text-secondary-light mb-4 d-block">
                                💻 Atau Tempel / Edit Langsung Kode (.tsx / .js / .html) di Sini:
                            </label>
                            <textarea name="isi_kode" id="textareaKodeAdd" rows="6"
                                class="form-control radius-8 font-monospace text-xs bg-dark text-white"
                                placeholder="import React, { useState } from 'react';&#10;export default function App() { ... }"></textarea>
                        </div>

                        <!-- BOX B: KHUSUS LINK EKSTERNAL -->
                        <div id="boxLinkAdd" class="mb-16 p-14 radius-12 bg-neutral-50 border d-none">
                            <label class="form-label fw-bold text-xs text-primary-600 mb-6 d-block">
                                🔗 Masukkan URL / Link Website Media Pembelajaran:
                            </label>
                            <input type="text" name="link_eksternal" class="form-control radius-8 font-monospace text-xs"
                                placeholder="https://phet.colorado.edu/in/ atau https://sites.google.com/...">
                        </div>

                        <!-- Warna Gradasi, Urutan & Thumbnail -->
                        <div class="row g-2 mb-14">
                            <div class="col-8">
                                <label class="form-label fw-bold text-xs mb-4">Warna Gradasi Kartu</label>
                                <select name="warna_gradasi" class="form-select form-select-sm radius-8 fw-semibold">
                                    @foreach ($daftarGradasi as $gClass => $gNama)
                                        <option value="{{ $gClass }}">{{ $gNama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-bold text-xs mb-4">Urutan</label>
                                <input type="number" name="urutan"
                                    class="form-control form-control-sm radius-8 fw-bold text-center"
                                    value="{{ $medias->count() + 1 }}" min="1" required>
                            </div>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-4">Ikon / Gambar Sampul Kartu (Opsional)</label>
                            <input type="file" name="gambar" class="form-control form-control-sm radius-8"
                                accept="image/*">
                        </div>

                        <div class="mb-16">
                            <label class="form-label fw-bold text-xs mb-4">Deskripsi Singkat Materi</label>
                            <textarea name="deskripsi" rows="2" class="form-control radius-8 text-xs"
                                placeholder="Jelaskan secara singkat kompetensi atau materi pada media ini..."></textarea>
                        </div>

                        <div class="p-10 radius-8 bg-neutral-50 border mb-16">
                            <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                    id="chkAddMediaActive" checked>
                                <label class="form-check-label text-xs fw-semibold" for="chkAddMediaActive">
                                    ✓ Tampilkan di Dunia Ilmu Halaman Depan
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold shadow-sm">
                            <i class="ph-bold ph-floppy-disk me-1"></i> Simpan &amp; Terbitkan Media
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (7 KOLOM): DAFTAR MEDIA + FILTER CEPAT KELAS & MAPEL           -->
        <!-- ========================================================================= -->
        <div class="col-lg-7">
            <div class="card radius-12 border shadow-sm">
                <div
                    class="card-header border-bottom bg-base py-16 px-20 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h6 class="text-md fw-bold mb-0">Daftar Media Dunia Ilmu ({{ $medias->count() }})</h6>
                    </div>

                    <!-- Filter Cepat Kelas & Mapel di Dashboard -->
                    <form action="{{ route('dunia-ilmu.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                        <select name="filter_kelas" class="form-select form-select-sm radius-8 text-xs fw-bold"
                            style="width: auto;" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas --</option>
                            @foreach ($daftarKelas as $kls)
                                <option value="{{ $kls }}"
                                    {{ request('filter_kelas') === $kls ? 'selected' : '' }}>{{ $kls }}</option>
                            @endforeach
                        </select>
                        <select name="filter_mapel" class="form-select form-select-sm radius-8 text-xs fw-bold"
                            style="width: auto;" onchange="this.form.submit()">
                            <option value="">-- Semua Mapel --</option>
                            @foreach ($daftarMapel as $mpl)
                                <option value="{{ $mpl }}"
                                    {{ request('filter_mapel') === $mpl ? 'selected' : '' }}>{{ $mpl }}</option>
                            @endforeach
                        </select>
                        @if (request()->filled('filter_kelas') or request()->filled('filter_mapel'))
                            <a href="{{ route('dunia-ilmu.index') }}"
                                class="btn btn-sm btn-secondary radius-8 text-xs">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="card-body p-20">
                    <div class="table-responsive">
                        <table class="table bordered-table align-middle mb-0">
                            <thead>
                                <tr class="bg-neutral-50">
                                    <th width="5%">#</th>
                                    <th>Judul Media &amp; Link</th>
                                    <th>Kelas &amp; Mapel</th>
                                    <th>Tipe</th>
                                    <th class="text-center" width="22%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($medias as $m)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $m->urutan }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $m->gambar_url }}" alt="{{ $m->judul }}"
                                                    class="radius-8 border p-1 bg-white flex-shrink-0"
                                                    style="width: 40px; height: 40px; object-fit: contain;">
                                                <div>
                                                    <div class="fw-bold text-sm text-primary-light">{{ $m->judul }}
                                                    </div>
                                                    <a href="{{ $m->url_tujuan }}" target="_blank"
                                                        class="text-xs text-primary-600 font-monospace">
                                                        {{ $m->tipe_sumber === 'kode_interaktif' ? '/dunia-ilmu/play/' . Str::limit($m->slug, 20) : Str::limit($m->link_eksternal, 26) }}
                                                        &#8599;
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="bg-success-focus text-success-main px-10 py-2 rounded-pill text-xs fw-bold d-inline-block mb-1">
                                                {{ $m->kelas ?: 'Semua Kelas' }}
                                            </span>
                                            <div class="text-xs fw-bold text-secondary-light">
                                                {{ $m->mapel ?: $m->kategori_label }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($m->tipe_sumber === 'kode_interaktif')
                                                <span
                                                    class="bg-warning-focus text-warning-main border border-warning px-8 py-2 rounded-pill text-xs fw-bold d-inline-block">
                                                    ⚛️ TSX/JS
                                                </span>
                                            @else
                                                <span
                                                    class="bg-info-focus text-info-main border border-info px-8 py-2 rounded-pill text-xs fw-bold d-inline-block">
                                                    🔗 Link
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <a href="{{ $m->url_tujuan }}" target="_blank"
                                                    class="btn btn-sm btn-primary-600 radius-8 px-10 py-4 text-xs fw-bold"
                                                    title="Jalankan Media Pembelajaran">
                                                    🚀 Buka
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-success-600 radius-8 px-8 py-4 text-xs fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMediaModal{{ $m->id }}">
                                                    ✎
                                                </button>
                                                <button type="button"
                                                    onclick="konfirmasiHapusMedia('{{ route('dunia-ilmu.destroy', $m->id) }}', '{{ addslashes($m->judul) }}')"
                                                    class="btn btn-sm btn-outline-danger-600 radius-8 px-8 py-4 text-xs fw-bold">
                                                    ✕
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-24 text-secondary-light">
                                            Belum ada media pembelajaran untuk filter Kelas/Mapel ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form id="formDeleteMediaGlobal" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- KUMPULAN MODAL EDIT MEDIA DUNIA ILMU                                      -->
    <!-- ========================================================================= -->
    @foreach ($medias as $m)
        <div class="modal fade" id="editMediaModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Edit Media: {{ $m->judul }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('dunia-ilmu.update', $m->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">Judul Media <span
                                            class="text-danger-600">*</span></label>
                                    <input type="text" name="judul" class="form-control radius-8 fw-bold"
                                        value="{{ $m->judul }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">Jenis Sumber Media</label>
                                    <select name="tipe_sumber" class="form-select radius-8 fw-bold"
                                        onchange="toggleSumberMedia('Edit{{ $m->id }}', this.value)">
                                        <option value="kode_interaktif"
                                            {{ $m->tipe_sumber === 'kode_interaktif' ? 'selected' : '' }}>⚛️ Aplikasi
                                            Interaktif (.TSX / .JS / .HTML)</option>
                                        <option value="link" {{ $m->tipe_sumber === 'link' ? 'selected' : '' }}>🔗 Link
                                            Website Eksternal</option>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-xs mb-4">Pilih Kelas (Database) <span
                                            class="text-danger-600">*</span></label>
                                    <select name="kelas" class="form-select radius-8 fw-bold border-success" required>
                                        @foreach ($daftarKelas as $kls)
                                            <option value="{{ $kls }}"
                                                {{ ($m->kelas ?? 'Semua Kelas') === $kls ? 'selected' : '' }}>
                                                {{ $kls }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-xs mb-4">Mata Pelajaran (Database) <span
                                            class="text-danger-600">*</span></label>
                                    <select name="mapel" class="form-select radius-8 fw-bold border-primary" required>
                                        @foreach ($daftarMapel as $mpl)
                                            <option value="{{ $mpl }}"
                                                {{ ($m->mapel ?? '') === $mpl ? 'selected' : '' }}>{{ $mpl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold text-xs mb-4">Urutan</label>
                                    <input type="number" name="urutan"
                                        class="form-control radius-8 fw-bold text-center" value="{{ $m->urutan }}"
                                        min="1" required>
                                </div>

                                <!-- Box Kode Edit -->
                                <div class="col-12 {{ $m->tipe_sumber === 'kode_interaktif' ? '' : 'd-none' }}"
                                    id="boxKodeEdit{{ $m->id }}">
                                    <div class="p-14 radius-12 bg-neutral-50 border">
                                        <label class="form-label fw-bold text-xs text-success-600 mb-4 d-block">
                                            📂 Ganti dengan Upload File (.tsx / .jsx / .js / .html) Baru:
                                        </label>
                                        <input type="file" name="file_kode"
                                            class="form-control form-control-sm radius-8 mb-10"
                                            accept=".tsx,.jsx,.js,.ts,.html,.txt"
                                            onchange="bacaFileKodeKeEditor(this, 'textareaKodeEdit{{ $m->id }}')">

                                        <label class="form-label fw-bold text-xs mb-4 d-block">💻 Kode Aplikasi Saat
                                            Ini:</label>
                                        <textarea name="isi_kode" id="textareaKodeEdit{{ $m->id }}" rows="7"
                                            class="form-control radius-8 font-monospace text-xs bg-dark text-white">{{ $m->isi_kode }}</textarea>
                                    </div>
                                </div>

                                <!-- Box Link Edit -->
                                <div class="col-12 {{ $m->tipe_sumber === 'link' ? '' : 'd-none' }}"
                                    id="boxLinkEdit{{ $m->id }}">
                                    <label class="form-label fw-bold text-xs mb-4">🔗 Link Website Eksternal:</label>
                                    <input type="text" name="link_eksternal"
                                        class="form-control radius-8 font-monospace text-xs"
                                        value="{{ $m->link_eksternal }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">Warna Gradasi Kartu</label>
                                    <select name="warna_gradasi" class="form-select radius-8">
                                        @foreach ($daftarGradasi as $gClass => $gNama)
                                            <option value="{{ $gClass }}"
                                                {{ $m->warna_gradasi === $gClass ? 'selected' : '' }}>{{ $gNama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-xs mb-4">Ganti Ikon / Sampul (Opsional)</label>
                                    <input type="file" name="gambar" class="form-control form-control-sm radius-8"
                                        accept="image/*">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold text-xs mb-4">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" rows="2" class="form-control radius-8 text-xs">{{ $m->deskripsi }}</textarea>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" role="switch" name="is_active"
                                            id="chkEditMedia{{ $m->id }}" {{ $m->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label text-xs fw-semibold"
                                            for="chkEditMedia{{ $m->id }}">✓ Tampilkan Media Ini</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function toggleSumberMedia(suffix, nilai) {
            var boxKode = document.getElementById('boxKode' + suffix);
            var boxLink = document.getElementById('boxLink' + suffix);
            if (!boxKode) return;
            if (!boxLink) return;

            if (nilai === 'kode_interaktif') {
                boxKode.classList.remove('d-none');
                boxLink.classList.add('d-none');
            } else {
                boxKode.classList.add('d-none');
                boxLink.classList.remove('d-none');
            }
        }

        function bacaFileKodeKeEditor(input, targetTextareaId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var area = document.getElementById(targetTextareaId);
                    if (area) {
                        area.value = e.target.result;
                    }
                };
                reader.readAsText(input.files[0]);
            }
        }

        function konfirmasiHapusMedia(url, judul) {
            Swal.fire({
                title: 'Hapus Media Pembelajaran?',
                text: '"' + judul + '" akan dihapus dari Dunia Ilmu MIN 1 Ciamis.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(res) {
                if (res.isConfirmed) {
                    var f = document.getElementById('formDeleteMediaGlobal');
                    f.action = url;
                    f.submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    timer: 2200,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Perhatian!',
                    text: @json(session('error'))
                });
            });
        </script>
    @endif

@endsection
