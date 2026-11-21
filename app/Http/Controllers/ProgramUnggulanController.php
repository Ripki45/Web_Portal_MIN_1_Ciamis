<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ProgramWriterAgent;
use App\Models\Ekskul;
use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\ProgramUnggulan;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgramUnggulanController extends Controller
{
    /**
     * Halaman Publik: Direktori Semua Program Unggulan
     */
    public function publik(Request $request): View
    {
        ProgramUnggulan::pastikanTabelSiap();

        $query = ProgramUnggulan::where('is_active', true);

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori_badge', $request->kategori);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('ringkasan', 'like', "%{$q}%")
                    ->orWhere('kategori_badge', 'like', "%{$q}%")
                    ->orWhere('isi_konten', 'like', "%{$q}%");
            });
        }

        $programs = $query->orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();
        $totalSemua = ProgramUnggulan::where('is_active', true)->count();
        $daftarKategori = ProgramUnggulan::where('is_active', true)->pluck('kategori_badge')->unique()->values();

        return view('frontend.program_unggulan', compact('programs', 'totalSemua', 'daftarKategori'));
    }

    /**
     * Halaman Publik: Detail Program Unggulan
     */
    public function detail(string $slug): View
    {
        ProgramUnggulan::pastikanTabelSiap();

        $program = ProgramUnggulan::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $program->increment('views');

        $programLain = ProgramUnggulan::where('is_active', true)
            ->where('id', '!=', $program->id)
            ->orderBy('urutan', 'asc')
            ->take(5)
            ->get();

        $statMadrasah = [
            'Siswa Aktif' => Siswa::where('status', 'Aktif')->count(),
            'Guru & Tendik' => Gtk::count(),
            'Rombel Kelas' => Kelas::count(),
            'Ekstrakurikuler' => Ekskul::where('status', 'Aktif')->count(),
        ];

        return view('frontend.program_detail', compact('program', 'programLain', 'statMadrasah'));
    }

    /**
     * Dashboard: Daftar Program Unggulan
     */
    public function index(): View
    {
        ProgramUnggulan::pastikanTabelSiap();

        $programs = ProgramUnggulan::orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();
        $totalProgram = $programs->count();
        $totalAktif = $programs->where('is_active', true)->count();
        $iconRekomendasi = ProgramUnggulan::daftarIconRekomendasi();
        $urutanBerikutnya = ($programs->max('urutan') ?? 0) + 1;

        return view('program_unggulans.index', compact(
            'programs',
            'totalProgram',
            'totalAktif',
            'iconRekomendasi',
            'urutanBerikutnya'
        ));
    }

    /**
     * Daftar pilihan model AI untuk asisten penulisan program unggulan
     *
     * @return array<string, string>
     */
    public static function daftarModelAi(): array
    {
        return [
            'gemini-2.5-flash' => '✦ Gemini 2.5 Flash (Utama — Cepat & Andal)',
            'gemini-2.5-pro' => '✦ Gemini 2.5 Pro (Penulisan Mendalam)',
            'smart_local' => '⚡ Smart AI Studio Lokal (Instan Tanpa Kuota)',
        ];
    }

    /**
     * Dashboard: Form Tambah Program Unggulan Baru
     */
    public function create(): View
    {
        ProgramUnggulan::pastikanTabelSiap();

        $iconRekomendasi = ProgramUnggulan::daftarIconRekomendasi();
        $urutanBerikutnya = (ProgramUnggulan::max('urutan') ?? 0) + 1;
        $daftarModelAi = self::daftarModelAi();

        return view('program_unggulans.create', compact('iconRekomendasi', 'urutanBerikutnya', 'daftarModelAi'));
    }

    /**
     * Dashboard: Simpan Program Unggulan Baru
     */
    public function store(Request $request): RedirectResponse
    {
        ProgramUnggulan::pastikanTabelSiap();

        $validated = $request->validate([
            'judul' => 'required|string|max:160',
            'kategori_badge' => 'required|string|max:80',
            'icon' => 'required|string|max:80',
            'ringkasan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'kutipan' => 'nullable|string|max:600',
            'keunggulan_poin' => 'nullable|string',
            'mini_feature_title' => 'nullable|array',
            'mini_feature_title.*' => 'nullable|string|max:100',
            'mini_feature_desc' => 'nullable|array',
            'mini_feature_desc.*' => 'nullable|string|max:255',
            'mini_feature_icon' => 'nullable|array',
            'mini_feature_icon.*' => 'nullable|string|max:80',
            'pilar_title' => 'nullable|array',
            'pilar_title.*' => 'nullable|string|max:120',
            'pilar_desc' => 'nullable|array',
            'pilar_desc.*' => 'nullable|string|max:500',
            'pilar_icon' => 'nullable|array',
            'pilar_icon.*' => 'nullable|string|max:80',
            'kegiatan_judul' => 'nullable|string|max:220',
            'kegiatan_badge' => 'nullable|string|max:100',
            'kegiatan_deskripsi' => 'nullable|string|max:500',
            'kegiatan_card_badge' => 'nullable|array',
            'kegiatan_card_badge.*' => 'nullable|string|max:100',
            'kegiatan_card_title' => 'nullable|array',
            'kegiatan_card_title.*' => 'nullable|string|max:220',
            'kegiatan_card_desc' => 'nullable|array',
            'kegiatan_card_desc.*' => 'nullable|string|max:1000',
            'isi_konten' => 'nullable|string',
            'penulis' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
        ], [
            'judul.required' => 'Nama program unggulan wajib diisi.',
            'kategori_badge.required' => 'Label kategori/badge wajib diisi.',
            'icon.required' => 'Ikon Font Awesome program wajib dipilih/diisi.',
            'gambar.max' => 'Ukuran gambar sampul maksimal 3 MB.',
        ]);

        $baseSlug = Str::slug($validated['judul']) ?: 'program-'.time();
        $slug = $baseSlug;
        $counter = 1;
        while (ProgramUnggulan::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('program_unggulan', 'public');
        }

        $poinList = [];
        if (! empty($validated['keunggulan_poin'])) {
            $lines = explode("\n", str_replace("\r", '', $validated['keunggulan_poin']));
            $poinList = array_values(array_filter(array_map('trim', $lines)));
        }

        // Susun 2 Mini Features Section 1
        $miniFeatures = [];
        if (! empty($request->input('mini_feature_title'))) {
            foreach ($request->input('mini_feature_title') as $i => $title) {
                if (! empty($title) || ! empty($request->input("mini_feature_desc.{$i}"))) {
                    $miniFeatures[] = [
                        'icon' => $request->input("mini_feature_icon.{$i}") ?: 'fas fa-shield-alt',
                        'title' => strip_tags(trim((string) $title)),
                        'desc' => strip_tags(trim((string) $request->input("mini_feature_desc.{$i}"))),
                    ];
                }
            }
        }

        // Susun 4 Pilar Section 2
        $pilarList = [];
        if (! empty($request->input('pilar_title'))) {
            foreach ($request->input('pilar_title') as $i => $title) {
                if (! empty($title) || ! empty($request->input("pilar_desc.{$i}"))) {
                    $pilarList[] = [
                        'icon' => $request->input("pilar_icon.{$i}") ?: 'fas fa-award',
                        'title' => strip_tags(trim((string) $title)),
                        'desc' => strip_tags(trim((string) $request->input("pilar_desc.{$i}"))),
                    ];
                }
            }
        }

        // Susun 6 Kegiatan Aktivitas Section 3
        $kegiatanList = [];
        if (! empty($request->input('kegiatan_card_title'))) {
            foreach ($request->input('kegiatan_card_title') as $i => $cardTitle) {
                if (! empty($cardTitle) || ! empty($request->input("kegiatan_card_desc.{$i}"))) {
                    $kegiatanList[] = [
                        'badge' => strip_tags(trim((string) ($request->input("kegiatan_card_badge.{$i}") ?: 'Kegiatan'))),
                        'title' => strip_tags(trim((string) $cardTitle)),
                        'desc' => strip_tags(trim((string) $request->input("kegiatan_card_desc.{$i}"))),
                    ];
                }
            }
        }

        ProgramUnggulan::create([
            'judul' => strip_tags(trim($validated['judul'])),
            'slug' => $slug,
            'kategori_badge' => strip_tags(trim($validated['kategori_badge'])),
            'icon' => trim($validated['icon']),
            'ringkasan' => trim((string) ($validated['ringkasan'] ?? '')),
            'gambar_sampul' => $gambarPath,
            'kutipan' => strip_tags(trim((string) ($validated['kutipan'] ?? ''))),
            'mini_features' => $miniFeatures,
            'keunggulan_poin' => $poinList,
            'pilar_list' => $pilarList,
            'kegiatan_badge' => strip_tags(trim((string) ($validated['kegiatan_badge'] ?? ''))),
            'kegiatan_judul' => strip_tags(trim((string) ($validated['kegiatan_judul'] ?? ''))),
            'kegiatan_deskripsi' => strip_tags(trim((string) ($validated['kegiatan_deskripsi'] ?? ''))),
            'kegiatan_list' => $kegiatanList,
            'isi_konten' => $validated['isi_konten'] ?? null,
            'penulis' => strip_tags(trim((string) ($validated['penulis'] ?: 'Tim Kurikulum MIN 1 Ciamis'))),
            'urutan' => (int) ($validated['urutan'] ?? ((ProgramUnggulan::max('urutan') ?? 0) + 1)),
            'views' => 1,
            'is_active' => $request->has('is_active'),
        ]);

        HomeController::bersihkanCache();

        return redirect()->route('program-unggulan.index')
            ->with('success', 'Program unggulan "'.e($validated['judul']).'" berhasil ditambahkan!');
    }

    /**
     * Dashboard: Form Edit Program Unggulan
     */
    public function edit(int $id): View
    {
        ProgramUnggulan::pastikanTabelSiap();
        $program = ProgramUnggulan::findOrFail($id);
        $iconRekomendasi = ProgramUnggulan::daftarIconRekomendasi();
        $daftarModelAi = self::daftarModelAi();

        return view('program_unggulans.edit', compact('program', 'iconRekomendasi', 'daftarModelAi'));
    }

    /**
     * Dashboard: Perbarui Data Program Unggulan
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        ProgramUnggulan::pastikanTabelSiap();
        $program = ProgramUnggulan::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:160',
            'kategori_badge' => 'required|string|max:80',
            'icon' => 'required|string|max:80',
            'ringkasan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'kutipan' => 'nullable|string|max:600',
            'keunggulan_poin' => 'nullable|string',
            'mini_feature_title' => 'nullable|array',
            'mini_feature_title.*' => 'nullable|string|max:100',
            'mini_feature_desc' => 'nullable|array',
            'mini_feature_desc.*' => 'nullable|string|max:255',
            'mini_feature_icon' => 'nullable|array',
            'mini_feature_icon.*' => 'nullable|string|max:80',
            'pilar_title' => 'nullable|array',
            'pilar_title.*' => 'nullable|string|max:120',
            'pilar_desc' => 'nullable|array',
            'pilar_desc.*' => 'nullable|string|max:500',
            'pilar_icon' => 'nullable|array',
            'pilar_icon.*' => 'nullable|string|max:80',
            'kegiatan_judul' => 'nullable|string|max:220',
            'kegiatan_badge' => 'nullable|string|max:100',
            'kegiatan_deskripsi' => 'nullable|string|max:500',
            'kegiatan_card_badge' => 'nullable|array',
            'kegiatan_card_badge.*' => 'nullable|string|max:100',
            'kegiatan_card_title' => 'nullable|array',
            'kegiatan_card_title.*' => 'nullable|string|max:220',
            'kegiatan_card_desc' => 'nullable|array',
            'kegiatan_card_desc.*' => 'nullable|string|max:1000',
            'isi_konten' => 'nullable|string',
            'penulis' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
        ], [
            'judul.required' => 'Nama program unggulan wajib diisi.',
            'kategori_badge.required' => 'Label kategori/badge wajib diisi.',
            'icon.required' => 'Ikon Font Awesome program wajib dipilih/diisi.',
        ]);

        $poinList = [];
        if (! empty($validated['keunggulan_poin'])) {
            $lines = explode("\n", str_replace("\r", '', $validated['keunggulan_poin']));
            $poinList = array_values(array_filter(array_map('trim', $lines)));
        }

        // Susun 2 Mini Features Section 1
        $miniFeatures = [];
        if (! empty($request->input('mini_feature_title'))) {
            foreach ($request->input('mini_feature_title') as $i => $title) {
                if (! empty($title) || ! empty($request->input("mini_feature_desc.{$i}"))) {
                    $miniFeatures[] = [
                        'icon' => $request->input("mini_feature_icon.{$i}") ?: 'fas fa-shield-alt',
                        'title' => strip_tags(trim((string) $title)),
                        'desc' => strip_tags(trim((string) $request->input("mini_feature_desc.{$i}"))),
                    ];
                }
            }
        }

        // Susun 4 Pilar Section 2
        $pilarList = [];
        if (! empty($request->input('pilar_title'))) {
            foreach ($request->input('pilar_title') as $i => $title) {
                if (! empty($title) || ! empty($request->input("pilar_desc.{$i}"))) {
                    $pilarList[] = [
                        'icon' => $request->input("pilar_icon.{$i}") ?: 'fas fa-award',
                        'title' => strip_tags(trim((string) $title)),
                        'desc' => strip_tags(trim((string) $request->input("pilar_desc.{$i}"))),
                    ];
                }
            }
        }

        // Susun 6 Kegiatan Aktivitas Section 3
        $kegiatanList = [];
        if (! empty($request->input('kegiatan_card_title'))) {
            foreach ($request->input('kegiatan_card_title') as $i => $cardTitle) {
                if (! empty($cardTitle) || ! empty($request->input("kegiatan_card_desc.{$i}"))) {
                    $kegiatanList[] = [
                        'badge' => strip_tags(trim((string) ($request->input("kegiatan_card_badge.{$i}") ?: 'Kegiatan'))),
                        'title' => strip_tags(trim((string) $cardTitle)),
                        'desc' => strip_tags(trim((string) $request->input("kegiatan_card_desc.{$i}"))),
                    ];
                }
            }
        }

        $data = [
            'judul' => strip_tags(trim($validated['judul'])),
            'kategori_badge' => strip_tags(trim($validated['kategori_badge'])),
            'icon' => trim($validated['icon']),
            'ringkasan' => trim((string) ($validated['ringkasan'] ?? '')),
            'kutipan' => strip_tags(trim((string) ($validated['kutipan'] ?? ''))),
            'mini_features' => $miniFeatures,
            'keunggulan_poin' => $poinList,
            'pilar_list' => $pilarList,
            'kegiatan_badge' => strip_tags(trim((string) ($validated['kegiatan_badge'] ?? ''))),
            'kegiatan_judul' => strip_tags(trim((string) ($validated['kegiatan_judul'] ?? ''))),
            'kegiatan_deskripsi' => strip_tags(trim((string) ($validated['kegiatan_deskripsi'] ?? ''))),
            'kegiatan_list' => $kegiatanList,
            'isi_konten' => $validated['isi_konten'] ?? null,
            'penulis' => strip_tags(trim((string) ($validated['penulis'] ?: $program->penulis))),
            'urutan' => (int) ($validated['urutan'] ?? $program->urutan),
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            if ($program->gambar_sampul && ! str_starts_with($program->gambar_sampul, 'frontend/') && Storage::disk('public')->exists($program->gambar_sampul)) {
                Storage::disk('public')->delete($program->gambar_sampul);
            }
            $data['gambar_sampul'] = $request->file('gambar')->store('program_unggulan', 'public');
        }

        $program->update($data);
        HomeController::bersihkanCache();

        return redirect()->route('program-unggulan.index')
            ->with('success', 'Program unggulan "'.e($program->judul).'" berhasil diperbarui!');
    }

    /**
     * Dashboard: Hapus Program Unggulan
     */
    public function destroy(int $id): RedirectResponse
    {
        ProgramUnggulan::pastikanTabelSiap();
        $program = ProgramUnggulan::findOrFail($id);

        if ($program->gambar_sampul && ! str_starts_with($program->gambar_sampul, 'frontend/') && Storage::disk('public')->exists($program->gambar_sampul)) {
            Storage::disk('public')->delete($program->gambar_sampul);
        }

        $judul = $program->judul;
        $program->delete();
        HomeController::bersihkanCache();

        return redirect()->route('program-unggulan.index')
            ->with('success', 'Program unggulan "'.e($judul).'" berhasil dihapus!');
    }

    /**
     * Dashboard: Toggle Status Aktif / Nonaktif
     */
    public function toggleStatus(Request $request, int $id): JsonResponse|RedirectResponse
    {
        ProgramUnggulan::pastikanTabelSiap();
        $program = ProgramUnggulan::findOrFail($id);
        $program->is_active = ! $program->is_active;
        $program->save();

        HomeController::bersihkanCache();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $program->is_active,
                'message' => 'Status program "'.$program->judul.'" berhasil diubah menjadi '.($program->is_active ? 'Aktif' : 'Nonaktif').'.',
            ]);
        }

        return redirect()->back()->with('success', 'Status program berhasil diubah!');
    }

    /**
     * GENERATE NARASI PROGRAM UNGGULAN DENGAN GEMINI AI & LARAVEL AI SDK
     */
    public function generateAiProgram(Request $request): JsonResponse
    {
        set_time_limit(120);

        $request->validate([
            'topik' => ['required', 'string', 'min:3', 'max:600'],
            'kategori' => ['nullable', 'string'],
            'nada' => ['nullable', 'string'],
            'engine' => ['nullable', 'string'],
            'api_key' => ['nullable', 'string'],
        ]);

        $topik = trim($request->topik);
        $kategori = $request->kategori ?: 'Program Unggulan';
        $nada = $request->nada ?: 'Formal, Edukatif, dan Inspiratif';
        $engine = $request->engine ?: 'gemini-2.5-flash';

        // 1. Jika user memilih Smart AI Studio Lokal
        if ($engine === 'smart_local') {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal MIN 1 Ciamis',
                'data' => $this->susunProgramSmartLokal($topik, $kategori, $nada),
            ]);
        }

        // 2. Ambil API Key dari request atau config
        $apiKey = trim((string) $request->api_key);
        if (empty($apiKey)) {
            $apiKey = trim((string) config('services.gemini.key', ''));
        }

        if (empty($apiKey)) {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal (API Key Belum Diisi)',
                'data' => $this->susunProgramSmartLokal($topik, $kategori, $nada),
            ]);
        }

        // 3. Prioritas 1: Gunakan Laravel AI SDK Agent (ProgramWriterAgent)
        try {
            $agent = new ProgramWriterAgent;
            $agentPrompt = "Program Unggulan: {$topik}\nKategori: {$kategori}\nGaya Bahasa: {$nada}";
            $aiOutput = $agent->prompt($agentPrompt);

            if (! empty($aiOutput['ringkasan'])) {
                return response()->json([
                    'status' => 'success',
                    'source' => 'Laravel AI SDK (ProgramWriterAgent)',
                    'data' => [
                        'ringkasan' => $aiOutput['ringkasan'],
                        'kutipan' => $aiOutput['kutipan'] ?? "Melalui {$topik}, kami membentuk generasi berakhlak mulia dan berprestasi tinggi.",
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // Lanjut ke Direct HTTP API jika SDK Agent mengalami kendala
        }

        // 4. Prioritas 2: Direct HTTP Call ke Gemini API
        $prompt = 'Kamu adalah Penulis Konten & Pengembang Kurikulum Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). '
            ."Susunkan narasi pengantar program unggulan madrasah yang profesional, menyentuh hati orang tua, dan mendidik berdasarkan data berikut:\n"
            ."Nama Program / Topik: {$topik}\n"
            ."Kategori: {$kategori}\n"
            ."Gaya Bahasa: {$nada}\n\n"
            ."Wajib kembalikan respons HANYA dalam format JSON murni dengan struktur key berikut:\n"
            ."{\n"
            .'  "ringkasan": "Narasi pengantar program 3-5 kalimat dalam format HTML rich text menggunakan tag <p>, <strong>, dan <em>",'."\n"
            .'  "kutipan": "1 kalimat kutipan komitmen inspiratif yang mewakili jiwa program ini"'."\n"
            .'}';

        $baseUrl = config('services.gemini.endpoint', 'https://generativelanguage.googleapis.com/v1beta');
        $kandidatModel = array_values(array_unique(array_filter([
            $engine,
            config('services.gemini.model', 'gemini-2.5-flash'),
            config('services.gemini.model_fallback', 'gemini-2.5-flash-lite'),
        ])));

        foreach ($kandidatModel as $modelName) {
            if ($modelName === 'smart_local') {
                continue;
            }

            $urlEndpoint = "{$baseUrl}/models/{$modelName}:generateContent";

            try {
                $response = Http::withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])->withOptions([
                    'force_ip_resolve' => 'v4',
                    'verify' => false,
                ])->connectTimeout(15)->timeout(40)->post($urlEndpoint, [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $prompt]],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.5,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

                if ($response->successful()) {
                    $parts = $response->json('candidates.0.content.parts') ?? [];
                    $parsed = $this->ekstrakJsonGeminiProgram($parts, $topik, $kategori);

                    if (! empty($parsed['ringkasan'])) {
                        return response()->json([
                            'status' => 'success',
                            'source' => 'Google '.strtoupper($modelName),
                            'data' => $parsed,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Lanjut ke model berikutnya
            }
        }

        // Fallback cerdas lokal jika Gemini offline / kuota limit
        return response()->json([
            'status' => 'success',
            'source' => 'Smart AI Studio MIN 1 Ciamis (Auto-Fallback Lokal)',
            'data' => $this->susunProgramSmartLokal($topik, $kategori, $nada),
        ]);
    }

    /**
     * Ekstraktor JSON Gemini untuk Program Unggulan
     */
    private function ekstrakJsonGeminiProgram(array $parts, string $topik, string $kategori): array
    {
        $rawText = '';
        foreach ($parts as $part) {
            if (! empty($part['thought'])) {
                continue;
            }
            $rawText .= (string) ($part['text'] ?? '');
        }

        $cleaned = trim(str_replace(['```json', '```'], '', $rawText));
        $firstBrace = strpos($cleaned, '{');
        $lastBrace = strrpos($cleaned, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $cleaned = substr($cleaned, $firstBrace, ($lastBrace - $firstBrace) + 1);
        }

        $parsed = json_decode($cleaned, true);
        if (! is_array($parsed)) {
            $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $cleaned);
            $parsed = json_decode((string) $sanitized, true);
        }

        if (is_array($parsed) && ! empty($parsed['ringkasan'])) {
            return $parsed;
        }

        if (! empty(trim($rawText))) {
            return [
                'ringkasan' => '<p>'.nl2br(trim($rawText)).'</p>',
                'kutipan' => "Komitmen MIN 1 Ciamis dalam membina program {$topik} secara istiqamah.",
            ];
        }

        return [];
    }

    /**
     * Generator Draf Lokal Mandiri (Tanpa Kuota & Anti Gagal)
     */
    private function susunProgramSmartLokal(string $topik, string $kategori, string $nada): array
    {
        $topikRapih = ucwords($topik);
        $lower = strtolower($topik);

        if (str_contains($lower, 'anak') || str_contains($lower, 'ramah') || str_contains($lower, 'sra') || str_contains($lower, 'bullying')) {
            $kutipan = 'Membentuk madrasah yang tidak hanya mencetak insan berprestasi, tetapi juga menjadi rumah kedua yang hangat, aman, dan penuh kasih sayang bagi setiap anak.';
            $ringkasan = "<p>Madrasah Ibtidaiyah Negeri 1 Ciamis berkomitmen penuh menghadirkan program <strong>{$topikRapih}</strong> guna menciptakan iklim belajar yang inklusif, ramah anak, dan bebas dari segala bentuk perundungan (<em>zero-bullying</em>).</p><p>Melalui bimbingan konseling sahabat anak, sarana prasarana yang higienis, serta kemitraan harmonis bersama orang tua dalam program <em>parenting</em>, kami memastikan hak setiap anak terlindungi dan potensinya berkembang optimal lahir dan batin.</p>";
        } elseif (str_contains($lower, 'qur') || str_contains($lower, 'tahfidz') || str_contains($lower, 'tartil') || str_contains($lower, 'agama')) {
            $kutipan = 'Menjadikan Al-Qur\'an sebagai pedoman hidup santri, menghafalnya dengan hati, dan mengamalkannya dalam pekerti sehari-hari.';
            $ringkasan = "<p>Program unggulan <strong>{$topikRapih}</strong> di MIN 1 Ciamis dirancang sebagai wahana pembiasaan spiritual untuk mencetak generasi hafiz-hafizah yang berakhlak Qur'ani dan bertartil sesuai kaidah tajwid makharijul huruf.</p><p>Dengan metode pembelajaran <em>talaqqi</em> intensif dan bimbingan asatidz bersertifikasi, santri dibina dengan penuh kehangatan agar mencintai kalam Ilahi sejak usia dini.</p>";
        } elseif (str_contains($lower, 'sains') || str_contains($lower, 'riset') || str_contains($lower, 'ksm') || str_contains($lower, 'olimpiade') || str_contains($lower, 'robot')) {
            $kutipan = 'Mengasah daya nalar kritis, kecerdasan analitis, dan kecintaan terhadap sains dalam naungan tauhid.';
            $ringkasan = "<p>Melalui program <strong>{$topikRapih}</strong>, MIN 1 Ciamis memfasilitasi santri yang memiliki minat dan talenta di bidang sains, matematika, serta teknologi melalui pembinaan intensif dan praktikum ilmiah terintegrasi.</p><p>Santri dipersiapkan menjadi juara kompetisi sains tingkat kabupaten hingga nasional dengan tetap menjunjung tinggi kerendahan hati dan nilai-nilai ketakwaan kepada Allah SWT.</p>";
        } else {
            $kutipan = 'Mewujudkan madrasah hebat bermartabat dengan keunggulan karakter, integritas, dan kecakapan masa depan santri.';
            $ringkasan = "<p>Program <strong>{$topikRapih}</strong> merupakan salah satu pilar unggulan di MIN 1 Ciamis yang dirancang secara terpadu untuk membentuk kepribadian mulia, kecakapan akademik, dan keterampilan hidup abad ke-21.</p><p>Didukung oleh tenaga pendidik profesional dan lingkungan belajar yang inspiratif, madrasah kami terus berikhtiar mendampingi tumbuh kembang santri secara holistik dan berkesinambungan.</p>";
        }

        return [
            'ringkasan' => $ringkasan,
            'kutipan' => $kutipan,
        ];
    }
}
