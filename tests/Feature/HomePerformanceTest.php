<?php

use App\Http\Controllers\HomeController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

test('landing page populates cache on first visit', function () {
    HomeController::bersihkanCache();

    $response = $this->get('/');

    $response->assertOk();
    expect(Cache::has(HomeController::CACHE_KEY))->toBeTrue();
});

test('landing page serves from cache and keeps it warm with touch on subsequent visit', function () {
    HomeController::bersihkanCache();

    // Kunjungan pertama
    $this->get('/')->assertOk();
    expect(Cache::has(HomeController::CACHE_KEY))->toBeTrue();

    // Kunjungan kedua (hot cache via Cache::touch)
    $response = $this->get('/');
    $response->assertOk();
    expect(Cache::has(HomeController::CACHE_KEY))->toBeTrue();
});

test('bersihkanCache removes cached landing page data', function () {
    Cache::put(HomeController::CACHE_KEY, ['test' => true], 60);
    expect(Cache::has(HomeController::CACHE_KEY))->toBeTrue();

    HomeController::bersihkanCache();
    expect(Cache::has(HomeController::CACHE_KEY))->toBeFalse();
});

test('landing page self-heals when cache is corrupt or invalid', function () {
    // Simpan data invalid ke dalam cache
    Cache::put(HomeController::CACHE_KEY, ['gtks' => 'invalid_string'], 60);

    $response = $this->get('/');
    $response->assertOk();

    $cached = Cache::get(HomeController::CACHE_KEY);
    expect($cached['gtks'])->toBeInstanceOf(Collection::class);
});
