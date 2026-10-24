@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')

    @php
        // Menentukan sumber Foto Profil (Ambil dari foto GTK, jika kosong pakai Avatar Inisial Hijau)
        $namaTampil = $gtk->nama_lengkap ?? $user->name;
        $fotoProfil = ($gtk && $gtk->foto) 
            ? asset('storage/' . $gtk->foto) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($namaTampil) . '&background=16a34a&color=fff&bold=true&size=200';
    @endphp

    <!-- Breadcrumb Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Profil Saya</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium text-primary-600">Profil Saya</li>
        </ul>
    </div>

    <div class="row gy-4">
        <!-- KOLOM KIRI: KARTU BIODATA DARI TABEL GTK -->
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <img src="{{ asset('backend/assets/images/user-grid/user-grid-bg1.png') }}" alt="Cover" class="w-100 object-fit-cover">
                <div class="pb-24 ms-16 mb-24 me-16 mt--100">
                    <div class="text-center border border-top-0 border-start-0 border-end-0 pb-16">
                        <img src="{{ $fotoProfil }}" 
                             alt="Foto Profil" 
                             class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                        <h2 class="h6 mb-0 mt-16">{{ $namaTampil }}</h2>
                        <span class="text-secondary-light mb-8 d-block">{{ $user->email }}</span>
                        <span class="bg-success-focus text-success-main px-16 py-4 rounded-pill fw-medium text-sm">
                            {{ $gtk->jabatan->nama_jabatan ?? ucfirst($user->role ?? 'Super Admin') }}
                        </span>
                    </div>

                    <div class="mt-24">
                        <h2 class="h6 text-xl mb-16">Biodata Kepegawaian</h2>
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-35 text-md fw-semibold text-primary-light">Nama Lengkap</span>
                                <span class="w-65 text-secondary-light fw-medium">: {{ $namaTampil }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-35 text-md fw-semibold text-primary-light">NIP/NUPTK/NIK</span>
                                <span class="w-65 text-secondary-light fw-medium">: {{ $gtk->nomor_induk ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-35 text-md fw-semibold text-primary-light">Jenis Kelamin</span>
                                <span class="w-65 text-secondary-light fw-medium">: 
                                    @if(($gtk->jenis_kelamin ?? '') == 'L') Laki-laki @elseif(($gtk->jenis_kelamin ?? '') == 'P') Perempuan @else - @endif
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-35 text-md fw-semibold text-primary-light">No. Telepon</span>
                                <span class="w-65 text-secondary-light fw-medium">: {{ $gtk->no_hp ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-35 text-md fw-semibold text-primary-light">Status GTK</span>
                                <span class="w-65 text-secondary-light fw-medium">: {{ $gtk->status_kepegawaian ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-35 text-md fw-semibold text-primary-light">Jabatan</span>
                                <span class="w-65 text-secondary-light fw-medium">: {{ $gtk->jabatan->nama_jabatan ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1">
                                <span class="w-35 text-md fw-semibold text-primary-light">Posisi / Tugas</span>
                                <span class="w-65 text-secondary-light fw-medium">: {{ $gtk->posisi->nama_posisi ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TAB EDIT DATA DIRI & GANTI PASSWORD -->
        <div class="col-lg-8">
            <div class="card h-100 radius-16">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Biodata Diri
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false">
                                Ganti Password
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">   
                        <!-- TAB 1: FORM EDIT BIODATA GTK -->
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <h2 class="h6 text-md text-primary-light mb-16">Foto Profil GTK</h2>
                                <!-- Upload Image Start -->
                                <div class="mb-24 mt-16">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                            <input type="file" name="foto" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                            <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle cursor-pointer" title="Ubah Foto">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview" style="background-image: url('{{ $fotoProfil }}');">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-secondary-light d-block mt-2">*Foto otomatis diambil dari data GTK. Klik ikon kamera jika ingin memperbarui foto.</span>
                                </div>
                                <!-- Upload Image End -->

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="nama_lengkap" class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap & Gelar <span class="text-danger-600">*</span></label>
                                            <input type="text" name="nama_lengkap" class="form-control radius-8" id="nama_lengkap" value="{{ old('nama_lengkap', $namaTampil) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="nomor_induk" class="form-label fw-semibold text-primary-light text-sm mb-8">NIP / NUPTK / NIK</label>
                                            <input type="text" name="nomor_induk" class="form-control radius-8" id="nomor_induk" value="{{ old('nomor_induk', $gtk->nomor_induk ?? '') }}" placeholder="Kosongkan jika belum ada">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="jenis_kelamin" class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select radius-8">
                                                <option value="L" {{ ($gtk->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                                <option value="P" {{ ($gtk->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="no_hp" class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Telepon / WhatsApp</label>
                                            <input type="text" name="no_hp" class="form-control radius-8" id="no_hp" value="{{ old('no_hp', $gtk->no_hp ?? '') }}" placeholder="Contoh: 081234567890">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email Login <span class="text-danger-600">*</span></label>
                                            <input type="email" name="email" class="form-control radius-8" id="email" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status Kepegawaian <span class="text-xs text-secondary-light fw-normal">(Diatur Admin)</span></label>
                                            <input type="text" class="form-control radius-8 bg-neutral-100" value="{{ $gtk->status_kepegawaian ?? '-' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jabatan <span class="text-xs text-secondary-light fw-normal">(Diatur Admin)</span></label>
                                            <input type="text" class="form-control radius-8 bg-neutral-100" value="{{ $gtk->jabatan->nama_jabatan ?? '-' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Posisi / Penugasan <span class="text-xs text-secondary-light fw-normal">(Diatur Admin)</span></label>
                                            <input type="text" class="form-control radius-8 bg-neutral-100" value="{{ $gtk->posisi->nama_posisi ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-end gap-3 mt-16">
                                    <button type="submit" class="btn btn-primary-600 border border-primary-600 text-md px-40 py-12 radius-8"> 
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: FORM GANTI PASSWORD -->
                        <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab" tabindex="0">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-20">
                                    <label for="current-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password Saat Ini <span class="text-danger-600">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="current_password" class="form-control radius-8" id="current-password" placeholder="Masukkan Password Saat Ini" required>
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#current-password"></span>
                                    </div>
                                </div>

                                <div class="mb-20">
                                    <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password Baru <span class="text-danger-600">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="password" class="form-control radius-8" id="your-password" placeholder="Masukkan Password Baru" required>
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                    </div>
                                </div>

                                <div class="mb-20">
                                    <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Konfirmasi Password Baru <span class="text-danger-600">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="password_confirmation" class="form-control radius-8" id="confirm-password" placeholder="Ulangi Password Baru" required>
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#confirm-password"></span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-end gap-3 mt-24">
                                    <button type="submit" class="btn btn-primary-600 border border-primary-600 text-md px-40 py-12 radius-8"> 
                                        Perbarui Password
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Preview Foto saat memilih gambar baru
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });

    // Show / Hide Password
    function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on('click', function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    initializePasswordToggle('.toggle-password');
</script>
@endpush