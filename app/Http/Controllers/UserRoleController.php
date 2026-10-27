<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gtk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    public function index()
    {
        User::pastikanTabelRoleSiap();

        $users      = User::with('gtk')->orderBy('id', 'asc')->get();
        $gtks       = Gtk::with(['jabatan', 'posisi'])->orderBy('nama_lengkap', 'asc')->get();
        $modulList  = User::daftarModulSistem();
        $roleTarget = ['admin' => 'Admin / TU', 'guru' => 'Guru', 'pembina_ekskul' => 'Pembina Ekskul'];

        // Ambil matriks status toggle saat ini dari tabel role_permissions
        $rows = DB::table('role_permissions')->get();
        $matrix = [];
        foreach ($rows as $r) {
            $matrix[$r->role][$r->menu_key] = (bool) $r->is_allowed;
        }

        return view('users.index', compact('users', 'gtks', 'modulList', 'roleTarget', 'matrix'));
    }

    /**
     * SIMPAN PERUBAHAN TOGGLE SWITCH SECARA INSTAN (AJAX)
     */
    public function togglePermission(Request $request)
    {
        User::pastikanTabelRoleSiap();

        $request->validate([
            'role'       => 'required|in:admin,guru,pembina_ekskul',
            'menu_key'   => 'required|string',
            'is_allowed' => 'required|boolean',
        ]);

        DB::table('role_permissions')->updateOrInsert(
            [
                'role'     => $request->role,
                'menu_key' => $request->menu_key,
            ],
            [
                'is_allowed' => $request->is_allowed ? 1 : 0,
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Hak akses role ' . strtoupper($request->role) . ' berhasil diperbarui!',
        ]);
    }

    public function store(Request $request)
    {
        User::pastikanTabelRoleSiap();

        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:superadmin,admin,guru,pembina_ekskul',
            'gtk_id'   => 'nullable|exists:gtks,id',
        ], [
            'email.unique' => 'Email ini sudah terdaftar pada akun lain!',
        ]);

        User::create([
            'name'     => trim($request->name),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'gtk_id'   => $request->gtk_id ?: null,
        ]);

        return redirect()->route('users.index', ['tab' => 'akun'])
            ->with('success', 'Akun pengguna baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        User::pastikanTabelRoleSiap();

        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|max:150|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:superadmin,admin,guru,pembina_ekskul',
            'gtk_id'   => 'nullable|exists:gtks,id',
        ]);

        if ($user->id === auth()->id() && $request->role !== 'superadmin') {
            $jumlahSuper = User::where('role', 'superadmin')->count();
            if ($jumlahSuper <= 1) {
                return redirect()->back()->with('error', 'Anda adalah satu-satunya Superadmin, role tidak dapat diturunkan!');
            }
        }

        $data = [
            'name'   => trim($request->name),
            'email'  => strtolower(trim($request->email)),
            'role'   => $request->role,
            'gtk_id' => $request->gtk_id ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index', ['tab' => 'akun'])
            ->with('success', 'Data akun & role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun yang sedang Anda gunakan!');
        }

        $user->delete();

        return redirect()->route('users.index', ['tab' => 'akun'])
            ->with('success', 'Akun pengguna berhasil dihapus!');
    }
}