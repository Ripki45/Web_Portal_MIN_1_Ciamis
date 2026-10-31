@extends('layouts.admin')

@section('title', 'Rekap Absensi Kelas - MIN 1 Ciamis')

@section('content')

    <style>
        .sheet-container {
            background: #ffffff;
            color: #000000;
            font-family: Arial, Helvetica, sans-serif;
            padding: 24px;
            border-radius: 12px;
        }
        .sheet-title {
            font-size: 16px;
            font-weight: 800;
            text-align: center;
            line-height: 1.25;
            color: #000;
            text-transform: uppercase;
        }
        .meta-table td {
            padding: 1px 6px;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            line-height: 1.2;
        }
        .month-box {
            border: 2px solid #000;
            width: 68px;
            text-align: center;
            font-weight: 800;
        }
        .month-box-num {
            background-color: #ffff00;
            font-size: 20px;
            padding: 2px 0;
            border-bottom: 2px solid #000;
            color: #000;
            line-height: 1.1;
        }
        .month-box-year {
            background-color: #ffffff;
            font-size: 13px;
            padding: 2px 0;
            color: #000;
            line-height: 1.1;
        }
        .table-min1 {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }
        .table-min1 th, .table-min1 td {
            border: 1px solid #000 !important;
            padding: 3px 2px !important;
            font-size: 11px;
            color: #000;
            vertical-align: middle;
            line-height: 1.15;
        }
        .table-min1 thead th {
            background-color: #f3e9d2 !important;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
        }
        .col-tgl { width: 22px; min-width: 22px; max-width: 22px; text-align: center; }
        .col-jml { width: 26px; min-width: 26px; text-align: center; }
        .dot-hadir { font-size: 15px; font-weight: 900; line-height: 1; display: inline-block; }
        .table-min1 thead th.cell-libur,
        /* Pastikan tanggal Minggu di Header Tabel tetap berwarna Merah & Teks Putih Jelas */
        .table-min1 thead th.cell-libur,
        .cell-libur {
            background-color: #ef5350 !important;
            color: #ffffff !important;
        }
        .cell-libur-khusus {
            background-color: #ef5350 !important;
            color: #ffffff !important;
            padding: 4px 2px !important;
            text-align: center;
            vertical-align: middle !important;
        }
        .vertical-holiday-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            font-weight: 800;
            font-size: 10.5px;
            letter-spacing: 1px;
            color: #ffffff;
            display: inline-block;
            max-height: 300px;
            overflow: hidden;
        }
        .bg-row-sakit { background-color: #fdfae5 !important; }
        .bg-row-izin  { background-color: #e3f2fd !important; }
        .bg-row-alfa  { background-color: #ffffff !important; }
        .bg-tan-total { background-color: #c5b382 !important; font-weight: 800; }

        /* ================================================================= */
        /* LAYOUT FOOTER 3 KOLOM & LAYER TTD + CAP TERPISAH ANTI-NABRAK      */
        /* ================================================================= */
        .rekap-footer-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-top: 32px;
            padding-bottom: 12px;
            width: 100%;
        }
        .rekap-footer-col {
            flex: 1 1 0;
            width: 33%;
            text-align: center;
        }
        .rekap-footer-middle {
            flex: 1 1 0;
            width: 34%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 24px;
        }

        /* Kotak Kunci Ukuran Tanda Tangan (240px) agar posisi Cap & TTD selalu presisi */
        /* Kotak Kunci Ukuran Tanda Tangan agar posisi Cap & TTD selalu presisi */
        .sig-block {
            position: relative;
            display: inline-block;
            width: 250px;
            text-align: center;
            margin: 0 auto;
            padding: 6px 0;
        }
        .sig-text {
            position: relative;
            z-index: 1;
            line-height: 1.35;
            font-size: 12.5px;
            color: #000;
        }
        .sig-text p {
            margin: 0 0 2px 0 !important;
        }

        /* Layer Gambar TTD & Stempel */
        .img-ttd-digital {
            position: absolute;
            pointer-events: none;
            user-select: none;
            mix-blend-mode: multiply;
        }

        /* 1. Ukuran & Posisi Cap Kemenag (Masuk ke Kata "Mengetahui" & "Iim Siti") */
        .cap-madrasah-overlay {
            width: 152px;
            height: 152px;
            object-fit: contain;
            top: 47%;
            left: 50%;
            transform: translate(-78%, -50%);
            z-index: 2;
            opacity: 0.92;
        }

        /* 2. Ukuran & Posisi TTD Kepala Madrasah (Diperbesar & Menyatu dengan Cap) */
        .ttd-kamad-overlay {
            height: 165px;
            width: auto;
            max-width: none;
            object-fit: contain;
            top: 44%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
        }

        /* 3. Ukuran & Posisi TTD Wali Kelas / Guru Mapel */
        .ttd-wali-overlay {
            height: 110px;
            width: auto;
            max-width: none;
            object-fit: contain;
            top: 52%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
        }

        /* Responsif untuk Tablet & HP agar tidak pernah bertabrakan */
        @media screen and (max-width: 991px) {
            .sheet-container {
                padding: 16px 12px;
            }
            .rekap-footer-container {
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
            }
            .rekap-footer-middle {
                order: 1;
                width: 100% !important;
                flex: 0 0 100%;
                background: #f8fafc;
                border: 1px dashed #cbd5e1;
                border-radius: 12px;
                padding: 12px;
            }
            .rekap-col-kiri {
                order: 2;
                width: 48% !important;
                flex: 0 0 48%;
            }
            .rekap-col-kanan {
                order: 3;
                width: 48% !important;
                flex: 0 0 48%;
            }
        }

        /* Khusus Layar HP Kecil: Susun Ke Bawah Agar Cap & Nama Panjang Tidak Nabrak */
        @media screen and (max-width: 640px) {
            .rekap-col-kiri,
            .rekap-col-kanan {
                width: 100% !important;
                flex: 0 0 100%;
            }
            .rekap-col-kiri {
                padding-left: 20px; /* Beri ruang sedikit di kiri untuk Cap Stempel */
            }
        }
    </style>

    <!-- Toolbar Filter & Tombol Export Terpisah -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Rekap & Laporan Presensi Kelas</h6>
            <span class="text-xs text-secondary-light">Format Standar Buku Absen Manual MIN 1 Ciamis</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <!-- Saklar Tampilkan / Sembunyikan TTD Digital & Cap -->
            <div class="form-switch switch-success d-flex align-items-center gap-2 bg-white px-12 py-8 radius-8 border">
                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="toggleTtdDigital" checked onchange="aturTampilanTtd(this.checked)">
                <label class="form-check-label text-xs fw-bold text-primary-light cursor-pointer" for="toggleTtdDigital">
                    Tampilkan TTD & Cap Digital
                </label>
            </div>

            <!-- Tombol Menuju Halaman Khusus Export Excel -->
            <a href="{{ route('presensi.rekap', ['kategori' => $kategori ?? 'harian', 'kelas_id' => $selectedKelasId, 'ekskul_id' => $selectedEkskulId ?? '', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_mapel_id' => $selectedMapelId, 'export' => 'excel', 'ttd' => 1]) }}" 
               id="linkExportExcel" target="_blank"
               class="btn btn-success-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold">
                <i class="ph-bold ph-file-xls text-lg"></i> Download Excel (.xlsx)
            </a>

            <!-- Tombol Menuju Halaman Khusus Cetak / PDF Utuh -->
            <a href="{{ route('presensi.rekap', ['kategori' => $kategori ?? 'harian', 'kelas_id' => $selectedKelasId, 'ekskul_id' => $selectedEkskulId ?? '', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_mapel_id' => $selectedMapelId, 'export' => 'pdf', 'ttd' => 1]) }}" 
               id="linkExportPdf" target="_blank"
               class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold">
                <i class="ph-bold ph-printer text-lg"></i> Cetak / Simpan PDF Utuh
            </a>
        </div>
    </div>

    <!-- Filter Kategori Sesi, Kelas, Penandatangan, Bulan, & Tahun -->
    <div class="card radius-12 mb-20">
        <div class="card-body p-16">
            @php
                $userRekap      = auth()->user();
                $isGuruRekap    = $userRekap && $userRekap->role === 'guru';
                $isPembinaRekap = $userRekap && $userRekap->role === 'pembina_ekskul';
            @endphp
            <form action="{{ route('presensi.rekap') }}" method="GET" class="row gy-3 align-items-end">
                <div class="col-md-3">
                    @if ($isPembinaRekap)
                        {{-- Jika Pembina Ekskul: Kunci Kategori ke Ekstrakurikuler Tanpa Dropdown --}}
                        <label class="form-label fw-semibold text-xs mb-4">Kategori Sesi Presensi:</label>
                        <input type="hidden" name="kategori" value="ekskul">
                        <div class="form-control form-control-sm radius-8 bg-warning-focus text-warning-main border-warning fw-bold">
                            ⛺ Kegiatan Ekstrakurikuler
                        </div>
                    @else
                        <label class="form-label fw-semibold text-xs mb-4">Kategori Sesi Presensi:</label>
                        <select name="kategori" class="form-select form-select-sm radius-8 fw-bold border-primary" onchange="this.form.submit()">
                            <option value="harian" {{ ($kategori ?? 'harian') == 'harian' ? 'selected' : '' }}>🏫 1. Kehadiran Harian (KBM)</option>
                            <option value="shalat" {{ ($kategori ?? '') == 'shalat' ? 'selected' : '' }}>🕌 2. Shalat Dzuhur Berjamaah</option>
                            @if (!$isGuruRekap)
                                <option value="ekskul" {{ ($kategori ?? '') == 'ekskul' ? 'selected' : '' }}>⛺ 3. Kegiatan Ekstrakurikuler</option>
                            @endif
                        </select>
                    @endif
                </div>

                @if (($kategori ?? 'harian') === 'ekskul')
                    <div class="col-md-5">
                        @if ($isPembinaRekap && ($ekskulList ?? collect())->count() <= 1)
                            {{-- Jika Pembina hanya memegang 1 Ekskul: Kunci Tanpa Dropdown --}}
                            <label class="form-label fw-semibold text-xs mb-4">Ekstrakurikuler Dibina:</label>
                            <input type="hidden" name="ekskul_id" value="{{ $selectedEkskulId }}">
                            <div class="form-control form-control-sm radius-8 bg-primary-50 text-primary-600 border-primary fw-bold">
                                {{ $ekskulTerpilih->nama_ekskul ?? 'Belum Diplot' }} (Pembina: {{ $ekskulTerpilih->pembina->nama_lengkap ?? '-' }})
                            </div>
                        @else
                            <label class="form-label fw-semibold text-xs mb-4">Pilih Kegiatan Ekstrakurikuler:</label>
                            <select name="ekskul_id" class="form-select form-select-sm radius-8 fw-bold text-warning-main" onchange="this.form.submit()">
                                <?php foreach (($ekskulList ?? []) as $eks): ?>
                                    <option value="{{ $eks->id }}" {{ ($selectedEkskulId ?? '') == $eks->id ? 'selected' : '' }}>
                                        {{ $eks->nama_ekskul }} (Pembina: {{ $eks->pembina->nama_lengkap ?? '-' }})
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        @endif
                    </div>
                @else
                    <div class="col-md-2">
                        @if ($isGuruRekap && $kelasList->count() <= 1)
                            <label class="form-label fw-semibold text-xs mb-4">Kelas Diampu:</label>
                            <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                            <div class="form-control form-control-sm radius-8 bg-primary-50 text-primary-600 border-primary fw-bold">
                                {{ $kelasTerpilih->nama_kelas ?? '-' }}
                            </div>
                        @else
                            <label class="form-label fw-semibold text-xs mb-4">{{ $isGuruRekap ? 'Kelas Ampuan:' : 'Pilih Kelas:' }}</label>
                            <select name="kelas_id" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()">
                                <?php foreach ($kelasList as $kls): ?>
                                    <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>
                                        {{ $kls->nama_kelas }}
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-xs mb-4">Penandatangan (Wali / Guru Mapel):</label>
                        <select name="kelas_mapel_id" class="form-select form-select-sm radius-8 fw-bold text-success-600" onchange="this.form.submit()">
                            <option value="">★ Wali Kelas ({{ $kelasTerpilih->waliKelas->nama_lengkap ?? 'Default' }})</option>
                            <?php foreach ($daftarMapelKelas as $km): ?>
                                <option value="{{ $km->id }}" {{ $selectedMapelId == $km->id ? 'selected' : '' }}>
                                    Guru {{ $km->mapel->nama_mapel ?? '-' }} — {{ $km->guru->nama_lengkap ?? 'Belum diset' }}
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                @endif

                <div class="col-md-2">
                    <label class="form-label fw-semibold text-xs mb-4">Bulan:</label>
                    <select name="bulan" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()">
                        <?php foreach ($namaBulanList as $num => $namaBln): ?>
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $num }} - {{ $namaBln }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-xs mb-4">Tahun:</label>
                    <select name="tahun" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()">
                        <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Lembar Buku Absen -->
    <div class="sheet-container shadow-sm border">

        <div class="sheet-title mb-12">
            MADRASAH IBTIDAIYAH NEGERI 1 CIAMIS<br>
            TAHUN AJARAN {{ $tahunAktif->tahun_ajaran ?? '2026/2027' }}
        </div>

        <div class="d-flex justify-content-between align-items-end mb-12 px-1">
            <table class="meta-table">
                <tr>
                    <td width="65">{{ ($kategori ?? 'harian') === 'ekskul' ? 'EKSKUL' : 'KELAS' }}</td>
                    <td>
                        @if (($kategori ?? 'harian') === 'ekskul')
                            : {{ $ekskulTerpilih->nama_ekskul ?? '-' }}
                        @else
                            : {{ str_replace('Kelas ', '', $kelasTerpilih->nama_kelas ?? '-') }} {{ $labelTingkat ? '(' . $labelTingkat . ')' : '' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>BULAN</td>
                    <td>: {{ $namaBulan }}</td>
                </tr>
                <tr>
                    <td>TAHUN</td>
                    <td>: {{ $tahun }}</td>
                </tr>
            </table>

            <div class="month-box">
                <div class="month-box-num">{{ $bulan }}</div>
                <div class="month-box-year">{{ $tahun }}</div>
            </div>
        </div>

        @php
            $rekapHarianS    = array_fill(1, $jumlahHari, 0);
            $rekapHarianI    = array_fill(1, $jumlahHari, 0);
            $rekapHarianA    = array_fill(1, $jumlahHari, 0);
            $grandTotalS     = 0;
            $grandTotalI     = 0;
            $grandTotalA     = 0;
            $grandTotalAbsen = 0;
            $totalSiswa      = $siswas->count();
            $hariEfektif     = max(0, $jumlahHari - count($tanggalLibur));
        @endphp

        <div class="table-responsive">
            <table class="table-min1">
                <thead>
                    <tr>
                        <th rowspan="2" width="3%">NO</th>
                        <th rowspan="2" width="11%">NO INDUK</th>
                        <th rowspan="2" width="9%">NISN</th>
                        <th rowspan="2" width="18%">NAMA</th>
                        <th colspan="{{ $jumlahHari }}">TANGGAL</th>
                        <th colspan="4">JUMLAH</th>
                    </tr>
                    <tr>
                        <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                            @php $isLibur = isset($tanggalLibur[$d]); @endphp
                            <th class="col-tgl {{ $isLibur ? 'cell-libur' : '' }}" title="{{ $tanggalLibur[$d]['keterangan'] ?? '' }}">
                                {{ sprintf('%02d', $d) }}
                            </th>
                        <?php endfor; ?>
                        <th class="col-jml bg-row-sakit">S</th>
                        <th class="col-jml bg-row-izin">I</th>
                        <th class="col-jml bg-row-alfa">A</th>
                        <th class="col-jml bg-tan-total">JML</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($siswas->isEmpty())
                        <tr>
                            <td colspan="{{ $jumlahHari + 8 }}" class="text-center py-24 text-secondary">
                                Belum ada data siswa aktif di kelas/kegiatan ini.
                            </td>
                        </tr>
                    @else
                        <?php foreach ($siswas as $idx => $s): ?>
                            @php $jmlS = 0; $jmlI = 0; $jmlA = 0; @endphp
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="text-center" style="font-size: 10px;">{{ $s->nomor_induk ?? '' }}</td>
                                <td class="text-center" style="font-size: 10px;">{{ $s->nisn ?? '' }}</td>
                                <td class="fw-semibold text-nowrap px-2">{{ $s->nama_siswa }}</td>

                                <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                                    @php
                                        $infoLibur = $tanggalLibur[$d] ?? null;
                                        $kode      = $matriks[$s->id][$d] ?? '';
                                        if (!$infoLibur) {
                                            if ($kode === 'S') { $jmlS++; $rekapHarianS[$d]++; }
                                            elseif ($kode === 'I') { $jmlI++; $rekapHarianI[$d]++; }
                                            elseif ($kode === 'A') { $jmlA++; $rekapHarianA[$d]++; }
                                        }
                                    @endphp

                                    @if ($infoLibur)
                                        @if ($infoLibur['tipe'] === 'khusus')
                                            @if ($idx === 0)
                                                <td rowspan="{{ $totalSiswa }}" class="col-tgl cell-libur-khusus" title="{{ $infoLibur['keterangan'] }}">
                                                    <span class="vertical-holiday-text">{{ $infoLibur['keterangan'] }}</span>
                                                </td>
                                            @endif
                                        @else
                                            <td class="col-tgl cell-libur"></td>
                                        @endif
                                    @else
                                        <td class="col-tgl">
                                            @if ($kode === '•')
                                                <span class="dot-hadir">&bull;</span>
                                            @elseif ($kode === 'S')
                                                <b class="text-dark">s</b>
                                            @elseif ($kode === 'I')
                                                <b class="text-dark">i</b>
                                            @elseif ($kode === 'A')
                                                <b class="text-dark">a</b>
                                            @endif
                                        </td>
                                    @endif
                                <?php endfor; ?>

                                @php
                                    $jmlAbsenSiswa = $jmlS + $jmlI + $jmlA;
                                    $grandTotalS  += $jmlS;
                                    $grandTotalI  += $jmlI;
                                    $grandTotalA  += $jmlA;
                                @endphp

                                <td class="col-jml bg-row-sakit fw-bold">{{ $jmlS ?: '' }}</td>
                                <td class="col-jml bg-row-izin fw-bold">{{ $jmlI ?: '' }}</td>
                                <td class="col-jml bg-row-alfa fw-bold">{{ $jmlA ?: '' }}</td>
                                <td class="col-jml bg-tan-total">{{ $jmlAbsenSiswa ?: '' }}</td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td>&nbsp;</td><td></td><td></td><td></td>
                            <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                                <td class="col-tgl {{ isset($tanggalLibur[$d]) ? 'cell-libur' : '' }}"></td>
                            <?php endfor; ?>
                            <td class="bg-row-sakit"></td><td class="bg-row-izin"></td><td class="bg-row-alfa"></td><td class="bg-tan-total"></td>
                        </tr>

                        <tr>
                            <td></td><td></td><td></td>
                            <td class="fw-bold bg-row-sakit px-2">Sakit</td>
                            <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                                @if (isset($tanggalLibur[$d]))
                                    <td class="col-tgl cell-libur"></td>
                                @else
                                    <td class="col-tgl bg-row-sakit fw-bold">{{ $rekapHarianS[$d] ?: '' }}</td>
                                @endif
                            <?php endfor; ?>
                            <td class="col-jml bg-row-sakit fw-bold">{{ $grandTotalS ?: '' }}</td>
                            <td class="col-jml bg-row-izin"></td><td class="col-jml bg-row-alfa"></td><td class="col-jml bg-tan-total"></td>
                        </tr>

                        <tr>
                            <td></td><td></td><td></td>
                            <td class="fw-bold bg-row-izin px-2">Izin</td>
                            <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                                @if (isset($tanggalLibur[$d]))
                                    <td class="col-tgl cell-libur"></td>
                                @else
                                    <td class="col-tgl bg-row-izin fw-bold">{{ $rekapHarianI[$d] ?: '' }}</td>
                                @endif
                            <?php endfor; ?>
                            <td class="col-jml bg-row-sakit"></td>
                            <td class="col-jml bg-row-izin fw-bold">{{ $grandTotalI ?: '' }}</td>
                            <td class="col-jml bg-row-alfa"></td><td class="col-jml bg-tan-total"></td>
                        </tr>

                        <tr>
                            <td></td><td></td><td></td>
                            <td class="fw-bold bg-row-alfa px-2">Alfa</td>
                            <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                                @if (isset($tanggalLibur[$d]))
                                    <td class="col-tgl cell-libur"></td>
                                @else
                                    <td class="col-tgl bg-row-alfa fw-bold">{{ $rekapHarianA[$d] ?: '' }}</td>
                                @endif
                            <?php endfor; ?>
                            <td class="col-jml bg-row-sakit"></td><td class="col-jml bg-row-izin"></td>
                            <td class="col-jml bg-row-alfa fw-bold">{{ $grandTotalA ?: '' }}</td>
                            <td class="col-jml bg-tan-total"></td>
                        </tr>

                        @php $grandTotalAbsen = $grandTotalS + $grandTotalI + $grandTotalA; @endphp
                        <tr class="bg-tan-total">
                            <td colspan="4" class="text-center fw-bold">JUMLAH</td>
                            <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                                @if (isset($tanggalLibur[$d]))
                                    <td class="col-tgl cell-libur"></td>
                                @else
                                    @php $totHariIni = $rekapHarianS[$d] + $rekapHarianI[$d] + $rekapHarianA[$d]; @endphp
                                    <td class="col-tgl bg-tan-total">{{ $totHariIni ?: '' }}</td>
                                @endif
                            <?php endfor; ?>
                            <td class="col-jml bg-tan-total">{{ $grandTotalS ?: '' }}</td>
                            <td class="col-jml bg-tan-total">{{ $grandTotalI ?: '' }}</td>
                            <td class="col-jml bg-tan-total">{{ $grandTotalA ?: '' }}</td>
                            <td class="col-jml bg-tan-total">{{ $grandTotalAbsen ?: '' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @php
            $pembagiPersen = ($totalSiswa * max(1, $hariEfektif));
            $persenAbsen   = $totalSiswa > 0 ? round(($grandTotalAbsen * 100) / $pembagiPersen, 2) : 0;
        @endphp

        <!-- ===================================================================== -->
        <!-- FOOTER REKAP: KIRI (KAMAD + CAP) | TENGAH (PROSENTASE) | KANAN (GURU) -->
        <!-- ===================================================================== -->
        <div class="rekap-footer-container">

            <!-- 1. SISI KIRI: MENGETAHUI KEPALA MADRASAH + CAP TERPISAH -->
            <div class="rekap-footer-col rekap-col-kiri">
                <div class="sig-block">
                    <div class="sig-text">
                        <p>Mengetahui,</p>
                        <p>Kepala Madrasah</p>
                        <div style="height: 64px;"></div>
                        <p style="font-weight: bold; text-decoration: underline; white-space: nowrap;">
                            {{ $kepalaMadrasah->nama_lengkap ?? '(Kepala Madrasah Belum Diatur)' }}
                        </p>
                        <p style="white-space: nowrap;">
                            NIP. {{ $kepalaMadrasah->nip ?? $kepalaMadrasah->nomor_induk ?? '-' }}
                        </p>
                    </div>

                    <!-- Layer 1: Cap Resmi MIN 1 Ciamis (Terpisah & Dinamis) -->
                    @if (file_exists(public_path('backend/assets/images/cap_min_satu.png')))
                        <img src="{{ asset('backend/assets/images/cap_min_satu.png') }}"
                             alt="Cap MIN 1 Ciamis"
                             class="img-ttd-digital cap-madrasah-overlay">
                    @endif

                    <!-- Layer 2: Tanda Tangan Dinamis Kepala Madrasah dari Database GTK -->
                    @if ($kepalaMadrasah && $kepalaMadrasah->ttd)
                        <img src="{{ asset('storage/' . $kepalaMadrasah->ttd) }}"
                             alt="TTD Kepala Madrasah"
                             class="img-ttd-digital ttd-kamad-overlay">
                    @endif
                </div>
            </div>

            <!-- 2. SISI TENGAH: RUMUS PROSENTASE ABSEN BULAN INI -->
            <div class="rekap-footer-middle">
                <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-2" style="font-size: 12px; color: #000;">
                    <span class="fw-bold">Prosentase Absen Bulan Ini</span>
                    <div class="text-center fw-bold">
                        <div style="border-bottom: 1.5px solid #000; padding: 0 8px 2px 8px;">
                            {{ $grandTotalAbsen }} &times; 100
                        </div>
                        <div style="padding: 2px 8px 0 8px;">
                            {{ $totalSiswa }} &times; {{ $hariEfektif }} = {{ $pembagiPersen }}
                        </div>
                    </div>
                    <span class="fw-extrabold" style="font-size: 13px;">
                        = {{ $persenAbsen }}%
                    </span>
                </div>
            </div>

            <!-- 3. SISI KANAN: WALI KELAS / GURU MAPEL / PEMBINA EKSKUL -->
            <div class="rekap-footer-col rekap-col-kanan">
                <div class="sig-block">
                    <div class="sig-text">
                        <p>Ciamis, {{ $namaBulan }} {{ $tahun }}</p>
                        <p>{{ $jabatanPenandatangan }}</p>
                        <div style="height: 64px;"></div>
                        <p style="font-weight: bold; text-decoration: underline; white-space: nowrap;">
                            {{ $penandatanganGuru->nama_lengkap ?? '(Guru Belum Dipilih)' }}
                        </p>
                        <p style="white-space: nowrap;">
                            NIP. {{ $penandatanganGuru->nip ?? $penandatanganGuru->nomor_induk ?? '-' }}
                        </p>
                    </div>

                    @if ($penandatanganGuru && $penandatanganGuru->ttd)
                        <img src="{{ asset('storage/' . $penandatanganGuru->ttd) }}"
                             alt="TTD Guru"
                             class="img-ttd-digital ttd-wali-overlay">
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script>
    function aturTampilanTtd(tampilkan) {
        document.querySelectorAll('.img-ttd-digital').forEach(img => {
            img.style.display = tampilkan ? 'block' : 'none';
        });

        const nilaiTtd = tampilkan ? '1' : '0';
        const linkExcel = document.getElementById('linkExportExcel');
        const linkPdf   = document.getElementById('linkExportPdf');

        if (linkExcel) {
            const urlEx = new URL(linkExcel.href);
            urlEx.searchParams.set('ttd', nilaiTtd);
            linkExcel.href = urlEx.toString();
        }
        if (linkPdf) {
            const urlPd = new URL(linkPdf.href);
            urlPd.searchParams.set('ttd', nilaiTtd);
            linkPdf.href = urlPd.toString();
        }
    }
</script>
@endpush