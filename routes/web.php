<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\GtkController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MutasiSiswaController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PresensiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// ROUTE HALAMAN DEPAN PRESENSI (TANPA LOGIN: SCANNER & INPUT SAKIT/IZIN/ALFA GURU)
Route::get('/scan-presensi', [PresensiController::class, 'scanner'])->name('presensi.scanner');
Route::post('/scan-presensi/proses', [PresensiController::class, 'prosesScan'])->name('presensi.prosesScan');
Route::post('/scan-presensi/manual-guru', [PresensiController::class, 'storeManualDepan'])->name('presensi.storeManualDepan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('jabatan', JabatanController::class)->except(['create', 'show', 'edit']);
    // Route Master Posisi & AJAX Dropdown
    Route::resource('posisi', PosisiController::class)->except(['create', 'show', 'edit']);
    Route::get('/posisi/by-jabatan/{jabatan_id}', [PosisiController::class, 'getByJabatan'])->name('posisi.byJabatan');
    // Route Data Guru & Tendik
    Route::resource('gtk', GtkController::class)->except(['create', 'show', 'edit']);
    Route::post('/gtk/bulk-delete', [App\Http\Controllers\GtkController::class, 'bulkDestroy'])->name('gtk.bulkDestroy');
    // Route Master Tahun Ajaran & Semester
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['create', 'show', 'edit']);
    Route::patch('/tahun-ajaran/{id}/activate', [TahunAjaranController::class, 'setActive'])->name('tahun-ajaran.activate');
    // Route Manajemen Kelas
    Route::resource('kelas', KelasController::class)->except(['create', 'show', 'edit']);
    Route::get('/kelas/{id}/dashboard', [App\Http\Controllers\KelasController::class, 'show'])->name('kelas.show');
    Route::post('/kelas/plotting-mapel', [App\Http\Controllers\KelasController::class, 'storePlottingMapel'])->name('kelas.storePlottingMapel');
    Route::delete('/kelas/plotting-mapel/{id}', [App\Http\Controllers\KelasController::class, 'destroyPlottingMapel'])->name('kelas.destroyPlottingMapel');
    Route::post('/kelas/plotting-mapel/bulk-delete', [App\Http\Controllers\KelasController::class, 'bulkDestroyPlottingMapel'])->name('kelas.bulkDestroyPlottingMapel');
    // Route Data Siswa & Smart Import Excel
    Route::post('/siswa/import-paste', [SiswaController::class, 'importPaste'])->name('siswa.importPaste');
    Route::resource('siswa', SiswaController::class)->except(['create', 'show', 'edit']);
    Route::post('/siswa/bulk-delete', [App\Http\Controllers\SiswaController::class, 'bulkDestroy'])->name('siswa.bulkDestroy');
    // Route Kenaikan Kelas & Mutasi Siswa
    Route::get('/mutasi', [MutasiSiswaController::class, 'index'])->name('mutasi.index');
    Route::post('/mutasi/kenaikan', [MutasiSiswaController::class, 'prosesKenaikan'])->name('mutasi.kenaikan');
    Route::post('/mutasi/masuk', [MutasiSiswaController::class, 'storeMasuk'])->name('mutasi.storeMasuk');
    Route::post('/mutasi/keluar', [MutasiSiswaController::class, 'storeKeluar'])->name('mutasi.storeKeluar');
    Route::delete('/mutasi/{id}', [MutasiSiswaController::class, 'destroy'])->name('mutasi.destroy');
    // Route Master Mata Pelajaran
    Route::post('/mapel/generate-default', [MataPelajaranController::class, 'generateDefault'])->name('mapel.generate');
    Route::resource('mapel', MataPelajaranController::class)->except(['create', 'show', 'edit']);
    Route::post('/mapel/bulk-delete', [App\Http\Controllers\MataPelajaranController::class, 'bulkDestroy'])->name('mapel.bulkDestroy');
    // Route Presensi Admin, Pengaturan Jam, & Kalender Libur
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/manual', [PresensiController::class, 'storeManual'])->name('presensi.storeManual');
    Route::post('/presensi/setting-jam', [PresensiController::class, 'updateSettingJam'])->name('presensi.updateSettingJam');
    Route::post('/presensi/hari-libur', [PresensiController::class, 'storeHariLibur'])->name('presensi.storeHariLibur');
    Route::delete('/presensi/hari-libur/{id}', [PresensiController::class, 'destroyHariLibur'])->name('presensi.destroyHariLibur');
    Route::get('/presensi/kartu-qr', [PresensiController::class, 'kartuQr'])->name('presensi.kartuQr');
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');
    // Modul Ekstrakurikuler & Daftar Anggota
    Route::get('/ekskul', [App\Http\Controllers\EkskulController::class, 'index'])->name('ekskul.index');
    Route::post('/ekskul', [App\Http\Controllers\EkskulController::class, 'store'])->name('ekskul.store');
    Route::put('/ekskul/{id}', [App\Http\Controllers\EkskulController::class, 'update'])->name('ekskul.update');
    Route::delete('/ekskul/{id}', [App\Http\Controllers\EkskulController::class, 'destroy'])->name('ekskul.destroy');
    Route::post('/ekskul/bulk-delete', [App\Http\Controllers\EkskulController::class, 'bulkDestroy'])->name('ekskul.bulkDestroy');

    // Dashboard Anggota per Ekskul (Sistem Daftar via Ceklis)
    Route::get('/ekskul/{id}/anggota', [App\Http\Controllers\EkskulController::class, 'show'])->name('ekskul.show');
    Route::post('/ekskul/{id}/anggota', [App\Http\Controllers\EkskulController::class, 'storeAnggota'])->name('ekskul.storeAnggota');
    Route::delete('/ekskul/anggota/{anggotaId}', [App\Http\Controllers\EkskulController::class, 'destroyAnggota'])->name('ekskul.destroyAnggota');
    Route::post('/ekskul/anggota/bulk-delete', [App\Http\Controllers\EkskulController::class, 'bulkDestroyAnggota'])->name('ekskul.bulkDestroyAnggota');
    // Tahap 6: Kenaikan Kelas Berurutan, Arsip Alumni, & Tombol Ajaib Pemeliharaan Sistem
    Route::get('/kenaikan-kelas', [App\Http\Controllers\KenaikanKelasController::class, 'index'])->name('kenaikan.index');
    Route::post('/kenaikan-kelas/proses', [App\Http\Controllers\KenaikanKelasController::class, 'prosesNaikKelas'])->name('kenaikan.proses');
    Route::post('/kenaikan-kelas/alumni/bulk-delete', [App\Http\Controllers\KenaikanKelasController::class, 'bulkDeleteAlumni'])->name('kenaikan.bulkDeleteAlumni');
    Route::post('/kenaikan-kelas/magic-action', [App\Http\Controllers\KenaikanKelasController::class, 'magicAction'])->name('kenaikan.magicAction');
});

require __DIR__.'/auth.php';
