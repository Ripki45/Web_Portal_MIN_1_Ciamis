<?php

use App\Http\Controllers\BannerCarouselController;
use App\Http\Controllers\BannerPpdbController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DuniaIlmuController;
use App\Http\Controllers\EkskulController;
use App\Http\Controllers\GtkController;
use App\Http\Controllers\GuruStaffController;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KenaikanKelasController;
use App\Http\Controllers\KepalaStaffController;
use App\Http\Controllers\LayananCepatController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\MutasiSiswaController;
use App\Http\Controllers\NavMenuController;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilMadrasahController;
use App\Http\Controllers\ProgramUnggulanController;
use App\Http\Controllers\SambutanProfilController;
use App\Http\Controllers\SemanticSearchController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KalenderAkademikController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK & PORTAL INFORMASI
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/semantic-search', [SemanticSearchController::class, 'search'])
    ->middleware('throttle:30,1')
    ->name('api.semantic-search');

// Kabar Madrasah (Berita & Warta)
Route::get('/kabar-madrasah', [BeritaController::class, 'publik'])->name('berita.publik');
Route::get('/kabar', [BeritaController::class, 'publik']);
Route::get('/warta', [BeritaController::class, 'publik']);
Route::get('/warta-madrasah', [BeritaController::class, 'publik']);
Route::get('/berita/{slug}', [BeritaController::class, 'show'])
    ->where('slug', '^(?!create$|edit$).*')
    ->name('berita.show');

// Direktori Layanan Cepat, Dunia Ilmu & Program Unggulan
Route::get('/layanan-cepat', [LayananCepatController::class, 'publik'])->name('layanan-cepat.publik');
Route::get('/layanan', [LayananCepatController::class, 'publik']);

Route::get('/dunia-ilmu', [DuniaIlmuController::class, 'publik'])->name('dunia-ilmu.publik');
Route::get('/media-pembelajaran', [DuniaIlmuController::class, 'publik']);
Route::get('/dunia-ilmu/play/{slug}', [DuniaIlmuController::class, 'play'])->name('dunia-ilmu.play');

Route::get('/program-unggulan', [ProgramUnggulanController::class, 'publik'])->name('program-unggulan.publik');
Route::get('/program', [ProgramUnggulanController::class, 'publik']);
Route::get('/program-unggulan/{slug}', [ProgramUnggulanController::class, 'detail'])->name('program-unggulan.detail');

// Profil, Pimpinan, Guru & Halaman Statis
Route::get('/profil-madrasah', [ProfilMadrasahController::class, 'showFrontend'])->name('profil-madrasah.public');
Route::get('/kepala-madrasah-dan-staff', [KepalaStaffController::class, 'showFrontend'])->name('kepala-staff.public');
Route::get('/guru-dan-staff', [GuruStaffController::class, 'showFrontend'])->name('guru-staff.public');
Route::get('/halaman/{slug}', [HalamanController::class, 'show'])->name('halaman.show');

// Layar Scanner Presensi QR Depan (Tanpa Login + Rate Limit)
Route::get('/scan-presensi', [PresensiController::class, 'scanner'])->name('presensi.scanner');
Route::post('/scan-presensi/proses', [PresensiController::class, 'prosesScan'])
    ->middleware('throttle:60,1')
    ->name('presensi.prosesScan');
Route::post('/scan-presensi/manual-guru', [PresensiController::class, 'storeManualDepan'])
    ->middleware('throttle:30,1')
    ->name('presensi.storeManualDepan');
Route::post('/scan-presensi/verifikasi-pin', [PresensiController::class, 'verifikasiPinServer'])
    ->middleware('throttle:5,1')
    ->name('presensi.verifikasiPin');
Route::post('/scan-presensi/kunci-pin', [PresensiController::class, 'kunciPinServer'])
    ->name('presensi.kunciPin');
    // Kalender & Agenda Akademik (Dashboard)
    Route::get('/kalender-akademik-admin', [KalenderAkademikController::class, 'index'])->name('kalender-akademik.index');
    Route::get('/kalender-akademik-admin/cetak-heb', [KalenderAkademikController::class, 'cetakHeb'])->name('kalender-akademik.cetakHeb');
    // Kalender & Agenda Akademik Publik
    Route::get('/kalender-akademik', [KalenderAkademikController::class, 'publik'])->name('kalender-akademik.publik');
    Route::get('/agenda-akademik', [KalenderAkademikController::class, 'publik']);
    Route::get('/kaldik', [KalenderAkademikController::class, 'publik']);

/*
|--------------------------------------------------------------------------
| 2. AREA TERAUTENTIKASI (DASHBOARD & MANAJEMEN BERBASIS HAK AKSES MODUL)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profil Akun Pribadi (Semua Role yang Login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Khusus Superadmin: Manajemen Pengguna & Matriks Hak Akses
    Route::middleware('modul:superadmin')->group(function () {
        Route::post('/users/toggle-permission', [UserRoleController::class, 'togglePermission'])->name('users.togglePermission');
        Route::resource('users', UserRoleController::class)->except(['create', 'show', 'edit']);
    });

    // Modul Kepegawaian (Jabatan, Posisi & GTK)
    Route::middleware('modul:kepegawaian')->group(function () {
        Route::resource('jabatan', JabatanController::class)->except(['create', 'show', 'edit']);
        Route::resource('posisi', PosisiController::class)->except(['create', 'show', 'edit']);
        Route::get('/posisi/by-jabatan/{jabatan_id}', [PosisiController::class, 'getByJabatan'])->name('posisi.byJabatan');
        Route::resource('gtk', GtkController::class)->except(['create', 'show', 'edit']);
        Route::post('/gtk/bulk-delete', [GtkController::class, 'bulkDestroy'])->name('gtk.bulkDestroy');
    });

    // Modul Tahun Ajaran
    Route::middleware('modul:tahun_ajaran')->group(function () {
        Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['create', 'show', 'edit']);
        Route::patch('/tahun-ajaran/{id}/activate', [TahunAjaranController::class, 'setActive'])->name('tahun-ajaran.activate');
    });

    // Modul Manajemen Kelas & Plotting Mapel
    Route::middleware('modul:kelas')->group(function () {
        Route::resource('kelas', KelasController::class)->except(['create', 'show', 'edit']);
        Route::post('/kelas/plotting-mapel', [KelasController::class, 'storePlottingMapel'])->name('kelas.storePlottingMapel');
        Route::delete('/kelas/plotting-mapel/{id}', [KelasController::class, 'destroyPlottingMapel'])->name('kelas.destroyPlottingMapel');
        Route::post('/kelas/plotting-mapel/bulk-delete', [KelasController::class, 'bulkDestroyPlottingMapel'])->name('kelas.bulkDestroyPlottingMapel');
    });
    // Dashboard detail kelas boleh dibuka oleh pengelola kelas, siswa, atau wali kelas/guru presensi
    Route::get('/kelas/{id}/dashboard', [KelasController::class, 'show'])
        ->middleware('modul:kelas,siswa,presensi_monitoring')
        ->name('kelas.show');

    // Modul Data Siswa & Buku Mutasi
    Route::middleware('modul:siswa')->group(function () {
        Route::post('/siswa/import-paste', [SiswaController::class, 'importPaste'])->name('siswa.importPaste');
        Route::resource('siswa', SiswaController::class)->except(['create', 'show', 'edit']);
        Route::post('/siswa/bulk-delete', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulkDestroy');

        Route::get('/mutasi', [MutasiSiswaController::class, 'index'])->name('mutasi.index');
        Route::post('/mutasi/kenaikan', [MutasiSiswaController::class, 'prosesKenaikan'])->name('mutasi.kenaikan');
        Route::post('/mutasi/masuk', [MutasiSiswaController::class, 'storeMasuk'])->name('mutasi.storeMasuk');
        Route::post('/mutasi/keluar', [MutasiSiswaController::class, 'storeKeluar'])->name('mutasi.storeKeluar');
        Route::delete('/mutasi/{id}', [MutasiSiswaController::class, 'destroy'])->name('mutasi.destroy');
        Route::post('/mutasi/bulk-delete', [MutasiSiswaController::class, 'bulkDestroy'])->name('mutasi.bulkDestroy');
    });

    // Modul Master Mata Pelajaran (Duplikasi Dihapus)
    Route::middleware('modul:mapel')->group(function () {
        Route::post('/mapel/generate-default', [MataPelajaranController::class, 'generateDefault'])->name('mapel.generate');
        Route::post('/mapel/bulk-delete', [MataPelajaranController::class, 'bulkDestroy'])->name('mapel.bulkDestroy');
        Route::resource('mapel', MataPelajaranController::class)->except(['create', 'show', 'edit']);
    });

    // Modul Jadwal Pelajaran
    Route::middleware('modul:jadwal')->group(function () {
        Route::resource('jadwal', JadwalController::class);
    });

    // Modul Ekstrakurikuler & Anggota
    Route::middleware('modul:ekskul')->group(function () {
        Route::get('/ekskul', [EkskulController::class, 'index'])->name('ekskul.index');
        Route::post('/ekskul', [EkskulController::class, 'store'])->name('ekskul.store');
        Route::put('/ekskul/{id}', [EkskulController::class, 'update'])->name('ekskul.update');
        Route::delete('/ekskul/{id}', [EkskulController::class, 'destroy'])->name('ekskul.destroy');
        Route::post('/ekskul/bulk-delete', [EkskulController::class, 'bulkDestroy'])->name('ekskul.bulkDestroy');

        Route::get('/ekskul/{id}/anggota', [EkskulController::class, 'show'])->name('ekskul.show');
        Route::post('/ekskul/{id}/anggota', [EkskulController::class, 'storeAnggota'])->name('ekskul.storeAnggota');
        Route::delete('/ekskul/anggota/{anggotaId}', [EkskulController::class, 'destroyAnggota'])->name('ekskul.destroyAnggota');
        Route::post('/ekskul/anggota/bulk-delete', [EkskulController::class, 'bulkDestroyAnggota'])->name('ekskul.bulkDestroyAnggota');
    });

    // Modul Kenaikan Kelas, Arsip Alumni & Pemeliharaan Sistem
    Route::middleware('modul:kenaikan_reset')->group(function () {
        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan.index');
        Route::post('/kenaikan-kelas/proses', [KenaikanKelasController::class, 'prosesNaikKelas'])->name('kenaikan.proses');
        Route::post('/kenaikan-kelas/alumni/bulk-delete', [KenaikanKelasController::class, 'bulkDeleteAlumni'])->name('kenaikan.bulkDeleteAlumni');
        Route::post('/kenaikan-kelas/magic-action', [KenaikanKelasController::class, 'magicAction'])->name('kenaikan.magicAction');
    });

    // Modul Presensi (Monitoring, Cetak Kartu QR & Rekap Bulanan)
    Route::middleware('modul:presensi_monitoring')->group(function () {
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi/manual', [PresensiController::class, 'storeManual'])->name('presensi.storeManual');
        Route::post('/presensi/setting-jam', [PresensiController::class, 'updateSettingJam'])->name('presensi.updateSettingJam');
        Route::post('/presensi/hari-libur', [PresensiController::class, 'storeHariLibur'])->name('presensi.storeHariLibur');
        Route::delete('/presensi/hari-libur/{id}', [PresensiController::class, 'destroyHariLibur'])->name('presensi.destroyHariLibur');
    });
    Route::get('/presensi/kartu-qr', [PresensiController::class, 'kartuQr'])
        ->middleware('modul:siswa,presensi_monitoring,presensi_rekap')
        ->name('presensi.kartuQr');
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])
        ->middleware('modul:presensi_rekap')
        ->name('presensi.rekap');

    // Modul Menu Navbar
    Route::middleware('modul:nav_menu')->group(function () {
        Route::resource('nav-menu', NavMenuController::class)->except(['create', 'show', 'edit']);
    });
    Route::get('/nav-menu/{id}/halaman', [HalamanController::class, 'kelolaDariMenu'])
        ->middleware('modul:nav_menu,cms')
        ->name('nav-menu.halaman');

    // Modul Konten Website Publik (CMS)
    Route::middleware('modul:cms')->group(function () {
        Route::resource('halaman', HalamanController::class)->except(['create', 'show']);

        Route::post('/berita/generate-ai', [BeritaController::class, 'generateAi'])->name('berita.generateAi');
        Route::resource('berita', BeritaController::class)->except(['show']);

        Route::post('/kategori-berita', [BeritaController::class, 'storeKategori'])->name('kategori-berita.store');
        Route::put('/kategori-berita/{id}', [BeritaController::class, 'updateKategori'])->name('kategori-berita.update');
        Route::delete('/kategori-berita/{id}', [BeritaController::class, 'destroyKategori'])->name('kategori-berita.destroy');

        Route::post('/banner-carousel/generate-ai', [BannerCarouselController::class, 'generateAi'])->name('banner-carousel.generateAi');
        Route::resource('banner-carousel', BannerCarouselController::class)->except(['create', 'show', 'edit']);

        Route::get('/sambutan-profil', [SambutanProfilController::class, 'index'])->name('sambutan-profil.index');
        Route::put('/sambutan-profil', [SambutanProfilController::class, 'update'])->name('sambutan-profil.update');
        Route::post('/sambutan-profil/generate-ai', [SambutanProfilController::class, 'generateAi'])->name('sambutan-profil.generateAi');

        Route::resource('dunia-ilmu-admin', DuniaIlmuController::class)
            ->names('dunia-ilmu')
            ->parameters(['dunia-ilmu-admin' => 'id'])
            ->except(['create', 'show', 'edit']);

        Route::get('/profil-madrasah-admin', [ProfilMadrasahController::class, 'index'])->name('profil-madrasah.index');
        Route::put('/profil-madrasah-admin', [ProfilMadrasahController::class, 'update'])->name('profil-madrasah.update');
        Route::post('/profil-madrasah-admin/sinkron-menu', [ProfilMadrasahController::class, 'sinkronKeNavbar'])->name('profil-madrasah.sinkronMenu');

        Route::get('/kepala-staff-admin', [KepalaStaffController::class, 'index'])->name('kepala-staff.index');
        Route::put('/kepala-staff-admin', [KepalaStaffController::class, 'update'])->name('kepala-staff.update');

        Route::get('/guru-staff-admin', [GuruStaffController::class, 'index'])->name('guru-staff.index');
        Route::put('/guru-staff-admin', [GuruStaffController::class, 'update'])->name('guru-staff.update');

        Route::get('/banner-ppdb', [BannerPpdbController::class, 'index'])->name('banner-ppdb.index');
        Route::put('/banner-ppdb', [BannerPpdbController::class, 'update'])->name('banner-ppdb.update');

        Route::resource('layanan-cepat-admin', LayananCepatController::class)
            ->names('layanan-cepat')
            ->except(['create', 'show', 'edit']);
        Route::patch('/layanan-cepat-admin/{id}/toggle-status', [LayananCepatController::class, 'toggleStatus'])->name('layanan-cepat.toggle-status');

        Route::post('/program-unggulan-admin/generate-ai', [ProgramUnggulanController::class, 'generateAiProgram'])->name('program-unggulan.generateAi');
        Route::resource('program-unggulan-admin', ProgramUnggulanController::class)
            ->names('program-unggulan')
            ->parameters(['program-unggulan-admin' => 'id'])
            ->except(['show']);
        Route::patch('/program-unggulan-admin/{id}/toggle-status', [ProgramUnggulanController::class, 'toggleStatus'])->name('program-unggulan.toggle-status');
    });
    // CRUD & Pengaturan Kaldik (Hanya Superadmin / pengelola Tahun Ajaran / CMS)
    Route::middleware('modul:tahun_ajaran,cms')->group(function () {
        Route::post('/kalender-akademik-admin', [KalenderAkademikController::class, 'store'])->name('kalender-akademik.store');
        Route::put('/kalender-akademik-admin/{agendaAkademik}', [KalenderAkademikController::class, 'update'])->name('kalender-akademik.update');
        Route::delete('/kalender-akademik-admin/{agendaAkademik}', [KalenderAkademikController::class, 'destroy'])->name('kalender-akademik.destroy');
        Route::put('/kalender-akademik-dokumen', [KalenderAkademikController::class, 'updateDokumen'])->name('kalender-akademik.updateDokumen');
        Route::post('/kalender-akademik-admin/sinkron-libur', [KalenderAkademikController::class, 'sinkronHariLibur'])
            ->name('kalender-akademik.sinkronLibur');
    });
});

require __DIR__.'/auth.php';