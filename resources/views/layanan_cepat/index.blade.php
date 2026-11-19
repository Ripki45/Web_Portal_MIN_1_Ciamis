@extends('layouts.admin')

@section('title', 'Manajemen Layanan Cepat (Quick Access) - MIN 1 Ciamis')

@section('content')

    <!-- Font Awesome 6.5.1 untuk Preview Ikon Nyata di Dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .icon-preview-card {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #ecfdf5;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .icon-palette-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .icon-palette-btn:hover {
            background: #0d9488;
            color: #ffffff;
            border-color: #0d9488;
            transform: scale(1.1);
        }
    </style>

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Layanan Cepat (Portal Akses Kilat)</h6>
            <span class="text-xs text-secondary-light">
                Kelola menu ikon pintasan aplikasi madrasah, SIMPATIKA, EMIS, RDM, dan tautan eksternal lainnya di beranda.
            </span>
        </div>
        <a href="{{ url('/') }}#layanan-cepat" target="_blank"
            class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Layanan di Halaman Depan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show radius-8 mb-24" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-bold ph-check-circle text-lg"></i>
                <span class="fw-semibold text-sm">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-4">

        <!-- ========================================================================= -->
        <!-- SISI KIRI (5 KOLOM): FORM TAMBAH LAYANAN CEPAT BARU                       -->
        <!-- ========================================================================= -->
        <div class="col-lg-5">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20">
                    <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Layanan Cepat Baru
                    </h6>
                </div>

                <form action="{{ route('layanan-cepat.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-20">

                        <!-- Nama Layanan -->
                        <div class="mb-16">
                            <label class="form-label fw-bold text-xs mb-4">
                                Nama Layanan <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="nama_layanan" class="form-control radius-8 fw-semibold"
                                placeholder="Contoh: Scanner QR Presensi / EMIS Madrasah" required>
                        </div>

                        <!-- Ikon Font Awesome / Favicon + Live Preview -->
                        <div class="p-14 radius-12 bg-primary-50 border border-primary-200 mb-16">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <label class="form-label fw-bold text-xs text-primary-600 m-0">
                                    Ikon / Favicon (Font Awesome Class) <span class="text-danger-600">*</span>
                                </label>
                                <span class="text-2xs text-secondary-light">Contoh: fas fa-qrcode</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-12">
                                <div class="icon-preview-card" id="boxPreviewAdd" title="Live Preview Ikon">
                                    <i class="fas fa-qrcode" id="iconPreviewAdd"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="text" name="icon" id="inputIconAdd" class="form-control radius-8 fw-semibold font-monospace"
                                        value="fas fa-qrcode" placeholder="fas fa-qrcode" required onkeyup="updateLiveIconAdd(this.value)">
                                </div>
                            </div>

                            <!-- Palet Cepat Ikon Rekomendasi -->
                            <div>
                                <span class="d-block text-2xs fw-bold text-secondary-light mb-6">PILIH CEPAT IKON REKOMENDASI:</span>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($iconRekomendasi as $icoClass => $icoLabel)
                                        <button type="button" class="icon-palette-btn" title="{{ $icoLabel }} ({{ $icoClass }})"
                                            onclick="pilihIconAdd('{{ $icoClass }}')">
                                            <i class="{{ $icoClass }}"></i>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Link Isian / URL Tujuan -->
                        <div class="mb-16">
                            <label class="form-label fw-bold text-xs mb-4">
                                Link Isian / URL Tujuan <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" name="link_url" class="form-control radius-8 font-monospace text-xs"
                                placeholder="https://emis.kemenag.go.id/ atau /presensi-scanner" required>
                            <div class="form-text text-2xs text-secondary-light mt-1">
                                Masukkan URL lengkap (misal: https://...) atau path lokal (misal: /presensi-scanner).
                            </div>
                        </div>

                        <!-- Target Buka & Urutan -->
                        <div class="row g-2 mb-16">
                            <div class="col-6">
                                <label class="form-label fw-bold text-xs mb-4">Buka Di Tab <span class="text-danger-600">*</span></label>
                                <select name="target" class="form-select radius-8 fw-semibold">
                                    <option value="_blank" selected>Tab Baru (_blank)</option>
                                    <option value="_self">Halaman Sama (_self)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-xs mb-4">Nomor Urut</label>
                                <input type="number" name="urutan" class="form-control radius-8 fw-bold"
                                    value="{{ $urutanBerikutnya }}" min="1">
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="p-12 radius-10 bg-light border mb-20 d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fw-bold text-xs text-dark d-block" for="is_active_add">
                                    Status Aktif
                                </label>
                                <span class="text-2xs text-secondary-light">Tampilkan layanan ini di beranda</span>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active_add" name="is_active" value="1"
                                    checked style="width: 2.4em; height: 1.3em;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="ph-bold ph-plus text-lg"></i> Simpan Layanan Cepat
                        </button>

                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SISI KANAN (7 KOLOM): DAFTAR SEMUA LAYANAN CEPAT                          -->
        <!-- ========================================================================= -->
        <div class="col-lg-7">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-20 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                            <i class="ph-bold ph-list-bullets text-lg"></i> Daftar Layanan Cepat ({{ $layananCepats->count() }})
                        </h6>
                    </div>
                    <span class="badge bg-success-focus text-success-main border border-success text-xs px-10 py-4 radius-6 fw-bold">
                        Top 6 Tampil Default
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="p-16 border-bottom bg-light d-flex align-items-center gap-2">
                        <i class="ph-bold ph-info text-primary-600 text-lg flex-shrink-0"></i>
                        <span class="text-xs text-secondary-light">
                            <b>6 Layanan pertama</b> (urutan terkecil) otomatis tampil langsung di layar beranda. Layanan ke-7 dan seterusnya akan muncul saat pengunjung mengklik <b>"Lihat Selengkapnya"</b>.
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-base border-bottom">
                                <tr>
                                    <th class="text-xs fw-bold text-secondary-light ps-20 py-12" style="width: 60px;">URUT</th>
                                    <th class="text-xs fw-bold text-secondary-light py-12" style="width: 60px;">IKON</th>
                                    <th class="text-xs fw-bold text-secondary-light py-12">LAYANAN &amp; LINK</th>
                                    <th class="text-xs fw-bold text-secondary-light py-12 text-center" style="width: 100px;">STATUS</th>
                                    <th class="text-xs fw-bold text-secondary-light pe-20 py-12 text-end" style="width: 110px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($layananCepats as $idx => $item)
                                    @php
                                        $isTop6 = $idx < 6;
                                    @endphp
                                    <tr>
                                        <!-- Nomor Urut -->
                                        <td class="ps-20 py-14">
                                            <span class="badge {{ $isTop6 ? 'bg-primary-600 text-white' : 'bg-light text-dark border' }} radius-6 fw-bold text-xs px-8 py-4">
                                                #{{ $item->urutan }}
                                            </span>
                                        </td>

                                        <!-- Ikon Live -->
                                        <td class="py-14">
                                            <div class="icon-preview-card" style="width: 40px; height: 40px; font-size: 18px;" title="{{ $item->icon }}">
                                                <i class="{{ $item->icon }}"></i>
                                            </div>
                                        </td>

                                        <!-- Nama & Link -->
                                        <td class="py-14">
                                            <div class="fw-bold text-sm text-dark">{{ $item->nama_layanan }}</div>
                                            <a href="{{ $item->link_url }}" target="_blank" rel="noopener noreferrer"
                                                class="text-xs text-primary-600 text-truncate d-inline-block text-decoration-none" style="max-width: 280px;">
                                                <i class="ph-bold ph-link text-xs"></i> {{ $item->link_url }}
                                            </a>
                                            @if ($isTop6)
                                                <span class="badge bg-success-focus text-success-main text-2xs ms-1 px-6 py-2 radius-4">6 Akses Utama</span>
                                            @endif
                                        </td>

                                        <!-- Toggle Status Aktif -->
                                        <td class="py-14 text-center">
                                            <form action="{{ route('layanan-cepat.toggle-status', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="border-0 bg-transparent p-0" title="Klik untuk ubah status">
                                                    @if ($item->is_active)
                                                        <span class="badge bg-success text-white radius-6 px-10 py-4 text-2xs fw-bold">
                                                            <i class="ph-bold ph-check"></i> Aktif
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-muted radius-6 px-10 py-4 text-2xs fw-bold">
                                                            Nonaktif
                                                        </span>
                                                    @endif
                                                </button>
                                            </form>
                                        </td>

                                        <!-- Tombol Aksi -->
                                        <td class="pe-20 py-14 text-end">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <!-- Tombol Edit Modal -->
                                                <button type="button" class="btn btn-outline-primary-600 btn-sm radius-6 px-8 py-4"
                                                    onclick="bukaModalEdit({{ json_encode($item) }})" title="Edit Layanan">
                                                    <i class="ph-bold ph-pencil-simple text-sm"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('layanan-cepat.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan \'{{ $item->nama_layanan }}\'?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm radius-6 px-8 py-4" title="Hapus Layanan">
                                                        <i class="ph-bold ph-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-32 text-secondary-light">
                                            <i class="ph-bold ph-folder-open text-4xl mb-2 d-block"></i>
                                            Belum ada data layanan cepat. Silakan tambahkan melalui form di samping.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MODAL EDIT LAYANAN CEPAT                                                  -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalEditLayanan" tabindex="-1" aria-labelledby="modalEditLayananLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-12 border">
                <div class="modal-header border-bottom py-16 px-20">
                    <h6 class="modal-title text-md fw-bold text-primary-600 d-flex align-items-center gap-2" id="modalEditLayananLabel">
                        <i class="ph-bold ph-pencil-simple text-lg"></i> Edit Layanan Cepat
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formEditLayanan" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-20">

                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-4">Nama Layanan <span class="text-danger-600">*</span></label>
                            <input type="text" name="nama_layanan" id="editNama" class="form-control radius-8 fw-semibold" required>
                        </div>

                        <!-- Ikon Font Awesome Edit + Live Preview -->
                        <div class="p-14 radius-12 bg-primary-50 border border-primary-200 mb-14">
                            <label class="form-label fw-bold text-xs text-primary-600 mb-6 d-block">
                                Ikon / Favicon (Font Awesome Class) <span class="text-danger-600">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-3 mb-10">
                                <div class="icon-preview-card" style="width: 44px; height: 44px; font-size: 19px;">
                                    <i class="fas fa-qrcode" id="iconPreviewEdit"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="text" name="icon" id="editIcon" class="form-control radius-8 fw-semibold font-monospace"
                                        required onkeyup="updateLiveIconEdit(this.value)">
                                </div>
                            </div>

                            <span class="d-block text-2xs fw-bold text-secondary-light mb-4">KLIK IKON REKOMENDASI:</span>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($iconRekomendasi as $icoClass => $icoLabel)
                                    <button type="button" class="icon-palette-btn" style="width: 34px; height: 34px; font-size: 14px;"
                                        title="{{ $icoLabel }}" onclick="pilihIconEdit('{{ $icoClass }}')">
                                        <i class="{{ $icoClass }}"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-4">Link Isian / URL Tujuan <span class="text-danger-600">*</span></label>
                            <input type="text" name="link_url" id="editLinkUrl" class="form-control radius-8 font-monospace text-xs" required>
                        </div>

                        <div class="row g-2 mb-14">
                            <div class="col-6">
                                <label class="form-label fw-bold text-xs mb-4">Buka Di Tab <span class="text-danger-600">*</span></label>
                                <select name="target" id="editTarget" class="form-select radius-8 fw-semibold">
                                    <option value="_blank">Tab Baru (_blank)</option>
                                    <option value="_self">Halaman Sama (_self)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-xs mb-4">Nomor Urut</label>
                                <input type="number" name="urutan" id="editUrutan" class="form-control radius-8 fw-bold" min="1">
                            </div>
                        </div>

                        <div class="p-12 radius-10 bg-light border d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fw-bold text-xs text-dark d-block" for="editIsActive">
                                    Status Aktif
                                </label>
                                <span class="text-2xs text-secondary-light">Tampilkan layanan ini di beranda</span>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="editIsActive" name="is_active" value="1"
                                    style="width: 2.4em; height: 1.3em;">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer border-top py-12 px-20">
                        <button type="button" class="btn btn-outline-secondary radius-8 px-16 py-8 text-xs fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-md"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateLiveIconAdd(val) {
            var iconElem = document.getElementById('iconPreviewAdd');
            if (iconElem) {
                iconElem.className = val.trim() ? val.trim() : 'fas fa-question';
            }
        }

        function pilihIconAdd(iconClass) {
            document.getElementById('inputIconAdd').value = iconClass;
            updateLiveIconAdd(iconClass);
        }

        function updateLiveIconEdit(val) {
            var iconElem = document.getElementById('iconPreviewEdit');
            if (iconElem) {
                iconElem.className = val.trim() ? val.trim() : 'fas fa-question';
            }
        }

        function pilihIconEdit(iconClass) {
            document.getElementById('editIcon').value = iconClass;
            updateLiveIconEdit(iconClass);
        }

        function bukaModalEdit(item) {
            var form = document.getElementById('formEditLayanan');
            form.action = '{{ url("layanan-cepat-admin") }}/' + item.id;

            document.getElementById('editNama').value = item.nama_layanan || '';
            document.getElementById('editIcon').value = item.icon || 'fas fa-link';
            updateLiveIconEdit(item.icon || 'fas fa-link');

            document.getElementById('editLinkUrl').value = item.link_url || '';
            document.getElementById('editTarget').value = item.target || '_blank';
            document.getElementById('editUrutan').value = item.urutan || 1;
            document.getElementById('editIsActive').checked = (item.is_active == 1);

            var modal = new bootstrap.Modal(document.getElementById('modalEditLayanan'));
            modal.show();
        }
    </script>

@endsection
