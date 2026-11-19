<?php

use App\Http\Controllers\HomeController;
use App\Models\LayananCepat;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('admin can access layanan cepat dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('layanan-cepat.index'));

    $response->assertOk();
    $response->assertSee('Manajemen Layanan Cepat');
});

test('admin can store new layanan cepat with font awesome icon and link', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    LayananCepat::pastikanTabelSiap();

    Cache::put(HomeController::CACHE_KEY, ['test' => true], 60);

    $response = $this->actingAs($admin)->post(route('layanan-cepat.store'), [
        'nama_layanan' => 'Perpustakaan Digital Madrasah',
        'icon' => 'fas fa-book-open',
        'link_url' => 'https://perpus.min1ciamis.sch.id',
        'target' => '_blank',
        'urutan' => 15,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('layanan-cepat.index'));
    $response->assertSessionHas('success');

    expect(LayananCepat::where('nama_layanan', 'Perpustakaan Digital Madrasah')->exists())->toBeTrue()
        ->and(Cache::has(HomeController::CACHE_KEY))->toBeFalse();
});

test('admin can update and toggle layanan cepat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    LayananCepat::pastikanTabelSiap();

    $layanan = LayananCepat::first();

    // Update
    $response = $this->actingAs($admin)->put(route('layanan-cepat.update', $layanan->id), [
        'nama_layanan' => 'QR Code Scanner Presensi Baru',
        'icon' => 'fas fa-barcode',
        'link_url' => '/presensi-scanner-v2',
        'target' => '_self',
        'urutan' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('layanan-cepat.index'));
    expect($layanan->fresh()->nama_layanan)->toBe('QR Code Scanner Presensi Baru')
        ->and($layanan->fresh()->icon)->toBe('fas fa-barcode');

    // Toggle
    $this->actingAs($admin)->patch(route('layanan-cepat.toggle-status', $layanan->id));
    expect($layanan->fresh()->is_active)->toBeFalse();
});

test('admin can delete layanan cepat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    LayananCepat::pastikanTabelSiap();

    $item = LayananCepat::create([
        'nama_layanan' => 'Layanan Untuk Dihapus',
        'icon' => 'fas fa-trash',
        'link_url' => 'https://example.com/delete',
        'target' => '_blank',
        'urutan' => 99,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->delete(route('layanan-cepat.destroy', $item->id));

    $response->assertRedirect(route('layanan-cepat.index'));
    expect(LayananCepat::find($item->id))->toBeNull();
});

test('landing page renders dynamic layanan cepat from database and links to public page', function () {
    LayananCepat::pastikanTabelSiap();
    HomeController::bersihkanCache();

    $response = $this->get('/');

    $response->assertOk();
    $first = LayananCepat::where('is_active', true)->orderBy('urutan', 'asc')->first();
    $response->assertSee($first->nama_layanan);
    $response->assertSee(route('layanan-cepat.publik'));
});

test('public user can access dedicated layanan cepat page', function () {
    LayananCepat::pastikanTabelSiap();

    $response = $this->get(route('layanan-cepat.publik'));

    $response->assertOk();
    $response->assertSee('Direktori Layanan &amp; Integrasi Digital', false);

    $aliasResponse = $this->get('/layanan');
    $aliasResponse->assertOk();
});
