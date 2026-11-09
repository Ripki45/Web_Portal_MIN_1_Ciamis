@extends('layouts.admin')

@section('title', 'Edit Halaman: ' . $halaman->judul)

@section('content')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Edit Konten Halaman: {{ $halaman->judul }}</h6>
            <span class="text-xs text-secondary-light">
                Link Halaman: <code>/halaman/{{ $halaman->slug }}</code>
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('halaman.show', $halaman->slug) }}" target="_blank"
                class="btn btn-info-600 radius-8 px-14 py-8 text-xs fw-bold text-white">
                Lihat Hasil Halaman &#8599;
            </a>
            <a href="{{ route('nav-menu.index') }}" class="btn btn-secondary radius-8 px-14 py-8 text-xs fw-bold">
                &larr; Kembali ke Menu Navbar
            </a>
        </div>
    </div>

    <div class="card radius-12 border shadow-sm">
        <form action="{{ route('halaman.update', $halaman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body p-24">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-sm mb-6">Judul Halaman <span
                                class="text-danger-600">*</span></label>
                        <input type="text" name="judul" class="form-control radius-8 fw-bold"
                            value="{{ $halaman->judul }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-sm mb-6">Label Kategori</label>
                        <input type="text" name="kategori" class="form-control radius-8"
                            value="{{ $halaman->kategori }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-sm mb-6">Tautkan ke Menu Navbar</label>
                        <select name="nav_menu_id" class="form-select radius-8 fw-semibold border-success">
                            <option value="">-- Tidak Ditautkan --</option>
                            @foreach ($menus as $m)
                                <option value="{{ $m->id }}" {{ $halaman->nav_menu_id == $m->id ? 'selected' : '' }}>
                                    [Level {{ $m->level }}] {{ $m->nama_menu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold text-sm mb-6">Kalimat Sorotan / Kutipan Visi (Blockquote)</label>
                        <textarea name="kutipan" rows="2" class="form-control radius-8"
                            placeholder="Kalimat penting yang ingin ditonjolkan dengan huruf besar...">{{ $halaman->kutipan }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-sm mb-6">Ganti Gambar Sampul (Opsional)</label>
                        <input type="file" name="gambar" class="form-control radius-8" accept="image/*">
                        @if ($halaman->gambar)
                            <small class="text-xs text-success-600 d-block mt-4">✓ Gambar sampul sudah terpasang</small>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-sm mb-6">Isi Lengkap Halaman <span
                                class="text-danger-600">*</span></label>
                        <textarea name="isi_konten" rows="12" class="form-control radius-8" required>{{ $halaman->isi_konten }}</textarea>
                        <small class="text-xs text-secondary-light d-block mt-4">
                            Tips: Gunakan tag <code>&lt;h4&gt;Sub Judul&lt;/h4&gt;</code> untuk sub-bab,
                            <code>&lt;p&gt;Paragraf&lt;/p&gt;</code> untuk teks, dan
                            <code>&lt;ul&gt;&lt;li&gt;Poin&lt;/li&gt;&lt;/ul&gt;</code> untuk daftar poin seperti di desain
                            Magzin.
                        </small>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch switch-success d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="chkAktif"
                                {{ $halaman->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-sm" for="chkAktif">Tampilkan Halaman Ini Secara
                                Publik</label>
                        </div>
                    </div>
                </div>

                <div class="mt-24 pt-16 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('halaman.index') }}" class="btn btn-secondary radius-8 px-20">Daftar Semua
                        Halaman</a>
                    <button type="submit" class="btn btn-primary-600 radius-8 px-24 fw-bold">
                        Simpan Perubahan Halaman
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection
