@extends('layouts.admin')

@section('title', 'Manajemen Profil Madrasah - Dashboard MIN 1 Ciamis')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />

    <style>
        .profil-tab-btn {
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 18px;
            border-radius: 50px;
            transition: all 0.2s ease;
        }
        .profil-tab-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
        }
    </style>

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Profil Madrasah (MIN 1 Ciamis)</h6>
            <span class="text-xs text-secondary-light">
                Kelola konten: <b>1. Profil</b>, <b>2. Identitas Resmi</b>, <b>3. Visi, Misi &amp; Motto</b>, <b>4. Lini Masa Sejarah</b>, dan <b>5. Kepala Sekolah dari Masa ke Masa</b>
            </span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Tombol 1-Klik Hubungkan Halaman Khusus ini ke Navbar Menu & Submenu -->
            <button type="button"
                    onclick="document.getElementById('formSinkronMenuProfil').submit()"
                    class="btn btn-success-600 radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-link text-md"></i> Pasang ke Menu Navbar
            </button>

            <a href="{{ route('profil-madrasah.public') }}" target="_blank" class="btn btn-outline-primary-600 radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Halaman Publik
            </a>
        </div>
    </div>

    <form id="formSinkronMenuProfil" action="{{ route('profil-madrasah.sinkronMenu') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <form action="{{ route('profil-madrasah.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card radius-12 border shadow-sm">
            <!-- Navigasi Tab -->
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="profil-tab-btn active" onclick="bukaTabProfil('intro', this)">
                        1. Profil &amp; Foto Gedung
                    </button>
                    <button type="button" class="profil-tab-btn" onclick="bukaTabProfil('identitas', this)">
                        2. Identitas Resmi Madrasah
                    </button>
                    <button type="button" class="profil-tab-btn" onclick="bukaTabProfil('visimisi', this)">
                        3. Visi, Misi &amp; Motto
                    </button>
                    <button type="button" class="profil-tab-btn" onclick="bukaTabProfil('sejarah', this)">
                        4. Lini Masa Sejarah &amp; Kepsek
                    </button>
                </div>

                <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-10 text-xs fw-bold shadow-sm">
                    <i class="ph-bold ph-floppy-disk me-1"></i> Simpan Semua Profil
                </button>
            </div>

            <div class="card-body p-24">

                <!-- ===================================================================== -->
                <!-- TAB 1: PROFIL & FOTO GEDUNG                                           -->
                <!-- ===================================================================== -->
                <div class="pane-profil" id="pane-profil-intro">
                    <div class="row gy-4">
                        <div class="col-lg-7">
                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-4">Judul Utama Halaman Profil <span class="text-danger-600">*</span></label>
                                <input type="text" name="judul_hero" class="form-control radius-8 fw-bold" value="{{ old('judul_hero', $profil->judul_hero) }}" required>
                            </div>
                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-4">Sub-Judul / Tagline Singkat</label>
                                <input type="text" name="subjudul_hero" class="form-control radius-8 fw-semibold" value="{{ old('subjudul_hero', $profil->subjudul_hero) }}">
                            </div>
                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-4">Paragraf Profil 1</label>
                                <textarea name="deskripsi_1" rows="3" class="form-control radius-8 text-sm">{{ old('deskripsi_1', $profil->deskripsi_1) }}</textarea>
                            </div>
                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-4">Paragraf Profil 2</label>
                                <textarea name="deskripsi_2" rows="3" class="form-control radius-8 text-sm">{{ old('deskripsi_2', $profil->deskripsi_2) }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold text-xs mb-4">Paragraf Profil 3 (Opsional)</label>
                                <textarea name="deskripsi_3" rows="3" class="form-control radius-8 text-sm">{{ old('deskripsi_3', $profil->deskripsi_3) }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="p-16 radius-12 bg-neutral-50 border">
                                <label class="form-label fw-bold text-xs mb-8 d-block">Foto Gedung / Kampus Madrasah</label>
                                <img id="previewFotoGedung"
                                     src="{{ $profil->foto_gedung_url }}"
                                     alt="Foto Gedung"
                                     class="w-100 radius-12 border bg-white mb-10 shadow-sm"
                                     style="height: 240px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                                <input type="file"
                                       name="foto_gedung"
                                       class="form-control form-control-sm radius-8 mb-12"
                                       accept="image/*"
                                       onchange="pratinjauGambarProfil(this, 'previewFotoGedung')">

                                <label class="form-label fw-bold text-xs mb-4">Keterangan di Bawah Foto (Caption)</label>
                                <textarea name="caption_foto" rows="3" class="form-control radius-8 text-xs">{{ old('caption_foto', $profil->caption_foto) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================================================================== -->
                <!-- TAB 2: IDENTITAS RESMI MADRASAH                                       -->
                <!-- ===================================================================== -->
                <div class="pane-profil d-none" id="pane-profil-identitas">
                    <div class="d-flex align-items-center justify-content-between mb-16">
                        <div>
                            <h6 class="text-sm fw-bold mb-0">Kartu Identitas Resmi Madrasah</h6>
                            <span class="text-xs text-secondary-light">Informasi seperti NPSN, NSM, Akreditasi, SK Pendirian, Status, dan Alamat</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-success-600 radius-8 text-xs fw-bold" onclick="tambahBarisIdentitas()">
                            + Tambah Identitas
                        </button>
                    </div>

                    <div id="wadahIdentitasList" class="d-flex flex-column gap-2">
                        @foreach (($profil->identitas_list ?? []) as $item)
                            <div class="row g-2 align-items-center p-10 radius-8 bg-neutral-50 border baris-identitas">
                                <div class="col-md-3">
                                    <label class="text-xs text-secondary-light fw-bold">Ikon (FontAwesome)</label>
                                    <input type="text" name="identitas_icon[]" class="form-control form-control-sm radius-8 font-monospace text-xs" value="{{ $item['icon'] ?? 'fas fa-school' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-xs text-secondary-light fw-bold">Judul Data</label>
                                    <input type="text" name="identitas_label[]" class="form-control form-control-sm radius-8 fw-bold" value="{{ $item['label'] ?? '' }}" placeholder="Contoh: NPSN">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-xs text-secondary-light fw-bold">Isi / Nilai</label>
                                    <input type="text" name="identitas_nilai[]" class="form-control form-control-sm radius-8" value="{{ $item['nilai'] ?? '' }}" placeholder="Contoh: 60708447">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger-600 radius-8 mt-3" onclick="this.closest('.baris-identitas').remove()">✕</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ===================================================================== -->
                <!-- TAB 3: VISI, MISI & MOTTO MADRASAH                                    -->
                <!-- ===================================================================== -->
                <div class="pane-profil d-none" id="pane-profil-visimisi">
                    <!-- Visi -->
                    <div class="mb-20 p-16 radius-12 bg-primary-50 border border-primary-200">
                        <label class="form-label fw-bold text-sm text-primary-600 mb-6">A. Rumusan Visi Madrasah</label>
                        <textarea name="visi_teks" rows="3" class="form-control radius-8 fw-bold">{{ old('visi_teks', $profil->visi_teks) }}</textarea>
                    </div>

                    <!-- Misi -->
                    <div class="mb-24">
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <label class="form-label fw-bold text-sm mb-0">B. Butir-Butir Misi Madrasah</label>
                            <button type="button" class="btn btn-sm btn-success-600 radius-8 text-xs fw-bold" onclick="tambahBarisTeks('wadahMisiList', 'misi_item[]', 'Tuliskan poin misi madrasah...')">
                                + Tambah Butir Misi
                            </button>
                        </div>
                        <div id="wadahMisiList" class="d-flex flex-column gap-2">
                            @foreach (($profil->misi_list ?? []) as $misi)
                                <div class="d-flex gap-2 align-items-start baris-teks">
                                    <textarea name="misi_item[]" rows="2" class="form-control form-control-sm radius-8">{{ $misi }}</textarea>
                                    <button type="button" class="btn btn-sm btn-outline-danger-600 radius-8" onclick="this.closest('.baris-teks').remove()">✕</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Motto Madrasah -->
                    <div class="p-16 radius-12 bg-warning-focus border border-warning">
                        <label class="form-label fw-bold text-sm text-warning-main mb-6">C. Motto Madrasah</label>
                        <input type="text"
                               name="motto_teks"
                               class="form-control radius-8 fw-bold mb-10"
                               value="{{ old('motto_teks', $profil->motto_teks) }}"
                               placeholder="Contoh: Berakhlak Mulia, Unggul Prestasi, Berwawasan Lingkungan">

                        <label class="form-label fw-bold text-xs text-secondary-light mb-4">Makna / Uraian Singkat Motto</label>
                        <textarea name="motto_uraian"
                                  rows="2"
                                  class="form-control radius-8 text-sm"
                                  placeholder="Penjelasan makna motto madrasah...">{{ old('motto_uraian', $profil->motto_uraian) }}</textarea>
                    </div>
                </div>

                <!-- ===================================================================== -->
                <!-- TAB 4: LINI MASA SEJARAH & KEPALA SEKOLAH DARI MASA KE MASA           -->
                <!-- ===================================================================== -->
                <div class="pane-profil d-none" id="pane-profil-sejarah">
                    <!-- Bagian A: Lini Masa Sejarah -->
                    <div class="d-flex align-items-center justify-content-between mb-12">
                        <div>
                            <h6 class="text-sm fw-bold mb-0">A. Lini Masa Sejarah Madrasah</h6>
                            <span class="text-xs text-secondary-light">Tonggak perjalanan dan perkembangan MIN 1 Ciamis dari awal berdiri hingga kini</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-success-600 radius-8 text-xs fw-bold" onclick="tambahBarisSejarah()">
                            + Tambah Lini Masa Sejarah
                        </button>
                    </div>

                    <div id="wadahSejarahList" class="d-flex flex-column gap-2 mb-28">
                        @foreach (($profil->sejarah_list ?? []) as $sej)
                            <div class="row g-2 align-items-start p-12 radius-8 bg-neutral-50 border baris-sejarah">
                                <div class="col-md-3">
                                    <label class="text-xs text-secondary-light fw-bold">Tahun / Era</label>
                                    <input type="text" name="sejarah_tahun[]" class="form-control form-control-sm radius-8 fw-bold" value="{{ $sej['tahun'] ?? '' }}" placeholder="Contoh: 1995">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-xs text-secondary-light fw-bold">Judul Peristiwa</label>
                                    <input type="text" name="sejarah_judul[]" class="form-control form-control-sm radius-8 fw-bold" value="{{ $sej['judul'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="text-xs text-secondary-light fw-bold">Uraian Singkat</label>
                                    <textarea name="sejarah_uraian[]" rows="2" class="form-control form-control-sm radius-8 text-xs">{{ $sej['uraian'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger-600 radius-8 mt-3" onclick="this.closest('.baris-sejarah').remove()">✕</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-20">

                    <!-- Bagian B: Kepala Sekolah dari Masa ke Masa -->
                    <div class="d-flex align-items-center justify-content-between mb-12">
                        <div>
                            <h6 class="text-sm fw-bold mb-0">B. Kepala Sekolah / Kepala Madrasah dari Masa ke Masa</h6>
                            <span class="text-xs text-secondary-light">Kepala madrasah terdahulu otomatis tampil hitam-putih klasik, sedangkan yang menjabat aktif tampil berwarna</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary-600 radius-8 text-xs fw-bold" onclick="tambahBarisKepsek()">
                            + Tambah Kepala Madrasah
                        </button>
                    </div>

                    <div id="wadahKepsekList" class="d-flex flex-column gap-2">
                        @foreach (($profil->kepsek_masa_list ?? []) as $idx => $kp)
                            @php
                                $fotoKpUrl = !empty($kp['foto'])
                                    ? asset('storage/' . $kp['foto'])
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($kp['nama'] ?? 'Kepsek') . '&background=0D6A56&color=fff&size=200';
                            @endphp
                            <div class="row g-2 align-items-center p-12 radius-8 bg-neutral-50 border baris-kepsek">
                                <div class="col-auto">
                                    <img src="{{ $fotoKpUrl }}" class="radius-8 border bg-white" style="width: 52px; height: 62px; object-fit: cover;">
                                    <input type="hidden" name="kepsek_foto_lama[]" value="{{ $kp['foto'] ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="text-xs text-secondary-light fw-bold">Nama &amp; Gelar</label>
                                    <input type="text" name="kepsek_nama[]" class="form-control form-control-sm radius-8 fw-bold" value="{{ $kp['nama'] ?? '' }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-xs text-secondary-light fw-bold">Masa Jabatan</label>
                                    <input type="text" name="kepsek_periode[]" class="form-control form-control-sm radius-8" value="{{ $kp['periode'] ?? '' }}" placeholder="2020 - 2024">
                                </div>
                                <div class="col-md-3">
                                    <label class="text-xs text-secondary-light fw-bold">Upload Foto</label>
                                    <input type="file" name="kepsek_foto[]" class="form-control form-control-sm radius-8" accept="image/*">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="radio" name="kepsek_aktif_index" value="{{ $idx }}" {{ !empty($kp['is_aktif']) ? 'checked' : '' }}>
                                        <label class="form-check-label text-xs fw-bold text-success-600">Menjabat Aktif</label>
                                    </div>
                                </div>
                                <div class="col-auto ms-auto">
                                    <button type="button" class="btn btn-sm btn-outline-danger-600 radius-8" onclick="this.closest('.baris-kepsek').remove()">✕</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        function bukaTabProfil(kode, btnEl) {
            document.querySelectorAll('.profil-tab-btn').forEach(function (b) {
                b.classList.remove('active');
            });
            btnEl.classList.add('active');

            document.querySelectorAll('.pane-profil').forEach(function (p) {
                p.classList.add('d-none');
            });
            var target = document.getElementById('pane-profil-' + kode);
            if (target) {
                target.classList.remove('d-none');
            }
        }

        function pratinjauGambarProfil(input, targetId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById(targetId);
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function tambahBarisIdentitas() {
            var wadah = document.getElementById('wadahIdentitasList');
            var div = document.createElement('div');
            div.className = 'row g-2 align-items-center p-10 radius-8 bg-neutral-50 border baris-identitas';
            div.innerHTML =
                '<div class="col-md-3"><input type="text" name="identitas_icon[]" class="form-control form-control-sm radius-8 font-monospace text-xs" value="fas fa-check-circle"></div>' +
                '<div class="col-md-4"><input type="text" name="identitas_label[]" class="form-control form-control-sm radius-8 fw-bold" placeholder="Judul Data"></div>' +
                '<div class="col-md-4"><input type="text" name="identitas_nilai[]" class="form-control form-control-sm radius-8" placeholder="Isi / Nilai Data"></div>' +
                '<div class="col-md-1 text-end"><button type="button" class="btn btn-sm btn-outline-danger-600 radius-8" onclick="this.closest(\'.baris-identitas\').remove()">✕</button></div>';
            wadah.appendChild(div);
        }

        function tambahBarisTeks(wadahId, inputName, placeholder) {
            var wadah = document.getElementById(wadahId);
            var div = document.createElement('div');
            div.className = 'd-flex gap-2 align-items-start baris-teks';
            div.innerHTML =
                '<textarea name="' + inputName + '" rows="2" class="form-control form-control-sm radius-8" placeholder="' + placeholder + '"></textarea>' +
                '<button type="button" class="btn btn-sm btn-outline-danger-600 radius-8" onclick="this.closest(\'.baris-teks\').remove()">✕</button>';
            wadah.appendChild(div);
        }

        function tambahBarisSejarah() {
            var wadah = document.getElementById('wadahSejarahList');
            var div = document.createElement('div');
            div.className = 'row g-2 align-items-start p-12 radius-8 bg-neutral-50 border baris-sejarah';
            div.innerHTML =
                '<div class="col-md-3"><input type="text" name="sejarah_tahun[]" class="form-control form-control-sm radius-8 fw-bold" placeholder="Tahun / Era"></div>' +
                '<div class="col-md-4"><input type="text" name="sejarah_judul[]" class="form-control form-control-sm radius-8 fw-bold" placeholder="Judul Peristiwa"></div>' +
                '<div class="col-md-4"><textarea name="sejarah_uraian[]" rows="2" class="form-control form-control-sm radius-8 text-xs" placeholder="Uraian singkat..."></textarea></div>' +
                '<div class="col-md-1 text-end"><button type="button" class="btn btn-sm btn-outline-danger-600 radius-8" onclick="this.closest(\'.baris-sejarah\').remove()">✕</button></div>';
            wadah.appendChild(div);
        }

        function tambahBarisKepsek() {
            var wadah = document.getElementById('wadahKepsekList');
            var idxBaru = wadah.querySelectorAll('.baris-kepsek').length;
            var div = document.createElement('div');
            div.className = 'row g-2 align-items-center p-12 radius-8 bg-neutral-50 border baris-kepsek';
            div.innerHTML =
                '<div class="col-auto"><span class="badge bg-secondary">Baru</span><input type="hidden" name="kepsek_foto_lama[]" value=""></div>' +
                '<div class="col-md-3"><input type="text" name="kepsek_nama[]" class="form-control form-control-sm radius-8 fw-bold" placeholder="Nama Kepala Madrasah" required></div>' +
                '<div class="col-md-2"><input type="text" name="kepsek_periode[]" class="form-control form-control-sm radius-8" placeholder="2020 - 2024"></div>' +
                '<div class="col-md-3"><input type="file" name="kepsek_foto[]" class="form-control form-control-sm radius-8" accept="image/*"></div>' +
                '<div class="col-md-2"><div class="form-check"><input class="form-check-input" type="radio" name="kepsek_aktif_index" value="' + idxBaru + '"><label class="form-check-label text-xs fw-bold text-success-600">Menjabat Aktif</label></div></div>' +
                '<div class="col-auto ms-auto"><button type="button" class="btn btn-sm btn-outline-danger-600 radius-8" onclick="this.closest(\'.baris-kepsek\').remove()">✕</button></div>';
            wadah.appendChild(div);
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: @json(session('success')), timer: 2500, showConfirmButton: false });
            });
        </script>
    @endif

@endsection