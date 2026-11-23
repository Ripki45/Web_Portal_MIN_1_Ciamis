@extends('layouts.frontend')

@section('title', 'Kalender & Agenda Akademik TA ' . $tahunAktif . ' — MIN 1 Ciamis')

@section('content')

    <style>
        .kaldik-pub-wrap {
            padding: 36px 0 84px;
            background: #f8fafc;
            min-height: 85vh;
        }
        .kaldik-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .kaldik-breadcrumb a {
            color: #0D6A56;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .kaldik-breadcrumb a:hover { text-decoration: underline; }

        /* Hero Banner */
        .kaldik-hero {
            background: linear-gradient(135deg, #06372D 0%, #0D6A56 52%, #15803D 100%);
            border-radius: 26px;
            padding: 40px 36px;
            color: #ffffff;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(6, 55, 45, 0.18);
        }
        .kaldik-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 225, 93, 0.18);
            border: 1px solid rgba(255, 225, 93, 0.45);
            color: #FFE15D;
            padding: 5px 16px;
            border-radius: 9999px;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .kaldik-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.75rem, 3.2vw, 2.4rem);
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 10px;
            line-height: 1.25;
        }
        .kaldik-hero-desc {
            font-size: 0.96rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 780px;
            line-height: 1.65;
            margin-bottom: 22px;
        }
        .kaldik-hero-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }
        .kaldik-stat-pills {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .kaldik-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.22);
            padding: 7px 16px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #ffffff;
        }
        .kaldik-stat-pill i { color: #FFE15D; }
        .kaldik-ta-select {
            padding: 9px 16px;
            border-radius: 12px;
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.96);
            color: #0f172a;
            font-weight: 800;
            font-size: 0.86rem;
            cursor: pointer;
        }
        .kaldik-btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFE15D;
            color: #06372D;
            font-weight: 800;
            font-size: 0.86rem;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kaldik-btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
            color: #06372D;
        }

        /* Tab Switcher */
        .kaldik-tab-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .kaldik-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 9999px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .kaldik-tab-btn.active {
            background: linear-gradient(135deg, #0D6A56 0%, #15803D 100%);
            border-color: #0D6A56;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(13, 106, 86, 0.22);
        }

        /* Cards & Layout */
        .kaldik-main-grid {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 24px;
            align-items: start;
        }
        .kaldik-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        }
        .kaldik-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .kaldik-filter-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.86rem;
            background: #f8fafc;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .kaldik-agenda-list {
            max-height: 540px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-right: 4px;
        }
        .kaldik-agenda-row {
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0D6A56;
            background: #fafcfb;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .kaldik-agenda-row:hover {
            transform: translateX(3px);
            border-color: #0D6A56;
            box-shadow: 0 6px 16px rgba(13, 106, 86, 0.08);
        }
        .kaldik-agenda-date {
            font-size: 0.76rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }
        .kaldik-agenda-name {
            font-family: 'Outfit', sans-serif;
            font-size: 0.94rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
        }
        .kaldik-legend-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        .kaldik-legend-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 11px;
            border-radius: 9999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
        }

        /* FullCalendar Styling (Teks Hitam Tegas & Warna Kontras) */
        #kalenderPublik .fc-toolbar-title {
            font-family: 'Outfit', sans-serif !important;
            font-size: 1.25rem !important;
            font-weight: 800 !important;
            color: #0f172a;
        }
        #kalenderPublik .fc-button-primary {
            background-color: #0D6A56 !important;
            border-color: #0D6A56 !important;
            font-size: 0.82rem !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            text-transform: capitalize;
        }
        #kalenderPublik .fc-button-primary:hover,
        #kalenderPublik .fc-button-primary.fc-button-active {
            background-color: #06372D !important;
            border-color: #06372D !important;
        }
        #kalenderPublik .fc-daygrid-event {
            cursor: pointer;
            border-radius: 6px !important;
            padding: 3px 7px !important;
            margin-top: 2px !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        }
        #kalenderPublik .fc-daygrid-event .fc-event-main,
        #kalenderPublik .fc-daygrid-event .fc-event-title {
            color: #0f172a !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
        }
        #kalenderPublik .fc-daygrid-day-number {
            color: #0f172a !important;
            font-weight: 600;
            text-decoration: none !important;
        }
        #kalenderPublik .fc-day-sun {
            background-color: rgba(239, 68, 68, 0.04);
        }
        #kalenderPublik .fc-day-sun .fc-daygrid-day-number {
            color: #dc2626 !important;
        }
        #kalenderPublik .fc-col-header-cell-cushion {
            color: #334155 !important;
            font-weight: 700;
            text-decoration: none !important;
        }

        /* Tabel HEB */
        .kaldik-heb-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        .kaldik-heb-table th,
        .kaldik-heb-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        .kaldik-heb-table thead th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            font-size: 0.78rem;
            text-transform: uppercase;
        }
        .kaldik-heb-table tfoot td {
            background: #ecfdf5;
            font-weight: 800;
            color: #065f46;
        }

        /* Dark Mode */
        [data-bs-theme="dark"] .kaldik-pub-wrap { background: #0b1519; }
        [data-bs-theme="dark"] .kaldik-breadcrumb { color: #94a3b8; }
        [data-bs-theme="dark"] .kaldik-breadcrumb a { color: #34d399; }
        [data-bs-theme="dark"] .kaldik-tab-btn {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.12);
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .kaldik-card {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .kaldik-card-title,
        [data-bs-theme="dark"] .kaldik-agenda-name,
        [data-bs-theme="dark"] #kalenderPublik .fc-toolbar-title { color: #f8fafc; }
        [data-bs-theme="dark"] .kaldik-filter-input {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.14);
            color: #f1f5f9;
        }
        [data-bs-theme="dark"] .kaldik-agenda-row {
            background: #172a32;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .kaldik-agenda-date { color: #94a3b8; }
        [data-bs-theme="dark"] .kaldik-legend-wrap { border-bottom-color: rgba(255, 255, 255, 0.08); }
        [data-bs-theme="dark"] .kaldik-legend-chip {
            background: #1a2f37;
            border-color: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
        }
        [data-bs-theme="dark"] #kalenderPublik {
            --fc-border-color: rgba(255, 255, 255, 0.12);
            --fc-page-bg-color: transparent;
            --fc-neutral-bg-color: rgba(255, 255, 255, 0.05);
        }
        [data-bs-theme="dark"] #kalenderPublik .fc-daygrid-day-number,
        [data-bs-theme="dark"] #kalenderPublik .fc-col-header-cell-cushion { color: #e2e8f0 !important; }
        [data-bs-theme="dark"] #kalenderPublik .fc-day-sun .fc-daygrid-day-number { color: #f87171 !important; }
        [data-bs-theme="dark"] .kaldik-heb-table th,
        [data-bs-theme="dark"] .kaldik-heb-table td { border-bottom-color: rgba(255, 255, 255, 0.08); color: #cbd5e1; }
        [data-bs-theme="dark"] .kaldik-heb-table thead th { background: #1a2f37; color: #f1f5f9; }
        [data-bs-theme="dark"] .kaldik-heb-table tfoot td { background: rgba(13, 106, 86, 0.25); color: #34d399; }

        /* Mode Responsif HEB: Tabel di Desktop, Kartu Ringkas di Mobile */
        .heb-desktop-only { display: block; }
        .heb-mobile-only { display: none; }

        .heb-m-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .heb-m-card {
            background: #fafcfb;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0D6A56;
            border-radius: 14px;
            padding: 14px;
        }
        .heb-m-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 10px;
        }
        .heb-m-month {
            font-family: 'Outfit', sans-serif;
            font-size: 0.98rem;
            font-weight: 800;
            color: #0f172a;
        }
        .heb-m-badge {
            background: #dcfce7;
            color: #166534;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 9999px;
            white-space: nowrap;
        }
        .heb-m-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }
        .heb-m-stat-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 6px;
            text-align: center;
        }
        .heb-m-stat-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2px;
        }
        .heb-m-stat-val {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }
        .heb-m-note {
            font-size: 0.78rem;
            line-height: 1.5;
            color: #475569;
            padding-top: 8px;
            border-top: 1px dashed #cbd5e1;
        }
        .heb-m-summary {
            margin-top: 14px;
            background: linear-gradient(135deg, #06372D 0%, #0D6A56 100%);
            color: #ffffff;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        [data-bs-theme="dark"] .heb-m-card {
            background: #172a32;
            border-color: rgba(255, 255, 255, 0.08);
            border-left-color: #34d399;
        }
        [data-bs-theme="dark"] .heb-m-month,
        [data-bs-theme="dark"] .heb-m-stat-val { color: #f8fafc; }
        [data-bs-theme="dark"] .heb-m-stat-box {
            background: #132228;
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-bs-theme="dark"] .heb-m-stat-label,
        [data-bs-theme="dark"] .heb-m-note { color: #94a3b8; }
        [data-bs-theme="dark"] .heb-m-note { border-top-color: rgba(255, 255, 255, 0.12); }

        @media (max-width: 767.98px) {
            .heb-desktop-only { display: none; }
            .heb-mobile-only { display: block; }
        }
        @media (max-width: 991.98px) {
            .kaldik-main-grid { grid-template-columns: 1fr; }
            .kaldik-agenda-list { max-height: 360px; }
        }
        @media (max-width: 575.98px) {
            .kaldik-hero { padding: 26px 20px; border-radius: 20px; }
            .kaldik-card { padding: 18px; border-radius: 18px; }
        }
    </style>

    <div class="kaldik-pub-wrap">
        <div class="container">

            <div class="kaldik-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
                <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                <span>Kalender &amp; Agenda Akademik</span>
            </div>

            {{-- Hero Banner --}}
            <div class="kaldik-hero">
                <div class="kaldik-hero-badge">
                    <i class="fas fa-calendar-alt"></i> KALENDER PENDIDIKAN MADRASAH
                </div>
                <h1 class="kaldik-hero-title">Kalender &amp; Agenda Akademik TA {{ $tahunAktif }}</h1>
                <p class="kaldik-hero-desc">
                    {{ $dokumen->dasar_hukum ?: 'Pedoman jadwal kegiatan belajar mengajar, asesmen, pembagian rapor, dan hari libur resmi di lingkungan MIN 1 Ciamis.' }}
                </p>

                <div class="kaldik-hero-toolbar">
                    <div class="kaldik-stat-pills">
                        <div class="kaldik-stat-pill">
                            <i class="fas fa-calendar-check"></i>
                            <span><strong>{{ $agendasPublik->count() }}</strong> Agenda Resmi</span>
                        </div>
                        <div class="kaldik-stat-pill">
                            <i class="fas fa-book-open"></i>
                            <span>HEB Gasal: <strong>{{ $analisisHeb['gasal']['total_efektif'] }} Hari</strong></span>
                        </div>
                        <div class="kaldik-stat-pill">
                            <i class="fas fa-graduation-cap"></i>
                            <span>HEB Genap: <strong>{{ $analisisHeb['genap']['total_efektif'] }} Hari</strong></span>
                        </div>
                        <div class="kaldik-stat-pill">
                            <i class="fas fa-award"></i>
                            <span>Total: <strong>{{ $analisisHeb['total_efektif_tahunan'] }} Hari Efektif</strong></span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <form method="GET" action="{{ route('kalender-akademik.publik') }}">
                            <select name="tahun_ajaran" class="kaldik-ta-select" onchange="this.form.submit()" aria-label="Pilih Tahun Ajaran">
                                @foreach ($daftarTahunAjaran as $ta)
                                    <option value="{{ $ta }}" {{ $ta === $tahunAktif ? 'selected' : '' }}>
                                        TA {{ $ta }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        @if ($dokumen->file_pdf_url)
                            <a href="{{ $dokumen->file_pdf_url }}" target="_blank" rel="noopener noreferrer" class="kaldik-btn-pdf">
                                <i class="fas fa-file-pdf"></i> Unduh PDF Kaldik
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tab Switcher --}}
            <div class="kaldik-tab-bar">
                <button type="button" class="kaldik-tab-btn active" onclick="gantiTabKaldikPublik('kalender', this)">
                    <i class="fas fa-calendar-days"></i> Kalender Interaktif &amp; Agenda
                </button>
                <button type="button" class="kaldik-tab-btn" onclick="gantiTabKaldikPublik('heb', this)">
                    <i class="fas fa-table"></i> Analisis Hari Efektif Belajar (HEB)
                </button>
            </div>

            {{-- PANEL 1: KALENDER & DAFTAR AGENDA --}}
            <div id="panelKaldikKalender">
                <div class="kaldik-main-grid">

                    {{-- Kolom Kiri: Daftar Agenda --}}
                    <div class="kaldik-card">
                        <h2 class="kaldik-card-title">
                            <i class="fas fa-list-ul text-success"></i> Daftar Agenda
                        </h2>

                        <input type="text" id="cariAgendaPublik" class="kaldik-filter-input" placeholder="Cari kegiatan atau hari libur...">
                        <select id="filterSemPublik" class="kaldik-filter-input">
                            <option value="semua">Semua Semester ({{ $agendasPublik->count() }})</option>
                            <option value="gasal">Semester Gasal (Juli – Desember)</option>
                            <option value="genap">Semester Genap (Januari – Juni)</option>
                        </select>

                        <div class="kaldik-agenda-list" id="daftarAgendaPublik">
                            @forelse ($agendasPublik as $ag)
                                @php $meta = $ag->meta_kategori; @endphp
                                <div class="kaldik-agenda-row"
                                     style="border-left-color: {{ $meta['color'] }};"
                                     data-search="{{ strtolower($ag->judul.' '.$meta['label']) }}"
                                     data-semester="{{ $ag->semester }}"
                                     onclick='tampilDetailAgendaPublik(@json($ag->toCalendarEvent()["extendedProps"]))'>
                                    <div class="kaldik-agenda-date">
                                        <span><i class="far fa-calendar"></i> {{ $ag->rentang_tanggal_label }}</span>
                                        @if ($ag->is_hari_libur)
                                            <span style="background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 9999px; font-size: 0.68rem;">Libur</span>
                                        @endif
                                    </div>
                                    <div class="kaldik-agenda-name">{{ $ag->judul }}</div>
                                </div>
                            @empty
                                <p class="text-muted text-center py-4 mb-0" style="font-size: 0.88rem;">
                                    Belum ada agenda akademik untuk Tahun Ajaran {{ $tahunAktif }}.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Kolom Kanan: FullCalendar 6 --}}
                    <div class="kaldik-card">
                        <div class="kaldik-legend-wrap">
                            @foreach ($daftarKategori as $katMeta)
                                <span class="kaldik-legend-chip">
                                    <span style="width: 9px; height: 9px; border-radius: 50%; background: {{ $katMeta['color'] }}; display: inline-block;"></span>
                                    {{ $katMeta['label'] }}
                                </span>
                            @endforeach
                        </div>

                        <div id="kalenderPublik"></div>

                        <p style="font-size: 0.8rem; color: #64748b; margin: 14px 0 0;">
                            <i class="fas fa-info-circle text-success"></i> Klik pada balok acara di kalender atau daftar sebelah kiri untuk melihat keterangan lengkap agenda.
                        </p>
                    </div>

                </div>
            </div>

            {{-- PANEL 2: ANALISIS HARI EFEKTIF BELAJAR (HEB) --}}
            <div id="panelKaldikHeb" style="display: none;">
                @foreach (['gasal' => 'Semester Gasal (Juli – Desember)', 'genap' => 'Semester Genap (Januari – Juni)'] as $semKey => $semJudul)
                    @php $semData = $analisisHeb[$semKey]; @endphp
                    <div class="kaldik-card mb-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <h2 class="kaldik-card-title mb-0">
                                <i class="fas fa-calendar-check text-success"></i> {{ $semJudul }} — TA {{ $tahunAktif }}
                            </h2>
                            <span style="background: #dcfce7; color: #166534; font-weight: 800; font-size: 0.8rem; padding: 5px 14px; border-radius: 9999px;">
                                {{ $semData['total_efektif'] }} Hari Efektif Belajar
                            </span>
                        </div>

                        {{-- Tampilan Desktop & Tablet: Tabel Resmi --}}
                        <div class="heb-desktop-only">
                            <div class="table-responsive">
                                <table class="kaldik-heb-table" style="min-width: 680px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 48px;">No</th>
                                            <th style="width: 150px;">Bulan / Tahun</th>
                                            <th class="text-center">Hari Kalender</th>
                                            <th class="text-center">{{ $analisisHeb['hari_kerja'] === 6 ? 'Hari Minggu' : 'Akhir Pekan' }}</th>
                                            <th class="text-center">Libur Resmi / Sem</th>
                                            <th class="text-center">Hari Efektif</th>
                                            <th>Keterangan Agenda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($semData['rows'] as $i => $row)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td class="fw-bold">{{ $row['bulan_label'] }}</td>
                                                <td class="text-center">{{ $row['hari_kalender'] }}</td>
                                                <td class="text-center">{{ $row['hari_akhir_pekan'] }}</td>
                                                <td class="text-center fw-bold" style="color: #dc2626;">{{ $row['hari_libur'] }}</td>
                                                <td class="text-center fw-bold" style="color: #059669;">{{ $row['hari_efektif'] }} Hari</td>
                                                <td style="font-size: 0.84rem;">{{ $row['keterangan'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">Total {{ $semJudul }}</td>
                                            <td class="text-center">{{ $semData['total_kalender'] }}</td>
                                            <td class="text-center">{{ $semData['total_akhir_pekan'] }}</td>
                                            <td class="text-center">{{ $semData['total_libur'] }}</td>
                                            <td class="text-center">{{ $semData['total_efektif'] }} Hari</td>
                                            <td>Pola {{ $analisisHeb['hari_kerja'] }} hari kerja efektif</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Tampilan HP (Mobile): Kartu Ringkas Tanpa Scroll Samping --}}
                        <div class="heb-mobile-only">
                            <div class="heb-m-list">
                                @foreach ($semData['rows'] as $row)
                                    <div class="heb-m-card">
                                        <div class="heb-m-head">
                                            <span class="heb-m-month">{{ $row['bulan_label'] }}</span>
                                            <span class="heb-m-badge">{{ $row['hari_efektif'] }} Hari Efektif</span>
                                        </div>
                                        <div class="heb-m-stats">
                                            <div class="heb-m-stat-box">
                                                <span class="heb-m-stat-label">Kalender</span>
                                                <span class="heb-m-stat-val">{{ $row['hari_kalender'] }}</span>
                                            </div>
                                            <div class="heb-m-stat-box">
                                                <span class="heb-m-stat-label">{{ $analisisHeb['hari_kerja'] === 6 ? 'Minggu' : 'Akhir Pekan' }}</span>
                                                <span class="heb-m-stat-val">{{ $row['hari_akhir_pekan'] }}</span>
                                            </div>
                                            <div class="heb-m-stat-box">
                                                <span class="heb-m-stat-label">Libur</span>
                                                <span class="heb-m-stat-val" style="color: {{ $row['hari_libur'] > 0 ? '#dc2626' : 'inherit' }};">
                                                    {{ $row['hari_libur'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="heb-m-note">
                                            <strong>Catatan:</strong> {{ $row['keterangan'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="heb-m-summary">
                                <div>
                                    <div style="font-size: 0.72rem; opacity: 0.85;">Total {{ $semJudul }}</div>
                                    <div style="font-size: 0.76rem; opacity: 0.9;">
                                        Kalender {{ $semData['total_kalender'] }} &bull; Libur {{ $semData['total_libur'] }}
                                    </div>
                                </div>
                                <div style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 800; color: #FFE15D; white-space: nowrap;">
                                    {{ $semData['total_efektif'] }} Hari
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($dokumen->catatan_kaki)
                    <div class="kaldik-card" style="font-size: 0.88rem;">
                        <strong><i class="fas fa-sticky-note text-success me-1"></i> Catatan:</strong>
                        {{ $dokumen->catatan_kaki }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/id.global.min.js"></script>

    <script>
        var PUB_KALDIK_EVENTS = @json($calendarEvents);
        var PUB_KALDIK_THEME = {
            matsama:           { bg: '#bae6fd', border: '#0284c7' },
            asesmen:           { bg: '#fde68a', border: '#d97706' },
            ujian:             { bg: '#ddd6fe', border: '#7c3aed' },
            rapor:             { bg: '#a7f3d0', border: '#059669' },
            libur_semester:    { bg: '#fecdd3', border: '#e11d48' },
            libur_keagamaan:   { bg: '#fecaca', border: '#dc2626' },
            libur_nasional:    { bg: '#fca5a5', border: '#b91c1c' },
            kegiatan_madrasah: { bg: '#99f6e4', border: '#0d6a56' }
        };
        var kalenderPublikInstance = null;

        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('kalenderPublik');
            if (el && typeof FullCalendar !== 'undefined') {
                kalenderPublikInstance = new FullCalendar.Calendar(el, {
                    locale: 'id',
                    initialView: 'dayGridMonth',
                    initialDate: @json($initialCalendarDate),
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth'
                    },
                    buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'Daftar' },
                    events: PUB_KALDIK_EVENTS,
                    eventDidMount: function (info) {
                        var kat = info.event.extendedProps.kategori || 'kegiatan_madrasah';
                        var tema = PUB_KALDIK_THEME[kat] || PUB_KALDIK_THEME.kegiatan_madrasah;

                        if (info.el.classList.contains('fc-daygrid-event')) {
                            info.el.style.setProperty('background-color', tema.bg, 'important');
                            info.el.style.setProperty('border', '1px solid ' + tema.border, 'important');
                            info.el.style.setProperty('border-left', '4px solid ' + tema.border, 'important');
                            info.el.style.setProperty('color', '#0f172a', 'important');

                            info.el.querySelectorAll('.fc-event-main, .fc-event-title, .fc-event-time').forEach(function (node) {
                                node.style.setProperty('color', '#0f172a', 'important');
                                node.style.setProperty('font-weight', '700', 'important');
                            });
                        }

                        var dot = info.el.querySelector('.fc-list-event-dot');
                        if (dot) dot.style.setProperty('border-color', tema.border, 'important');
                    },
                    eventClick: function (info) {
                        tampilDetailAgendaPublik(info.event.extendedProps);
                    }
                });
                kalenderPublikInstance.render();
            }

            var cariEl = document.getElementById('cariAgendaPublik');
            var semEl = document.getElementById('filterSemPublik');
            function saringPublik() {
                var q = (cariEl ? cariEl.value : '').trim().toLowerCase();
                var sem = semEl ? semEl.value : 'semua';
                document.querySelectorAll('#daftarAgendaPublik .kaldik-agenda-row').forEach(function (row) {
                    var teks = row.getAttribute('data-search') || '';
                    var s = row.getAttribute('data-semester') || '';
                    row.style.display = (teks.indexOf(q) !== -1 && (sem === 'semua' || s === sem)) ? 'block' : 'none';
                });
            }
            if (cariEl) cariEl.addEventListener('input', saringPublik);
            if (semEl) semEl.addEventListener('change', saringPublik);
        });

        function gantiTabKaldikPublik(tab, btn) {
            document.querySelectorAll('.kaldik-tab-btn').forEach(function (b) { b.classList.remove('active'); });
            if (btn) btn.classList.add('active');

            document.getElementById('panelKaldikKalender').style.display = tab === 'kalender' ? 'block' : 'none';
            document.getElementById('panelKaldikHeb').style.display = tab === 'heb' ? 'block' : 'none';

            if (tab === 'kalender' && kalenderPublikInstance) {
                kalenderPublikInstance.updateSize();
            }
        }

        function tampilDetailAgendaPublik(props) {
            var modal = document.getElementById('general-modal');
            var title = document.getElementById('modal-title');
            var content = document.getElementById('modal-content-area');
            if (!modal || !title || !content) return;

            title.textContent = '';
            var icon = document.createElement('i');
            icon.className = 'fas fa-calendar-day';
            icon.style.color = props.kategori_color || '#0D6A56';
            icon.style.marginRight = '8px';
            title.appendChild(icon);
            title.appendChild(document.createTextNode(props.judul || 'Detail Agenda'));

            content.textContent = '';

            var badgeRow = document.createElement('div');
            badgeRow.style.cssText = 'display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;';

            var katBadge = document.createElement('span');
            katBadge.style.cssText = 'display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:9999px;font-size:0.78rem;font-weight:700;background:#f1f5f9;color:#0f172a;border:1px solid #cbd5e1;';
            var dot = document.createElement('span');
            dot.style.cssText = 'width:8px;height:8px;border-radius:50%;display:inline-block;background:' + (props.kategori_color || '#0D6A56') + ';';
            katBadge.appendChild(dot);
            katBadge.appendChild(document.createTextNode(props.kategori_label || 'Agenda'));
            badgeRow.appendChild(katBadge);

            var statusBadge = document.createElement('span');
            statusBadge.style.cssText = props.is_hari_libur
                ? 'padding:4px 12px;border-radius:9999px;font-size:0.78rem;font-weight:700;background:#fee2e2;color:#b91c1c;'
                : 'padding:4px 12px;border-radius:9999px;font-size:0.78rem;font-weight:700;background:#dcfce7;color:#166534;';
            statusBadge.textContent = props.is_hari_libur ? 'Hari Libur' : 'Hari Efektif / Kegiatan Madrasah';
            badgeRow.appendChild(statusBadge);

            var tglBox = document.createElement('div');
            tglBox.style.cssText = 'padding:12px 14px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;margin-bottom:14px;font-size:0.9rem;font-weight:700;color:#0f172a;';
            tglBox.textContent = 'Tanggal: ' + (props.rentang_label || '-') + ' (Semester ' + (props.semester === 'genap' ? 'Genap' : 'Gasal') + ')';

            var descEl = document.createElement('p');
            descEl.style.cssText = 'font-size:0.94rem;line-height:1.7;color:#475569;margin:0;';
            descEl.textContent = props.deskripsi || 'Agenda resmi berdasarkan Kalender Pendidikan MIN 1 Ciamis.';

            content.appendChild(badgeRow);
            content.appendChild(tglBox);
            content.appendChild(descEl);

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    </script>

@endsection