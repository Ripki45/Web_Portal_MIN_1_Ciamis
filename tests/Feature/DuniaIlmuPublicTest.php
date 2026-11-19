<?php

use App\Models\DuniaIlmu;
use App\Models\User;

test('public can access dunia ilmu dedicated page and alias', function () {
    DuniaIlmu::pastikanTabelSiap();

    // Pastikan ada setidaknya satu record aktif
    DuniaIlmu::firstOrCreate([
        'slug' => 'simulasi-sains-tata-surya',
    ], [
        'judul' => 'Simulasi Sains Tata Surya',
        'kelas' => 'Kelas 5',
        'mapel' => 'IPAS (Sains & Sosial)',
        'kategori_label' => 'IPAS • Kelas 5',
        'kategori_filter' => 'ipas',
        'warna_gradasi' => 'dunia-grad-phet',
        'tipe_sumber' => 'link',
        'link_eksternal' => 'https://phet.colorado.edu',
        'deskripsi' => 'Simulasi peredaran planet dan tata surya interaktif.',
        'urutan' => 1,
        'views' => 10,
        'is_active' => true,
    ]);

    $response = $this->get(route('dunia-ilmu.publik'));
    $response->assertOk();
    $response->assertSee('Dunia Ilmu MIN 1 Ciamis');
    $response->assertSee('Pilih Tingkat Kelas');
    $response->assertSee('Simulasi Sains Tata Surya');

    // Cek alias route /media-pembelajaran
    $aliasResponse = $this->get('/media-pembelajaran');
    $aliasResponse->assertOk();
    $aliasResponse->assertSee('Dunia Ilmu MIN 1 Ciamis');
});

test('public page supports filtering by kelas, mapel, and search query', function () {
    DuniaIlmu::pastikanTabelSiap();

    DuniaIlmu::updateOrCreate([
        'slug' => 'kuis-fikih-wudhu',
    ], [
        'judul' => 'Kuis Fikih Tata Cara Berwudhu',
        'kelas' => 'Kelas 2',
        'mapel' => 'Fikih',
        'kategori_label' => 'Fikih • Kelas 2',
        'kategori_filter' => 'fikih',
        'warna_gradasi' => 'dunia-grad-hadits',
        'tipe_sumber' => 'link',
        'link_eksternal' => 'https://quizizz.com',
        'deskripsi' => 'Kuis interaktif rukun dan syarat wudhu.',
        'urutan' => 2,
        'views' => 25,
        'is_active' => true,
    ]);

    // Filter Kelas 2
    $respKelas = $this->get(route('dunia-ilmu.publik', ['kelas' => 'Kelas 2']));
    $respKelas->assertOk();
    $respKelas->assertSee('Kuis Fikih Tata Cara Berwudhu');

    // Filter Mapel Fikih
    $respMapel = $this->get(route('dunia-ilmu.publik', ['mapel' => 'Fikih']));
    $respMapel->assertOk();
    $respMapel->assertSee('Kuis Fikih Tata Cara Berwudhu');

    // Search Query 'Berwudhu'
    $respSearch = $this->get(route('dunia-ilmu.publik', ['q' => 'Berwudhu']));
    $respSearch->assertOk();
    $respSearch->assertSee('Kuis Fikih Tata Cara Berwudhu');
});

test('mobile bottom nav and home page link to dedicated dunia ilmu page', function () {
    $response = $this->get(route('home'));
    $response->assertOk();

    // Link di mobile bottom nav
    $response->assertSee(route('dunia-ilmu.publik'));

    // Tombol Katalog Lengkap di home
    $response->assertSee('Katalog Lengkap');
});

test('admin route for dunia ilmu index requires authentication', function () {
    // Tamu tanpa login diarahkan ke login
    $guestResponse = $this->get(route('dunia-ilmu.index'));
    $guestResponse->assertRedirect(route('login'));

    // Admin login bisa mengakses
    $admin = User::factory()->create(['role' => 'admin']);
    $authResponse = $this->actingAs($admin)->get(route('dunia-ilmu.index'));
    $authResponse->assertOk();
    $authResponse->assertSee('Lihat Halaman Publik Dunia Ilmu');
});
