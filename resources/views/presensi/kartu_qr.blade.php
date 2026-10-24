<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu_Siswa_MIN_1_Ciamis_{{ str_replace(' ', '_', $judulCetak ?? 'Cetak') }}</title>
    <style>
        /* KUNCI KERTAS A4 LANDSCAPE (297mm x 210mm) AGAR 2 SISI (254mm) PAS 100% */
        @page {
            size: A4 landscape;
            margin: 8mm 12mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            margin: 0;
            padding: 16px;
            background: #e2e8f0;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            color: #0f172a;
        }

        /* Toolbar Atas (Hilang Saat Print / Download PDF) */
        .toolbar-cetak {
            max-width: 265mm;
            margin: 0 auto 16px auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 12px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .btn-print {
            background: #15803d;
            color: #ffffff;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }
        .btn-print:hover { background: #14532d; }

        /* 1 Halaman A4 Landscape memuat maksimal 2 pasang kartu (Atas & Bawah) */
        .a4-landscape-sheet {
            width: 265mm;
            margin: 0 auto 12mm auto;
            background: #ffffff;
            padding: 8mm 5mm;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            gap: 8mm;
            align-items: center;
            justify-content: center;
            page-break-after: always;
        }
        .a4-landscape-sheet:last-child {
            page-break-after: auto;
        }

        /* 1 Pasang Kartu (Kiri: Depan 126mm | Kanan: Belakang 126mm = Total 254mm) */
        .card-pair {
            display: flex;
            gap: 2mm;
            align-items: center;
            justify-content: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* UKURAN PRESISI B2 LANDSCAPE: 126mm x 79mm */
        .b2-card {
            width: 126mm;
            height: 79mm;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 65%, #bbf7d0 100%);
            border: 0.4mm dashed #475569;
            border-radius: 3mm;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-shrink: 0;
        }

        /* Ornamen Lingkaran Halus ala Kartu EMIS */
        .emis-curve {
            position: absolute;
            right: -25mm;
            bottom: -25mm;
            width: 85mm;
            height: 85mm;
            background: rgba(21, 128, 61, 0.10);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* KOP ATAS KARTU DEPAN */
        .front-header {
            background: linear-gradient(90deg, #14532d 0%, #15803d 60%, #16a34a 100%);
            color: #ffffff;
            padding: 2.3mm 4mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 0.9mm solid #facc15;
            position: relative;
            z-index: 2;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 2.5mm;
        }
        .logo-box {
            width: 9mm;
            height: 9mm;
            background: #ffffff;
            border-radius: 50%;
            padding: 0.8mm;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 5.5pt;
            color: #15803d;
            text-align: center;
            line-height: 1;
            flex-shrink: 0;
        }
        .instansi-text { line-height: 1.15; }
        .instansi-sub { font-size: 6.2pt; font-weight: 700; letter-spacing: 0.4px; }
        .instansi-main { font-size: 9.2pt; font-weight: 900; letter-spacing: 0.3px; }
        .card-badge-title { text-align: right; line-height: 1.05; }
        .card-badge-title .t-main { font-size: 10.5pt; font-weight: 900; color: #fef08a; letter-spacing: 0.5px; }
        .card-badge-title .t-sub { font-size: 5.2pt; font-weight: 700; color: #ffffff; display: block; }

        /* ISI TENGAH KARTU DEPAN */
        .front-body {
            padding: 2.5mm 4mm 2mm 4mm;
            display: flex;
            align-items: center;
            gap: 3.2mm;
            flex-grow: 1;
            position: relative;
            z-index: 2;
        }
        .photo-frame {
            width: 23.5mm;
            height: 31.5mm;
            background: #dc2626;
            border: 0.7mm solid #ffffff;
            outline: 0.3mm solid #15803d;
            border-radius: 1.8mm;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .bio-box { flex: 1; overflow: hidden; }
        .bio-name {
            font-size: 10pt;
            font-weight: 900;
            color: #14532d;
            text-transform: uppercase;
            margin-bottom: 1.2mm;
            line-height: 1.15;
            border-bottom: 0.4mm solid #86efac;
            padding-bottom: 0.8mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .bio-table { width: 100%; font-size: 7.5pt; line-height: 1.32; border-collapse: collapse; }
        .bio-table td { padding: 0.2mm 0; vertical-align: top; }
        .bio-label { width: 20mm; font-weight: 700; color: #334155; }
        .bio-val { font-weight: 800; color: #0f172a; }

        /* Kolom Kanan: QR Code */
        .qr-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .qr-wrapper {
            width: 30mm;
            height: 30mm;
            background: #ffffff;
            border: 0.6mm solid #15803d;
            border-radius: 2mm;
            padding: 1.2mm;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-wrapper img.qr-img { width: 100%; height: 100%; object-fit: contain; }
        .qr-center-badge {
            position: absolute;
            width: 6.2mm;
            height: 6.2mm;
            background: #ffffff;
            border: 0.4mm solid #15803d;
            border-radius: 1mm;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4.2pt;
            font-weight: 900;
            color: #15803d;
            line-height: 1;
            text-align: center;
        }
        .qr-caption {
            font-size: 5.2pt;
            font-weight: 800;
            color: #15803d;
            margin-top: 0.7mm;
            letter-spacing: 0.3px;
        }

        .front-footer {
            background: #14532d;
            color: #ffffff;
            font-size: 5.8pt;
            font-weight: 700;
            text-align: center;
            padding: 1.1mm 3mm;
            letter-spacing: 0.4px;
            position: relative;
            z-index: 2;
        }

        /* SISI BELAKANG: JANJI SISWA */
        .back-header {
            background: linear-gradient(90deg, #14532d 0%, #15803d 50%, #14532d 100%);
            color: #ffffff;
            text-align: center;
            padding: 2mm 4mm;
            border-bottom: 0.9mm solid #facc15;
            position: relative;
            z-index: 2;
        }
        .back-header-title { font-size: 9.8pt; font-weight: 900; letter-spacing: 1px; color: #fef08a; margin: 0; }
        .back-header-sub { font-size: 6.2pt; font-weight: 700; }
        .back-body {
            padding: 2.2mm 4.5mm;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
        .janji-box {
            background: rgba(255, 255, 255, 0.88);
            border: 0.4mm solid #86efac;
            border-radius: 2mm;
            padding: 2mm 3.2mm;
        }
        .janji-intro { font-size: 7pt; font-weight: 800; color: #14532d; margin-bottom: 1mm; text-align: center; }
        .janji-list { margin: 0; padding-left: 3.8mm; font-size: 6.8pt; font-weight: 600; color: #1e293b; line-height: 1.35; }
        .janji-list li { margin-bottom: 0.5mm; }
        .back-note { font-size: 5.5pt; text-align: center; color: #334155; font-weight: 700; margin-top: 1.2mm; }

        @media print {
            body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .a4-landscape-sheet {
                width: 100% !important;
                margin: 0 !important;
                padding: 2mm 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar-cetak no-print">
        <div>
            <b style="color: #15803d; font-size: 15px;">Cetak / Download PDF Kartu Siswa ({{ $judulCetak ?? '' }} — {{ $siswas->count() }} Kartu)</b>
            <div style="font-size: 12px; color: #475569; margin-top: 2px;">
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 14px;">
            <label style="font-size: 12.5px; font-weight: bold; cursor: pointer;">
                <input type="checkbox" id="toggleSisiBelakang" checked onchange="aturSisiBelakang(this.checked)">
                Sertakan Sisi Belakang
            </label>
            <button onclick="window.print()" class="btn-print">🖨️ Simpan PDF / Print Sekarang</button>
        </div>
    </div>

    <!-- Bagi setiap 2 siswa ke dalam 1 lembar A4 Landscape agar tidak pernah terpotong di tengah halaman -->
    <?php foreach ($siswas->chunk(2) as $chunkSiswa): ?>
        <div class="a4-landscape-sheet">
            <?php foreach ($chunkSiswa as $s): ?>
                @php
                    $nisnVal  = $s->nisn ?: '-';
                    $indukVal = $s->nomor_induk ?: '-';
                    $ttlVal   = ($s->tempat_lahir ?? 'Ciamis') . ', ' . ($s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->translatedFormat('d F Y') : '-');
                    $jkVal    = $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';

                    $isiQrLengkap = "KARTU SISWA MIN 1 CIAMIS\n"
                                  . "NISN : " . ($s->nisn ?: $s->nomor_induk) . "\n"
                                  . "INDUK : " . $indukVal . "\n"
                                  . "Nama : " . $s->nama_siswa . "\n"
                                  . "TTL : " . $ttlVal . "\n"
                                  . "JK : " . $jkVal;

                    $fotoUrl = $s->foto
                        ? asset('storage/' . $s->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($s->nama_siswa) . '&background=dc2626&color=fff&bold=true&size=256';
                @endphp

                <div class="card-pair">
                    <!-- SISI DEPAN -->
                    <div class="b2-card">
                        <div class="emis-curve"></div>

                        <div class="front-header">
                            <div class="header-left">
                                <div class="logo-box">
                                    @if (file_exists(public_path('backend/assets/images/logo_kemenag.png')))
                                        <img src="{{ asset('backend/assets/images/logo_kemenag.png') }}" style="width: 100%; height: 100%; object-fit: contain;">
                                    @else
                                        KEMENAG
                                    @endif
                                </div>
                                <div class="instansi-text">
                                    <div class="instansi-sub">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                                    <div class="instansi-main">MADRASAH IBTIDAIYAH NEGERI 1 CIAMIS</div>
                                </div>
                            </div>
                            <div class="card-badge-title">
                                <span class="t-main">KARTU SISWA</span>
                                <span class="t-sub">DAN PRESENSI DIGITAL</span>
                            </div>
                        </div>

                        <div class="front-body">
                            <div class="photo-frame">
                                <img src="{{ $fotoUrl }}" alt="Foto">
                            </div>

                            <div class="bio-box">
                                <div class="bio-name">{{ $s->nama_siswa }}</div>
                                <table class="bio-table">
                                    <tr>
                                        <td class="bio-label">NISN</td>
                                        <td class="bio-val">: {{ $nisnVal }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bio-label">No. Induk</td>
                                        <td class="bio-val">: {{ $indukVal }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bio-label">Tempat Lahir</td>
                                        <td class="bio-val">: {{ $s->tempat_lahir ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bio-label">Tanggal Lahir</td>
                                        <td class="bio-val">: {{ $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bio-label">Jenis Kelamin</td>
                                        <td class="bio-val">: {{ $jkVal }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="qr-column">
                                <div class="qr-wrapper">
                                    <img class="qr-img" src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&ecc=H&margin=0&data={{ urlencode($isiQrLengkap) }}" alt="QR">
                                    <div class="qr-center-badge">
                                        @if (file_exists(public_path('backend/assets/images/logo_min.png')))
                                            <img src="{{ asset('backend/assets/images/logo_min.png') }}" style="width: 100%; height: 100%; object-fit: contain;">
                                        @else
                                            MIN 1
                                        @endif
                                    </div>
                                </div>
                                <span class="qr-caption">SCAN PRESENSI & INFO</span>
                            </div>
                        </div>

                        <div class="front-footer">
                            BERLAKU SELAMA MENJADI SISWA AKTIF DI MIN 1 CIAMIS
                        </div>
                    </div>

                    <!-- SISI BELAKANG -->
                    <div class="b2-card card-back-side">
                        <div class="emis-curve"></div>

                        <div class="back-header">
                            <div class="back-header-title">JANJI SISWA</div>
                            <div class="back-header-sub">MADRASAH IBTIDAIYAH NEGERI 1 CIAMIS</div>
                        </div>

                        <div class="back-body">
                            <div class="janji-box">
                                <div class="janji-intro">Kami Siswa-Siswi MIN 1 Ciamis Berjanji:</div>
                                <ol class="janji-list">
                                    <li>Takwa terhadap Tuhan Yang Maha Esa, Abdi terhadap Tanah Air dan Bangsa, setia kepada Pancasila, dan Undang-Undang Dasar 1945.</li>
                                    <li>Adab terhadap Orang Tua, Hormat terhadap Guru, serta Menjunjung tinggi derajat dan maratabat Sekolah.</li>
                                    <li>Belajar dengan sungguh-sungguh sebagai bekal untuk masa depan Bangsa.</li>
                                    <li>Berpestasi dalam rangka mengisi Kemerdekaan.</li>
                                    <li>Menjadi warga Negara yang baik, dan Pemuda Indonesia yang bertanggung jawab.</li>
                                </ol>
                            </div>
                            <div class="back-note">
                                * Kartu ini wajib dibawa setiap hari untuk Presensi Kehadiran, Shalat Berjamaah, dan Ekstrakurikuler.
                            </div>
                        </div>

                        <div class="front-footer">
                            MADRASAH HEBAT BERMARTABAT • MANDIRI BERPRESTASI
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <script>
        function aturSisiBelakang(tampilkan) {
            document.querySelectorAll('.card-back-side').forEach(el => {
                el.style.display = tampilkan ? 'flex' : 'none';
            });
        }

        // Otomatis panggil dialog Save as PDF / Print dalam mode A4 Landscape
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>