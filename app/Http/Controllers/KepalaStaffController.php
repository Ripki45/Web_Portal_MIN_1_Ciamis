<?php

namespace App\Http\Controllers;

use App\Models\SambutanProfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KepalaStaffController extends Controller
{
    /**
     * Halaman Dashboard Manajemen Kepala Madrasah & Staff Manajemen
     */
    public function index()
    {
        $data = SambutanProfil::ambilData();

        return view('kepala_staff.index', compact('data'));
    }

    /**
     * Simpan Sambutan Kepala Madrasah & Daftar Staff Manajemen
     */
    public function update(Request $request)
    {
        $data = SambutanProfil::ambilData();

        $request->validate([
            'nama_kepsek' => ['required', 'string', 'max:150'],
            'jabatan_kepsek' => ['required', 'string', 'max:120'],
            'nip_kepsek' => ['nullable', 'string', 'max:60'],
            'foto_kepsek' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'sambutan_kutipan' => ['required', 'string', 'max:350'],
            'sambutan_isi' => ['required', 'string'],
        ]);

        // Susun Daftar Staff Manajemen beserta Upload Foto Masing-Masing
        $timList = [];
        $tNamas = $request->input('tim_nama', []);
        $tJabatans = $request->input('tim_jabatan', []);
        $tBidangs = $request->input('tim_bidang', []);
        $tNips = $request->input('tim_nip', []);
        $tFotoLamas = $request->input('tim_foto_lama', []);
        $tFotoFiles = $request->file('tim_foto', []);

        for ($i = 0; $i < count($tNamas); $i++) {
            $nama = strip_tags(trim((string) ($tNamas[$i] ?? '')));
            if (empty($nama)) {
                continue;
            }

            $pathFoto = trim((string) ($tFotoLamas[$i] ?? ''));
            if (isset($tFotoFiles[$i]) && $tFotoFiles[$i]->isValid()) {
                if (! empty($pathFoto) && ! str_starts_with($pathFoto, 'frontend/') && Storage::disk('public')->exists($pathFoto)) {
                    Storage::disk('public')->delete($pathFoto);
                }
                $pathFoto = $tFotoFiles[$i]->store('kepsek_staff/tim', 'public');
            }

            $timList[] = [
                'nama' => $nama,
                'jabatan' => strip_tags(trim((string) ($tJabatans[$i] ?? 'Staff Manajemen'))),
                'bidang' => strip_tags(trim((string) ($tBidangs[$i] ?? 'Manajemen'))),
                'nip' => strip_tags(trim((string) ($tNips[$i] ?? ''))),
                'foto' => $pathFoto,
            ];
        }

        $updateData = [
            'nama_kepsek' => strip_tags(trim($request->nama_kepsek)),
            'jabatan_kepsek' => strip_tags(trim($request->jabatan_kepsek)),
            'nip_kepsek' => strip_tags(trim((string) $request->nip_kepsek)),
            'sambutan_kutipan' => strip_tags(trim($request->sambutan_kutipan)),
            'sambutan_isi' => trim($request->sambutan_isi),
            'tim_manajemen_list' => $timList,
        ];

        if ($request->hasFile('foto_kepsek')) {
            if ($data->foto_kepsek && ! str_starts_with($data->foto_kepsek, 'frontend/') && Storage::disk('public')->exists($data->foto_kepsek)) {
                Storage::disk('public')->delete($data->foto_kepsek);
            }
            $updateData['foto_kepsek'] = $request->file('foto_kepsek')->store('kepsek', 'public');
        }

        $data->update($updateData);

        return redirect()->back()->with('success', 'Data Sambutan Kepala Madrasah & Staff Manajemen berhasil diperbarui!');
    }

    /**
     * Halaman Publik Sub-Menu 2: Kepala Madrasah dan Staff (/kepala-madrasah-dan-staff)
     */
    public function showFrontend()
    {
        $data = SambutanProfil::ambilData();

        return view('frontend.kepala_staff', compact('data'));
    }
}
