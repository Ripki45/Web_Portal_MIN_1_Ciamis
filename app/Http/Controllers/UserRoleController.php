<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gtk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Kunci Pintu Belakang: Pastikan hanya Superadmin murni yang bisa mengeksekusi Controller ini
     */
    private function pastikanSuperadmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak! Halaman dan aksi ini khusus untuk Super Admin.');
        }
    }

    public function index()
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $users = User::with('gtk')->orderBy('id', 'asc')->get();
        $gtks  = Gtk::orderBy('nama_lengkap', 'asc')->get();

        $daftarModul = User::daftarModulSistem();
        $permissions = DB::table('role_permissions')
            ->get()
            ->groupBy('role')
            ->map(function ($items) {
                return $items->pluck('is_allowed', 'menu_key')->toArray();
            })
            ->toArray();

        return view('users.index', compact('users', 'gtks', 'daftarModul', 'permissions'));
    }

    public function store(Request $request)
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', Rule::in(['superadmin', 'admin', 'guru', 'pembina_ekskul'])],
            'gtk_id'   => ['nullable', 'exists:gtks,id'],
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain!',
            'password.min' => 'Kata sandi minimal harus 8 karakter demi keamanan!',
        ]);

        User::create([
            'name'     => strip_tags(trim($request->name)),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'gtk_id'   => $request->gtk_id ?: null,
        ]);

        return redirect()->back()->with('success', 'Akun pengguna baru berhasil dibuat dengan aman!');
    }

    public function update(Request $request, $id)
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $user = User::findOrFail($id);

        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role'     => ['required', Rule::in(['superadmin', 'admin', 'guru', 'pembina_ekskul'])],
            'gtk_id'   => ['nullable', 'exists:gtks,id'],
        ], [
            'password.min' => 'Kata sandi baru minimal harus 8 karakter!',
        ]);

        // KEAMANAN: Cegah Superadmin yang sedang login menurunkan role-nya sendiri
        if ($user->id === auth()->id() && $request->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Keamanan Sistem: Anda tidak dapat mengubah role akun Superadmin milik Anda sendiri yang sedang aktif!');
        }

        $data = [
            'name'   => strip_tags(trim($request->name)),
            'email'  => strtolower(trim($request->email)),
            'role'   => $request->role,
            'gtk_id' => $request->gtk_id ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data akun & hak akses berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->pastikanSuperadmin();

        $user = User::findOrFail($id);

        // 1. Cegah hapus akun sendiri yang sedang login
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan login!');
        }

        // 2. Pastikan minimal selalu tersisa 1 Superadmin di dalam database
        if ($user->role === 'superadmin') {
            $jumlahSuperadmin = User::where('role', 'superadmin')->count();
            if ($jumlahSuperadmin <= 1) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus Superadmin terakhir di sistem!');
            }
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function updatePermissions(Request $request)
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $daftarModul = array_keys(User::daftarModulSistem());
        $roles       = ['admin', 'guru', 'pembina_ekskul'];
        $inputMatrix = $request->input('permissions', []);

        DB::transaction(function () use ($roles, $daftarModul, $inputMatrix) {
            foreach ($roles as $role) {
                foreach ($daftarModul as $menuKey) {
                    $isAllowed = isset($inputMatrix[$role][$menuKey]) && $inputMatrix[$role][$menuKey] == '1';

                    DB::table('role_permissions')->updateOrInsert(
                        ['role' => $role, 'menu_key' => $menuKey],
                        ['is_allowed' => $isAllowed, 'updated_at' => now()]
                    );
                }
            }
        });

        return redirect()->back()->with('success', 'Matriks Toggle Hak Akses berhasil disimpan & disinkronkan!');
    }
}