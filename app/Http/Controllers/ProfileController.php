<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Profil Saya dengan data dari tabel GTK.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['gtk.jabatan', 'gtk.posisi']);
        $gtk  = $user->gtk;

        return view('profile.edit', compact('user', 'gtk'));
    }

    /**
     * Memperbarui data Profil dan Foto langsung ke tabel GTK dan Users.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $gtk  = $user->gtk;

        // Validasi langsung menunjuk ke tabel 'users' agar tidak error
        $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:150'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nomor_induk'   => ['nullable', 'string', 'max:50'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'no_hp'         => ['nullable', 'string', 'max:20'],
            'foto'          => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // 1. Update Nama & Email di tabel users
        $user->update([
            'name'  => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        // 2. Siapkan data untuk disimpan ke tabel gtks
        $dataGtk = [
            'nama_lengkap'       => $request->nama_lengkap,
            'nomor_induk'        => $request->nomor_induk,
            'jenis_kelamin'      => $request->jenis_kelamin ?? ($gtk->jenis_kelamin ?? 'L'),
            'no_hp'              => $request->no_hp,
            'status_kepegawaian' => $gtk->status_kepegawaian ?? 'PNS',
        ];

        // 3. Proses Upload Foto Profil jika ada file baru yang dipilih
        if ($request->hasFile('foto')) {
            if ($gtk && $gtk->foto && Storage::disk('public')->exists($gtk->foto)) {
                Storage::disk('public')->delete($gtk->foto);
            }
            $dataGtk['foto'] = $request->file('foto')->store('gtk-fotos', 'public');
        }

        // 4. Simpan ke tabel gtks (jika akun Super Admin belum punya baris di gtks, otomatis dibuatkan!)
        Gtk::updateOrCreate(
            ['user_id' => $user->id],
            $dataGtk
        );

        return Redirect::route('profile.edit')->with('success', 'Profil dan foto berhasil diperbarui!');
    }

    /**
     * Menghapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}