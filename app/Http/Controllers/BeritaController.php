<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\Kelas;
use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        $beritas   = $query->orderBy('id', 'desc')->get();
        $kategoris = KategoriBerita::withCount('beritas')->orderBy('nama_kategori', 'asc')->get();

        $totalBerita  = Berita::count();
        $totalTayang  = Berita::where('status', 'Tayang')->count();
        $totalDraft   = Berita::where('status', 'Draft')->count();
        $totalPembaca = Berita::sum('views');

        return view('berita.index', compact(
            'beritas', 'kategoris', 'totalBerita', 'totalTayang', 'totalDraft', 'totalPembaca'
        ));
    }

    public function create()
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $berita    = null;
        $kategoris = KategoriBerita::orderBy('nama_kategori', 'asc')->get();

        return view('berita.form', compact('berita', 'kategoris'));
    }

    public function edit($id)
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $berita    = Berita::findOrFail($id);
        $kategoris = KategoriBerita::orderBy('nama_kategori', 'asc')->get();

        return view('berita.form', compact('berita', 'kategoris'));
    }

    public function store(Request $request)
    {
        Berita::pastikanTabelSiap();
        KategoriBerita::pastikanTabelSiap();

        $request->validate([
            'judul'      => ['required', 'string', 'max:220'],
            'kategori'   => ['required', 'string', 'max:80'],
            'ringkasan'  => ['nullable', 'string', 'max:500'],
            'kutipan'    => ['nullable', 'string', 'max:400'],
            'isi_konten' => ['required', 'string'],
            'tags'       => ['nullable', 'string', 'max:255'],
            'penulis'    => ['nullable', 'string', 'max:120'],
            'status'     => ['required', 'in:Tayang,Draft'],
            'gambar'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        // Gunakan slug kustom jika diisi, atau buat otomatis dari judul
        $rawSlug  = $request->filled('slug') ? $request->slug : $request->judul;
        $baseSlug = Str::slug($rawSlug) ?: 'berita-' . time();
        $slug     = $baseSlug;
        $counter  = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
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
            'judul'       => strip_tags(trim($request->judul)),
            'slug'        => $slug,
            'kategori'    => strip_tags(trim($request->kategori)),
            'ringkasan'   => $ringkasanOtomatis,
            'kutipan'     => strip_tags(trim((string) $request->kutipan)) ?: null,
            'isi_konten'  => $request->isi_konten,
            'gambar'      => $gambarPath,
            'tags'        => strip_tags(trim((string) $request->tags)) ?: 'MIN 1 Ciamis, Pendidikan',
            'penulis'     => strip_tags(trim($request->penulis ?: (auth()->user()->name ?? 'Tim Humas MIN 1 Ciamis'))),
            'user_id'     => auth()->id(),
            'status'      => $request->status,
            'is_featured' => $isFeatured,
            'views'       => 1,
        ]);

        return redirect()->route('berita.index')->with('success', 'Artikel berita berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        Berita::pastikanTabelSiap();

        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul'      => ['required', 'string', 'max:220'],
            'kategori'   => ['required', 'string', 'max:80'],
            'ringkasan'  => ['nullable', 'string', 'max:500'],
            'kutipan'    => ['nullable', 'string', 'max:400'],
            'isi_konten' => ['required', 'string'],
            'tags'       => ['nullable', 'string', 'max:255'],
            'penulis'    => ['nullable', 'string', 'max:120'],
            'status'     => ['required', 'in:Tayang,Draft'],
            'gambar'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $isFeatured = $request->has('is_featured');
        if ($isFeatured) {
            Berita::where('id', '!=', $berita->id)->update(['is_featured' => false]);
        }

        // Update slug jika diubah oleh admin
        $slugBaru = $berita->slug;
        if ($request->filled('slug') && Str::slug($request->slug) !== $berita->slug) {
            $baseSlug = Str::slug($request->slug);
            $slugBaru = $baseSlug;
            $counter  = 1;
            while (Berita::where('slug', $slugBaru)->where('id', '!=', $berita->id)->exists()) {
                $slugBaru = $baseSlug . '-' . $counter++;
            }
        }

        $data = [
            'judul'       => strip_tags(trim($request->judul)),
            'slug'        => $slugBaru,
            'kategori'    => strip_tags(trim($request->kategori)),
            'ringkasan'   => $request->ringkasan ? strip_tags(trim($request->ringkasan)) : Str::limit(strip_tags($request->isi_konten), 160),
            'kutipan'     => strip_tags(trim((string) $request->kutipan)) ?: null,
            'isi_konten'  => $request->isi_konten,
            'tags'        => strip_tags(trim((string) $request->tags)),
            'penulis'     => strip_tags(trim($request->penulis ?: $berita->penulis)),
            'status'      => $request->status,
            'is_featured' => $isFeatured,
        ];

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && !str_starts_with($berita->gambar, 'frontend/') && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($data);

        return redirect()->route('berita.index')->with('success', 'Perubahan artikel "' . $berita->judul . '" berhasil disimpan!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar && !str_starts_with($berita->gambar, 'frontend/') && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->back()->with('success', 'Artikel berita berhasil dihapus!');
    }

    /**
     * =========================================================================
     * CRUD KATEGORI BERITA (BESERTA SLUG OTOMATIS)
     * =========================================================================
     */
    public function storeKategori(Request $request)
    {
        KategoriBerita::pastikanTabelSiap();

        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:80', 'unique:kategori_beritas,nama_kategori'],
            'warna_badge'   => ['nullable', 'string', 'max:20'],
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada!',
        ]);

        $nama = strip_tags(trim($request->nama_kategori));
        $slug = Str::slug($request->filled('slug') ? $request->slug : $nama) ?: 'kategori-' . time();

        KategoriBerita::create([
            'nama_kategori' => $nama,
            'slug'          => $slug,
            'warna_badge'   => $request->warna_badge ?: 'bg-1',
        ]);

        return redirect()->back()->with('success', 'Kategori "' . $nama . '" (slug: ' . $slug . ') berhasil ditambahkan!');
    }

    public function updateKategori(Request $request, $id)
    {
        KategoriBerita::pastikanTabelSiap();

        $kat = KategoriBerita::findOrFail($id);
        $namaLama = $kat->nama_kategori;

        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:80', Rule::unique('kategori_beritas', 'nama_kategori')->ignore($kat->id)],
            'warna_badge'   => ['nullable', 'string', 'max:20'],
        ]);

        $namaBaru = strip_tags(trim($request->nama_kategori));
        $slugBaru = Str::slug($request->filled('slug') ? $request->slug : $namaBaru) ?: $kat->slug;

        $kat->update([
            'nama_kategori' => $namaBaru,
            'slug'          => $slugBaru,
            'warna_badge'   => $request->warna_badge ?: 'bg-1',
        ]);

        // Sinkronkan nama kategori pada berita yang sudah menggunakan kategori ini
        if ($namaLama !== $namaBaru) {
            Berita::where('kategori', $namaLama)->update(['kategori' => $namaBaru]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui menjadi "' . $namaBaru . '"!');
    }

    public function destroyKategori($id)
    {
        $kat = KategoriBerita::findOrFail($id);
        $kat->delete();

        return redirect()->back()->with('success', 'Kategori "' . $kat->nama_kategori . '" berhasil dihapus!');
    }

    /**
     * GENERATE ARTIKEL DENGAN GEMINI AI
     */
    public function generateAi(Request $request)
    {
        $request->validate([
            'topik'    => ['required', 'string', 'min:5', 'max:600'],
            'kategori' => ['nullable', 'string'],
            'nada'     => ['nullable', 'string'],
            'engine'   => ['nullable', 'string'],
            'api_key'  => ['nullable', 'string'],
        ]);

        $topik    = trim($request->topik);
        $kategori = $request->kategori ?: 'Kegiatan Madrasah';
        $nada     = $request->nada ?: 'Formal, Inspiratif, dan Edukatif';
        $engine   = $request->engine ?: 'gemini-2.5-flash';

        // 1. Jika user sengaja memilih Smart AI Studio Lokal di dropdown
        if ($engine === 'smart_local') {
            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal MIN 1 Ciamis',
                'data'   => $this->susunBeritaSmartLokal($topik, $kategori),
            ]);
        }

        // 2. Ambil API Key dari input form, env(), atau baca langsung dari file .env (Anti Masalah Cache)
        $apiKey = trim((string) $request->api_key);
        if (empty($apiKey)) {
            $apiKey = trim((string) env('GEMINI_API_KEY', ''));
        }
        if (empty($apiKey) && file_exists(base_path('.env'))) {
            $envContent = file_get_contents(base_path('.env'));
            if (preg_match('/^GEMINI_API_KEY\s*=\s*([^\r\n#]+)/m', $envContent, $matches)) {
                $apiKey = trim($matches[1], " \t\n\r\0\x0B\"'");
            }
        }

        if (empty($apiKey)) {
            return response()->json([
                'status'  => 'error_gemini',
                'message' => 'GEMINI_API_KEY belum ditemukan di file .env! Silakan tempelkan API Key Anda pada kotak Pengaturan API Key atau pilih mode Smart AI Lokal.',
                'backup'  => $this->susunBeritaSmartLokal($topik, $kategori),
            ]);
        }

        $modelName = in_array($engine, ['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash'])
            ? $engine
            : 'gemini-2.5-flash';

        $prompt = "Kamu adalah Jurnalis & Humas Resmi Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). "
            . "Buatkan artikel berita website madrasah yang lengkap, menarik, dan profesional berdasarkan topik berikut:\n"
            . "Topik/Poin Kegiatan: {$topik}\n"
            . "Kategori: {$kategori}\n"
            . "Gaya Bahasa: {$nada}\n\n"
            . "Wajib kembalikan respons HANYA dalam format JSON murni dengan struktur key berikut:\n"
            . "{\n"
            . '  "judul": "Judul berita yang menarik dan profesional",' . "\n"
            . '  "ringkasan": "Ringkasan berita 2 kalimat untuk preview kartu berita",' . "\n"
            . '  "kutipan": "1 kalimat kutipan inspiratif terkait kegiatan ini",' . "\n"
            . '  "tags": "3 sampai 4 kata kunci dipisahkan koma",' . "\n"
            . '  "isi_konten": "Isi artikel lengkap minimal 3 paragraf dan daftar poin menggunakan tag HTML <h4>, <p>, <ul>, <li>, dan <strong>"' . "\n"
            . "}";

        // Daftar endpoint untuk kunci Vertex AI (AQ.) maupun Google AI Studio (AIzaSy)
        $endpoints = str_starts_with($apiKey, 'AQ.')
            ? [
                "https://aiplatform.googleapis.com/v1beta1/publishers/google/models/{$modelName}:generateContent?key={$apiKey}",
                "https://aiplatform.googleapis.com/v1/publishers/google/models/{$modelName}:generateContent?key={$apiKey}",
                "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}",
            ]
            : [
                "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}",
            ];

        $pesanErrorTerakhir = 'Koneksi ke server Google Gemini gagal.';

        foreach ($endpoints as $urlEndpoint) {
            try {
                $response = Http::withoutVerifying()->timeout(30)->post($urlEndpoint, [
                    'contents' => [
                        [
                            'role'  => 'user',
                            'parts' => [['text' => $prompt]],
                        ]
                    ],
                    'generationConfig' => [
                        'temperature'      => 0.7,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

                if ($response->successful()) {
                    $rawText = (string) $response->json('candidates.0.content.parts.0.text');
                    $cleaned = trim(str_replace(['```json', '```'], '', $rawText));
                    $parsed  = json_decode($cleaned, true);

                    if (is_array($parsed) && !empty($parsed['judul']) && !empty($parsed['isi_konten'])) {
                        $parsed['slug'] = Str::slug($parsed['judul']);
                        return response()->json([
                            'status' => 'success',
                            'source' => 'Google ' . strtoupper($modelName),
                            'data'   => $parsed,
                        ]);
                    }
                } else {
                    $errGoogle = $response->json('error.message') ?? $response->body();
                    $pesanErrorTerakhir = 'Google API (' . $response->status() . '): ' . Str::limit((string) $errGoogle, 180);
                }
            } catch (\Exception $e) {
                $pesanErrorTerakhir = $e->getMessage();
            }
        }

        // Jika Gemini gagal, kirim pesan diagnostik asli beserta draf cadangan agar user tahu penyebabnya
        return response()->json([
            'status'  => 'error_gemini',
            'message' => $pesanErrorTerakhir,
            'backup'  => $this->susunBeritaSmartLokal($topik, $kategori),
        ]);
    }

    /**
     * Mesin Penulis Berita Kontekstual (Smart AI Studio Lokal MIN 1 Ciamis)
     */
    private function susunBeritaSmartLokal(string $topik, string $kategori): array
    {
        $topikRapih = ucwords($topik);
        $judul      = strlen($topikRapih) < 38
            ? $topikRapih . ' di MIN 1 Ciamis Berlangsung Khidmat dan Penuh Semangat'
            : $topikRapih;

        $lower = strtolower($topik);

        if (str_contains($lower, 'shalat') or str_contains($lower, 'dhuha') or str_contains($lower, 'mengaji') or str_contains($lower, 'tahfidz')) {
            $kutipan = "Pembiasaan ibadah sejak dini adalah fondasi utama dalam membentuk generasi madrasah yang beriman, bertakwa, dan berakhlakul karimah.";
            $isi     = "<h4>Pembiasaan Ibadah Pagi di Lingkungan Madrasah</h4>"
                . "<p><strong>CIAMIS</strong> — Suasana religius dan penuh kekhusyukan tampak menyelimuti halaman serta mushala Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis dalam pelaksanaan <strong>{$topik}</strong>. Kegiatan rutin keagamaan ini diikuti oleh seluruh peserta didik dengan didampingi langsung oleh Bapak dan Ibu dewan guru.</p>"
                . "<p>Sejak pagi hari, para siswa telah berwudhu dengan tertib dan menempati shaf masing-masing. Selain melaksanakan ibadah secara berjamaah, rangkaian kegiatan juga diisi dengan pembacaan doa bersama, dzikir pagi, serta tausiyah singkat mengenai adab sehari-hari.</p>"
                . "<h4>Menanamkan Karakter Islami Sejak Dini</h4>"
                . "<ul>"
                . "<li><strong>Kedisiplinan Waktu:</strong> Melatih siswa hadir tepat waktu dan mempersiapkan diri sebelum pembelajaran dimulai.</li>"
                . "<li><strong>Ketenangan Batin & Fokus Belajar:</strong> Mengawali pagi dengan ibadah membuat siswa lebih tenang dan siap menerima materi pelajaran di kelas.</li>"
                . "<li><strong>Pengamalan Nilai Ibadah:</strong> Membiasakan praktik shalat dan doa agar melekat dalam keseharian siswa baik di madrasah maupun di rumah.</li>"
                . "</ul>"
                . "<p>Kepala Madrasah beserta jajaran pendidik MIN 1 Ciamis berharap program pembiasaan ibadah ini terus istiqamah dijalankan demi mencetak lulusan yang cerdas secara intelektual sekaligus kokoh dalam spiritual.</p>";
        } else {
            $kutipan = "Melalui kegiatan {$topik}, kita wujudkan generasi MIN 1 Ciamis yang berakhlak mulia, cerdas, dan siap berprestasi.";
            $isi     = "<h4>Pelaksanaan Kegiatan di MIN 1 Ciamis</h4>"
                . "<p><strong>CIAMIS</strong> — Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis kembali menyelenggarakan agenda positif bertajuk <strong>{$topik}</strong>. Kegiatan yang berlangsung dengan penuh antusiasme ini diikuti oleh jajaran pendidik, tenaga kependidikan, serta para siswa sebagai wujud nyata komitmen madrasah dalam menghadirkan layanan pendidikan yang unggul.</p>"
                . "<p>Dalam pelaksanaannya, seluruh rangkaian kegiatan berjalan tertib, interaktif, dan kondusif. Para peserta tampak bersemangat mengikuti setiap sesi yang dirancang untuk menumbuhkan kreativitas, kemandirian, serta kebersamaan.</p>"
                . "<h4>Tujuan dan Dampak Positif</h4>"
                . "<ul>"
                . "<li><strong>Penguatan Karakter & Akhlak:</strong> Menanamkan nilai kedisiplinan, tanggung jawab, serta kerja sama sejak usia dini.</li>"
                . "<li><strong>Pengembangan Potensi:</strong> Memberikan ruang aktualisasi bagi warga madrasah untuk terus berkarya dan berinovasi.</li>"
                . "<li><strong>Sinergi Madrasah:</strong> Mempererat kolaborasi positif antara guru, siswa, dan lingkungan pendidikan.</li>"
                . "</ul>"
                . "<p>Melalui terselenggaranya kegiatan ini, keluarga besar MIN 1 Ciamis optimis semangat belajar dan berprestasi akan terus tumbuh secara berkelanjutan.</p>";
        }

        return [
            'judul'      => $judul,
            'slug'       => Str::slug($judul),
            'ringkasan'  => "MIN 1 Ciamis menggelar kegiatan {$topik} sebagai bagian dari penguatan karakter Islami, mutu akademik, dan pengembangan potensi siswa.",
            'kutipan'    => $kutipan,
            'tags'       => "{$kategori}, MIN 1 Ciamis, Madrasah Hebat, Kegiatan Siswa",
            'isi_konten' => $isi,
        ];
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
            'Siswa Aktif'     => Siswa::where('status', 'Aktif')->count(),
            'Guru & Tendik'   => Gtk::count(),
            'Rombel Kelas'    => Kelas::count(),
            'Ekstrakurikuler' => Ekskul::where('status', 'Aktif')->count(),
        ];

        return view('frontend.berita_detail', compact('berita', 'beritaTerkait', 'statMadrasah'));
    }
}