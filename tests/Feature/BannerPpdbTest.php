<?php

use App\Http\Controllers\HomeController;
use App\Models\BannerPpdb;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('admin can access banner and popup ppdb dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('banner-ppdb.index'));

    $response->assertOk();
    $response->assertSee('Manajemen Banner PPDB');
});

test('admin can update banner and popup ppdb settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    BannerPpdb::pastikanTabelSiap();

    // Cache warm
    Cache::put(HomeController::CACHE_KEY, ['test' => true], 60);

    $response = $this->actingAs($admin)->put(route('banner-ppdb.update'), [
        'judul' => 'PPDB MIN 1 Ciamis 2026/2027 Dibuka',
        'subjudul' => 'Ayo Daftar Sekarang',
        'tipe_link' => 'popup',
        'link_url' => '#ppdb',
        'is_active' => '1',
        'is_active_popup' => '1',
        'auto_popup' => '1',
        'popup_judul' => 'Pendaftaran Resmi Siswa Baru',
        'popup_badge' => 'PPDB UNGGULAN',
        'popup_konten' => 'Syarat pendaftaran: Akta Kelahiran dan Kartu Keluarga.',
        'popup_tombol_teks' => 'Isi Formulir',
        'popup_tombol_url' => 'https://example.com/daftar',
        'popup_wa_nomor' => '081234567890',
        'popup_wa_teks' => 'Halo Panitia',
    ]);

    $response->assertRedirect(route('banner-ppdb.index'));
    $response->assertSessionHas('success');

    $setting = BannerPpdb::first();
    expect($setting->judul)->toBe('PPDB MIN 1 Ciamis 2026/2027 Dibuka')
        ->and($setting->popup_judul)->toBe('Pendaftaran Resmi Siswa Baru')
        ->and($setting->auto_popup)->toBeTrue()
        ->and(Cache::has(HomeController::CACHE_KEY))->toBeFalse(); // Cache invalidated
});

test('landing page displays dynamic ppdb banner', function () {
    BannerPpdb::pastikanTabelSiap();
    $ppdb = BannerPpdb::ambilData();
    $ppdb->update([
        'judul' => 'Banner Uji Coba Landing Page',
        'is_active' => true,
    ]);

    HomeController::bersihkanCache();

    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Banner Uji Coba Landing Page');
});

test('admin can update banner without text inputs and popup template renders properly', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    BannerPpdb::pastikanTabelSiap();

    $response = $this->actingAs($admin)->put(route('banner-ppdb.update'), [
        'tipe_link' => 'popup',
        'is_active' => '1',
        'is_active_popup' => '1',
        'popup_judul' => 'Pendaftaran Resmi Siswa Baru Multi-line',
        'popup_badge' => 'PPDB 2026',
        'popup_konten' => "Baris 1: Pendaftaran dibuka\nBaris 2: Syarat berkas lengkap\nBaris 3: Kuota terbatas",
    ]);

    $response->assertRedirect(route('banner-ppdb.index'));

    HomeController::bersihkanCache();

    $home = $this->get('/');
    $home->assertOk();
    $home->assertSee('ppdb-modal-content-template');
    $home->assertSee('Pendaftaran Resmi Siswa Baru Multi-line');
    $home->assertSee('Baris 1: Pendaftaran dibuka');
});
