<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gtk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRoleController extends Controller
{
    public function index()
    {
        User::pastikanTabelRoleSiap();

        $users = User::with('gtk')->orderBy('id', 'asc')->get();
        $gtks  = Gtk::orderBy('nama_lengkap', 'asc')->get();

        $modulList = User::daftarModulSistem();
        $roleTarget = [
            'admin'          => 'Admin / TU',
            'guru'           => 'Guru',
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
        User::pastikanTabelRoleSiap();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
            'role'     => ['required', 'string'],
        ], [
            'email.unique' => 'Email tersebut sudah terdaftar pada akun lain!',
            'password.min' => 'Password minimal 4 karakter!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $user           = new User();
        $user->name     = strip_tags(trim($request->name));
        $user->email    = strtolower(trim($request->email));
        $user->password = Hash::make($request->password);
        $user->role     = $request->role;
        $user->gtk_id   = $request->gtk_id ?: null;
        $user->save();

        return redirect()->back()->with('success', 'Akun ' . $user->name . ' berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        User::pastikanTabelRoleSiap();

        $user = User::findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'  => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role'  => ['required', 'string'],
        ], [
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $user->name   = strip_tags(trim($request->name));
        $user->email  = strtolower(trim($request->email));
        $user->role   = $request->role;
        $user->gtk_id = $request->gtk_id ?: null;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data akun ' . $user->name . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan login!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function togglePermission(Request $request)
    {
        User::pastikanTabelRoleSiap();

        DB::table('role_permissions')->updateOrInsert(
            [
                'role'     => $request->role,
                'menu_key' => $request->menu_key,
            ],
            [
                'is_allowed' => $request->boolean('is_allowed'),
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Hak akses berhasil diperbarui!',
        ]);
    }

    public function impersonate($id)
    {
        $targetUser = User::findOrFail($id);
        session(['impersonate_original_id' => auth()->id()]);
        Auth::login($targetUser);

        return redirect()->route('dashboard')->with('success', 'Mode Audit Aktif: Melihat tampilan sebagai ' . $targetUser->name);
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