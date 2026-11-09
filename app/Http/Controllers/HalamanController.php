<?php

namespace App\Http\Controllers;

use App\Models\Halaman;
use App\Models\NavMenu;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HalamanController extends Controller
{
    /**
     * TAMPILAN PUBLIK (FRONTEND) - Kerangka single-3.html
     */
    public function show($slug)
    {
        Halaman::pastikanTabelSiap();

        $halaman = Halaman::with('navMenu.parent')->where('slug', $slug)->first();

        // Jika menu sudah dibuat tapi konten halamannya belum diisi, jangan error 404! Buatkan otomatis:
        if (!$halaman) {
            $cariMenu = NavMenu::where('url', '/halaman/' . $slug)->first();
            $judulOtomatis = $cariMenu ? $cariMenu->nama_menu : ucwords(str_replace('-', ' ', $slug));

            $halaman = Halaman::create([
                'nav_menu_id' => $cariMenu->id ?? null,
                'judul'       => $judulOtomatis,
                'slug'        => $slug,
                'kategori'    => 'Informasi Madrasah',
                'kutipan'     => 'Halaman informasi resmi ' . $judulOtomatis . ' — MIN 1 Ciamis.',
                'isi_konten'  => '<p>Konten untuk halaman <strong>' . e($judulOtomatis) . '</strong> sedang dalam tahap pembaruan oleh tim pengelola website MIN 1 Ciamis.</p>',
                'penulis'     => 'Admin MIN 1 Ciamis',
                'views'       => 1,
                'is_active'   => true,
            ]);
        }

        // Tambah counter pembaca (views odometer)
        $halaman->increment('views');

        // Ambil daftar halaman lain untuk bagian Sidebar "Halaman Terkait" & "Rekomendasi"
        $halamanLain = Halaman::where('id', '!=', $halaman->id)
            ->where('is_active', true)
            ->latest('updated_at')
            ->take(4)
            ->get();

        // Ambil sub-menu satu induk (jika halaman ini terhubung ke menu dropdown)
        $menuSeinduk = collect();
        if ($halaman->navMenu && $halaman->navMenu->parent_id) {
            $menuSeinduk = NavMenu::where('parent_id', $halaman->navMenu->parent_id)
                ->where('is_active', true)
                ->orderBy('urutan', 'asc')
                ->get();
        }

        // Statistik ringkas untuk Tag Odometer di Sidebar Kanan
        $statMadrasah = [
            'Siswa Aktif'      => Siswa::where('status', 'Aktif')->count(),
            'Guru & Tendik'    => Gtk::count(),
            'Rombel Kelas'     => Kelas::count(),
            'Ekstrakurikuler'  => Ekskul::where('status', 'Aktif')->count(),
        ];

        return view('frontend.page', compact('halaman', 'halamanLain', 'menuSeinduk', 'statMadrasah'));
    }

    /**
     * DASHBOARD ADMIN: Daftar Semua Halaman Statis
     */
    public function index()
    {
        Halaman::pastikanTabelSiap();

        $halamans = Halaman::with('navMenu')->orderBy('id', 'desc')->get();
        $menus    = NavMenu::orderBy('level', 'asc')->orderBy('urutan', 'asc')->get();

        return view('halaman.index', compact('halamans', 'menus'));
    }

    /**
     * JEMBATAN OTOMATIS DARI TABEL MENU NAVBAR -> LANGSUNG BUKA EDITOR HALAMAN
     */
    public function kelolaDariMenu($menuId)
    {
        Halaman::pastikanTabelSiap();

        $menu = NavMenu::with('parent')->findOrFail($menuId);

        // Cek apakah menu ini sudah punya halaman
        $halaman = Halaman::where('nav_menu_id', $menu->id)->first();

        if (!$halaman) {
            $baseSlug = Str::slug($menu->nama_menu) ?: 'halaman-' . $menu->id;
            $slug     = $baseSlug;
            $counter  = 1;
            while (Halaman::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $halaman = Halaman::create([
                'nav_menu_id' => $menu->id,
                'judul'       => $menu->nama_menu,
                'slug'        => $slug,
                'kategori'    => $menu->parent->nama_menu ?? 'Profil Madrasah',
                'kutipan'     => '',
                'isi_konten'  => '<h4>' . e($menu->nama_menu) . '</h4><p>Tuliskan isi lengkap halaman ' . e($menu->nama_menu) . ' di sini...</p>',
                'penulis'     => auth()->user()->name ?? 'Admin MIN 1 Ciamis',
                'views'       => 1,
                'is_active'   => true,
            ]);
        }

        // Otomatis arahkan URL pada NavMenu ke /halaman/{slug}
        $menu->update(['url' => '/halaman/' . $halaman->slug]);

        return redirect()->route('halaman.edit', $halaman->id)
            ->with('success', 'Menu "' . $menu->nama_menu . '" telah dihubungkan ke halaman /halaman/' . $halaman->slug . '. Silakan isi kontennya!');
    }

    public function edit($id)
    {
        Halaman::pastikanTabelSiap();

        $halaman = Halaman::findOrFail($id);
        $menus   = NavMenu::orderBy('level', 'asc')->orderBy('urutan', 'asc')->get();

        return view('halaman.edit', compact('halaman', 'menus'));
    }

    public function store(Request $request)
    {
        Halaman::pastikanTabelSiap();

        $request->validate([
            'judul'       => 'required|string|max:200',
            'kategori'    => 'nullable|string|max:100',
            'kutipan'     => 'nullable|string',
            'isi_konten'  => 'required|string',
            'nav_menu_id' => 'nullable|exists:nav_menus,id',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $baseSlug = Str::slug($request->judul) ?: 'halaman-' . time();
        $slug     = $baseSlug;
        $counter  = 1;
        while (Halaman::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('halaman', 'public');
        }

        $halaman = Halaman::create([
            'nav_menu_id' => $request->nav_menu_id ?: null,
            'judul'       => strip_tags(trim($request->judul)),
            'slug'        => $slug,
            'kategori'    => strip_tags(trim($request->kategori ?: 'Profil Madrasah')),
            'kutipan'     => strip_tags(trim((string) $request->kutipan)) ?: null,
            'isi_konten'  => $request->isi_konten,
            'gambar'      => $gambarPath,
            'penulis'     => auth()->user()->name ?? 'Humas MIN 1 Ciamis',
            'is_active'   => true,
        ]);

        // Jika dihubungkan ke Menu Navbar, otomatis update URL menu tersebut
        if ($halaman->nav_menu_id) {
            NavMenu::where('id', $halaman->nav_menu_id)->update([
                'url' => '/halaman/' . $halaman->slug,
            ]);
        }

        return redirect()->route('halaman.index')->with('success', 'Halaman "' . $halaman->judul . '" berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        Halaman::pastikanTabelSiap();

        $halaman = Halaman::findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:200',
            'kategori'    => 'nullable|string|max:100',
            'kutipan'     => 'nullable|string',
            'isi_konten'  => 'required|string',
            'nav_menu_id' => 'nullable|exists:nav_menus,id',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = [
            'nav_menu_id' => $request->nav_menu_id ?: null,
            'judul'       => strip_tags(trim($request->judul)),
            'kategori'    => strip_tags(trim($request->kategori ?: 'Profil Madrasah')),
            'kutipan'     => strip_tags(trim((string) $request->kutipan)) ?: null,
            'isi_konten'  => $request->isi_konten,
            'is_active'   => $request->has('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            if ($halaman->gambar && Storage::disk('public')->exists($halaman->gambar)) {
                Storage::disk('public')->delete($halaman->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('halaman', 'public');
        }

        $halaman->update($data);

        if ($halaman->nav_menu_id) {
            NavMenu::where('id', $halaman->nav_menu_id)->update([
                'url' => '/halaman/' . $halaman->slug,
            ]);
        }

        return redirect()->route('halaman.index')->with('success', 'Konten halaman "' . $halaman->judul . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $halaman = Halaman::findOrFail($id);

        if ($halaman->gambar && Storage::disk('public')->exists($halaman->gambar)) {
            Storage::disk('public')->delete($halaman->gambar);
        }

        $halaman->delete();

        return redirect()->back()->with('success', 'Halaman berhasil dihapus!');
    }
}