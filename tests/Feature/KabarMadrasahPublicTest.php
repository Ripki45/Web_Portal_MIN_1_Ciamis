<?php

use App\Models\Berita;
use App\Models\Gtk;
use App\Models\KategoriBerita;

test('public can access kabar madrasah dedicated page and its aliases', function () {
    Berita::pastikanTabelSiap();
    KategoriBerita::pastikanTabelSiap();

    // Pastikan ada berita aktif
    Berita::firstOrCreate([
        'slug' => 'warta-kegiatan-literasi-ciamis',
    ], [
        'judul' => 'Warta Kegiatan Literasi Ciamis',
        'kategori' => 'Kegiatan Siswa',
        'ringkasan' => 'Ringkasan berita literasi.',
        'isi_konten' => '<p>Konten lengkap warta literasi madrasah.</p>',
        'status' => 'Tayang',
        'is_featured' => true,
        'views' => 50,
    ]);

    // Akses route utama
    $response = $this->get(route('berita.publik'));
    $response->assertOk();
    $response->assertSee('Kabar Madrasah &amp; Liputan Terkini', false);
    $response->assertSee('Warta Kegiatan Literasi Ciamis');

    // Akses alias /kabar
    $aliasKabar = $this->get('/kabar');
    $aliasKabar->assertOk();
    $aliasKabar->assertSee('Kabar Madrasah &amp; Liputan Terkini', false);

    // Akses alias /warta
    $aliasWarta = $this->get('/warta');
    $aliasWarta->assertOk();
    $aliasWarta->assertSee('Kabar Madrasah &amp; Liputan Terkini', false);
});

test('public page supports search query and category filtering', function () {
    Berita::pastikanTabelSiap();
    KategoriBerita::pastikanTabelSiap();

    Berita::updateOrCreate([
        'slug' => 'prestasi-olimpiade-matematika-sains',
    ], [
        'judul' => 'Siswa Raih Juara 1 Olimpiade Matematika dan Sains',
        'kategori' => 'Prestasi Madrasah',
        'ringkasan' => 'Santri berhasil menjuarai kompetisi bergengsi.',
        'isi_konten' => '<p>Detail prestasi membanggakan madrasah.</p>',
        'status' => 'Tayang',
        'is_featured' => false,
        'views' => 120,
    ]);

    // Filter kategori 'Prestasi Madrasah'
    $respKategori = $this->get(route('berita.publik', ['kategori' => 'Prestasi Madrasah']));
    $respKategori->assertOk();
    $respKategori->assertSee('Siswa Raih Juara 1 Olimpiade Matematika dan Sains');

    // Search query 'Olimpiade'
    $respSearch = $this->get(route('berita.publik', ['q' => 'Olimpiade']));
    $respSearch->assertOk();
    $respSearch->assertSee('Siswa Raih Juara 1 Olimpiade Matematika dan Sains');

    // Search query yang tidak ada menghasilkan empty state
    $respNotFound = $this->get(route('berita.publik', ['q' => 'KataKunciYangPastiTidakAdaXYZ123']));
    $respNotFound->assertOk();
    $respNotFound->assertSee('Kabar Tidak Ditemukan');
});

test('home page contains lihat selengkapnya button pointing to kabar madrasah', function () {
    $response = $this->get(route('home'));
    $response->assertOk();

    // Memastikan terdapat link ke halaman publik kabar madrasah
    $response->assertSee(route('berita.publik'));
    $response->assertSee('Lihat Seluruh Kabar Madrasah');
});

test('detail berita displays centered author photo, dynamic popular tags, and official min 1 ciamis banner', function () {
    Berita::pastikanTabelSiap();
    KategoriBerita::pastikanTabelSiap();

    // Buat data GTK untuk mencocokkan penulis
    $gtk = Gtk::firstOrCreate([
        'nama_lengkap' => 'Ust. Fauzi Rahman, S.Pd',
    ], [
        'nomor_induk' => '1987654321',
        'jenis_kelamin' => 'L',
        'status_kepegawaian' => 'PNS',
    ]);

    $berita = Berita::updateOrCreate([
        'slug' => 'pembukaan-kegiatan-ramadhan-di-min-1-ciamis',
    ], [
        'judul' => 'Pembukaan Kegiatan Ramadhan di MIN 1 Ciamis',
        'kategori' => 'Kegiatan Siswa',
        'ringkasan' => 'Kegiatan menyambut bulan suci penuh berkah.',
        'isi_konten' => '<p>Konten rincian agenda ramadhan siswa madrasah.</p>',
        'tags' => 'Ramadhan, Kegiatan Siswa, Pendidikan Karakter',
        'penulis' => 'Ust. Fauzi Rahman, S.Pd',
        'status' => 'Tayang',
        'views' => 10,
    ]);

    $response = $this->get(route('berita.show', $berita->slug));
    $response->assertOk();

    // 1. Author Card assertions
    $response->assertSee('Ust. Fauzi Rahman, S.Pd');
    $response->assertSee('author-card');

    // 2. Popular Tags / Kategori assertions
    $response->assertSee('Tag & Kategori Populer', false);
    $response->assertSee('Kegiatan Siswa');

    // 3. Official MIN 1 Ciamis Banner assertions
    $response->assertSee('MIN 1 CIAMIS');
    $response->assertSee('Madrasah Unggul, Ramah Anak');
    $response->assertSee('Pendidikan Berkarakter');
    $response->assertSee('Terakreditasi A');
    $response->assertSee('Sekolah Ramah Anak');
});
