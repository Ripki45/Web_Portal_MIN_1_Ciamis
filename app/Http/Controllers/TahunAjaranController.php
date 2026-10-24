<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        // Urutkan dari atas ke bawah sesuai urutan input
        $tahunAjarans = TahunAjaran::orderBy('id', 'asc')->get();
        return view('tahun_ajaran.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester'     => 'required|in:Ganjil,Genap',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        // Cek apakah kombinasi Tahun Ajaran dan Semester sudah ada
        $exists = TahunAjaran::where('tahun_ajaran', $request->tahun_ajaran)
                             ->where('semester', $request->semester)
                             ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Tahun Ajaran dan Semester tersebut sudah ada!');
        }

        $isActive = $request->has('is_active') ? true : false;

        // Jika ini data pertama di tabel, otomatis jadikan Aktif
        if (TahunAjaran::count() === 0) {
            $isActive = true;
        }

        // Jika diset Aktif, nonaktifkan semua tahun ajaran lainnya
        if ($isActive) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester'     => $request->semester,
            'is_active'    => $isActive,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil ditambahkan!');
    }

    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester'     => 'required|in:Ganjil,Genap',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        $tahun_ajaran->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester'     => $request->semester,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil diperbarui!');
    }

    // Fungsi khusus saat tombol "Aktifkan" diklik di tabel
    public function setActive($id)
    {
        // Nonaktifkan semua terlebih dahulu
        TahunAjaran::query()->update(['is_active' => false]);

        // Aktifkan yang dipilih
        $ta = TahunAjaran::findOrFail($id);
        $ta->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Tahun Ajaran ' . $ta->tahun_ajaran . ' (' . $ta->semester . ') berhasil diaktifkan!');
    }

    public function destroy(TahunAjaran $tahun_ajaran)
    {
        // Pengaman: Tahun ajaran yang sedang aktif tidak boleh dihapus
        if ($tahun_ajaran->is_active) {
            return redirect()->back()->with('error', 'Tahun Ajaran yang sedang AKTIF tidak dapat dihapus!');
        }

        $tahun_ajaran->delete();
        return redirect()->back()->with('success', 'Tahun Ajaran berhasil dihapus!');
    }
}