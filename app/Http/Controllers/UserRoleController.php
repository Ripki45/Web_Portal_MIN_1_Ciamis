<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserRoleController extends Controller
{
    /**
     * KEAMANAN: Memastikan hanya Superadmin yang berwenang mengelola pengguna & hak akses
     */
    private function pastikanSuperadmin(): void
    {
        if (Auth::user()?->role !== 'superadmin') {
            abort(403, 'Akses Ditolak: Hanya Superadmin yang berwenang mengelola akun pengguna dan matriks hak akses sistem.');
        }
    }

    public function index()
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $users = User::with('gtk')->orderBy('id', 'asc')->get();
        $gtks = Gtk::orderBy('nama_lengkap', 'asc')->get();

        $modulList = User::daftarModulSistem();
        $roleTarget = [
            'admin' => 'Admin / TU',
            'guru' => 'Guru',
            'pembina_ekskul' => 'Pembina Ekskul',
        ];

        $matrix = DB::table('role_permissions')
            ->get()
            ->groupBy('role')
            ->map(function ($items) {
                return $items->pluck('is_allowed', 'menu_key')->toArray();
            })
            ->toArray();

        return view('users.index', compact(
            'users', 'gtks', 'modulList', 'roleTarget', 'matrix'
        ));
    }

    public function store(Request $request)
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'string', 'in:superadmin,admin,guru,pembina_ekskul'],
        ], [
            'email.unique' => 'Email tersebut sudah terdaftar pada akun lain!',
            'password.min' => 'Password minimal 4 karakter!',
            'role.in' => 'Pilihan peran pengguna tidak valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $user = new User;
        $user->name = strip_tags(trim($request->name));
        $user->email = strtolower(trim($request->email));
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->gtk_id = $request->gtk_id ?: null;
        $user->save();

        return redirect()->back()->with('success', 'Akun '.$user->name.' berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', 'in:superadmin,admin,guru,pembina_ekskul'],
        ], [
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain!',
            'role.in' => 'Pilihan peran pengguna tidak valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $user->name = strip_tags(trim($request->name));
        $user->email = strtolower(trim($request->email));
        $user->role = $request->role;
        $user->gtk_id = $request->gtk_id ?: null;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data akun '.$user->name.' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->pastikanSuperadmin();
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan login!');
        }

        if ($user->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus satu-satunya akun Superadmin!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function togglePermission(Request $request)
    {
        $this->pastikanSuperadmin();
        User::pastikanTabelRoleSiap();

        DB::table('role_permissions')->updateOrInsert(
            [
                'role' => $request->role,
                'menu_key' => $request->menu_key,
            ],
            [
                'is_allowed' => $request->boolean('is_allowed'),
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Hak akses berhasil diperbarui!',
        ]);
    }

    public function impersonate($id)
    {
        $this->pastikanSuperadmin();
        $targetUser = User::findOrFail($id);
        session(['impersonate_original_id' => auth()->id()]);
        Auth::login($targetUser);

        return redirect()->route('dashboard')->with('success', 'Mode Audit Aktif: Melihat tampilan sebagai '.$targetUser->name);
    }

    public function stopImpersonate()
    {
        $originalId = session('impersonate_original_id');
        if ($originalId) {
            $superAdmin = User::find($originalId);
            session()->forget('impersonate_original_id');
            if ($superAdmin) {
                Auth::login($superAdmin);

                return redirect()->route('users.index')->with('success', 'Kembali ke akun Super Admin.');
            }
        }

        return redirect()->route('dashboard');
    }
}
