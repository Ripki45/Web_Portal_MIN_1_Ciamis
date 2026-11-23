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
        $validated = $this->validasiLayanan($request);

        $layanan = LayananCepat::create([
            'nama_layanan' => strip_tags(trim($validated['nama_layanan'])),
            'icon' => $this->bersihkanClassIcon($validated['icon']),
            'link_url' => trim($validated['link_url']),
            'target' => $validated['target'],
            'urutan' => $validated['urutan'] ?? ((LayananCepat::max('urutan') ?? 0) + 1),
            'is_active' => $request->boolean('is_active'),
            'keterangan' => isset($validated['keterangan']) ? (strip_tags(trim($validated['keterangan'])) ?: null) : null,
        ]);

        HomeController::bersihkanCache();

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.$layanan->nama_layanan.'" berhasil ditambahkan!');
    }

    /**
     * Perbarui Data Layanan Cepat
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $layanan = LayananCepat::findOrFail($id);
        $validated = $this->validasiLayanan($request);

        $layanan->update([
            'nama_layanan' => strip_tags(trim($validated['nama_layanan'])),
            'icon' => $this->bersihkanClassIcon($validated['icon']),
            'link_url' => trim($validated['link_url']),
            'target' => $validated['target'],
            'urutan' => $validated['urutan'] ?? $layanan->urutan,
            'is_active' => $request->boolean('is_active'),
            'keterangan' => isset($validated['keterangan']) ? (strip_tags(trim($validated['keterangan'])) ?: null) : null,
        ]);

        HomeController::bersihkanCache();

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.$layanan->nama_layanan.'" berhasil diperbarui!');
    }

    /**
     * Hapus Layanan Cepat
     */
    public function destroy(int $id): RedirectResponse
    {
        $layanan = LayananCepat::findOrFail($id);
        $nama = $layanan->nama_layanan;
        $layanan->delete();

        HomeController::bersihkanCache();

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.$nama.'" berhasil dihapus!');
    }

    /**
     * Beralih Status Aktif / Non-Aktif
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $layanan = LayananCepat::findOrFail($id);
        $layanan->is_active = ! $layanan->is_active;
        $layanan->save();

        HomeController::bersihkanCache();

        $statusTeks = $layanan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('layanan-cepat.index')
            ->with('success', 'Layanan cepat "'.$layanan->nama_layanan.'" berhasil '.$statusTeks.'!');
    }

    /**
     * Validasi terpadu untuk store & update (hanya mengizinkan URL http/https, path relatif, atau anchor)
     *
     * @return array<string, mixed>
     */
    private function validasiLayanan(Request $request): array
    {
        return $request->validate([
            'nama_layanan' => 'required|string|max:150',
            'icon' => 'required|string|max:80',
            'link_url' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $url = trim((string) $value);
                    $valid = str_starts_with($url, 'https://')
                        || str_starts_with($url, 'http://')
                        || str_starts_with($url, '/')
                        || str_starts_with($url, '#');

                    if (! $valid) {
                        $fail('Tautan layanan harus diawali dengan https://, http://, /, atau #.');
                    }
                },
            ],
            'target' => 'required|in:_blank,_self',
            'urutan' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'icon.required' => 'Ikon Font Awesome wajib diisi.',
            'link_url.required' => 'Tautan / URL layanan wajib diisi.',
        ]);
    }

    /**
     * Memastikan class ikon hanya berisi karakter CSS yang valid.
     */
    private function bersihkanClassIcon(?string $icon): string
    {
        $clean = trim((string) preg_replace('/[^a-zA-Z0-9\-\s_]/', '', (string) $icon));

        return $clean !== '' ? $clean : 'fas fa-link';
    }
}