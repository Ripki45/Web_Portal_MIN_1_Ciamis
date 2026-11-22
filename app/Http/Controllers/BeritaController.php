<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BeritaWriterAgent;
use App\Models\Berita;
use App\Models\Ekskul;
use App\Models\Gtk;
use App\Models\KategoriBerita;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $query = Berita::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beritas = $query->orderBy('id', 'desc')->get();
        $kategoris = KategoriBerita::withCount('beritas')->orderBy('nama_kategori', 'asc')->get();

        $totalBerita = Berita::count();
        $totalTayang = Berita::where('status', 'Tayang')->count();
        $totalDraft = Berita::where('status', 'Draft')->count();
        $totalPembaca = Berita::sum('views');

        return view('berita.index', compact(
            'beritas', 'kategoris', 'totalBerita', 'totalTayang', 'totalDraft', 'totalPembaca'
        ));
    }

    /**
     * SATU PINTU KONTROL DAFTAR MODEL AI
     */
    public static function daftarModelAi(): array
    {
        return [
            'gemini-2.5-flash' => '✦ Gemini 2.5 Flash (Utama — Cepat & Andal)',
            'gemini-2.5-pro' => '✦ Gemini 2.5 Pro (Penulisan Mendalam)',
            'smart_local' => '⚡ Smart AI Studio Lokal (Instan Tanpa Kuota)',
        ];
    }

    public function create()
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $berita = null;
        $kategoris = KategoriBerita::orderBy('nama_kategori', 'asc')->get();
        $daftarModelAi = self::daftarModelAi();

        return view('berita.form', compact('berita', 'kategoris', 'daftarModelAi'));
    }

    public function edit($id)
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $berita = Berita::findOrFail($id);
        $kategoris = KategoriBerita::orderBy('nama_kategori', 'asc')->get();
        $daftarModelAi = self::daftarModelAi();

        return view('berita.form', compact('berita', 'kategoris', 'daftarModelAi'));
    }

    public function store(Request $request)
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $request->validate([
            'judul' => ['required', 'string', 'max:220'],
            'kategori' => ['required', 'string', 'max:80'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'kutipan' => ['nullable', 'string', 'max:400'],
            'isi_konten' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:255'],
            'penulis' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:Tayang,Draft'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $rawSlug = $request->filled('slug') ? $request->slug : $request->judul;
        $baseSlug = Str::slug($rawSlug) ?: 'berita-'.time();
        $slug = $baseSlug;
        $counter = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('berita', 'public');
        }

        $isFeatured = $request->has('is_featured');
        if ($isFeatured) {
            Berita::where('is_featured', true)->update(['is_featured' => false]);
        }

        $ringkasanOtomatis = $request->ringkasan
            ? strip_tags(trim($request->ringkasan))
            : Str::limit(strip_tags($request->isi_konten), 160);

        Berita::create([
            'judul' => strip_tags(trim($request->judul)),
            'slug' => $slug,
            'kategori' => strip_tags(trim($request->kategori)),
            'ringkasan' => $ringkasanOtomatis,
            'kutipan' => strip_tags(trim((string) $request->kutipan)) ?: null,
            'isi_konten' => $request->isi_konten,
            'gambar' => $gambarPath,
            'tags' => strip_tags(trim((string) $request->tags)) ?: 'MIN 1 Ciamis, Pendidikan',
            'penulis' => strip_tags(trim($request->penulis ?: (auth()->user()->name ?? 'Tim Humas MIN 1 Ciamis'))),
            'user_id' => auth()->id(),
            'status' => $request->status,
            'is_featured' => $isFeatured,
            'views' => 1,
        ]);

        HomeController::bersihkanCache();

        return redirect()->route('berita.index')->with('success', 'Artikel berita berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        Berita::pastikanTabelSiap();

        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => ['required', 'string', 'max:220'],
            'kategori' => ['required', 'string', 'max:80'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'kutipan' => ['nullable', 'string', 'max:400'],
            'isi_konten' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:255'],
            'penulis' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:Tayang,Draft'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $isFeatured = $request->has('is_featured');
        if ($isFeatured) {
            Berita::where('id', '!=', $berita->id)->update(['is_featured' => false]);
        }

        $slugBaru = $berita->slug;
        if ($request->filled('slug') && Str::slug($request->slug) !== $berita->slug) {
            $baseSlug = Str::slug($request->slug);
            $slugBaru = $baseSlug;
            $counter = 1;
            while (Berita::where('slug', $slugBaru)->where('id', '!=', $berita->id)->exists()) {
                $slugBaru = $baseSlug.'-'.$counter++;
            }
        }

        $data = [
            'judul' => strip_tags(trim($request->judul)),
            'slug' => $slugBaru,
            'kategori' => strip_tags(trim($request->kategori)),
            'ringkasan' => $request->ringkasan ? strip_tags(trim($request->ringkasan)) : Str::limit(strip_tags($request->isi_konten), 160),
            'kutipan' => strip_tags(trim((string) $request->kutipan)) ?: null,
            'isi_konten' => $request->isi_konten,
            'tags' => strip_tags(trim((string) $request->tags)),
            'penulis' => strip_tags(trim($request->penulis ?: $berita->penulis)),
            'status' => $request->status,
            'is_featured' => $isFeatured,
        ];

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && ! str_starts_with($berita->gambar, 'frontend/') && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($data);

        HomeController::bersihkanCache();

        return redirect()->route('berita.index')->with('success', 'Perubahan artikel "'.$berita->judul.'" berhasil disimpan!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar && ! str_starts_with($berita->gambar, 'frontend/') && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        HomeController::bersihkanCache();

        return redirect()->back()->with('success', 'Artikel berita berhasil dihapus!');
    }

    public function storeKategori(Request $request)
    {
        KategoriBerita::pastikanTabelSiap();

        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:80', 'unique:kategori_beritas,nama_kategori'],
            'warna_badge' => ['nullable', 'string', 'max:20'],
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada!',
        ]);

        $nama = strip_tags(trim($request->nama_kategori));
        $slug = Str::slug($request->filled('slug') ? $request->slug : $nama) ?: 'kategori-'.time();

        KategoriBerita::create([
            'nama_kategori' => $nama,
            'slug' => $slug,
            'warna_badge' => $request->warna_badge ?: 'bg-1',
        ]);

        return redirect()->back()->with('success', 'Kategori "'.$nama.'" (slug: '.$slug.') berhasil ditambahkan!');
    }

    public function updateKategori(Request $request, $id)
    {
        KategoriBerita::pastikanTabelSiap();

        $kat = KategoriBerita::findOrFail($id);
        $namaLama = $kat->nama_kategori;

        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:80', Rule::unique('kategori_beritas', 'nama_kategori')->ignore($kat->id)],
            'warna_badge' => ['nullable', 'string', 'max:20'],
        ]);

        $namaBaru = strip_tags(trim($request->nama_kategori));
        $slugBaru = Str::slug($request->filled('slug') ? $request->slug : $namaBaru) ?: $kat->slug;

        $kat->update([
            'nama_kategori' => $namaBaru,
            'slug' => $slugBaru,
            'warna_badge' => $request->warna_badge ?: 'bg-1',
        ]);

        if ($namaLama !== $namaBaru) {
            Berita::where('kategori', $namaLama)->update(['kategori' => $namaBaru]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui menjadi "'.$namaBaru.'"!');
    }

    public function destroyKategori($id)
    {
        $kat = KategoriBerita::findOrFail($id);
        $kat->delete();

        return redirect()->back()->with('success', 'Kategori "'.$kat->nama_kategori.'" berhasil dihapus!');
    }

    /**
     * GENERATE ARTIKEL DENGAN GEMINI AI (DENGAN RESPONSE SCHEMA & ANTI TIMEOUT PHP)
     */
    public function generateAi(Request $request)
    {
        set_time_limit(120);

        $request->validate([
            'topik' => ['required', 'string', 'min:5', 'max:600'],
            'kategori' => ['nullable', 'string'],
            'nada' => ['nullable', 'string'],
            'engine' => ['nullable', 'string'],
            'api_key' => ['nullable', 'string'],
        ]);

        $topik = trim($request->topik);
        $kategori = $request->kategori ?: 'Kegiatan Madrasah';
        $nada = $request->nada ?: 'Formal, Inspiratif, dan Edukatif';
        $engine = $request->engine ?: 'gemini-2.5-flash';

        // 1. Jika user memilih Smart AI Studio Lokal
        if ($engine === 'smart_local') {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal MIN 1 Ciamis',
                'data' => $this->susunBeritaSmartLokal($topik, $kategori),
            ]);
        }

        // 2. Ambil API Key dari input form atau config (standar Laravel)
        $apiKey = trim((string) $request->api_key);
        if (empty($apiKey)) {
            $apiKey = trim((string) config('services.gemini.key', ''));
        }

        if (empty($apiKey)) {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal (API Key Belum Diisi)',
                'data' => $this->susunBeritaSmartLokal($topik, $kategori),
            ]);
        }

        // 3. Prioritas 1: Gunakan Laravel AI SDK Agent (BeritaWriterAgent)
        try {
            $agent = new BeritaWriterAgent;
            $agentPrompt = "Topik / Poin Kegiatan: {$topik}\nKategori: {$kategori}\nGaya Bahasa: {$nada}";
            $aiOutput = $agent->prompt($agentPrompt);

            if (! empty($aiOutput['judul']) && ! empty($aiOutput['isi_konten'])) {
                return response()->json([
                    'status' => 'success',
                    'source' => 'Laravel AI SDK (BeritaWriterAgent)',
                    'data' => [
                        'judul' => $aiOutput['judul'],
                        'slug' => Str::slug($aiOutput['judul']),
                        'ringkasan' => $aiOutput['ringkasan'] ?? '',
                        'kutipan' => $aiOutput['kutipan'] ?? '',
                        'tags' => $aiOutput['tags'] ?? "{$kategori}, MIN 1 Ciamis",
                        'isi_konten' => $aiOutput['isi_konten'],
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // Lanjut ke Direct HTTP API atau Smart Local jika SDK Agent mengalami kendala jaringan
        }

        // 3. Prioritas 1: Gunakan Laravel AI SDK Agent (BeritaWriterAgent)
        try {
            $agent = new BeritaWriterAgent;
            $agentPrompt = "Topik / Poin Kegiatan: {$topik}\nKategori: {$kategori}\nGaya Bahasa: {$nada}";
            $aiOutput = $agent->prompt($agentPrompt);

            if (! empty($aiOutput['judul']) && ! empty($aiOutput['isi_konten'])) {
                return response()->json([
                    'status' => 'success',
                    'source' => 'Laravel AI SDK (BeritaWriterAgent)',
                    'data' => [
                        'judul' => $aiOutput['judul'],
                        'slug' => Str::slug($aiOutput['judul']),
                        'ringkasan' => $aiOutput['ringkasan'] ?? '',
                        'kutipan' => $aiOutput['kutipan'] ?? '',
                        'tags' => $aiOutput['tags'] ?? "{$kategori}, MIN 1 Ciamis",
                        'isi_konten' => $aiOutput['isi_konten'],
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // Lanjut ke Direct HTTP API atau Smart Local jika SDK Agent mengalami kendala jaringan
        }

        $prompt = 'Kamu adalah Jurnalis & Humas Resmi Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). '
            ."Buatkan artikel berita website madrasah yang lengkap, menarik, dan profesional berdasarkan topik berikut:\n"
            ."Topik/Poin Kegiatan: {$topik}\n"
            ."Kategori: {$kategori}\n"
            ."Gaya Bahasa: {$nada}\n\n"
            ."Wajib kembalikan respons HANYA dalam format JSON murni dengan struktur key berikut:\n"
            ."{\n"
            .'  "judul": "Judul berita yang menarik dan profesional",'."\n"
            .'  "ringkasan": "Ringkasan berita 2 kalimat untuk preview kartu berita",'."\n"
            .'  "kutipan": "1 kalimat kutipan inspiratif terkait kegiatan ini",'."\n"
            .'  "tags": "3 sampai 4 kata kunci dipisahkan koma",'."\n"
            .'  "isi_konten": "Isi artikel lengkap minimal 3 paragraf dan daftar poin menggunakan tag HTML <h4>, <p>, <ul>, <li>, dan <strong>"'."\n"
            .'}';

        $baseUrl = config('services.gemini.endpoint', 'https://generativelanguage.googleapis.com/v1beta');

        // Urutan model yang dicoba: pilihan user → utama → fallback
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
                ])->connectTimeout(20)->timeout(50)->post($urlEndpoint, [
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
                    $parsed = $this->ekstrakJsonGemini($parts, $topik, $kategori);

                    if (! empty($parsed['judul']) && ! empty($parsed['isi_konten'])) {
                        $parsed['slug'] = Str::slug($parsed['judul']);

                        return response()->json([
                            'status' => 'success',
                            'source' => 'Google '.strtoupper($modelName),
                            'data' => $parsed,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Lanjut ke kandidat model Gemini berikutnya
            }
        }

        // Jika server Google sedang padat (503), langsung isikan draf berkualitas tanpa memunculkan popup error
        return response()->json([
            'status' => 'success',
            'source' => 'Smart AI Studio MIN 1 Ciamis (Auto-Fallback Server Padat)',
            'data' => $this->susunBeritaSmartLokal($topik, $kategori),
        ]);
    }

    /**
     * Ekstraktor JSON Tahan Banting (Mengabaikan bagian 'thought' dan membersihkan karakter kontrol)
     */
    private function ekstrakJsonGemini(array $parts, string $topik, string $kategori): array
    {
        $rawText = '';
        foreach ($parts as $part) {
            // Lewati bagian internal 'thought' jika model menyertakan proses berpikir
            if (! empty($part['thought'])) {
                continue;
            }
            $rawText .= (string) ($part['text'] ?? '');
        }

        $cleaned = trim(str_replace(['```json', '```'], '', $rawText));

        // Ambil blok di antara kurung kurawal pertama '{' dan terakhir '}'
        $firstBrace = strpos($cleaned, '{');
        $lastBrace = strrpos($cleaned, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $cleaned = substr($cleaned, $firstBrace, ($lastBrace - $firstBrace) + 1);
        }

        // Coba decode langsung
        $parsed = json_decode($cleaned, true);

        // Jika gagal karena ada karakter newline/tab mentah di dalam string HTML, bersihkan lalu decode ulang
        if (! is_array($parsed)) {
            $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $cleaned);
            $parsed = json_decode((string) $sanitized, true);
        }

        if (is_array($parsed) && ! empty($parsed['judul']) && ! empty($parsed['isi_konten'])) {
            return $parsed;
        }

        // Pengaman terakhir: jika Gemini membalas 200 OK dalam bentuk teks biasa, tetap tampilkan hasilnya!
        if (! empty(trim($rawText))) {
            $fallback = $this->susunBeritaSmartLokal($topik, $kategori);
            $fallback['isi_konten'] = nl2br(trim($rawText));

            return $fallback;
        }

        return [];
    }

    /**
     * Klasifikasi pesan error spesifik dari Google Gemini API
     */
    private function klasifikasiErrorGemini(int $status, string $rawMsg, string $modelName): array
    {
        $cleanMsg = Str::limit($rawMsg, 180);

        if ($status === 400 or $status === 401 or $status === 403 or str_contains(strtolower($rawMsg), 'api key not valid')) {
            return [
                'title' => 'API Key Tidak Valid ('.$status.')',
                'message' => 'Kunci API ditolak oleh Google. Detail: '.$cleanMsg,
            ];
        }

        if ($status === 404) {
            return [
                'title' => 'Model AI Tidak Tersedia (404)',
                'message' => "Model '{$modelName}' tidak ditemukan atau tidak didukung untuk akun ini. Detail: ".$cleanMsg,
            ];
        }

        if ($status === 429) {
            return [
                'title' => 'Kuota / Rate Limit Habis (429)',
                'message' => 'Batas pemanggilan API Gemini gratis per menit telah tercapai. Tunggu 1 menit atau gunakan Smart AI Lokal. Detail: '.$cleanMsg,
            ];
        }

        if ($status >= 500) {
            return [
                'title' => 'Gangguan Server Google ('.$status.')',
                'message' => 'Server Google Gemini sedang padat. Detail: '.$cleanMsg,
            ];
        }

        return [
            'title' => 'Permintaan Ditolak ('.$status.')',
            'message' => $cleanMsg,
        ];
    }

    /**
     * Mesin Penulis Berita Kontekstual (Smart AI Studio Lokal MIN 1 Ciamis)
     *
     * Mengenali 7 kategori topik madrasah dan menyusun draf artikel yang relevan.
     */
    private function susunBeritaSmartLokal(string $topik, string $kategori): array
    {
        $topikRapih = ucwords($topik);
        $judul = strlen($topikRapih) < 38
            ? $topikRapih.' di MIN 1 Ciamis Berlangsung Khidmat dan Penuh Semangat'
            : $topikRapih;

        $lower = strtolower($topik);

        // Kategori 1: Ibadah & Keagamaan
        if (str_contains($lower, 'shalat') || str_contains($lower, 'dhuha') || str_contains($lower, 'mengaji') || str_contains($lower, 'tahfidz') || str_contains($lower, 'qur') || str_contains($lower, 'isra') || str_contains($lower, 'maulid') || str_contains($lower, 'pesantren')) {
            $kutipan = 'Pembiasaan ibadah sejak dini adalah fondasi utama dalam membentuk generasi madrasah yang beriman, bertakwa, dan berakhlakul karimah.';
            $isi = '<h4>Pembiasaan Ibadah Pagi di Lingkungan Madrasah</h4>'
                ."<p><strong>CIAMIS</strong> — Suasana religius dan penuh kekhusyukan tampak menyelimuti halaman serta mushala Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis dalam pelaksanaan <strong>{$topik}</strong>. Kegiatan rutin keagamaan ini diikuti oleh seluruh peserta didik dengan didampingi langsung oleh Bapak dan Ibu dewan guru.</p>"
                .'<p>Sejak pagi hari, para siswa telah berwudhu dengan tertib dan menempati shaf masing-masing. Selain melaksanakan ibadah secara berjamaah, rangkaian kegiatan juga diisi dengan pembacaan doa bersama, dzikir pagi, serta tausiyah singkat mengenai adab sehari-hari.</p>'
                .'<h4>Menanamkan Karakter Islami Sejak Dini</h4>'
                .'<ul>'
                .'<li><strong>Kedisiplinan Waktu:</strong> Melatih siswa hadir tepat waktu dan mempersiapkan diri sebelum pembelajaran dimulai.</li>'
                .'<li><strong>Ketenangan Batin & Fokus Belajar:</strong> Mengawali pagi dengan ibadah membuat siswa lebih tenang dan siap menerima materi pelajaran di kelas.</li>'
                .'<li><strong>Pengamalan Nilai Ibadah:</strong> Membiasakan praktik shalat dan doa agar melekat dalam keseharian siswa baik di madrasah maupun di rumah.</li>'
                .'</ul>'
                .'<p>Kepala Madrasah beserta jajaran pendidik MIN 1 Ciamis berharap program pembiasaan ibadah ini terus istiqamah dijalankan demi mencetak lulusan yang cerdas secara intelektual sekaligus kokoh dalam spiritual.</p>';
        }
        // Kategori 2: Upacara & Hari Besar Nasional
        elseif (str_contains($lower, 'upacara') || str_contains($lower, 'kemerdekaan') || str_contains($lower, 'hari guru') || str_contains($lower, 'hari santri') || str_contains($lower, 'hari pendidikan') || str_contains($lower, 'bendera') || str_contains($lower, 'nasional')) {
            $kutipan = 'Melalui peringatan hari besar nasional, semangat cinta tanah air dan nilai kebangsaan tertanam kuat di hati setiap siswa MIN 1 Ciamis.';
            $isi = '<h4>Upacara & Peringatan Hari Besar di MIN 1 Ciamis</h4>'
                ."<p><strong>CIAMIS</strong> — Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis dengan penuh khidmat menyelenggarakan <strong>{$topik}</strong>. Seluruh warga madrasah — mulai dari Kepala Madrasah, dewan guru, tenaga kependidikan, hingga para siswa — hadir kompak mengenakan atribut lengkap sebagai bentuk penghormatan.</p>"
                .'<p>Pembina upacara menyampaikan amanat yang menekankan pentingnya meneladani semangat para pahlawan dan pendiri bangsa. Pesan ini disampaikan dengan bahasa yang membumi agar mudah dipahami oleh peserta didik tingkat dasar.</p>'
                .'<h4>Nilai-Nilai yang Ditanamkan</h4>'
                .'<ul>'
                .'<li><strong>Cinta Tanah Air:</strong> Menanamkan rasa kebangsaan dan patriotisme melalui lagu-lagu nasional dan amanat upacara.</li>'
                .'<li><strong>Kedisiplinan:</strong> Melatih siswa berbaris rapi, mendengarkan dengan tertib, dan menghormati simbol negara.</li>'
                .'<li><strong>Semangat Juang:</strong> Menginspirasi siswa untuk tekun belajar demi kemajuan agama, bangsa, dan negara.</li>'
                .'</ul>'
                .'<p>Kegiatan diakhiri dengan doa bersama dan sesi foto seluruh peserta upacara sebagai dokumentasi kegiatan madrasah.</p>';
        }
        // Kategori 3: Lomba, Prestasi & Kompetisi
        elseif (str_contains($lower, 'lomba') || str_contains($lower, 'juara') || str_contains($lower, 'prestasi') || str_contains($lower, 'kompetisi') || str_contains($lower, 'olimpiade') || str_contains($lower, 'ksm') || str_contains($lower, 'aksioma') || str_contains($lower, 'porseni')) {
            $kutipan = 'Prestasi gemilang siswa MIN 1 Ciamis membuktikan bahwa madrasah mampu bersaing di kancah lokal maupun nasional.';
            $isi = '<h4>Raihan Prestasi Membanggakan dari MIN 1 Ciamis</h4>'
                ."<p><strong>CIAMIS</strong> — Putra-putri terbaik Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis kembali mengukir prestasi membanggakan dalam ajang <strong>{$topik}</strong>. Keberhasilan ini merupakan buah dari pembinaan intensif yang dilakukan oleh para guru pembimbing dan semangat pantang menyerah dari para siswa.</p>"
                .'<p>Para juara tampak bangga menerima piala dan sertifikat penghargaan yang diserahkan langsung dalam upacara khusus di halaman madrasah. Kepala Madrasah memberikan apresiasi setinggi-tingginya atas pencapaian ini.</p>'
                .'<h4>Pembinaan Berkelanjutan</h4>'
                .'<ul>'
                .'<li><strong>Program Bimbingan Intensif:</strong> Siswa berprestasi mendapatkan sesi bimbingan tambahan dari guru mapel dan tutor eksternal.</li>'
                .'<li><strong>Dukungan Fasilitas:</strong> Madrasah menyediakan perpustakaan, laboratorium, dan akses belajar digital untuk menunjang persiapan lomba.</li>'
                .'<li><strong>Mental Juara:</strong> Pembekalan motivasi dan simulasi kompetisi rutin dilaksanakan untuk membentuk mental tangguh siswa.</li>'
                .'</ul>'
                .'<p>MIN 1 Ciamis bertekad terus mencetak generasi berprestasi yang mampu menjadi kebanggaan madrasah, orang tua, dan bangsa.</p>';
        }
        // Kategori 4: PPDB & Penerimaan Siswa Baru
        elseif (str_contains($lower, 'ppdb') || str_contains($lower, 'pendaftaran') || str_contains($lower, 'siswa baru') || str_contains($lower, 'penerimaan') || str_contains($lower, 'orientasi')) {
            $kutipan = 'Pendaftaran peserta didik baru dibuka sebagai langkah awal dalam mewujudkan generasi madrasah yang unggul dan berakhlak mulia.';
            $isi = '<h4>Informasi Penerimaan Peserta Didik Baru</h4>'
                ."<p><strong>CIAMIS</strong> — Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis resmi membuka kegiatan <strong>{$topik}</strong>. Proses penerimaan dilaksanakan secara transparan, adil, dan sesuai ketentuan Kementerian Agama Republik Indonesia.</p>"
                .'<p>Para calon wali murid dapat memperoleh informasi lengkap mengenai persyaratan, jadwal seleksi, dan ketentuan lainnya melalui website resmi madrasah atau datang langsung ke kantor tata usaha pada jam kerja.</p>'
                .'<h4>Keunggulan MIN 1 Ciamis</h4>'
                .'<ul>'
                .'<li><strong>Akreditasi Unggul:</strong> Kualitas pendidikan terjamin dengan capaian akreditasi terbaik dari BAN-S/M.</li>'
                .'<li><strong>Kurikulum Terpadu:</strong> Memadukan kurikulum nasional dengan muatan keagamaan dan karakter Islami.</li>'
                .'<li><strong>Lingkungan Kondusif:</strong> Fasilitas belajar modern, area hijau, dan suasana yang aman serta nyaman bagi anak.</li>'
                .'</ul>'
                .'<p>Ayo bergabung bersama keluarga besar MIN 1 Ciamis untuk masa depan putra-putri yang lebih cerah!</p>';
        }
        // Kategori 5: Olahraga & Senam
        elseif (str_contains($lower, 'senam') || str_contains($lower, 'olahraga') || str_contains($lower, 'jalan sehat') || str_contains($lower, 'renang') || str_contains($lower, 'futsal') || str_contains($lower, 'bola') || str_contains($lower, 'atletik')) {
            $kutipan = 'Tubuh yang sehat melahirkan jiwa yang kuat — semangat olahraga menjadi bagian penting kehidupan madrasah.';
            $isi = '<h4>Kegiatan Olahraga & Kebugaran di MIN 1 Ciamis</h4>'
                ."<p><strong>CIAMIS</strong> — Suasana ceria dan penuh semangat menghiasi halaman Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis dalam kegiatan <strong>{$topik}</strong>. Seluruh siswa berpartisipasi aktif dengan antusias tinggi.</p>"
                .'<p>Kegiatan ini bertujuan menumbuhkan budaya hidup sehat dan aktif di kalangan warga madrasah, sekaligus menjadi sarana mempererat kebersamaan antar-siswa dari berbagai kelas.</p>'
                .'<h4>Manfaat bagi Siswa</h4>'
                .'<ul>'
                .'<li><strong>Kesehatan Jasmani:</strong> Menjaga kebugaran tubuh agar siswa tetap prima dan bersemangat dalam belajar.</li>'
                .'<li><strong>Sportivitas:</strong> Menanamkan jiwa sportif, kerja sama tim, dan sikap pantang menyerah.</li>'
                .'<li><strong>Kebersamaan:</strong> Mempererat silaturahmi dan kekompakan antar-siswa lintas kelas.</li>'
                .'</ul>'
                .'<p>MIN 1 Ciamis berkomitmen menjadikan olahraga sebagai bagian rutin pendidikan holistik bagi seluruh peserta didik.</p>';
        }
        // Kategori 6: Kunjungan, Study Tour & Kolaborasi
        elseif (str_contains($lower, 'kunjungan') || str_contains($lower, 'study tour') || str_contains($lower, 'wisata') || str_contains($lower, 'museum') || str_contains($lower, 'kolaborasi') || str_contains($lower, 'kerja sama') || str_contains($lower, 'mou')) {
            $kutipan = 'Belajar tidak hanya di dalam kelas — pengalaman di luar madrasah memperluas cakrawala pengetahuan siswa.';
            $isi = '<h4>Kegiatan Kunjungan Edukatif MIN 1 Ciamis</h4>'
                ."<p><strong>CIAMIS</strong> — Dalam rangka memperkaya pengalaman belajar siswa, Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis menyelenggarakan <strong>{$topik}</strong>. Kegiatan ini diikuti dengan penuh antusiasme oleh para peserta didik didampingi oleh guru-guru pendamping.</p>"
                .'<p>Melalui kegiatan ini, siswa mendapatkan kesempatan belajar langsung dari sumber dan lingkungan nyata yang tidak ditemukan di ruang kelas biasa.</p>'
                .'<h4>Dampak Positif</h4>'
                .'<ul>'
                .'<li><strong>Wawasan Luas:</strong> Siswa mengenal secara langsung objek pembelajaran yang sebelumnya hanya dibaca di buku teks.</li>'
                .'<li><strong>Kolaborasi:</strong> Terbukanya peluang kerja sama antara madrasah dengan lembaga dan institusi lain.</li>'
                .'<li><strong>Motivasi Belajar:</strong> Pengalaman baru memicu semangat dan rasa ingin tahu yang lebih besar pada siswa.</li>'
                .'</ul>'
                .'<p>Kegiatan ini menjadi bukti komitmen MIN 1 Ciamis dalam menerapkan pembelajaran kontekstual dan bermakna.</p>';
        }
        // Kategori 7: Default / Umum
        else {
            $kutipan = "Melalui kegiatan {$topik}, kita wujudkan generasi MIN 1 Ciamis yang berakhlak mulia, cerdas, dan siap berprestasi.";
            $isi = '<h4>Pelaksanaan Kegiatan di MIN 1 Ciamis</h4>'
                ."<p><strong>CIAMIS</strong> — Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis kembali menyelenggarakan agenda positif bertajuk <strong>{$topik}</strong>. Kegiatan yang berlangsung dengan penuh antusiasme ini diikuti oleh jajaran pendidik, tenaga kependidikan, serta para siswa sebagai wujud nyata komitmen madrasah dalam menghadirkan layanan pendidikan yang unggul.</p>"
                .'<p>Dalam pelaksanaannya, seluruh rangkaian kegiatan berjalan tertib, interaktif, dan kondusif. Para peserta tampak bersemangat mengikuti setiap sesi yang dirancang untuk menumbuhkan kreativitas, kemandirian, serta kebersamaan.</p>'
                .'<h4>Tujuan dan Dampak Positif</h4>'
                .'<ul>'
                .'<li><strong>Penguatan Karakter & Akhlak:</strong> Menanamkan nilai kedisiplinan, tanggung jawab, serta kerja sama sejak usia dini.</li>'
                .'<li><strong>Pengembangan Potensi:</strong> Memberikan ruang aktualisasi bagi warga madrasah untuk terus berkarya dan berinovasi.</li>'
                .'<li><strong>Sinergi Madrasah:</strong> Mempererat kolaborasi positif antara guru, siswa, dan lingkungan pendidikan.</li>'
                .'</ul>'
                .'<p>Melalui terselenggaranya kegiatan ini, keluarga besar MIN 1 Ciamis optimis semangat belajar dan berprestasi akan terus tumbuh secara berkelanjutan.</p>';
        }

        return [
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'ringkasan' => "MIN 1 Ciamis menggelar kegiatan {$topik} sebagai bagian dari penguatan karakter Islami, mutu akademik, dan pengembangan potensi siswa.",
            'kutipan' => $kutipan,
            'tags' => "{$kategori}, MIN 1 Ciamis, Madrasah Hebat, Kegiatan Siswa",
            'isi_konten' => $isi,
        ];
    }

    /**
     * Halaman Publik: Direktori Kabar Madrasah (Warta, Berita & Artikel)
     */
    public function publik(Request $request): View
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $query = Berita::where('status', 'Tayang');

        // Filter Kategori
        $kategoriAktif = $request->query('kategori', 'semua');
        if ($kategoriAktif && $kategoriAktif !== 'semua') {
            $query->where('kategori', $kategoriAktif);
        }

        // Pencarian Kata Kunci
        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('ringkasan', 'like', "%{$q}%")
                    ->orWhere('isi_konten', 'like', "%{$q}%")
                    ->orWhere('tags', 'like', "%{$q}%")
                    ->orWhere('penulis', 'like', "%{$q}%");
            });
        }

        // Sorting (Terbaru, Populer, Terlama)
        $sort = $request->query('sort', 'terbaru');
        if ($sort === 'populer') {
            $query->orderBy('views', 'desc')->orderBy('id', 'desc');
        } elseif ($sort === 'terlama') {
            $query->orderBy('id', 'asc');
        } else {
            $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc');
        }

        $beritas = $query->paginate(9)->withQueryString();

        // Berita Sorotan / Utama (khusus ditampilkan menonjol di halaman 1 saat tidak sedang mencari)
        $beritaUtama = null;
        $page = (int) $request->query('page', 1);
        if ($page <= 1 && empty($q) && $kategoriAktif === 'semua') {
            $beritaUtama = Berita::where('status', 'Tayang')
                ->where('is_featured', true)
                ->latest('id')
                ->first()
                ?? Berita::where('status', 'Tayang')->latest('id')->first();
        }

        // 4 Berita Terpopuler untuk Sidebar / Sorotan
        $beritaPopuler = Berita::where('status', 'Tayang')
            ->orderBy('views', 'desc')
            ->take(4)
            ->get();

        $kategoris = KategoriBerita::withCount(['beritas' => function ($q) {
            $q->where('status', 'Tayang');
        }])->orderBy('nama_kategori', 'asc')->get();

        $totalBerita = Berita::where('status', 'Tayang')->count();
        $totalPembaca = Berita::where('status', 'Tayang')->sum('views');
        $warnaKategori = KategoriBerita::pluck('warna_badge', 'nama_kategori')->toArray();

        return view('frontend.berita', compact(
            'beritas',
            'beritaUtama',
            'beritaPopuler',
            'kategoris',
            'totalBerita',
            'totalPembaca',
            'kategoriAktif',
            'q',
            'sort',
            'warnaKategori'
        ));
    }

    public function show($slug)
    {
        Berita::pastikanTabelSiap();

        $berita = Berita::where('slug', $slug)->firstOrFail();
        $berita->increment('views');

        $beritaTerkait = Berita::where('id', '!=', $berita->id)
            ->where('status', 'Tayang')
            ->latest('id')
            ->take(4)
            ->get();

        $statMadrasah = [
            'Siswa Aktif' => Siswa::where('status', 'Aktif')->count(),
            'Guru & Tendik' => Gtk::count(),
            'Rombel Kelas' => Kelas::count(),
            'Ekstrakurikuler' => Ekskul::where('status', 'Aktif')->count(),
        ];

        $kategoriPopuler = KategoriBerita::withCount(['beritas' => function ($q) {
            $q->where('status', 'Tayang');
        }])->orderByDesc('beritas_count')->take(10)->get();

        return view('frontend.berita_detail', compact('berita', 'beritaTerkait', 'statMadrasah', 'kategoriPopuler'));
    }
}
