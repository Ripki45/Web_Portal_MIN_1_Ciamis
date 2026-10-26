<?php

namespace App\Http\Controllers;

use App\Models\Gtk;
use App\Models\Jabatan;
use App\Models\Posisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class GtkController extends Controller
{
    /**
     * Otomatis memastikan kolom tambahan (alamat, no_hp, posisi_id, foto, ttd)
     * sudah tersedia di tabel gtks tanpa perlu menjalankan migrate manual di terminal
     */
    private function pastikanKolomGtkLengkap()
    {
        if (!Schema::hasColumn('gtks', 'posisi_id')) {
            Schema::table('gtks', function (Blueprint $table) {
                $table->unsignedBigInteger('posisi_id')->nullable();
            });
        }
        if (!Schema::hasColumn('gtks', 'no_hp')) {
            Schema::table('gtks', function (Blueprint $table) {
                $table->string('no_hp', 25)->nullable();
            });
        }
        if (!Schema::hasColumn('gtks', 'alamat')) {
            Schema::table('gtks', function (Blueprint $table) {
                $table->text('alamat')->nullable();
            });
        }
        if (!Schema::hasColumn('gtks', 'foto')) {
            Schema::table('gtks', function (Blueprint $table) {
                $table->string('foto')->nullable();
            });
        }
        if (!Schema::hasColumn('gtks', 'ttd')) {
            Schema::table('gtks', function (Blueprint $table) {
                $table->string('ttd')->nullable();
            });
        }
    }

    public function index()
    {
        $this->pastikanKolomGtkLengkap();

        // Urutkan data terbaru di paling atas (latest)
        $gtks     = Gtk::with(['jabatan', 'posisi', 'kelasWali'])->latest()->get();
        $jabatans = Jabatan::orderBy('id', 'asc')->get();
        $posisis  = Posisi::with('jabatan')->orderBy('nama_posisi', 'asc')->get();

        return view('gtk.index', compact('gtks', 'jabatans', 'posisis'));
    }

    public function store(Request $request)
    {
        $this->pastikanKolomGtkLengkap();

        $request->validate([
            'nomor_induk'        => 'nullable|string|max:50|unique:gtks,nomor_induk',
            'nama_lengkap'       => 'required|string|max:150',
            'jenis_kelamin'      => 'required|in:L,P',
            'jabatan_id'         => 'required|exists:jabatans,id',
            'posisi_id'          => 'nullable|exists:posisis,id',
            'status_kepegawaian' => 'required|in:PNS,PPPK,Honorer,Magang,Kontrak,Lainnya',
            'no_hp'              => 'nullable|string|max:25',
            'alamat'             => 'nullable|string',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ttd'                => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'nomor_induk.unique' => 'NIP / NUPTK / Nomor Induk ini sudah terdaftar!',
        ]);

        // Jika jabatannya Kepala Madrasah, kosongkan posisi_id karena jabatan tertinggi tunggal
        $jabatan  = Jabatan::find($request->jabatan_id);
        $isKepala = $jabatan && (stripos($jabatan->nama_jabatan, 'Kepala Madrasah') !== false || stripos($jabatan->nama_jabatan, 'Kepala Sekolah') !== false);
        $posisiId = $isKepala ? null : $request->posisi_id;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('gtk/foto', 'public');
        }

        $ttdPath = null;
        if ($request->hasFile('ttd')) {
            $ttdPath = $request->file('ttd')->store('gtk/ttd', 'public');
        }

        Gtk::create([
            'nomor_induk'        => $request->nomor_induk,
            'nama_lengkap'       => $request->nama_lengkap,
            'jenis_kelamin'      => $request->jenis_kelamin,
            'jabatan_id'         => $request->jabatan_id,
            'posisi_id'          => $posisiId,
            'status_kepegawaian' => $request->status_kepegawaian,
            'no_hp'              => $request->no_hp,
            'alamat'             => $request->alamat,
            'foto'               => $fotoPath,
            'ttd'                => $ttdPath,
        ]);

        return redirect()->back()->with('success', 'Data GTK baru berhasil ditambahkan di urutan teratas!');
    }

    public function update(Request $request, $id)
    {
        $this->pastikanKolomGtkLengkap();

        $gtk = Gtk::findOrFail($id);

        $request->validate([
            'nomor_induk'        => 'nullable|string|max:50|unique:gtks,nomor_induk,' . $gtk->id,
            'nama_lengkap'       => 'required|string|max:150',
            'jenis_kelamin'      => 'required|in:L,P',
            'jabatan_id'         => 'required|exists:jabatans,id',
            'posisi_id'          => 'nullable|exists:posisis,id',
            'status_kepegawaian' => 'required|in:PNS,PPPK,Honorer,Magang,Kontrak,Lainnya',
            'no_hp'              => 'nullable|string|max:25',
            'alamat'             => 'nullable|string',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ttd'                => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $jabatan  = Jabatan::find($request->jabatan_id);
        $isKepala = $jabatan && (stripos($jabatan->nama_jabatan, 'Kepala Madrasah') !== false || stripos($jabatan->nama_jabatan, 'Kepala Sekolah') !== false);
        $posisiId = $isKepala ? null : $request->posisi_id;

        $data = [
            'nomor_induk'        => $request->nomor_induk,
            'nama_lengkap'       => $request->nama_lengkap,
            'jenis_kelamin'      => $request->jenis_kelamin,
            'jabatan_id'         => $request->jabatan_id,
            'posisi_id'          => $posisiId,
            'status_kepegawaian' => $request->status_kepegawaian,
            'no_hp'              => $request->no_hp,
            'alamat'             => $request->alamat,
        ];

        if ($request->hasFile('foto')) {
            if ($gtk->foto && Storage::disk('public')->exists($gtk->foto)) {
                Storage::disk('public')->delete($gtk->foto);
            }
            $data['foto'] = $request->file('foto')->store('gtk/foto', 'public');
        }

        if ($request->hasFile('ttd')) {
            if ($gtk->ttd && Storage::disk('public')->exists($gtk->ttd)) {
                Storage::disk('public')->delete($gtk->ttd);
            }
            $data['ttd'] = $request->file('ttd')->store('gtk/ttd', 'public');
        }

        $gtk->update($data);

        return redirect()->back()->with('success', 'Data GTK berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $gtk = Gtk::findOrFail($id);

        if ($gtk->foto && Storage::disk('public')->exists($gtk->foto)) {
            Storage::disk('public')->delete($gtk->foto);
        }
        if ($gtk->ttd && Storage::disk('public')->exists($gtk->ttd)) {
            Storage::disk('public')->delete($gtk->ttd);
        }

        $gtk->delete();

        return redirect()->back()->with('success', 'Data GTK berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 data GTK yang ingin dihapus!');
        }

        $gtks = Gtk::whereIn('id', $ids)->get();
        foreach ($gtks as $gtk) {
            if ($gtk->foto && Storage::disk('public')->exists($gtk->foto)) {
                Storage::disk('public')->delete($gtk->foto);
            }
            if ($gtk->ttd && Storage::disk('public')->exists($gtk->ttd)) {
                Storage::disk('public')->delete($gtk->ttd);
            }
            $gtk->delete();
        }

        return redirect()->back()->with('success', count($gtks) . ' Data GTK terpilih berhasil dihapus!');
    }
}