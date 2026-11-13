@extends('layouts.admin')

@php
    $isEdit = !is_null($berita);
    \App\Models\KategoriBerita::pastikanTabelSiap();
    $kategoris = $kategoris ?? \App\Models\KategoriBerita::orderBy('nama_kategori', 'asc')->get();
    $daftarModelAi = $daftarModelAi ?? \App\Http\Controllers\BeritaController::daftarModelAi();
    $katAktif = old('kategori', $berita->kategori ?? 'Kegiatan Siswa');
@endphp

@section('title',
    ($isEdit ? 'Edit Artikel: ' . $berita->judul : 'Studio Penulisan Artikel + Gemini AI') .
    ' - MIN 1
    Ciamis')

@section('content')

    <!-- Quill 2.0 Snow CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

    <style>
        .gemini-studio-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #14532d 100%);
            border-radius: 16px;
            color: #ffffff;
            position: relative;
        }

        .gemini-badge-pill {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #38bdf8;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-gemini-magic {
            background: linear-gradient(90deg, #2563eb 0%, #7c3aed 50%, #16a34a 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-gemini-magic:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            color: #fff;
            box-shadow: 0 8px 20px rgba(124, 58, 237, 0.35);
        }

        .ql-toolbar.ql-snow {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background: #f8fafc;
            border-color: #cbd5e1;
            padding: 10px 14px;
        }

        .ql-container.ql-snow {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border-color: #cbd5e1;
            font-family: inherit;
            font-size: 15px;
            min-height: 360px;
            background: #ffffff;
        }

        .ql-editor {
            min-height: 360px;
            line-height: 1.8;
            color: #1e293b;
        }

        .preview-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
            background: #f8fafc;
        }
    </style>

    <!-- Header Studio -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">
                {{ $isEdit ? '✎ Edit Artikel Berita' : '✨ Studio Penulisan Berita & Artikel Baru' }}
            </h6>
            <span class="text-xs text-secondary-light">
                Gunakan <b>Asisten Gemini AI</b> untuk menyusun draf berita otomatis atau tulis langsung di editor visual
            </span>
        </div>
        <a href="{{ route('berita.index') }}" class="btn btn-secondary radius-8 px-16 py-8 text-xs fw-bold">
            &larr; Kembali ke Daftar Berita
        </a>
    </div>

    <!-- ========================================================================= -->
    <!-- PANEL ASISTEN PENULIS GEMINI AI                                           -->
    <!-- ========================================================================= -->
    <div class="gemini-studio-card p-24 mb-24 shadow">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-12">
            <span class="gemini-badge-pill">
                ✦ Powered by Google Gemini AI & Smart Studio — Asisten Jurnalistik MIN 1 Ciamis
            </span>
            <button type="button" class="btn btn-sm text-white text-xs opacity-75 p-0 border-0 bg-transparent"
                onclick="toggleKotakApiKey()">
                ⚙️ Masukkan Gemini API Key Manual (Opsional)
            </button>
        </div>

        <h5 class="text-white fw-bold mb-1">Buat Draf Berita Otomatis dalam Hitungan Detik</h5>
        <p class="text-xs text-light opacity-75 mb-16">
            Ketikkan topik atau poin kegiatan di bawah ini, lalu klik tombol <b>Generate dengan Gemini</b> (Bisa langsung
            digunakan meskipun belum mengisi API Key).
        </p>

        <div class="row g-2 align-items-end">
            <div class="col-lg-5">
                <label class="text-xs fw-semibold text-light mb-4 d-block">1. Topik / Poin Kegiatan Berita</label>
                <input type="text" id="inputTopikAi" class="form-control radius-8 bg-white text-dark fw-semibold"
                    placeholder="Contoh: Kegiatan Shalat Dhuha bersama di halaman madrasah...">
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="text-xs fw-semibold text-warning mb-4 d-block">2. Pilih Mesin AI</label>
                <select id="selectEngineAi" class="form-select radius-8 bg-white text-dark fw-bold border-warning">
                    @foreach ($daftarModelAi as $kodeModel => $labelModel)
                        <option value="{{ $kodeModel }}">{{ $labelModel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="text-xs fw-semibold text-light mb-4 d-block">3. Gaya Bahasa</label>
                <select id="selectNadaAi" class="form-select radius-8 bg-white text-dark fw-semibold">
                    <option value="Jurnalistik Formal & Inspiratif">Formal & Inspiratif</option>
                    <option value="Hangat, Edukatif & Ramah Keluarga">Hangat & Edukatif</option>
                    <option value="Semangat Prestasi & Motivasi">Semangat Prestasi</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <button type="button" id="btnGenerateAi" onclick="jalankanGeminiAi()"
                    class="btn btn-gemini-magic w-100 radius-8 py-10 d-flex align-items-center justify-content-center gap-2">
                    <span id="teksBtnAi">✨ Buat Draf</span>
                </button>
            </div>
        </div>

        <!-- Kotak Opsional API Key -->
        <div id="boxApiKeyOpsional" class="mt-12 pt-12 border-top border-secondary d-none">
            <label class="text-xs text-warning fw-semibold mb-4 d-block">
                🔑 Google AI Studio API Key (Kosongkan jika sudah diisi di <code>.env</code>):
            </label>
            <input type="password" id="inputApiKeyAi" autocomplete="off"
                class="form-control form-control-sm radius-8 bg-dark text-white border-secondary"
                placeholder="Masukkan API Key (Disensor otomatis)...">
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- FORM UTAMA STUDIO PENULISAN ARTIKEL                                       -->
    <!-- ========================================================================= -->
    <form action="{{ $isEdit ? route('berita.update', $berita->id) : route('berita.store') }}" method="POST"
        enctype="multipart/form-data" id="formStudioBerita" onsubmit="sinkronkanQuillKeTextarea()">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="row gy-4">
            <!-- KOLOM KIRI (8 KOLOM): EDITOR ARTIKEL -->
            <div class="col-lg-8">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-body p-24">

                        <!-- Judul Berita -->
                        <div class="mb-12">
                            <label class="form-label fw-bold text-sm mb-6">
                                Judul Berita / Artikel <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="judul" id="inputJudul"
                                class="form-control form-control-lg radius-8 fw-bold"
                                value="{{ old('judul', $berita->judul ?? '') }}" oninput="buatSlugJudul(this.value)"
                                placeholder="Ketik judul berita yang menarik perhatian pembaca..." required>
                        </div>

                        <!-- Slug URL Otomatis -->
                        <div class="mb-20">
                            <label class="form-label fw-bold text-xs text-secondary-light mb-4">
                                🔗 Slug URL Berita (Terbentuk Otomatis dari Judul / Bisa Diedit)
                            </label>
                            <div class="input-group input-group-sm">
                                <span
                                    class="input-group-text bg-neutral-100 text-secondary-light font-monospace text-xs">/berita/</span>
                                <input type="text" name="slug" id="inputSlugBerita"
                                    class="form-control radius-8 font-monospace text-xs"
                                    value="{{ old('slug', $berita->slug ?? '') }}" placeholder="judul-berita-otomatis">
                            </div>
                        </div>

                        <!-- Kutipan Sorotan / Blockquote -->
                        <div class="mb-20 p-16 radius-12 bg-neutral-50 border">
                            <label class="form-label fw-bold text-xs text-primary-600 mb-6 d-flex align-items-center gap-1">
                                💬 Kalimat Kutipan Sorotan / Blockquote (Opsional — Tampil Besar di Atas Artikel)
                            </label>
                            <textarea name="kutipan" id="inputKutipan" rows="2" class="form-control radius-8 text-sm fst-italic"
                                placeholder="Contoh: Membaca adalah jendela dunia; melalui literasi pagi kita tumbuhkan generasi pembelajar...">{{ old('kutipan', $berita->kutipan ?? '') }}</textarea>
                        </div>

                        <!-- Rich Text Editor Quill.js -->
                        <div class="mb-16">
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <label class="form-label fw-bold text-sm mb-0">
                                    Isi Lengkap Berita <span class="text-danger-600">*</span>
                                </label>
                                <span id="indikatorKata"
                                    class="bg-primary-50 text-primary-600 px-10 py-4 rounded-pill text-xs fw-bold d-inline-block">
                                    0 kata (~1 menit baca)
                                </span>
                            </div>

                            <!-- Wadah Visual Quill Editor -->
                            <div id="quillEditor">{!! old('isi_konten', $berita->isi_konten ?? '') !!}</div>

                            <!-- Textarea Tersembunyi yang Dikirim ke Server Laravel -->
                            <textarea name="isi_konten" id="textareaIsiKonten" class="d-none">{{ old('isi_konten', $berita->isi_konten ?? '') }}</textarea>
                        </div>

                        <!-- Ringkasan Singkat -->
                        <div class="mb-0">
                            <label class="form-label fw-bold text-sm mb-6">
                                Ringkasan Singkat (Tampil di Kartu Halaman Depan)
                            </label>
                            <textarea name="ringkasan" id="inputRingkasan" rows="2" class="form-control radius-8 text-sm"
                                placeholder="Jika dikosongkan, sistem otomatis mengambil 2 kalimat pertama dari isi berita...">{{ old('ringkasan', $berita->ringkasan ?? '') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (4 KOLOM): PENGATURAN PUBLIKASI, GAMBAR SAMPUL & TAGS -->
            <div class="col-lg-4">

                <div class="card radius-12 border shadow-sm mb-20">
                    <div class="card-header border-bottom bg-base py-14 px-20">
                        <h6 class="text-sm fw-bold mb-0">Pengaturan Publikasi</h6>
                    </div>
                    <div class="card-body p-20">

                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">Status Publikasi</label>
                            <select name="status" class="form-select radius-8 fw-bold border-success">
                                <option value="Tayang"
                                    {{ old('status', $berita->status ?? 'Tayang') === 'Tayang' ? 'selected' : '' }}>
                                    🟢 Tayang (Langsung Terbit di Website)
                                </option>
                                <option value="Draft"
                                    {{ old('status', $berita->status ?? '') === 'Draft' ? 'selected' : '' }}>
                                    🟡 Simpan Sebagai Draft Dulu
                                </option>
                            </select>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">Kategori Berita <span
                                    class="text-danger-600">*</span></label>
                            <select name="kategori" id="selectKategori" class="form-select radius-8 fw-semibold">
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat->nama_kategori }}"
                                        {{ $katAktif === $kat->nama_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-sm mb-6">Nama Penulis / Redaksi</label>
                            <input type="text" name="penulis" class="form-control radius-8 text-sm fw-semibold"
                                value="{{ old('penulis', $berita->penulis ?? (auth()->user()->name ?? 'Tim Humas MIN 1 Ciamis')) }}">
                        </div>

                        <div class="mb-16">
                            <label class="form-label fw-bold text-sm mb-6">Tags / Kata Kunci</label>
                            <input type="text" name="tags" id="inputTags" class="form-control radius-8 text-xs"
                                value="{{ old('tags', $berita->tags ?? 'MIN 1 Ciamis, Kegiatan Siswa') }}"
                                placeholder="Pisahkan dengan koma (misal: Pramuka, Juara, Literasi)">
                        </div>

                        <div class="p-12 radius-8 bg-warning-focus border border-warning mb-20">
                            <div class="form-check form-switch switch-warning d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_featured"
                                    id="chkFeatured"
                                    {{ old('is_featured', $berita->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label text-xs fw-bold text-warning-main" for="chkFeatured">
                                    ★ Jadikan Berita Utama (Kartu Besar di Kiri)
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-primary-600 w-100 radius-8 py-12 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <i class="ph-bold ph-paper-plane-tilt text-lg"></i>
                            {{ $isEdit ? 'Simpan Perubahan Artikel' : 'Terbitkan Artikel Sekarang' }}
                        </button>
                    </div>
                </div>

                <!-- Kartu Gambar Sampul (Thumbnail) -->
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-14 px-20">
                        <h6 class="text-sm fw-bold mb-0">Foto Sampul Berita (Thumbnail)</h6>
                    </div>
                    <div class="card-body p-20">
                        <div class="preview-dropzone mb-12">
                            <img id="imgPreviewSampul"
                                src="{{ $isEdit ? $berita->gambar_url : asset('frontend/assets/imgs/page/img-4.png') }}"
                                alt="Preview Sampul" class="w-100 radius-8 mb-10"
                                style="height: 190px; object-fit: cover;">
                            <input type="file" name="gambar" id="inputFileSampul"
                                class="form-control form-control-sm radius-8" accept="image/*"
                                onchange="pratinjauGambar(this)">
                        </div>
                        <small class="text-xs text-secondary-light d-block">
                            Format JPG, PNG, atau WEBP (Maksimal 3 MB). Disarankan orientasi mendatar (landscape).
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- Script diletakkan langsung di dalam section content agar dijamin 100% dieksekusi oleh browser -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        var quill = new Quill('#quillEditor', {
            theme: 'snow',
            placeholder: 'Mulai menulis isi berita di sini, atau gunakan tombol Generate dengan Gemini di atas...',
            modules: {
                toolbar: [
                    [{
                        'header': [2, 3, 4, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    ['blockquote', 'link'],
                    ['clean']
                ]
            }
        });

        function toggleKotakApiKey() {
            var box = document.getElementById('boxApiKeyOpsional');
            if (box) {
                box.classList.toggle('d-none');
            }
        }

        function buatSlugJudul(teks) {
            var slug = String(teks).toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            var el = document.getElementById('inputSlugBerita');
            if (el) {
                el.value = slug;
            }
        }

        function updateStatistikKata() {
            var text = quill.getText().trim();
            var words = text.length > 0 ? text.split(/\s+/).length : 0;
            var mins = Math.max(1, Math.ceil(words / 180));
            var ind = document.getElementById('indikatorKata');
            if (ind) {
                ind.innerText = words + ' kata (~' + mins + ' menit baca)';
            }
            var txtArea = document.getElementById('textareaIsiKonten');
            if (txtArea) {
                txtArea.value = quill.root.innerHTML;
            }
        }

        quill.on('text-change', updateStatistikKata);
        updateStatistikKata();

        function sinkronkanQuillKeTextarea() {
            document.getElementById('textareaIsiKonten').value = quill.root.innerHTML;
        }

        function pratinjauGambar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgPreviewSampul').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function terapkanDataKeForm(data) {
            var judulBaru = data.judul ? data.judul : '';
            document.getElementById('inputJudul').value = judulBaru;
            document.getElementById('inputRingkasan').value = data.ringkasan ? data.ringkasan : '';
            document.getElementById('inputKutipan').value = data.kutipan ? data.kutipan : '';
            document.getElementById('inputTags').value = data.tags ? data.tags : '';

            if (data.slug) {
                document.getElementById('inputSlugBerita').value = data.slug;
            } else {
                buatSlugJudul(judulBaru);
            }

            quill.root.innerHTML = data.isi_konten ? data.isi_konten : '';
            updateStatistikKata();
        }

        function jalankanGeminiAi() {
            var inputTopik = document.getElementById('inputTopikAi');
            var topik = inputTopik ? inputTopik.value.trim() : '';
            var kategori = document.getElementById('selectKategori').value;
            var nada = document.getElementById('selectNadaAi').value;
            var engineEl = document.getElementById('selectEngineAi');
            var engine = engineEl ? engineEl.value : 'gemini-2.5-flash';
            var inputKey = document.getElementById('inputApiKeyAi');
            var apiKey = inputKey ? inputKey.value.trim() : '';
            var btn = document.getElementById('btnGenerateAi');
            var teksBtn = document.getElementById('teksBtnAi');

            if (topik.length < 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Topik Masih Kosong!',
                    text: 'Ketikkan minimal beberapa kata mengenai kegiatan yang ingin dibuatkan beritanya.'
                });
                if (inputTopik) inputTopik.focus();
                return;
            }

            btn.disabled = true;
            teksBtn.innerHTML = '⏳ Memproses...';

            fetch("{{ route('berita.generateAi') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        topik: topik,
                        kategori: kategori,
                        nada: nada,
                        engine: engine,
                        api_key: apiKey
                    })
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(res) {
                    btn.disabled = false;
                    teksBtn.innerHTML = '✨ Buat Draf';

                    if (res.status === 'success' && res.data) {
                        terapkanDataKeForm(res.data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Draf Artikel Selesai Dibuat!',
                            text: 'Disusun otomatis oleh ' + (res.source ? res.source : 'AI Studio') + '.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else if (res.status === 'error_gemini') {
                        Swal.fire({
                            icon: 'warning',
                            title: res.error_title ? res.error_title : 'Koneksi Gemini API Bermasalah',
                            html: '<div class="text-start text-xs bg-light p-2 rounded border mb-2"><b>Pesan Server:</b><br>' +
                                res.message + '</div>' +
                                'Apakah Anda ingin tetap mengisi form menggunakan <b>Smart AI Studio Lokal</b>?',
                            showCancelButton: true,
                            confirmButtonColor: '#16a34a',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: '⚡ Ya, Gunakan Smart AI Lokal',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            if (result.isConfirmed && res.backup) {
                                terapkanDataKeForm(res.backup);
                            }
                        });
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    teksBtn.innerHTML = '✨ Buat Draf';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memproses',
                        text: 'Periksa koneksi lokal Anda lalu coba lagi.'
                    });
                });
        }
    </script>
@endsection
