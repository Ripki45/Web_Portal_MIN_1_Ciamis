@php
    $isEdit = isset($program);
    $miniCards = $isEdit ? $program->mini_features_list : [];
    $pilars = $isEdit ? $program->pilar_list_formatted : [];
    $kegiatanCards = $isEdit ? $program->kegiatan_list_data : [];
    $poinText = $isEdit ? implode("\n", $program->poin_list) : '';
    $ringkasanAwal = old('ringkasan', $isEdit ? ($program->ringkasan_bersih ?? '') : '');
    $statusAktif = old('_token') ? (bool) old('is_active') : ($isEdit ? (bool) $program->is_active : true);

    $placeholderMini = [
        ['icon' => 'fas fa-shield-alt', 'title' => 'Contoh: Lingkungan Aman & Nyaman', 'desc' => 'Deskripsi singkat keunggulan pertama (1 kalimat)...'],
        ['icon' => 'fas fa-hands-holding-child', 'title' => 'Contoh: Sinergi Madrasah & Orang Tua', 'desc' => 'Deskripsi singkat keunggulan kedua (1 kalimat)...'],
    ];

    $placeholderPilar = [
        ['icon' => 'fas fa-smile-beam', 'title' => 'Contoh: Pembiasaan Karakter Mulia'],
        ['icon' => 'fas fa-chalkboard-teacher', 'title' => 'Contoh: Pendampingan Guru Profesional'],
        ['icon' => 'fas fa-book-open', 'title' => 'Contoh: Kurikulum Terpadu & Aplikatif'],
        ['icon' => 'fas fa-users', 'title' => 'Contoh: Kolaborasi Orang Tua & Komite'],
    ];

    $placeholderKegiatan = [
        ['badge' => 'Pembiasaan', 'title' => 'Contoh: Kegiatan Pagi & Pembinaan Karakter'],
        ['badge' => 'Praktik', 'title' => 'Contoh: Eksplorasi & Praktikum Terjadwal'],
        ['badge' => 'Kompetisi', 'title' => 'Contoh: Pembinaan Lomba & Ajang Prestasi'],
        ['badge' => 'Kolaborasi', 'title' => 'Contoh: Forum Sinergi Orang Tua & Guru'],
        ['badge' => 'Literasi', 'title' => 'Contoh: Apresiasi Karya & Gelar Ekspresi'],
        ['badge' => 'Evaluasi', 'title' => 'Contoh: Munaqosyah / Evaluasi Berkala'],
    ];
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
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
        transform: scale(1.08);
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
        height: 185px;
        object-fit: cover;
        border-radius: 8px;
        display: block;
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
    .quill-editor-wrap {
        display: block;
        width: 100%;
        position: relative;
    }
    .quill-editor-wrap .ql-toolbar.ql-snow {
        border-radius: 10px 10px 0 0;
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .quill-editor-wrap .ql-container.ql-snow {
        height: auto !important;
        border-radius: 0 0 10px 10px;
        border-color: #cbd5e1;
        background: #ffffff;
        font-size: 0.95rem;
        font-family: inherit;
    }
    .quill-editor-wrap .ql-editor {
        min-height: 170px;
        max-height: 340px;
        overflow-y: auto;
    }
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

<form action="{{ $isEdit ? route('program-unggulan.update', $program->id) : route('program-unggulan.store') }}"
      method="POST" enctype="multipart/form-data" id="formProgramUnggulan" onsubmit="sinkronkanQuillKeTextarea()">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row g-4">

        <!-- KOLOM KIRI: 3 SECTION KONTEN -->
        <div class="col-lg-8">

            <!-- SECTION 1: IDENTITAS & HERO PROGRAM -->
            <div class="card radius-12 border shadow-sm mb-20">
                <div class="card-body p-24">
                    <div class="section-header-box">
                        <span class="section-number-badge">1</span>
                        <div>
                            <h6 class="fw-bold mb-0">Section 1: Identitas &amp; Narasi Utama</h6>
                            <span class="text-xs text-secondary-light">Informasi utama yang tampil pada kartu katalog dan bagian hero halaman detail.</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold text-xs mb-4">
                                Nama Program Unggulan <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="judul" id="inputJudulProgram" class="form-control radius-8 fw-bold"
                                placeholder="Contoh: Tahfidz & Tartil Qur'an / Sekolah Ramah Anak"
                                value="{{ old('judul', $isEdit ? $program->judul : '') }}" required oninput="sinkronkanTopikAi(this.value)">
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-bold text-xs mb-4">
                                Label Kategori / Badge <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="kategori_badge" id="inputKategoriBadge" class="form-control radius-8"
                                placeholder="Contoh: Karakter Qur'ani / Sains & Riset"
                                value="{{ old('kategori_badge', $isEdit ? $program->kategori_badge : '') }}" required>
                        </div>

                        <!-- Ikon Font Awesome -->
                        <div class="col-12">
                            <div class="p-14 radius-10 bg-primary-50 border border-primary-200">
                                <label class="form-label fw-bold text-xs mb-4 text-primary-900">
                                    Ikon Program (Font Awesome 6) <span class="text-danger-600">*</span>
                                </label>
                                <div class="d-flex align-items-center gap-3 mb-8">
                                    <div class="icon-preview-box" id="boxIconPreview">
                                        <i class="{{ old('icon', $isEdit ? $program->icon : 'fas fa-star') }}" id="iconPreviewTag"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="text" name="icon" id="inputIconProgram"
                                            class="form-control radius-8 fw-semibold"
                                            placeholder="Contoh: fas fa-quran / fas fa-flask / fas fa-star"
                                            value="{{ old('icon', $isEdit ? $program->icon : 'fas fa-star') }}" required
                                            oninput="updatePreviewIcon(this.value)">
                                        <small class="text-xs text-secondary-light">
                                            Ketik class Font Awesome atau pilih dari palet rekomendasi di bawah:
                                        </small>
                                    </div>
                                </div>
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

                        <!-- Kutipan Komitmen -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-xs mb-4">
                                Kutipan Komitmen Program <span class="text-secondary-light fw-normal">(Opsional)</span>
                            </label>
                            <textarea name="kutipan" id="inputKutipan" rows="2" class="form-control radius-8 fst-italic"
                                placeholder="Tuliskan 1 kalimat kutipan inspiratif yang mewakili visi program ini...">{{ old('kutipan', $isEdit ? $program->kutipan : '') }}</textarea>
                        </div>

                        <!-- Panel Asisten AI -->
                        <div class="col-12">
                            <div class="gemini-studio-card p-18 radius-12 border shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-10">
                                    <span class="gemini-badge-pill">
                                        ✦ Asisten Penulis Narasi — Google Gemini &amp; Smart AI Lokal
                                    </span>
                                    <button type="button" class="btn btn-sm text-white text-xs opacity-75 p-0 border-0 bg-transparent"
                                        onclick="toggleKotakApiKey()">
                                        ⚙️ API Key Manual (Opsional)
                                    </button>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-lg-5">
                                        <label class="text-xs fw-semibold text-light mb-4 d-block">1. Topik / Fokus Program</label>
                                        <input type="text" id="inputTopikAi" class="form-control form-control-sm radius-8 bg-white text-dark fw-semibold"
                                            value="{{ $isEdit ? $program->judul : '' }}"
                                            placeholder="Kosongkan untuk otomatis memakai Nama Program...">
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <label class="text-xs fw-semibold text-warning mb-4 d-block">2. Mesin AI</label>
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
                                            <span id="teksBtnAi">✨ Susun</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="boxApiKeyOpsional" class="mt-10 pt-10 border-top border-secondary d-none">
                                    <label class="text-xs text-warning fw-semibold mb-2 d-block">
                                        Google AI Studio API Key (Kosongkan jika sudah diatur di <code>.env</code>):
                                    </label>
                                    <input type="password" id="inputApiKeyAi" autocomplete="off"
                                        class="form-control form-control-sm radius-8 bg-dark text-white border-secondary"
                                        placeholder="Masukkan API Key manual jika diperlukan...">
                                </div>
                            </div>
                        </div>

                        <!-- Quill Editor Ringkasan -->
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <label class="form-label fw-bold text-xs mb-0">
                                    Narasi Pengantar Program
                                </label>
                                <span id="indikatorKataRingkasan" class="badge bg-success-subtle text-success-main text-2xs fw-bold">
                                    0 kata
                                </span>
                            </div>

                            <div class="quill-editor-wrap">
                                <div id="quillRingkasan">{!! \App\Models\ProgramUnggulan::bersihkanHtml($ringkasanAwal) ?? '' !!}</div>
                            </div>
                            <textarea name="ringkasan" id="textareaRingkasan" class="d-none">{{ $ringkasanAwal }}</textarea>
                        </div> 
                        <!-- 2 Kolom Mini Feature Cards (Opsional) -->
                        <div class="col-12 mt-2">
                            <label class="form-label fw-bold text-xs mb-2 d-block">
                                2 Kartu Sorotan Singkat di Hero <span class="text-secondary-light fw-normal">(Opsional — kosongkan jika tidak perlu)</span>
                            </label>
                            <div class="row g-3">
                                @for ($m = 0; $m < 2; $m++)
                                    <div class="col-md-6">
                                        <div class="p-14 radius-10 bg-light border">
                                            <span class="badge bg-success-subtle text-success-main text-2xs fw-bold mb-2">Mini Card {{ $m + 1 }}</span>
                                            <div class="mb-2">
                                                <label class="text-2xs fw-bold text-secondary-light">Ikon Font Awesome:</label>
                                                <input type="text" name="mini_feature_icon[]" class="form-control form-control-sm radius-6"
                                                    value="{{ old('mini_feature_icon.'.$m, $miniCards[$m]['icon'] ?? '') }}"
                                                    placeholder="{{ $placeholderMini[$m]['icon'] }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="text-2xs fw-bold text-secondary-light">Judul Singkat:</label>
                                                <input type="text" name="mini_feature_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                                    value="{{ old('mini_feature_title.'.$m, $miniCards[$m]['title'] ?? '') }}"
                                                    placeholder="{{ $placeholderMini[$m]['title'] }}">
                                            </div>
                                            <div>
                                                <label class="text-2xs fw-bold text-secondary-light">Keterangan:</label>
                                                <input type="text" name="mini_feature_desc[]" class="form-control form-control-sm radius-6"
                                                    value="{{ old('mini_feature_desc.'.$m, $miniCards[$m]['desc'] ?? '') }}"
                                                    placeholder="{{ $placeholderMini[$m]['desc'] }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Poin Ceklis untuk Katalog -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-xs mb-4">
                                Poin Ceklis Ringkas <span class="text-secondary-light fw-normal">(Opsional, 1 poin per baris — tampil pada kartu Katalog Program)</span>
                            </label>
                            <textarea name="keunggulan_poin" rows="3" class="form-control radius-8 font-monospace text-xs"
                                placeholder="Target Hafalan Juz 30 & Surat Pilihan&#10;Bimbingan Tahsin Bersertifikasi&#10;Wisuda & Munaqosyah Tahfidz Terpadu">{{ old('keunggulan_poin', $poinText) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PILAR UTAMA PROGRAM (OPSIONAL) -->
            <div class="card radius-12 border shadow-sm mb-20">
                <div class="card-body p-24">
                    <div class="section-header-box">
                        <span class="section-number-badge">2</span>
                        <div>
                            <h6 class="fw-bold mb-0">Section 2: Pilar Utama Program <span class="badge bg-light text-secondary-light border text-2xs ms-1">Opsional</span></h6>
                            <span class="text-xs text-secondary-light">Isi 1 sampai 4 pilar keunggulan. Jika dikosongkan semua, section ini otomatis disembunyikan di halaman detail.</span>
                        </div>
                    </div>

                    <div class="row g-3">
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
                                            value="{{ old('pilar_icon.'.$p, $pilars[$p]['icon'] ?? '') }}"
                                            placeholder="{{ $placeholderPilar[$p]['icon'] }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-2xs fw-bold text-secondary-light">Judul Pilar:</label>
                                        <input type="text" name="pilar_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                            value="{{ old('pilar_title.'.$p, $pilars[$p]['title'] ?? '') }}"
                                            placeholder="{{ $placeholderPilar[$p]['title'] }}">
                                    </div>
                                    <div>
                                        <label class="text-2xs fw-bold text-secondary-light">Deskripsi Pilar:</label>
                                        <textarea name="pilar_desc[]" rows="2" class="form-control form-control-sm radius-6"
                                            placeholder="Penjelasan singkat pilar ke-{{ $p + 1 }}...">{{ old('pilar_desc.'.$p, $pilars[$p]['desc'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- SECTION 3: AKTIVITAS & EDUKASI (OPSIONAL) -->
            <div class="card radius-12 border shadow-sm">
                <div class="card-body p-24">
                    <div class="section-header-box">
                        <span class="section-number-badge">3</span>
                        <div>
                            <h6 class="fw-bold mb-0">Section 3: Daftar Kegiatan &amp; Aktivitas <span class="badge bg-light text-secondary-light border text-2xs ms-1">Opsional</span></h6>
                            <span class="text-xs text-secondary-light">Isi hingga 6 kartu agenda/aktivitas pendukung. Jika semua kartu kosong, section otomatis disembunyikan.</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 p-14 radius-10 bg-light border">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-xs mb-4">Badge Subjudul Section 3</label>
                            <input type="text" name="kegiatan_badge" class="form-control radius-8 fw-semibold"
                                placeholder="Contoh: Aktivitas & Edukasi"
                                value="{{ old('kegiatan_badge', $isEdit ? $program->kegiatan_badge : '') }}">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold text-xs mb-4">Judul Utama Section 3</label>
                            <input type="text" name="kegiatan_judul" class="form-control radius-8 fw-bold"
                                placeholder="Kosongkan untuk otomatis memakai nama program..."
                                value="{{ old('kegiatan_judul', $isEdit ? $program->kegiatan_judul : '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-xs mb-4">Deskripsi Pengantar Section 3</label>
                            <input type="text" name="kegiatan_deskripsi" class="form-control radius-8"
                                placeholder="Contoh: Dokumentasi agenda, kegiatan rutin, dan pembiasaan peserta didik..."
                                value="{{ old('kegiatan_deskripsi', $isEdit ? $program->kegiatan_deskripsi : '') }}">
                        </div>
                    </div>

                    <div class="row g-3">
                        @for ($k = 0; $k < 6; $k++)
                            <div class="col-md-6">
                                <div class="kegiatan-card-item">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-success-subtle text-success-main text-2xs fw-bold">Kartu {{ $k + 1 }}</span>
                                        <small class="text-secondary-light text-2xs font-monospace">Slot #{{ $k + 1 }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-2xs fw-bold text-secondary-light">Label Badge:</label>
                                        <input type="text" name="kegiatan_card_badge[]" class="form-control form-control-sm radius-6 fw-semibold"
                                            value="{{ old('kegiatan_card_badge.'.$k, $kegiatanCards[$k]['badge'] ?? '') }}"
                                            placeholder="Contoh: {{ $placeholderKegiatan[$k]['badge'] }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-2xs fw-bold text-secondary-light">Judul Kegiatan:</label>
                                        <input type="text" name="kegiatan_card_title[]" class="form-control form-control-sm radius-6 fw-bold"
                                            value="{{ old('kegiatan_card_title.'.$k, $kegiatanCards[$k]['title'] ?? '') }}"
                                            placeholder="{{ $placeholderKegiatan[$k]['title'] }}">
                                    </div>
                                    <div>
                                        <label class="text-2xs fw-bold text-secondary-light">Deskripsi Singkat:</label>
                                        <textarea name="kegiatan_card_desc[]" rows="2" class="form-control form-control-sm radius-6"
                                            placeholder="Deskripsi kegiatan 1–2 kalimat...">{{ old('kegiatan_card_desc.'.$k, $kegiatanCards[$k]['desc'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: PENGATURAN PUBLIKASI & FOTO SAMPUL -->
        <div class="col-lg-4">
            <div class="card radius-12 border shadow-sm mb-20">
                <div class="card-header border-bottom bg-base py-14 px-20">
                    <h6 class="text-sm fw-bold mb-0">Pengaturan Publikasi</h6>
                </div>
                <div class="card-body p-20">
                    <div class="mb-14">
                        <label class="form-label fw-bold text-xs mb-6">Status Program</label>
                        <div class="form-check form-switch switch-success d-flex align-items-center gap-2 p-12 radius-8 bg-light">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="chkAktif"
                                {{ $statusAktif ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-xs mb-0 text-dark" for="chkAktif">
                                Tayang di Website Publik
                            </label>
                        </div>
                    </div>

                    <div class="mb-14">
                        <label class="form-label fw-bold text-xs mb-6">Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                            value="{{ old('urutan', $isEdit ? $program->urutan : $urutanBerikutnya) }}" min="1">
                        <small class="text-xs text-secondary-light">Angka lebih kecil tampil lebih awal.</small>
                    </div>

                    <div class="mb-20">
                        <label class="form-label fw-bold text-xs mb-6">Penanggung Jawab / Penulis</label>
                        <input type="text" name="penulis" class="form-control radius-8 text-sm fw-semibold"
                            value="{{ old('penulis', $isEdit ? $program->penulis : 'Tim Kurikulum MIN 1 Ciamis') }}">
                    </div>

                    <button type="submit"
                        class="btn btn-primary-600 w-100 radius-8 py-12 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> {{ $isEdit ? 'Simpan Perubahan Program' : 'Simpan Program Baru' }}
                    </button>
                </div>
            </div>

            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-14 px-20">
                    <h6 class="text-sm fw-bold mb-0">Foto Sampul / Dokumentasi</h6>
                </div>
                <div class="card-body p-20">
                    <label class="form-label fw-bold text-xs mb-6">
                        {{ $isEdit ? 'Ganti Foto Sampul (Opsional)' : 'Unggah Foto Dokumentasi (Rasio 16:9 / 4:3)' }}
                    </label>
                    <input type="file" name="gambar" class="form-control radius-8"
                        accept="image/jpeg,image/png,image/webp" onchange="previewFotoSampul(this)">
                    <small class="text-xs text-secondary-light mt-1 d-block">
                        Format: JPG, PNG, atau WEBP. Maksimal 3 MB.
                    </small>

                    <div class="image-preview-container mt-3" id="previewImageWrap"
                         style="display: {{ $isEdit ? 'block' : 'none' }};">
                        <img id="previewImageTag"
                             src="{{ $isEdit ? $program->gambar_url : '' }}"
                             alt="{{ $isEdit ? $program->judul : 'Pratinjau Sampul' }}">
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    var quillRingkasan = new Quill('#quillRingkasan', {
        theme: 'snow',
        placeholder: 'Tuliskan narasi pengantar program atau klik tombol Susun di panel AI di atas...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    function updateStatistikRingkasan() {
        var text = quillRingkasan.getText().trim();
        var jumlahKata = text.length === 0 ? 0 : text.split(/\s+/).length;
        var badge = document.getElementById('indikatorKataRingkasan');
        if (badge) {
            badge.innerText = jumlahKata + ' kata';
        }
        var txtArea = document.getElementById('textareaRingkasan');
        if (txtArea) {
            txtArea.value = text.length === 0 ? '' : quillRingkasan.root.innerHTML;
        }
    }

    quillRingkasan.on('text-change', updateStatistikRingkasan);
    updateStatistikRingkasan();

    function sinkronkanQuillKeTextarea() {
        var text = quillRingkasan.getText().trim();
        document.getElementById('textareaRingkasan').value = text.length === 0 ? '' : quillRingkasan.root.innerHTML;
    }

    function updatePreviewIcon(iconClass) {
        var tag = document.getElementById('iconPreviewTag');
        if (tag) {
            tag.className = iconClass.trim() || 'fas fa-star';
        }
    }

    function pilihIcon(iconClass) {
        var input = document.getElementById('inputIconProgram');
        if (input) {
            input.value = iconClass;
            updatePreviewIcon(iconClass);
        }
    }

    function previewFotoSampul(input) {
        var wrap = document.getElementById('previewImageWrap');
        var img = document.getElementById('previewImageTag');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                wrap.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function sinkronkanTopikAi(val) {
        var inputTopik = document.getElementById('inputTopikAi');
        if (inputTopik && inputTopik.value.trim() === '') {
            inputTopik.placeholder = val ? ('Otomatis: ' + val) : 'Kosongkan untuk otomatis memakai Nama Program...';
        }
    }

    function toggleKotakApiKey() {
        var box = document.getElementById('boxApiKeyOpsional');
        if (box) box.classList.toggle('d-none');
    }

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
                text: 'Isi Nama Program di atas atau tuliskan topik pada kolom Asisten AI terlebih dahulu.'
            });
            if (inputJudul) inputJudul.focus();
            return;
        }

        var kategori = document.getElementById('inputKategoriBadge') ? document.getElementById('inputKategoriBadge').value : 'Program Unggulan';
        var nada = document.getElementById('selectNadaAi') ? document.getElementById('selectNadaAi').value : 'Formal, Edukatif, dan Inspiratif';
        var engine = document.getElementById('selectEngineAi') ? document.getElementById('selectEngineAi').value : 'gemini-2.5-flash';
        var apiKey = document.getElementById('inputApiKeyAi') ? document.getElementById('inputApiKeyAi').value.trim() : '';

        var btn = document.getElementById('btnGenerateAi');
        var teksBtn = document.getElementById('teksBtnAi');

        btn.disabled = true;
        teksBtn.innerHTML = '⏳ Proses...';

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
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (res) {
            btn.disabled = false;
            teksBtn.innerHTML = '✨ Susun';

            if (res.status === 'success' && res.data) {
                if (res.data.ringkasan) {
                    quillRingkasan.root.innerHTML = res.data.ringkasan;
                    updateStatistikRingkasan();
                }
                if (res.data.kutipan) {
                    var kutipanEl = document.getElementById('inputKutipan');
                    if (kutipanEl) kutipanEl.value = res.data.kutipan;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Narasi Berhasil Disusun!',
                    text: 'Disusun oleh ' + (res.source || 'AI Studio') + '.',
                    timer: 2400,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal Menghasilkan Narasi',
                    text: res.message || 'Silakan coba lagi.'
                });
            }
        })
        .catch(function () {
            btn.disabled = false;
            teksBtn.innerHTML = '✨ Susun';
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menghubungi Server',
                text: 'Terjadi kendala koneksi. Coba ganti mesin ke Smart AI Studio Lokal atau ulangi beberapa saat lagi.'
            });
        });
    }
</script>