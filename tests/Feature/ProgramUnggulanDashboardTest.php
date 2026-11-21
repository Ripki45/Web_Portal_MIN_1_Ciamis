<?php

use App\Http\Controllers\HomeController;
use App\Models\ProgramUnggulan;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('guest cannot access program unggulan dashboard', function () {
    $response = $this->get(route('program-unggulan.index'));
    $response->assertRedirect(route('login'));
});

test('admin can access program unggulan index page and sees seeded programs', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('program-unggulan.index'));

    $response->assertOk();
    $response->assertSee('Manajemen Program Unggulan Madrasah');
    $response->assertSee('Sekolah Ramah Anak (SRA)');
    $response->assertSee('Tahfidz');
});

test('admin can view create program unggulan page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('program-unggulan.create'));

    $response->assertOk();
    $response->assertSee('Tambah Program Unggulan Baru');
    $response->assertSee('Ikon Program (Font Awesome 6)');
});

test('admin can store new program unggulan with features and rich content', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    ProgramUnggulan::pastikanTabelSiap();

    Cache::put(HomeController::CACHE_KEY, ['test' => true], 60);

    $response = $this->actingAs($admin)->post(route('program-unggulan.store'), [
        'judul' => 'Adiwiyata Mandiri & Budaya Lingkungan Hidup',
        'kategori_badge' => 'Wawasan Lingkungan',
        'icon' => 'fas fa-leaf',
        'ringkasan' => 'Edukasi pelestarian lingkungan hidup, bank sampah, komposting, dan penanaman pohon.',
        'kutipan' => 'Mencintai bumi sebagai wujud syukur atas nikmat dan keindahan ciptaan Allah SWT.',
        'keunggulan_poin' => "Pengelolaan Bank Sampah Mandiri\nProgram Satu Santri Satu Pohon\nGreenhouse & Hidroponik Edukatif",
        'isi_konten' => '<h4>1. Gerakan Peduli Lingkungan</h4><p>Program Adiwiyata MIN 1 Ciamis mendidik generasi peduli bumi.</p>',
        'penulis' => 'Tim Adiwiyata',
        'urutan' => 5,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('program-unggulan.index'));
    $response->assertSessionHas('success');

    $created = ProgramUnggulan::where('judul', 'Adiwiyata Mandiri & Budaya Lingkungan Hidup')->first();
    expect($created)->not->toBeNull()
        ->and($created->slug)->toBe('adiwiyata-mandiri-budaya-lingkungan-hidup')
        ->and($created->poin_list)->toHaveCount(3)
        ->and($created->poin_list[0])->toBe('Pengelolaan Bank Sampah Mandiri')
        ->and(Cache::has(HomeController::CACHE_KEY))->toBeFalse();
});

test('admin can view edit page and update program unggulan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    ProgramUnggulan::pastikanTabelSiap();

    $program = ProgramUnggulan::first();

    $editResponse = $this->actingAs($admin)->get(route('program-unggulan.edit', $program->id));
    $editResponse->assertOk();
    $editResponse->assertSee($program->judul);

    $updateResponse = $this->actingAs($admin)->put(route('program-unggulan.update', $program->id), [
        'judul' => 'Sekolah Ramah Anak Unggulan Terpadu',
        'kategori_badge' => 'Inklusif & SRA',
        'icon' => 'fas fa-child-reaching',
        'ringkasan' => 'Deskripsi ringkas terbarukan.',
        'kutipan' => 'Kutipan kasih sayang.',
        'keunggulan_poin' => "Konseling Psikolog Anak\nAnti Perundungan",
        'isi_konten' => '<p>Konten revisi.</p>',
        'penulis' => 'Humas MIN 1 Ciamis',
        'urutan' => 1,
        'is_active' => '1',
    ]);

    $updateResponse->assertRedirect(route('program-unggulan.index'));
    $updateResponse->assertSessionHas('success');

    $program->refresh();
    expect($program->judul)->toBe('Sekolah Ramah Anak Unggulan Terpadu')
        ->and($program->poin_list)->toHaveCount(2);
});

test('admin can toggle status of program unggulan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    ProgramUnggulan::pastikanTabelSiap();

    $program = ProgramUnggulan::first();
    $originalStatus = $program->is_active;

    $response = $this->actingAs($admin)->patch(route('program-unggulan.toggle-status', $program->id));
    $response->assertRedirect();

    $program->refresh();
    expect($program->is_active)->toBe(! $originalStatus);
});

test('admin can delete program unggulan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    ProgramUnggulan::pastikanTabelSiap();

    $program = ProgramUnggulan::create([
        'judul' => 'Program Percobaan Dihapus',
        'slug' => 'program-percobaan-dihapus',
        'kategori_badge' => 'Uji Coba',
        'icon' => 'fas fa-trash',
        'ringkasan' => 'Akan dihapus',
        'urutan' => 99,
        'is_active' => false,
    ]);

    $id = $program->id;

    $response = $this->actingAs($admin)->delete(route('program-unggulan.destroy', $id));
    $response->assertRedirect(route('program-unggulan.index'));
    $response->assertSessionHas('success');

    expect(ProgramUnggulan::find($id))->toBeNull();
});

test('admin can store and public can see rich 3-section program unggulan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    ProgramUnggulan::pastikanTabelSiap();

    $postData = [
        'judul' => 'Inkubasi Riset & Robotika Cilik',
        'kategori_badge' => 'Teknologi & Riset',
        'icon' => 'fas fa-robot',
        'ringkasan' => 'Pelatihan logika komputasi, perakitan robotika sederhana, dan riset madrasah.',
        'kutipan' => 'Membangun generasi madrasah penemu masa depan dengan teknologi dan akhlak.',
        'mini_feature_icon' => ['fas fa-microchip', 'fas fa-laptop-code'],
        'mini_feature_title' => ['Logika Algoritma', 'Praktik Robotika'],
        'mini_feature_desc' => ['Pengenalan koding ramah anak.', 'Perakitan kit robotika sederhana.'],
        'pilar_icon' => ['fas fa-brain', 'fas fa-cogs', 'fas fa-award', 'fas fa-users'],
        'pilar_title' => ['1. Berpikir Kritis', '2. Eksperimen Mandiri', '3. Kompetisi Inovasi', '4. Kolaborasi Tim'],
        'pilar_desc' => ['Mengasah nalar.', 'Praktikum langsung.', 'Ajang lomba riset.', 'Kerja sama santri.'],
        'kegiatan_judul' => 'Kegiatan Laboratorium Robotika & Koding Madrasah',
        'kegiatan_badge' => 'Eksplorasi Sains Digital',
        'kegiatan_deskripsi' => 'Dokumentasi agenda riset teknologi cilik madrasah.',
        'kegiatan_card_badge' => ['Robot Cilik', 'Algoritma'],
        'kegiatan_card_title' => ['Perakitan Line Follower', 'Dasar Logika Blockly'],
        'kegiatan_card_desc' => ['Siswa merakit sensor robotik sederhana.', 'Membuat animasi interaktif dengan blok kode.'],
        'isi_konten' => '<p>Kegiatan robotika dilaksanakan setiap pekan di Laboratorium Komputer MIN 1 Ciamis.</p>',
        'penulis' => 'Tim Pembina Robotika',
        'urutan' => 10,
        'is_active' => '1',
    ];

    $response = $this->actingAs($admin)->post(route('program-unggulan.store'), $postData);
    $response->assertRedirect(route('program-unggulan.index'));

    $program = ProgramUnggulan::where('judul', 'Inkubasi Riset & Robotika Cilik')->first();
    expect($program)->not->toBeNull();
    expect($program->mini_features_list)->toHaveCount(2);
    expect($program->pilar_list_formatted)->toHaveCount(4);
    expect($program->kegiatan_judul_formatted)->toBe('Kegiatan Laboratorium Robotika & Koding Madrasah');
    expect($program->kegiatan_list_data)->toHaveCount(2);
    expect($program->kegiatan_list_data[0]['title'])->toBe('Perakitan Line Follower');

    // Cek tampilan publik
    $publicResp = $this->get(route('program-unggulan.detail', $program->slug));
    $publicResp->assertOk();
    $publicResp->assertSee('Logika Algoritma');
    $publicResp->assertSee('Praktik Robotika');
    $publicResp->assertSee('1. Berpikir Kritis');
    $publicResp->assertSee('Kegiatan Laboratorium Robotika &amp; Koding Madrasah', false);
    $publicResp->assertSee('Eksplorasi Sains Digital');
    $publicResp->assertSee('Robot Cilik');
    $publicResp->assertSee('Perakitan Line Follower');
});

test('admin can generate program narrative using smart ai studio', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson(route('program-unggulan.generateAi'), [
        'topik' => 'Sekolah Ramah Anak dan Anti-Bullying',
        'kategori' => 'Inklusif & SRA',
        'engine' => 'smart_local',
    ]);

    $response->assertOk();
    $response->assertJson([
        'status' => 'success',
    ]);
    expect($response->json('data.ringkasan'))->toContain('Sekolah Ramah Anak');
    expect($response->json('data.kutipan'))->not->toBeEmpty();
});
