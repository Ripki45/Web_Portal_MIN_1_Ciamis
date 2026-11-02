@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran & Akses Scan Guru - MIN 1 Ciamis')

@section('content')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-1">Jadwal Pelajaran & Sinkronisasi Hak Scan QR Guru</h6>
            <span class="text-xs text-secondary-light">
                Guru Mata Pelajaran otomatis hanya dapat men-scan siswa sesuai kelas dan hari mengajar yang terdaftar di sini
            </span>
        </div>
    </div>

    <div class="row gy-4">
        {{-- SISI KIRI (4 KOLOM): FORM TAMBAH JADWAL (KHUSUS ADMIN / SUPERADMIN) --}}
        @if (!$isGuru)
            <div class="col-lg-4">
                <div class="card radius-12 border shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-20">
                        <h6 class="text-md fw-bold mb-0 text-primary-600 d-flex align-items-center gap-2">
                            <i class="ph-bold ph-calendar-plus text-lg"></i> Plot Jadwal Mengajar
                        </h6>
                    </div>
                    <form action="{{ route('jadwal.store') }}" method="POST">
                        @csrf
                        <div class="card-body p-20">
                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-6">Pilih Kelas / Rombel <span class="text-danger-600">*</span></label>
                                <select name="kelas_id" class="form-select radius-8 fw-bold" required>
                                    <?php foreach ($kelasList as $kls): ?>
                                        <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>
                                            {{ $kls->nama_kelas }}
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-6">Mata Pelajaran <span class="text-danger-600">*</span></label>
                                <select name="mapel_id" class="form-select radius-8 fw-semibold" required>
                                    <?php foreach ($mapels as $mp): ?>
                                        <option value="{{ $mp->id }}">{{ $mp->kode_mapel }} — {{ $mp->nama_mapel }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-14">
                                <label class="form-label fw-bold text-xs mb-6">Guru Pengampu <span class="text-danger-600">*</span></label>
                                <select name="guru_id" class="form-select radius-8 fw-semibold" required>
                                    <?php foreach ($gtks as $g): ?>
                                        <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row g-2 mb-20">
                                <div class="col-6">
                                    <label class="form-label fw-bold text-xs mb-6">Hari Mengajar <span class="text-danger-600">*</span></label>
                                    <select name="hari" class="form-select radius-8 fw-bold border-primary" required>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Setiap Hari">Setiap Hari (Wali Kelas)</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-xs mb-6">Jam Pelajaran <span class="text-danger-600">*</span></label>
                                    <input type="text" name="jam_pelajaran" class="form-control radius-8 fw-semibold" value="07:30 - 09:00" placeholder="07:30 - 09:00" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-600 w-100 radius-8 py-10 fw-bold">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- SISI KANAN: TABEL JADWAL PELAJARAN --}}
        <div class="{{ $isGuru ? 'col-lg-12' : 'col-lg-8' }}">
            <div class="card radius-12 border shadow-sm">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h6 class="text-md fw-bold mb-0">Daftar Jadwal Mengajar</h6>

                    <form action="{{ route('jadwal.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <select name="kelas_id" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas Ampuan --</option>
                            <?php foreach ($kelasList as $kls): ?>
                                <option value="{{ $kls->id }}" {{ $selectedKelasId == $kls->id ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                            <?php endforeach; ?>
                        </select>
                        <select name="hari" class="form-select form-select-sm radius-8 fw-bold" onchange="this.form.submit()">
                            <option value="">-- Semua Hari --</option>
                            <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h): ?>
                                <option value="{{ $h }}" {{ $selectedHari == $h ? 'selected' : '' }}>{{ $h }}</option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th width="6%">No</th>
                                    <th>Hari & Jam</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru Pengampu</th>
                                    @if (!$isGuru)
                                        <th class="text-center" width="14%">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwals as $idx => $jd)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary-50 text-primary-600 fw-bold px-10 py-4">{{ $jd->hari ?? 'Setiap Hari' }}</span>
                                            <span class="d-block text-xs text-secondary-light mt-1">{{ $jd->jam_pelajaran ?? '07:30 - 09:00' }}</span>
                                        </td>
                                        <td class="fw-bold">{{ $jd->kelas->nama_kelas ?? '-' }}</td>
                                        <td class="fw-bold text-primary-light">{{ $jd->mapel->nama_mapel ?? '-' }}</td>
                                        <td class="text-xs fw-semibold">{{ $jd->guru->nama_lengkap ?? '-' }}</td>
                                        @if (!$isGuru)
                                            <td class="text-center">
                                                <form action="{{ route('jadwal.destroy', $jd->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger radius-8 py-4 px-10">Hapus</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-24 text-secondary-light">Belum ada jadwal pelajaran pada filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection