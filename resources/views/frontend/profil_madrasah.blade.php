@extends('layouts.frontend')

@section('title', ($profil->judul_hero ?? 'Profil MIN 1 Ciamis') . ' — Madrasah Ibtidaiyah Negeri 1 Ciamis')

@section('content')

    <!-- ========================================================================= -->
    <!-- 1. PROFIL MADRASAH & FOTO GEDUNG INTERAKTIF                               -->
    <!-- ========================================================================= -->
    <section class="profil-intro-section" id="profil-intro" style="padding-top: 40px;">
        <div class="container">
            <div class="profil-intro-container">
                <div class="profil-intro-grid">
                    <div class="profil-intro-text">
                        <div class="sekilas-eyebrow" style="margin-bottom: 10px;">
                            <span class="sekilas-eyebrow-line"></span>
                            <span class="sekilas-eyebrow-text">{{ $profil->subjudul_hero ?? 'MADRASAH MAJU, BERMUTU, BERAKHLAK MULIA' }}</span>
                        </div>
                        <h1 class="profil-hero-title">{{ $profil->judul_hero }}</h1>

                        @if (!empty($profil->deskripsi_1))
                            <p class="profil-hero-desc">{{ $profil->deskripsi_1 }}</p>
                        @endif
                        @if (!empty($profil->deskripsi_2))
                            <p class="profil-hero-desc">{{ $profil->deskripsi_2 }}</p>
                        @endif
                        @if (!empty($profil->deskripsi_3))
                            <p class="profil-hero-desc">{{ $profil->deskripsi_3 }}</p>
                        @endif
                    </div>

                    <div class="profil-intro-media">
                        <div class="profil-interactive-photo" id="profilBuildingPhoto">
                            <div class="profil-photo-sheen"></div>
                            <img src="{{ $profil->foto_gedung_url }}"
                                 alt="{{ $profil->judul_hero }}"
                                 class="profil-building-img"
                                 style="border-radius: 20px;"
                                 onerror="this.onerror=null; this.src='{{ asset('frontend/assets/imgs/page/img-4.png') }}';">
                        </div>
                        @if (!empty($profil->caption_foto))
                            <p class="profil-media-caption">{{ $profil->caption_foto }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. IDENTITAS RESMI MADRASAH                                               -->
    <!-- ========================================================================= -->
    @if (!empty($profil->identitas_list))
        <section class="identity-section" id="identitas">
            <div class="container">
                <div class="section-header" style="margin-bottom: 30px;">
                    <span class="section-subtitle">Data Pokok Pendidikan</span>
                    <h2 class="section-title">Identitas Resmi Madrasah</h2>
                    <div class="section-divider"></div>
                </div>

                <div class="identity-grid">
                    @foreach ($profil->identitas_list as $idItem)
                        <div class="identity-card">
                            <div class="identity-icon">
                                <i class="{{ $idItem['icon'] ?? 'fas fa-school' }}"></i>
                            </div>
                            <div class="identity-card-content">
                                <h4>{{ $idItem['label'] ?? '' }}</h4>
                                <p>{{ $idItem['nilai'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ========================================================================= -->
    <!-- 3. VISI, MISI, DAN MOTTO MADRASAH                                         -->
    <!-- ========================================================================= -->
    <section class="section" id="visi-misi" style="background: #ffffff;">
        <div class="container">
            <div style="max-width: 1000px; margin: 0 auto;">
                <!-- A. Visi Madrasah -->
                <div style="background: linear-gradient(135deg, #09332C 0%, #115E59 100%); border-radius: 28px; padding: 40px 36px; color: #ffffff; margin-bottom: 52px; box-shadow: 0 20px 45px rgba(9, 51, 44, 0.18);">
                    <span style="display: inline-block; background: rgba(255, 225, 93, 0.2); color: #FFE15D; padding: 5px 16px; border-radius: 9999px; font-size: 12px; font-weight: 800; letter-spacing: 1px; margin-bottom: 14px;">
                        VISI MADRASAH
                    </span>
                    <h2 style="color: #ffffff; font-size: 1.85rem; line-height: 1.45; margin: 0; font-style: italic;">
                        &ldquo;{{ $profil->visi_teks }}&rdquo;
                    </h2>
                </div>

                <!-- B. Misi Madrasah -->
                @if (!empty($profil->misi_list))
                    <div class="misi-layout-grid" style="margin-bottom: 52px;">
                        <div class="misi-title-col">
                            <h2 class="misi-main-heading">MISI</h2>
                            <div class="misi-heading-bar"></div>
                        </div>
                        <ul class="misi-official-list">
                            @foreach ($profil->misi_list as $idx => $misiItem)
                                <li>
                                    <span class="misi-num">{{ $idx + 1 }}.</span>
                                    <span class="misi-text">{{ $misiItem }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- C. Motto Madrasah -->
                @if (!empty($profil->motto_teks))
                    <div id="motto" style="background: #f8fafc; border: 2px dashed rgba(27, 156, 133, 0.45); border-radius: 24px; padding: 32px 36px; display: flex; flex-wrap: wrap; align-items: center; gap: 22px;">
                        <div style="width: 64px; height: 64px; border-radius: 18px; background: #1B9C85; color: #FFE15D; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0; box-shadow: 0 10px 22px rgba(27, 156, 133, 0.25);">
                            <i class="fas fa-award"></i>
                        </div>
                        <div style="flex: 1; min-width: 240px;">
                            <span style="display: inline-block; color: #1B9C85; font-size: 12px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 4px;">
                                MOTTO MADRASAH
                            </span>
                            <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.55rem; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                                &ldquo;{{ $profil->motto_teks }}&rdquo;
                            </h3>
                            @if (!empty($profil->motto_uraian))
                                <p style="margin: 0; color: #475569; font-size: 0.95rem; line-height: 1.6;">
                                    {{ $profil->motto_uraian }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. LINI MASA SEJARAH MADRASAH                                             -->
    <!-- ========================================================================= -->
    @if (!empty($profil->sejarah_list))
        <section class="timeline-section" id="sejarah">
            <div class="container">
                <div class="section-header">
                    <span class="section-subtitle">Jejak Langkah</span>
                    <h2 class="section-title">Lini Masa Sejarah MIN 1 Ciamis</h2>
                    <div class="section-divider"></div>
                </div>

                <div class="timeline-container">
                    @foreach ($profil->sejarah_list as $sej)
                        <div class="timeline-block">
                            <div class="timeline-node"><i class="fas fa-star"></i></div>
                            <div class="timeline-card">
                                @if (!empty($sej['tahun']))
                                    <span class="timeline-badge"><i class="far fa-calendar-alt"></i> {{ $sej['tahun'] }}</span>
                                @endif
                                <h3 class="timeline-heading">{{ $sej['judul'] ?? '' }}</h3>
                                <p class="timeline-text">{{ $sej['uraian'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ========================================================================= -->
    <!-- 5. KEPALA SEKOLAH / KEPALA MADRASAH DARI MASA KE MASA                     -->
    <!-- ========================================================================= -->
    @if (!empty($profil->kepsek_masa_list))
        <section class="historic-timeline-section" id="kepala-madrasah">
            <div class="container">
                <div class="historic-header">
                    <span class="historic-subtitle">Estafet Kepemimpinan</span>
                    <h2 class="historic-title">Kepala Madrasah dari Masa ke Masa</h2>
                    <div class="historic-divider">
                        <span class="divider-line"></span>
                        <i class="fas fa-landmark divider-icon"></i>
                        <span class="divider-line"></span>
                    </div>
                    <p class="historic-desc">Dedikasi para pemimpin yang telah membina dan memajukan MIN 1 Ciamis lintas generasi.</p>
                </div>

                <div class="timeline-scroll-hint">
                    <i class="fas fa-arrows-alt-h"></i> Geser ke samping untuk melihat seluruh periode
                </div>

                <div class="historic-timeline-scroll-container">
                    <div class="historic-timeline-track" style="min-width: {{ max(760, count($profil->kepsek_masa_list) * 165) }}px;">
                        <div class="historic-pillars-row" style="grid-template-columns: repeat({{ count($profil->kepsek_masa_list) }}, 1fr);">
                            @foreach ($profil->kepsek_masa_list as $idx => $kp)
                                @php
                                    $isAktif = !empty($kp['is_aktif']);
                                    $staggerClass = 'stagger-' . (($idx % 7) + 1);
                                    $fotoKp = !empty($kp['foto'])
                                        ? asset('storage/' . $kp['foto'])
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($kp['nama'] ?? 'Kepsek') . '&background=0D6A56&color=fff&size=300';
                                @endphp
                                <div class="historic-col {{ $isAktif ? 'is-current-leader' : '' }}">
                                    <div class="pillar-frame {{ $staggerClass }} {{ $isAktif ? 'current-frame' : '' }}">
                                        @if ($isAktif)
                                            <span class="current-badge-ribbon">Aktif</span>
                                        @endif
                                        <img src="{{ $fotoKp }}"
                                             alt="{{ $kp['nama'] ?? '' }}"
                                             class="pillar-img {{ $isAktif ? 'color-active' : 'bw-filter' }}">
                                    </div>
                                    <div class="pillar-info">
                                        <div class="pillar-name">{{ $kp['nama'] ?? '' }}</div>
                                        <div class="pillar-years">{{ $kp['periode'] ?? '' }}</div>
                                    </div>
                                    <div class="pillar-stem-line {{ $isAktif ? 'current-stem' : '' }}"></div>
                                </div>
                            @endforeach
                        </div>

                        <div class="historic-axis-bar">
                            <div class="axis-nodes-row" style="grid-template-columns: repeat({{ count($profil->kepsek_masa_list) }}, 1fr);">
                                @foreach ($profil->kepsek_masa_list as $kp)
                                    <div class="axis-node-dot {{ !empty($kp['is_aktif']) ? 'is-current' : '' }}">
                                        @if (!empty($kp['is_aktif']))
                                            <span class="node-ring"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection