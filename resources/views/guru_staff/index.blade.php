@extends('layouts.admin')

@section('title', 'Penyusun Struktur Guru & Staff - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Pengaturan Halaman Guru &amp; Staff (Susunan Point A, B, C... &amp; Dropdown GTK)</h6>
            <span class="text-xs text-secondary-light">
                Susun kelompok jabatan secara fleksibel (Point A, B, C, dst.) lalu pilih nama guru &amp; tendik langsung dari <b>Database Master GTK</b>
            </span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if (Route::has('gtk.index'))
                <a href="{{ route('gtk.index') }}" class="btn btn-outline-success-600 radius-8 px-14 py-8 text-xs fw-bold d-inline-flex align-items-center gap-1">
                    <i class="ph-bold ph-users text-md"></i> Master Data GTK ({{ $gtks->count() }})
                </a>
            @endif
            <a href="{{ route('guru-staff.public') }}" target="_blank" class="btn btn-primary-600 radius-8 px-16 py-8 text-xs fw-bold d-inline-flex align-items-center gap-2">
                <i class="ph-bold ph-arrow-square-out text-md"></i> Lihat Halaman Publik
            </a>
        </div>
    </div>

    <form action="{{ route('guru-staff.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- ========================================================================= -->
        <!-- BAGIAN 1: PENGATURAN BANNER FOTO BERSAMA & JUDUL HALAMAN                  -->
        <!-- ========================================================================= -->
        <div class="card radius-12 border shadow-sm mb-24">
            <div class="card-header border-bottom bg-base py-14 px-24 d-flex align-items-center justify-content-between">
                <h6 class="text-sm fw-bold mb-0 text-primary-600">📸 1. Banner Foto Bersama Guru &amp; Staff</h6>
                <button type="submit" class="btn btn-primary-600 radius-8 px-18 py-8 text-xs fw-bold shadow-sm">
                    <i class="ph-bold ph-floppy-disk me-1"></i> Simpan Semua Perubahan
                </button>
            </div>
            <div class="card-body p-20">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4 text-center">
                        <img id="previewFotoBersamaGuru"
                             src="{{ $profil->foto_bersama_guru_url }}"
                             alt="Foto Bersama Guru"
                             class="w-100 radius-12 border bg-neutral-50 mb-8"
                             style="height: 155px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                        <input type="file"
                               name="foto_bersama_guru"
                               class="form-control form-control-sm radius-8"
                               accept="image/*"
                               onchange="pratinjauFotoBersama(this)">
                    </div>
                    <div class="col-md-8">
                        <div class="mb-12">
                            <label class="form-label fw-bold text-xs mb-4">Judul Utama Halaman <span class="text-danger-600">*</span></label>
                            <input type="text"
                                   name="judul_guru_staff"
                                   class="form-control radius-8 fw-bold"
                                   value="{{ old('judul_guru_staff', $profil->judul_guru_staff ?? 'Dewan Guru dan Tenaga Kependidikan MIN 1 Ciamis') }}"
                                   required>
                        </div>
                        <div>
                            <label class="form-label fw-bold text-xs mb-4">Sub-Judul / Slogan Halaman</label>
                            <textarea name="subjudul_guru_staff"
                                      rows="2"
                                      class="form-control radius-8 text-xs">{{ old('subjudul_guru_staff', $profil->subjudul_guru_staff ?? 'Mewujudkan generasi Qur\'ani yang unggul, berakhlak mulia, berprestasi, dan berwawasan lingkungan.') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- BAGIAN 2: PENYUSUN POINT (A, B, C...) & TABEL DROPDOWN NAMA GTK           -->
        <!-- ========================================================================= -->
        <div class="card radius-12 border shadow-sm">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h6 class="text-sm fw-bold mb-0 text-success-600">📋 2. Susunan Point Kelompok &amp; Tabel Nama GTK</h6>
                    <span class="text-xs text-secondary-light">
                        Isi <b>Huruf Point (A, B, C...)</b>, <b>Nama Point</b>, lalu tambahkan baris <b>Jabatan &amp; Pilih Nama Guru/Tendik dari Dropdown</b>
                    </span>
                </div>
                <button type="button" class="btn btn-success-600 radius-8 px-16 py-8 text-xs fw-bold" onclick="tambahPointBaru()">
                    + Tambah Point Baru (A, B, C...)
                </button>
            </div>

            <div class="card-body p-24">
                <div id="wadahSemuaPoint" class="d-flex flex-column gap-4">
                    @foreach ($strukturPoints as $pIdx => $pt)
                        <div class="card radius-12 border border-secondary-subtle shadow-none kartu-point" data-point-idx="{{ $pIdx }}">
                            <!-- Header Point: Kolom Point (A/B/C) & Kolom Nama Point -->
                            <div class="card-header bg-neutral-50 border-bottom py-12 px-16">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-2 col-4">
                                        <label class="text-xs fw-bold text-primary-600 d-block mb-1">1. Point</label>
                                        <input type="text"
                                               name="points[{{ $pIdx }}][kode]"
                                               class="form-control form-control-sm radius-8 fw-bold text-center text-uppercase input-kode-point"
                                               value="{{ $pt['kode'] ?? 'A' }}"
                                               maxlength="5"
                                               placeholder="A"
                                               required>
                                    </div>
                                    <div class="col-md-7 col-8">
                                        <label class="text-xs fw-bold text-primary-600 d-block mb-1">2. Nama dari Point (Judul Kelompok)</label>
                                        <input type="text"
                                               name="points[{{ $pIdx }}][nama]"
                                               class="form-control form-control-sm radius-8 fw-bold"
                                               value="{{ $pt['nama'] ?? '' }}"
                                               placeholder="Contoh: Penjamin Mutu Madrasah (PMM)"
                                               required>
                                    </div>
                                    <div class="col-md-3 text-end d-flex justify-content-end gap-1 mt-md-3">
                                        <button type="button"
                                                class="btn btn-sm btn-primary-600 radius-8 px-10 py-6 text-xs fw-bold"
                                                onclick="tambahBarisDalamPoint(this)">
                                            + Baris Guru
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger-600 radius-8 px-10 py-6 text-xs fw-bold"
                                                onclick="this.closest('.kartu-point').remove()"
                                                title="Hapus Seluruh Point Ini">
                                            Hapus Point
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Body Point: 3. Tabel Jabatan & Dropdown Nama Guru/Tendik dari Database -->
                            <div class="card-body p-16">
                                <div class="table-responsive">
                                    <table class="table table-sm bordered-table align-middle mb-0">
                                        <thead>
                                            <tr class="bg-white text-xs text-secondary-light">
                                                <th width="6%" class="text-center">No</th>
                                                <th width="42%">Jabatan / Posisi Tugas</th>
                                                <th width="44%">Nama Guru &amp; Tendik (Database GTK)</th>
                                                <th width="8%" class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-rows-point">
                                            @foreach (($pt['rows'] ?? []) as $rIdx => $row)
                                                <tr class="baris-gtk-point">
                                                    <td class="text-center fw-bold text-xs nomor-baris">{{ $rIdx + 1 }}</td>
                                                    <td>
                                                        <input type="text"
                                                               name="points[{{ $pIdx }}][rows][{{ $rIdx }}][jabatan]"
                                                               class="form-control form-control-sm radius-8 fw-semibold"
                                                               value="{{ $row['jabatan'] ?? '' }}"
                                                               placeholder="Contoh: Koordinator Penjamin Mutu / Wali Kelas 1-A"
                                                               required>
                                                    </td>
                                                    <td>
                                                        <select name="points[{{ $pIdx }}][rows][{{ $rIdx }}][gtk_id]"
                                                                class="form-select form-select-sm radius-8 fw-bold"
                                                                required>
                                                            <option value="">-- Pilih Nama Guru / Tendik --</option>
                                                            @foreach ($gtks as $g)
                                                                @php
                                                                    $jabInfo = $g->jabatan->nama_jabatan ?? '';
                                                                    $golInfo = $g->golongan ?? $g->status_kepegawaian ?? '';
                                                                    $labelOpt = $g->nama_lengkap;
                                                                    if (!empty($jabInfo) or !empty($golInfo)) {
                                                                        $labelOpt .= ' (' . trim($jabInfo . ' ' . $golInfo) . ')';
                                                                    }
                                                                @endphp
                                                                <option value="{{ $g->id }}" {{ (int) ($row['gtk_id'] ?? 0) === $g->id ? 'selected' : '' }}>
                                                                    {{ $labelOpt }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger-600 radius-8 px-8 py-2 text-xs"
                                                                onclick="hapusBarisPoint(this)">
                                                            ✕
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-24 pt-16 border-top d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-success-600 radius-8 px-16 py-10 text-xs fw-bold" onclick="tambahPointBaru()">
                        + Tambah Point Baru (A, B, C...)
                    </button>
                    <button type="submit" class="btn btn-primary-600 radius-8 px-24 py-10 text-sm fw-bold shadow-sm">
                        <i class="ph-bold ph-floppy-disk me-1"></i> Simpan Semua Susunan Point &amp; GTK
                    </button>
                </div>
            </div>
        </div>

    </form>

    <!-- Template Option Dropdown GTK untuk Baris Dinamis Baru -->
    <template id="templateOpsiGtk">
        <option value="">-- Pilih Nama Guru / Tendik --</option>
        @foreach ($gtks as $g)
            @php
                $jabInfo = $g->jabatan->nama_jabatan ?? '';
                $golInfo = $g->golongan ?? $g->status_kepegawaian ?? '';
                $labelOpt = $g->nama_lengkap;
                if (!empty($jabInfo) or !empty($golInfo)) {
                    $labelOpt .= ' (' . trim($jabInfo . ' ' . $golInfo) . ')';
                }
            @endphp
            <option value="{{ $g->id }}">{{ $labelOpt }}</option>
        @endforeach
    </template>

    <script>
        var counterPointGlobal = {{ count($strukturPoints) + 1 }};

        function pratinjauFotoBersama(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById('previewFotoBersamaGuru');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function tebakHurufPointBerikutnya() {
            var inputs = document.querySelectorAll('.input-kode-point');
            if (inputs.length === 0) return 'A';
            var terakhir = inputs[inputs.length - 1].value.trim().toUpperCase();
            if (terakhir.length === 1 && terakhir >= 'A' && terakhir < 'Z') {
                return String.fromCharCode(terakhir.charCodeAt(0) + 1);
            }
            return String.fromCharCode(65 + (inputs.length % 26));
        }

        function tambahPointBaru() {
            var wadah = document.getElementById('wadahSemuaPoint');
            var pIdx  = counterPointGlobal++;
            var huruf = tebakHurufPointBerikutnya();
            var opsiHtml = document.getElementById('templateOpsiGtk').innerHTML;

            var card = document.createElement('div');
            card.className = 'card radius-12 border border-secondary-subtle shadow-none kartu-point';
            card.setAttribute('data-point-idx', pIdx);

            card.innerHTML =
                '<div class="card-header bg-neutral-50 border-bottom py-12 px-16">' +
                    '<div class="row g-2 align-items-center">' +
                        '<div class="col-md-2 col-4">' +
                            '<label class="text-xs fw-bold text-primary-600 d-block mb-1">1. Point</label>' +
                            '<input type="text" name="points[' + pIdx + '][kode]" class="form-control form-control-sm radius-8 fw-bold text-center text-uppercase input-kode-point" value="' + huruf + '" maxlength="5" required>' +
                        '</div>' +
                        '<div class="col-md-7 col-8">' +
                            '<label class="text-xs fw-bold text-primary-600 d-block mb-1">2. Nama dari Point (Judul Kelompok)</label>' +
                            '<input type="text" name="points[' + pIdx + '][nama]" class="form-control form-control-sm radius-8 fw-bold" placeholder="Contoh: Wakil Kepala Madrasah / Koordinator Unit" required>' +
                        '</div>' +
                        '<div class="col-md-3 text-end d-flex justify-content-end gap-1 mt-md-3">' +
                            '<button type="button" class="btn btn-sm btn-primary-600 radius-8 px-10 py-6 text-xs fw-bold" onclick="tambahBarisDalamPoint(this)">+ Baris Guru</button>' +
                            '<button type="button" class="btn btn-sm btn-outline-danger-600 radius-8 px-10 py-6 text-xs fw-bold" onclick="this.closest(\'.kartu-point\').remove()">Hapus Point</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="card-body p-16">' +
                    '<div class="table-responsive">' +
                        '<table class="table table-sm bordered-table align-middle mb-0">' +
                            '<thead>' +
                                '<tr class="bg-white text-xs text-secondary-light">' +
                                    '<th width="6%" class="text-center">No</th>' +
                                    '<th width="42%">Jabatan / Posisi Tugas</th>' +
                                    '<th width="44%">Nama Guru &amp; Tendik (Database GTK)</th>' +
                                    '<th width="8%" class="text-center">Hapus</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody class="tbody-rows-point">' +
                                '<tr class="baris-gtk-point">' +
                                    '<td class="text-center fw-bold text-xs nomor-baris">1</td>' +
                                    '<td><input type="text" name="points[' + pIdx + '][rows][0][jabatan]" class="form-control form-control-sm radius-8 fw-semibold" placeholder="Contoh: Koordinator Bidang..." required></td>' +
                                    '<td><select name="points[' + pIdx + '][rows][0][gtk_id]" class="form-select form-select-sm radius-8 fw-bold" required>' + opsiHtml + '</select></td>' +
                                    '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger-600 radius-8 px-8 py-2 text-xs" onclick="hapusBarisPoint(this)">✕</button></td>' +
                                '</tr>' +
                            '</tbody>' +
                        '</table>' +
                    '</div>' +
                '</div>';

            wadah.appendChild(card);
        }

        function tambahBarisDalamPoint(btnEl) {
            var card  = btnEl.closest('.kartu-point');
            var pIdx  = card.getAttribute('data-point-idx');
            var tbody = card.querySelector('.tbody-rows-point');
            var rIdx  = Date.now() + Math.floor(Math.random() * 100);
            var nomor = tbody.querySelectorAll('.baris-gtk-point').length + 1;
            var opsiHtml = document.getElementById('templateOpsiGtk').innerHTML;

            var tr = document.createElement('tr');
            tr.className = 'baris-gtk-point';
            tr.innerHTML =
                '<td class="text-center fw-bold text-xs nomor-baris">' + nomor + '</td>' +
                '<td><input type="text" name="points[' + pIdx + '][rows][' + rIdx + '][jabatan]" class="form-control form-control-sm radius-8 fw-semibold" placeholder="Jabatan / Tugas..." required></td>' +
                '<td><select name="points[' + pIdx + '][rows][' + rIdx + '][gtk_id]" class="form-select form-select-sm radius-8 fw-bold" required>' + opsiHtml + '</select></td>' +
                '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger-600 radius-8 px-8 py-2 text-xs" onclick="hapusBarisPoint(this)">✕</button></td>';

            tbody.appendChild(tr);
            urutkanNomorBaris(tbody);
        }

        function hapusBarisPoint(btnEl) {
            var tbody = btnEl.closest('.tbody-rows-point');
            btnEl.closest('.baris-gtk-point').remove();
            urutkanNomorBaris(tbody);
        }

        function urutkanNomorBaris(tbody) {
            if (!tbody) return;
            tbody.querySelectorAll('.nomor-baris').forEach(function (td, i) {
                td.textContent = i + 1;
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