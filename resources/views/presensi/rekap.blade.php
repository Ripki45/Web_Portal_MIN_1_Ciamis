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
        .cell-libur { background-color: #ef5350 !important; color: #ffffff !important; }
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

        .sig-block {
            position: relative;
            display: inline-block;
            text-align: center;
            min-width: 240px;
            padding: 4px 12px;
        }
        .sig-text {
            position: relative;
            z-index: 1;
            line-height: 1.2;
        }
        .sig-text p {
            margin-bottom: 2px !important;
            line-height: 1.2 !important;
        }
        .ttd-kepala-overlay {
            position: absolute;
            top: 42%;
            left: 50%;
            transform: translate(-64%, -50%);
            height: 142px;
            width: auto;
            max-width: none;
            object-fit: contain;
            mix-blend-mode: multiply;
            pointer-events: none;
            z-index: 2;
        }
        .ttd-wali-overlay {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 108px;
            width: auto;
            max-width: none;
            object-fit: contain;
            mix-blend-mode: multiply;
            pointer-events: none;
            z-index: 2;
        }
    </style>

    <!-- Toolbar Filter & Tombol Export Terpisah -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Rekap & Laporan Presensi Kelas</h6>
            <span class="text-xs text-secondary-light">Format Standar Buku Absen Manual MIN 1 Ciamis</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <!-- Saklar Tampilkan / Sembunyikan TTD Digital -->
            <div class="form-switch switch-success d-flex align-items-center gap-2 bg-white px-12 py-8 radius-8 border">
                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="toggleTtdDigital" checked onchange="aturTampilanTtd(this.checked)">
                <label class="form-check-label text-xs fw-bold text-primary-light cursor-pointer" for="toggleTtdDigital">
                    Tampilkan TTD Digital
                </label>
            </div>

            <!-- Tombol Menuju Halaman Khusus Export Excel -->
            <a href="{{ route('presensi.rekap', ['kelas_id' => $selectedKelasId, 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_mapel_id' => $selectedMapelId, 'export' => 'excel', 'ttd' => 1]) }}" 
               id="linkExportExcel" target="_blank"
               class="btn btn-success-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold">
                <i class="ph-bold ph-file-xls text-lg"></i> Download Excel (.xlsx)
            </a>

            <!-- Tombol Menuju Halaman Khusus Cetak / PDF Utuh -->
            <a href="{{ route('presensi.rekap', ['kelas_id' => $selectedKelasId, 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_mapel_id' => $selectedMapelId, 'export' => 'pdf', 'ttd' => 1]) }}" 
               id="linkExportPdf" target="_blank"
               class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold">
                <i class="ph-bold ph-printer text-lg"></i> Cetak / Simpan PDF Utuh
            </a>
        </div>
    </div>

    <!-- Filter Kelas, Bulan, & Tahun -->
    <div class="card radius-12 mb-20">
        <div class="card-body p-16">
            <form action="{{ route('presensi.rekap') }}" method="GET" class="row gy-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-xs mb-4">Pilih Kelas / Rombel:</label>
                    <select name="kelas_id" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()">
                        <?php foreach ($kelasList as $kls): ?>
                            <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>
                                {{ $kls->nama_kelas }} (Wali: {{ $kls->waliKelas->nama_lengkap ?? '-' }})
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-xs mb-4">Penandatangan Laporan (Wali Kelas / Guru Mapel):</label>
                    <select name="kelas_mapel_id" class="form-select form-select-sm radius-8 fw-bold text-success-600" onchange="this.form.submit()">
                        <option value="">★ Wali Kelas ({{ $kelasTerpilih->waliKelas->nama_lengkap ?? 'Default' }})</option>
                        <?php foreach ($daftarMapelKelas as $km): ?>
                            <option value="{{ $km->id }}" {{ $selectedMapelId == $km->id ? 'selected' : '' }}>
                                Guru {{ $km->mapel->nama_mapel ?? '-' }} — {{ $km->guru->nama_lengkap ?? 'Belum diset' }}
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary-600 btn-sm radius-8 w-100 py-8 fw-bold">
                        Filter
                    </button>
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
                    <td width="65">KELAS</td>
                    <td>: {{ str_replace('Kelas ', '', $kelasTerpilih->nama_kelas ?? '-') }} {{ $labelTingkat ? '(' . $labelTingkat . ')' : '' }}</td>
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
            $rekapHarianS = array_fill(1, $jumlahHari, 0);
            $rekapHarianI = array_fill(1, $jumlahHari, 0);
            $rekapHarianA = array_fill(1, $jumlahHari, 0);
            $grandTotalS  = 0;
            $grandTotalI  = 0;
            $grandTotalA  = 0;
            $totalSiswa   = $siswas->count();
            $hariEfektif  = $jumlahHari - count($tanggalLibur);
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
                                Belum ada data siswa aktif di kelas ini.
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

        <!-- Bagian Tanda Tangan -->
        <div class="d-flex justify-content-between align-items-center mt-24 pt-8" style="font-size: 13px; color: #000;">
            <div style="width: 33%; text-align: center;">
                <div class="sig-block" style="margin-right: 20px;">
                    <div class="sig-text">
                        <p>Ciamis, {{ $namaBulan }} {{ $tahun }}</p>
                        <p>{{ $jabatanPenandatangan }}</p>
                        <div style="height: 68px;"></div>
                        <p class="fw-bold text-decoration-underline text-nowrap" style="font-weight: bold; text-decoration: underline;">
                            {{ $penandatanganGuru->nama_lengkap ?? '(Guru belum dipilih)' }}
                        </p>
                        <p class="text-nowrap">
                            NIP. {{ $penandatanganGuru->nip ?? $penandatanganGuru->nomor_induk ?? '-' }}
                        </p>
                    </div>
                    @if ($penandatanganGuru && $penandatanganGuru->ttd)
                        <img src="{{ asset('storage/' . $penandatanganGuru->ttd) }}" alt="TTD Guru" class="img-ttd-digital ttd-wali-overlay">
                    @endif
                </div>
            </div>

            <div style="width: 34%; text-align: center;">
                <div class="d-inline-flex align-items-center justify-content-center gap-12 text-nowrap">
                    <span class="fw-bold">Prosentase Absen Bulan Ini</span>
                    <div class="text-center px-8">
                        <div style="border-bottom: 1.5px solid #000; padding-bottom: 2px; font-weight: 700;">
                            {{ $grandTotalAbsen }} &nbsp;&times;&nbsp; 100
                        </div>
                        <div style="padding-top: 2px; font-weight: 700;">
                            {{ $totalSiswa }} &nbsp;&times;&nbsp; {{ $hariEfektif }} &nbsp;=&nbsp; {{ $pembagiPersen }}
                        </div>
                    </div>
                    <span class="fw-bold">= {{ $persenAbsen }} %</span>
                </div>
            </div>

            <div style="width: 33%; text-align: center;">
                <div class="sig-block" style="margin-right: 20px;">
                    <div class="sig-text">
                        <p>Ciamis, {{ $namaBulan }} {{ $tahun }}</p>
                        <p>Guru {{ $kelasTerpilih->nama_kelas ?? 'Kelas' }}</p>
                        <div style="height: 68px;"></div>
                        <p class="fw-bold text-decoration-underline text-nowrap">
                            {{ $kelasTerpilih->waliKelas->nama_lengkap ?? '(Wali Kelas belum dipilih)' }}
                        </p>
                        <p class="text-nowrap">
                            NIP. {{ $kelasTerpilih->waliKelas->nip ?? $kelasTerpilih->waliKelas->nomor_induk ?? '-' }}
                        </p>
                    </div>
                    @if ($kelasTerpilih && $kelasTerpilih->waliKelas && $kelasTerpilih->waliKelas->ttd)
                        <img src="{{ asset('storage/' . $kelasTerpilih->waliKelas->ttd) }}" alt="TTD Wali" class="img-ttd-digital ttd-wali-overlay">
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

        // Update parameter ttd=1 atau ttd=0 pada tombol PDF & Excel
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