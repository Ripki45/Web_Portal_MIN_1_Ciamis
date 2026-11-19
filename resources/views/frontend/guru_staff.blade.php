@extends('layouts.frontend')

@section('title', 'Guru dan Staf — MIN 1 Ciamis')

@section('content')

    <style>
        .gs-page-section {
            padding: 36px 0 80px;
            background: #f8fafc;
        }

        /* 1. Banner Foto Bersama Guru & Staf Besar */
        .gs-hero-banner-card {
            background: #ffffff;
            border-radius: 28px;
            padding: 18px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.07);
            border: 1px solid #e2e8f0;
            margin-bottom: 52px;
        }
        .gs-hero-img-wrap {
            position: relative;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 16 / 7.2;
            background: #0f172a;
        }
        .gs-hero-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }
        .gs-hero-banner-card:hover .gs-hero-img-wrap img {
            transform: scale(1.02);
        }
        .gs-hero-caption-bar {
            text-align: center;
            padding: 32px 24px 18px;
            max-width: 820px;
            margin: 0 auto;
        }
        .gs-eyebrow-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 6px 18px;
            border-radius: 9999px;
            margin-bottom: 14px;
        }
        .gs-main-heading {
            font-family: 'Outfit', sans-serif;
            font-size: 2.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 10px;
        }
        .gs-sub-heading {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* 2. Blok Tabel Point A, B, C... */
        .gs-group-card {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            margin-bottom: 28px;
        }
        .gs-group-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 26px;
            background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
            border-bottom: 1.5px solid #e2e8f0;
        }
        .gs-letter-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #1B9C85;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 14px rgba(27, 156, 133, 0.25);
        }
        .gs-group-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .gs-group-count {
            margin-left: auto;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 9999px;
        }

        .gs-table-wrap {
            width: 100%;
            overflow-x: auto;
        }
        .gs-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .gs-table th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 13px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .gs-table td {
            padding: 14px 24px;
            font-size: 0.94rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .gs-table tbody tr:last-child td {
            border-bottom: none;
        }
        .gs-table tbody tr:hover {
            background: #f0fdf4;
        }
        .gs-td-no {
            width: 70px;
            font-weight: 700;
            color: #64748b;
        }
        .gs-td-jabatan {
            width: 44%;
            font-weight: 600;
            color: #0d6a56;
        }
        .gs-td-nama {
            font-weight: 700;
            color: #0f172a;
        }

        /* 3. Direktori Menyeluruh & Filter Golongan + Jabatan */
        .gs-directory-box {
            background: #ffffff;
            border-radius: 26px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.05);
            padding: 36px 32px;
            margin-top: 56px;
        }
        .gs-filter-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .gs-filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }
        .gs-filter-label {
            font-size: 0.78rem;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 135px;
        }
        .gs-pill-btn {
            padding: 6px 15px;
            border-radius: 9999px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .gs-pill-btn:hover {
            border-color: #1B9C85;
            color: #1B9C85;
        }
        .gs-pill-btn.active {
            background: #1B9C85;
            border-color: #1B9C85;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(27, 156, 133, 0.25);
        }
        .gs-pill-btn.active-amber {
            background: #d97706;
            border-color: #d97706;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(217, 119, 6, 0.25);
        }
        .gs-search-input {
            padding: 10px 18px;
            border-radius: 9999px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.88rem;
            width: 100%;
            max-width: 340px;
            outline: none;
            background: #ffffff;
        }
        .gs-search-input:focus {
            border-color: #1B9C85;
        }
        .gs-badge-golongan {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            font-size: 0.74rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 9999px;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .gs-main-heading { font-size: 1.65rem; }
            .gs-hero-img-wrap { aspect-ratio: 16 / 9; }
            .gs-group-header { padding: 16px 18px; }
            .gs-table th, .gs-table td { padding: 11px 14px; font-size: 0.84rem; }
            .gs-directory-box { padding: 24px 16px; }
            .gs-filter-label { min-width: 100%; }
        }
    </style>

    <section class="gs-page-section">
        <div class="container">

            <!-- ========================================================================= -->
            <!-- 1. BANNER FOTO BERSAMA GURU & STAF BESAR                                  -->
            <!-- ========================================================================= -->
            <div class="gs-hero-banner-card">
                <div class="gs-hero-img-wrap">
                    <img src="{{ $profil->foto_bersama_guru_url }}"
                         alt="Foto Bersama Guru dan Staf MIN 1 Ciamis"
                         onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                </div>

                <div class="gs-hero-caption-bar">
                    <div class="gs-eyebrow-pill">
                        <i class="fas fa-users"></i> Pendidik &amp; Tenaga Kependidikan
                    </div>
                    <h1 class="gs-main-heading">
                        {{ $profil->judul_guru_staff ?? 'Dewan Guru dan Tenaga Kependidikan MIN 1 Ciamis' }}
                    </h1>
                    <p class="gs-sub-heading">
                        {{ $profil->subjudul_guru_staff ?? 'Mewujudkan generasi Qur\'ani yang unggul, berakhlak mulia, berprestasi, dan berwawasan lingkungan.' }}
                    </p>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 2. TABEL SUSUNAN POINT (A, B, C...) — NAMA DITARIK DARI DATABASE GTK      -->
            <!-- ========================================================================= -->
            @foreach ($strukturPoints as $pt)
                @php
                    $barisValid = collect($pt['rows'] ?? [])->filter(function ($r) use ($gtkMap) {
                        $idG = (int) ($r['gtk_id'] ?? 0);
                        return isset($gtkMap[$idG]) ? true : !empty($r['jabatan']);
                    })->values();
                @endphp

                <div class="gs-group-card">
                    <div class="gs-group-header">
                        <div class="gs-letter-badge">{{ $pt['kode'] ?? 'A' }}</div>
                        <h2 class="gs-group-title">{{ $pt['nama'] ?? 'Kelompok Tugas' }}</h2>
                        <span class="gs-group-count">{{ $barisValid->count() }} Personil</span>
                    </div>

                    <div class="gs-table-wrap">
                        <table class="gs-table">
                            <thead>
                                <tr>
                                    <th class="gs-td-no">No</th>
                                    <th class="gs-td-jabatan">Jabatan / Bidang Tugas</th>
                                    <th>Nama Lengkap</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barisValid as $idx => $row)
                                    @php
                                        $gtkObj = $gtkMap[(int) ($row['gtk_id'] ?? 0)] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="gs-td-no">{{ $idx + 1 }}</td>
                                        <td class="gs-td-jabatan">{{ $row['jabatan'] ?? '-' }}</td>
                                        <td class="gs-td-nama">{{ $gtkObj ? $gtkObj->nama_lengkap : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align: center; color: #94a3b8; padding: 20px;">
                                            Belum ada personil yang ditugaskan pada kelompok ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <!-- ========================================================================= -->
            <!-- 3. DIREKTORI MENYELURUH (FILTER GOLONGAN & JABATAN DARI DATABASE GTK)     -->
            <!-- ========================================================================= -->
            <div class="gs-directory-box" id="direktori-lengkap">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-20">
                    <div>
                        <span class="section-subtitle" style="margin-bottom: 4px;">Direktori Menyeluruh</span>
                        <h2 class="section-title" style="font-size: 1.85rem; margin-bottom: 6px; text-align: left;">
                            Daftar Lengkap Pendidik dan Tenaga Kependidikan
                        </h2>
                        <p style="color: #64748b; font-size: 0.92rem; margin: 0;">
                            Menampilkan <strong id="labelJumlahTampil">{{ $gtksProcessed->count() }}</strong> Personil MIN 1 Ciamis
                        </p>
                    </div>

                    <input type="text"
                           id="inputCariGtkPublik"
                           class="gs-search-input"
                           placeholder="🔍 Cari nama guru, jabatan, atau golongan..."
                           oninput="jalankanFilterDirektoriGtk()">
                </div>

                <!-- Panel Filter Ganda: 1. Golongan/Status & 2. Jabatan -->
                <div class="gs-filter-panel">
                    <!-- Filter 1: Berdasarkan Golongan / Status Kepegawaian (PNS, PPPK, dst.) -->
                    <div class="gs-filter-row">
                        <span class="gs-filter-label"><i class="fas fa-id-badge text-success me-1"></i> Golongan / Status:</span>
                        <button type="button" class="gs-pill-btn btn-filter-gol active" onclick="pilihFilterGolongan('semua', this)">
                            Semua ({{ $gtksProcessed->count() }})
                        </button>
                        @foreach ($daftarGolongan as $gol)
                            @php
                                $jmlGol = $gtksProcessed->where('golongan_tampil', $gol)->count();
                            @endphp
                            <button type="button" class="gs-pill-btn btn-filter-gol" onclick="pilihFilterGolongan('{{ addslashes($gol) }}', this)">
                                {{ $gol }} ({{ $jmlGol }})
                            </button>
                        @endforeach
                    </div>

                    <!-- Filter 2: Berdasarkan Jabatan (Wali Kelas, Guru Mapel, Kepala Madrasah, dst.) -->
                    <div class="gs-filter-row pt-8" style="border-top: 1px dashed #cbd5e1;">
                        <span class="gs-filter-label"><i class="fas fa-briefcase text-warning me-1"></i> Jabatan / Tugas:</span>
                        <button type="button" class="gs-pill-btn btn-filter-jab active-amber" onclick="pilihFilterJabatan('semua', this)">
                            Semua Jabatan
                        </button>
                        @foreach ($daftarJabatan as $jab)
                            @php
                                $jmlJab = $gtksProcessed->filter(function ($item) use ($jab) {
                                    return in_array($jab, $item->tags_jabatan ?? []);
                                })->count();
                            @endphp
                            <button type="button" class="gs-pill-btn btn-filter-jab" onclick="pilihFilterJabatan('{{ addslashes($jab) }}', this)">
                                {{ $jab }} ({{ $jmlJab }})
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tabel Lengkap Personil GTK -->
                <div class="gs-table-wrap">
                    <table class="gs-table">
                        <thead>
                            <tr>
                                <th class="gs-td-no">No</th>
                                <th>Nama Lengkap</th>
                                <th>Jabatan / Posisi</th>
                                <th width="18%">Golongan / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gtksProcessed as $idx => $g)
                                <tr class="baris-gtk-direktori"
                                    data-search="{{ strtolower($g->nama_lengkap . ' ' . $g->jabatan_tampil . ' ' . ($g->wali_info ?? '') . ' ' . $g->golongan_tampil) }}"
                                    data-golongan="{{ $g->golongan_tampil }}"
                                    data-jabatans='@json($g->tags_jabatan)'>
                                    <td class="gs-td-no nomor-urut-gtk">{{ $idx + 1 }}</td>
                                    <td class="gs-td-nama">{{ $g->nama_lengkap }}</td>
                                    <td class="gs-td-jabatan">
                                        {{ strtoupper($g->jabatan_tampil) }}
                                        @if (!empty($g->wali_info))
                                            <span style="display: inline-block; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; margin-left: 6px;">
                                                {{ $g->wali_info }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="gs-badge-golongan">
                                            {{ strtoupper($g->golongan_tampil) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div id="pesanKosongGtk" style="display: none; text-align: center; padding: 36px 16px; color: #64748b;">
                        Tidak ditemukan pendidik atau tenaga kependidikan yang sesuai dengan kombinasi filter Golongan &amp; Jabatan tersebut.
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        var filterGolAktif = 'semua';
        var filterJabAktif = 'semua';

        function pilihFilterGolongan(gol, btnEl) {
            filterGolAktif = gol;
            document.querySelectorAll('.btn-filter-gol').forEach(function (b) {
                b.classList.remove('active');
            });
            if (btnEl) btnEl.classList.add('active');
            jalankanFilterDirektoriGtk();
        }

        function pilihFilterJabatan(jab, btnEl) {
            filterJabAktif = jab;
            document.querySelectorAll('.btn-filter-jab').forEach(function (b) {
                b.classList.remove('active-amber');
            });
            if (btnEl) btnEl.classList.add('active-amber');
            jalankanFilterDirektoriGtk();
        }

        function jalankanFilterDirektoriGtk() {
            var keyword = (document.getElementById('inputCariGtkPublik')?.value ?? '').toLowerCase().trim();
            var rows = document.querySelectorAll('.baris-gtk-direktori');
            var nomor = 1;
            var tampilCount = 0;

            rows.forEach(function (row) {
                var teksCari = row.getAttribute('data-search') ?? '';
                var golRow   = row.getAttribute('data-golongan') ?? '';
                var jabsRow  = [];
                try {
                    jabsRow = JSON.parse(row.getAttribute('data-jabatans') ?? '[]');
                } catch (e) {}

                var cocokKata = (keyword === '') ? true : teksCari.includes(keyword);
                var cocokGol  = (filterGolAktif === 'semua') ? true : (golRow === filterGolAktif);
                var cocokJab  = (filterJabAktif === 'semua') ? true : jabsRow.includes(filterJabAktif);

                if (cocokKata && cocokGol && cocokJab) {
                    row.style.display = '';
                    var selNo = row.querySelector('.nomor-urut-gtk');
                    if (selNo) selNo.textContent = nomor++;
                    tampilCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            var lblJumlah = document.getElementById('labelJumlahTampil');
            if (lblJumlah) lblJumlah.textContent = tampilCount;

            var pesanKosong = document.getElementById('pesanKosongGtk');
            if (pesanKosong) {
                pesanKosong.style.display = (tampilCount === 0) ? 'block' : 'none';
            }
        }
    </script>

@endsection