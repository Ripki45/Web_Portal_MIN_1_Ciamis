<?php

namespace App\Http\Controllers;

use App\Models\Posisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class PosisiController extends Controller
{
    // Menampilkan halaman Master Posisi
    public function index()
    {
        $posisis = Posisi::with('jabatan')->latest()->get();
        
        // Kecualikan Kepala Madrasah dari pilihan Jabatan Induk di Master Posisi
        $jabatans = Jabatan::where('nama_jabatan', 'not like', '%Kepala Madrasah%')
            ->where('nama_jabatan', 'not like', '%Kepala Sekolah%')
            ->orderBy('id', 'asc')
            ->get();

        return view('posisi.index', compact('posisis', 'jabatans'));
    }

    // Menyimpan data Posisi baru
    public function store(Request $request)
    {
        $request->validate([
            'jabatan_id'  => 'required|exists:jabatans,id',
            'nama_posisi' => 'required|string|max:100',
            'keterangan'  => 'nullable|string|max:255',
        ], [
            'jabatan_id.required'  => 'Silakan pilih Induk Jabatan terlebih dahulu!',
            'nama_posisi.required' => 'Nama posisi wajib diisi!',
        ]);

        Posisi::create([
            'jabatan_id'  => $request->jabatan_id,
            'nama_posisi' => $request->nama_posisi,
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data Posisi berhasil ditambahkan!');
    }

    // Mengupdate data Posisi
    public function update(Request $request, Posisi $posisi)
    {
        $request->validate([
            'jabatan_id'  => 'required|exists:jabatans,id',
            'nama_posisi' => 'required|string|max:100',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $posisi->update([
            'jabatan_id'  => $request->jabatan_id,
            'nama_posisi' => $request->nama_posisi,
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data Posisi berhasil diperbarui!');
    }

    // Menghapus data Posisi
    public function destroy(Posisi $posisi)
    {
        $posisi->delete();
        return redirect()->back()->with('success', 'Data Posisi berhasil dihapus!');
    }

    // API JSON untuk Dependent Dropdown di Form GTK nanti
    public function getByJabatan($jabatan_id)
    {
        $posisis = Posisi::where('jabatan_id', $jabatan_id)
                         ->orderBy('nama_posisi', 'asc')
                         ->get(['id', 'nama_posisi']);
                         
        return response()->json($posisis);
    }
}