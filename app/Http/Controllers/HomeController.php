<?php

namespace App\Http\Controllers;

use App\Models\BannerCarousel;
use App\Models\BannerPpdb;
use App\Models\Berita;
use App\Models\Ekskul;
use App\Models\Gtk;
use App\Models\KategoriBerita;
use App\Models\Kelas;
use App\Models\LayananCepat;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Kunci Cache Data Landing Page
     */
    public const CACHE_KEY = 'min1_landing_page_data_v1';

    /**
     * Durasi TTL Cache (30 Menit)
     */
    public const CACHE_TTL = 1800;

    /**
     * Menampilkan Landing Page dengan Optimasi Performa Cache::touch() (Laravel 13)
     */
    public function index(): View
    {
        // 1. Cek apakah dataset landing page sudah ada di cache
        if (Cache::has(self::CACHE_KEY)) {
            $data = Cache::get(self::CACHE_KEY);

            // Validasi integritas cache: jika data valid dan merupakan Collection yang utuh (bukan incomplete class)
            if (is_array($data) && isset($data['gtks']) && ($data['gtks'] instanceof Collection)) {
                // Perpanjang masa aktif cache secara atomik menggunakan fitur baru Laravel 13 Cache::touch()
                Cache::touch(self::CACHE_KEY, self::CACHE_TTL);
            } else {
                // Jika cache corrupt atau kelas belum ter-deserialisasi sempurna, regenerasi dari database
                $data = $this->ambilDataLandingPage();
                Cache::put(self::CACHE_KEY, $data, self::CACHE_TTL);
            }

            // Validasi integritas cache: jika data valid dan merupakan Collection yang utuh (bukan incomplete class)
            if (is_array($data) && isset($data['gtks']) && ($data['gtks'] instanceof Collection)) {
                // Perpanjang masa aktif cache secara atomik menggunakan fitur baru Laravel 13 Cache::touch()
                Cache::touch(self::CACHE_KEY, self::CACHE_TTL);
            } else {
                // Jika cache corrupt atau kelas belum ter-deserialisasi sempurna, regenerasi dari database
                $data = $this->ambilDataLandingPage();
                Cache::put(self::CACHE_KEY, $data, self::CACHE_TTL);
            }
        } else {
            // Jika cache belum tersedia atau baru kedaluwarsa, ambil dari database dan simpan
            $data = $this->ambilDataLandingPage();
            Cache::put(self::CACHE_KEY, $data, self::CACHE_TTL);
        }

        return view('frontend.home', $data);
    }

    /**
     * Menghimpun seluruh data dinamis yang dibutuhkan halaman depan
     *
     * @return array<string, mixed>
     */
    protected function ambilDataLandingPage(): array
    {
        BannerCarousel::pastikanTabelSiap();
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();
        BannerPpdb::pastikanTabelSiap();
        LayananCepat::pastikanTabelSiap();

        $bannerPpdb = BannerPpdb::ambilData();
        $layananCepats = LayananCepat::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Ambil 4 Slide Carousel Banner Aktif
        $banners = BannerCarousel::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();

        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('id', 'asc')->get();
        $ekskuls = Ekskul::with(['pembina', 'anggotas'])->where('status', 'Aktif')->get();
        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        $totalGuru = $gtks->count();
        $totalKelas = Kelas::count();
        $totalEkskul = $ekskuls->count();

        $semuaBerita = Berita::where('status', 'Tayang')
            ->orderBy('is_featured', 'desc')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        $beritaUtama = $semuaBerita->first();
        $beritaSamping = $semuaBerita->slice(1, 2);

        $warnaKategori = KategoriBerita::pluck('warna_badge', 'nama_kategori')->toArray();

        return compact(
            'banners', 'gtks', 'ekskuls', 'totalSiswa', 'totalGuru', 'totalKelas', 'totalEkskul',
            'beritaUtama', 'beritaSamping', 'warnaKategori', 'bannerPpdb', 'layananCepats'
        );
    }

    /**
     * Invalidation helper: Dipanggil saat ada pembaruan banner, berita, atau data madrasah
     */
    public static function bersihkanCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
