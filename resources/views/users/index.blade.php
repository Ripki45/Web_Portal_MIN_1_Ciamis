@extends('layouts.admin')

@section('title', 'Manajemen Role & Hak Akses - MIN 1 Ciamis')

@section('content')

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Pusat Manajemen Role & Toggle Hak Akses</h6>
            <span class="text-xs text-secondary-light">
                Atur akun pengguna (`Superadmin`, `Admin`, `Guru`, `Pembina Ekskul`) dan nyalakan/matikan hak akses tiap role dengan 1 klik
            </span>
        </div>
        <span class="bg-primary-50 text-primary-600 border border-primary fw-bold px-14 py-6 radius-8 text-xs d-inline-block">
            Total Akun: {{ $users->count() }} Pengguna
        </span>
    </div>

    <!-- Navigasi 2 Tab: 1. Daftar Akun & Role | 2. Pengaturan Toggle Hak Akses -->
    <div class="card radius-12 mb-24">
        <div class="card-body p-12 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <ul class="nav nav-pills button-tab gap-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') != 'akses' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                            data-bs-toggle="pill" data-bs-target="#pane-akun" type="button" role="tab">
                        <i class="ph-bold ph-users-three text-lg"></i> 1. Kelola Akun & Role Pengguna ({{ $users->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'akses' ? 'active' : '' }} d-flex align-items-center gap-2 radius-8 px-20 py-10 fw-bold"
                            data-bs-toggle="pill" data-bs-target="#pane-akses" type="button" role="tab">
                        <i class="ph-bold ph-sliders-horizontal text-lg"></i> 2. Matriks Toggle Hak Akses Role
                    </button>
                </li>
            </ul>

            <span id="badgeStatusSimpanToggle" class="bg-success-focus text-success-main px-12 py-6 rounded-pill fw-bold text-xs d-none">
                ✓ Perubahan Tersimpan Otomatis
            </span>
        </div>
    </div>

    <div class="tab-content">

        <!-- ========================================================================= -->
        <!-- TAB 1: KELOLA AKUN & ROLE PENGGUNA (FORM KIRI + TABEL KANAN)              -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade {{ request('tab') != 'akses' ? 'show active' : '' }}" id="pane-akun" role="tabpanel">
            <div class="row gy-4">

                <!-- SISI KIRI (4 KOLOM): FORM TAMBAH AKUN -->
                <div class="col-lg-4">
                    <div class="card radius-12 border shadow-sm">
                        <div class="card-header border-bottom bg-base py-16 px-20">
                            <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                                <i class="ph-bold ph-user-plus text-lg"></i> Buat Akun & Role Baru
                            </h6>
                        </div>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="card-body p-20">
                                <div class="mb-14">
                                    <label class="form-label fw-bold text-xs text-success-600 mb-6">
                                        💡 Hubungkan dengan Data GTK (Opsional)
                                    </label>
                                    <select name="gtk_id" class="form-select form-select-sm radius-8 border-success" onchange="isiNamaDariGtk(this)">
                                        <option value="" data-nama="">-- Pilih Guru / Pembina dari GTK --</option>
                                        <?php foreach ($gtks as $g): ?>
                                            <option value="{{ $g->id }}" data-nama="{{ $g->nama_lengkap }}">
                                                {{ $g->nama_lengkap }} ({{ $g->posisi->nama_posisi ?? $g->jabatan->nama_jabatan ?? 'GTK' }})
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-xs text-secondary-light">Otomatis mengisi nama pengguna sesuai GTK.</small>
                                </div>

                                <div class="mb-14">
                                    <label class="form-label fw-bold text-sm mb-6">Nama Lengkap <span class="text-danger-600">*</span></label>
                                    <input type="text" name="name" id="inputNamaUser" class="form-control radius-8 fw-semibold" placeholder="Contoh: Ripki Ramdani, S.Pd" required>
                                </div>

                                <div class="mb-14">
                                    <label class="form-label fw-bold text-sm mb-6">Email Login <span class="text-danger-600">*</span></label>
                                    <input type="email" name="email" class="form-control radius-8" placeholder="nama@min1ciamis.sch.id" required>
                                </div>

                                <div class="mb-14">
                                    <label class="form-label fw-bold text-sm mb-6">Password Login <span class="text-danger-600">*</span></label>
                                    <input type="password" name="password" class="form-control radius-8" placeholder="Minimal 6 karakter" required>
                                </div>

                                <div class="mb-20">
                                    <label class="form-label fw-bold text-sm mb-6">Pilih Role Pengguna <span class="text-danger-600">*</span></label>
                                    <select name="role" class="form-select radius-8 fw-bold border-primary" required>
                                        <option value="guru">👨‍🏫 Guru (Wali Kelas / Guru Mapel)</option>
                                        <option value="pembina_ekskul">⛺ Pembina Ekstrakurikuler</option>
                                        <option value="admin">💼 Admin / Operator TU</option>
                                        <option value="superadmin">👑 Super Admin</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold d-flex align-items-center justify-content-center gap-2">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Akun Baru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- SISI KANAN (8 KOLOM): TABEL AKUN -->
                <div class="col-lg-8">
                    <div class="card basic-data-table radius-12 border shadow-sm">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-md fw-bold mb-0">Daftar Akun Pengguna Terdaftar</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="table-responsive">
                                <table class="table bordered-table align-middle mb-0" id="tableUsers">
                                    <thead>
                                        <tr>
                                            <th width="6%">No</th>
                                            <th>Nama Pengguna</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Tautan GTK</th>
                                            <th class="text-center" width="14%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $idx => $u): ?>
                                            @php
                                                $badgeColor = match($u->role) {
                                                    'superadmin'     => 'bg-danger-focus text-danger-main border-danger',
                                                    'admin'          => 'bg-primary-50 text-primary-600 border-primary',
                                                    'guru'           => 'bg-success-focus text-success-main border-success',
                                                    'pembina_ekskul' => 'bg-warning-focus text-warning-main border-warning',
                                                    default          => 'bg-neutral-200 text-secondary-light',
                                                };
                                            @endphp
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td class="fw-bold text-primary-light">
                                                    {{ $u->name }}
                                                    @if ($u->id === auth()->id())
                                                        <span class="bg-info-focus text-info-main px-8 py-2 rounded-pill text-xs fw-bold ms-1 d-inline-block">Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-xs fw-semibold">{{ $u->email }}</td>
                                                <td class="text-nowrap">
                                                    {{-- Tanpa class .badge agar tidak menggelembung jadi lingkaran besar --}}
                                                    <span class="{{ $badgeColor }} border px-12 py-4 rounded-pill text-xs fw-bold d-inline-block text-nowrap">
                                                        {{ $u->label_role }}
                                                    </span>
                                                </td>
                                                <td class="text-xs text-nowrap">
                                                    @if ($u->gtk)
                                                        <span class="text-success-600 fw-semibold">✓ {{ $u->gtk->nama_lengkap }}</span>
                                                    @else
                                                        <span class="text-secondary-light fst-italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <button type="button"
                                                                class="bg-success-focus text-success-600 bg-hover-success-200 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editUserModal{{ $u->id }}"
                                                                title="Edit Role / Password">
                                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                                        </button>

                                                        @if ($u->id !== auth()->id())
                                                            <button type="button"
                                                                    onclick="konfirmasiHapusUser('{{ route('users.destroy', $u->id) }}', '{{ addslashes($u->name) }}')"
                                                                    class="bg-danger-focus text-danger-600 bg-hover-danger-200 w-32-px h-32-px rounded-circle d-flex align-items-center justify-content-center border-0"
                                                                    title="Hapus Akun">
                                                                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: MATRIKS TOGGLE HAK AKSES TIAP ROLE (ADMIN, GURU, PEMBINA EKSKUL)   -->
        <!-- ========================================================================= -->
        <div class="tab-pane fade {{ request('tab') == 'akses' ? 'show active' : '' }}" id="pane-akses" role="tabpanel">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h6 class="text-md fw-bold mb-1">Pengaturan Hak Akses Modul per Role (Toggle Switch)</h6>
                        <span class="text-xs text-secondary-light">
                            Geser tombol <b>Switch</b> ke kanan (Hijau) untuk mengaktifkan akses menu pada role terkait. Perubahan langsung tersimpan otomatis!
                        </span>
                    </div>
                    <span class="bg-danger-focus text-danger-main px-12 py-6 rounded-pill text-xs fw-bold d-inline-block">
                        👑 Super Admin: Selalu Aktif di Semua Modul
                    </span>
                </div>

                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table align-middle mb-0">
                            <thead>
                                <tr class="bg-neutral-50">
                                    <th width="5%" class="text-center">No</th>
                                    <th width="18%">Kategori</th>
                                    <th>Nama Modul / Fitur Sistem</th>
                                    <th width="14%" class="text-center">👑 Super Admin</th>
                                    <th width="15%" class="text-center">💼 Admin / TU</th>
                                    <th width="15%" class="text-center">👨‍🏫 Guru</th>
                                    <th width="15%" class="text-center">⛺ Pembina Ekskul</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                <?php foreach ($modulList as $menuKey => $info): ?>
                                    <tr>
                                        <td class="text-center fw-bold">{{ $no++ }}</td>
                                        <td>
                                            <span class="bg-info-focus text-info-main px-10 py-4 rounded-pill text-xs fw-semibold d-inline-block text-nowrap">
                                                {{ $info['kategori'] }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-primary-light">{{ $info['label'] }}</td>

                                        <!-- Kolom Superadmin (Selalu Terkunci Aktif) -->
                                        <td class="text-center">
                                            <div class="form-switch switch-success d-inline-flex align-items-center justify-content-center">
                                                <input class="form-check-input" type="checkbox" checked disabled style="width: 44px; height: 22px; opacity: 0.75;">
                                            </div>
                                        </td>

                                        <!-- Kolom Toggle Dinamis: Admin, Guru, Pembina Ekskul -->
                                        <?php foreach (array_keys($roleTarget) as $roleCode): ?>
                                            @php
                                                $aktif = $matrix[$roleCode][$menuKey] ?? false;
                                            @endphp
                                            <td class="text-center">
                                                <div class="form-switch switch-success d-inline-flex align-items-center justify-content-center">
                                                    <input class="form-check-input cursor-pointer toggle-hak-akses"
                                                           type="checkbox"
                                                           role="switch"
                                                           data-role="{{ $roleCode }}"
                                                           data-menu="{{ $menuKey }}"
                                                           {{ $aktif ? 'checked' : '' }}
                                                           style="width: 46px; height: 24px;">
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Form Tersembunyi untuk Hapus Akun via SweetAlert -->
    <form id="formDeleteUserGlobal" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Edit Akun -->
    <?php foreach ($users as $u): ?>
        <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border-bottom">
                        <h1 class="modal-title fs-5 fw-bold">Edit Akun: {{ $u->name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('users.update', $u->id) }}" method="POST" id="formEditUser{{ $u->id }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-24">
                            <div class="mb-14">
                                <label class="form-label fw-semibold text-sm mb-6">Hubungkan dengan Data GTK</label>
                                <select name="gtk_id" class="form-select radius-8">
                                    <option value="">-- Tidak Dihubungkan --</option>
                                    <?php foreach ($gtks as $g): ?>
                                        <option value="{{ $g->id }}" {{ $u->gtk_id == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama_lengkap }}
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-14">
                                <label class="form-label fw-semibold text-sm mb-6">Nama Lengkap <span class="text-danger-600">*</span></label>
                                <input type="text" name="name" class="form-control radius-8 fw-bold" value="{{ $u->name }}" required>
                            </div>
                            <div class="mb-14">
                                <label class="form-label fw-semibold text-sm mb-6">Email Login <span class="text-danger-600">*</span></label>
                                <input type="email" name="email" class="form-control radius-8" value="{{ $u->email }}" required>
                            </div>
                            <div class="mb-14">
                                <label class="form-label fw-semibold text-sm mb-6">Role Pengguna <span class="text-danger-600">*</span></label>
                                <select name="role" class="form-select radius-8 fw-bold border-primary" required>
                                    <option value="superadmin" {{ $u->role == 'superadmin' ? 'selected' : '' }}>👑 Super Admin</option>
                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>💼 Admin / Operator TU</option>
                                    <option value="guru" {{ $u->role == 'guru' ? 'selected' : '' }}>👨‍🏫 Guru (Wali Kelas / Mapel)</option>
                                    <option value="pembina_ekskul" {{ $u->role == 'pembina_ekskul' ? 'selected' : '' }}>⛺ Pembina Ekstrakurikuler</option>
                                </select>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold text-sm mb-6">Password Baru (Kosongkan jika tidak diganti)</label>
                                <input type="password" name="password" class="form-control radius-8" placeholder="Ketik password baru jika ingin reset...">
                            </div>
                        </div>
                        <div class="modal-footer px-24 pb-24 border-0">
                            <button type="button" class="btn btn-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                            <button type="button"
                                    onclick="konfirmasiUpdateUser('formEditUser{{ $u->id }}', '{{ addslashes($u->name) }}')"
                                    class="btn btn-primary-600 radius-8 fw-bold">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

@endsection

@push('scripts')
<script>
    new DataTable('#tableUsers', {
        columnDefs: [{ orderable: false, targets: [5] }]
    });

    function isiNamaDariGtk(selectEl) {
        const opt = selectEl.options[selectEl.selectedIndex];
        const nama = opt.getAttribute('data-nama');
        if (nama) {
            document.getElementById('inputNamaUser').value = nama;
        }
    }

    // 1. SweetAlert Konfirmasi Hapus Akun
    function konfirmasiHapusUser(url, nama) {
        Swal.fire({
            title: 'Hapus Akun ' + nama + '?',
            text: 'Akun pengguna ini tidak akan bisa login lagi setelah dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Akun!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formDeleteUserGlobal');
                form.action = url;
                form.submit();
            }
        });
    }

    // 2. SweetAlert Konfirmasi Update Akun & Role
    function konfirmasiUpdateUser(formId, nama) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Perbarui Akun ' + nama + '?',
            text: 'Perubahan data profil, role, atau password akan langsung diterapkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan Perubahan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // 3. SweetAlert Notifikasi Sukses / Gagal dari Session Server
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 2200,
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Perhatian!',
            text: @json(session('error'))
        });
    @endif

    // 4. Simpan otomatis saat Toggle Switch digeser
    document.querySelectorAll('.toggle-hak-akses').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const roleVal    = this.getAttribute('data-role');
            const menuVal    = this.getAttribute('data-menu');
            const isAllowed  = this.checked;
            const badgeNotif = document.getElementById('badgeStatusSimpanToggle');

            fetch("{{ route('users.togglePermission') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    role: roleVal,
                    menu_key: menuVal,
                    is_allowed: isAllowed
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && badgeNotif) {
                    badgeNotif.classList.remove('d-none');
                    setTimeout(() => badgeNotif.classList.add('d-none'), 2000);
                }
            });
        });
    });
</script>
@endpush