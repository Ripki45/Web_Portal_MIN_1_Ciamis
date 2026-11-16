<?php

namespace App\Http\Controllers;

use App\Models\DuniaIlmu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DuniaIlmuController extends Controller
{
    public function index()
    {
        DuniaIlmu::pastikanTabelSiap();

        $medias         = DuniaIlmu::orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();
        $daftarGradasi  = DuniaIlmu::daftarGradasi();
        $daftarKategori = DuniaIlmu::daftarKategoriFilter();

        return view('dunia_ilmu.index', compact('medias', 'daftarGradasi', 'daftarKategori'));
    }

    public function store(Request $request)
    {
        DuniaIlmu::pastikanTabelSiap();

        $request->validate([
            'judul'           => ['required', 'string', 'max:160'],
            'kategori_label'  => ['required', 'string', 'max:100'],
            'kategori_filter' => ['required', 'string', 'max:40'],
            'warna_gradasi'   => ['required', 'string', 'max:50'],
            'tipe_sumber'     => ['required', 'in:link,kode_interaktif'],
            'link_eksternal'  => ['nullable', 'string', 'max:255'],
            'isi_kode'        => ['nullable', 'string'],
            'deskripsi'       => ['nullable', 'string', 'max:600'],
            'urutan'          => ['required', 'integer', 'min:1'],
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
        ]);

        $baseSlug = Str::slug($request->judul) ?: 'media-' . time();
        $slug     = $baseSlug;
        $counter  = 1;
        while (DuniaIlmu::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // Ambil isi kode dari File Upload (.tsx/.jsx/.js/.html) ATAU dari Textarea Kode
        $isiKode      = $request->input('isi_kode');
        $namaFileKode = null;

        if ($request->hasFile('file_kode')) {
            $file         = $request->file('file_kode');
            $namaFileKode = $file->getClientOriginalName();
            $isiKode      = file_get_contents($file->getRealPath());
        } elseif (!empty($isiKode)) {
            $namaFileKode = $slug . '.tsx';
        }

        if ($request->tipe_sumber === 'kode_interaktif' && empty(trim((string) $isiKode))) {
            return redirect()->back()->withInput()->with('error', 'Untuk tipe Aplikasi TSX/JS, silakan unggah file .tsx/.js/.html atau tempelkan kodenya!');
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('dunia_ilmu', 'public');
        }

        DuniaIlmu::create([
            'judul'           => strip_tags(trim($request->judul)),
            'slug'            => $slug,
            'kategori_label'  => strip_tags(trim($request->kategori_label)),
            'kategori_filter' => $request->kategori_filter,
            'warna_gradasi'   => $request->warna_gradasi,
            'gambar'          => $gambarPath,
            'tipe_sumber'     => $request->tipe_sumber,
            'link_eksternal'  => trim((string) $request->link_eksternal),
            'nama_file_kode'  => $namaFileKode,
            'isi_kode'        => $isiKode,
            'deskripsi'       => strip_tags(trim((string) $request->deskripsi)),
            'urutan'          => (int) $request->urutan,
            'views'           => 1,
            'is_active'       => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Media Dunia Ilmu "' . $request->judul . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        DuniaIlmu::pastikanTabelSiap();

        $media = DuniaIlmu::findOrFail($id);

        $request->validate([
            'judul'           => ['required', 'string', 'max:160'],
            'kategori_label'  => ['required', 'string', 'max:100'],
            'kategori_filter' => ['required', 'string', 'max:40'],
            'warna_gradasi'   => ['required', 'string', 'max:50'],
            'tipe_sumber'     => ['required', 'in:link,kode_interaktif'],
            'link_eksternal'  => ['nullable', 'string', 'max:255'],
            'isi_kode'        => ['nullable', 'string'],
            'deskripsi'       => ['nullable', 'string', 'max:600'],
            'urutan'          => ['required', 'integer', 'min:1'],
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
        ]);

        $data = [
            'judul'           => strip_tags(trim($request->judul)),
            'kategori_label'  => strip_tags(trim($request->kategori_label)),
            'kategori_filter' => $request->kategori_filter,
            'warna_gradasi'   => $request->warna_gradasi,
            'tipe_sumber'     => $request->tipe_sumber,
            'link_eksternal'  => trim((string) $request->link_eksternal),
            'deskripsi'       => strip_tags(trim((string) $request->deskripsi)),
            'urutan'          => (int) $request->urutan,
            'is_active'       => $request->has('is_active'),
        ];

        if ($request->hasFile('file_kode')) {
            $file                   = $request->file('file_kode');
            $data['nama_file_kode'] = $file->getClientOriginalName();
            $data['isi_kode']       = file_get_contents($file->getRealPath());
        } elseif ($request->filled('isi_kode')) {
            $data['isi_kode'] = $request->input('isi_kode');
            if (empty($media->nama_file_kode)) {
                $data['nama_file_kode'] = $media->slug . '.tsx';
            }
        }

        if ($request->hasFile('gambar')) {
            if ($media->gambar && !str_starts_with($media->gambar, 'frontend/') && Storage::disk('public')->exists($media->gambar)) {
                Storage::disk('public')->delete($media->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('dunia_ilmu', 'public');
        }

        $media->update($data);

        return redirect()->back()->with('success', 'Media "' . $media->judul . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $media = DuniaIlmu::findOrFail($id);

        if ($media->gambar && !str_starts_with($media->gambar, 'frontend/') && Storage::disk('public')->exists($media->gambar)) {
            Storage::disk('public')->delete($media->gambar);
        }

        $media->delete();

        return redirect()->back()->with('success', 'Media pembelajaran berhasil dihapus!');
    }

    /**
     * 🚀 MESIN PEMUTAR INTERAKTIF (.TSX / .JSX / .JS / .HTML)
     */
    public function play($slug)
    {
        DuniaIlmu::pastikanTabelSiap();

        $media = DuniaIlmu::where('slug', $slug)->firstOrFail();
        $media->increment('views');

        if ($media->tipe_sumber === 'link' && !empty($media->link_eksternal)) {
            return redirect()->away($media->link_eksternal);
        }

        return view('frontend.dunia_ilmu_play', compact('media'));
    }
}