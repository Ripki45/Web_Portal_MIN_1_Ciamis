@extends('layouts.admin')

@section('title', 'Anggota Ekstrakurikuler ' . $ekskul->nama_ekskul)

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div class="d-flex align-items-center gap-12">
            <a href="{{ route('ekskul.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-12 py-8 d-flex align-items-center gap-1 fw-bold">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <div>
                <h5 class="fw-bold mb-0">Daftar Anggota Ekstrakurikuler: {{ $ekskul->nama_ekskul }}</h5>
                <span class="text-xs text-secondary-light">
                    Pembina: <b>{{ $ekskul->pembina->nama_lengkap ?? 'Belum Ditentukan' }}</b> • Jadwal: <b>{{ $ekskul->hari_latihan ?: '-' }} ({{ $ekskul->jam_latihan ?: '-' }})</b>
                </span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Form Keluarkan Banyak Anggota (Fitur Ceklis) -->
            <form action="{{ route('ekskul.bulkDestroyAnggota') }}" method="POST" id="formBulkDeleteAnggota" class="d-inline">
                @csrf
                <div id="hiddenIdsAnggota"></div>
                <button type="button" id="btnBulkAnggota" onclick="konfirmasiKeluarkanBanyak()" class="btn btn-danger-600 radius-8 px-14 py-9 d-none align-items-center gap-2 text-sm fw-bold">
                    <i class="ph-bold ph-user-minus text-lg"></i> Keluarkan Terpilih (<span id="countSelectedAnggota">0</span>)
                </button>
            </form>

            <!-- Tombol Buka Modal Ceklis Pendaftaran Siswa -->
            <button type="button" class="btn btn-primary-600 radius-8 px-16 py-9 d-flex align-items-center gap-2 text-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addAnggotaModal">
                <i class="ph-bold ph-checks text-lg"></i> + Pilih & Ceklis Anggota Siswa
            </button>
        </div>
    </div>

    <!-- Tabel Daftar Anggota Terdaftar -->
    <div class="card basic-data-table radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-md fw-bold mb-0">Siswa Terdaftar di {{ $ekskul->nama_ekskul }}</h6>
            <span class="badge bg-success-focus text-success-main fw-bold px-12 py-6">
                Total: {{ $ekskul->anggotas->count() }} Anggota Aktif
            </span>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="tableAnggotaEkskul" data-page-length='25'>
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">
                                <input type="checkbox" class="form-check-input border-secondary cursor-pointer" id="checkAllAnggota">
                            </th>
                            <th width="5%">No</th>
                            <th>Kelas</th>
                            <th>NISN / No. Induk</th>
                            <th>Nama Lengkap Siswa</th>
                            <th class="text-center">L/P</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ekskul->anggotas as $idx => $ag): ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input check-item-anggota border-secondary cursor-pointer" value="{{ $ag->id }}">
                                </td>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <span class="badge bg-success-focus text-success-main fw-bold">
                                        {{ $ag->siswa->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-xs">
                                    <b>{{ $ag->siswa->nisn ?: ($ag->siswa->nomor_induk ?: '-') }}</b>
                                </td>
                                <td class="fw-bold text-primary-light">{{ $ag->siswa->nama_siswa ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ ($ag->siswa->jenis_kelamin ?? 'L') == 'L' ? 'bg-info-focus text-info-main' : 'bg-warning-focus text-warning-main' }}">
                                        {{ $ag->siswa->jenis_kelamin ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('ekskul.destroyAnggota', $ag->id) }}" method="POST" onsubmit="return confirm('Keluarkan siswa ini dari ekstrakurikuler?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-danger-focus text-danger-600 w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center border-0" title="Keluarkan">
                                            <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL PILIH & CEKLIS SISWA LINTAS KELAS (SEKALI KLIK DAFTARKAN BANYAK!)   -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="addAnggotaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <div>
                        <h1 class="modal-title fs-5 fw-bold mb-0">Daftarkan Anggota ke {{ $ekskul->nama_ekskul }}</h1>
                        <span class="text-xs text-secondary-light">Filter per kelas atau cari nama siswa, lalu ceklis siswa yang mengikuti ekskul ini</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('ekskul.storeAnggota', $ekskul->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-24">
                        <!-- Baris Filter Kelas, Cari Nama, & Tombol Ceklis Semua -->
                        <div class="row g-2 align-items-center mb-12">
                            <div class="col-md-4">
                                <select id="filterKelasModalEkskul" onchange="saringSiswaModalEkskul()" class="form-select form-select-sm radius-8 fw-bold">
                                    <option value="">-- Semua Kelas (Tingkat 1-6) --</option>
                                    <?php foreach ($kelasList as $kls): ?>
                                        <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="cariNamaSiswaEkskul" onkeyup="saringSiswaModalEkskul()" class="form-control form-control-sm radius-8" placeholder="🔍 Ketik nama siswa...">
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" onclick="ceklisSemuaSiswaTampil(true)" class="btn btn-outline-primary btn-sm radius-8 py-4 px-10 text-xs fw-bold">
                                    Ceklis Semua Tampil
                                </button>
                                <button type="button" onclick="ceklisSemuaSiswaTampil(false)" class="btn btn-outline-secondary btn-sm radius-8 py-4 px-10 text-xs fw-bold">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <div class="mb-8 d-flex justify-content-between align-items-center">
                            <span class="text-xs fw-bold text-primary-600">
                                <span id="jmlSiswaDiceklisEkskul">0</span> Siswa baru dipilih untuk didaftarkan
                            </span>
                            <span class="text-xs text-secondary-light">* Klik di mana saja pada baris siswa untuk menceklis</span>
                        </div>

                        <div class="border radius-12 overflow-hidden">
                            <div style="max-height: 320px; overflow-y: auto;">
                                <table class="table bordered-table mb-0 table-sm">
                                    <thead class="bg-neutral-100" style="position: sticky; top: 0; z-index: 2;">
                                        <tr>
                                            <th width="7%" class="text-center">Pilih</th>
                                            <th width="16%">Kelas</th>
                                            <th width="20%">NISN / Induk</th>
                                            <th>Nama Siswa</th>
                                            <th width="20%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($semuaSiswa as $s): ?>
                                            @php
                                                $sudahIkut = in_array($s->id, $terdaftarIds);
                                            @endphp
                                            <tr class="row-siswa-ekskul cursor-pointer"
                                                data-kelas="{{ $s->kelas_id }}"
                                                onclick="klikBarisSiswaEkskul(event, this)">
                                                <td class="text-center">
                                                    @if ($sudahIkut)
                                                        <input type="checkbox" class="form-check-input" checked disabled>
                                                    @else
                                                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" class="form-check-input cb-pilih-siswa-ekskul border-secondary cursor-pointer" onchange="hitungSiswaEkskul()">
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-neutral-200 text-primary-light fw-bold">{{ $s->kelas->nama_kelas ?? '-' }}</span></td>
                                                <td class="text-xs">{{ $s->nisn ?: ($s->nomor_induk ?: '-') }}</td>
                                                <td class="fw-bold text-primary-light">{{ $s->nama_siswa }}</td>
                                                <td>
                                                    @if ($sudahIkut)
                                                        <span class="badge bg-success-focus text-success-main text-xs">✓ Sudah Terdaftar</span>
                                                    @else
                                                        <span class="text-xs text-secondary-light">Belum Terdaftar</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer px-24 pb-24 border-0">
                        <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8 fw-bold px-20">
                            <i class="ph-bold ph-check-circle me-1"></i> Daftarkan Siswa Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let tblAg = new DataTable('#tableAnggotaEkskul', {
        columnDefs: [{ orderable: false, targets: [0, 6] }]
    });

    const checkAllAg = document.getElementById('checkAllAnggota');
    const btnBulkAg  = document.getElementById('btnBulkAnggota');
    const spanCountAg= document.getElementById('countSelectedAnggota');

    function updateBulkAnggota() {
        const checked = document.querySelectorAll('.check-item-anggota:checked');
        spanCountAg.innerText = checked.length;
        if (checked.length > 0) {
            btnBulkAg.classList.remove('d-none');
            btnBulkAg.classList.add('d-inline-flex');
        } else {
            btnBulkAg.classList.add('d-none');
            btnBulkAg.classList.remove('d-inline-flex');
        }
    }

    if (checkAllAg) {
        checkAllAg.addEventListener('change', function () {
            document.querySelectorAll('.check-item-anggota').forEach(cb => cb.checked = checkAllAg.checked);
            updateBulkAnggota();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('check-item-anggota')) updateBulkAnggota();
    });

    function konfirmasiKeluarkanBanyak() {
        const checked = document.querySelectorAll('.check-item-anggota:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Keluarkan ' + checked.length + ' Anggota?',
            text: 'Siswa yang dicentang akan dikeluarkan dari daftar ekstrakurikuler ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluarkan!',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) {
                const box = document.getElementById('hiddenIdsAnggota');
                box.innerHTML = '';
                checked.forEach(cb => {
                    box.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}">`);
                });
                document.getElementById('formBulkDeleteAnggota').submit();
            }
        });
    }

    // Fungsi Filter & Ceklis di Dalam Modal Pendaftaran Siswa
    function klikBarisSiswaEkskul(e, tr) {
        if (e.target.tagName === 'INPUT') return;
        const cb = tr.querySelector('.cb-pilih-siswa-ekskul');
        if (cb && !cb.disabled) {
            cb.checked = !cb.checked;
            hitungSiswaEkskul();
        }
    }

    function hitungSiswaEkskul() {
        const jml = document.querySelectorAll('.cb-pilih-siswa-ekskul:checked').length;
        document.getElementById('jmlSiswaDiceklisEkskul').innerText = jml;
    }

    function saringSiswaModalEkskul() {
        const klsId   = document.getElementById('filterKelasModalEkskul').value;
        const keyword = document.getElementById('cariNamaSiswaEkskul').value.toLowerCase();

        document.querySelectorAll('.row-siswa-ekskul').forEach(row => {
            const rowKelas = row.getAttribute('data-kelas');
            const rowText  = row.innerText.toLowerCase();

            const cocokKelas = (!klsId || rowKelas === klsId);
            const cocokTeks  = (!keyword || rowText.includes(keyword));

            row.style.display = (cocokKelas && cocokTeks) ? '' : 'none';
        });
    }

    function ceklisSemuaSiswaTampil(status) {
        document.querySelectorAll('.row-siswa-ekskul').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.cb-pilih-siswa-ekskul');
                if (cb && !cb.disabled) cb.checked = status;
            }
        });
        hitungSiswaEkskul();
    }
</script>
@endpush