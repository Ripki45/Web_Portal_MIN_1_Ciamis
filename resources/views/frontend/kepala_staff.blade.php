@extends('layouts.frontend')

@section('title', 'Kepala Madrasah dan Staff Manajemen — MIN 1 Ciamis')

@section('content')

    <style>
        /* Hero Sambutan Eksekutif Kepala Madrasah */
        .kepsek-exec-section {
            position: relative;
            background: #ffffff;
            padding: 48px 0 80px;
            overflow: hidden;
        }
        .kepsek-quote-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-left: 5px solid #1B9C85;
            border-radius: 0 20px 20px 0;
            padding: 20px 24px;
            margin: 18px 0 24px;
            box-shadow: 0 8px 24px rgba(27, 156, 133, 0.08);
        }
        .kepsek-signature-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1.5px dashed #cbd5e1;
        }

        /* Filter Pill Bidang Manajemen */
        .manajemen-filter-bar {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 36px;
        }
        .manajemen-filter-btn {
            padding: 9px 22px;
            border-radius: 9999px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .manajemen-filter-btn:hover {
            border-color: #1B9C85;
            color: #1B9C85;
        }
        .manajemen-filter-btn.active {
            background: #1B9C85;
            border-color: #1B9C85;
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(27, 156, 133, 0.28);
        }

        /* Susunan Grid Staff Manajemen Responsif */
        .manajemen-cards-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
            gap: 28px;
            max-width: 1120px;
            margin: 0 auto;
        }
    </style>

    <!-- ========================================================================= -->
    <!-- 1. BAGIAN ATAS: SAMBUTAN & PROFIL EKSEKUTIF KEPALA MADRASAH               -->
    <!-- ========================================================================= -->
    <section class="kepsek-exec-section sekilas-section" id="sambutan-kepala">
        <div class="sekilas-gradasi-garis"></div>

        <div class="container" style="position: relative; z-index: 2;">
            <div class="sekilas-grid">

                <!-- SISI KIRI: POTRET KEPALA MADRASAH + BADGE IDENTITAS RESMI -->
                <div class="sekilas-card-container">
                    <div class="sekilas-card-decor-gradient"></div>
                    <div class="sekilas-card-frame">
                        <div class="sekilas-card-img-wrapper" style="height: 500px !important;">
                            <img src="{{ $data->foto_kepsek_url }}"
                                 alt="{{ $data->nama_kepsek }}"
                                 class="sekilas-card-img"
                                 style="object-position: top center;"
                                 onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">

                            <!-- Glass Badge Nama & NIP Kepala Madrasah -->
                            <div style="position: absolute; bottom: 18px; left: 18px; right: 18px; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1.5px solid rgba(255, 225, 93, 0.6); border-radius: 20px; padding: 16px 20px; color: #ffffff; box-shadow: 0 14px 32px rgba(0, 0, 0, 0.3);">
                                <div style="display: inline-flex; align-items: center; gap: 6px; background: #1B9C85; color: #FFE15D; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 6px;">
                                    <i class="fas fa-user-tie"></i> {{ $data->jabatan_kepsek }}
                                </div>
                                <div style="font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 800; color: #ffffff; line-height: 1.25;">
                                    {{ $data->nama_kepsek }}
                                </div>
                                @if (!empty($data->nip_kepsek))
                                    <div style="font-size: 0.78rem; color: #cbd5e1; margin-top: 3px; font-family: monospace;">
                                        NIP. {{ $data->nip_kepsek }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SISI KANAN: TEKS SAMBUTAN KEPALA MADRASAH -->
                <div class="sekilas-content-wrap">
                    <div class="sekilas-eyebrow">
                        <span class="sekilas-eyebrow-line"></span>
                        <span class="sekilas-eyebrow-text">PIMPINAN &amp; MANAJEMEN MADRASAH</span>
                    </div>

                    <h1 class="sekilas-title" style="margin-bottom: 14px !important;">
                        <span class="title-italic">SAMBUTAN</span>
                        <span class="title-highlight">KEPALA MADRASAH</span>
                    </h1>

                    <!-- Kotak Kutipan Sorotan -->
                    @if (!empty($data->sambutan_kutipan))
                        <div class="kepsek-quote-box">
                            <div style="font-size: 0.75rem; font-weight: 800; color: #1B9C85; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">
                                <i class="fas fa-quote-left"></i> Pesan Utama
                            </div>
                            <h3 class="sekilas-quote" style="margin: 0 !important; font-size: 1.32rem !important;">
                                &ldquo;{{ $data->sambutan_kutipan }}&rdquo;
                            </h3>
                        </div>
                    @endif

                    <!-- Isi Sambutan Lengkap -->
                    <div class="sekilas-text" style="white-space: pre-line; font-size: 1.02rem; line-height: 1.8;">{{ $data->sambutan_isi }}</div>

                    <!-- Footer Tanda Tangan Kepala Madrasah -->
                    <div class="kepsek-signature-bar">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="{{ asset('backend/assets/images/icon_min.png') }}"
                                 alt="Logo MIN 1 Ciamis"
                                 style="width: 48px; height: 48px; object-fit: contain;">
                            <div>
                                <div style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.05rem; color: #0f172a;">
                                    {{ $data->nama_kepsek }}
                                </div>
                                <div style="font-size: 0.82rem; font-weight: 700; color: #1B9C85;">
                                    {{ $data->jabatan_kepsek }} &bull; MIN 1 Ciamis
                                </div>
                            </div>
                        </div>

                        <a href="#staff-manajemen" class="sekilas-detail-link" style="margin-top: 0 !important;">
                            <span>LIHAT STAFF MANAJEMEN</span>
                            <span class="sekilas-arrow-circle"><i class="fas fa-arrow-down"></i></span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. BAGIAN BAWAH: JAJARAN STAFF MANAJEMEN & WAKIL KEPALA MADRASAH          -->
    <!-- ========================================================================= -->
    @php
        $timList = $data->tim_manajemen_list ?? [];
        $daftarBidangUnik = collect($timList)
            ->pluck('bidang')
            ->filter()
            ->unique()
            ->values();
    @endphp

    <section class="section section-bg-alt" id="staff-manajemen">
        <div class="container">
            <div class="section-header" style="margin-bottom: 32px;">
                <span class="section-subtitle">Sinergi &amp; Tata Kelola</span>
                <h2 class="section-title">Kepala Madrasah &amp; Staff Manajemen</h2>
                <p class="section-desc">
                    Jajaran pimpinan, wakil kepala madrasah, dan koordinator bidang yang bersinergi menghadirkan layanan pendidikan terbaik di MIN 1 Ciamis.
                </p>
                <div class="section-divider"></div>
            </div>

            <!-- Filter Bidang (Otomatis muncul jika ada kategori bidang) -->
            @if ($daftarBidangUnik->count() > 1)
                <div class="manajemen-filter-bar">
                    <button type="button" class="manajemen-filter-btn active" onclick="filterBidangManajemen('semua', this)">
                        Semua Bidang ({{ count($timList) + 1 }})
                    </button>
                    <button type="button" class="manajemen-filter-btn" onclick="filterBidangManajemen('Pimpinan', this)">
                        Pimpinan Utama
                    </button>
                    @foreach ($daftarBidangUnik as $bdg)
                        <button type="button" class="manajemen-filter-btn" onclick="filterBidangManajemen('{{ addslashes($bdg) }}', this)">
                            {{ $bdg }}
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Grid Kartu Glassmorphism 3D (Kepala Madrasah + Seluruh Staff Manajemen) -->
            <div class="manajemen-cards-wrapper" id="gridKartuManajemen">

                <!-- Kartu Utama: Kepala Madrasah -->
                <div class="tim-manajemen-card item-kartu-manajemen" data-bidang="Pimpinan" style="border: 2px solid rgba(27, 156, 133, 0.45);">
                    <div class="tim-card-img-wrapper">
                        <span class="tim-card-top-tag" style="background: rgba(27, 156, 133, 0.92);">
                            <i class="fas fa-crown" style="color: #FFE15D; margin-right: 4px;"></i> Pimpinan Utama
                        </span>
                        <img src="{{ $data->foto_kepsek_url }}"
                             alt="{{ $data->nama_kepsek }}"
                             class="tim-card-img"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($data->nama_kepsek) }}&background=0D6A56&color=fff&size=400';">
                        <div class="tim-card-glass-badge">
                            <h4 class="tim-leader-name">{{ $data->nama_kepsek }}</h4>
                            <p class="tim-leader-role">{{ $data->jabatan_kepsek }}</p>
                            @if (!empty($data->nip_kepsek))
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 3px; font-family: monospace;">
                                    NIP. {{ $data->nip_kepsek }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kartu Jajaran Staff Manajemen dari Database -->
                @foreach ($timList as $tm)
                    @php
                        $namaStaff   = $tm['nama'] ?? 'Staff Manajemen';
                        $jabatanStf  = $tm['jabatan'] ?? 'Koordinator Bidang';
                        $bidangStf   = $tm['bidang'] ?? 'Manajemen';
                        $nipStf      = $tm['nip'] ?? '';
                        $fotoStfUrl  = !empty($tm['foto'])
                            ? asset('storage/' . $tm['foto'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($namaStaff) . '&background=1B9C85&color=fff&size=400';
                    @endphp
                    <div class="tim-manajemen-card item-kartu-manajemen" data-bidang="{{ $bidangStf }}">
                        <div class="tim-card-img-wrapper">
                            <span class="tim-card-top-tag">{{ $bidangStf }}</span>
                            <img src="{{ $fotoStfUrl }}"
                                 alt="{{ $namaStaff }}"
                                 class="tim-card-img"
                                 loading="lazy">
                            <div class="tim-card-glass-badge">
                                <h4 class="tim-leader-name">{{ $namaStaff }}</h4>
                                <p class="tim-leader-role">{{ $jabatanStf }}</p>
                                @if (!empty($nipStf) && $nipStf !== '-')
                                    <div style="font-size: 0.72rem; color: #64748b; margin-top: 3px; font-family: monospace;">
                                        NIP. {{ $nipStf }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <script>
        function filterBidangManajemen(bidang, btnEl) {
            document.querySelectorAll('.manajemen-filter-btn').forEach(function (b) {
                b.classList.remove('active');
            });
            if (btnEl) btnEl.classList.add('active');

            document.querySelectorAll('.item-kartu-manajemen').forEach(function (card) {
                var cardBidang = card.getAttribute('data-bidang');
                if (bidang === 'semua' ? true : cardBidang === bidang) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

@endsection