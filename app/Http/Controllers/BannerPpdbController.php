<?php

namespace App\Http\Controllers;

use App\Models\BannerPpdb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerPpdbController extends Controller
{
    /**
     * Tampilkan Halaman Pengaturan Banner PPDB & Popup Modal
     */
    public function index(): View
    {
        BannerPpdb::pastikanTabelSiap();
        $bannerPpdb = BannerPpdb::ambilData();

        return view('banner_ppdb.index', compact('bannerPpdb'));
    }

    /**
     * Simpan Perubahan Pengaturan Banner & Popup PPDB
     */
    public function update(Request $request): RedirectResponse
    {
        BannerPpdb::pastikanTabelSiap();
        $bannerPpdb = BannerPpdb::ambilData();

        $validated = $request->validate([
            'judul' => 'nullable|string|max:200',
            'subjudul' => 'nullable|string|max:255',
            'tipe_link' => 'required|in:popup,url',
            'link_url' => 'nullable|string|max:255',
            'is_active' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'hapus_gambar' => 'nullable|boolean',

            'is_active_popup' => 'nullable',
            'auto_popup' => 'nullable',
            'popup_judul' => 'required|string|max:200',
            'popup_badge' => 'nullable|string|max:100',
            'popup_konten' => 'nullable|string',
            'popup_tombol_teks' => 'nullable|string|max:100',
            'popup_tombol_url' => 'nullable|string|max:255',
            'popup_wa_nomor' => 'nullable|string|max:50',
            'popup_wa_teks' => 'nullable|string|max:255',
            'popup_gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'hapus_popup_gambar' => 'nullable|boolean',
        ], [
            'judul.required' => 'Judul banner PPDB wajib diisi.',
            'popup_judul.required' => 'Judul popup modal wajib diisi.',
            'gambar.max' => 'Ukuran gambar banner maksimal 5MB.',
            'popup_gambar.max' => 'Ukuran foto brosur popup maksimal 5MB.',
        ]);

        // 1. Kelola Upload Gambar Banner Landscape
        if ($request->boolean('hapus_gambar')) {
            if (! empty($bannerPpdb->gambar) && Storage::disk('public')->exists($bannerPpdb->gambar)) {
                Storage::disk('public')->delete($bannerPpdb->gambar);
            }
            $bannerPpdb->gambar = null;
        } elseif ($request->hasFile('gambar')) {
            if (! empty($bannerPpdb->gambar) && Storage::disk('public')->exists($bannerPpdb->gambar)) {
                Storage::disk('public')->delete($bannerPpdb->gambar);
            }
            $bannerPpdb->gambar = $request->file('gambar')->store('ppdb/banner', 'public');
        }

        // 2. Kelola Upload Gambar Brosur Popup
        if ($request->boolean('hapus_popup_gambar')) {
            if (! empty($bannerPpdb->popup_gambar) && Storage::disk('public')->exists($bannerPpdb->popup_gambar)) {
                Storage::disk('public')->delete($bannerPpdb->popup_gambar);
            }
            $bannerPpdb->popup_gambar = null;
        } elseif ($request->hasFile('popup_gambar')) {
            if (! empty($bannerPpdb->popup_gambar) && Storage::disk('public')->exists($bannerPpdb->popup_gambar)) {
                Storage::disk('public')->delete($bannerPpdb->popup_gambar);
            }
            $bannerPpdb->popup_gambar = $request->file('popup_gambar')->store('ppdb/popup', 'public');
        }

        // 3. Update Nilai Field Teks & Pengaturan
        $bannerPpdb->judul = $validated['judul'] ?? $bannerPpdb->judul ?? 'Banner PPDB MIN 1 Ciamis';
        $bannerPpdb->subjudul = $validated['subjudul'] ?? $bannerPpdb->subjudul ?? '';
        $bannerPpdb->tipe_link = $validated['tipe_link'];
        $bannerPpdb->link_url = $validated['link_url'] ?? '#ppdb';
        $bannerPpdb->is_active = $request->has('is_active');

        $bannerPpdb->is_active_popup = $request->has('is_active_popup');
        $bannerPpdb->auto_popup = $request->has('auto_popup');
        $bannerPpdb->popup_judul = $validated['popup_judul'];
        $bannerPpdb->popup_badge = $validated['popup_badge'] ?? 'PPDB ONLINE';
        $bannerPpdb->popup_konten = $validated['popup_konten'] ?? '';
        $bannerPpdb->popup_tombol_teks = $validated['popup_tombol_teks'] ?? 'Daftar Online Sekarang';
        $bannerPpdb->popup_tombol_url = $validated['popup_tombol_url'] ?? '#';
        $bannerPpdb->popup_wa_nomor = $validated['popup_wa_nomor'] ?? '';
        $bannerPpdb->popup_wa_teks = $validated['popup_wa_teks'] ?? '';
        $bannerPpdb->save();

        // 4. Invalidate landing page cache agar perubahan instan terlihat pengunjung
        HomeController::bersihkanCache();

        return redirect()->route('banner-ppdb.index')
            ->with('success', 'Pengaturan Banner & Popup PPDB berhasil disimpan dan diperbarui!');
    }
}
