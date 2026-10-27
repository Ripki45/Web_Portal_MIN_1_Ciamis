<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menyusun File Excel - {{ $kelasTerpilih->nama_kelas ?? 'Kelas' }}</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/bootstrap.min.css') }}">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-sm border-0 p-4 text-center" style="max-width: 420px; border-radius: 16px;">
        <h5 class="fw-bold text-success mb-2" id="judulStatus">⏳ Sedang Menyusun File Excel...</h5>
        <p class="text-secondary small mb-3" id="pesanStatus">
            Mohon tunggu sebentar, sedang memformat tabel untuk <b>{{ $kelasTerpilih->nama_kelas ?? '' }} ({{ $namaBulan }} {{ $tahun }})</b>.
        </p>
        <button onclick="window.close()" class="btn btn-outline-secondary btn-sm">Tutup Tab Ini</button>

        <!-- Gambar Cap & TTD Tersembunyi untuk Diambil ke Excel -->
        <div style="display: none;">
            @if (file_exists(public_path('backend/assets/images/cap_min_satu.png')))
                <img src="{{ asset('backend/assets/images/cap_min_satu.png') }}" id="imgCapMadrasah">
            @endif
            @if ($kepalaMadrasah && $kepalaMadrasah->ttd)
                <img src="{{ asset('storage/' . $kepalaMadrasah->ttd) }}" id="imgTtdKepala">
            @endif
            @if ($penandatanganGuru && $penandatanganGuru->ttd)
                <img src="{{ asset('storage/' . $penandatanganGuru->ttd) }}" id="imgTtdWali">
            @endif
        </div>
    </div>

    @php
        $rekapHarianS = array_fill(1, $jumlahHari, 0);
        $rekapHarianI = array_fill(1, $jumlahHari, 0);
        $rekapHarianA = array_fill(1, $jumlahHari, 0);
        $grandTotalS  = 0; $grandTotalI = 0; $grandTotalA = 0;

        foreach ($siswas as $s) {
            for ($d = 1; $d <= $jumlahHari; $d++) {
                if (!isset($tanggalLibur[$d])) {
                    $kd = $matriks[$s->id][$d] ?? '';
                    if ($kd === 'S') { $rekapHarianS[$d]++; $grandTotalS++; }
                    elseif ($kd === 'I') { $rekapHarianI[$d]++; $grandTotalI++; }
                    elseif ($kd === 'A') { $rekapHarianA[$d]++; $grandTotalA++; }
                }
            }
        }

        $totalSiswa      = $siswas->count();
        $hariEfektif     = max(0, $jumlahHari - count($tanggalLibur));
        $grandTotalAbsen = $grandTotalS + $grandTotalI + $grandTotalA;
        $pembagiPersen   = ($totalSiswa * max(1, $hariEfektif));
        $persenAbsen     = $totalSiswa > 0 ? round(($grandTotalAbsen * 100) / $pembagiPersen, 2) : 0;

        $daftarSiswaJson = [];
        foreach ($siswas as $s) {
            $daftarSiswaJson[] = [
                'id'    => $s->id,
                'induk' => (string) ($s->nomor_induk ?? ''),
                'nisn'  => (string) ($s->nisn ?? ''),
                'nama'  => $s->nama_siswa,
            ];
        }

        $payloadExcel = [
            'siswas'          => $daftarSiswaJson,
            'matriks'         => $matriks,
            'libur'           => $tanggalLibur,
            'rekapS'          => $rekapHarianS,
            'rekapI'          => $rekapHarianI,
            'rekapA'          => $rekapHarianA,
            'jumlahHari'      => $jumlahHari,
            'totalSiswa'      => $totalSiswa,
            'hariEfektif'     => $hariEfektif,
            'pembagiPersen'   => $pembagiPersen,
            'grandTotalAbsen' => $grandTotalAbsen,
            'persenAbsen'     => $persenAbsen,
            'pakaiTtd'        => request('ttd', '1') == '1',
        ];
    @endphp

    <script id="jsonPayload" type="application/json">{!! json_encode($payloadExcel) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.3.0/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

    <script>
        const D = JSON.parse(document.getElementById('jsonPayload').textContent);

        async function ambilBase64(id) {
            const el = document.getElementById(id);
            if (!el) return null;
            try {
                const res = await fetch(el.src);
                const blob = await res.blob();
                return await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });
            } catch (e) { return null; }
        }

        async function generateExcelNow() {
            const workbook = new ExcelJS.Workbook();
            const ws = workbook.addWorksheet('Absen {{ $namaBulan }}', {
                pageSetup: { paperSize: 9, orientation: 'landscape', fitToPage: true, fitToWidth: 1, fitToHeight: 1 }
            });

            const jumlahHariBulan = D.jumlahHari;
            const totalCols = 4 + jumlahHariBulan + 4;

            ws.getColumn(1).width = 4.5;
            ws.getColumn(2).width = 16.5;
            ws.getColumn(3).width = 13;
            ws.getColumn(4).width = 28;
            for (let d = 1; d <= jumlahHariBulan; d++) ws.getColumn(4 + d).width = 3.3;
            ws.getColumn(4 + jumlahHariBulan + 1).width = 4.2;
            ws.getColumn(4 + jumlahHariBulan + 2).width = 4.2;
            ws.getColumn(4 + jumlahHariBulan + 3).width = 4.2;
            ws.getColumn(4 + jumlahHariBulan + 4).width = 5.2;

            const borderThin = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };

            // 1. KOP JUDUL
            ws.mergeCells(1, 1, 1, totalCols);
            ws.getCell(1, 1).value = "MADRASAH IBTIDAIYAH NEGERI 1 CIAMIS";
            ws.getCell(1, 1).font = { name: 'Arial', size: 13, bold: true };
            ws.getCell(1, 1).alignment = { horizontal: 'center', vertical: 'middle' };

            ws.mergeCells(2, 1, 2, totalCols);
            ws.getCell(2, 1).value = "TAHUN AJARAN {{ $tahunAktif->tahun_ajaran ?? '2026/2027' }}";
            ws.getCell(2, 1).font = { name: 'Arial', size: 12, bold: true };
            ws.getCell(2, 1).alignment = { horizontal: 'center', vertical: 'middle' };

            // 2. INFO KELAS & KOTAK KUNING BULAN
            ws.getCell(4, 2).value = "{{ ($kategori ?? 'harian') === 'ekskul' ? 'EKSKUL' : 'KELAS' }}";
            ws.getCell(4, 3).value = ": {{ ($kategori ?? 'harian') === 'ekskul' ? ($ekskulTerpilih->nama_ekskul ?? '-') : (str_replace('Kelas ', '', $kelasTerpilih->nama_kelas ?? '-') . ($labelTingkat ? ' (' . $labelTingkat . ')' : '')) }}";
            ws.getCell(5, 2).value = "BULAN";
            ws.getCell(5, 3).value = ": {{ $namaBulan }}";
            ws.getCell(6, 2).value = "TAHUN";
            ws.getCell(6, 3).value = ": {{ $tahun }}";
            [4, 5, 6].forEach(r => {
                ws.getCell(r, 2).font = { name: 'Arial', size: 10, bold: true };
                ws.getCell(r, 3).font = { name: 'Arial', size: 10, bold: true };
            });

            const colBoxStart = totalCols - 2;
            ws.mergeCells(4, colBoxStart, 5, totalCols);
            const cellMonthNum = ws.getCell(4, colBoxStart);
            cellMonthNum.value = {{ $bulan }};
            cellMonthNum.font = { name: 'Arial', size: 15, bold: true };
            cellMonthNum.alignment = { horizontal: 'center', vertical: 'middle' };
            cellMonthNum.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFF00' } };

            ws.mergeCells(6, colBoxStart, 6, totalCols);
            const cellMonthYear = ws.getCell(6, colBoxStart);
            cellMonthYear.value = {{ $tahun }};
            cellMonthYear.font = { name: 'Arial', size: 10, bold: true };
            cellMonthYear.alignment = { horizontal: 'center', vertical: 'middle' };

            for (let r = 4; r <= 6; r++) {
                for (let c = colBoxStart; c <= totalCols; c++) ws.getCell(r, c).border = borderThin;
            }

            // 3. HEADER TABEL
            const rHead1 = 8, rHead2 = 9;
            ws.mergeCells(rHead1, 1, rHead2, 1); ws.getCell(rHead1, 1).value = "NO";
            ws.mergeCells(rHead1, 2, rHead2, 2); ws.getCell(rHead1, 2).value = "NO INDUK";
            ws.mergeCells(rHead1, 3, rHead2, 3); ws.getCell(rHead1, 3).value = "NISN";
            ws.mergeCells(rHead1, 4, rHead2, 4); ws.getCell(rHead1, 4).value = "NAMA";
            ws.mergeCells(rHead1, 5, rHead1, 4 + jumlahHariBulan); ws.getCell(rHead1, 5).value = "TANGGAL";
            ws.mergeCells(rHead1, 4 + jumlahHariBulan + 1, rHead1, totalCols); ws.getCell(rHead1, 4 + jumlahHariBulan + 1).value = "JUMLAH";

            for (let d = 1; d <= jumlahHariBulan; d++) {
                ws.getCell(rHead2, 4 + d).value = String(d).padStart(2, '0');
            }
            ws.getCell(rHead2, 4 + jumlahHariBulan + 1).value = "S";
            ws.getCell(rHead2, 4 + jumlahHariBulan + 2).value = "I";
            ws.getCell(rHead2, 4 + jumlahHariBulan + 3).value = "A";
            ws.getCell(rHead2, 4 + jumlahHariBulan + 4).value = "JML";

            for (let r = rHead1; r <= rHead2; r++) {
                ws.getRow(r).height = 18;
                for (let c = 1; c <= totalCols; c++) {
                    const cell = ws.getCell(r, c);
                    cell.font = { name: 'Arial', size: 9.5, bold: true };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.border = borderThin;
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF3E9D2' } };
                }
            }
            for (let d = 1; d <= jumlahHariBulan; d++) {
                if (D.libur[d]) {
                    const cell = ws.getCell(rHead2, 4 + d);
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFEF5350' } };
                    cell.font = { name: 'Arial', size: 9.5, bold: true, color: { argb: 'FFFFFFFF' } };
                }
            }
            ws.getCell(rHead2, 4 + jumlahHariBulan + 1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFDFAE5' } };
            ws.getCell(rHead2, 4 + jumlahHariBulan + 2).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE3F2FD' } };
            ws.getCell(rHead2, 4 + jumlahHariBulan + 3).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
            ws.getCell(rHead2, 4 + jumlahHariBulan + 4).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFC5B382' } };

            // 4. DATA SISWA
            const startSiswaRow = 10;
            let currentRow = startSiswaRow;
            let sumS = 0, sumI = 0, sumA = 0;

            D.siswas.forEach((s, idx) => {
                ws.getRow(currentRow).height = 17;
                ws.getCell(currentRow, 1).value = idx + 1;
                ws.getCell(currentRow, 1).alignment = { horizontal: 'center', vertical: 'middle' };
                ws.getCell(currentRow, 2).value = s.induk;
                ws.getCell(currentRow, 2).alignment = { horizontal: 'center', vertical: 'middle' };
                ws.getCell(currentRow, 3).value = s.nisn;
                ws.getCell(currentRow, 3).alignment = { horizontal: 'center', vertical: 'middle' };
                ws.getCell(currentRow, 4).value = s.nama;
                ws.getCell(currentRow, 4).alignment = { horizontal: 'left', vertical: 'middle' };

                let jS = 0, jI = 0, jA = 0;
                for (let d = 1; d <= jumlahHariBulan; d++) {
                    const cell = ws.getCell(currentRow, 4 + d);
                    const infoLibur = D.libur[d] || null;
                    const kode = (D.matriks[s.id] && D.matriks[s.id][d]) ? D.matriks[s.id][d] : '';
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };

                    if (infoLibur) {
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFEF5350' } };
                    } else {
                        if (kode === '•') { cell.value = '•'; cell.font = { name: 'Arial', size: 12, bold: true }; }
                        else if (kode === 'S') { cell.value = 's'; jS++; cell.font = { name: 'Arial', size: 9.5, bold: true }; }
                        else if (kode === 'I') { cell.value = 'i'; jI++; cell.font = { name: 'Arial', size: 9.5, bold: true }; }
                        else if (kode === 'A') { cell.value = 'a'; jA++; cell.font = { name: 'Arial', size: 9.5, bold: true }; }
                    }
                }

                const totSiswa = jS + jI + jA;
                sumS += jS; sumI += jI; sumA += jA;

                const cS = ws.getCell(currentRow, 4 + jumlahHariBulan + 1);
                const cI = ws.getCell(currentRow, 4 + jumlahHariBulan + 2);
                const cA = ws.getCell(currentRow, 4 + jumlahHariBulan + 3);
                const cJ = ws.getCell(currentRow, 4 + jumlahHariBulan + 4);

                cS.value = jS || ''; cS.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFDFAE5' } };
                cI.value = jI || ''; cI.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE3F2FD' } };
                cA.value = jA || ''; cA.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                cJ.value = totSiswa || ''; cJ.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFC5B382' } };

                [cS, cI, cA, cJ].forEach(el => {
                    el.alignment = { horizontal: 'center', vertical: 'middle' };
                    el.font = { name: 'Arial', size: 9.5, bold: true };
                });

                for (let c = 1; c <= totalCols; c++) {
                    ws.getCell(currentRow, c).border = borderThin;
                    if (c <= 4) ws.getCell(currentRow, c).font = { name: 'Arial', size: 9.5, bold: c === 4 };
                }
                currentRow++;
            });

            const endSiswaRow = currentRow - 1;
            if (endSiswaRow >= startSiswaRow) {
                for (let d = 1; d <= jumlahHariBulan; d++) {
                    if (D.libur[d] && D.libur[d].tipe === 'khusus') {
                        ws.mergeCells(startSiswaRow, 4 + d, endSiswaRow, 4 + d);
                        const mc = ws.getCell(startSiswaRow, 4 + d);
                        mc.value = D.libur[d].keterangan;
                        mc.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFEF5350' } };
                        mc.font = { name: 'Arial', size: 9.5, bold: true, color: { argb: 'FFFFFFFF' } };
                        mc.alignment = { horizontal: 'center', vertical: 'middle', textRotation: 90 };
                    }
                }
            }

            // 5. BARIS REKAP BAWAH
            const rowsBottom = [
                { label: '', bg: 'FFFFFFFF', data: null },
                { label: 'Sakit', bg: 'FFFDFAE5', data: D.rekapS, colTot: 1, valTot: sumS },
                { label: 'Izin',  bg: 'FFE3F2FD', data: D.rekapI, colTot: 2, valTot: sumI },
                { label: 'Alfa',  bg: 'FFFFFFFF', data: D.rekapA, colTot: 3, valTot: sumA },
            ];

            rowsBottom.forEach(rb => {
                ws.getRow(currentRow).height = 17;
                ws.getCell(currentRow, 4).value = rb.label;
                ws.getCell(currentRow, 4).font = { name: 'Arial', size: 9.5, bold: true };
                if (rb.label) ws.getCell(currentRow, 4).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rb.bg } };

                for (let d = 1; d <= jumlahHariBulan; d++) {
                    const cell = ws.getCell(currentRow, 4 + d);
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.font = { name: 'Arial', size: 9.5, bold: true };
                    if (D.libur[d]) {
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFEF5350' } };
                    } else {
                        if (rb.label) cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rb.bg } };
                        if (rb.data && rb.data[d]) cell.value = rb.data[d];
                    }
                }

                const cS = ws.getCell(currentRow, 4 + jumlahHariBulan + 1);
                const cI = ws.getCell(currentRow, 4 + jumlahHariBulan + 2);
                const cA = ws.getCell(currentRow, 4 + jumlahHariBulan + 3);
                const cJ = ws.getCell(currentRow, 4 + jumlahHariBulan + 4);

                cS.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFDFAE5' } };
                cI.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE3F2FD' } };
                cA.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                cJ.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFC5B382' } };

                if (rb.colTot === 1) cS.value = rb.valTot || '';
                if (rb.colTot === 2) cI.value = rb.valTot || '';
                if (rb.colTot === 3) cA.value = rb.valTot || '';

                [cS, cI, cA, cJ].forEach(el => {
                    el.alignment = { horizontal: 'center', vertical: 'middle' };
                    el.font = { name: 'Arial', size: 9.5, bold: true };
                });

                for (let c = 1; c <= totalCols; c++) ws.getCell(currentRow, c).border = borderThin;
                currentRow++;
            });

            // Baris JUMLAH
            ws.getRow(currentRow).height = 18;
            ws.mergeCells(currentRow, 1, currentRow, 4);
            ws.getCell(currentRow, 1).value = "JUMLAH";
            for (let d = 1; d <= jumlahHariBulan; d++) {
                const cell = ws.getCell(currentRow, 4 + d);
                if (D.libur[d]) {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFEF5350' } };
                } else {
                    const totD = (D.rekapS[d] || 0) + (D.rekapI[d] || 0) + (D.rekapA[d] || 0);
                    cell.value = totD || '';
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFC5B382' } };
                }
            }
            ws.getCell(currentRow, 4 + jumlahHariBulan + 1).value = sumS || '';
            ws.getCell(currentRow, 4 + jumlahHariBulan + 2).value = sumI || '';
            ws.getCell(currentRow, 4 + jumlahHariBulan + 3).value = sumA || '';
            ws.getCell(currentRow, 4 + jumlahHariBulan + 4).value = D.grandTotalAbsen || '';

            for (let c = 1; c <= totalCols; c++) {
                const cell = ws.getCell(currentRow, c);
                if (!cell.fill || cell.fill.fgColor?.argb !== 'FFEF5350') {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFC5B382' } };
                }
                cell.font = { name: 'Arial', size: 9.5, bold: true };
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
                cell.border = borderThin;
            }

            // 6. TANDA TANGAN & PROSENTASE ABSEN
            const sigStartRow = currentRow + 2;

            ws.mergeCells(sigStartRow, 2, sigStartRow, 6);
            ws.getCell(sigStartRow, 2).value = "Mengetahui,";
            ws.getCell(sigStartRow, 2).alignment = { horizontal: 'center' };
            ws.mergeCells(sigStartRow + 1, 2, sigStartRow + 1, 6);
            ws.getCell(sigStartRow + 1, 2).value = "Kepala Madrasah";
            ws.getCell(sigStartRow + 1, 2).alignment = { horizontal: 'center' };
            ws.mergeCells(sigStartRow + 5, 2, sigStartRow + 5, 6);
            ws.getCell(sigStartRow + 5, 2).value = "{{ $kepalaMadrasah->nama_lengkap ?? '(Belum diatur di Data GTK)' }}";
            ws.getCell(sigStartRow + 5, 2).alignment = { horizontal: 'center' };
            ws.getCell(sigStartRow + 5, 2).font = { name: 'Arial', size: 10, bold: true, underline: true };
            ws.mergeCells(sigStartRow + 6, 2, sigStartRow + 6, 6);
            ws.getCell(sigStartRow + 6, 2).value = "NIP. {{ $kepalaMadrasah->nip ?? $kepalaMadrasah->nomor_induk ?? '-' }}";
            ws.getCell(sigStartRow + 6, 2).alignment = { horizontal: 'center' };

            const midCol = Math.floor(totalCols / 2) - 3;
            ws.mergeCells(sigStartRow + 2, midCol - 5, sigStartRow + 3, midCol);
            ws.getCell(sigStartRow + 2, midCol - 5).value = "Prosentase Absen Bulan Ini";
            ws.getCell(sigStartRow + 2, midCol - 5).font = { name: 'Arial', size: 10, bold: true };
            ws.getCell(sigStartRow + 2, midCol - 5).alignment = { horizontal: 'right', vertical: 'middle' };

            ws.mergeCells(sigStartRow + 2, midCol + 1, sigStartRow + 2, midCol + 5);
            ws.getCell(sigStartRow + 2, midCol + 1).value = `${D.grandTotalAbsen}  x  100`;
            ws.getCell(sigStartRow + 2, midCol + 1).font = { name: 'Arial', size: 10, bold: true };
            ws.getCell(sigStartRow + 2, midCol + 1).alignment = { horizontal: 'center', vertical: 'bottom' };
            for (let c = midCol + 1; c <= midCol + 5; c++) {
                ws.getCell(sigStartRow + 2, c).border = { bottom: { style: 'thin', color: { argb: 'FF000000' } } };
            }

            ws.mergeCells(sigStartRow + 3, midCol + 1, sigStartRow + 3, midCol + 5);
            ws.getCell(sigStartRow + 3, midCol + 1).value = `${D.totalSiswa} x ${D.hariEfektif} = ${D.pembagiPersen}`;
            ws.getCell(sigStartRow + 3, midCol + 1).font = { name: 'Arial', size: 10, bold: true };
            ws.getCell(sigStartRow + 3, midCol + 1).alignment = { horizontal: 'center', vertical: 'top' };

            ws.mergeCells(sigStartRow + 2, midCol + 6, sigStartRow + 3, midCol + 9);
            ws.getCell(sigStartRow + 2, midCol + 6).value = `= ${D.persenAbsen} %`;
            ws.getCell(sigStartRow + 2, midCol + 6).font = { name: 'Arial', size: 10, bold: true };
            ws.getCell(sigStartRow + 2, midCol + 6).alignment = { horizontal: 'left', vertical: 'middle' };

            const rightColStart = totalCols - 9;
            ws.mergeCells(sigStartRow, rightColStart, sigStartRow, totalCols - 1);
            ws.getCell(sigStartRow, rightColStart).value = "Ciamis, {{ $namaBulan }} {{ $tahun }}";
            ws.getCell(sigStartRow, rightColStart).alignment = { horizontal: 'center' };
            ws.mergeCells(sigStartRow + 1, rightColStart, sigStartRow + 1, totalCols - 1);
            ws.getCell(sigStartRow + 1, rightColStart).value = "{{ $jabatanPenandatangan }}";
            ws.getCell(sigStartRow + 1, rightColStart).alignment = { horizontal: 'center' };
            ws.mergeCells(sigStartRow + 5, rightColStart, sigStartRow + 5, totalCols - 1);
            ws.getCell(sigStartRow + 5, rightColStart).value = "{{ $penandatanganGuru->nama_lengkap ?? '(Guru belum dipilih)' }}";
            ws.getCell(sigStartRow + 5, rightColStart).alignment = { horizontal: 'center' };
            ws.getCell(sigStartRow + 5, rightColStart).font = { name: 'Arial', size: 10, bold: true, underline: true };
            ws.mergeCells(sigStartRow + 6, rightColStart, sigStartRow + 6, totalCols - 1);
            ws.getCell(sigStartRow + 6, rightColStart).value = "NIP. {{ $penandatanganGuru->nip ?? $penandatanganGuru->nomor_induk ?? '-' }}";
            ws.getCell(sigStartRow + 6, rightColStart).alignment = { horizontal: 'center' };

            if (D.pakaiTtd) {
                const b64Cap = await ambilBase64('imgCapMadrasah');
                if (b64Cap) {
                    const idCap = workbook.addImage({ base64: b64Cap, extension: 'png' });
                    ws.addImage(idCap, { tl: { col: 1.0, row: sigStartRow - 0.3 }, ext: { width: 115, height: 115 } });
                }
                const b64Kep = await ambilBase64('imgTtdKepala');
                if (b64Kep) {
                    const idK = workbook.addImage({ base64: b64Kep, extension: 'png' });
                    ws.addImage(idK, { tl: { col: 2.1, row: sigStartRow + 0.8 }, ext: { width: 135, height: 85 } });
                }
                const b64Wal = await ambilBase64('imgTtdWali');
                if (b64Wal) {
                    const idW = workbook.addImage({ base64: b64Wal, extension: 'png' });
                    ws.addImage(idW, { tl: { col: rightColStart + 1.2, row: sigStartRow + 0.8 }, ext: { width: 135, height: 85 } });
                }
            }

            const buffer = await workbook.xlsx.writeBuffer();
            const namaKelasFile = "{{ str_replace(' ', '_', $kelasTerpilih->nama_kelas ?? 'Kelas') }}";
            saveAs(new Blob([buffer]), `Rekap_Absensi_${namaKelasFile}_{{ $namaBulan }}_{{ $tahun }}.xlsx`);

            document.getElementById('judulStatus').innerText = "✅ File Excel Berhasil Diunduh!";
            document.getElementById('pesanStatus').innerText = "Silakan cek file difolder Downloads perangkat Bapak/Ibu kemudian sesuaikan format dengan kebutuhannya.";
        }

        window.onload = function() {
            generateExcelNow();
        };
    </script>
</body>
</html>