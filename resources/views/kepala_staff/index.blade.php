@extends('layouts.admin')

@section('title', 'Manajemen Kepala Madrasah & Staff Manajemen - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Manajemen Kepala Madrasah &amp; Staff Manajemen</h6>
            <span class="text-xs text-secondary-light">
                Kelola <b>Foto &amp; Sambutan Kepala Madrasah</b> (Bagian Atas) serta <b>Foto, Nama, Jabatan &amp; Bidang Staff Manajemen</b> (Bagian Bawah)
            </span>
        </div>
        <a href="{{ route('kepala-staff.public') }}" target="_blank" class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
            <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Halaman Publik
        </a>
    </div>

    <form action="{{ route('kepala-staff.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <!-- ========================================================================= -->
            <!-- KOLOM KIRI (5 KOLOM): FOTO & SAMBUTAN KEPALA MADRASAH                     -->
            <!-- ========================================================================= -->
            <div class="col-lg-5">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-20 d-flex align-items-center justify-content-between">
                        <h6 class="text-sm fw-bold mb-0 text-primary-600">👔 1. Kepala Madrasah &amp; Sambutan</h6>
                        <button type="button" class="btn btn-warning btn-sm radius-8 fw-bold text-xs px-10 py-4" onclick="buatSambutanAiOtomatis()">
                            ⚡ Draf AI
                        </button>
                    </div>

                    <div class="card-body p-20">
                        <!-- Preview & Upload Foto Kepala Madrasah -->
                        <div class="p-12 radius-12 bg-neutral-50 border text-center mb-16">
                            <img id="previewFotoKepsekStaff"
                                 src="{{ $data->foto_kepsek_url }}"
                                 alt="{{ $data->nama_kepsek }}"
                                 class="w-100 radius-12 mb-10 border shadow-sm bg-white"
                                 style="height: 250px; object-fit: cover; object-position: top center;"
                                 onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                            <input type="file"
                                   name="foto_kepsek"
                                   class="form-control form-control-sm radius-8"
                                   accept="image/*"
                                   onchange="pratinjauGambarUmum(this, 'previewFotoKepsekStaff')">
                            <small class="text-xs text-secondary-light d-block mt-4">
                                Foto ini otomatis tersinkron dengan Sambutan di Beranda.
                            </small>
                        </div>

                        <div class="mb-12">
                            <label class="form-label fw-bold text-xs mb-4">Nama Lengkap &amp; Gelar Kepala Madrasah <span class="text-danger-600">*</span></label>
                            <input type="text"
                                   name="nama_kepsek"
                                   id="inputNamaKepsekStaff"
                                   class="form-control form-control-sm radius-8 fw-bold"
                                   value="{{ old('nama_kepsek', $data->nama_kepsek) }}"
                                   required>
                        </div>

                        <div class="row g-2 mb-12">
                            <div class="col-6">
                                <label class="form-label fw-bold text-xs mb-4">Jabatan <span class="text-danger-600">*</span></label>
                                <input type="text"
                                       name="jabatan_kepsek"
                                       class="form-control form-control-sm radius-8 fw-semibold"
                                       value="{{ old('jabatan_kepsek', $data->jabatan_kepsek) }}"
                                       required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-xs mb-4">NIP</label>
                                <input type="text"
                                       name="nip_kepsek"
                                       class="form-control form-control-sm radius-8 font-monospace text-xs"
                                       value="{{ old('nip_kepsek', $data->nip_kepsek) }}">
                            </div>
                        </div>

                        <div class="mb-12">
                            <label class="form-label fw-bold text-xs mb-4">Kalimat Kutipan / Highlight Sambutan <span class="text-danger-600">*</span></label>
                            <input type="text"
                                   name="sambutan_kutipan"
                                   id="inputKutipanStaff"
                                   class="form-control form-control-sm radius-8 fw-bold"
                                   value="{{ old('sambutan_kutipan', $data->sambutan_kutipan) }}"
                                   required>
                        </div>

                        <div class="mb-16">
                            <label class="form-label fw-bold text-xs mb-4">Isi Lengkap Sambutan Kepala Madrasah <span class="text-danger-600">*</span></label>
                            <textarea name="sambutan_isi"
                                      id="inputIsiSambutanStaff"
                                      rows="6"
                                      class="form-control radius-8 text-xs lh-lg"
                                      required>{{ old('sambutan_isi', $data->sambutan_isi) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-12 fw-bold shadow-sm">
                            <i class="ph-bold ph-floppy-disk me-1"></i> Simpan Kepala &amp; Staff Manajemen
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- KOLOM KANAN (7 KOLOM): DAFTAR FOTO & BIDANG STAFF MANAJEMEN               -->
            <!-- ========================================================================= -->
            <div class="col-lg-7">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h6 class="text-sm fw-bold mb-0 text-primary-600">👥 2. Jajaran Staff Manajemen &amp; Wakil Kepala</h6>
                            <span class="text-xs text-secondary-light">
                                Unggah foto, nama, jabatan, dan bidang tugas (Kurikulum, Kesiswaan, Sarpras, Humas, Tata Usaha, dll.)
                            </span>
                        </div>
                        <button type="button" class="btn btn-sm btn-success-600 radius-8 text-xs fw-bold px-14 py-6" onclick="tambahKartuStaff()">
                            + Tambah Staff Manajemen
                        </button>
                    </div>

                    <div class="card-body p-20">
                        <div id="wadahStaffManajemen" class="d-flex flex-column gap-3">
                            @foreach (($data->tim_manajemen_list ?? []) as $idx => $tm)
                                @php
                                    $fotoTmUrl = !empty($tm['foto'])
                                        ? asset('storage/' . $tm['foto'])
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($tm['nama'] ?? 'Staff') . '&background=1B9C85&color=fff&size=240';
                                @endphp
                                <div class="p-14 radius-12 bg-neutral-50 border item-staff-card">
                                    <div class="row g-3 align-items-center">
                                        <!-- Preview Foto Staff -->
                                        <div class="col-md-3 text-center">
                                            <img id="prevStaffImg{{ $idx }}"
                                                 src="{{ $fotoTmUrl }}"
                                                 alt="{{ $tm['nama'] ?? '' }}"
                                                 class="radius-12 border bg-white shadow-sm mb-6"
                                                 style="width: 100%; max-width: 110px; height: 130px; object-fit: cover; object-position: top center;">
                                            <input type="hidden" name="tim_foto_lama[]" value="{{ $tm['foto'] ?? '' }}">
                                            <input type="file"
                                                   name="tim_foto[]"
                                                   class="form-control form-control-sm radius-8 text-xs"
                                                   accept="image/*"
                                                   onchange="pratinjauGambarUmum(this, 'prevStaffImg{{ $idx }}')">
                                        </div>

                                        <!-- Input Nama, Jabatan, Bidang & NIP -->
                                        <div class="col-md-9">
                                            <div class="d-flex justify-content-between align-items-center mb-8">
                                                <span class="badge bg-primary-600 text-white radius-8 text-xs px-10 py-4">
                                                    Anggota Manajemen #{{ $idx + 1 }}
                                                </span>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger-600 radius-8 px-10 py-2 text-xs fw-bold"
                                                        onclick="this.closest('.item-staff-card').remove()">
                                                    ✕ Hapus
                                                </button>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-7">
                                                    <label class="text-xs text-secondary-light fw-bold d-block mb-1">Nama Lengkap &amp; Gelar <span class="text-danger-600">*</span></label>
                                                    <input type="text"
                                                           name="tim_nama[]"
                                                           class="form-control form-control-sm radius-8 fw-bold"
                                                           value="{{ $tm['nama'] ?? '' }}"
                                                           placeholder="Contoh: H. Asep Nurjaman, S.Pd."
                                                           required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="text-xs text-secondary-light fw-bold d-block mb-1">Label Bidang (Badge Atas)</label>
                                                    <input type="text"
                                                           name="tim_bidang[]"
                                                           class="form-control form-control-sm radius-8 fw-semibold text-primary-600"
                                                           value="{{ $tm['bidang'] ?? '' }}"
                                                           placeholder="Contoh: Kurikulum / Kesiswaan">
                                                </div>
                                                <div class="col-md-7">
                                                    <label class="text-xs text-secondary-light fw-bold d-block mb-1">Jabatan Lengkap</label>
                                                    <input type="text"
                                                           name="tim_jabatan[]"
                                                           class="form-control form-control-sm radius-8"
                                                           value="{{ $tm['jabatan'] ?? '' }}"
                                                           placeholder="Contoh: Waka Bidang Kurikulum">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="text-xs text-secondary-light fw-bold d-block mb-1">NIP (Opsional)</label>
                                                    <input type="text"
                                                           name="tim_nip[]"
                                                           class="form-control form-control-sm radius-8 font-monospace text-xs"
                                                           value="{{ $tm['nip'] ?? '' }}"
                                                           placeholder="1985xxxx...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        function pratinjauGambarUmum(input, targetImgId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById(targetImgId);
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function tambahKartuStaff() {
            var wadah = document.getElementById('wadahStaffManajemen');
            var uniqueId = 'prevStaffNew' + Date.now();
            var nomor = wadah.querySelectorAll('.item-staff-card').length + 1;

            var div = document.createElement('div');
            div.className = 'p-14 radius-12 bg-neutral-50 border item-staff-card';
            div.innerHTML =
                '<div class="row g-3 align-items-center">' +
                    '<div class="col-md-3 text-center">' +
                        '<img id="' + uniqueId + '" src="https://ui-avatars.com/api/?name=Staff+Baru&background=1B9C85&color=fff&size=240" class="radius-12 border bg-white shadow-sm mb-6" style="width:100%;max-width:110px;height:130px;object-fit:cover;">' +
                        '<input type="hidden" name="tim_foto_lama[]" value="">' +
                        '<input type="file" name="tim_foto[]" class="form-control form-control-sm radius-8 text-xs" accept="image/*" onchange="pratinjauGambarUmum(this, \'' + uniqueId + '\')">' +
                    '</div>' +
                    '<div class="col-md-9">' +
                        '<div class="d-flex justify-content-between align-items-center mb-8">' +
                            '<span class="badge bg-success-600 text-white radius-8 text-xs px-10 py-4">Anggota Manajemen Baru #' + nomor + '</span>' +
                            '<button type="button" class="btn btn-sm btn-outline-danger-600 radius-8 px-10 py-2 text-xs fw-bold" onclick="this.closest(\'.item-staff-card\').remove()">✕ Hapus</button>' +
                        '</div>' +
                        '<div class="row g-2">' +
                            '<div class="col-md-7">' +
                                '<label class="text-xs text-secondary-light fw-bold d-block mb-1">Nama Lengkap &amp; Gelar <span class="text-danger-600">*</span></label>' +
                                '<input type="text" name="tim_nama[]" class="form-control form-control-sm radius-8 fw-bold" placeholder="Nama Lengkap & Gelar" required>' +
                            '</div>' +
                            '<div class="col-md-5">' +
                                '<label class="text-xs text-secondary-light fw-bold d-block mb-1">Label Bidang (Badge Atas)</label>' +
                                '<input type="text" name="tim_bidang[]" class="form-control form-control-sm radius-8 fw-semibold text-primary-600" placeholder="Kurikulum / Kesiswaan">' +
                            '</div>' +
                            '<div class="col-md-7">' +
                                '<label class="text-xs text-secondary-light fw-bold d-block mb-1">Jabatan Lengkap</label>' +
                                '<input type="text" name="tim_jabatan[]" class="form-control form-control-sm radius-8" placeholder="Waka Bidang...">' +
                            '</div>' +
                            '<div class="col-md-5">' +
                                '<label class="text-xs text-secondary-light fw-bold d-block mb-1">NIP (Opsional)</label>' +
                                '<input type="text" name="tim_nip[]" class="form-control form-control-sm radius-8 font-monospace text-xs" placeholder="-">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            wadah.appendChild(div);
        }

        function buatSambutanAiOtomatis() {
            var namaKepsek = document.getElementById('inputNamaKepsekStaff').value;
            var isiSekarang = document.getElementById('inputIsiSambutanStaff').value;

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
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status === 'success') {
                    document.getElementById('inputKutipanStaff').value     = res.kutipan;
                    document.getElementById('inputIsiSambutanStaff').value = res.isi;
                    Swal.fire({
                        icon: 'success',
                        title: 'Sambutan Disusun!',
                        text: 'Draf sambutan Kepala Madrasah berhasil diperbarui.',
                        timer: 1800,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: @json(session('success')), timer: 2200, showConfirmButton: false });
            });
        </script>
    @endif

@endsection