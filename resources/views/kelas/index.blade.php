@extends('layouts.admin')

@section('title', 'Manajemen Kelas & Penugasan Pengajar')

@section('content')

    <!-- Breadcrumb Header -->
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Kelas / Rombongan Belajar</h6>
            <span class="text-xs text-secondary-light">
                Tahun Ajaran Aktif: <b class="text-success-600">{{ $tahunAktif->tahun_ajaran ?? '2026/2027' }} ({{ $tahunAktif->semester ?? 'Ganjil' }})</b>
            </span>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Manajemen Kelas & Kelas</li>
                <li>-</li>
                <li class="fw-medium text-primary-600">Manajemen Kelas</li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Tombol Tambah Kelas / Penugasan Guru Mapel -->
            <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addKelasModal">
                <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Kelas / Plotting Mapel
            </button>
        </div>
    </div>

    <!-- Toggle Tampilan Kartu vs Tabel -->
    <div class="d-flex align-items-center gap-2 mb-20">
        <button type="button" id="btnViewCard" onclick="switchViewKelas('card')" class="btn btn-success-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold">
            <i class="ph-bold ph-squares-four"></i> Tampilan Kartu
        </button>
        <button type="button" id="btnViewTable" onclick="switchViewKelas('table')" class="btn btn-outline-secondary btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold">
            <i class="ph-bold ph-list-bullets"></i> Tampilan Tabel
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. TAMPILAN KARTU KELAS (DILENGKAPI TOMBOL PANAH MENUJU DASHBOARD KELAS)  -->
    <!-- ========================================================================= -->
    <div id="sectionCardView" class="row gy-4">
        @if ($kelasList->isEmpty())
            <div class="col-12">
                <div class="card radius-12 p-40 text-center">
                    <h6 class="text-secondary-light mb-8">Belum ada data Rombongan Belajar.</h6>
                    <p class="text-xs text-secondary-light mb-0">Klik tombol <b>"Tambah Kelas / Plotting Mapel"</b> di pojok kanan atas untuk memulai.</p>
                </div>
            </div>
        @else
            <?php foreach ($kelasList as $kls): ?>
                @php
                    $jmlSiswa   = $kls->siswas->count();
                    $kapasitas  = $kls->kapasitas ?: 30;
                    $jmlMapel   = $kls->kelasMapels->count();
                    $avatarWali = ($kls->waliKelas && $kls->waliKelas->foto)
                        ? asset('storage/' . $kls->waliKelas->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($kls->waliKelas->nama_lengkap ?? 'Wali') . '&background=dc2626&color=fff&bold=true';
                @endphp
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="card radius-16 border shadow-sm h-100 hover-border-success transition-2">
                        <div class="card-body p-20 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-16">
                                    <div>
                                        <h4 class="fw-black text-primary-light mb-2">{{ str_replace('Kelas ', '', $kls->nama_kelas) }}</h4>
                                        <span class="text-xs text-secondary-light d-flex align-items-center gap-1">
                                            <i class="ph-bold ph-map-pin text-success-600"></i>
                                            {{ $kls->ruangan ?: 'Ruang ' . $kls->nama_kelas }}
                                        </span>
                                    </div>
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-bold text-xs">
                                        Tingkat {{ $kls->tingkat }}
                                    </span>
                                </div>

                                <div class="p-12 radius-12 bg-neutral-50 border mb-12 d-flex align-items-center gap-10">
                                    <img src="{{ $avatarWali }}" alt="Wali" class="w-36-px h-36-px rounded-circle object-fit-cover flex-shrink-0 border">
                                    <div class="overflow-hidden">
                                        <span class="text-xs text-secondary-light d-block">Wali Kelas</span>
                                        <span class="text-sm fw-bold text-primary-light d-block text-truncate">
                                            {{ $kls->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between px-4 mb-16">
                                    <span class="text-xs text-secondary-light">Mata Pelajaran Terhubung:</span>
                                    <span class="badge bg-info-focus text-info-main fw-bold text-xs">
                                        {{ $jmlMapel }} Mapel
                                    </span>
                                </div>
                            </div>

                            <div class="pt-12 border-top d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-xs text-secondary-light d-block">Kapasitas Rombel</span>
                                    <span class="text-sm fw-extrabold {{ $jmlSiswa > $kapasitas ? 'text-danger-600' : 'text-success-600' }}">
                                        {{ $jmlSiswa }} / {{ $kapasitas }} Siswa
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-6">
                                    <button type="button"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editKelasModal{{ $kls->id }}"
                                            title="Edit Kelas">
                                        <iconify-icon icon="lucide:edit" class="text-sm"></iconify-icon>
                                    </button>

                                    <button type="button"
                                            onclick="hapusKelas('{{ route('kelas.destroy', $kls->id) }}', '{{ addslashes($kls->nama_kelas) }}')"
                                            class="bg-danger-focus text-danger-600 bg-hover-danger-200 w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                            title="Hapus Kelas">
                                        <iconify-icon icon="fluent:delete-24-regular" class="text-sm"></iconify-icon>
                                    </button>

                                    <a href="{{ route('kelas.show', $kls->id) }}"
                                       class="bg-primary-600 text-white bg-hover-primary-700 w-36-px h-36-px d-flex justify-content-center align-items-center rounded-circle shadow-sm ms-1"
                                       title="Buka Dashboard Kelas {{ $kls->nama_kelas }}">
                                        <i class="ph-bold ph-arrow-right text-md"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TAMPILAN TABEL KELAS                                                   -->
    <!-- ========================================================================= -->
    <div id="sectionTableView" class="card radius-12 d-none">
        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                            <th>Ruangan</th>
                            <th>Siswa / Kapasitas</th>
                            <th>Jumlah Mapel</th>
                            <th class="text-center">Dashboard & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kelasList as $idx => $kls): ?>
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold text-primary-light">{{ $kls->nama_kelas }}</td>
                                <td><span class="badge bg-success-focus text-success-main">Tingkat {{ $kls->tingkat }}</span></td>
                                <td class="fw-semibold">{{ $kls->waliKelas->nama_lengkap ?? '-' }}</td>
                                <td>{{ $kls->ruangan ?: '-' }}</td>
                                <td class="fw-bold">{{ $kls->siswas->count() }} / {{ $kls->kapasitas }} Siswa</td>
                                <td><span class="badge bg-info-focus text-info-main">{{ $kls->kelasMapels->count() }} Mapel</span></td>
                                <td class="text-center">
                                    <a href="{{ route('kelas.show', $kls->id) }}" class="btn btn-primary-600 btn-sm radius-8 px-12 py-4 d-inline-flex align-items-center gap-1 fw-bold">
                                        Buka Kelas <i class="ph-bold ph-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="formDeleteKelas" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================================================= -->
    <!-- MODAL GABUNGAN: 1. ROMBEL & WALI KELAS | 2. TABEL CEKLIS MAPEL & KELAS    -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h1 class="modal-title fs-5 fw-bold">Tambah Kelas / Plotting Guru Mata Pelajaran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-24">

                    <div class="mb-20">
                        <label class="form-label fw-bold text-xs text-secondary-light text-uppercase mb-8">Pilih Mode Pengaturan:</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" id="tabModeWali" onclick="gantiModeModalKelas('wali')" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2">
                                    <i class="ph-bold ph-users-three text-lg"></i> 1. Buat Rombel & Wali Kelas
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" id="tabModeMapel" onclick="gantiModeModalKelas('mapel')" class="btn btn-outline-primary w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2">
                                    <i class="ph-bold ph-checks text-lg"></i> 2. Ceklis Mapel & Guru Pengajar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- FORM A: ROMBEL & WALI KELAS -->
                    <form id="formModeWali" action="{{ route('kelas.store') }}" method="POST">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Tingkat Kelas <span class="text-danger-600">*</span></label>
                                <select name="tingkat" class="form-select radius-8" required>
                                    <option value="" disabled selected>-- Pilih Tingkat --</option>
                                    <?php for ($t = 1; $t <= 6; $t++): ?>
                                        <option value="{{ $t }}">Tingkat {{ $t }} (Kelas {{ $t }})</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Nama Kelas / Rombel <span class="text-danger-600">*</span></label>
                                <input type="text" name="nama_kelas" class="form-control radius-8" placeholder="Contoh: 1-A atau 3-B" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-sm mb-6">Pilih Wali Kelas (Dari Master GTK)</label>
                                <select name="wali_kelas_id" class="form-select radius-8">
                                    <option value="">-- Pilih Wali Kelas (Bisa Menyusul) --</option>
                                    <?php foreach ($gtks as $g): ?>
                                        @php
                                            $jabNama = $g->jabatan->nama_jabatan ?? '';
                                            $posNama = $g->posisi->nama_posisi ?? '';
                                        @endphp
                                        <option value="{{ $g->id }}">
                                            {{ $g->nama_lengkap }} — [{{ $jabNama }}{{ $posNama ? ' : ' . $posNama : '' }}]
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Nama Ruangan</label>
                                <input type="text" name="ruangan" class="form-control radius-8" placeholder="Contoh: Ruang 1-A / Lantai 1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-sm mb-6">Kapasitas Siswa <span class="text-danger-600">*</span></label>
                                <input type="number" name="kapasitas" class="form-control radius-8" value="30" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-24 pt-16 border-top">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Rombel Baru</button>
                        </div>
                    </form>

                    <!-- FORM B: TABEL CEKLIS MATA PELAJARAN & CEKLIS KELAS (SEKALI KLIK SIMPAN BANYAK!) -->
                    <form id="formModeMapel" action="{{ route('kelas.storePlottingMapel') }}" method="POST" class="d-none">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-sm mb-6">1. Pilih Guru Pengajar <span class="text-danger-600">*</span></label>
                                <select name="gtk_id" class="form-select radius-8 fw-bold" required>
                                    <option value="" disabled selected>-- Pilih Nama Guru --</option>
                                    <?php foreach ($gtks as $g): ?>
                                        <option value="{{ $g->id }}">
                                            {{ $g->nama_lengkap }} — [{{ $g->posisi->nama_posisi ?? $g->jabatan->nama_jabatan ?? 'GTK' }}]
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-8">
                                    <label class="form-label fw-bold text-sm mb-0">
                                        2. Ceklis Tabel Kode & Mata Pelajaran (<span id="countMapelIndex" class="text-primary-600">0</span> dipilih) <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" id="searchMapelIndex" onkeyup="filterMapelIndex()" class="form-control form-control-sm radius-8" placeholder="🔍 Cari kode / mapel..." style="width: 190px;">
                                        <button type="button" onclick="ceklisSemuaMapelIndex(true)" class="btn btn-outline-primary btn-sm radius-8 py-4 px-10 text-xs fw-bold">Pilih Semua</button>
                                        <button type="button" onclick="ceklisSemuaMapelIndex(false)" class="btn btn-outline-secondary btn-sm radius-8 py-4 px-10 text-xs fw-bold">Reset</button>
                                    </div>
                                </div>

                                <div class="border radius-12 overflow-hidden">
                                    <div style="max-height: 220px; overflow-y: auto;">
                                        <table class="table bordered-table mb-0 table-sm">
                                            <thead class="bg-neutral-100" style="position: sticky; top: 0; z-index: 2;">
                                                <tr>
                                                    <th width="6%" class="text-center">Pilih</th>
                                                    <th width="16%">Kode</th>
                                                    <th>Nama Mata Pelajaran</th>
                                                    <th width="25%">Kelompok</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($mapels as $mp): ?>
                                                    <tr class="row-mapel-index cursor-pointer" onclick="klikBarisMapelIndex(event, this)">
                                                        <td class="text-center">
                                                            <input type="checkbox" name="mapel_ids[]" value="{{ $mp->id }}" class="form-check-input cb-mapel-index border-secondary cursor-pointer" onchange="hitungMapelIndex()">
                                                        </td>
                                                        <td><span class="badge bg-primary-50 text-primary-600 fw-bold">{{ $mp->kode_mapel }}</span></td>
                                                        <td class="fw-bold text-primary-light">{{ $mp->nama_mapel }}</td>
                                                        <td><span class="text-xs text-secondary-light">{{ $mp->kelompok ?? 'Umum / PAI' }}</span></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex align-items-center justify-content-between mb-8">
                                    <label class="form-label fw-bold text-sm mb-0">
                                        3. Terapkan di Kelas Mana Saja? <span class="text-danger-600">*</span>
                                    </label>
                                    <button type="button" onclick="ceklisSemuaKelasMapel(true)" class="btn btn-outline-primary btn-xs py-2 px-8 radius-4 text-xs fw-bold">
                                        Ceklis Semua Kelas
                                    </button>
                                </div>

                                <div class="row g-2 p-12 border radius-8 bg-neutral-50" style="max-height: 140px; overflow-y: auto;">
                                    <?php foreach ($kelasList as $kls): ?>
                                        <div class="col-md-3 col-6">
                                            <label class="d-flex align-items-center gap-2 p-6 bg-white border radius-8 cursor-pointer">
                                                <input type="checkbox" name="kelas_ids[]" value="{{ $kls->id }}" class="form-check-input check-kelas-plotting m-0">
                                                <span class="text-xs fw-bold text-primary-light">Kelas {{ str_replace('Kelas ', '', $kls->nama_kelas) }}</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-24 pt-16 border-top">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-info-600 radius-8 fw-bold px-20">
                                <i class="ph-bold ph-check-circle me-1"></i> Simpan Semua Mapel & Kelas Terpilih
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kelas -->
    <?php foreach ($kelasList as $kls): ?>
        <div class="modal fade" id="editKelasModal{{ $kls->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5">Edit Rombel {{ $kls->nama_kelas }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('kelas.update', $kls->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Tingkat Kelas</label>
                                    <select name="tingkat" class="form-select radius-8" required>
                                        <?php for ($t = 1; $t <= 6; $t++): ?>
                                            <option value="{{ $t }}" {{ $kls->tingkat == $t ? 'selected' : '' }}>Tingkat {{ $t }}</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Kelas</label>
                                    <input type="text" name="nama_kelas" class="form-control radius-8" value="{{ $kls->nama_kelas }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-sm mb-6">Wali Kelas</label>
                                    <select name="wali_kelas_id" class="form-select radius-8">
                                        <option value="">-- Belum Ditentukan --</option>
                                        <?php foreach ($gtks as $g): ?>
                                            <option value="{{ $g->id }}" {{ $kls->wali_kelas_id == $g->id ? 'selected' : '' }}>
                                                {{ $g->nama_lengkap }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Nama Ruangan</label>
                                    <input type="text" name="ruangan" class="form-control radius-8" value="{{ $kls->ruangan }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-sm mb-6">Kapasitas Siswa</label>
                                    <input type="number" name="kapasitas" class="form-control radius-8" value="{{ $kls->kapasitas }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-600 radius-8 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

@endsection

@push('scripts')
<script>
    function switchViewKelas(mode) {
        const cardSec = document.getElementById('sectionCardView');
        const tblSec  = document.getElementById('sectionTableView');
        const btnCard = document.getElementById('btnViewCard');
        const btnTbl  = document.getElementById('btnViewTable');

        if (mode === 'card') {
            cardSec.classList.remove('d-none');
            tblSec.classList.add('d-none');
            btnCard.className = 'btn btn-success-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold';
            btnTbl.className  = 'btn btn-outline-secondary btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold';
        } else {
            cardSec.classList.add('d-none');
            tblSec.classList.remove('d-none');
            btnTbl.className  = 'btn btn-success-600 btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold';
            btnCard.className = 'btn btn-outline-secondary btn-sm radius-8 px-14 py-8 d-flex align-items-center gap-2 fw-bold';
        }
    }

    function gantiModeModalKelas(mode) {
        const fWali  = document.getElementById('formModeWali');
        const fMapel = document.getElementById('formModeMapel');
        const bWali  = document.getElementById('tabModeWali');
        const bMapel = document.getElementById('tabModeMapel');

        if (mode === 'wali') {
            fWali.classList.remove('d-none');
            fMapel.classList.add('d-none');
            bWali.className  = 'btn btn-primary-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2';
            bMapel.className = 'btn btn-outline-primary w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2';
        } else {
            fWali.classList.add('d-none');
            fMapel.classList.remove('d-none');
            bMapel.className = 'btn btn-info-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2';
            bWali.className  = 'btn btn-outline-primary w-100 radius-8 py-10 fw-bold d-flex align-items-center gap-2';
        }
    }

    function klikBarisMapelIndex(e, tr) {
        if (e.target.tagName === 'INPUT') return;
        const cb = tr.querySelector('.cb-mapel-index');
        if (cb) {
            cb.checked = !cb.checked;
            hitungMapelIndex();
        }
    }

    function hitungMapelIndex() {
        const jml = document.querySelectorAll('.cb-mapel-index:checked').length;
        document.getElementById('countMapelIndex').innerText = jml;
    }

    function ceklisSemuaMapelIndex(status) {
        document.querySelectorAll('.row-mapel-index').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.cb-mapel-index');
                if (cb) cb.checked = status;
            }
        });
        hitungMapelIndex();
    }

    function filterMapelIndex() {
        const kw = document.getElementById('searchMapelIndex').value.toLowerCase();
        document.querySelectorAll('.row-mapel-index').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
        });
    }

    function ceklisSemuaKelasMapel(status) {
        document.querySelectorAll('.check-kelas-plotting').forEach(cb => cb.checked = status);
    }

    function hapusKelas(url, nama) {
        Swal.fire({
            title: 'Hapus Rombel ' + nama + '?',
            text: 'Data kelas ini akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const f = document.getElementById('formDeleteKelas');
                f.action = url;
                f.submit();
            }
        });
    }
</script>
@endpush