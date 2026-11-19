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
use App\Http\Controllers\SambutanProfilController;
use App\Http\Controllers\SemanticSearchController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/semantic-search', [SemanticSearchController::class, 'search'])->name('api.semantic-search');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])
    ->where('slug', '^(?!create$|edit$).*')
    ->name('berita.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// ROUTE HALAMAN DEPAN PRESENSI (TANPA LOGIN: SCANNER & INPUT SAKIT/IZIN/ALFA GURU)
Route::get('/scan-presensi', [PresensiController::class, 'scanner'])->name('presensi.scanner');
Route::post('/scan-presensi/proses', [PresensiController::class, 'prosesScan'])->name('presensi.prosesScan');
Route::post('/scan-presensi/manual-guru', [PresensiController::class, 'storeManualDepan'])->name('presensi.storeManualDepan');

// Route Halaman Publik Layanan Cepat
Route::get('/layanan-cepat', [LayananCepatController::class, 'publik'])->name('layanan-cepat.publik');
Route::get('/layanan', [LayananCepatController::class, 'publik']);

// Route Halaman Publik Dunia Ilmu (Direktori Media Pembelajaran & Aplikasi Edukasi)
Route::get('/dunia-ilmu', [DuniaIlmuController::class, 'publik'])->name('dunia-ilmu.publik');
Route::get('/media-pembelajaran', [DuniaIlmuController::class, 'publik']);
Route::post('/scan-presensi/verifikasi-pin', [PresensiController::class, 'verifikasiPinServer'])
    ->middleware('throttle:5,1')
    ->name('presensi.verifikasiPin');
Route::post('/scan-presensi/kunci-pin', [PresensiController::class, 'kunciPinServer'])
    ->name('presensi.kunciPin');
// Rute Publik Pemutar Media Dunia Ilmu (.TSX / .JS / .HTML)
Route::get('/dunia-ilmu/play/{slug}', [DuniaIlmuController::class, 'play'])->name('dunia-ilmu.play');
// Halaman Publik Profil Madrasah
Route::get('/profil-madrasah', [ProfilMadrasahController::class, 'showFrontend'])->name('profil-madrasah.public');
// Halaman Publik Sub-Menu 2: Kepala Madrasah dan Staff
Route::get('/kepala-madrasah-dan-staff', [KepalaStaffController::class, 'showFrontend'])->name('kepala-staff.public');
// Halaman Publik Sub-Menu 3: Guru dan Staff
Route::get('/guru-dan-staff', [GuruStaffController::class, 'showFrontend'])->name('guru-staff.public');

Route::middleware('auth')->group(function () {
    // Manajemen Role Pengguna & Toggle Hak Akses
    Route::post('/users/toggle-permission', [UserRoleController::class, 'togglePermission'])->name('users.togglePermission');
    Route::resource('users', UserRoleController::class)->except(['create', 'show', 'edit']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('jabatan', JabatanController::class)->except(['create', 'show', 'edit']);
    // Route Master Posisi & AJAX Dropdown
    Route::resource('posisi', PosisiController::class)->except(['create', 'show', 'edit']);
    Route::get('/posisi/by-jabatan/{jabatan_id}', [PosisiController::class, 'getByJabatan'])->name('posisi.byJabatan');
    // Route Data Guru & Tendik
    Route::resource('gtk', GtkController::class)->except(['create', 'show', 'edit']);
    Route::post('/gtk/bulk-delete', [GtkController::class, 'bulkDestroy'])->name('gtk.bulkDestroy');
    // Route Master Tahun Ajaran & Semester
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['create', 'show', 'edit']);
    Route::patch('/tahun-ajaran/{id}/activate', [TahunAjaranController::class, 'setActive'])->name('tahun-ajaran.activate');
    // Route Manajemen Kelas
    Route::resource('kelas', KelasController::class)->except(['create', 'show', 'edit']);
    Route::get('/kelas/{id}/dashboard', [KelasController::class, 'show'])->name('kelas.show');
    Route::post('/kelas/plotting-mapel', [KelasController::class, 'storePlottingMapel'])->name('kelas.storePlottingMapel');
    Route::delete('/kelas/plotting-mapel/{id}', [KelasController::class, 'destroyPlottingMapel'])->name('kelas.destroyPlottingMapel');
    Route::post('/kelas/plotting-mapel/bulk-delete', [KelasController::class, 'bulkDestroyPlottingMapel'])->name('kelas.bulkDestroyPlottingMapel');
    // Route Data Siswa & Smart Import Excel
    Route::post('/siswa/import-paste', [SiswaController::class, 'importPaste'])->name('siswa.importPaste');
    Route::resource('siswa', SiswaController::class)->except(['create', 'show', 'edit']);
    Route::post('/siswa/bulk-delete', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulkDestroy');
    // Route Kenaikan Kelas & Mutasi Siswa
    Route::get('/mutasi', [MutasiSiswaController::class, 'index'])->name('mutasi.index');
    Route::post('/mutasi/kenaikan', [MutasiSiswaController::class, 'prosesKenaikan'])->name('mutasi.kenaikan');
    Route::post('/mutasi/masuk', [MutasiSiswaController::class, 'storeMasuk'])->name('mutasi.storeMasuk');
    Route::post('/mutasi/keluar', [MutasiSiswaController::class, 'storeKeluar'])->name('mutasi.storeKeluar');
    Route::delete('/mutasi/{id}', [MutasiSiswaController::class, 'destroy'])->name('mutasi.destroy');
    Route::post('/mutasi/bulk-delete', [MutasiSiswaController::class, 'bulkDestroy'])->name('mutasi.bulkDestroy');
    // Route Master Mata Pelajaran
    Route::post('/mapel/generate-default', [MataPelajaranController::class, 'generateDefault'])->name('mapel.generate');
    Route::resource('mapel', MataPelajaranController::class)->except(['create', 'show', 'edit']);
    Route::post('/mapel/bulk-delete', [MataPelajaranController::class, 'bulkDestroy'])->name('mapel.bulkDestroy');
    // Master Mata Pelajaran (Tambah Mapel Baru, Edit, Hapus, & Bulk Delete)
    Route::post('/mapel/bulk-delete', [MataPelajaranController::class, 'bulkDestroy'])->name('mapel.bulkDestroy');
    Route::resource('mapel', MataPelajaranController::class)->except(['create', 'show', 'edit']);
    // Route Presensi Admin, Pengaturan Jam, & Kalender Libur
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/manual', [PresensiController::class, 'storeManual'])->name('presensi.storeManual');
    Route::post('/presensi/setting-jam', [PresensiController::class, 'updateSettingJam'])->name('presensi.updateSettingJam');
    Route::post('/presensi/hari-libur', [PresensiController::class, 'storeHariLibur'])->name('presensi.storeHariLibur');
    Route::delete('/presensi/hari-libur/{id}', [PresensiController::class, 'destroyHariLibur'])->name('presensi.destroyHariLibur');
    Route::get('/presensi/kartu-qr', [PresensiController::class, 'kartuQr'])->name('presensi.kartuQr');
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');
    // Modul Ekstrakurikuler & Daftar Anggota
    Route::get('/ekskul', [EkskulController::class, 'index'])->name('ekskul.index');
    Route::post('/ekskul', [EkskulController::class, 'store'])->name('ekskul.store');
    Route::put('/ekskul/{id}', [EkskulController::class, 'update'])->name('ekskul.update');
    Route::delete('/ekskul/{id}', [EkskulController::class, 'destroy'])->name('ekskul.destroy');
    Route::post('/ekskul/bulk-delete', [EkskulController::class, 'bulkDestroy'])->name('ekskul.bulkDestroy');

    // Dashboard Anggota per Ekskul (Sistem Daftar via Ceklis)
    Route::get('/ekskul/{id}/anggota', [EkskulController::class, 'show'])->name('ekskul.show');
    Route::post('/ekskul/{id}/anggota', [EkskulController::class, 'storeAnggota'])->name('ekskul.storeAnggota');
    Route::delete('/ekskul/anggota/{anggotaId}', [EkskulController::class, 'destroyAnggota'])->name('ekskul.destroyAnggota');
    Route::post('/ekskul/anggota/bulk-delete', [EkskulController::class, 'bulkDestroyAnggota'])->name('ekskul.bulkDestroyAnggota');
    // Tahap 6: Kenaikan Kelas Berurutan, Arsip Alumni, & Tombol Ajaib Pemeliharaan Sistem
    Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan.index');
    Route::post('/kenaikan-kelas/proses', [KenaikanKelasController::class, 'prosesNaikKelas'])->name('kenaikan.proses');
    Route::post('/kenaikan-kelas/alumni/bulk-delete', [KenaikanKelasController::class, 'bulkDeleteAlumni'])->name('kenaikan.bulkDeleteAlumni');
    Route::post('/kenaikan-kelas/magic-action', [KenaikanKelasController::class, 'magicAction'])->name('kenaikan.magicAction');
    Route::resource('jadwal', JadwalController::class);

    Route::resource('nav-menu', NavMenuController::class)->except(['create', 'show', 'edit']);
    Route::get('/nav-menu/{id}/halaman', [HalamanController::class, 'kelolaDariMenu'])->name('nav-menu.halaman');
    Route::resource('halaman', HalamanController::class)->except(['create', 'show']);

    // CRUD Berita & Studio AI
    Route::post('/berita/generate-ai', [BeritaController::class, 'generateAi'])->name('berita.generateAi');
    Route::resource('berita', BeritaController::class)->except(['show']);

    // CRUD Kategori Berita (Beserta Slug Otomatis)
    Route::post('/kategori-berita', [BeritaController::class, 'storeKategori'])->name('kategori-berita.store');
    Route::put('/kategori-berita/{id}', [BeritaController::class, 'updateKategori'])->name('kategori-berita.update');
    Route::delete('/kategori-berita/{id}', [BeritaController::class, 'destroyKategori'])->name('kategori-berita.destroy');

    Route::post('/banner-carousel/generate-ai', [BannerCarouselController::class, 'generateAi'])->name('banner-carousel.generateAi');
    Route::resource('banner-carousel', BannerCarouselController::class)->except(['create', 'show', 'edit']);
    // Manajemen Terpadu: Sambutan Kepala Madrasah, Profil, Visi, Misi & Motto
    Route::get('/sambutan-profil', [SambutanProfilController::class, 'index'])->name('sambutan-profil.index');
    Route::put('/sambutan-profil', [SambutanProfilController::class, 'update'])->name('sambutan-profil.update');
    Route::post('/sambutan-profil/generate-ai', [SambutanProfilController::class, 'generateAi'])->name('sambutan-profil.generateAi');

    // Di dalam grup Route::middleware(['auth'])->group(function () { ... }) tambahkan:
    Route::resource('dunia-ilmu-admin', DuniaIlmuController::class)->names('dunia-ilmu')->parameters(['dunia-ilmu-admin' => 'id'])->except(['create', 'show', 'edit']);
    // Di dalam grup Route::middleware(['auth'])->group(function () { ... }) tambahkan:
    Route::get('/profil-madrasah-admin', [ProfilMadrasahController::class, 'index'])->name('profil-madrasah.index');
    Route::put('/profil-madrasah-admin', [ProfilMadrasahController::class, 'update'])->name('profil-madrasah.update');
    Route::post('/profil-madrasah-admin/sinkron-menu', [ProfilMadrasahController::class, 'sinkronKeNavbar'])->name('profil-madrasah.sinkronMenu');
    // Di dalam grup Route::middleware(['auth'])->group(function () { ... }) tambahkan:
    Route::get('/kepala-staff-admin', [KepalaStaffController::class, 'index'])->name('kepala-staff.index');
    Route::put('/kepala-staff-admin', [KepalaStaffController::class, 'update'])->name('kepala-staff.update');
    // Di dalam grup Route::middleware(['auth'])->group(function () { ... }) tambahkan:
    Route::get('/guru-staff-admin', [GuruStaffController::class, 'index'])->name('guru-staff.index');
    Route::put('/guru-staff-admin', [GuruStaffController::class, 'update'])->name('guru-staff.update');

    // Manajemen Banner PPDB & Popup Modal
    Route::get('/banner-ppdb', [BannerPpdbController::class, 'index'])->name('banner-ppdb.index');
    Route::put('/banner-ppdb', [BannerPpdbController::class, 'update'])->name('banner-ppdb.update');

    // Manajemen Layanan Cepat (Quick Access Portal)
    Route::resource('layanan-cepat-admin', LayananCepatController::class)->names('layanan-cepat')->except(['create', 'show', 'edit']);
    Route::patch('/layanan-cepat-admin/{id}/toggle-status', [LayananCepatController::class, 'toggleStatus'])->name('layanan-cepat.toggle-status');
});

require __DIR__.'/auth.php';
