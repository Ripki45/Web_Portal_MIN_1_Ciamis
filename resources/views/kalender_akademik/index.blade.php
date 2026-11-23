@extends('layouts.admin')

@section('title', 'Kalender & Agenda Akademik - MIN 1 Ciamis')

@section('content')

    <style>
        .kaldik-event-scroll {
            max-height: 540px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .kaldik-legend-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.74rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .fc .fc-toolbar-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
        }
        .fc .fc-button-primary {
            background-color: #0d6a56 !important;
            border-color: #0d6a56 !important;
            font-size: 0.8rem !important;
            text-transform: capitalize;
        }
        .fc .fc-button-primary:hover,
        .fc .fc-button-primary.fc-button-active {
            background-color: #094f40 !important;
            border-color: #094f40 !important;
        }
        #calendarAkademik .fc-daygrid-event {
            cursor: pointer;
            border-radius: 6px !important;
            padding: 3px 7px !important;
            margin-top: 2px !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            transition: filter 0.15s ease, transform 0.15s ease;
        }
        #calendarAkademik .fc-daygrid-event:hover {
            filter: brightness(0.95);
        }
        #calendarAkademik .fc-daygrid-event .fc-event-main,
        #calendarAkademik .fc-daygrid-event .fc-event-title,
        #calendarAkademik .fc-daygrid-event .fc-event-time {
            color: #0f172a !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            line-height: 1.35 !important;
        }
        #calendarAkademik .fc-daygrid-day-number {
            color: #0f172a !important;
            font-weight: 600;
        }
        #calendarAkademik .fc-day-sun .fc-daygrid-day-number {
            color: #dc2626 !important;
        }
        #calendarAkademik .fc-list-event-title {
            color: #0f172a !important;
            font-weight: 600 !important;
        }
        .fc-day-sun {
            background-color: rgba(239, 68, 68, 0.04);
        }
        [data-theme="dark"] .kaldik-legend-pill {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.12);
        }
        [data-theme="dark"] .fc {
            --fc-border-color: rgba(255, 255, 255, 0.12);
            --fc-page-bg-color: transparent;
            --fc-neutral-bg-color: rgba(255, 255, 255, 0.04);
        }
    </style>

    @php
        $tabAktif = request('tab', 'kalender');
    @endphp

    {{-- Header & Pilih Tahun Ajaran --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Kalender &amp; Agenda Akademik Madrasah</h6>
            <span class="text-xs text-secondary-light">
                Kelola jadwal tahun ajaran, pantau kalender interaktif, dan hitung otomatis Hari Efektif Belajar (HEB).
            </span>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <form method="GET" action="{{ route('kalender-akademik.index') }}" class="d-flex align-items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tabAktif }}">
                <select name="tahun_ajaran" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()" style="min-width: 150px;">
                    @foreach ($daftarTahunAjaran as $ta)
                        <option value="{{ $ta }}" {{ $ta === $tahunAktif ? 'selected' : '' }}>
                            TA {{ $ta }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('kalender-akademik.cetakHeb', ['tahun_ajaran' => $tahunAktif]) }}" target="_blank"
               class="btn btn-outline-success btn-sm radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <iconify-icon icon="solar:printer-minimalistic-linear" class="text-md"></iconify-icon>
                Cetak Analisis HEB
            </a>
            @if ($bolehKelola)
                <form action="{{ route('kalender-akademik.sinkronLibur') }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Sinkronkan seluruh agenda Hari Libur TA {{ $tahunAktif }} ke kalender libur Presensi QR?');">
                    @csrf
                    <input type="hidden" name="tahun_ajaran" value="{{ $tahunAktif }}">
                    <button type="submit"
                        class="btn btn-outline-warning btn-sm radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                        <iconify-icon icon="solar:refresh-circle-linear" class="text-md"></iconify-icon>
                        Sinkron Libur ke Presensi
                    </button>
                </form>
            @endif

            @if ($dokumen->file_pdf_url)
                <a href="{{ $dokumen->file_pdf_url }}" target="_blank"
                   class="btn btn-outline-primary-600 btn-sm radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                    <iconify-icon icon="solar:file-download-linear" class="text-md"></iconify-icon>
                    Unduh PDF Kaldik
                </a>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show radius-8 mb-20" role="alert">
            <span class="fw-semibold text-sm">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show radius-8 mb-20" role="alert">
            <strong class="text-sm d-block mb-1">Periksa kembali input Anda:</strong>
            <ul class="mb-0 text-xs ps-18">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Ringkasan Statistik Cepat --}}
    <div class="row g-3 mb-20">
        <div class="col-md-3 col-6">
            <div class="card radius-12 border p-16 bg-base shadow-sm h-100">
                <span class="text-xs text-secondary-light fw-semibold">Total Agenda TA {{ $tahunAktif }}</span>
                <h5 class="fw-bold mb-0 mt-1 text-primary-600">{{ $agendas->count() }} Agenda</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card radius-12 border p-16 bg-base shadow-sm h-100">
                <span class="text-xs text-secondary-light fw-semibold">HEB Semester Gasal</span>
                <h5 class="fw-bold mb-0 mt-1 text-success-600">{{ $analisisHeb['gasal']['total_efektif'] }} Hari</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card radius-12 border p-16 bg-base shadow-sm h-100">
                <span class="text-xs text-secondary-light fw-semibold">HEB Semester Genap</span>
                <h5 class="fw-bold mb-0 mt-1 text-info-600">{{ $analisisHeb['genap']['total_efektif'] }} Hari</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card radius-12 border p-16 bg-base shadow-sm h-100">
                <span class="text-xs text-secondary-light fw-semibold">Total HEB ({{ $analisisHeb['hari_kerja'] }} Hari Kerja)</span>
                <h5 class="fw-bold mb-0 mt-1 text-warning-600">{{ $analisisHeb['total_efektif_tahunan'] }} Hari</h5>
            </div>
        </div>
    </div>

    {{-- Navigasi 3 Tab --}}
    <ul class="nav nav-pills mb-20 gap-2" id="kaldikTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link radius-8 px-18 py-10 text-xs fw-bold {{ $tabAktif === 'kalender' ? 'active' : '' }}"
                    id="tab-kalender-btn" data-bs-toggle="pill" data-bs-target="#pane-kalender" type="button" role="tab">
                Kalender &amp; Daftar Agenda
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link radius-8 px-18 py-10 text-xs fw-bold {{ $tabAktif === 'heb' ? 'active' : '' }}"
                    id="tab-heb-btn" data-bs-toggle="pill" data-bs-target="#pane-heb" type="button" role="tab">
                Analisis Hari Efektif (HEB)
            </button>
        </li>
        @if ($bolehKelola)
            <li class="nav-item" role="presentation">
                <button class="nav-link radius-8 px-18 py-10 text-xs fw-bold {{ $tabAktif === 'pengaturan' ? 'active' : '' }}"
                        id="tab-pengaturan-btn" data-bs-toggle="pill" data-bs-target="#pane-pengaturan" type="button" role="tab">
                    Pengaturan &amp; PDF Kaldik
                </button>
            </li>
        @endif
    </ul>

    <div class="tab-content">

        {{-- =================================================================
             TAB 1: KALENDER & DAFTAR AGENDA (ADAPTASI TEMPLATE WOWDASH)
             ================================================================= --}}
        <div class="tab-pane fade {{ $tabAktif === 'kalender' ? 'show active' : '' }}" id="pane-kalender" role="tabpanel">
            <div class="row gy-20-px">
                {{-- Kolom Kiri: Tombol Tambah + Daftar Agenda --}}
                <div class="col-xxl-3 col-lg-4">
                    <div class="card h-100 p-0 radius-12 border shadow-sm">
                        <div class="card-body p-20">
                            @if ($bolehKelola)
                                <button type="button"
                                    class="btn btn-primary text-sm btn-sm px-12 py-12 w-100 radius-8 d-flex align-items-center justify-content-center gap-2 mb-16"
                                    onclick="bukaModalTambah()">
                                    <iconify-icon icon="fa6-regular:square-plus" class="icon text-lg line-height-1"></iconify-icon>
                                    Tambah Agenda Baru
                                </button>
                            @endif

                            {{-- Filter Cepat Daftar Kiri --}}
                            <div class="mb-16">
                                <input type="text" id="filterAgendaSidebar" class="form-control form-control-sm radius-8 mb-2"
                                       placeholder="Cari judul agenda...">
                                <select id="filterSemesterSidebar" class="form-select form-select-sm radius-8">
                                    <option value="semua">Semua Semester ({{ $agendas->count() }})</option>
                                    <option value="gasal">Semester Gasal (Jul–Des)</option>
                                    <option value="genap">Semester Genap (Jan–Jun)</option>
                                </select>
                            </div>

                            <div class="kaldik-event-scroll" id="daftarAgendaSidebar">
                                @forelse ($agendas as $agenda)
                                    @php $meta = $agenda->meta_kategori; @endphp
                                    <div class="event-item d-flex align-items-start justify-content-between gap-2 pb-14 mb-14 border-bottom"
                                         data-search="{{ strtolower($agenda->judul.' '.$meta['label']) }}"
                                         data-semester="{{ $agenda->semester }}">
                                        <div class="overflow-hidden">
                                            <div class="d-flex align-items-center gap-8 mb-1">
                                                <span class="w-10-px h-10-px rounded-circle flex-shrink-0" style="background: {{ $meta['color'] }};"></span>
                                                <span class="text-secondary-light text-xs fw-medium">{{ $agenda->rentang_tanggal_label }}</span>
                                            </div>
                                            <a href="javascript:void(0)"
                                               class="text-primary-light fw-semibold text-sm d-block text-decoration-none"
                                               onclick='bukaModalLihat(@json($agenda->toCalendarEvent()["extendedProps"]))'>
                                                {{ $agenda->judul }}
                                            </a>
                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                @if ($agenda->is_hari_libur)
                                                    <span class="badge bg-danger-focus text-danger-main text-2xs">Libur</span>
                                                @endif
                                                <span class="badge bg-neutral-100 text-secondary-light text-2xs text-capitalize">
                                                    {{ $agenda->semester }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="dropdown flex-shrink-0">
                                            <button type="button" class="border-0 bg-transparent p-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end p-8 border bg-base shadow">
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item px-12 py-8 rounded text-secondary-light d-flex align-items-center gap-8 text-xs"
                                                        onclick='bukaModalLihat(@json($agenda->toCalendarEvent()["extendedProps"]))'>
                                                        <iconify-icon icon="hugeicons:view" class="icon text-md"></iconify-icon>
                                                        Lihat Detail
                                                    </button>
                                                </li>
                                                @if ($bolehKelola)
                                                    <li>
                                                        <button type="button"
                                                            class="dropdown-item px-12 py-8 rounded text-secondary-light d-flex align-items-center gap-8 text-xs"
                                                            onclick='bukaModalEdit(@json($agenda->toCalendarEvent()["extendedProps"]))'>
                                                            <iconify-icon icon="lucide:edit" class="icon text-md"></iconify-icon>
                                                            Edit Agenda
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            class="dropdown-item px-12 py-8 rounded text-danger-600 d-flex align-items-center gap-8 text-xs"
                                                            onclick='bukaModalHapus({{ $agenda->id }}, @json($agenda->judul))'>
                                                            <iconify-icon icon="fluent:delete-24-regular" class="icon text-md"></iconify-icon>
                                                            Hapus
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-24 text-secondary-light text-xs">
                                        Belum ada agenda untuk TA {{ $tahunAktif }}.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: FullCalendar 6 Interaktif + Legenda Warna --}}
                <div class="col-xxl-9 col-lg-8">
                    <div class="card h-100 p-0 radius-12 border shadow-sm">
                        <div class="card-body p-24">
                            <div class="d-flex flex-wrap gap-2 mb-16 pb-12 border-bottom">
                                @foreach ($daftarKategori as $katMeta)
                                    <span class="kaldik-legend-pill">
                                        <span class="w-8-px h-8-px rounded-circle" style="background: {{ $katMeta['color'] }};"></span>
                                        {{ $katMeta['label'] }}
                                    </span>
                                @endforeach
                            </div>

                            <div id="calendarAkademik"></div>

                            @if ($bolehKelola)
                                <small class="text-xs text-secondary-light d-block mt-12">
                                    Tips: Klik tanggal kosong pada kalender untuk langsung menambah agenda baru, atau klik balok acara untuk melihat detail.
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =================================================================
             TAB 2: ANALISIS HARI EFEKTIF BELAJAR (HEB) OTOMATIS
             ================================================================= --}}
        <div class="tab-pane fade {{ $tabAktif === 'heb' ? 'show active' : '' }}" id="pane-heb" role="tabpanel">
            @foreach (['gasal' => 'Semester Gasal (Juli – Desember)', 'genap' => 'Semester Genap (Januari – Juni)'] as $semKey => $semJudul)
                @php $semData = $analisisHeb[$semKey]; @endphp
                <div class="card radius-12 border shadow-sm mb-20">
                    <div class="card-header bg-base border-bottom py-14 px-20 d-flex align-items-center justify-content-between">
                        <h6 class="text-sm fw-bold mb-0">{{ $semJudul }} — TA {{ $tahunAktif }}</h6>
                        <span class="badge bg-success-focus text-success-main radius-6 px-10 py-6 text-xs fw-bold">
                            {{ $semData['total_efektif'] }} Hari Efektif
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-2xs text-uppercase">
                                    <tr>
                                        <th class="ps-20 py-12" style="width: 50px;">No</th>
                                        <th class="py-12" style="width: 150px;">Bulan / Tahun</th>
                                        <th class="py-12 text-center" style="width: 110px;">Hari Kalender</th>
                                        <th class="py-12 text-center" style="width: 120px;">
                                            {{ $analisisHeb['hari_kerja'] === 6 ? 'Hari Minggu' : 'Sabtu & Minggu' }}
                                        </th>
                                        <th class="py-12 text-center" style="width: 130px;">Libur Resmi / Sem</th>
                                        <th class="py-12 text-center" style="width: 130px;">Hari Efektif (HEB)</th>
                                        <th class="pe-20 py-12">Agenda &amp; Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($semData['rows'] as $i => $row)
                                        <tr>
                                            <td class="ps-20 py-12 text-xs">{{ $i + 1 }}</td>
                                            <td class="py-12 text-xs fw-bold">{{ $row['bulan_label'] }}</td>
                                            <td class="py-12 text-xs text-center">{{ $row['hari_kalender'] }}</td>
                                            <td class="py-12 text-xs text-center">{{ $row['hari_akhir_pekan'] }}</td>
                                            <td class="py-12 text-xs text-center text-danger-600 fw-semibold">{{ $row['hari_libur'] }}</td>
                                            <td class="py-12 text-xs text-center fw-bold text-success-600">{{ $row['hari_efektif'] }} Hari</td>
                                            <td class="pe-20 py-12 text-xs text-secondary-light">{{ $row['keterangan'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="ps-20 py-12 text-xs">Total {{ $semJudul }}</td>
                                        <td class="py-12 text-xs text-center">{{ $semData['total_kalender'] }}</td>
                                        <td class="py-12 text-xs text-center">{{ $semData['total_akhir_pekan'] }}</td>
                                        <td class="py-12 text-xs text-center text-danger-600">{{ $semData['total_libur'] }}</td>
                                        <td class="py-12 text-xs text-center text-success-600">{{ $semData['total_efektif'] }} Hari</td>
                                        <td class="pe-20 py-12 text-xs">Asumsi {{ $analisisHeb['hari_kerja'] }} hari kerja efektif</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- =================================================================
             TAB 3: PENGATURAN DOKUMEN & UPLOAD PDF KALDIK
             ================================================================= --}}
        @if ($bolehKelola)
            <div class="tab-pane fade {{ $tabAktif === 'pengaturan' ? 'show active' : '' }}" id="pane-pengaturan" role="tabpanel">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header bg-base border-bottom py-16 px-24">
                        <h6 class="text-sm fw-bold mb-0">Pengaturan Dokumen Kaldik — TA {{ $tahunAktif }}</h6>
                    </div>
                    <div class="card-body p-24">
                        <form action="{{ route('kalender-akademik.updateDokumen') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAktif }}">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-xs mb-6">Dasar Hukum / Nomor SK Dirjen Pendis</label>
                                    <input type="text" name="dasar_hukum" class="form-control radius-8"
                                           value="{{ old('dasar_hukum', $dokumen->dasar_hukum) }}"
                                           placeholder="Contoh: Lampiran Keputusan Dirjen Pendis Nomor 4860 Tahun 2026...">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-xs mb-6">Pola Hari Kerja Madrasah</label>
                                    <select name="hari_kerja" class="form-select radius-8">
                                        <option value="6" {{ (int) old('hari_kerja', $dokumen->hari_kerja) === 6 ? 'selected' : '' }}>6 Hari Kerja (Senin – Sabtu)</option>
                                        <option value="5" {{ (int) old('hari_kerja', $dokumen->hari_kerja) === 5 ? 'selected' : '' }}>5 Hari Kerja (Senin – Jumat)</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-xs mb-6">Tanggal Pengesahan</label>
                                    <input type="date" name="tanggal_pengesahan" class="form-control radius-8"
                                           value="{{ old('tanggal_pengesahan', $dokumen->tanggal_pengesahan?->format('Y-m-d')) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-xs mb-6">Unggah File PDF Kaldik Resmi (Opsional)</label>
                                    <input type="file" name="file_pdf" class="form-control radius-8" accept="application/pdf">
                                    @if ($dokumen->file_pdf_url)
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <a href="{{ $dokumen->file_pdf_url }}" target="_blank" class="text-xs text-primary-600 fw-semibold">
                                                Lihat PDF tersimpan &#8599;
                                            </a>
                                            <label class="text-xs text-danger-600 mb-0">
                                                <input type="checkbox" name="hapus_pdf" value="1"> Hapus PDF
                                            </label>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-xs mb-6">Nama Kepala Madrasah (Blok TTD Cetak)</label>
                                    <input type="text" name="nama_kepsek" class="form-control radius-8"
                                           value="{{ old('nama_kepsek', $dokumen->nama_kepsek) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-xs mb-6">NIP Kepala Madrasah</label>
                                    <input type="text" name="nip_kepsek" class="form-control radius-8"
                                           value="{{ old('nip_kepsek', $dokumen->nip_kepsek) }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-xs mb-6">Catatan Akhir Dokumen</label>
                                    <textarea name="catatan_kaki" rows="3" class="form-control radius-8"
                                              placeholder="Catatan penyesuaian SKB 3 Menteri atau informasi tambahan...">{{ old('catatan_kaki', $dokumen->catatan_kaki) }}</textarea>
                                </div>

                                <div class="col-12 pt-8">
                                    <button type="submit" class="btn btn-primary radius-8 px-24 py-10 text-sm fw-semibold">
                                        Simpan Pengaturan Kaldik
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- =====================================================================
         MODAL LIHAT DETAIL AGENDA
         ===================================================================== --}}
    <div class="modal fade" id="modalLihatAgenda" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h6 class="modal-title fw-bold mb-0">Detail Agenda Akademik</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="mb-14">
                        <span class="text-secondary-light text-xs fw-medium d-block">Judul Kegiatan</span>
                        <h6 class="fw-bold text-md mb-0 mt-1" id="viewJudul"></h6>
                    </div>
                    <div class="mb-14">
                        <span class="text-secondary-light text-xs fw-medium d-block">Tanggal Pelaksanaan</span>
                        <span class="fw-semibold text-sm" id="viewTanggal"></span>
                    </div>
                    <div class="mb-14">
                        <span class="text-secondary-light text-xs fw-medium d-block mb-1">Kategori</span>
                        <span class="d-inline-flex align-items-center gap-2 text-sm fw-semibold">
                            <span id="viewDot" class="w-10-px h-10-px rounded-circle"></span>
                            <span id="viewKategori"></span>
                        </span>
                    </div>
                    <div class="mb-14">
                        <span class="text-secondary-light text-xs fw-medium d-block mb-1">Status Hari &amp; Visibilitas</span>
                        <div class="d-flex flex-wrap gap-2" id="viewBadges"></div>
                    </div>
                    <div class="mb-0">
                        <span class="text-secondary-light text-xs fw-medium d-block">Keterangan</span>
                        <p class="text-sm mb-0 mt-1" id="viewDeskripsi"></p>
                    </div>
                </div>
                @if ($bolehKelola)
                    <div class="modal-footer border-top py-12 px-24">
                        <button type="button" class="btn btn-outline-primary-600 btn-sm radius-8 px-16" id="btnEditDariView">
                            Edit Agenda
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Tutup</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($bolehKelola)
        {{-- =================================================================
             MODAL TAMBAH & EDIT AGENDA (DIPAKAI BERSAMA BIAR RINGKAS)
             ================================================================= --}}
        <div class="modal fade" id="modalFormAgenda" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h6 class="modal-title fw-bold mb-0" id="judulModalForm">Tambah Agenda Akademik Baru</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-24">
                        <form id="formAgenda" method="POST" action="{{ route('kalender-akademik.store') }}">
                            @csrf
                            <input type="hidden" name="_method" id="methodFormAgenda" value="POST">
                            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAktif }}">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-sm mb-6">Judul Agenda / Kegiatan <span class="text-danger-600">*</span></label>
                                    <input type="text" name="judul" id="fieldJudul" class="form-control radius-8"
                                           placeholder="Contoh: Asesmen Sumatif Akhir Semester (ASAS) Gasal" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tanggal Mulai <span class="text-danger-600">*</span></label>
                                    <input type="date" name="tanggal_mulai" id="fieldTanggalMulai" class="form-control radius-8 bg-base" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tanggal Selesai <span class="text-danger-600">*</span></label>
                                    <input type="date" name="tanggal_selesai" id="fieldTanggalSelesai" class="form-control radius-8 bg-base" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-sm mb-8 d-block">Kategori Warna Kalender</label>
                                    <div class="d-flex align-items-center flex-wrap gap-16">
                                        @foreach ($daftarKategori as $kodeKat => $katMeta)
                                            <div class="form-check d-flex align-items-center gap-2 mb-0">
                                                <input class="form-check-input" type="radio" name="kategori"
                                                       id="kat_{{ $kodeKat }}" value="{{ $kodeKat }}"
                                                       {{ $kodeKat === 'kegiatan_madrasah' ? 'checked' : '' }}
                                                       onchange="otomatisStatusLibur('{{ $kodeKat }}')">
                                                <label class="form-check-label text-xs fw-medium d-flex align-items-center gap-1" for="kat_{{ $kodeKat }}">
                                                    <span class="w-8-px h-8-px rounded-circle" style="background: {{ $katMeta['color'] }};"></span>
                                                    {{ $katMeta['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch p-12 radius-8 border bg-light d-flex align-items-center gap-2">
                                        <input class="form-check-input ms-0" type="checkbox" name="is_hari_libur" value="1" id="fieldIsLibur">
                                        <label class="form-check-label text-xs fw-semibold mb-0" for="fieldIsLibur">
                                            Tandai sebagai Hari Libur (mengurangi Hari Efektif)
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch p-12 radius-8 border bg-light d-flex align-items-center gap-2">
                                        <input class="form-check-input ms-0" type="checkbox" name="is_publik" value="1" id="fieldIsPublik" checked>
                                        <label class="form-check-label text-xs fw-semibold mb-0" for="fieldIsPublik">
                                            Tampilkan di Halaman Kalender Publik
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-sm mb-6">Keterangan Tambahan</label>
                                    <textarea name="deskripsi" id="fieldDeskripsi" rows="3" class="form-control radius-8"
                                              placeholder="Tuliskan detail singkat agenda..."></textarea>
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-3 mt-16">
                                    <button type="button" class="btn btn-outline-danger px-28 py-10 radius-8 text-sm" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary px-28 py-10 radius-8 text-sm fw-semibold">
                                        Simpan Agenda
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <div class="modal fade" id="modalHapusAgenda" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-body p-24 text-center">
                        <span class="mb-12 fs-1 line-height-1 text-danger d-block">
                            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                        </span>
                        <h6 class="fw-semibold mb-8">Hapus Agenda Ini?</h6>
                        <p class="text-xs text-secondary-light mb-20" id="teksHapusAgenda"></p>
                        <form id="formHapusAgenda" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <button type="button" class="w-50 btn btn-outline-secondary btn-sm py-10 radius-8" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="w-50 btn btn-danger btn-sm py-10 radius-8">
                                    Ya, Hapus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- FullCalendar 6 Global Bundle + Bahasa Indonesia --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/id.global.min.js"></script>

    <script>
      var KALDIK_THEME = {
            matsama:           { bg: '#bae6fd', border: '#0284c7' }, // Biru tegas
            asesmen:           { bg: '#fde68a', border: '#d97706' }, // Kuning / Amber
            ujian:             { bg: '#ddd6fe', border: '#7c3aed' }, // Ungu
            rapor:             { bg: '#a7f3d0', border: '#059669' }, // Hijau Emerald
            libur_semester:    { bg: '#fecdd3', border: '#e11d48' }, // Merah Mawar
            libur_keagamaan:   { bg: '#fecaca', border: '#dc2626' }, // Merah
            libur_nasional:    { bg: '#fca5a5', border: '#b91c1c' }, // Merah Pekat
            kegiatan_madrasah: { bg: '#99f6e4', border: '#0d6a56' }  // Hijau Toska MIN 1
        };
        var KALDIK_EVENTS = @json($calendarEvents);
        var BOLEH_KELOLA = @json($bolehKelola);
        var ROUTE_STORE = @json(route('kalender-akademik.store'));
        var ROUTE_BASE = @json(url('/kalender-akademik-admin'));
        var agendaTerpilih = null;
        var calendarInstance = null;

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendarAkademik');
            if (calendarEl && typeof FullCalendar !== 'undefined') {
                calendarInstance = new FullCalendar.Calendar(calendarEl, {
                    locale: 'id',
                    initialView: 'dayGridMonth',
                    initialDate: @json($initialCalendarDate),
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth'
                    },
                    buttonText: {
                        today: 'Hari Ini',
                        month: 'Bulan',
                        list: 'Daftar'
                    },
                    events: KALDIK_EVENTS,
                    eventDidMount: function (info) {
                        var kat = info.event.extendedProps.kategori || 'kegiatan_madrasah';
                        var tema = KALDIK_THEME[kat] || KALDIK_THEME.kegiatan_madrasah;

                        // Paksa warna latar, border kiri tebal, & teks hitam di tampilan bulan
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

                        // Warna titik di tampilan Daftar (listMonth)
                        var dot = info.el.querySelector('.fc-list-event-dot');
                        if (dot) {
                            dot.style.setProperty('border-color', tema.border, 'important');
                        }
                    },
                    dateClick: function (info) {
                        if (BOLEH_KELOLA) {
                            bukaModalTambah(info.dateStr);
                        }
                    }
                });
                calendarInstance.render();
            }

            // Perbarui ukuran kalender saat berpindah kembali ke tab Kalender
            var tabKalenderBtn = document.getElementById('tab-kalender-btn');
            if (tabKalenderBtn) {
                tabKalenderBtn.addEventListener('shown.bs.tab', function () {
                    if (calendarInstance) calendarInstance.updateSize();
                });
            }

            // Sinkronkan tanggal selesai agar tidak lebih kecil dari tanggal mulai
            var mulaiEl = document.getElementById('fieldTanggalMulai');
            var selesaiEl = document.getElementById('fieldTanggalSelesai');
            if (mulaiEl && selesaiEl) {
                mulaiEl.addEventListener('change', function () {
                    if (!selesaiEl.value || selesaiEl.value < mulaiEl.value) {
                        selesaiEl.value = mulaiEl.value;
                    }
                });
            }

            // Filter daftar agenda di kolom kiri
            var inputCari = document.getElementById('filterAgendaSidebar');
            var pilihSem = document.getElementById('filterSemesterSidebar');
            function saringSidebar() {
                var q = (inputCari ? inputCari.value : '').trim().toLowerCase();
                var sem = pilihSem ? pilihSem.value : 'semua';
                document.querySelectorAll('#daftarAgendaSidebar .event-item').forEach(function (el) {
                    var teks = el.getAttribute('data-search') || '';
                    var s = el.getAttribute('data-semester') || '';
                    var cocok = teks.indexOf(q) !== -1 && (sem === 'semua' || s === sem);
                    el.style.display = cocok ? 'flex' : 'none';
                });
            }
            if (inputCari) inputCari.addEventListener('input', saringSidebar);
            if (pilihSem) pilihSem.addEventListener('change', saringSidebar);

            var btnEditView = document.getElementById('btnEditDariView');
            if (btnEditView) {
                btnEditView.addEventListener('click', function () {
                    if (!agendaTerpilih) return;
                    bootstrap.Modal.getInstance(document.getElementById('modalLihatAgenda'))?.hide();
                    bukaModalEdit(agendaTerpilih);
                });
            }
        });

        function otomatisStatusLibur(kategori) {
            var chkLibur = document.getElementById('fieldIsLibur');
            if (!chkLibur) return;
            chkLibur.checked = ['libur_semester', 'libur_keagamaan', 'libur_nasional'].includes(kategori);
        }

        function bukaModalLihat(props) {
            agendaTerpilih = props;
            document.getElementById('viewJudul').textContent = props.judul || '-';
            document.getElementById('viewTanggal').textContent = props.rentang_label || '-';
            document.getElementById('viewKategori').textContent = props.kategori_label || '-';
            document.getElementById('viewDot').style.backgroundColor = props.kategori_color || '#0d6a56';
            document.getElementById('viewDeskripsi').textContent = props.deskripsi || 'Tidak ada keterangan tambahan.';

            var badges = document.getElementById('viewBadges');
            badges.innerHTML = '';

            var b1 = document.createElement('span');
            b1.className = props.is_hari_libur
                ? 'badge bg-danger-focus text-danger-main text-xs'
                : 'badge bg-success-focus text-success-main text-xs';
            b1.textContent = props.is_hari_libur ? 'Hari Libur' : 'Hari Masuk Madrasah';
            badges.appendChild(b1);

            var b2 = document.createElement('span');
            b2.className = 'badge bg-neutral-100 text-secondary-light text-xs';
            b2.textContent = props.is_publik ? 'Tampil di Publik' : 'Internal Dashboard';
            badges.appendChild(b2);

            new bootstrap.Modal(document.getElementById('modalLihatAgenda')).show();
        }

        function bukaModalTambah(tanggalDefault) {
            var form = document.getElementById('formAgenda');
            if (!form) return;

            form.reset();
            form.action = ROUTE_STORE;
            document.getElementById('methodFormAgenda').value = 'POST';
            document.getElementById('judulModalForm').textContent = 'Tambah Agenda Akademik Baru';
            document.getElementById('fieldIsPublik').checked = true;
            document.getElementById('fieldIsLibur').checked = false;

            if (tanggalDefault) {
                document.getElementById('fieldTanggalMulai').value = tanggalDefault;
                document.getElementById('fieldTanggalSelesai').value = tanggalDefault;
            }

            new bootstrap.Modal(document.getElementById('modalFormAgenda')).show();
        }

        function bukaModalEdit(props) {
            var form = document.getElementById('formAgenda');
            if (!form) return;

            form.action = ROUTE_BASE + '/' + props.id;
            document.getElementById('methodFormAgenda').value = 'PUT';
            document.getElementById('judulModalForm').textContent = 'Edit Agenda Akademik';
            document.getElementById('fieldJudul').value = props.judul || '';
            document.getElementById('fieldTanggalMulai').value = props.tanggal_mulai || '';
            document.getElementById('fieldTanggalSelesai').value = props.tanggal_selesai || '';
            document.getElementById('fieldDeskripsi').value = props.deskripsi || '';
            document.getElementById('fieldIsLibur').checked = !!props.is_hari_libur;
            document.getElementById('fieldIsPublik').checked = !!props.is_publik;

            var radio = document.getElementById('kat_' + props.kategori);
            if (radio) radio.checked = true;

            new bootstrap.Modal(document.getElementById('modalFormAgenda')).show();
        }

        function bukaModalHapus(id, judul) {
            document.getElementById('formHapusAgenda').action = ROUTE_BASE + '/' + id;
            document.getElementById('teksHapusAgenda').textContent = judul;
            new bootstrap.Modal(document.getElementById('modalHapusAgenda')).show();
        }
    </script>

@endsection