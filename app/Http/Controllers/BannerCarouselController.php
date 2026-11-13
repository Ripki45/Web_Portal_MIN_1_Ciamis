<?php

namespace App\Http\Controllers;

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
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'urutan' => ['required', 'integer', 'min:1'],
        ], [
            'gambar.required' => 'Silakan pilih file foto banner terlebih dahulu!',
        ]);

        $gambarPath = $request->file('gambar')->store('banner_carousel', 'public');

        BannerCarousel::create([
            'kategori'   => 'Banner Utama',
            'judul'      => 'Foto Banner Slide #' . (int) $request->urutan,
            'keterangan' => '-',
            'gambar'     => $gambarPath,
            'link_url'   => '#',
            'urutan'     => (int) $request->urutan,
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Foto Banner berhasil diunggah!');
    }

    public function update(Request $request, $id)
    {
        BannerCarousel::pastikanTabelSiap();

        $banner = BannerCarousel::findOrFail($id);

        $request->validate([
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'urutan' => ['required', 'integer', 'min:1'],
        ]);

        $data = [
            'judul'     => 'Foto Banner Slide #' . (int) $request->urutan,
            'urutan'    => (int) $request->urutan,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            if ($banner->gambar && !str_starts_with($banner->gambar, 'frontend/') && Storage::disk('public')->exists($banner->gambar)) {
                Storage::disk('public')->delete($banner->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('banner_carousel', 'public');
        }

        $banner->update($data);

        return redirect()->back()->with('success', 'Foto Banner Slide #' . $banner->urutan . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $banner = BannerCarousel::findOrFail($id);

        if ($banner->gambar && !str_starts_with($banner->gambar, 'frontend/') && Storage::disk('public')->exists($banner->gambar)) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        return redirect()->back()->with('success', 'Foto Banner berhasil dihapus!');
    }
}