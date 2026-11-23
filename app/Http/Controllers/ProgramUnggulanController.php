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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgramUnggulanController extends Controller
{
    /**
     * Halaman Publik: Direktori Semua Program Unggulan
     */
    public function publik(): View
    {
        $programs = ProgramUnggulan::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalSemua = $programs->count();
        $daftarKategori = $programs->pluck('kategori_badge')->filter()->unique()->values();

        return view('frontend.program_unggulan', compact('programs', 'totalSemua', 'daftarKategori'));
    }

    /**
     * Halaman Publik: Detail Program Unggulan
     */
    public function detail(string $slug): View
    {
        $program = ProgramUnggulan::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $program->increment('views');

        $programLain = ProgramUnggulan::where('is_active', true)
            ->where('id', '!=', $program->id)
            ->orderBy('urutan', 'asc')
            ->take(5)
            ->get();

        $statMadrasah = Cache::remember('stat_madrasah_ringkas', now()->addMinutes(5), function () {
            return [
                'Siswa Aktif' => Siswa::where('status', 'Aktif')->count(),
                'Guru & Tendik' => Gtk::count(),
                'Rombel Kelas' => Kelas::count(),
                'Ekstrakurikuler' => Ekskul::where('status', 'Aktif')->count(),
            ];
        });

        return view('frontend.program_detail', compact('program', 'programLain', 'statMadrasah'));
    }

    /**
     * Dashboard: Daftar Program Unggulan
     */
    public function index(): View
    {
        $programs = ProgramUnggulan::orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $totalProgram = $programs->count();
        $totalAktif = $programs->where('is_active', true)->count();

        return view('program_unggulans.index', compact('programs', 'totalProgram', 'totalAktif'));
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
        $validated = $this->validasiProgram($request);
        $data = $this->susunDataProgram($request, $validated);

        $baseSlug = Str::slug($data['judul']) ?: 'program-'.time();
        $slug = $baseSlug;
        $counter = 1;
        while (ProgramUnggulan::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $data['slug'] = $slug;
        $data['views'] = 0;

        if ($request->hasFile('gambar')) {
            $data['gambar_sampul'] = $request->file('gambar')->store('program_unggulan', 'public');
        }

        $program = ProgramUnggulan::create($data);
        HomeController::bersihkanCache();

        return redirect()->route('program-unggulan.index')
            ->with('success', 'Program unggulan "'.$program->judul.'" berhasil ditambahkan!');
    }

    /**
     * Dashboard: Form Edit Program Unggulan
     */
    public function edit(int $id): View
    {
        $program = ProgramUnggulan::findOrFail($id);
        $iconRekomendasi = ProgramUnggulan::daftarIconRekomendasi();
        $daftarModelAi = self::daftarModelAi();
        $urutanBerikutnya = $program->urutan;

        return view('program_unggulans.edit', compact('program', 'iconRekomendasi', 'daftarModelAi', 'urutanBerikutnya'));
    }

    /**
     * Dashboard: Perbarui Data Program Unggulan
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $program = ProgramUnggulan::findOrFail($id);
        $validated = $this->validasiProgram($request);
        $data = $this->susunDataProgram($request, $validated, $program);

        if ($request->hasFile('gambar')) {
            if (
                $program->gambar_sampul
                && ! str_starts_with($program->gambar_sampul, 'frontend/')
                && ! str_starts_with($program->gambar_sampul, 'backend/')
                && Storage::disk('public')->exists($program->gambar_sampul)
            ) {
                Storage::disk('public')->delete($program->gambar_sampul);
            }
            $data['gambar_sampul'] = $request->file('gambar')->store('program_unggulan', 'public');
        }

        $program->update($data);
        HomeController::bersihkanCache();

        return redirect()->route('program-unggulan.index')
            ->with('success', 'Program unggulan "'.$program->judul.'" berhasil diperbarui!');
    }

    /**
     * Dashboard: Hapus Program Unggulan
     */
    public function destroy(int $id): RedirectResponse
    {
        $program = ProgramUnggulan::findOrFail($id);

        if (
            $program->gambar_sampul
            && ! str_starts_with($program->gambar_sampul, 'frontend/')
            && ! str_starts_with($program->gambar_sampul, 'backend/')
            && Storage::disk('public')->exists($program->gambar_sampul)
        ) {
            Storage::disk('public')->delete($program->gambar_sampul);
        }

        $judul = $program->judul;
        $program->delete();
        HomeController::bersihkanCache();

        return redirect()->route('program-unggulan.index')
            ->with('success', 'Program unggulan "'.$judul.'" berhasil dihapus!');
    }

    /**
     * Dashboard: Toggle Status Aktif / Nonaktif
     */
    public function toggleStatus(Request $request, int $id): JsonResponse|RedirectResponse
    {
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

        return redirect()->back()->with('success', 'Status program "'.$program->judul.'" berhasil diubah!');
    }

    /**
     * Validasi terpadu untuk store & update (tanpa SVG demi keamanan XSS)
     *
     * @return array<string, mixed>
     */
    private function validasiProgram(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:160',
            'kategori_badge' => 'required|string|max:80',
            'icon' => 'required|string|max:80',
            'ringkasan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'kutipan' => 'nullable|string|max:600',
            'keunggulan_poin' => 'nullable|string',
            'mini_feature_title' => 'nullable|array|max:2',
            'mini_feature_title.*' => 'nullable|string|max:100',
            'mini_feature_desc' => 'nullable|array|max:2',
            'mini_feature_desc.*' => 'nullable|string|max:255',
            'mini_feature_icon' => 'nullable|array|max:2',
            'mini_feature_icon.*' => 'nullable|string|max:80',
            'pilar_title' => 'nullable|array|max:4',
            'pilar_title.*' => 'nullable|string|max:120',
            'pilar_desc' => 'nullable|array|max:4',
            'pilar_desc.*' => 'nullable|string|max:500',
            'pilar_icon' => 'nullable|array|max:4',
            'pilar_icon.*' => 'nullable|string|max:80',
            'kegiatan_judul' => 'nullable|string|max:220',
            'kegiatan_badge' => 'nullable|string|max:100',
            'kegiatan_deskripsi' => 'nullable|string|max:500',
            'kegiatan_card_badge' => 'nullable|array|max:6',
            'kegiatan_card_badge.*' => 'nullable|string|max:100',
            'kegiatan_card_title' => 'nullable|array|max:6',
            'kegiatan_card_title.*' => 'nullable|string|max:220',
            'kegiatan_card_desc' => 'nullable|array|max:6',
            'kegiatan_card_desc.*' => 'nullable|string|max:1000',
            'penulis' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
        ], [
            'judul.required' => 'Nama program unggulan wajib diisi.',
            'kategori_badge.required' => 'Label kategori/badge wajib diisi.',
            'icon.required' => 'Ikon Font Awesome program wajib dipilih atau diisi.',
            'gambar.image' => 'File sampul harus berupa gambar yang valid.',
            'gambar.mimes' => 'Format gambar sampul hanya mendukung JPG, JPEG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar sampul maksimal 3 MB.',
        ]);
    }

    /**
     * Merapikan payload input menjadi struktur siap simpan untuk Model.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function susunDataProgram(Request $request, array $validated, ?ProgramUnggulan $existing = null): array
    {
        $poinList = [];
        if (! empty($validated['keunggulan_poin'])) {
            $lines = explode("\n", str_replace("\r", '', $validated['keunggulan_poin']));
            $poinList = array_values(array_filter(array_map(fn ($line) => strip_tags(trim($line)), $lines)));
        }

        // Susun Mini Features (maksimal 2)
        $miniFeatures = [];
        $miniTitles = (array) $request->input('mini_feature_title', []);
        $miniDescs = (array) $request->input('mini_feature_desc', []);
        $miniIcons = (array) $request->input('mini_feature_icon', []);
        $jumlahMini = max(count($miniTitles), count($miniDescs));

        for ($i = 0; $i < min($jumlahMini, 2); $i++) {
            $title = strip_tags(trim((string) ($miniTitles[$i] ?? '')));
            $desc = strip_tags(trim((string) ($miniDescs[$i] ?? '')));
            if ($title !== '' || $desc !== '') {
                $miniFeatures[] = [
                    'icon' => $this->bersihkanClassIcon($miniIcons[$i] ?? '', 'fas fa-check-circle'),
                    'title' => $title,
                    'desc' => $desc,
                ];
            }
        }

        // Susun Pilar Program (maksimal 4)
        $pilarList = [];
        $pilarTitles = (array) $request->input('pilar_title', []);
        $pilarDescs = (array) $request->input('pilar_desc', []);
        $pilarIcons = (array) $request->input('pilar_icon', []);
        $jumlahPilar = max(count($pilarTitles), count($pilarDescs));

        for ($i = 0; $i < min($jumlahPilar, 4); $i++) {
            $title = strip_tags(trim((string) ($pilarTitles[$i] ?? '')));
            $desc = strip_tags(trim((string) ($pilarDescs[$i] ?? '')));
            if ($title !== '' || $desc !== '') {
                $pilarList[] = [
                    'icon' => $this->bersihkanClassIcon($pilarIcons[$i] ?? '', 'fas fa-award'),
                    'title' => $title,
                    'desc' => $desc,
                ];
            }
        }

        // Susun Kartu Kegiatan (maksimal 6)
        $kegiatanList = [];
        $kegTitles = (array) $request->input('kegiatan_card_title', []);
        $kegDescs = (array) $request->input('kegiatan_card_desc', []);
        $kegBadges = (array) $request->input('kegiatan_card_badge', []);
        $jumlahKeg = max(count($kegTitles), count($kegDescs));

        for ($i = 0; $i < min($jumlahKeg, 6); $i++) {
            $title = strip_tags(trim((string) ($kegTitles[$i] ?? '')));
            $desc = strip_tags(trim((string) ($kegDescs[$i] ?? '')));
            if ($title !== '' || $desc !== '') {
                $badge = strip_tags(trim((string) ($kegBadges[$i] ?? '')));
                $kegiatanList[] = [
                    'badge' => $badge !== '' ? $badge : 'Kegiatan',
                    'title' => $title,
                    'desc' => $desc,
                ];
            }
        }

        $defaultPenulis = $existing?->penulis ?: 'Tim Kurikulum MIN 1 Ciamis';
        $defaultUrutan = $existing?->urutan ?? ((ProgramUnggulan::max('urutan') ?? 0) + 1);

        return [
            'judul' => strip_tags(trim((string) $validated['judul'])),
            'kategori_badge' => strip_tags(trim((string) $validated['kategori_badge'])),
            'icon' => $this->bersihkanClassIcon($validated['icon'] ?? '', 'fas fa-star'),
            'ringkasan' => ProgramUnggulan::bersihkanHtml($validated['ringkasan'] ?? null),
            'kutipan' => strip_tags(trim((string) ($validated['kutipan'] ?? ''))) ?: null,
            'mini_features' => $miniFeatures,
            'keunggulan_poin' => $poinList,
            'pilar_list' => $pilarList,
            'kegiatan_badge' => strip_tags(trim((string) ($validated['kegiatan_badge'] ?? ''))) ?: null,
            'kegiatan_judul' => strip_tags(trim((string) ($validated['kegiatan_judul'] ?? ''))) ?: null,
            'kegiatan_deskripsi' => strip_tags(trim((string) ($validated['kegiatan_deskripsi'] ?? ''))) ?: null,
            'kegiatan_list' => $kegiatanList,
            'penulis' => strip_tags(trim((string) ($validated['penulis'] ?? ''))) ?: $defaultPenulis,
            'urutan' => (int) ($validated['urutan'] ?? $defaultUrutan),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    /**
     * Memastikan class ikon hanya berisi karakter CSS yang valid.
     */
    private function bersihkanClassIcon(?string $icon, string $fallback): string
    {
        $clean = trim((string) preg_replace('/[^a-zA-Z0-9\-\s_]/', '', (string) $icon));

        return $clean !== '' ? $clean : $fallback;
    }

    /**
     * GENERATE NARASI PROGRAM UNGGULAN DENGAN GEMINI AI & LARAVEL AI SDK
     */
    public function generateAiProgram(Request $request): JsonResponse
    {
        set_time_limit(120);

        $request->validate([
            'topik' => ['required', 'string', 'min:3', 'max:600'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'nada' => ['nullable', 'string', 'max:120'],
            'engine' => ['nullable', 'string', 'max:80'],
            'api_key' => ['nullable', 'string', 'max:200'],
        ]);

        $topik = trim((string) $request->topik);
        $kategori = trim((string) ($request->kategori ?: 'Program Unggulan'));
        $nada = trim((string) ($request->nada ?: 'Formal, Edukatif, dan Inspiratif'));
        $engine = trim((string) ($request->engine ?: 'gemini-2.5-flash'));

        // 1. Mode Smart AI Studio Lokal
        if ($engine === 'smart_local') {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal MIN 1 Ciamis',
                'data' => $this->susunProgramSmartLokal($topik),
            ]);
        }

        // 2. Ambil API Key dari request manual atau config (.env)
        $apiKey = trim((string) $request->api_key);
        if ($apiKey === '') {
            $apiKey = trim((string) config('services.gemini.key', ''));
        }

        if ($apiKey === '') {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal (API Key Belum Diisi)',
                'data' => $this->susunProgramSmartLokal($topik),
            ]);
        }

        // 3. Prioritas 1: Laravel AI SDK Agent (ProgramWriterAgent)
        try {
            $agent = new ProgramWriterAgent;
            $agentPrompt = "Program Unggulan: {$topik}\nKategori: {$kategori}\nGaya Bahasa: {$nada}";
            $aiOutput = $agent->prompt($agentPrompt);

            if (! empty($aiOutput['ringkasan'])) {
                return response()->json([
                    'status' => 'success',
                    'source' => 'Laravel AI SDK (ProgramWriterAgent)',
                    'data' => [
                        'ringkasan' => ProgramUnggulan::bersihkanHtml((string) $aiOutput['ringkasan']),
                        'kutipan' => strip_tags(trim((string) ($aiOutput['kutipan'] ?? "Melalui {$topik}, kami membentuk generasi berakhlak mulia dan berprestasi tinggi."))),
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('ProgramWriterAgent gagal, beralih ke Direct Gemini API.', [
                'error' => $e->getMessage(),
            ]);
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
                    'verify' => app()->isProduction(),
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
                    $parsed = $this->ekstrakJsonGeminiProgram($parts, $topik);

                    if (! empty($parsed['ringkasan'])) {
                        return response()->json([
                            'status' => 'success',
                            'source' => 'Google '.strtoupper($modelName),
                            'data' => [
                                'ringkasan' => ProgramUnggulan::bersihkanHtml((string) $parsed['ringkasan']),
                                'kutipan' => strip_tags(trim((string) ($parsed['kutipan'] ?? ''))),
                            ],
                        ]);
                    }
                } else {
                    Log::warning('Gemini API mengembalikan status gagal pada ProgramUnggulan.', [
                        'model' => $modelName,
                        'status' => $response->status(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal menghubungi Gemini API pada ProgramUnggulan.', [
                    'model' => $modelName,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Fallback lokal mandiri jika API offline / kena limit
        return response()->json([
            'status' => 'success',
            'source' => 'Smart AI Studio MIN 1 Ciamis (Auto-Fallback Lokal)',
            'data' => $this->susunProgramSmartLokal($topik),
        ]);
    }

    /**
     * Ekstraktor JSON Gemini untuk Program Unggulan
     *
     * @param  array<int, array<string, mixed>>  $parts
     * @return array<string, string>
     */
    private function ekstrakJsonGeminiProgram(array $parts, string $topik): array
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
            return [
                'ringkasan' => (string) $parsed['ringkasan'],
                'kutipan' => (string) ($parsed['kutipan'] ?? ''),
            ];
        }

        if (trim($rawText) !== '') {
            return [
                'ringkasan' => '<p>'.nl2br(e(trim($rawText))).'</p>',
                'kutipan' => "Komitmen MIN 1 Ciamis dalam membina program {$topik} secara istiqamah.",
            ];
        }

        return [];
    }

    /**
     * Generator Draf Lokal Mandiri (Tanpa Kuota & Anti Gagal)
     *
     * @return array{ringkasan: string, kutipan: string}
     */
    private function susunProgramSmartLokal(string $topik): array
    {
        $topikRapih = e(ucwords($topik));
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