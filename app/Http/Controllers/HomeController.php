<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Ekskul;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\BannerCarousel;

class HomeController extends Controller
{
    public function index()
    {
        BannerCarousel::pastikanTabelSiap();
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        // Ambil 4 Slide Carousel Banner Aktif
        $banners = BannerCarousel::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();

        $gtks        = Gtk::with(['jabatan', 'posisi'])->orderBy('id', 'asc')->get();
        $ekskuls     = Ekskul::with(['pembina', 'anggotas'])->where('status', 'Aktif')->get();
        $totalSiswa  = Siswa::where('status', 'Aktif')->count();
        $totalGuru   = $gtks->count();
        $totalKelas  = Kelas::count();
        $totalEkskul = $ekskuls->count();

        $semuaBerita   = Berita::where('status', 'Tayang')->orderBy('is_featured', 'desc')->orderBy('id', 'desc')->take(3)->get();
        $beritaUtama   = $semuaBerita->first();
        $beritaSamping = $semuaBerita->slice(1, 2);

        $warnaKategori = KategoriBerita::pluck('warna_badge', 'nama_kategori')->toArray();

        return view('frontend.home', compact(
            'banners', 'gtks', 'ekskuls', 'totalSiswa', 'totalGuru', 'totalKelas', 'totalEkskul',
            'beritaUtama', 'beritaSamping', 'warnaKategori'
        ));
    }
}