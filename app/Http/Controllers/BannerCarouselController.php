<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BannerTaglineAgent;
use App\Models\BannerCarousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerCarouselController extends Controller
{
    public function index()
    {
        BannerCarousel::pastikanTabelSiap();

        $banners = BannerCarousel::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();

        return view('banner_carousel.index', compact('banners'));
    }

    public function store(Request $request)
    {
        BannerCarousel::pastikanTabelSiap();

        $request->validate([
            'tipe_layout' => ['required', 'in:tanpa_foto,dengan_foto'],
            'aksara_kecil' => ['required', 'string', 'max:120'],
            'aksara_besar' => ['required', 'string', 'max:150'],
            'teks_domain' => ['nullable', 'string', 'max:180'],
            'keterangan_1' => ['required', 'string', 'max:220'],
            'keterangan_2' => ['nullable', 'string', 'max:220'],
            'urutan' => ['required', 'integer', 'min:1'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('banner_carousel', 'public');
        } elseif ($request->tipe_layout === 'dengan_foto') {
            $gambarPath = 'frontend/landing/hero-collage-students.png';
        }

        $aksaraBesar = strip_tags(trim($request->aksara_besar));
        $ket1 = strip_tags(trim($request->keterangan_1));
        $ket2 = strip_tags(trim((string) $request->keterangan_2));

        BannerCarousel::create([
            'tipe_layout' => $request->tipe_layout,
            'aksara_kecil' => strip_tags(trim($request->aksara_kecil)),
            'aksara_besar' => $aksaraBesar,
            'teks_domain' => strip_tags(trim((string) $request->teks_domain)),
            'teks_badge' => null,
            'keterangan_1' => $ket1,
            'keterangan_2' => $ket2,
            'tampilkan_stiker' => $request->has('tampilkan_stiker'),
            'kategori' => 'Hero Utama',
            'judul' => $aksaraBesar,
            'keterangan' => trim($ket1.' '.$ket2),
            'gambar' => $gambarPath,
            'link_url' => '#',
            'urutan' => (int) $request->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        HomeController::bersihkanCache();

        return redirect()->back()->with('success', 'Slide Hero "'.$aksaraBesar.'" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        BannerCarousel::pastikanTabelSiap();

        $banner = BannerCarousel::findOrFail($id);

        $request->validate([
            'tipe_layout' => ['required', 'in:tanpa_foto,dengan_foto'],
            'aksara_kecil' => ['required', 'string', 'max:120'],
            'aksara_besar' => ['required', 'string', 'max:150'],
            'teks_domain' => ['nullable', 'string', 'max:180'],
            'keterangan_1' => ['required', 'string', 'max:220'],
            'keterangan_2' => ['nullable', 'string', 'max:220'],
            'urutan' => ['required', 'integer', 'min:1'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $aksaraBesar = strip_tags(trim($request->aksara_besar));
        $ket1 = strip_tags(trim($request->keterangan_1));
        $ket2 = strip_tags(trim((string) $request->keterangan_2));

        $data = [
            'tipe_layout' => $request->tipe_layout,
            'aksara_kecil' => strip_tags(trim($request->aksara_kecil)),
            'aksara_besar' => $aksaraBesar,
            'teks_domain' => strip_tags(trim((string) $request->teks_domain)),
            'keterangan_1' => $ket1,
            'keterangan_2' => $ket2,
            'tampilkan_stiker' => $request->has('tampilkan_stiker'),
            'judul' => $aksaraBesar,
            'keterangan' => trim($ket1.' '.$ket2),
            'urutan' => (int) $request->urutan,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            if ($banner->gambar && ! str_starts_with($banner->gambar, 'frontend/') && Storage::disk('public')->exists($banner->gambar)) {
                Storage::disk('public')->delete($banner->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('banner_carousel', 'public');
        }

        $banner->update($data);

        HomeController::bersihkanCache();

        return redirect()->back()->with('success', 'Slide #'.$banner->urutan.' ('.$aksaraBesar.') berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $banner = BannerCarousel::findOrFail($id);

        if ($banner->gambar && ! str_starts_with($banner->gambar, 'frontend/') && Storage::disk('public')->exists($banner->gambar)) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        HomeController::bersihkanCache();

        return redirect()->back()->with('success', 'Slide Hero Carousel berhasil dihapus!');
    }

    /**
     * Generate Tagline & Teks Banner Carousel Menggunakan Laravel AI Agent (BannerTaglineAgent)
     */
    public function generateAi(Request $request)
    {
        $tema = trim((string) $request->input('tema', 'Pendidikan Karakter Islami dan Prestasi Siswa Madrasah'));

        try {
            $agent = new BannerTaglineAgent;
            $result = $agent->prompt("Tema Banner: {$tema}\nBuatkan teks banner hero landing page yang memikat.");

            if (! empty($result['aksara_besar'])) {
                return response()->json([
                    'status' => 'success',
                    'source' => 'Laravel AI SDK (BannerTaglineAgent)',
                    'data' => $result,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback ke Smart AI Lokal
        }

        return response()->json([
            'status' => 'success',
            'source' => 'Smart AI Studio Lokal',
            'data' => [
                'aksara_kecil' => 'MADRASAH IBTIDAIYAH NEGERI 1 CIAMIS',
                'aksara_besar' => strlen($tema) > 10 ? ucwords($tema) : 'Mewujudkan Generasi Madrasah Unggul, Berakhlak, dan Berprestasi',
                'teks_domain' => 'BERAKHLAK MULIA • CERDAS • BERPRESTASI GLOBAL',
                'deskripsi' => 'Lembaga pendidikan dasar berciri khas Islam dengan kurikulum terpadu, lingkungan asri, dan pembinaan karakter holistik.',
            ],
        ]);
    }
}
