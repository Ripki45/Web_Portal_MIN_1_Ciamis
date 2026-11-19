<?php

namespace App\Http\Controllers;

use App\Models\LayananCepat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananCepatController extends Controller
{
    /**
     * Halaman Publik Direktori Layanan Cepat Terpadu
     */
    public function publik(): View
    {
        LayananCepat::pastikanTabelSiap();

        $layananCepats = LayananCepat::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.layanan_cepat', compact('layananCepats'));
    }

    /**
     * Tampilkan Halaman Manajemen Layanan Cepat (Quick Access Portal)
     */
    public function index(): View
    {
        LayananCepat::pastikanTabelSiap();

        $layananCepats = LayananCepat::orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $iconRekomendasi = LayananCepat::daftarIconRekomendasi();
        $urutanBerikutnya = ($layananCepats->max('urutan') ?? 0) + 1;

        return view('layanan_cepat.index', compact('layananCepats', 'iconRekomendasi', 'urutanBerikutnya'));
    }

    /**
     * Tambah Item Layanan Cepat Baru
     */
    public function store(Request $request): RedirectResponse
    {
        LayananCepat::pastikanTabelSiap();

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:150',
            'icon' => 'required|string|max:80',
            'link_url' => 'required|string|max:255',
            'target' => 'required|in:_blank,_self',
            'urutan' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'icon.required' => 'Ikon Font Awesome / Favicon wajib diisi.',
            'link_url.required' => 'Tautan / URL isian layanan wajib diisi.',
        ]);

        LayananCepat::create([
            'nama_layanan' => $validated['nama_layanan'],
            'icon' => trim($validated['icon']),
            'link_url' => trim($validated['link_url']),
            'target' => $validated['target'],
            'urutan' => $validated['urutan'] ?? ((LayananCepat::max('urutan') ?? 0) + 1),
            'is_active' => $request->has('is_active'),
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        HomeController::bersihkanCache();

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.e($validated['nama_layanan']).'" berhasil ditambahkan!');
    }

    /**
     * Perbarui Data Layanan Cepat
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        LayananCepat::pastikanTabelSiap();
        $layanan = LayananCepat::findOrFail($id);

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:150',
            'icon' => 'required|string|max:80',
            'link_url' => 'required|string|max:255',
            'target' => 'required|in:_blank,_self',
            'urutan' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'icon.required' => 'Ikon Font Awesome wajib diisi.',
            'link_url.required' => 'Tautan / URL layanan wajib diisi.',
        ]);

        $layanan->update([
            'nama_layanan' => $validated['nama_layanan'],
            'icon' => trim($validated['icon']),
            'link_url' => trim($validated['link_url']),
            'target' => $validated['target'],
            'urutan' => $validated['urutan'] ?? $layanan->urutan,
            'is_active' => $request->has('is_active'),
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        HomeController::bersihkanCache();

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.e($layanan->nama_layanan).'" berhasil diperbarui!');
    }

    /**
     * Hapus Layanan Cepat
     */
    public function destroy(int $id): RedirectResponse
    {
        LayananCepat::pastikanTabelSiap();
        $layanan = LayananCepat::findOrFail($id);
        $nama = $layanan->nama_layanan;
        $layanan->delete();

        HomeController::bersihkanCache();

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.e($nama).'" berhasil dihapus!');
    }

    /**
     * Beralih Status Aktif / Non-Aktif
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        LayananCepat::pastikanTabelSiap();
        $layanan = LayananCepat::findOrFail($id);
        $layanan->is_active = ! $layanan->is_active;
        $layanan->save();

        HomeController::bersihkanCache();

        $statusTeks = $layanan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.e($layanan->nama_layanan).'" berhasil '.$statusTeks.'!');
    }
}
