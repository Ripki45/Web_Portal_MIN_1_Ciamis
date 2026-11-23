<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisis Hari Efektif Belajar - MIN 1 Ciamis TA {{ $tahunAktif }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; color: #111; margin: 24px 32px; font-size: 12pt; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 18px; }
        .kop h2, .kop h3, .kop p { margin: 2px 0; }
        .kop p { font-size: 10.5pt; font-style: italic; }
        h4 { margin: 18px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 10.5pt; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background: #f1f5f9; text-align: center; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .ttd-wrap { margin-top: 28px; display: flex; justify-content: flex-end; }
        .ttd-box { width: 280px; text-align: left; }
        .no-print { margin-bottom: 16px; text-align: right; }
        @media print {
            .no-print { display: none; }
            body { margin: 12px 18px; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 18px; background: #0d6a56; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="kop">
        <h3>ANALISIS HARI EFEKTIF KALENDER AKADEMIK</h3>
        <h2>MI NEGERI 1 CIAMIS — TAHUN AJARAN {{ $tahunAktif }}</h2>
        @if ($dokumen->dasar_hukum)
            <p>{{ $dokumen->dasar_hukum }}</p>
        @endif
    </div>

    <p style="font-size: 10.5pt; text-align: justify;">
        Perhitungan Hari Efektif Belajar (HEB) disusun berdasarkan pola <strong>{{ $analisisHeb['hari_kerja'] }} hari kerja</strong>
        ({{ $analisisHeb['hari_kerja'] === 6 ? 'Senin – Sabtu' : 'Senin – Jumat' }}) dengan mengecualikan hari akhir pekan,
        hari libur nasional/keagamaan, dan libur semester.
    </p>

    @foreach (['gasal' => '1. Rincian Hari Efektif Semester Gasal', 'genap' => '2. Rincian Hari Efektif Semester Genap'] as $key => $label)
        @php $sem = $analisisHeb[$key]; @endphp
        <h4>{{ $label }}</h4>
        <table>
            <thead>
                <tr>
                    <th style="width: 36px;">No</th>
                    <th style="width: 130px;">Bulan / Tahun</th>
                    <th style="width: 75px;">Hari Kalender</th>
                    <th style="width: 85px;">{{ $analisisHeb['hari_kerja'] === 6 ? 'Hari Minggu' : 'Akhir Pekan' }}</th>
                    <th style="width: 90px;">Libur Resmi / Sem</th>
                    <th style="width: 95px;">Hari Efektif</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sem['rows'] as $i => $r)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $r['bulan_label'] }}</td>
                        <td class="text-center">{{ $r['hari_kalender'] }}</td>
                        <td class="text-center">{{ $r['hari_akhir_pekan'] }}</td>
                        <td class="text-center">{{ $r['hari_libur'] }}</td>
                        <td class="text-center fw-bold">{{ $r['hari_efektif'] }}</td>
                        <td>{{ $r['keterangan'] }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold" style="background: #f8fafc;">
                    <td colspan="2">Jumlah</td>
                    <td class="text-center">{{ $sem['total_kalender'] }}</td>
                    <td class="text-center">{{ $sem['total_akhir_pekan'] }}</td>
                    <td class="text-center">{{ $sem['total_libur'] }}</td>
                    <td class="text-center">{{ $sem['total_efektif'] }} Hari</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <p class="fw-bold">
        Total Hari Efektif Belajar TA {{ $tahunAktif }}:
        {{ $analisisHeb['gasal']['total_efektif'] }} Hari (Gasal) + {{ $analisisHeb['genap']['total_efektif'] }} Hari (Genap)
        = {{ $analisisHeb['total_efektif_tahunan'] }} Hari Efektif Belajar.
    </p>

    @if ($dokumen->catatan_kaki)
        <p style="font-size: 10pt; font-style: italic;">Catatan: {{ $dokumen->catatan_kaki }}</p>
    @endif

    <div class="ttd-wrap">
        <div class="ttd-box">
            <div>Ciamis, {{ $dokumen->tanggal_pengesahan ? $dokumen->tanggal_pengesahan->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</div>
            <div>Kepala MIN 1 Ciamis,</div>
            <div style="height: 68px;"></div>
            <div class="fw-bold" style="text-decoration: underline;">{{ $dokumen->nama_kepsek }}</div>
            <div>NIP. {{ $dokumen->nip_kepsek }}</div>
        </div>
    </div>
</body>
</html>