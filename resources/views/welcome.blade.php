<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Web Portal Resmi MIN 1 Ciamis - Layanan Presensi Digital, Informasi Akademik & Kepegawaian Madrasah Ibtidaiyah Negeri 1 Ciamis">
    <title>Web Portal Resmi - MIN 1 Ciamis</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('backend/assets/images/icon_min.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Styles -->
    <style>
        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --primary-darker: #166534;
            --primary-light: #22c55e;
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
            --accent-gold: #f59e0b;
            --dark-surface: #0f172a;
            --slate-body: #091e13;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Background ambient decorations */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
            background: 
                radial-gradient(circle at 15% 15%, rgba(34, 197, 94, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 85% 20%, rgba(245, 158, 11, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 85%, rgba(22, 163, 74, 0.09) 0%, transparent 55%),
                linear-gradient(180deg, #f0fdf4 0%, #ffffff 50%, #f8fafc 100%);
        }

        .ambient-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(22, 163, 74, 0.15) 1px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.6;
        }

        /* Top Header Navigation */
        .top-navbar {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-cluster {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-logos {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-img {
            height: 44px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.06));
        }

        .brand-divider {
            height: 28px;
            width: 1px;
            background-color: #cbd5e1;
        }

        .brand-meta h1 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-meta p {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .live-clock-pill {
            display: none;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #334155;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .live-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .btn-nav-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-nav-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.35);
            background: linear-gradient(135deg, #15803d, #14532d);
            color: #ffffff;
        }

        .btn-nav-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            color: #334155;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
        }

        .btn-nav-outline:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* Hero Section */
        .hero-section {
            padding: 56px 24px 40px;
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dcfce7;
            border: 1px solid #86efac;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 0.825rem;
            font-weight: 700;
            color: #166534;
            margin-bottom: 24px;
            box-shadow: 0 2px 4px rgba(22, 163, 74, 0.08);
            letter-spacing: 0.02em;
        }

        .hero-title {
            font-size: clamp(2.1rem, 4.5vw, 3.4rem);
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .hero-title-highlight {
            background: linear-gradient(135deg, #16a34a 20%, #0d9488 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: clamp(1rem, 1.8vw, 1.18rem);
            color: #475569;
            max-width: 740px;
            margin: 0 auto 36px;
            line-height: 1.65;
            font-weight: 400;
        }

        /* Hero CTA Buttons */
        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 16px;
            margin-bottom: 50px;
        }

        .btn-cta-scanner {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 16px 32px;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 8px 24px -4px rgba(22, 163, 74, 0.45);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: 2px solid rgba(255, 255, 255, 0.25);
            position: relative;
            overflow: hidden;
        }

        .btn-cta-scanner:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px -4px rgba(22, 163, 74, 0.55);
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            color: #ffffff;
        }

        .btn-cta-scanner i {
            font-size: 1.35rem;
        }

        .btn-cta-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #ffffff;
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 16px 28px;
            border-radius: 14px;
            text-decoration: none;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            transition: all 0.25s ease;
        }

        .btn-cta-login:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
            color: #16a34a;
        }

        /* Features & Quick Portal Grid */
        .portal-grid-section {
            max-width: 1200px;
            margin: 0 auto 60px;
            padding: 0 24px;
            width: 100%;
        }

        .section-header-box {
            text-align: center;
            margin-bottom: 32px;
        }

        .section-subtitle {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #16a34a;
            margin-bottom: 6px;
        }

        .section-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .portal-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 22px;
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            padding: 26px 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portal-card:hover {
            transform: translateY(-6px);
            border-color: #86efac;
            box-shadow: 0 16px 32px -8px rgba(22, 163, 74, 0.14);
            background: #ffffff;
        }

        .portal-card:hover::before {
            opacity: 1;
        }

        .card-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 18px;
            transition: transform 0.3s ease;
        }

        .portal-card:hover .card-icon-box {
            transform: scale(1.08);
        }

        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-blue { background: #e0f2fe; color: #0284c7; }
        .icon-amber { background: #fef3c7; color: #d97706; }
        .icon-purple { background: #f3e8ff; color: #9333ea; }

        .card-badge {
            position: absolute;
            top: 18px;
            right: 18px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 9999px;
            background: #f1f5f9;
            color: #475569;
        }

        .badge-active {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .card-text {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .card-action-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #16a34a;
            transition: gap 0.2s ease;
        }

        .portal-card:hover .card-action-link {
            gap: 10px;
            color: #15803d;
        }

        /* School Info & Stats Banner */
        .info-banner-section {
            max-width: 1200px;
            margin: 0 auto 70px;
            padding: 0 24px;
            width: 100%;
        }

        .banner-inner {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            border-radius: 24px;
            padding: 36px 32px;
            color: #ffffff;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            align-items: center;
            box-shadow: 0 20px 40px -10px rgba(21, 128, 61, 0.35);
            position: relative;
            overflow: hidden;
        }

        .banner-inner::after {
            content: '';
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        .banner-text h3 {
            font-size: 1.45rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -0.01em;
        }

        .banner-text p {
            font-size: 0.925rem;
            color: #dcfce7;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .banner-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            background: rgba(0, 0, 0, 0.15);
            padding: 20px;
            border-radius: 16px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .stat-item h4 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .stat-item span {
            font-size: 0.75rem;
            font-weight: 600;
            color: #86efac;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Footer */
        .site-footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 30px 24px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #1e293b;
        }

        .footer-brand img {
            height: 24px;
            width: auto;
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #16a34a;
        }

        /* Responsive Breakpoints */
        @media (min-width: 768px) {
            .live-clock-pill {
                display: flex;
            }
        }

        @media (max-width: 640px) {
            .nav-container {
                padding: 12px 16px;
            }
            .hero-section {
                padding: 36px 16px 28px;
            }
            .hero-actions {
                flex-direction: column;
                width: 100%;
            }
            .btn-cta-scanner, .btn-cta-login {
                width: 100%;
            }
            .banner-inner {
                padding: 24px 20px;
            }
            .banner-stats {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                padding: 14px 8px;
            }
            .stat-item h4 {
                font-size: 1.25rem;
            }
            .stat-item span {
                font-size: 0.65rem;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Background Lighting -->
    <div class="ambient-bg">
        <div class="ambient-pattern"></div>
    </div>

    <!-- Top Navigation Bar -->
    <header class="top-navbar">
        <div class="nav-container">
            <!-- Brand & Logos -->
            <a href="{{ url('/') }}" class="brand-cluster">
                <div class="brand-logos">
                    <img src="{{ asset('backend/assets/images/logo_kemenag.png') }}" alt="Kemenag" class="logo-img" title="Kementerian Agama RI">
                    <img src="{{ asset('backend/assets/images/logo_min.png') }}" alt="MIN 1 Ciamis" class="logo-img" title="MIN 1 Ciamis">
                </div>
                <div class="brand-divider"></div>
                <div class="brand-meta">
                    <h1>MIN 1 CIAMIS</h1>
                    <p>Kabupaten Ciamis - Jawa Barat</p>
                </div>
            </a>

            <!-- Right Nav Actions -->
            <div class="nav-actions">
                <!-- Live Clock Pill -->
                <div class="live-clock-pill">
                    <div class="live-pulse-dot"></div>
                    <span id="liveClockText">Memuat waktu...</span>
                </div>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-nav-primary">
                            <i class="ph-bold ph-squares-four"></i>
                            <span>Dashboard Utama</span>
                        </a>
                    @else
                        <a href="{{ route('presensi.scanner') }}" class="btn-nav-outline" title="Presensi Mandiri Guru & GTK">
                            <i class="ph-bold ph-qr-code"></i>
                            <span>Scan Presensi</span>
                        </a>

                        <a href="{{ route('login') }}" class="btn-nav-primary">
                            <i class="ph-bold ph-sign-in"></i>
                            <span>Login Portal</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-badge">
                <i class="ph-fill ph-sparkle"></i>
                <span>MADRASAH MAJU, BERMUTU & MENDUNIA</span>
            </div>

            <h2 class="hero-title">
                Sistem Informasi & Web Portal<br>
                <span class="hero-title-highlight">MIN 1 Ciamis</span>
            </h2>

            <p class="hero-desc">
                Pusat layanan digital terpadu Madrasah Ibtidaiyah Negeri 1 Ciamis untuk pemantauan presensi harian GTK secara real-time, pengelolaan data kepegawaian, kesiswaan, dan tata kelola akademik yang akuntabel.
            </p>

            <!-- Main Call-to-Actions -->
            <div class="hero-actions">
                <!-- Tombol Scan Presensi Cepat -->
                <a href="{{ route('presensi.scanner') }}" class="btn-cta-scanner">
                    <i class="ph-bold ph-qr-code"></i>
                    <span>Buka Scanner Presensi Harian</span>
                </a>

                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-cta-login">
                        <i class="ph-bold ph-gauge"></i>
                        <span>Masuk ke Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-cta-login">
                        <i class="ph-bold ph-lock-key"></i>
                        <span>Login Super Admin & GTK</span>
                    </a>
                @endauth
            </div>
        </section>

        <!-- Quick Access Portal Grid -->
        <section class="portal-grid-section">
            <div class="section-header-box">
                <p class="section-subtitle">Layanan Terpadu Madrasah</p>
                <h3 class="section-title">Modul Akses Cepat Sistem</h3>
            </div>

            <div class="portal-cards-grid">
                <!-- Card 1: Presensi Harian Guru & GTK -->
                <a href="{{ route('presensi.scanner') }}" class="portal-card">
                    <div class="card-badge badge-active">
                        <i class="ph-bold ph-circle"></i> Live Scanner
                    </div>
                    <div>
                        <div class="card-icon-box icon-green">
                            <i class="ph-duotone ph-scan"></i>
                        </div>
                        <h4 class="card-title">Presensi Mandiri GTK</h4>
                        <p class="card-text">
                            Pemindaian kartu QR Code untuk kehadiran masuk & pulang Guru dan Tendik secara cepat dan akurat.
                        </p>
                    </div>
                    <div class="card-action-link">
                        <span>Akses Scanner Presensi</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </div>
                </a>

                <!-- Card 2: Manajemen GTK -->
                <a href="{{ auth()->check() ? route('gtk.index') : route('login') }}" class="portal-card">
                    <div class="card-badge">Data Kepegawaian</div>
                    <div>
                        <div class="card-icon-box icon-blue">
                            <i class="ph-duotone ph-users-three"></i>
                        </div>
                        <h4 class="card-title">Data Guru & Tendik</h4>
                        <p class="card-text">
                            Pengelolaan biodata pendidik, NIP/NUPTK, status kepegawaian, jabatan struktural, dan posisi tugas madrasah.
                        </p>
                    </div>
                    <div class="card-action-link">
                        <span>Kelola Tenaga Pendidik</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </div>
                </a>

                <!-- Card 3: Manajemen Kesiswaan & Kelas -->
                <a href="{{ auth()->check() ? route('siswa.index') : route('login') }}" class="portal-card">
                    <div class="card-badge">Akademik</div>
                    <div>
                        <div class="card-icon-box icon-amber">
                            <i class="ph-duotone ph-student"></i>
                        </div>
                        <h4 class="card-title">Data Siswa & Kelas</h4>
                        <p class="card-text">
                            Registrasi data siswa aktif, impor Excel cepat, pembagian rombel kelas, wali kelas, mutasi, dan kenaikan kelas.
                        </p>
                    </div>
                    <div class="card-action-link">
                        <span>Layanan Kesiswaan</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </div>
                </a>

                <!-- Card 4: Rekapitulasi Presensi & Cetak Kartu QR -->
                <a href="{{ auth()->check() ? route('presensi.rekap') : route('login') }}" class="portal-card">
                    <div class="card-badge">Laporan</div>
                    <div>
                        <div class="card-icon-box icon-purple">
                            <i class="ph-duotone ph-chart-line-up"></i>
                        </div>
                        <h4 class="card-title">Rekap Presensi & QR Card</h4>
                        <p class="card-text">
                            Pencetakan kartu ID QR presensi GTK, rekapitulasi kehadiran bulanan, setting jam kerja, dan kalender libur.
                        </p>
                    </div>
                    <div class="card-action-link">
                        <span>Buka Laporan & Cetak</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </div>
                </a>
            </div>
        </section>

        <!-- Information & Madrasah Highlight Banner -->
        <section class="info-banner-section">
            <div class="banner-inner">
                <div class="banner-text">
                    <h3>Madrasah Ibtidaiyah Negeri 1 Ciamis</h3>
                    <p>
                        Mewujudkan generasi berakhlak mulia, cerdas, terampil, mandiri, dan berwawasan lingkungan islami melalui pemanfaatan ekosistem teknologi pendidikan digital yang modern.
                    </p>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.825rem; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 8px;">
                            <i class="ph-fill ph-map-pin"></i> Kabupaten Ciamis, Jawa Barat
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.825rem; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 8px;">
                            <i class="ph-fill ph-clock"></i> Senin - Sabtu
                        </span>
                    </div>
                </div>

                <div class="banner-stats">
                    <div class="stat-item">
                        <h4>Real-Time</h4>
                        <span>Presensi GTK</span>
                    </div>
                    <div class="stat-item">
                        <h4>Terpadu</h4>
                        <span>Data Siswa</span>
                    </div>
                    <div class="stat-item">
                        <h4>Aman</h4>
                        <span>Sistem Cloud</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Site Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <img src="{{ asset('backend/assets/images/logo_min.png') }}" alt="Logo MIN 1 Ciamis">
                <span>&copy; {{ date('Y') }} MIN 1 Ciamis. Hak Cipta Dilindungi.</span>
            </div>

            <div class="footer-links">
                <a href="{{ route('presensi.scanner') }}">Presensi Mandiri</a>
                <a href="{{ route('login') }}">Login Petugas</a>
                <span>v{{ app()->version() }}</span>
            </div>
        </div>
    </footer>

    <!-- Interactive Live Clock Script -->
    <script>
        function updateLiveClock() {
            const clockEl = document.getElementById('liveClockText');
            if (!clockEl) return;

            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const dayName = days[now.getDay()];
            const dateNum = now.getDate();
            const monthName = months[now.getMonth()];
            const yearNum = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            clockEl.textContent = `${dayName}, ${dateNum} ${monthName} ${yearNum} • ${hours}:${minutes}:${seconds} WIB`;
        }

        // Jalankan jam segera lalu perbarui setiap detik
        updateLiveClock();
        setInterval(updateLiveClock, 1000);
    </script>
</body>
</html>
