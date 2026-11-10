<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Ekskul;
use App\Models\Berita;
use App\Models\KategoriBerita;

class HomeController extends Controller
{
    public function index()
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $gtks        = Gtk::with(['jabatan', 'posisi'])->orderBy('id', 'asc')->get();
        $ekskuls     = Ekskul::with(['pembina', 'anggotas'])->where('status', 'Aktif')->get();
        $totalSiswa  = Siswa::where('status', 'Aktif')->count();
        $totalGuru   = $gtks->count();
        $totalKelas  = Kelas::count();
        $totalEkskul = $ekskuls->count();

        // Ambil Berita Tayang: 1 Berita Utama (Kiri Besar) + 2 Berita Terbaru (Kanan Bersusun)
        $semuaBerita   = Berita::where('status', 'Tayang')->orderBy('is_featured', 'desc')->orderBy('id', 'desc')->take(3)->get();
        $beritaUtama   = $semuaBerita->first();
        $beritaSamping = $semuaBerita->slice(1, 2);

        // Map warna badge per kategori
        $warnaKategori = KategoriBerita::pluck('warna_badge', 'nama_kategori')->toArray();

        return view('frontend.home', compact(
            'gtks', 'ekskuls', 'totalSiswa', 'totalGuru', 'totalKelas', 'totalEkskul',
            'beritaUtama', 'beritaSamping', 'warnaKategori'
        ));
    }
}