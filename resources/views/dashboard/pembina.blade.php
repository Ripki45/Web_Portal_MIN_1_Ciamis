@extends('layouts.admin')

@section('title', 'Dashboard Pembina Ekstrakurikuler - MIN 1 Ciamis')

@section('content')

    @if ($user->role === 'superadmin')
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 bg-white border border-primary radius-12 px-16 py-10 mb-20 shadow-sm">
            <span class="text-xs fw-bold text-primary-600 d-flex align-items-center gap-2">
                <i class="ph-fill ph-eye text-lg"></i> Mode Pratinjau Superadmin (Anda sedang melihat tampilan Dashboard Pembina Ekskul):
            </span>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dashboard', ['preview_role' => 'superadmin']) }}" class="btn btn-outline-secondary btn-sm radius-8 text-xs fw-bold py-4 px-12">👑 Dashboard Admin</a>
                <a href="{{ route('dashboard', ['preview_role' => 'guru']) }}" class="btn btn-outline-success btn-sm radius-8 text-xs fw-bold py-4 px-12">👨‍🏫 Dashboard Guru</a>
                <a href="{{ route('dashboard', ['preview_role' => 'pembina_ekskul']) }}" class="btn btn-warning-600 btn-sm radius-8 text-xs fw-bold py-4 px-12 text-white">⛺ Dashboard Pembina</a>
            </div>
        </div>
    @endif

    <div class="row gy-20-px">
        <div class="col-xxl-6 col-lg-6">
            <div style="background: linear-gradient(135deg, #78350f 0%, #b45309 55%, #d97706 100%);"
                 class="py-28 rounded-4 ps-32 pe-24 d-flex align-items-center h-100 shadow-sm">
                <div class="w-100">
                    <div class="d-flex align-items-center gap-2 mb-12">
                        <span class="py-6 px-12 bg-white bg-opacity-25 text-white fw-bold text-xs rounded-pill">
                            ⛺ Pembina Ekstrakurikuler • {{ $ekskulTerpilih->nama_ekskul ?? 'Ekskul' }}
                        </span>
                        @if ($daftarEkskul->count() > 1)
                            <form action="{{ route('dashboard') }}" method="GET">
                                @if ($user->role === 'superadmin')
                                    <input type="hidden" name="preview_role" value="pembina_ekskul">
                                @endif
                                <select name="ekskul_id" onchange="this.form.submit()" class="form-select form-select-sm radius-8 fw-bold bg-white text-dark border-0 py-4 px-10 text-xs">
                                    <?php foreach ($daftarEkskul as $eks): ?>
                                        <option value="{{ $eks->id }}" {{ ($ekskulTerpilih->id ?? '') == $eks->id ? 'selected' : '' }}>
                                            {{ $eks->nama_ekskul }}
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        @endif
                    </div>
                    <h2 class="h4 fw-bold text-white mb-8">{{ $ekskulTerpilih->nama_ekskul ?? 'Ekstrakurikuler' }}</h2>
                    <p class="text-xs text-white opacity-90 mb-20">
                        Jadwal Latihan: <b>{{ $ekskulTerpilih->hari_latihan ?? 'Setiap Hari Kerja' }} ({{ $ekskulTerpilih->jam_latihan ?? '13:00 - 16:30' }} WIB)</b>
                    </p>
                    <div class="d-flex flex-wrap gap-10">
                        <a href="{{ route('presensi.scanner', ['kategori' => 'ekskul', 'ekskul_id' => $ekskulTerpilih->id ?? '']) }}" target="_blank" class="py-10 px-18 bg-white rounded-pill text-warning-700 fw-bold text-xs">
                            <i class="ph-bold ph-qr-code"></i> Scan Presensi Ekskul
                        </a>
                        <a href="{{ route('presensi.rekap', ['kategori' => 'ekskul', 'ekskul_id' => $ekskulTerpilih->id ?? '']) }}" class="py-10 px-18 bg-dark bg-opacity-25 text-white rounded-pill fw-bold text-xs">
                            <i class="ph-bold ph-printer"></i> Cetak Rekap Ekskul
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 col-lg-6">
            <div class="row gy-20-px">
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <span class="text-secondary-light text-sm fw-medium d-block mb-8">Total Anggota Aktif</span>
                        <h2 class="h4 fw-bold mb-4 text-2xl">{{ $totalAnggota }} Siswa</h2>
                        <span class="text-xs text-success-600 fw-bold">Laki-laki: {{ $totalL }} • Perempuan: {{ $totalP }}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="bg-base border border-neutral-200 radius-16 p-20 h-100">
                        <span class="text-secondary-light text-sm fw-medium d-block mb-8">Hadir Latihan Hari Ini</span>
                        <h2 class="h4 fw-bold mb-4 text-2xl">{{ $hadirHariIni }} / {{ $totalAnggota }}</h2>
                        <span class="text-xs text-danger-600 fw-bold">Izin / Sakit / Alfa: {{ $absenHariIni }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Anggota Ekskul & Total Kehadiran -->
    <div class="card radius-16 border mt-20">
        <div class="card-header bg-base py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Daftar Anggota & Rekap Kehadiran — {{ $ekskulTerpilih->nama_ekskul ?? '' }}</h6>
            <a href="{{ route('ekskul.index', ['ekskul_id' => $ekskulTerpilih->id ?? '']) }}" class="btn btn-primary-600 btn-sm radius-8 text-xs fw-bold">Kelola Anggota</a>
        </div>
        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th width="6%">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center">Total Hadir</th>
                            <th class="text-center">Total Sakit</th>
                            <th class="text-center">Total Izin</th>
                            <th class="text-center">Total Alfa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $idx => $s)
                            @php $rk = $rekapAnggota[$s->id] ?? ['hadir'=>0, 'sakit'=>0, 'izin'=>0, 'alfa'=>0]; @endphp
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ $s->nama_siswa }}</td>
                                <td><span class="badge bg-primary-50 text-primary-600">{{ $s->kelas->nama_kelas ?? '-' }}</span></td>
                                <td class="text-center fw-bold text-success-600">{{ $rk['hadir'] }}</td>
                                <td class="text-center fw-bold text-warning-600">{{ $rk['sakit'] }}</td>
                                <td class="text-center fw-bold text-info-main">{{ $rk['izin'] }}</td>
                                <td class="text-center fw-bold text-danger-600">{{ $rk['alfa'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-20 text-secondary-light">Belum ada anggota terdaftar di ekskul ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection