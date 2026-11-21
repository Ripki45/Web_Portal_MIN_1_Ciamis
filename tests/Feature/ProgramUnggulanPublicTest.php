<?php

use App\Models\ProgramUnggulan;

test('public can access dedicated program unggulan listing page and alias', function () {
    ProgramUnggulan::pastikanTabelSiap();

    $response = $this->get(route('program-unggulan.publik'));
    $response->assertOk();
    $response->assertSee('Katalog Program Unggulan MIN 1 Ciamis');
    $response->assertSee('Sekolah Ramah Anak (SRA)');

    // Test alias route /program
    $aliasResponse = $this->get('/program');
    $aliasResponse->assertOk();
    $aliasResponse->assertSee('Katalog Program Unggulan MIN 1 Ciamis');
});

test('public listing supports filtering by category and search query', function () {
    ProgramUnggulan::pastikanTabelSiap();

    $respCat = $this->get(route('program-unggulan.publik', ['kategori' => 'Inklusif & Ramah Anak']));
    $respCat->assertOk();
    $respCat->assertSee('Sekolah Ramah Anak (SRA)');

    $respSearch = $this->get(route('program-unggulan.publik', ['q' => 'Tahfidz']));
    $respSearch->assertOk();
    $respSearch->assertSee('Tahfidz');
});

test('home page displays dynamic program unggulan cards and lihat selengkapnya button', function () {
    ProgramUnggulan::pastikanTabelSiap();

    $response = $this->get(route('home'));
    $response->assertOk();

    // Link lihat selengkapnya
    $response->assertSee(route('program-unggulan.publik'));
    $response->assertSee('Lihat Seluruh Program Unggulan');
});

test('public can access detail page of program unggulan', function () {
    ProgramUnggulan::pastikanTabelSiap();

    $program = ProgramUnggulan::where('slug', 'sekolah-ramah-anak')->first();
    expect($program)->not->toBeNull();

    $initialViews = $program->views;

    $response = $this->get(route('program-unggulan.detail', $program->slug));
    $response->assertOk();
    $response->assertSee($program->judul);
    $response->assertSee('Pilar');
    $response->assertSee('Dokumentasi Gerakan');

    $program->refresh();
    expect($program->views)->toBe($initialViews + 1);
});

test('detail page shows sidebar with other programs and returns 404 for non-existent slug', function () {
    ProgramUnggulan::pastikanTabelSiap();

    $program = ProgramUnggulan::where('slug', 'sekolah-ramah-anak')->first();

    $response = $this->get(route('program-unggulan.detail', $program->slug));
    $response->assertOk();
    $response->assertSee('Program Unggulan Lainnya');
    $response->assertSee('Data Real-Time Madrasah');

    // 404 test
    $notFoundResponse = $this->get(route('program-unggulan.detail', 'slug-tidak-terdaftar-404'));
    $notFoundResponse->assertNotFound();
});
