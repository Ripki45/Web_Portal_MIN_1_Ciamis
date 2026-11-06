<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Ekskul;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Data otomatis dari tabel akademik yang sudah ada
        $gtks        = Gtk::with(['jabatan', 'posisi'])->orderBy('id', 'asc')->get();
        $ekskuls     = Ekskul::with(['pembina', 'anggotas'])->where('status', 'Aktif')->get();
        $totalSiswa  = Siswa::where('status', 'Aktif')->count();
        $totalGuru   = $gtks->count();
        $totalKelas  = Kelas::count();
        $totalEkskul = $ekskuls->count();

        return view('frontend.home', compact(
            'gtks', 'ekskuls', 'totalSiswa', 'totalGuru', 'totalKelas', 'totalEkskul'
        ));
    }
}