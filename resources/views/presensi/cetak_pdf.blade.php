<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap_Absensi_{{ str_replace(' ', '_', $kelasTerpilih->nama_kelas ?? 'Kelas') }}_{{ $namaBulan }}_{{ $tahun }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 6mm 8mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            margin: 0;
            padding: 16px;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
        }
        .toolbar-cetak {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        @media print {
            body { padding: 0 !important; }
            .no-print { display: none !important; }
        }

        .sheet-title {
            font-size: 14pt;
            font-weight: 800;
            text-align: center;
            line-height: 1.25;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 8px;
        }
        .meta-table td {
            padding: 1px 4px;
            font-size: 10pt;
            font-weight: 700;
        }
        .month-box {
            border: 2px solid #000;
            width: 62px;
            text-align: center;
            font-weight: 800;
        }
        .month-box-num {
            background-color: #ffff00;
            font-size: 16pt;
            padding: 2px 0;
            border-bottom: 2px solid #000;
        }
        .month-box-year {
            background-color: #ffffff;
            font-size: 9.5pt;
            padding: 2px 0;
        }

        .table-min1 {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }
        .table-min1 th, .table-min1 td {
            border: 1px solid #000;
            padding: 2px 2px;
            font-size: 8.5pt;
            vertical-align: middle;
            line-height: 1.15;
        }
        .table-min1 thead th {
            background-color: #f3e9d2;
            font-weight: 800;
            text-align: center;
        }
        .col-tgl { width: 20px; text-align: center; }
        .col-jml { width: 24px; text-align: center; }
        .dot-hadir { font-size: 12pt; font-weight: 900; line-height: 1; }
        .cell-libur { background-color: #ef5350 !important; color: #fff !important; }
        .cell-libur-khusus {
            background-color: #ef5350 !important;
            color: #fff !important;
            text-align: center;
            vertical-align: middle;
        }
        .vertical-holiday-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            font-weight: 800;
            font-size: 8pt;
            letter-spacing: 0.8px;
            color: #ffffff;
            display: inline-block;
        }
        .bg-row-sakit { background-color: #fdfae5 !important; }
        .bg-row-izin  { background-color: #e3f2fd !important; }
        .bg-row-alfa  { background-color: #ffffff !important; }
        .bg-tan-total { background-color: #c5b382 !important; font-weight: 800; }

        .footer-sig {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            font-size: 10pt;
        }
        .sig-block {
            position: relative;
            display: inline-block;
            text-align: center;
            min-width: 220px;
        }
        .sig-text { position: relative; z-index: 1; line-height: 1.2; }
        .sig-text p { margin: 0 0 2px 0; }
        .ttd-kepala-overlay {
            position: absolute;
            top: 42%;
            left: 50%;
            transform: translate(-64%, -50%);
            height: 125px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
            z-index: 2;
        }
        .ttd-wali-overlay {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 95px;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
            z-index: 2;
        }
    </style>
</head>
<body>

    <div class="toolbar-cetak no-print">
        <div>
            <b>Lembar Cetak / Simpan PDF Utuh</b> — Pilih <b>"Save as PDF"</b> dan pastikan orientasi <b>Landscape</b>.
        </div>
        <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF Sekarang</button>
    </div>

    <div class="sheet-title">
        MADRASAH IBTIDAIYAH NEGERI 1 CIAMIS<br>
        TAHUN AJARAN {{ $tahunAktif->tahun_ajaran ?? '2026/2027' }}
    </div>

    <div class="header-row">
        <table class="meta-table">
            <tr>
                <td width="60">KELAS</td>
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
        $grandTotalS  = 0; $grandTotalI = 0; $grandTotalA = 0;
        $totalSiswa   = $siswas->count();
        $hariEfektif  = $jumlahHari - count($tanggalLibur);
        $tampilkanTtd = request('ttd', '1') == '1';
    @endphp

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
                    <th class="col-tgl {{ isset($tanggalLibur[$d]) ? 'cell-libur' : '' }}">{{ sprintf('%02d', $d) }}</th>
                <?php endfor; ?>
                <th class="col-jml bg-row-sakit">S</th>
                <th class="col-jml bg-row-izin">I</th>
                <th class="col-jml bg-row-alfa">A</th>
                <th class="col-jml bg-tan-total">JML</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($siswas as $idx => $s): ?>
                @php $jmlS = 0; $jmlI = 0; $jmlA = 0; @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: center;">{{ $s->nomor_induk ?? '' }}</td>
                    <td style="text-align: center;">{{ $s->nisn ?? '' }}</td>
                    <td style="font-weight: bold; white-space: nowrap; padding-left: 4px;">{{ $s->nama_siswa }}</td>

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
                                    <td rowspan="{{ $totalSiswa }}" class="col-tgl cell-libur-khusus">
                                        <span class="vertical-holiday-text">{{ $infoLibur['keterangan'] }}</span>
                                    </td>
                                @endif
                            @else
                                <td class="col-tgl cell-libur"></td>
                            @endif
                        @else
                            <td class="col-tgl">
                                @if ($kode === '•') <span class="dot-hadir">&bull;</span>
                                @elseif ($kode === 'S') <b>s</b>
                                @elseif ($kode === 'I') <b>i</b>
                                @elseif ($kode === 'A') <b>a</b>
                                @endif
                            </td>
                        @endif
                    <?php endfor; ?>

                    @php
                        $jmlAbsenSiswa = $jmlS + $jmlI + $jmlA;
                        $grandTotalS += $jmlS; $grandTotalI += $jmlI; $grandTotalA += $jmlA;
                    @endphp
                    <td class="col-jml bg-row-sakit" style="font-weight: bold;">{{ $jmlS ?: '' }}</td>
                    <td class="col-jml bg-row-izin" style="font-weight: bold;">{{ $jmlI ?: '' }}</td>
                    <td class="col-jml bg-row-alfa" style="font-weight: bold;">{{ $jmlA ?: '' }}</td>
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
                <td class="bg-row-sakit" style="font-weight: bold; padding-left: 4px;">Sakit</td>
                <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                    @if (isset($tanggalLibur[$d])) <td class="col-tgl cell-libur"></td>
                    @else <td class="col-tgl bg-row-sakit" style="font-weight: bold;">{{ $rekapHarianS[$d] ?: '' }}</td> @endif
                <?php endfor; ?>
                <td class="col-jml bg-row-sakit" style="font-weight: bold;">{{ $grandTotalS ?: '' }}</td>
                <td class="col-jml bg-row-izin"></td><td class="col-jml bg-row-alfa"></td><td class="col-jml bg-tan-total"></td>
            </tr>

            <tr>
                <td></td><td></td><td></td>
                <td class="bg-row-izin" style="font-weight: bold; padding-left: 4px;">Izin</td>
                <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                    @if (isset($tanggalLibur[$d])) <td class="col-tgl cell-libur"></td>
                    @else <td class="col-tgl bg-row-izin" style="font-weight: bold;">{{ $rekapHarianI[$d] ?: '' }}</td> @endif
                <?php endfor; ?>
                <td class="col-jml bg-row-sakit"></td>
                <td class="col-jml bg-row-izin" style="font-weight: bold;">{{ $grandTotalI ?: '' }}</td>
                <td class="col-jml bg-row-alfa"></td><td class="col-jml bg-tan-total"></td>
            </tr>

            <tr>
                <td></td><td></td><td></td>
                <td class="bg-row-alfa" style="font-weight: bold; padding-left: 4px;">Alfa</td>
                <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                    @if (isset($tanggalLibur[$d])) <td class="col-tgl cell-libur"></td>
                    @else <td class="col-tgl bg-row-alfa" style="font-weight: bold;">{{ $rekapHarianA[$d] ?: '' }}</td> @endif
                <?php endfor; ?>
                <td class="col-jml bg-row-sakit"></td><td class="col-jml bg-row-izin"></td>
                <td class="col-jml bg-row-alfa" style="font-weight: bold;">{{ $grandTotalA ?: '' }}</td>
                <td class="col-jml bg-tan-total"></td>
            </tr>

            @php $grandTotalAbsen = $grandTotalS + $grandTotalI + $grandTotalA; @endphp
            <tr class="bg-tan-total">
                <td colspan="4" style="text-align: center; font-weight: bold;">JUMLAH</td>
                <?php for ($d = 1; $d <= $jumlahHari; $d++): ?>
                    @if (isset($tanggalLibur[$d])) <td class="col-tgl cell-libur"></td>
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
        </tbody>
    </table>

    @php
        $pembagiPersen = ($totalSiswa * max(1, $hariEfektif));
        $persenAbsen   = $totalSiswa > 0 ? round(($grandTotalAbsen * 100) / $pembagiPersen, 2) : 0;
    @endphp

    <div class="footer-sig">
        <div style="width: 35%; text-align: center;">
            <div class="sig-block">
                <div class="sig-text">
                    <p class="mb-0">Mengetahui,</p>
                    <p class="mb-0">Kepala MIN 1 Ciamis</p>
                    <div style="height: 68px;"></div>
                    <p class="fw-bold text-decoration-underline text-nowrap mb-0" style="font-weight: bold; text-decoration: underline;">
                        {{ $kepalaMadrasah->nama_lengkap ?? '(Kepala Madrasah Belum Diatur)' }}
                    </p>
                    <p class="text-nowrap mb-0">
                        NIP. {{ $kepalaMadrasah->nip ?? $kepalaMadrasah->nomor_induk ?? '-' }}
                    </p>
                </div>
                @if ($kepalaMadrasah && $kepalaMadrasah->ttd)
                    <img src="{{ asset('storage/' . $kepalaMadrasah->ttd) }}" alt="TTD Kepala" class="img-ttd-digital ttd-kamad-overlay">
                @endif
            </div>
        </div>

        <div style="width: 34%; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 10px; font-weight: bold;">
                <span>Prosentase Absen Bulan Ini</span>
                <div style="text-align: center; padding: 0 6px;">
                    <div style="border-bottom: 1.5px solid #000; padding-bottom: 2px;">{{ $grandTotalAbsen }} &times; 100</div>
                    <div style="padding-top: 2px;">{{ $totalSiswa }} &times; {{ $hariEfektif }} = {{ $pembagiPersen }}</div>
                </div>
                <span>= {{ $persenAbsen }} %</span>
            </div>
        </div>

        <div style="width: 35%; text-align: center;">
            <div class="sig-block">
                <div class="sig-text">
                    <p class="mb-0">Ciamis, {{ $namaBulan }} {{ $tahun }}</p>
                    <p class="mb-0">{{ $jabatanPenandatangan }}</p>
                    <div style="height: 68px;"></div>
                    <p class="fw-bold text-decoration-underline text-nowrap mb-0" style="font-weight: bold; text-decoration: underline;">
                        {{ $penandatanganGuru->nama_lengkap ?? '(Guru Belum Dipilih)' }}
                    </p>
                    <p class="text-nowrap mb-0">
                        NIP. {{ $penandatanganGuru->nip ?? $penandatanganGuru->nomor_induk ?? '-' }}
                    </p>
                </div>
                @if ($penandatanganGuru && $penandatanganGuru->ttd)
                    <img src="{{ asset('storage/' . $penandatanganGuru->ttd) }}" alt="TTD Guru" class="img-ttd-digital ttd-wali-overlay">
                @endif
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 400);
        };
    </script>
</body>
</html>