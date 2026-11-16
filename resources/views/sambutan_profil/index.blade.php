@extends('layouts.admin')

@section('title', 'Sambutan Kepala Madrasah & Profil Terpadu - MIN 1 Ciamis')

@section('content')

    <!-- Font Awesome 6.5.1 agar Preview Ikon di Dashboard Persis dengan Halaman Depan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .studio-tab-btn {
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 18px;
            border-radius: 50px;
            transition: all 0.2s ease;
        }

        .studio-tab-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.28);
        }

        .icon-preview-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .ai-extract-banner {
            background: linear-gradient(135deg, #0f172a 0%, #14532d 100%);
            border-radius: 12px;
            padding: 14px 18px;
            color: #ffffff;
        }
    </style>

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Terpadu: Sambutan Kepala Madrasah, Profil, Visi, Misi &amp; Motto</h6>
            <span class="text-xs text-secondary-light">
                Satu pintu pengaturan untuk mengubah Foto Kepala Madrasah, isi sambutan, serta 4 poin keunggulan beserta
                ikonnya
            </span>
        </div>
        <a href="{{ url('/') }}#profil" target="_blank"
            class="btn btn-outline-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Hasil di Halaman Depan
        </a>
    </div>

    <form action="{{ route('sambutan-profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <!-- ========================================================================= -->
            <!-- KOLOM KIRI (4 KOLOM): FOTO & IDENTITAS KEPALA MADRASAH                    -->
            <!-- ========================================================================= -->
            <div class="col-lg-4">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-20">
                        <h6 class="text-sm fw-bold mb-0 text-primary-600">📸 Foto &amp; Identitas Kepala Madrasah</h6>
                    </div>
                    <div class="card-body p-20">

                        <div class="p-12 radius-12 bg-neutral-50 border text-center mb-16">
                            <img id="previewFotoKepsek" src="{{ $data->foto_kepsek_url }}" alt="{{ $data->nama_kepsek }}"
                                class="w-100 radius-12 mb-10 border shadow-sm"
                                style="height: 290px; object-fit: cover; object-position: top center;"
                                onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                            <input type="file" name="foto_kepsek" class="form-control form-control-sm radius-8"
                                accept="image/*" onchange="pratinjauFotoKepsek(this)">
                            <small class="text-xs text-secondary-light d-block mt-6">
                                Format JPG/PNG/WEBP (Maks. 4 MB). Kosongkan jika tidak ingin mengganti foto.
                            </small>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-4">Nama Lengkap &amp; Gelar Kepala Madrasah <span
                                    class="text-danger-600">*</span></label>
                            <input type="text" name="nama_kepsek" id="inputNamaKepsek"
                                class="form-control radius-8 fw-bold" value="{{ old('nama_kepsek', $data->nama_kepsek) }}"
                                required>
                        </div>

                        <div class="mb-14">
                            <label class="form-label fw-bold text-xs mb-4">Jabatan (Tampil di Badge Foto) <span
                                    class="text-danger-600">*</span></label>
                            <input type="text" name="jabatan_kepsek" class="form-control radius-8 fw-semibold"
                                value="{{ old('jabatan_kepsek', $data->jabatan_kepsek) }}" required>
                        </div>

                        <div class="mb-20">
                            <label class="form-label fw-bold text-xs mb-4">NIP Kepala Madrasah</label>
                            <input type="text" name="nip_kepsek" class="form-control radius-8 font-monospace text-xs"
                                value="{{ old('nip_kepsek', $data->nip_kepsek) }}">
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-12 fw-bold shadow-sm">
                            <i class="ph-bold ph-floppy-disk me-1"></i> Simpan Semua Perubahan
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- KOLOM KANAN (8 KOLOM): 5 TAB KONTEN (SAMBUTAN, PROFIL, VISI, MISI, MOTTO) -->
            <!-- ========================================================================= -->
            <div class="col-lg-8">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="d-flex flex-wrap gap-2" id="navTabStudio">
                            <button type="button" class="studio-tab-btn active" onclick="bukaTabStudio('sambutan', this)">
                                1. Sambutan Kepala
                            </button>
                            <button type="button" class="studio-tab-btn" onclick="bukaTabStudio('profil', this)">
                                2. Profil &amp; 4 Poin
                            </button>
                            <button type="button" class="studio-tab-btn" onclick="bukaTabStudio('visi', this)">
                                3. Visi Madrasah
                            </button>
                            <button type="button" class="studio-tab-btn" onclick="bukaTabStudio('misi', this)">
                                4. Misi &amp; 4 Poin
                            </button>
                            <button type="button" class="studio-tab-btn" onclick="bukaTabStudio('motto', this)">
                                5. Motto &amp; Prestasi
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-24">

                        <!-- ================================================================= -->
                        <!-- TAB 1: SAMBUTAN KEPALA MADRASAH                                   -->
                        <!-- ================================================================= -->
                        <div class="pane-studio" id="pane-studio-sambutan">
                            <div
                                class="ai-extract-banner d-flex flex-wrap align-items-center justify-content-between gap-2 mb-20">
                                <div>
                                    <div class="fw-bold text-sm">⚡ Asisten Smart AI Penyusun Sambutan</div>
                                    <span class="text-xs opacity-75">Susun draf kalimat sambutan Kepala Madrasah yang hangat
                                        dan inspiratif dalam 1 klik</span>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm radius-8 fw-bold text-xs px-14 py-8"
                                    onclick="generateSambutanAi()">
                                    ⚡ Buat Draf Sambutan Otomatis
                                </button>
                            </div>

                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Kalimat Sorotan / Kutipan Sambutan <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="sambutan_kutipan" id="inputSambutanKutipan"
                                    class="form-control radius-8 fw-bold"
                                    value="{{ old('sambutan_kutipan', $data->sambutan_kutipan) }}" required>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold text-sm mb-6">Isi Lengkap Sambutan Kepala Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <textarea name="sambutan_isi" id="inputSambutanIsi" rows="7" class="form-control radius-8 text-sm lh-lg"
                                    required>{{ old('sambutan_isi', $data->sambutan_isi) }}</textarea>
                            </div>
                        </div>

                        <!-- ================================================================= -->
                        <!-- TAB 2: PROFIL MADRASAH + 4 POIN KEUNGGULAN & IKON                 -->
                        <!-- ================================================================= -->
                        <div class="pane-studio d-none" id="pane-studio-profil">
                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Kutipan / Slogan Profil Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="profil_kutipan" class="form-control radius-8 fw-bold"
                                    value="{{ old('profil_kutipan', $data->profil_kutipan) }}" required>
                            </div>

                            <div class="mb-20">
                                <label class="form-label fw-bold text-sm mb-6">Deskripsi Profil Singkat MIN 1 Ciamis <span
                                        class="text-danger-600">*</span></label>
                                <textarea name="profil_isi" id="inputProfilIsi" rows="4" class="form-control radius-8 text-sm" required>{{ old('profil_isi', $data->profil_isi) }}</textarea>
                            </div>

                            <!-- Kotak Smart AI Ekstraktor 4 Poin Profil -->
                            <div
                                class="ai-extract-banner d-flex flex-wrap align-items-center justify-content-between gap-2 mb-16">
                                <div>
                                    <div class="fw-bold text-sm">⚡ Ekstrak Deskripsi Profil Jadi 4 Poin + Ikon Otomatis
                                    </div>
                                    <span class="text-xs opacity-75">Smart AI akan membaca deskripsi di atas, meringkas 4
                                        poin utama, dan memilihkan ikonnya</span>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm radius-8 fw-bold text-xs px-14 py-8"
                                    onclick="ekstrakEmpatPoinAi('profil')">
                                    ⚡ Generate 4 Poin &amp; Ikon
                                </button>
                            </div>

                            <label class="form-label fw-bold text-sm mb-10">4 Poin Keunggulan Profil &amp; Pilihan
                                Ikon:</label>
                            @php
                                $poinProfilList = $data->profil_poin ?? [];
                            @endphp
                            @for ($i = 0; $i < 4; $i++)
                                @php
                                    $ikonAktif = $poinProfilList[$i]['icon'] ?? 'fas fa-check-circle';
                                    $teksAktif = $poinProfilList[$i]['text'] ?? '';
                                @endphp
                                <div class="row g-2 align-items-center mb-12 p-10 radius-12 bg-neutral-50 border">
                                    <div class="col-auto">
                                        <div class="icon-preview-box" id="prev_profil_icon_{{ $i }}">
                                            <i class="{{ $ikonAktif }}"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="text-xs text-secondary-light fw-bold d-block mb-1">Pilih Ikon Poin
                                            #{{ $i + 1 }}</label>
                                        <select name="profil_icon[]" id="select_profil_icon_{{ $i }}"
                                            class="form-select form-select-sm radius-8 fw-semibold"
                                            onchange="ubahPreviewIkon(this.value, 'prev_profil_icon_{{ $i }}')">
                                            @foreach ($daftarIkon as $kodeIkon => $namaIkon)
                                                <option value="{{ $kodeIkon }}"
                                                    {{ $ikonAktif === $kodeIkon ? 'selected' : '' }}>
                                                    {{ $namaIkon }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-xs text-secondary-light fw-bold d-block mb-1">Teks Poin
                                            #{{ $i + 1 }}</label>
                                        <input type="text" name="profil_text[]"
                                            id="input_profil_text_{{ $i }}"
                                            class="form-control form-control-sm radius-8 fw-bold"
                                            value="{{ $teksAktif }}" placeholder="Contoh: Akreditasi A (Unggul)"
                                            required>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <!-- ================================================================= -->
                        <!-- TAB 3: VISI MADRASAH                                              -->
                        <!-- ================================================================= -->
                        <div class="pane-studio d-none" id="pane-studio-visi">
                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Rumusan Utama Visi Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <textarea name="visi_kutipan" rows="3" class="form-control radius-8 fw-bold" required>{{ old('visi_kutipan', $data->visi_kutipan) }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold text-sm mb-6">Penjelasan / Uraian Visi Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <textarea name="visi_isi" rows="5" class="form-control radius-8 text-sm" required>{{ old('visi_isi', $data->visi_isi) }}</textarea>
                            </div>
                        </div>

                        <!-- ================================================================= -->
                        <!-- TAB 4: MISI MADRASAH + 4 POIN MISI & IKON                         -->
                        <!-- ================================================================= -->
                        <div class="pane-studio d-none" id="pane-studio-misi">
                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Intisari / Kutipan Misi Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="misi_kutipan" class="form-control radius-8 fw-bold"
                                    value="{{ old('misi_kutipan', $data->misi_kutipan) }}" required>
                            </div>

                            <div class="mb-20">
                                <label class="form-label fw-bold text-sm mb-6">Uraian Deskripsi Misi (Bahan Ekstraksi 4
                                    Poin AI) <span class="text-danger-600">*</span></label>
                                <textarea name="misi_isi" id="inputMisiIsi" rows="3" class="form-control radius-8 text-sm"
                                    placeholder="Tuliskan kalimat misi madrasah di sini, lalu klik tombol Generate 4 Poin di bawah..." required>{{ old('misi_isi', $data->misi_isi) }}</textarea>
                            </div>

                            <!-- Kotak Smart AI Ekstraktor 4 Poin Misi -->
                            <div
                                class="ai-extract-banner d-flex flex-wrap align-items-center justify-content-between gap-2 mb-16">
                                <div>
                                    <div class="fw-bold text-sm">⚡ Ekstrak Uraian Misi Jadi 4 Poin + Ikon Otomatis</div>
                                    <span class="text-xs opacity-75">Ubah kalimat misi di atas menjadi 4 pilar misi lengkap
                                        dengan ikonnya</span>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm radius-8 fw-bold text-xs px-14 py-8"
                                    onclick="ekstrakEmpatPoinAi('misi')">
                                    ⚡ Generate 4 Poin &amp; Ikon
                                </button>
                            </div>

                            <label class="form-label fw-bold text-sm mb-10">4 Poin Misi Utama &amp; Pilihan Ikon:</label>
                            @php
                                $poinMisiList = $data->misi_poin ?? [];
                            @endphp
                            @for ($i = 0; $i < 4; $i++)
                                @php
                                    $ikonMisiAktif = $poinMisiList[$i]['icon'] ?? 'fas fa-star';
                                    $teksMisiAktif = $poinMisiList[$i]['text'] ?? '';
                                @endphp
                                <div class="row g-2 align-items-center mb-12 p-10 radius-12 bg-neutral-50 border">
                                    <div class="col-auto">
                                        <div class="icon-preview-box" id="prev_misi_icon_{{ $i }}">
                                            <i class="{{ $ikonMisiAktif }}"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="text-xs text-secondary-light fw-bold d-block mb-1">Pilih Ikon Misi
                                            #{{ $i + 1 }}</label>
                                        <select name="misi_icon[]" id="select_misi_icon_{{ $i }}"
                                            class="form-select form-select-sm radius-8 fw-semibold"
                                            onchange="ubahPreviewIkon(this.value, 'prev_misi_icon_{{ $i }}')">
                                            @foreach ($daftarIkon as $kodeIkon => $namaIkon)
                                                <option value="{{ $kodeIkon }}"
                                                    {{ $ikonMisiAktif === $kodeIkon ? 'selected' : '' }}>
                                                    {{ $namaIkon }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-xs text-secondary-light fw-bold d-block mb-1">Teks Poin Misi
                                            #{{ $i + 1 }}</label>
                                        <input type="text" name="misi_text[]"
                                            id="input_misi_text_{{ $i }}"
                                            class="form-control form-control-sm radius-8 fw-bold"
                                            value="{{ $teksMisiAktif }}"
                                            placeholder="Contoh: Pembiasaan Ibadah & Tahfidz" required>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <!-- ================================================================= -->
                        <!-- TAB 5: MOTTO MADRASAH & TOTAL PRESTASI PIALA 3D                   -->
                        <!-- ================================================================= -->
                        <div class="pane-studio d-none" id="pane-studio-motto">
                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Kalimat Motto Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="motto_kutipan" class="form-control radius-8 fw-bold"
                                    value="{{ old('motto_kutipan', $data->motto_kutipan) }}" required>
                            </div>

                            <div class="mb-16">
                                <label class="form-label fw-bold text-sm mb-6">Penjelasan Motto Madrasah <span
                                        class="text-danger-600">*</span></label>
                                <textarea name="motto_isi" rows="4" class="form-control radius-8 text-sm" required>{{ old('motto_isi', $data->motto_isi) }}</textarea>
                            </div>

                            <div class="p-16 radius-12 bg-warning-focus border border-warning">
                                <label class="form-label fw-bold text-sm text-warning-main mb-6">
                                    🏆 Angka Total Prestasi Juara (Tampil di Showcase Piala 3D Tab Motto &amp; Statistik)
                                </label>
                                <input type="number" name="total_prestasi" class="form-control radius-8 fw-bold"
                                    style="max-width: 220px;" value="{{ old('total_prestasi', $data->total_prestasi) }}"
                                    min="0">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        function bukaTabStudio(kode, btnEl) {
            document.querySelectorAll('.studio-tab-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            btnEl.classList.add('active');

            document.querySelectorAll('.pane-studio').forEach(function(p) {
                p.classList.add('d-none');
            });
            var target = document.getElementById('pane-studio-' + kode);
            if (target) {
                target.classList.remove('d-none');
            }
        }

        function pratinjauFotoKepsek(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewFotoKepsek').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function ubahPreviewIkon(kelasIkon, targetBoxId) {
            var box = document.getElementById(targetBoxId);
            if (box) {
                box.innerHTML = '<i class="' + kelasIkon + '"></i>';
            }
        }

        function generateSambutanAi() {
            var namaKepsek = document.getElementById('inputNamaKepsek').value;
            var isiSekarang = document.getElementById('inputSambutanIsi').value;

            fetch("{{ route('sambutan-profil.generateAi') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        tipe: 'sambutan',
                        nama_kepsek: namaKepsek,
                        teks: isiSekarang
                    })
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (res.status === 'success') {
                        document.getElementById('inputSambutanKutipan').value = res.kutipan;
                        document.getElementById('inputSambutanIsi').value = res.isi;
                        Swal.fire({
                            icon: 'success',
                            title: 'Draf Sambutan Siap!',
                            text: 'Kalimat sambutan Kepala Madrasah berhasil disusun.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
        }

        function ekstrakEmpatPoinAi(tipe) {
            var sumberId = (tipe === 'misi') ? 'inputMisiIsi' : 'inputProfilIsi';
            var teksDeskripsi = document.getElementById(sumberId).value.trim();

            if (teksDeskripsi.length < 10) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Deskripsi Masih Kosong',
                    text: 'Tuliskan dulu deskripsi ' + tipe.toUpperCase() +
                        ' di kotak atas agar Smart AI bisa mengekstraknya menjadi 4 poin.'
                });
                return;
            }

            fetch("{{ route('sambutan-profil.generateAi') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        tipe: tipe,
                        teks: teksDeskripsi
                    })
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (res.status === 'success' && Array.isArray(res.poin)) {
                        res.poin.forEach(function(item, idx) {
                            var inputTxt = document.getElementById('input_' + tipe + '_text_' + idx);
                            var selectIco = document.getElementById('select_' + tipe + '_icon_' + idx);
                            if (inputTxt) inputTxt.value = item.text;
                            if (selectIco) {
                                selectIco.value = item.icon;
                                ubahPreviewIkon(item.icon, 'prev_' + tipe + '_icon_' + idx);
                            }
                        });

                        Swal.fire({
                            icon: 'success',
                            title: '4 Poin & Ikon Berhasil Diekstrak!',
                            text: 'Smart AI telah meringkas deskripsi menjadi 4 poin beserta ikon yang sesuai. Jangan lupa klik Simpan Semua Perubahan.',
                            timer: 2400,
                            showConfirmButton: false
                        });
                    }
                });
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    timer: 2200,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

@endsection
