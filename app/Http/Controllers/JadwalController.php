<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Gtk;
use App\Models\KelasMapel;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        User::pastikanKolomJadwalAda();

        $user      = auth()->user();
        $isGuru    = $user && $user->role === 'guru';
        $kelasList = $user ? $user->getDaftarKelasAmpuan() : Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $mapels    = Mapel::orderBy('nama_mapel', 'asc')->get();
        $gtks      = Gtk::orderBy('nama_lengkap', 'asc')->get();

        $selectedKelasId = $request->get('kelas_id', $kelasList->first()->id ?? null);
        $selectedHari    = $request->get('hari', '');

        $query = KelasMapel::with(['kelas', 'mapel', 'guru']);

        if ($isGuru) {
            $gtkId = $user->gtk_id ?: Gtk::where('nama_lengkap', 'like', '%' . trim($user->name) . '%')->value('id');
            $allowedKelas = $kelasList->pluck('id')->toArray();
            $query->where(function ($q) use ($gtkId, $allowedKelas) {
                $q->where('guru_id', $gtkId)->orWhereIn('kelas_id', $allowedKelas);
            });
        }

        if (!empty($selectedKelasId)) {
            $query->where('kelas_id', $selectedKelasId);
        }

        if (!empty($selectedHari)) {
            $query->where('hari', 'like', '%' . $selectedHari . '%');
        }

        $jadwals = $query->orderBy('kelas_id', 'asc')->orderBy('id', 'asc')->get();

        return view('jadwal.index', compact(
            'jadwals', 'kelasList', 'mapels', 'gtks', 'selectedKelasId', 'selectedHari', 'isGuru'
        ));
    }

    public function store(Request $request)
    {
        User::pastikanKolomJadwalAda();

        $request->validate([
            'kelas_id'      => 'required|integer',
            'mapel_id'      => 'required|integer',
            'guru_id'       => 'required|integer',
            'hari'          => 'required|string|max:30',
            'jam_pelajaran' => 'required|string|max:50',
        ]);

        KelasMapel::create([
            'kelas_id'      => $request->kelas_id,
            'mapel_id'      => $request->mapel_id,
            'guru_id'       => $request->guru_id,
            'hari'          => $request->hari,
            'jam_pelajaran' => trim($request->jam_pelajaran),
        ]);

        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil ditambahkan & dihubungkan ke Scanner QR!');
    }

    public function update(Request $request, $id)
    {
        User::pastikanKolomJadwalAda();

        $jadwal = KelasMapel::findOrFail($id);

        $request->validate([
            'guru_id'       => 'required|integer',
            'hari'          => 'required|string|max:30',
            'jam_pelajaran' => 'required|string|max:50',
        ]);

        $jadwal->update([
            'guru_id'       => $request->guru_id,
            'hari'          => $request->hari,
            'jam_pelajaran' => trim($request->jam_pelajaran),
        ]);

        return redirect()->back()->with('success', 'Hari & jam mengajar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        KelasMapel::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil dihapus!');
    }
}