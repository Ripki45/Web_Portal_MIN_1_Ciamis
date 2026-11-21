@extends('layouts.admin')

@section('title', 'Edit Program Unggulan - ' . $program->judul)

@section('content')

    <!-- Font Awesome 6.5.1 untuk Preview Ikon Nyata di Form -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Quill 2.0 Snow CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

    <!-- SweetAlert2 untuk Notifikasi AI yang Elegan -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .icon-preview-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #ecfdf5;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .icon-palette-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .icon-palette-btn:hover {
            background: #0D6A56;
            color: #ffffff;
            border-color: #0D6A56;
            transform: scale(1.1);
        }
        .image-preview-container {
            max-width: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px dashed #cbd5e1;
            padding: 6px;
            background: #f8fafc;
        }
        .image-preview-container img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .section-header-box {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }
        .section-number-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #0D6A56;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pilar-card-item, .kegiatan-card-item {
            background: #fafcfb;
            border: 1px solid #e2e8f0;
            border-top: 3px solid #0D6A56;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
        }
        .pilar-card-item:hover, .kegiatan-card-item:hover {
            border-color: #0D6A56;
            box-shadow: 0 4px 14px rgba(13, 106, 86, 0.08);
        }
        .ql-toolbar.ql-snow {
            border-radius: 10px 10px 0 0;
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .ql-container.ql-snow {
            border-radius: 0 0 10px 10px;
            border-color: #cbd5e1;
            min-height: 180px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        /* GEMINI AI STUDIO CARD */
        .gemini-studio-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #14532d 100%);
            border-radius: 14px;
            color: #ffffff;
            position: relative;
        }
        .gemini-badge-pill {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #38bdf8;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
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
            box-shadow: 0 6px 18px rgba(124, 58, 237, 0.35);
        }
    </style>

    <!-- Header Form -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">✎ Edit Program Unggulan: {{ $program->judul }}</h6>
            <span class="text-xs text-secondary-light">
                Perbarui data <b>Section 1 (Hero + Smart AI Quill)</b>, <b>Section 2 (4 Pilar)</b>, dan <b>Section 3 (Grid 6 Aktivitas &amp; Edukasi)</b>.
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('program-unggulan.detail', $program->slug) }}" target="_blank"
                class="btn btn-outline-success radius-8 px-14 py-8 text-xs fw-bold">
                <i class="fas fa-external-link-alt"></i> Pratinjau Halaman Publik &#8599;
            </a>
            <a href="{{ route('program-unggulan.index') }}" class="btn btn-secondary radius-8 px-14 py-8 text-xs fw-bold">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show radius-8 mb-20" role="alert">
            <strong class="text-sm d-block mb-1">Terjadi kesalahan input:</strong>
            <ul class="mb-0 text-xs ps-18">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('program-unggulan.update', $program->id) }}" method="POST" enctype="multipart/form-data" id="formProgramUnggulan" onsubmit="sinkronkanQuillKeTextarea()">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- =========================================================================
                 KOLOM KIRI (8 KOLOM): 3 SECTION KONTEN HALAMAN DETAIL
                 ========================================================================= -->
            <div class="col-lg-8">

                <!-- 1. SECTION 1: IDENTITAS & HERO PROGRAM + SMART AI STUDIO -->
                <div class="card radius-12 border shadow-sm mb-20">
                    <div class="card-body p-24">
                        <div class="section-header-box">
                            <span class="section-number-badge">1</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Section 1: Identitas &amp; Hero Program</h6>
                                <span class="text-xs text-secondary-light">Nama program, badge kategori, ikon, blockquote komitmen, serta narasi dengan Smart AI &amp; Quill.js.</span>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Nama Program -->
                            <div class="col-md-7">
                                <label class="form-label fw-bold text-xs mb-4">
                                    Nama Program Unggulan <span class="text-danger-600">*</span>
                                </label>
                                <input type="text" name="judul" id="inputJudulProgram" class="form-control radius-8 fw-bold"
                                    value="{{ old('judul', $program->judul) }}" required oninput="sinkronkanTopikAi(this.value)">
                            </div>

                            <!-- Label Kategori / Badge -->
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-xs mb-4">
                                    Label Kategori / Badge <span class="text-danger-600">*</span>
                                </label>
                                <input type="text" name="kategori_badge" id="inputKategoriBadge" class="form-control radius-8"
                                    value="{{ old('kategori_badge', $program->kategori_badge) }}" required>
                            </div>

                            <!-- Ikon Font Awesome & Picker -->
                            <div class="col-12">
                                <div class="p-14 radius-10 bg-primary-50 border border-primary-200">
                                    <label class="form-label fw-bold text-xs mb-4 text-primary-900">
                                        Ikon Program (Font Awesome 6) <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="d-flex align-items-center gap-3 mb-8">
                                        <div class="icon-preview-box" id="boxIconPreview">
                                            <i class="{{ old('icon', $program->icon) }}" id="iconPreviewTag"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="text" name="icon" id="inputIconProgram"
                                                class="form-control radius-8 fw-semibold"
                                                value="{{ old('icon', $program->icon) }}" required
                                                oninput="updatePreviewIcon(this.value)">
                                            <small class="text-xs text-secondary-light">
                                                Ketik nama class Font Awesome atau klik palet rekomendasi di bawah:
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Palet Ikon Rekomendasi -->
                                    <div class="d-flex flex-wrap gap-2 pt-6 border-top border-primary-100">
                                        @foreach ($iconRekomendasi as $cls => $label)
                                            <button type="button" class="icon-palette-btn" title="{{ $label }} ({{ $cls }})"
                                                onclick="pilihIcon('{{ $cls }}')">
                                                <i class="{{ $cls }}"></i>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Blockquote Komitmen -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-xs mb-4">
                                    💬 Blockquote Komitmen Program (Kutipan Sorotan Bergaris Hijau)
                                </label>
                                <textarea name="kutipan" id="inputKutipan" rows="2" class="form-control radius-8 fst-italic"
                                    placeholder="Contoh: Membentuk madrasah yang tidak hanya mencetak insan berprestasi, tetapi juga menjadi rumah kedua yang hangat, aman, dan penuh kasih sayang...">{{ old('kutipan', $program->kutipan) }}</textarea>
                                <small class="text-xs text-secondary-light d-block mt-1">
                                    Akan ditampilkan dengan gaya blockquote tebal bergaris emerald di Section 1.
                                </small>
                            </div>

                            <!-- ========================================================================= -->
                            <!-- PANEL ASISTEN PENULIS SMART AI + LARAVEL SDK                              -->
                            <!-- ========================================================================= -->
                            <div class="col-12">
                                <div class="gemini-studio-card p-18 radius-12 border shadow-sm mb-2">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-10">
                                        <span class="gemini-badge-pill">
                                            ✦ Powered by Google Gemini &amp; Laravel AI SDK — Asisten Program MIN 1 Ciamis
                                        </span>
                                        <button type="button" class="btn btn-sm text-white text-xs opacity-75 p-0 border-0 bg-transparent"
                                            onclick="toggleKotakApiKey()">
                                            ⚙️ API Key Manual (Opsional)
                                        </button>
                                    </div>

                                    <div class="row g-2 align-items-end">
                                        <div class="col-lg-5">
                                            <label class="text-xs fw-semibold text-light mb-4 d-block">1. Topik / Fokus Narasi Program</label>
                                            <input type="text" id="inputTopikAi" class="form-control form-control-sm radius-8 bg-white text-dark fw-semibold"
                                                value="{{ $program->judul }}" placeholder="Topik atau fokus program...">
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <label class="text-xs fw-semibold text-warning mb-4 d-block">2. Pilihan Mesin AI</label>
                                            <select id="selectEngineAi" class="form-select form-select-sm radius-8 bg-white text-dark fw-bold border-warning">
                                                @foreach ($daftarModelAi as $kodeModel => $labelModel)
                                                    <option value="{{ $kodeModel }}">{{ $labelModel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4">
                                            <label class="text-xs fw-semibold text-light mb-4 d-block">3. Gaya Bahasa</label>
                                            <select id="selectNadaAi" class="form-select form-select-sm radius-8 bg-white text-dark fw-semibold">
                                                <option value="Formal, Edukatif, dan Inspiratif">Formal &amp; Edukatif</option>
                                                <option value="Hangat, Mengayomi & Ramah Keluarga">Hangat &amp; Mengayomi</option>
                                                <option value="Semangat Berprestasi & Religius">Semangat Prestasi</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-4">
                                            <button type="button" id="btnGenerateAi" onclick="jalankanAiProgram()"
                                                class="btn btn-gemini-magic btn-sm w-100 radius-8 py-8 d-flex align-items-center justify-content-center gap-2">
                                                <span id="teksBtnAi">✨ Susun Narasi</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Kotak Opsional API Key -->
                                    <div id="boxApiKeyOpsional" class="mt-10 pt-10 border-top border-secondary d-none">
                                        <label class="text-xs text-warning fw-semibold mb-2 d-block">
                                            🔑 Google AI Studio API Key (Kosongkan jika sudah diatur di <code>.env</code>):
                                        </label>
                                        <input type="password" id="inputApiKeyAi" autocomplete="off"
                                            class="form-control form-control-sm radius-8 bg-dark text-white border-secondary"
                                            placeholder="Masukkan API Key manual...">
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan / Narasi Pengantar dengan Editor Visual Quill.js -->
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <label class="form-label fw-bold text-xs mb-0">
                                        Keterangan / Narasi Pengantar Program (Quill.js Rich Visual Editor)
                                    </label>
                                    <span id="indikatorKataRingkasan" class="badge bg-success-subtle text-success-main text-2xs fw-bold">
                                        0 kata
                                    </span>
                                </div>

                                <!-- Container Quill untuk Ringkasan -->
                                <div id="quillRingkasan">{!! old('ringkasan', $program->ringkasan ?: '<p>Melalui program unggulan madrasah, kami menciptakan iklim belajar yang aman, nyaman, berkualitas tinggi, serta mendukung pembentukan akhlak mulia dan kecakapan masa depan santri.</p>') !!}</div>

                                <!-- Textarea tersembunyi yang disinkronkan saat submit -->
                                <textarea name="ringkasan" id="textareaRingkasan" class="d-none">{{ old('ringkasan', $program->ringkasan) }}</textarea>
                                <small class="text-xs text-secondary-light d-block mt-1">
                                    Dapat diedit langsung atau disusun otomatis secara instan melalui Asisten AI di atas.
                                </small>
                            </div>

                            <!-- 2 Kolom Mini Feature Cards -->
                            @php
                                $miniCards = $program->mini_features_list;
                            @endphp
                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold text-xs mb-6 d-block text-dark">
                                    Card untuk 2 Kolom (Poin Keunggulan Cepat di Bawah Narasi)
                                </label>
                                <div class="row g-3">
                                    <!-- Mini Card 1 -->
                                    <div class="col-md-6">
                                        <div class="p-14 radius-10 bg-light border">
                                            <span class="badge bg-success-subtle text-success-main text-2xs fw-bold mb-2">Mini Card 1</span>
                                            <div class="mb-2">
                                                <label class="text-2xs fw-bold text-secondary-light">Ikon Font Awesome:</label>
                                                <input type="text" name="mini_feature_icon[]" class="form-control form-control-sm radius-6"
                                                    value="{{ old('mini_feature_icon.0', $miniCards[0]['icon'] ?? 'fas fa-shield-alt') }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="text-2xs fw-bold text-secondary-light">Judul Singkat:</label>
                                                <input type="text" name="mini_feature_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                                    value="{{ old('mini_feature_title.0', $miniCards[0]['title'] ?? 'Aman & Anti-Bullying') }}">
                                            </div>
                                            <div>
                                                <label class="text-2xs fw-bold text-secondary-light">Keterangan:</label>
                                                <input type="text" name="mini_feature_desc[]" class="form-control form-control-sm radius-6"
                                                    value="{{ old('mini_feature_desc.0', $miniCards[0]['desc'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mini Card 2 -->
                                    <div class="col-md-6">
                                        <div class="p-14 radius-10 bg-light border">
                                            <span class="badge bg-success-subtle text-success-main text-2xs fw-bold mb-2">Mini Card 2</span>
                                            <div class="mb-2">
                                                <label class="text-2xs fw-bold text-secondary-light">Ikon Font Awesome:</label>
                                                <input type="text" name="mini_feature_icon[]" class="form-control form-control-sm radius-6"
                                                    value="{{ old('mini_feature_icon.1', $miniCards[1]['icon'] ?? 'fas fa-hands-holding-child') }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="text-2xs fw-bold text-secondary-light">Judul Singkat:</label>
                                                <input type="text" name="mini_feature_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                                    value="{{ old('mini_feature_title.1', $miniCards[1]['title'] ?? 'Sinergi Parenting Day') }}">
                                            </div>
                                            <div>
                                                <label class="text-2xs fw-bold text-secondary-light">Keterangan:</label>
                                                <input type="text" name="mini_feature_desc[]" class="form-control form-control-sm radius-6"
                                                    value="{{ old('mini_feature_desc.1', $miniCards[1]['desc'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Poin Ceklis Tambahan -->
                            @php
                                $poinText = is_array($program->keunggulan_poin) ? implode("\n", $program->keunggulan_poin) : $program->keunggulan_poin;
                            @endphp
                            <div class="col-12">
                                <label class="form-label fw-bold text-xs mb-4">
                                    Poin-Poin Ceklis Tambahan <span class="text-secondary-light font-normal">(1 Poin per Baris)</span>
                                </label>
                                <textarea name="keunggulan_poin" rows="3" class="form-control radius-8 font-monospace text-xs"
                                    placeholder="Layanan Psikolog Khusus Anak&#10;Penataan Ruang Kelas Inspiratif&#10;Pembelajaran Aktif & Bebas Bullying">{{ old('keunggulan_poin', $poinText) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. SECTION 2: 4 PILAR UTAMA PROGRAM -->
                <div class="card radius-12 border shadow-sm mb-20">
                    <div class="card-body p-24">
                        <div class="section-header-box">
                            <span class="section-number-badge">2</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Section 2: 4 Pilar Program Unggulan</h6>
                                <span class="text-xs text-secondary-light">Aksara 4 pilar, ikon, judul, dan deskripsi pilar (identik model min1kotamalang).</span>
                            </div>
                        </div>

                        <div class="row g-3">
                            @php
                                $pilars = $program->pilar_list_formatted;
                            @endphp

                            @for ($p = 0; $p < 4; $p++)
                                <div class="col-md-6">
                                    <div class="pilar-card-item">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-success text-white text-2xs fw-bold">Pilar {{ $p + 1 }}</span>
                                            <small class="text-secondary-light text-2xs font-monospace">Slot 0{{ $p + 1 }}</small>
                                        </div>
                                        <div class="mb-2">
                                            <label class="text-2xs fw-bold text-secondary-light">Ikon Font Awesome:</label>
                                            <input type="text" name="pilar_icon[]" class="form-control form-control-sm radius-6"
                                                value="{{ old('pilar_icon.'.$p, $pilars[$p]['icon'] ?? 'fas fa-award') }}" placeholder="fas fa-check-circle">
                                        </div>
                                        <div class="mb-2">
                                            <label class="text-2xs fw-bold text-secondary-light">Judul Pilar:</label>
                                            <input type="text" name="pilar_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                                value="{{ old('pilar_title.'.$p, $pilars[$p]['title'] ?? 'Pilar '.($p + 1)) }}" placeholder="Judul Pilar...">
                                        </div>
                                        <div>
                                            <label class="text-2xs fw-bold text-secondary-light">Deskripsi Pilar:</label>
                                            <textarea name="pilar_desc[]" rows="2" class="form-control form-control-sm radius-6"
                                                placeholder="Deskripsi pilar...">{{ old('pilar_desc.'.$p, $pilars[$p]['desc'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- 3. SECTION 3: AKTIVITAS & EDUKASI (GRID 6 KARTU KEGIATAN SESUAI TAMPILAN FRONTEND) -->
                <div class="card radius-12 border shadow-sm">
                    <div class="card-body p-24">
                        <div class="section-header-box">
                            <span class="section-number-badge">3</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Section 3: Grid 6 Aktivitas &amp; Edukasi Kegiatan</h6>
                                <span class="text-xs text-secondary-light">Atur judul section, subtitle, dan 6 kartu kegiatan berbadge (3 kolom x 2 baris) sesuai tampilan min1kotamalang.</span>
                            </div>
                        </div>

                        <!-- Pengaturan Header Section 3 -->
                        <div class="row g-3 mb-4 p-14 radius-10 bg-light border">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-xs mb-4">
                                    Badge Subtitle Section 3
                                </label>
                                <input type="text" name="kegiatan_badge" class="form-control radius-8 fw-semibold"
                                    value="{{ old('kegiatan_badge', $program->kegiatan_badge_formatted) }}">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-bold text-xs mb-4">
                                    Judul Utama Section 3 <span class="text-danger-600">*</span>
                                </label>
                                <input type="text" name="kegiatan_judul" class="form-control radius-8 fw-bold"
                                    value="{{ old('kegiatan_judul', $program->kegiatan_judul_formatted) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-xs mb-4">
                                    Deskripsi Pengantar Section 3 (Dibawah Judul)
                                </label>
                                <input type="text" name="kegiatan_deskripsi" class="form-control radius-8"
                                    value="{{ old('kegiatan_deskripsi', $program->kegiatan_deskripsi_formatted) }}">
                            </div>
                        </div>

                        <!-- 6 Kartu Aktivitas & Edukasi (Grid 2 Kolom di Form / 3 Kolom di Frontend) -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="text-xs fw-bold text-dark mb-0">
                                📋 6 Kartu Aktivitas &amp; Edukasi (Badge, Judul Kegiatan, Deskripsi)
                            </h6>
                            <span class="badge bg-primary-subtle text-primary-main text-2xs fw-bold">6 Slot Aktivitas</span>
                        </div>

                        @php
                            $cards = $program->kegiatan_list_data;
                        @endphp

                        <div class="row g-3">
                            @for ($k = 0; $k < 6; $k++)
                                <div class="col-md-6">
                                    <div class="kegiatan-card-item">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-success-subtle text-success-main text-2xs fw-bold">Kartu {{ $k + 1 }}</span>
                                            <small class="text-secondary-light text-2xs font-monospace">Grid Item #{{ $k + 1 }}</small>
                                        </div>
                                        <div class="mb-2">
                                            <label class="text-2xs fw-bold text-secondary-light">Badge Pill (Hijau):</label>
                                            <input type="text" name="kegiatan_card_badge[]" class="form-control form-control-sm radius-6 fw-semibold"
                                                value="{{ old('kegiatan_card_badge.'.$k, $cards[$k]['badge'] ?? 'Kegiatan') }}" placeholder="Contoh: Parenting Day">
                                        </div>
                                        <div class="mb-2">
                                            <label class="text-2xs fw-bold text-secondary-light">Judul Kegiatan:</label>
                                            <input type="text" name="kegiatan_card_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                                value="{{ old('kegiatan_card_title.'.$k, $cards[$k]['title'] ?? '') }}" placeholder="Judul kegiatan aktivitas...">
                                        </div>
                                        <div>
                                            <label class="text-2xs fw-bold text-secondary-light">Deskripsi Kegiatan:</label>
                                            <textarea name="kegiatan_card_desc[]" rows="2" class="form-control form-control-sm radius-6"
                                                placeholder="Deskripsi kegiatan 1-2 kalimat...">{{ old('kegiatan_card_desc.'.$k, $cards[$k]['desc'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                    </div>
                </div>

            </div>

            <!-- =========================================================================
                 KOLOM KANAN (4 KOLOM): PENGATURAN PUBLIKASI, FOTO & SUBMIT
                 ========================================================================= -->
            <div class="col-lg-4">

                <!-- Kartu Pengaturan Publikasi -->
                <div class="card radius-12 border shadow-sm mb-20">
                    <div class="card-header border-bottom bg-base py-14 px-20">
                        <h6 class="text-sm fw-bold mb-0">Pengaturan Publikasi</h6>
                    </div>
                    <div class="card-body p-20">

                        <!-- Status Tayang -->
                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-6">Status Program</label>
                            <div class="form-check form-switch switch-success d-flex align-items-center gap-2 p-12 radius-8 bg-light">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="chkAktif"
                                    {{ old('is_active', $program->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-xs mb-0 text-dark" for="chkAktif">
                                    🟢 Tayang di Website
                                </label>
                            </div>
                        </div>

                        <!-- Nomor Urut -->
                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-6">Nomor Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                                value="{{ old('urutan', $program->urutan) }}" min="1">
                        </div>

                        <!-- Penanggung Jawab -->
                        <div class="mb-20">
                            <label class="form-label fw-bold text-xs mb-6">Penanggung Jawab / Penulis</label>
                            <input type="text" name="penulis" class="form-control radius-8 text-sm fw-semibold"
                                value="{{ old('penulis', $program->penulis) }}">
                        </div>

                        <button type="submit"
                            class="btn btn-primary-600 w-100 radius-8 py-12 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <i class="fas fa-save"></i> Simpan Perubahan Program
                        </button>
                    </div>
                </div>

                <!-- Kartu Foto Sampul / Banner Resmi -->
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-14 px-20">
                        <h6 class="text-sm fw-bold mb-0">Foto Sampul / Banner Resmi</h6>
                    </div>
                    <div class="card-body p-20">
                        <label class="form-label fw-bold text-xs mb-6">
                            Ganti Foto Dokumentasi (Opsional)
                        </label>
                        <input type="file" name="gambar" class="form-control radius-8" accept="image/*"
                            onchange="previewFotoSampul(this)">
                        <small class="text-xs text-secondary-light mt-1 d-block">
                            Format: JPG, PNG, WEBP. Maks 3 MB. Ditampilkan berbingkai di Section 1.
                        </small>

                        <div class="image-preview-container mt-3" id="previewImageWrap">
                            <img id="previewImageTag" src="{{ $program->gambar_url }}" alt="{{ $program->judul }}">
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

    <!-- Quill 2.0 JS Library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        // Inisialisasi Quill Editor untuk Ringkasan / Narasi Pengantar Program
        var quillRingkasan = new Quill('#quillRingkasan', {
            theme: 'snow',
            placeholder: 'Tuliskan narasi pengantar program atau buat otomatis dengan Smart AI di atas...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // Hitung Kata Ringkasan
        function updateStatistikRingkasan() {
            var text = quillRingkasan.getText().trim();
            var jumlahKata = text.length === 0 ? 0 : text.split(/\s+/).length;
            var badge = document.getElementById('indikatorKataRingkasan');
            if (badge) {
                badge.innerText = jumlahKata + ' kata';
            }
            var txtArea = document.getElementById('textareaRingkasan');
            if (txtArea) {
                txtArea.value = quillRingkasan.root.innerHTML;
            }
        }
        quillRingkasan.on('text-change', updateStatistikRingkasan);
        updateStatistikRingkasan();

        // Sinkronisasi sebelum form disubmit
        function sinkronkanQuillKeTextarea() {
            document.getElementById('textareaRingkasan').value = quillRingkasan.root.innerHTML;
        }

        // Live Preview Icon
        function updatePreviewIcon(iconClass) {
            var tag = document.getElementById('iconPreviewTag');
            if (tag) {
                tag.className = iconClass.trim();
            }
        }

        function pilihIcon(iconClass) {
            var input = document.getElementById('inputIconProgram');
            if (input) {
                input.value = iconClass;
                updatePreviewIcon(iconClass);
            }
        }

        // Live Preview Upload Foto Sampul
        function previewFotoSampul(input) {
            var wrap = document.getElementById('previewImageWrap');
            var img = document.getElementById('previewImageTag');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    wrap.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Sinkronisasi Judul ke Topik AI jika topik masih kosong
        function sinkronkanTopikAi(val) {
            var inputTopik = document.getElementById('inputTopikAi');
            if (inputTopik && inputTopik.value.trim() === '') {
                inputTopik.placeholder = 'Contoh: Program ' + val;
            }
        }

        // Toggle API Key Opsional
        function toggleKotakApiKey() {
            var box = document.getElementById('boxApiKeyOpsional');
            if (box) {
                box.classList.toggle('d-none');
            }
        }

        // Eksekusi Smart AI Studio untuk Ringkasan Program
        function jalankanAiProgram() {
            var inputJudul = document.getElementById('inputJudulProgram');
            var inputTopik = document.getElementById('inputTopikAi');
            var topik = inputTopik ? inputTopik.value.trim() : '';

            if (topik.length < 3 && inputJudul && inputJudul.value.trim().length >= 3) {
                topik = inputJudul.value.trim();
            }

            if (topik.length < 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Program / Topik Belum Diisi',
                    text: 'Ketikkan nama program di atas atau tuliskan fokus program pada input topik AI.'
                });
                if (inputTopik) inputTopik.focus();
                return;
            }

            var kategori = document.getElementById('inputKategoriBadge') ? document.getElementById('inputKategoriBadge').value : 'Program Unggulan';
            var nada = document.getElementById('selectNadaAi') ? document.getElementById('selectNadaAi').value : 'Formal, Edukatif, dan Inspiratif';
            var engineEl = document.getElementById('selectEngineAi');
            var engine = engineEl ? engineEl.value : 'gemini-2.5-flash';
            var inputKey = document.getElementById('inputApiKeyAi');
            var apiKey = inputKey ? inputKey.value.trim() : '';

            var btn = document.getElementById('btnGenerateAi');
            var teksBtn = document.getElementById('teksBtnAi');

            btn.disabled = true;
            teksBtn.innerHTML = '⏳ Memproses...';

            fetch("{{ route('program-unggulan.generateAi') }}", {
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
                teksBtn.innerHTML = '✨ Susun Narasi';

                if (res.status === 'success' && res.data) {
                    if (res.data.ringkasan) {
                        quillRingkasan.root.innerHTML = res.data.ringkasan;
                        updateStatistikRingkasan();
                    }
                    if (res.data.kutipan) {
                        var kutipanEl = document.getElementById('inputKutipan');
                        if (kutipanEl) {
                            kutipanEl.value = res.data.kutipan;
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Narasi Berhasil Disusun!',
                        text: 'Disusun otomatis oleh ' + (res.source ? res.source : 'AI Studio') + '.',
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal Menghasilkan Narasi',
                        text: res.message || 'Silakan periksa koneksi atau coba lagi.'
                    });
                }
            })
            .catch(function(err) {
                btn.disabled = false;
                teksBtn.innerHTML = '✨ Susun Narasi';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghubungi Server',
                    text: 'Terjadi kendala jaringan lokal. Silakan coba kembali.'
                });
            });
        }
    </script>
@endsection
