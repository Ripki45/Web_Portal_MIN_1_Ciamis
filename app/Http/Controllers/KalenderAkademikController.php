<?php

namespace App\Http\Controllers;

use App\Models\AgendaAkademik;
use App\Models\KaldikDokumen;
use App\Models\TahunAjaran;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KalenderAkademikController extends Controller
{
    public function index(Request $request): View
    {
        $daftarTahunAjaran = $this->ambilDaftarTahunAjaran();
        $tahunAktif = (string) $request->query('tahun_ajaran', $daftarTahunAjaran->first() ?? '2026/2027');

        $agendas = AgendaAkademik::where('tahun_ajaran', $tahunAktif)
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $dokumen = KaldikDokumen::firstOrNew(
            ['tahun_ajaran' => $tahunAktif],
            [
                'dasar_hukum' => 'Pedoman Kalender Pendidikan Madrasah Tahun Ajaran '.$tahunAktif,
                'hari_kerja' => 6,
                'nama_kepsek' => 'IIM SITI HALIMAH, S.Ag., M.Pd.',
                'nip_kepsek' => '197206051997032003',
            ]
        );

        $analisisHeb = $this->hitungAnalisisHeb($tahunAktif, $agendas, (int) ($dokumen->hari_kerja ?: 6));
        $calendarEvents = $agendas->map(fn (AgendaAkademik $item) => $item->toCalendarEvent())->values();
        $daftarKategori = AgendaAkademik::daftarKategori();

        $user = $request->user();
        $bolehKelola = $user && (
            $user->role === 'superadmin'
            || $user->canAccess('tahun_ajaran')
            || $user->canAccess('cms')
        );

        // Tentukan tanggal awal kalender saat dibuka (Juli tahun ajaran terpilih)
        [$tahunMulai] = $this->uraiTahunAjaran($tahunAktif);
        $initialCalendarDate = now()->between("{$tahunMulai}-07-01", ($tahunMulai + 1).'-06-30')
            ? now()->format('Y-m-d')
            : "{$tahunMulai}-07-01";

        return view('kalender_akademik.index', compact(
            'daftarTahunAjaran',
            'tahunAktif',
            'agendas',
            'dokumen',
            'analisisHeb',
            'calendarEvents',
            'daftarKategori',
            'bolehKelola',
            'initialCalendarDate'
        ));
    }
    /**
     * Halaman Publik: Kalender & Agenda Akademik MIN 1 Ciamis
     */
    public function publik(Request $request): View
    {
        $daftarTahunAjaran = $this->ambilDaftarTahunAjaran();
        $tahunAktif = (string) $request->query('tahun_ajaran', $daftarTahunAjaran->first() ?? '2026/2027');

        $semuaAgendaTa = AgendaAkademik::where('tahun_ajaran', $tahunAktif)
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $agendasPublik = $semuaAgendaTa->where('is_publik', true)->values();

        $dokumen = KaldikDokumen::firstOrNew(
            ['tahun_ajaran' => $tahunAktif],
            [
                'dasar_hukum' => 'Pedoman Kalender Pendidikan Madrasah Tahun Ajaran '.$tahunAktif,
                'hari_kerja' => 6,
                'nama_kepsek' => 'IIM SITI HALIMAH, S.Ag., M.Pd.',
                'nip_kepsek' => '197206051997032003',
            ]
        );

        $analisisHeb = $this->hitungAnalisisHeb($tahunAktif, $semuaAgendaTa, (int) ($dokumen->hari_kerja ?: 6));
        $calendarEvents = $agendasPublik->map(fn (AgendaAkademik $item) => $item->toCalendarEvent())->values();
        $daftarKategori = AgendaAkademik::daftarKategori();

        [$tahunMulai] = $this->uraiTahunAjaran($tahunAktif);
        $initialCalendarDate = now()->between("{$tahunMulai}-07-01", ($tahunMulai + 1).'-06-30')
            ? now()->format('Y-m-d')
            : "{$tahunMulai}-07-01";

        return view('frontend.kalender_akademik', compact(
            'daftarTahunAjaran',
            'tahunAktif',
            'agendasPublik',
            'dokumen',
            'analisisHeb',
            'calendarEvents',
            'daftarKategori',
            'initialCalendarDate'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validasiAgenda($request);
        $validated['semester'] = $this->tentukanSemester($validated['tanggal_mulai']);
        $validated['is_hari_libur'] = $request->boolean('is_hari_libur');
        $validated['is_efektif_kbm'] = $validated['is_hari_libur'] ? false : $request->boolean('is_efektif_kbm', true);
        $validated['is_publik'] = $request->boolean('is_publik', true);

        $agenda = AgendaAkademik::create($validated);

        if ($agenda->is_hari_libur) {
            $this->simpanKeHariLiburPresensi($agenda);
        }

        return redirect()
            ->route('kalender-akademik.index', ['tahun_ajaran' => $agenda->tahun_ajaran])
            ->with('success', 'Agenda "'.$agenda->judul.'" berhasil ditambahkan'.($agenda->is_hari_libur ? ' & otomatis disinkronkan ke Hari Libur Presensi.' : '.'));
    }

    public function update(Request $request, AgendaAkademik $agendaAkademik): RedirectResponse
    {
        $lama = [
            'is_hari_libur' => $agendaAkademik->is_hari_libur,
            'tanggal_mulai' => $agendaAkademik->tanggal_mulai->format('Y-m-d'),
            'tanggal_selesai' => $agendaAkademik->tanggal_selesai->format('Y-m-d'),
            'judul' => $agendaAkademik->judul,
        ];

        $validated = $this->validasiAgenda($request);
        $validated['semester'] = $this->tentukanSemester($validated['tanggal_mulai']);
        $validated['is_hari_libur'] = $request->boolean('is_hari_libur');
        $validated['is_efektif_kbm'] = $validated['is_hari_libur'] ? false : $request->boolean('is_efektif_kbm', true);
        $validated['is_publik'] = $request->boolean('is_publik', true);

        $agendaAkademik->update($validated);

        // Jika sebelumnya adalah hari libur, hapus entri lama yang persis cocok agar tidak menyisakan duplikat
        if ($lama['is_hari_libur']) {
            HariLibur::where('tanggal_mulai', $lama['tanggal_mulai'])
                ->where('tanggal_selesai', $lama['tanggal_selesai'])
                ->where('keterangan', $lama['judul'])
                ->delete();
        }

        if ($agendaAkademik->is_hari_libur) {
            $this->simpanKeHariLiburPresensi($agendaAkademik);
        }

        return redirect()
            ->route('kalender-akademik.index', ['tahun_ajaran' => $agendaAkademik->tahun_ajaran])
            ->with('success', 'Agenda "'.$agendaAkademik->judul.'" berhasil diperbarui.');
    }

    public function destroy(AgendaAkademik $agendaAkademik): RedirectResponse
    {
        $ta = $agendaAkademik->tahun_ajaran;
        $judul = $agendaAkademik->judul;

        if ($agendaAkademik->is_hari_libur) {
            HariLibur::where('tanggal_mulai', $agendaAkademik->tanggal_mulai->format('Y-m-d'))
                ->where('tanggal_selesai', $agendaAkademik->tanggal_selesai->format('Y-m-d'))
                ->where('keterangan', $agendaAkademik->judul)
                ->delete();
        }

        $agendaAkademik->delete();

        return redirect()
            ->route('kalender-akademik.index', ['tahun_ajaran' => $ta])
            ->with('success', 'Agenda "'.$judul.'" berhasil dihapus.');
    }

    /**
     * Sinkronisasi massal seluruh agenda berstatus Hari Libur ke tabel hari_liburs (Presensi)
     */
    public function sinkronHariLibur(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $daftarLibur = AgendaAkademik::where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where('is_hari_libur', true)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        $baru = 0;
        $diperbarui = 0;

        foreach ($daftarLibur as $agenda) {
            $record = $this->simpanKeHariLiburPresensi($agenda);
            if ($record->wasRecentlyCreated) {
                $baru++;
            } else {
                $diperbarui++;
            }
        }

        return redirect()
            ->route('kalender-akademik.index', ['tahun_ajaran' => $validated['tahun_ajaran']])
            ->with('success', "Sinkronisasi Hari Libur Presensi selesai: {$baru} ditambahkan, {$diperbarui} diperbarui (TA {$validated['tahun_ajaran']}).");
    }

    private function simpanKeHariLiburPresensi(AgendaAkademik $agenda): HariLibur
    {
        return HariLibur::updateOrCreate(
            [
                'tanggal_mulai' => $agenda->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $agenda->tanggal_selesai->format('Y-m-d'),
            ],
            [
                'keterangan' => $agenda->judul,
            ]
        );
    }

    public function updateDokumen(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'dasar_hukum' => ['nullable', 'string', 'max:255'],
            'hari_kerja' => ['required', 'integer', 'in:5,6'],
            'tanggal_pengesahan' => ['nullable', 'date'],
            'nama_kepsek' => ['nullable', 'string', 'max:150'],
            'nip_kepsek' => ['nullable', 'string', 'max:50'],
            'catatan_kaki' => ['nullable', 'string', 'max:1000'],
            'file_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'hapus_pdf' => ['nullable', 'boolean'],
        ], [
            'file_pdf.mimes' => 'File dokumen Kaldik wajib berformat PDF.',
            'file_pdf.max' => 'Ukuran file PDF maksimal 10 MB.',
        ]);

        $dokumen = KaldikDokumen::firstOrNew(['tahun_ajaran' => $validated['tahun_ajaran']]);

        if ($request->boolean('hapus_pdf')) {
            if ($dokumen->file_pdf && Storage::disk('public')->exists($dokumen->file_pdf)) {
                Storage::disk('public')->delete($dokumen->file_pdf);
            }
            $dokumen->file_pdf = null;
        } elseif ($request->hasFile('file_pdf')) {
            if ($dokumen->file_pdf && Storage::disk('public')->exists($dokumen->file_pdf)) {
                Storage::disk('public')->delete($dokumen->file_pdf);
            }
            $dokumen->file_pdf = $request->file('file_pdf')->store('kaldik', 'public');
        }

        $dokumen->dasar_hukum = $validated['dasar_hukum'] ?? $dokumen->dasar_hukum;
        $dokumen->hari_kerja = (int) $validated['hari_kerja'];
        $dokumen->tanggal_pengesahan = $validated['tanggal_pengesahan'] ?? $dokumen->tanggal_pengesahan;
        $dokumen->nama_kepsek = $validated['nama_kepsek'] ?: 'IIM SITI HALIMAH, S.Ag., M.Pd.';
        $dokumen->nip_kepsek = $validated['nip_kepsek'] ?: '197206051997032003';
        $dokumen->catatan_kaki = $validated['catatan_kaki'] ?? null;
        $dokumen->save();

        return redirect()
            ->route('kalender-akademik.index', ['tahun_ajaran' => $dokumen->tahun_ajaran, 'tab' => 'pengaturan'])
            ->with('success', 'Pengaturan dokumen Kaldik TA '.$dokumen->tahun_ajaran.' berhasil disimpan.');
    }

    public function cetakHeb(Request $request): View
    {
        $daftarTahunAjaran = $this->ambilDaftarTahunAjaran();
        $tahunAktif = (string) $request->query('tahun_ajaran', $daftarTahunAjaran->first() ?? '2026/2027');

        $agendas = AgendaAkademik::where('tahun_ajaran', $tahunAktif)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        $dokumen = KaldikDokumen::firstOrNew(
            ['tahun_ajaran' => $tahunAktif],
            [
                'dasar_hukum' => 'Pedoman Kalender Pendidikan Madrasah Tahun Ajaran '.$tahunAktif,
                'hari_kerja' => 6,
                'nama_kepsek' => 'IIM SITI HALIMAH, S.Ag., M.Pd.',
                'nip_kepsek' => '197206051997032003',
            ]
        );

        $analisisHeb = $this->hitungAnalisisHeb($tahunAktif, $agendas, (int) ($dokumen->hari_kerja ?: 6));

        return view('kalender_akademik.cetak_heb', compact('tahunAktif', 'agendas', 'dokumen', 'analisisHeb'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validasiAgenda(Request $request): array
    {
        return $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'judul' => ['required', 'string', 'max:160'],
            'kategori' => ['required', 'string', Rule::in(array_keys(AgendaAkademik::daftarKategori()))],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'judul.required' => 'Judul agenda wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);
    }

    private function tentukanSemester(string $tanggalMulai): string
    {
        $bulan = (int) Carbon::parse($tanggalMulai)->format('n');

        return $bulan >= 7 ? 'gasal' : 'genap';
    }

    /**
     * Mengambil daftar pilihan Tahun Ajaran secara aman dari tabel tahun_ajarans + data agenda.
     *
     * @return Collection<int, string>
     */
    private function ambilDaftarTahunAjaran(): Collection
    {
        $list = collect();

        if (class_exists(TahunAjaran::class)) {
            try {
                foreach (TahunAjaran::orderBy('id', 'desc')->get() as $row) {
                    $label = $row->tahun_ajaran ?? $row->tahun ?? $row->nama ?? null;
                    if (is_string($label) && trim($label) !== '') {
                        $list->push(trim($label));
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan jika tabel tahun_ajarans belum siap
            }
        }

        $dariAgenda = AgendaAkademik::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran');
        $list = $list->merge($dariAgenda)->merge(['2026/2027', '2025/2026']);

        return $list->filter()->unique()->sortDesc()->values();
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function uraiTahunAjaran(string $tahunAjaran): array
    {
        if (preg_match('/(\d{4})\s*\/\s*(\d{4})/', $tahunAjaran, $match)) {
            return [(int) $match[1], (int) $match[2]];
        }

        $y = (int) now()->format('Y');

        return [$y, $y + 1];
    }

    /**
     * Menghitung rekap Analisis Hari Efektif Belajar (HEB) otomatis selama 12 bulan (Juli - Juni).
     *
     * @param  Collection<int, AgendaAkademik>  $agendas
     * @return array<string, mixed>
     */
    private function hitungAnalisisHeb(string $tahunAjaran, Collection $agendas, int $hariKerja = 6): array
    {
        [$tahunMulai, $tahunAkhir] = $this->uraiTahunAjaran($tahunAjaran);

        $bulanGasal = [
            [$tahunMulai, 7, 'Juli'],
            [$tahunMulai, 8, 'Agustus'],
            [$tahunMulai, 9, 'September'],
            [$tahunMulai, 10, 'Oktober'],
            [$tahunMulai, 11, 'November'],
            [$tahunMulai, 12, 'Desember'],
        ];

        $bulanGenap = [
            [$tahunAkhir, 1, 'Januari'],
            [$tahunAkhir, 2, 'Februari'],
            [$tahunAkhir, 3, 'Maret'],
            [$tahunAkhir, 4, 'April'],
            [$tahunAkhir, 5, 'Mei'],
            [$tahunAkhir, 6, 'Juni'],
        ];

        $hitungSemester = function (array $daftarBulan) use ($agendas, $hariKerja): array {
            $rows = [];
            $totKalender = 0;
            $totAkhirPekan = 0;
            $totLibur = 0;
            $totEfektif = 0;

            foreach ($daftarBulan as [$year, $month, $namaBulan]) {
                $awalBulan = Carbon::create($year, $month, 1);
                $jumlahHari = $awalBulan->daysInMonth;

                $akhirPekan = 0;
                $libur = 0;
                $efektif = 0;
                $catatanSet = [];

                for ($day = 1; $day <= $jumlahHari; $day++) {
                    $tanggal = Carbon::create($year, $month, $day);
                    $isWeekend = $tanggal->isSunday() || ($hariKerja === 5 && $tanggal->isSaturday());

                    // Cek agenda pada tanggal ini
                    $agendaHariIni = $agendas->filter(function (AgendaAkademik $a) use ($tanggal) {
                        return $tanggal->between($a->tanggal_mulai, $a->tanggal_selesai);
                    });

                    foreach ($agendaHariIni as $ag) {
                        $catatanSet[$ag->id] = $ag->judul;
                    }

                    if ($isWeekend) {
                        $akhirPekan++;
                        continue;
                    }

                    $adaLibur = $agendaHariIni->contains(fn (AgendaAkademik $a) => $a->is_hari_libur);
                    if ($adaLibur) {
                        $libur++;
                    } else {
                        $efektif++;
                    }
                }

                $rows[] = [
                    'bulan_label' => "{$namaBulan} {$year}",
                    'hari_kalender' => $jumlahHari,
                    'hari_akhir_pekan' => $akhirPekan,
                    'hari_libur' => $libur,
                    'hari_efektif' => $efektif,
                    'keterangan' => count($catatanSet) > 0 ? implode('; ', array_values($catatanSet)) : 'KBM Efektif Penuh',
                ];

                $totKalender += $jumlahHari;
                $totAkhirPekan += $akhirPekan;
                $totLibur += $libur;
                $totEfektif += $efektif;
            }

            return [
                'rows' => $rows,
                'total_kalender' => $totKalender,
                'total_akhir_pekan' => $totAkhirPekan,
                'total_libur' => $totLibur,
                'total_efektif' => $totEfektif,
            ];
        };

        $gasal = $hitungSemester($bulanGasal);
        $genap = $hitungSemester($bulanGenap);

        return [
            'hari_kerja' => $hariKerja,
            'gasal' => $gasal,
            'genap' => $genap,
            'total_efektif_tahunan' => $gasal['total_efektif'] + $genap['total_efektif'],
            'total_libur_tahunan' => $gasal['total_libur'] + $genap['total_libur'],
        ];
    }
}