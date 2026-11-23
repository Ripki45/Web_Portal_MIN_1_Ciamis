<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAksesModul
{
    /**
     * Mengecek izin akses modul berdasarkan Role & Toggle Switch Superadmin.
     * Pemakaian di route:
     * - middleware('modul:superadmin') -> khusus Superadmin
     * - middleware('modul:cms') -> cek User::canAccess('cms')
     * - middleware('modul:kelas,siswa') -> boleh jika punya salah satu izin
     */
    public function handle(Request $request, Closure $next, string ...$moduls): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Superadmin selalu memiliki akses penuh
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Jika rute dikhususkan untuk superadmin
        if (in_array('superadmin', $moduls, true)) {
            abort(403, 'Akses ditolak: halaman ini khusus untuk Superadmin.');
        }

        // Cek apakah user memiliki izin minimal pada salah satu modul yang disyaratkan
        foreach ($moduls as $modul) {
            if ($user->canAccess(trim($modul))) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak: akun Anda tidak memiliki izin untuk membuka modul ini.');
    }
}