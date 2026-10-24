<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Jabatan;
use App\Models\Posisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GtkController extends Controller
{
    public function index()
    {
        // Murni mengambil data GTK, Jabatan, dan Posisi (urut dari atas ke bawah)
        $gtks = Gtk::with(['jabatan', 'posisi'])->orderBy('id', 'asc')->get();
        $jabatans = Jabatan::orderBy('id', 'asc')->get();
        $posisis = Posisi::orderBy('id', 'asc')->get();

        return view('gtk.index', compact('gtks', 'jabatans', 'posisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'       => 'required|string|max:150',
            'nomor_induk'        => 'nullable|string|max:50',
            'jenis_kelamin'      => 'required|in:L,P',
            'no_hp'              => 'nullable|string|max:20',
            'status_kepegawaian' => 'required|in:PNS,PPPK,Honorer',
            'jabatan_id'         => 'required|exists:jabatans,id',
            'posisi_id'          => 'nullable|exists:posisis,id',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload Foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('gtk-fotos', 'public');
        }

        // Murni simpan ke tabel gtks saja (tanpa buat akun user/role)
        Gtk::create([
            'foto'               => $fotoPath,
            'nama_lengkap'       => $request->nama_lengkap,
            'nomor_induk'        => $request->nomor_induk,
            'jenis_kelamin'      => $request->jenis_kelamin,
            'no_hp'              => $request->no_hp,
            'status_kepegawaian' => $request->status_kepegawaian,
            'jabatan_id'         => $request->jabatan_id,
            'posisi_id'          => $request->posisi_id,
        ]);

        return redirect()->back()->with('success', 'Data Guru/Tendik berhasil ditambahkan!');
    }

    public function update(Request $request, Gtk $gtk)
    {
        $request->validate([
            'nama_lengkap'       => 'required|string|max:150',
            'nomor_induk'        => 'nullable|string|max:50',
            'jenis_kelamin'      => 'required|in:L,P',
            'no_hp'              => 'nullable|string|max:20',
            'status_kepegawaian' => 'required|in:PNS,PPPK,Honorer',
            'jabatan_id'         => 'required|exists:jabatans,id',
            'posisi_id'          => 'nullable|exists:posisis,id',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'nama_lengkap', 'nomor_induk', 'jenis_kelamin', 
            'no_hp', 'status_kepegawaian', 'jabatan_id', 'posisi_id'
        ]);

        // Jika ganti foto baru
        if ($request->hasFile('foto')) {
            if ($gtk->foto && Storage::disk('public')->exists($gtk->foto)) {
                Storage::disk('public')->delete($gtk->foto);
            }
            $data['foto'] = $request->file('foto')->store('gtk-fotos', 'public');
        }

        $gtk->update($data);

        return redirect()->back()->with('success', 'Data Guru/Tendik berhasil diperbarui!');
    }

    public function destroy(Gtk $gtk)
    {
        if ($gtk->foto && Storage::disk('public')->exists($gtk->foto)) {
            Storage::disk('public')->delete($gtk->foto);
        }

        $gtk->delete();

        return redirect()->back()->with('success', 'Data Guru/Tendik berhasil dihapus!');
    }
}