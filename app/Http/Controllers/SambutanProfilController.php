<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SambutanKepsekAgent;
use App\Models\SambutanProfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SambutanProfilController extends Controller
{
    public function index()
    {
        $data = SambutanProfil::ambilData();
        $daftarIkon = SambutanProfil::daftarIkon();

        return view('sambutan_profil.index', compact('data', 'daftarIkon'));
    }

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
            'profil_kutipan' => ['required', 'string', 'max:350'],
            'profil_isi' => ['required', 'string'],
            'visi_kutipan' => ['required', 'string', 'max:500'],
            'visi_isi' => ['required', 'string'],
            'misi_kutipan' => ['required', 'string', 'max:350'],
            'misi_isi' => ['required', 'string'],
            'motto_kutipan' => ['required', 'string', 'max:350'],
            'motto_isi' => ['required', 'string'],
            'total_prestasi' => ['nullable', 'integer', 'min:0'],
        ]);

        // Susun 4 Poin Profil beserta Ikonnya
        $profilPoin = [];
        $pIcons = $request->input('profil_icon', []);
        $pTexts = $request->input('profil_text', []);
        for ($i = 0; $i < 4; $i++) {
            $profilPoin[] = [
                'icon' => $pIcons[$i] ?? 'fas fa-check-circle',
                'text' => strip_tags(trim($pTexts[$i] ?? 'Keunggulan Madrasah '.($i + 1))),
            ];
        }

        // Susun 4 Poin Misi beserta Ikonnya
        $misiPoin = [];
        $mIcons = $request->input('misi_icon', []);
        $mTexts = $request->input('misi_text', []);
        for ($i = 0; $i < 4; $i++) {
            $misiPoin[] = [
                'icon' => $mIcons[$i] ?? 'fas fa-star',
                'text' => strip_tags(trim($mTexts[$i] ?? 'Misi Utama '.($i + 1))),
            ];
        }

        $updateData = [
            'nama_kepsek' => strip_tags(trim($request->nama_kepsek)),
            'jabatan_kepsek' => strip_tags(trim($request->jabatan_kepsek)),
            'nip_kepsek' => strip_tags(trim((string) $request->nip_kepsek)),
            'sambutan_kutipan' => strip_tags(trim($request->sambutan_kutipan)),
            'sambutan_isi' => trim($request->sambutan_isi),
            'profil_kutipan' => strip_tags(trim($request->profil_kutipan)),
            'profil_isi' => trim($request->profil_isi),
            'profil_poin' => $profilPoin,
            'visi_kutipan' => strip_tags(trim($request->visi_kutipan)),
            'visi_isi' => trim($request->visi_isi),
            'misi_kutipan' => strip_tags(trim($request->misi_kutipan)),
            'misi_isi' => trim($request->misi_isi),
            'misi_poin' => $misiPoin,
            'motto_kutipan' => strip_tags(trim($request->motto_kutipan)),
            'motto_isi' => trim($request->motto_isi),
            'total_prestasi' => (int) ($request->total_prestasi ?? 237),
        ];

        if ($request->hasFile('foto_kepsek')) {
            if ($data->foto_kepsek && ! str_starts_with($data->foto_kepsek, 'frontend/') && Storage::disk('public')->exists($data->foto_kepsek)) {
                Storage::disk('public')->delete($data->foto_kepsek);
            }
            $updateData['foto_kepsek'] = $request->file('foto_kepsek')->store('kepsek', 'public');
        }

        $data->update($updateData);

        return redirect()->back()->with('success', 'Data Sambutan Kepala Madrasah, Profil, Visi, Misi & Motto berhasil diperbarui!');
    }

    /**
     * ⚡ SMART AI: EKSTRAK DESKRIPSI MENJADI 4 POIN + PILIHKAN IKON OTOMATIS
     *
     * Mendukung 2 mode:
     *   - Smart AI Lokal (default): regex + keyword matching, instan tanpa kuota.
     *   - Gemini API (opsional): jika field 'engine' berisi model Gemini dan API Key tersedia.
     */
    public function generateAi(Request $request)
    {
        $tipe = $request->input('tipe', 'profil');
        $teks = trim((string) $request->input('teks', ''));
        $engine = $request->input('engine', 'auto');

        // A. Jika diminta menyusun Draf Sambutan Kepala Madrasah
        if ($tipe === 'sambutan') {
            $namaKepsek = trim((string) $request->input('nama_kepsek', 'Kepala MIN 1 Ciamis'));
            $fokus = ! empty($teks) ? $teks : 'Pendidikan karakter Qur\'ani, keunggulan akademik, dan madrasah ramah anak';

            // Coba Gemini API jika API Key tersedia dan user tidak memaksa 'smart_local'
            if ($engine !== 'smart_local') {
                $apiKey = trim((string) config('services.gemini.key', ''));
                if (! empty($apiKey)) {
                    $geminiResult = $this->generateSambutanGemini($namaKepsek, $fokus, $apiKey, $engine);
                    if ($geminiResult) {
                        return response()->json($geminiResult);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'source' => 'Smart AI Studio Lokal',
                'kutipan' => 'Mewujudkan Generasi Madrasah yang Berakhlak Mulia, Cerdas, dan Unggul dalam Prestasi.',
                'isi' => "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\n"
                    ."Alhamdulillahi rabbil 'alamin, puji dan syukur senantiasa kita panjatkan ke hadirat Allah SWT. Selamat datang di portal informasi resmi Madrasah Ibtidaiyah Negeri (MIN) 1 Ciamis.\n\n"
                    ."Sebagai lembaga pendidikan dasar berciri khas Islam, kami berkomitmen penuh dalam menguatkan {$fokus}. Melalui sinergi antara pendidik profesional, fasilitas belajar modern, serta dukungan orang tua wali murid, mari bersama-sama kita antarkan putra-putri kita menjadi generasi kebanggaan umat dan bangsa.\n\n"
                    ."Wassalamu'alaikum Warahmatullahi Wabarakatuh.\n— {$namaKepsek}",
            ]);
        }

        // B. Jika diminta mengekstrak Deskripsi Profil / Misi menjadi 4 Poin + Ikon Otomatis
        $poinHasil = $this->ekstrakEmpatPoinCerdas($teks, $tipe);

        return response()->json([
            'status' => 'success',
            'source' => 'Smart AI Studio Lokal',
            'poin' => $poinHasil,
        ]);
    }

    /**
     * Memanggil Gemini API / Laravel AI Agent untuk menyusun draf sambutan Kepala Madrasah.
     *
     * @return array|null Hasil JSON jika berhasil, null jika gagal.
     */
    private function generateSambutanGemini(string $namaKepsek, string $fokus, string $apiKey, string $engine): ?array
    {
        // 1. Prioritas 1: Gunakan Laravel AI SDK Agent (SambutanKepsekAgent)
        try {
            $agent = new SambutanKepsekAgent;
            $agentPrompt = "Nama Kepala Madrasah: {$namaKepsek}\nFokus Tema / Pesan Inti: {$fokus}\nBuatkan naskah sambutan resmi website madrasah.";
            $aiOutput = $agent->prompt($agentPrompt);

            if (! empty($aiOutput['kutipan']) && ! empty($aiOutput['isi'])) {
                return [
                    'status' => 'success',
                    'source' => 'Laravel AI SDK (SambutanKepsekAgent)',
                    'kutipan' => $aiOutput['kutipan'],
                    'isi' => $aiOutput['isi'],
                ];
            }
        } catch (\Throwable $e) {
            // Lanjut ke Direct HTTP API jika SDK Agent mengalami kendala
        }

        $baseUrl = config('services.gemini.endpoint', 'https://generativelanguage.googleapis.com/v1beta');
        $model = ($engine !== 'smart_local') ? $engine : config('services.gemini.model', 'gemini-2.5-flash');

        $prompt = "Kamu adalah penulis sambutan resmi Kepala Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis) bernama {$namaKepsek}.\n"
            ."Buatkan sambutan kepala madrasah untuk website resmi MIN 1 Ciamis yang:\n"
            ."- Formal, hangat, dan islami\n"
            ."- Fokus tema: {$fokus}\n"
            ."- Minimal 3 paragraf, diawali salam pembuka dan diakhiri salam penutup\n\n"
            ."Kembalikan dalam format JSON murni:\n"
            ."{\n"
            .'  "kutipan": "1 kalimat kutipan inspiratif untuk banner",'."\n"
            .'  "isi": "Teks sambutan lengkap dengan salam pembuka dan penutup"'."\n"
            .'}';

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'force_ip_resolve' => 'v4',
                'verify' => false,
            ])->connectTimeout(15)->timeout(40)->post("{$baseUrl}/models/{$model}:generateContent", [
                'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.5, 'responseMimeType' => 'application/json'],
            ]);

            if ($response->successful()) {
                $parts = $response->json('candidates.0.content.parts') ?? [];
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

                if (is_array($parsed) && ! empty($parsed['kutipan']) && ! empty($parsed['isi'])) {
                    return [
                        'status' => 'success',
                        'source' => 'Google '.strtoupper($model),
                        'kutipan' => $parsed['kutipan'],
                        'isi' => $parsed['isi'],
                    ];
                }
            }
        } catch (\Exception $e) {
            // Gagal, akan fallback ke Smart AI Lokal
        }

        return null;
    }

    /**
     * Mesin Cerdas Peringkas Kalimat Menjadi 4 Poin + Penentu Ikon Kontekstual
     */
    private function ekstrakEmpatPoinCerdas(string $teks, string $tipe): array
    {
        // Pecah kalimat berdasarkan titik, koma, baris baru, atau kata penghubung
        $potongan = preg_split('/[\.\;\n\,]+|\bdan\b|\bserta\b/i', strip_tags($teks));
        $kandidat = [];

        foreach ($potongan as $kalimat) {
            $bersih = trim($kalimat);
            if (strlen($bersih) >= 8) {
                $kandidat[] = $this->ringkasJadiPoinPendek($bersih);
            }
        }

        // Hilangkan duplikat
        $kandidat = array_values(array_unique(array_filter($kandidat)));

        // Cadangan default jika teks input terlalu pendek
        $defaultProfil = [
            'Terakreditasi A (Unggul)',
            'Program Tahfidz Juz 30',
            'Madrasah Ramah Anak',
            'Berwawasan Lingkungan',
        ];
        $defaultMisi = [
            'Pembiasaan Ibadah & Tahfidz',
            'Pembelajaran Aktif & Digital',
            'Pembinaan Prestasi Sains & Seni',
            'Madrasah Hijau & Sehat',
        ];
        $cadangan = ($tipe === 'misi') ? $defaultMisi : $defaultProfil;

        $hasil = [];
        for ($i = 0; $i < 4; $i++) {
            $teksPoin = $kandidat[$i] ?? $cadangan[$i];
            $hasil[] = [
                'icon' => $this->tebakIkonTerbaik($teksPoin),
                'text' => $teksPoin,
            ];
        }

        return $hasil;
    }

    /**
     * Mengubah potongan kalimat panjang menjadi judul poin 3-4 kata yang padat
     */
    private function ringkasJadiPoinPendek(string $kalimat): string
    {
        $lower = strtolower($kalimat);

        // Deteksi frasa kunci populer di madrasah agar poinnya tajam dan profesional
        if (str_contains($lower, 'npsn')) {
            if (preg_match('/npsn\s*[0-9]+/i', $kalimat, $m)) {
                return strtoupper($m[0]);
            }
        }
        if (str_contains($lower, 'akreditasi')) {
            return 'Akreditasi A (Unggul)';
        }
        if (str_contains($lower, 'tahfidz') or str_contains($lower, 'qur\'an') or str_contains($lower, 'quran')) {
            return 'Pembiasaan Tahfidz Al-Qur\'an';
        }
        if (str_contains($lower, 'ibadah') or str_contains($lower, 'dhuha') or str_contains($lower, 'sholat') or str_contains($lower, 'shalat')) {
            return 'Pembiasaan Ibadah & Akhlak';
        }
        if (str_contains($lower, 'ramah anak') or str_contains($lower, 'bullying')) {
            return 'Madrasah Ramah Anak';
        }
        if (str_contains($lower, 'lingkungan') or str_contains($lower, 'adiwiyata') or str_contains($lower, 'hijau') or str_contains($lower, 'sampah')) {
            return 'Madrasah Hijau & Adiwiyata';
        }
        if (str_contains($lower, 'digital') or str_contains($lower, 'teknologi') or str_contains($lower, 'inovatif') or str_contains($lower, 'aktif')) {
            return 'Pembelajaran Aktif & Digital';
        }
        if (str_contains($lower, 'prestasi') or str_contains($lower, 'juara') or str_contains($lower, 'olimpiade') or str_contains($lower, 'sains')) {
            return 'Pembinaan Prestasi & Sains';
        }
        if (str_contains($lower, 'bahasa') or str_contains($lower, 'literasi') or str_contains($lower, 'baca')) {
            return 'Budaya Literasi & Bahasa';
        }

        // Jika kalimat bebas, buang kata depan umum lalu ambil maksimal 4 kata inti
        $kataBuang = ['menyelenggarakan', 'melaksanakan', 'mewujudkan', 'membina', 'meningkatkan', 'mengembangkan', 'adalah', 'merupakan', 'dengan', 'untuk', 'dalam', 'pada', 'yang', 'secara'];
        $kataKata = explode(' ', preg_replace('/[^a-zA-Z0-9\s\-]/', '', $kalimat));
        $kataInti = [];

        foreach ($kataKata as $k) {
            if (strlen($k) > 2 && ! in_array(strtolower($k), $kataBuang)) {
                $kataInti[] = ucfirst($k);
            }
            if (count($kataInti) >= 4) {
                break;
            }
        }

        return ! empty($kataInti) ? implode(' ', $kataInti) : Str::limit(ucwords($kalimat), 28, '');
    }

    /**
     * Pencocok Ikon FontAwesome Otomatis Berdasarkan Kata Kunci
     */
    private function tebakIkonTerbaik(string $teks): string
    {
        $lower = strtolower($teks);

        if (str_contains($lower, 'tahfidz') or str_contains($lower, 'qur')) {
            return 'fas fa-quran';
        }
        if (str_contains($lower, 'ibadah') or str_contains($lower, 'islam') or str_contains($lower, 'iman') or str_contains($lower, 'masjid')) {
            return 'fas fa-mosque';
        }
        if (str_contains($lower, 'akreditasi') or str_contains($lower, 'unggul') or str_contains($lower, 'mutu')) {
            return 'fas fa-award';
        }
        if (str_contains($lower, 'prestasi') or str_contains($lower, 'juara') or str_contains($lower, 'lomba')) {
            return 'fas fa-trophy';
        }
        if (str_contains($lower, 'digital') or str_contains($lower, 'teknologi') or str_contains($lower, 'inovatif') or str_contains($lower, 'aktif')) {
            return 'fas fa-laptop-code';
        }
        if (str_contains($lower, 'lingkungan') or str_contains($lower, 'hijau') or str_contains($lower, 'adiwiyata') or str_contains($lower, 'sampah')) {
            return 'fas fa-leaf';
        }
        if (str_contains($lower, 'ramah anak') or str_contains($lower, 'siswa') or str_contains($lower, 'santri')) {
            return 'fas fa-child';
        }
        if (str_contains($lower, 'literasi') or str_contains($lower, 'baca') or str_contains($lower, 'buku') or str_contains($lower, 'bahasa')) {
            return 'fas fa-book-reader';
        }
        if (str_contains($lower, 'sains') or str_contains($lower, 'riset') or str_contains($lower, 'ksm')) {
            return 'fas fa-flask';
        }
        if (str_contains($lower, 'guru') or str_contains($lower, 'pendidik')) {
            return 'fas fa-chalkboard-teacher';
        }
        if (str_contains($lower, 'global') or str_contains($lower, 'dunia')) {
            return 'fas fa-globe-asia';
        }
        if (str_contains($lower, 'karakter') or str_contains($lower, 'akhlak') or str_contains($lower, 'adab')) {
            return 'fas fa-star';
        }

        return 'fas fa-check-circle';
    }
}
