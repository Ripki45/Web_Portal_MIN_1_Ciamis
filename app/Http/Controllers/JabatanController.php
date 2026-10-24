<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    // Menampilkan halaman form & tabel Jabatan
    public function index()
    {
        // Ubah latest() menjadi orderBy('id', 'asc')
        $jabatans = Jabatan::orderBy('id', 'asc')->get();
        return view('jabatan.index', compact('jabatans'));
    }

    // Menyimpan data Jabatan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100|unique:jabatans,nama_jabatan',
            'keterangan'   => 'nullable|string|max:255',
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi!',
            'nama_jabatan.unique'   => 'Nama jabatan ini sudah ada!',
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data Jabatan berhasil ditambahkan!');
    }

    // Mengupdate data Jabatan
    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100|unique:jabatans,nama_jabatan,' . $jabatan->id,
            'keterangan'   => 'nullable|string|max:255',
        ]);

        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data Jabatan berhasil diperbarui!');
    }

    // Menghapus data Jabatan
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->back()->with('success', 'Data Jabatan berhasil dihapus!');
    }
}