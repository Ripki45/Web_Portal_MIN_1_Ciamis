<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $media->judul }} — Dunia Ilmu MIN 1 Ciamis</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/assets/images/icon_min.png') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" />

    <!-- Tailwind CSS & Babel Standalone Compiler (Langsung di Head Tanpa Nested Script) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .player-topbar {
            height: 56px;
            background: rgba(15, 23, 42, 0.98);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
            z-index: 9999;
        }

        .player-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .player-brand img {
            width: 34px;
            height: 34px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 8px;
            padding: 3px;
        }

        .player-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .player-sub {
            font-size: 0.72rem;
            color: #34d399;
            font-weight: 700;
        }

        .player-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-player {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-player:hover {
            background: #1b9c85;
            border-color: #1b9c85;
            color: #ffffff;
        }

        .player-stage {
            flex-grow: 1;
            width: 100%;
            height: calc(100vh - 56px);
            overflow-y: auto;
            position: relative;
            background: #f8fafc;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        @media (max-width: 576px) {
            .d-none-sm {
                display: none;
            }

            .player-topbar {
                padding: 0 12px;
            }
        }
    </style>
</head>

<body>

    <!-- Topbar Navigasi Dunia Ilmu -->
    <header class="player-topbar">
        <div class="player-brand">
            <img src="{{ asset('backend/assets/images/icon_min.png') }}" alt="MIN 1 Ciamis">
            <div style="min-width: 0;">
                <div class="player-title">{{ $media->judul }}</div>
                <div class="player-sub">
                    ✦ Dunia Ilmu MIN 1 Ciamis &bull; {{ $media->kategori_label }} ({{ number_format($media->views) }}x
                    dimainkan)
                </div>
            </div>
        </div>

        <div class="player-actions">
            <button type="button" class="btn-player" onclick="window.location.reload()" title="Muat Ulang Aplikasi">
                <i class="fas fa-sync-alt"></i> <span class="d-none-sm">Ulangi</span>
            </button>
            <button type="button" class="btn-player" onclick="layarPenuhAplikasi()" title="Layar Penuh">
                <i class="fas fa-expand"></i> <span class="d-none-sm">Layar Penuh</span>
            </button>
            <a href="{{ route('home') }}#dunia-ilmu" class="btn-player"
                style="background: #1b9c85; border-color: #1b9c85;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </header>

    <!-- Area Render Langsung Aplikasi React TSX / HTML -->
    <div class="player-stage" id="stageContainer">
        <div id="root">
            <div
                style="min-height: calc(100vh - 56px); display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0f172a; color: #ffffff;">
                <div style="font-size: 18px; font-weight: 700; color: #34d399; margin-bottom: 8px;">
                    ⚡ Menyiapkan Media Interaktif MIN 1 Ciamis...
                </div>
                <div style="font-size: 13px; color: #94a3b8;">
                    Memuat komponen React &amp; Ikon Interaktif...
                </div>
            </div>
        </div>
    </div>

    <!-- Kode TSX Disimpan dalam Atribut Base64 agar Aman dari Pemotongan Karakter -->
    <div id="payloadKodeBase64" data-base64="{{ base64_encode((string) $media->isi_kode) }}" style="display: none;">
    </div>

    <script>
        function layarPenuhAplikasi() {
            var stage = document.getElementById('stageContainer');
            if (stage.requestFullscreen) {
                stage.requestFullscreen();
            } else if (stage.webkitRequestFullscreen) {
                stage.webkitRequestFullscreen();
            }
        }
    </script>

    <!-- Mesin Eksekutor TSX / JSX / JS Murni (Kompatibel Babel 8 Terbaru) -->
    <script type="module">
        import * as ReactModule from 'https://esm.sh/react@18.3.1';
        import * as ReactDOMClient from 'https://esm.sh/react-dom@18.3.1/client';
        import * as LucideIcons from 'https://esm.sh/lucide-react@0.460.0?deps=react@18.3.1';

        const React = ReactModule.default ?? ReactModule;
        window.React = React;

        const reactPkg = Object.assign({}, React, ReactModule, {
            default: React,
            __esModule: true
        });

        const reactDomPkg = Object.assign({}, ReactDOMClient.default ?? {}, ReactDOMClient, {
            __esModule: true
        });

        // Pengaman Ikon Pintar: Jika kode AI Studio memanggil nama ikon Lucide yang tidak ada, otomatis tampilkan ikon bintang agar aplikasi tidak crash
        const rawLucide = Object.assign({}, LucideIcons.default ?? {}, LucideIcons);
        const lucidePkg = new Proxy(rawLucide, {
            get: function(target, prop) {
                if (prop === '__esModule') return true;
                if (prop in target && target[prop] !== undefined) {
                    return target[prop];
                }
                return target.Sparkles ?? target.Activity ?? function FallbackIcon(props) {
                    return React.createElement('span', props, '✦');
                };
            }
        });

        // Jembatan Suara Otomatis (Web Speech API Bahasa Indonesia) untuk tombol "Putar Penjelasan" Guru
        const originalFetch = window.fetch;
        window.fetch = async function(url, options) {
            const urlStr = String(url);
            if (urlStr.includes('gemini-2.5-flash-preview-tts') && urlStr.endsWith('key=')) {
                try {
                    const bodyObj = JSON.parse(options.body);
                    const rawPrompt = bodyObj.contents[0].parts[0].text;
                    const matchQuote = rawPrompt.match(/Teks:\s*"([^"]+)"/i);
                    const textToSpeak = matchQuote ? matchQuote[1] : rawPrompt;
                    if ('speechSynthesis' in window) {
                        window.speechSynthesis.cancel();
                        const utter = new SpeechSynthesisUtterance(textToSpeak);
                        utter.lang = 'id-ID';
                        utter.rate = 0.95;
                        window.speechSynthesis.speak(utter);
                    }
                } catch (e) {}

                const silentPcm = new Uint8Array(24000 * 2);
                let binary = '';
                for (let i = 0; i < silentPcm.byteLength; i++) {
                    binary += String.fromCharCode(silentPcm[i]);
                }
                return new Response(JSON.stringify({
                    candidates: [{
                        content: {
                            parts: [{
                                inlineData: {
                                    mimeType: 'audio/pcm;rate=24000',
                                    data: window.btoa(binary)
                                }
                            }]
                        }
                    }]
                }), {
                    status: 200,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
            }
            return originalFetch.apply(this, arguments);
        };

        function decodeBase64Utf8(b64) {
            const bin = window.atob(b64);
            const bytes = new Uint8Array(bin.length);
            for (let i = 0; i < bin.length; i++) {
                bytes[i] = bin.charCodeAt(i);
            }
            return new TextDecoder('utf-8').decode(bytes);
        }

        async function jalankanMediaInteraktif() {
            const rootEl = document.getElementById('root');
            try {
                const payloadEl = document.getElementById('payloadKodeBase64');
                const b64 = payloadEl ? payloadEl.getAttribute('data-base64') : '';
                const sourceCode = decodeBase64Utf8(b64).trim();

                if (!sourceCode) {
                    throw new Error('Kode sumber aplikasi masih kosong.');
                }

                // 1. Jika yang diupload adalah dokumen HTML biasa
                const lower = sourceCode.toLowerCase();
                if (lower.startsWith('<!doctype') ? true : lower.startsWith('<html')) {
                    rootEl.innerHTML =
                        '<iframe id="htmlFrame" style="width:100%;height:calc(100vh - 56px);border:none;"></iframe>';
                    document.getElementById('htmlFrame').srcdoc = sourceCode;
                    return;
                }

                // 2. Kompilasi TypeScript (.tsx) & React JSX dengan Babel 8 (Tanpa opsi .isTSX yang sudah dihapus)
                const compiled = Babel.transform(sourceCode, {
                    presets: [
                        ['react', {
                            runtime: 'classic'
                        }],
                        'typescript'
                    ],
                    plugins: ['transform-modules-commonjs'],
                    filename: 'App.tsx'
                }).code;

                // 3. Eksekusi modul dengan penyedia paket (react, react-dom, lucide-react)
                const exportsObj = {};
                const moduleObj = {
                    exports: exportsObj
                };

                const customRequire = function(pkgName) {
                    if (pkgName === 'react') return reactPkg;
                    if (pkgName === 'react-dom' ? true : pkgName === 'react-dom/client') return reactDomPkg;
                    if (pkgName === 'lucide-react') return lucidePkg;
                    throw new Error('Paket eksternal belum terdaftar: ' + pkgName);
                };

                const evaluator = new Function('require', 'exports', 'module', 'React', compiled);
                evaluator(customRequire, exportsObj, moduleObj, React);

                const MainComponent = moduleObj.exports.default ?? exportsObj.default ?? moduleObj.exports;

                if (typeof MainComponent === 'function') {
                    rootEl.innerHTML = '';
                    const reactRoot = ReactDOMClient.createRoot(rootEl);
                    reactRoot.render(React.createElement(MainComponent));
                } else {
                    throw new Error('Komponen utama (export default function App) tidak ditemukan di dalam kode.');
                }
            } catch (err) {
                rootEl.innerHTML =
                    '<div style="padding:32px;color:#ef4444;font-family:monospace;background:#fef2f2;min-height:calc(100vh - 56px);">' +
                    '<strong style="font-size:16px;">Gagal Menjalankan Aplikasi TSX:</strong><br>' +
                    '<pre style="white-space:pre-wrap;margin-top:12px;font-size:13px;background:#fff;padding:16px;border-radius:8px;border:1px solid #fecaca;">' +
                    err.message +
                    '</pre></div>';
            }
        }

        jalankanMediaInteraktif();
    </script>
</body>

</html>
