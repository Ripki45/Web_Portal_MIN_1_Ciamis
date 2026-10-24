@extends('layouts.admin')

@section('title', 'Dashboard Kelas ' . $kelas->nama_kelas)

@section('content')

    <!-- Header & Navigasi Pintas -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div class="d-flex align-items-center gap-12">
            <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-12 py-8 d-flex align-items-center gap-1 fw-bold">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <div>
                <h5 class="fw-bold mb-0">Dashboard Kelas {{ str_replace('Kelas ', '', $kelas->nama_kelas) }} (Tingkat {{ $kelas->tingkat }})</h5>
                <span class="text-xs text-secondary-light">
                    Ruangan: <b>{{ $kelas->ruangan ?: 'Ruang ' . $kelas->nama_kelas }}</b> • Tahun Ajaran: <b>{{ $tahunAktif->tahun_ajaran ?? '2026/2027' }}</b>
                </span>
            </div>
        </div>

        <!-- Tombol Pintas Operasional Kelas Ini -->
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('presensi.index', ['kelas_id' => $kelas->id]) }}" class="btn btn-primary-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold">
                <i class="ph-bold ph-check-square-offset"></i> Presensi Harian
            </a>
            <a href="{{ route('presensi.rekap', ['kelas_id' => $kelas->id]) }}" class="btn btn-success-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold">
                <i class="ph-bold ph-file-xls"></i> Rekap & Buku Absen
            </a>
            <a href="{{ route('presensi.kartuQr', ['kelas_id' => $kelas->id]) }}" target="_blank" class="btn btn-warning-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold">
                <i class="ph-bold ph-identification-card"></i> Cetak Kartu Siswa
            </a>
        </div>
    </div>

    <!-- Baris Info Statistik Kelas & Wali Kelas -->
    <div class="row gy-4 mb-24">
        <div class="col-md-5">
            <div class="card radius-12 h-100 border">
                <div class="card-body p-20 d-flex align-items-center gap-16">
                    @php
                        $fotoWali = ($kelas->waliKelas && $kelas->waliKelas->foto)
                            ? asset('storage/' . $kelas->waliKelas->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($kelas->waliKelas->nama_lengkap ?? 'Wali') . '&background=16a34a&color=fff&bold=true';
                    @endphp
                    <img src="{{ $fotoWali }}" alt="Wali" class="w-64-px h-64-px rounded-circle object-fit-cover border flex-shrink-0">
                    <div>
                        <span class="badge bg-success-focus text-success-main mb-4">Wali Kelas {{ str_replace('Kelas ', '', $kelas->nama_kelas) }}</span>
                        <h6 class="fw-bold mb-2">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}</h6>
                        <span class="text-xs text-secondary-light d-block">NIP: {{ $kelas->waliKelas->nomor_induk ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="row g-3 h-100">
                <div class="col-4">
                    <div class="card radius-12 h-100 border bg-primary-50">
                        <div class="card-body p-16 text-center d-flex flex-column justify-content-center">
                            <span class="text-xs fw-bold text-secondary-light">Total Siswa</span>
                            <h4 class="fw-black text-primary-600 mb-0">{{ $kelas->siswas->count() }} <small class="text-xs fw-normal">/ {{ $kelas->kapasitas }}</small></h4>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card radius-12 h-100 border bg-info-50">
                        <div class="card-body p-16 text-center d-flex flex-column justify-content-center">
                            <span class="text-xs fw-bold text-secondary-light">Siswa L / P</span>
                            <h5 class="fw-black text-info-main mb-0">{{ $totalL }} L &bull; {{ $totalP }} P</h5>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card radius-12 h-100 border bg-warning-50">
                        <div class="card-body p-16 text-center d-flex flex-column justify-content-center">
                            <span class="text-xs fw-bold text-secondary-light">Mata Pelajaran</span>
                            <h4 class="fw-black text-warning-main mb-0">{{ $kelas->kelasMapels->count() }} <small class="text-xs">Mapel</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- DAFTAR MATA PELAJARAN & GURU PENGAJAR DI KELAS INI (DENGAN FITUR CEKLIS)  -->
    <!-- ========================================================================= -->
    <div class="card radius-12 mb-24">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h6 class="text-md fw-bold mb-0">Daftar Mata Pelajaran & Guru Pengajar di Kelas {{ str_replace('Kelas ', '', $kelas->nama_kelas) }}</h6>
                <span class="text-xs text-secondary-light">Guru yang terdaftar di sini otomatis dapat dipilih sebagai penandatangan Rekap Absensi</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Form Hapus Banyak Mapel via Ceklis -->
                <form action="{{ route('kelas.bulkDestroyPlottingMapel') }}" method="POST" id="formBulkMapelKelas" class="d-inline">
                    @csrf
                    <div id="hiddenIdsMapelKelas"></div>
                    <button type="button" id="btnBulkDeleteMapel" onclick="hapusBanyakMapelKelas()" class="btn btn-danger-600 btn-sm radius-8 px-14 py-8 d-none align-items-center gap-1 fw-bold">
                        <i class="ph-bold ph-trash"></i> Hapus Mapel Terpilih (<span id="countMapelSelected">0</span>)
                    </button>
                </form>

                <button type="button" class="btn btn-primary-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-1 fw-bold" data-bs-toggle="modal" data-bs-target="#addMapelKeKelasModal">
                    <i class="ph-bold ph-checks text-lg"></i> + Pilih & Ceklis Mapel Kelas Ini
                </button>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">
                                <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllMapelKelas">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kelompok</th>
                            <th>Guru Pengajar (Mapel / Kelas)</th>
                            <th>Status TTD Digital</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($kelas->kelasMapels->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-24 text-secondary-light">
                                    Belum ada Mata Pelajaran yang dihubungkan ke Kelas {{ $kelas->nama_kelas }}. Klik <b>"+ Pilih & Ceklis Mapel Kelas Ini"</b> di kanan atas.
                                </td>
                            </tr>
                        @else
                            <?php foreach ($kelas->kelasMapels as $idx => $km): ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input check-item-mapel-kelas border-secondary cursor-pointer" value="{{ $km->id }}">
                                    </td>
                                    <td>{{ $idx + 1 }}</td>
                                    <td><span class="badge bg-neutral-200 text-primary-light fw-bold">{{ $km->mapel->kode_mapel ?? '-' }}</span></td>
                                    <td class="fw-bold text-primary-light">{{ $km->mapel->nama_mapel ?? '-' }}</td>
                                    <td><span class="text-xs text-secondary-light">{{ $km->mapel->kelompok ?? '-' }}</span></td>
                                    <td>
                                        <span class="fw-bold text-success-600 d-block">{{ $km->guru->nama_lengkap ?? 'Belum Ditentukan' }}</span>
                                        <span class="text-xs text-secondary-light">NIP: {{ $km->guru->nomor_induk ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if ($km->guru && $km->guru->ttd)
                                            <span class="bg-success-focus text-success-main px-10 py-4 rounded-pill text-xs fw-bold">✓ TTD Siap</span>
                                        @else
                                            <span class="bg-warning-focus text-warning-main px-10 py-4 rounded-pill text-xs">Belum Upload TTD</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('kelas.destroyPlottingMapel', $km->id) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini dari kelas?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-danger-focus text-danger-600 w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center border-0">
                                                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL TABEL CEKLIS MATA PELAJARAN (SEKALI KLIK SIMPAN BANYAK MAPEL!)      -->
    <!-- ========================================================================= -->
    @php
        $mapelTerpasangIds = $kelas->kelasMapels->pluck('mapel_id')->toArray();
    @endphp
    <div class="modal fade" id="addMapelKeKelasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <div>
                        <h1 class="modal-title fs-5 fw-bold mb-0">Pilih & Ceklis Mata Pelajaran</h1>
                        <span class="text-xs text-secondary-light">Ceklis 2 atau lebih mata pelajaran sekaligus untuk langsung disimpan dalam 1 kali klik</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('kelas.storePlottingMapel') }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <div class="row gy-3">
                            <!-- 1. Pilih Guru Pengajar -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-sm mb-6">
                                    1. Pilih Guru Pengajar (Wali Kelas / Guru Mapel) <span class="text-danger-600">*</span>
                                </label>
                                <select name="gtk_id" class="form-select radius-8 fw-bold" required>
                                    <option value="" disabled {{ !$kelas->wali_kelas_id ? 'selected' : '' }}>-- Pilih Nama Guru Pengajar --</option>
                                    <?php foreach ($gtks as $g): ?>
                                        <option value="{{ $g->id }}" {{ $kelas->wali_kelas_id == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama_lengkap }} — [{{ $g->posisi->nama_posisi ?? $g->jabatan->nama_jabatan ?? 'GTK' }}]
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 2. Tabel Ceklis Mata Pelajaran -->
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-8">
                                    <label class="form-label fw-bold text-sm mb-0">
                                        2. Ceklis Mata Pelajaran yang Diajar (<span id="jmlMapelDiceklis" class="text-primary-600">0</span> dipilih) <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" id="cariMapelInput" onkeyup="filterTabelMapelModal()" class="form-control form-control-sm radius-8" placeholder="🔍 Cari kode / nama mapel..." style="width: 210px;">
                                        <button type="button" onclick="toggleCeklisSemuaMapel(true)" class="btn btn-outline-primary btn-sm radius-8 py-4 px-10 text-xs fw-bold">
                                            Ceklis Semua
                                        </button>
                                        <button type="button" onclick="toggleCeklisSemuaMapel(false)" class="btn btn-outline-secondary btn-sm radius-8 py-4 px-10 text-xs fw-bold">
                                            Reset
                                        </button>
                                    </div>
                                </div>

                                <div class="border radius-12 overflow-hidden">
                                    <div style="max-height: 260px; overflow-y: auto;">
                                        <table class="table bordered-table mb-0 table-sm" id="tabelPilihMapelModal">
                                            <thead class="bg-neutral-100" style="position: sticky; top: 0; z-index: 2;">
                                                <tr>
                                                    <th width="6%" class="text-center">Pilih</th>
                                                    <th width="16%">Kode Mapel</th>
                                                    <th>Nama Mata Pelajaran</th>
                                                    <th width="25%">Kelompok / Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($mapels as $mp): ?>
                                                    @php
                                                        $sudahAda = in_array($mp->id, $mapelTerpasangIds);
                                                    @endphp
                                                    <tr class="baris-mapel-modal cursor-pointer" onclick="klikBarisMapel(event, this)">
                                                        <td class="text-center">
                                                            <input type="checkbox" name="mapel_ids[]" value="{{ $mp->id }}" class="form-check-input cb-pilih-mapel border-secondary cursor-pointer" onchange="hitungMapelDiceklis()">
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary-50 text-primary-600 fw-bold">{{ $mp->kode_mapel }}</span>
                                                        </td>
                                                        <td class="fw-bold text-primary-light">{{ $mp->nama_mapel }}</td>
                                                        <td>
                                                            @if ($sudahAda)
                                                                <span class="badge bg-success-focus text-success-main text-xs">✓ Sudah Ada di Kelas Ini</span>
                                                            @else
                                                                <span class="text-xs text-secondary-light">{{ $mp->kelompok ?? 'Umum / PAI' }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Ceklis Kelas Tujuan -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-sm mb-8">
                                    3. Terapkan ke Kelas Mana Saja? <span class="text-xs fw-normal text-secondary-light">(Otomatis terceklis Kelas {{ str_replace('Kelas ', '', $kelas->nama_kelas) }}, bisa ceklis kelas lain juga)</span>
                                </label>
                                <div class="row g-2 p-12 border radius-8 bg-neutral-50" style="max-height: 130px; overflow-y: auto;">
                                    <?php foreach ($kelasList as $klsItem): ?>
                                        <div class="col-md-3 col-6">
                                            <label class="d-flex align-items-center gap-2 p-6 bg-white border radius-8 cursor-pointer">
                                                <input type="checkbox" name="kelas_ids[]" value="{{ $klsItem->id }}" class="form-check-input m-0" {{ $klsItem->id == $kelas->id ? 'checked' : '' }}>
                                                <span class="text-xs fw-bold">Kelas {{ str_replace('Kelas ', '', $klsItem->nama_kelas) }}</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8 fw-bold px-20">
                            <i class="ph-bold ph-check-circle me-1"></i> Simpan Semua Mapel Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // 1. Ceklis Hapus Mapel yang Sudah Terdaftar di Tabel Kelas
    const checkAllMp = document.getElementById('checkAllMapelKelas');
    const btnBulkMp  = document.getElementById('btnBulkDeleteMapel');
    const countMp    = document.getElementById('countMapelSelected');

    function updateBulkMp() {
        const checked = document.querySelectorAll('.check-item-mapel-kelas:checked');
        countMp.innerText = checked.length;
        if (checked.length > 0) {
            btnBulkMp.classList.remove('d-none');
            btnBulkMp.classList.add('d-inline-flex');
        } else {
            btnBulkMp.classList.add('d-none');
            btnBulkMp.classList.remove('d-inline-flex');
        }
    }

    if (checkAllMp) {
        checkAllMp.addEventListener('change', function () {
            document.querySelectorAll('.check-item-mapel-kelas').forEach(cb => cb.checked = checkAllMp.checked);
            updateBulkMp();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-mapel-kelas')) updateBulkMp();
    });

    function hapusBanyakMapelKelas() {
        const checked = document.querySelectorAll('.check-item-mapel-kelas:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' Mata Pelajaran?',
            text: 'Mata pelajaran yang dicentang akan dihapus dari kelas ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const box = document.getElementById('hiddenIdsMapelKelas');
                box.innerHTML = '';
                checked.forEach(cb => {
                    box.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                document.getElementById('formBulkMapelKelas').submit();
            }
        });
    }

    // 2. Interaksi Tabel Ceklis Mata Pelajaran di Dalam Modal
    function klikBarisMapel(e, trElement) {
        if (e.target.tagName === 'INPUT') return;
        const cb = trElement.querySelector('.cb-pilih-mapel');
        if (cb) {
            cb.checked = !cb.checked;
            hitungMapelDiceklis();
        }
    }

    function hitungMapelDiceklis() {
        const jml = document.querySelectorAll('.cb-pilih-mapel:checked').length;
        document.getElementById('jmlMapelDiceklis').innerText = jml;
    }

    function toggleCeklisSemuaMapel(status) {
        document.querySelectorAll('.baris-mapel-modal').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.cb-pilih-mapel');
                if (cb) cb.checked = status;
            }
        });
        hitungMapelDiceklis();
    }

    function filterTabelMapelModal() {
        const keyword = document.getElementById('cariMapelInput').value.toLowerCase();
        document.querySelectorAll('.baris-mapel-modal').forEach(row => {
            const teks = row.innerText.toLowerCase();
            row.style.display = teks.includes(keyword) ? '' : 'none';
        });
    }
</script>
@endpush