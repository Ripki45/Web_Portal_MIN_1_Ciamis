/**
 * MIN 1 KOTA MALANG - Interactive Script
 * Handles slider, counters, modals, video lightbox, search, and navigation.
 */

document.addEventListener('DOMContentLoaded', () => {
  initHeroLottie();
  initHeroSlider();
  initCustomSiteImages();
  initAnimatedCounters();
  initStickyHeader();
  initModals();
  initVideoLightbox();
  initSearch();
  initMobileDrawer();
  initDesktopDropdowns();
  initScrollToTop();
  initTimelineAnimation();
  initInteractivePilarExplorer();
  initInteractiveTalentQuiz();
  initNewsFilter();
  initMutiaraHikmah();
  initInteractiveTilt();
  initConfettiCelebration();
  init3dFloatingTrophy();
  initLunarTrophies();
  initArticleGallerySlider();
  initHeadmasterTimeline();
  initTimManajemenCards();
  initProfilBuildingPhoto();
  initThemeSwitcher();
});

/* --- THEME SWITCHER (DARK & LIGHT MODE) --- */
function initThemeSwitcher() {
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.dark-light-switcher');
    if (!btn) return;

    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || localStorage.getItem('theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    btn.style.transform = 'scale(0.88)';
    setTimeout(() => {
      btn.style.transform = '';
    }, 150);
  });
}

/* --- 0. HERO LOTTIE ANIMATION --- */
function initHeroLottie() {
  const container = document.getElementById('hero-lottie-container');
  if (!container || typeof lottie === 'undefined') return;

  const getAspectRatio = () => window.innerWidth < 768 ? 'xMidYMin meet' : 'xMidYMin slice';

  const animConfig = {
    container: container,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    rendererSettings: {
      preserveAspectRatio: getAspectRatio(),
      clearCanvas: true,
      progressiveLoad: true
    }
  };

  // Prioritize animationData if loaded via script (avoids all CORS restrictions on file:// & localhost)
  if (typeof window.lotiHeroData !== 'undefined' && window.lotiHeroData) {
    animConfig.animationData = window.lotiHeroData;
  } else {
    animConfig.path = 'assets/loti/loti%20hero%20min%201.json';
  }

  const anim = lottie.loadAnimation(animConfig);

  // Fallback if path fetch fails
  anim.addEventListener('data_failed', () => {
    if (typeof window.lotiHeroData !== 'undefined' && window.lotiHeroData) {
      container.innerHTML = '';
      lottie.loadAnimation({
        container: container,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        animationData: window.lotiHeroData,
        rendererSettings: {
          preserveAspectRatio: getAspectRatio()
        }
      });
    }
  });

  // Handle responsive window resize
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (anim && anim.renderer && anim.renderer.svgElement) {
        anim.renderer.svgElement.setAttribute('preserveAspectRatio', getAspectRatio());
      }
    }, 200);
  });
}

/* --- 1. HERO SLIDER --- */
function initHeroSlider() {
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('.slider-dot');
  const prevBtn = document.querySelector('.slider-arrow.prev');
  const nextBtn = document.querySelector('.slider-arrow.next');
  let currentSlide = 0;
  let slideInterval;

  if (!slides.length) return;

  function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    if (dots[currentSlide]) dots[currentSlide].classList.add('active');
  }

  function nextSlide() {
    showSlide(currentSlide + 1);
  }

  function prevSlide() {
    showSlide(currentSlide - 1);
  }

  function startAutoplay() {
    slideInterval = setInterval(nextSlide, 10000);
  }

  function resetAutoplay() {
    clearInterval(slideInterval);
    startAutoplay();
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      resetAutoplay();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      resetAutoplay();
    });
  }

  dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
      showSlide(idx);
      resetAutoplay();
    });
  });

  const sliderWrapper = document.querySelector('.hero-slider-section');
  if (sliderWrapper) {
    sliderWrapper.addEventListener('mouseenter', () => clearInterval(slideInterval));
    sliderWrapper.addEventListener('mouseleave', () => startAutoplay());
  }

  startAutoplay();
}

/* --- 2. ANIMATED COUNTERS --- */
function initAnimatedCounters() {
  const counterElements = document.querySelectorAll('.stat-number');
  const totalCounter = document.getElementById('total-prestasi-counter');
  let animated = false;

  const countUp = (el, target, duration = 2000) => {
    let start = 0;
    const stepTime = 20;
    const steps = duration / stepTime;
    const increment = target / steps;

    const timer = setInterval(() => {
      start += increment;
      if (start >= target) {
        el.innerText = target.toLocaleString('id-ID');
        clearInterval(timer);
      } else {
        el.innerText = Math.floor(start).toLocaleString('id-ID');
      }
    }, stepTime);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !animated) {
        animated = true;
        counterElements.forEach(el => {
          const target = parseInt(el.getAttribute('data-target') || '0', 10);
          countUp(el, target);
        });

        if (totalCounter) {
          const totalTarget = parseInt(totalCounter.getAttribute('data-target') || '1924', 10);
          countUp(totalCounter, totalTarget, 2200);
        }
      }
    });
  }, { threshold: 0.25 });

  const statsSection = document.querySelector('.stats-section');
  if (statsSection) observer.observe(statsSection);
}

/* --- 3. STICKY HEADER & SCROLL-TO-TOP --- */
function initStickyHeader() {
  const header = document.querySelector('.site-header');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
}

function initScrollToTop() {
  const backToTopBtn = document.getElementById('back-to-top-btn');
  if (!backToTopBtn) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      backToTopBtn.classList.add('visible');
    } else {
      backToTopBtn.classList.remove('visible');
    }
  });

  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* --- 4. MODALS & NEWS READER --- */
const newsDatabase = {
  1: {
    title: "Ayo Berinovasi! MIN 1 Ciamis Ajak MI Swasta Wujudkan \"Madrasah Lestari\" Lewat Kompetisi Bergengsi",
    date: "04 September 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-1.png",
    content: `
      <p>MIN 1 Ciamis kembali menunjukkan kepeloporannya dalam dunia pendidikan madrasah dengan menginisiasi kompetisi inovasi lingkungan bertajuk "Madrasah Lestari". Program ini melibatkan madrasah ibtidaiyah swasta se-Malang Raya untuk bersama-sama menciptakan lingkungan belajar yang asri, hijau, dan berorientasi pada pelestarian alam.</p>
      <p>Kepala MIN 1 Ciamis menyampaikan bahwa pendidikan adiwiyata dan kesadaran ekologis harus ditanamkan sejak dini kepada peserta didik. Melalui kompetisi ini, madrasah diajak bertransformasi dalam pengelolaan sampah, penanaman pohon, dan digitalisasi madrasah ramah anak.</p>
      <p>Kegiatan ini disambut dengan antusiasme tinggi dari berbagai perwakilan madrasah yang hadir dan siap berkolaborasi dalam gerakan madrasah lestari masa depan.</p>
    `
  },
  2: {
    title: "48 Tahun Menjaga Asa, Mengalirkan Cahaya bagi Bangsa: MIN 1 Ciamis Semarakan HUT ke-48",
    date: "02 September 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-2.jpg",
    content: `
      <p>Bulan September menjadi momen yang sangat istimewa bagi segenap keluarga besar MIN 1 Ciamis seiring dengan peringatan Hari Ulang Tahun ke-48 madrasah tercinta.</p>
      <p>Dengan mengusung tema <em>"48 Tahun Menjaga Asa, Mengalirkan Cahaya bagi Bangsa"</em>, serangkaian acara digelar secara khidmat dan meriah, mulai dari khotmil Qur'an, festival kreasi seni siswa, santunan anak yatim, hingga pameran karya inovasi pembelajaran digital.</p>
      <p>Selama hampir setengah abad berkiprah, MIN 1 Ciamis telah melahirkan ribuan alumni berprestasi yang kini berkontribusi di kancah nasional maupun internasional di berbagai bidang profesi terkemuka.</p>
    `
  },
  3: {
    title: "MIN 1 Ciamis Raih Juara 2 Mini Drama Competition Berbahasa Inggris di Expo Madrasah Jatim 2026",
    date: "01 September 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-3.jpeg",
    content: `
      <p>Prestasi membanggakan kembali ditorehkan oleh tim bahasa Inggris MIN 1 Ciamis yang sukses meraih Juara 2 dalam ajang bergengsi <em>Mini Drama Competition</em> tingkat Jawa Timur yang digelar pada Expo Madrasah Jatim 2026.</p>
      <p>Menampilkan penampilan teatrikal yang memadukan kefasihan berbahasa Inggris dengan kearifan lokal Topeng Malangan dan kepedulian lingkungan, para siswa berhasil memukau dewan juri dan penonton.</p>
      <p>Penghargaan ini semakin menegaskan komitmen madrasah dalam mengembangkan kompetensi global siswa tanpa melupakan akar budaya nusantara.</p>
    `
  },
  4: {
    title: "OBA 2026 Tingkat Jawa Timur, Kakanwil Kemenag Jatim Tekankan Bahasa Arab sebagai Bahasa Peradaban",
    date: "28 Agustus 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-4.jpg",
    content: `
      <p>Olimpiade Bahasa Arab (OBA) ke-IX tingkat Provinsi Jawa Timur resmi dibuka di MIN 1 Ciamis. Dalam sambutannya, Kepala Kantor Wilayah Kementerian Agama Provinsi Jawa Timur menekankan bahwa bahasa Arab bukan hanya bahasa ibadah, melainkan bahasa ilmu pengetahuan dan peradaban dunia.</p>
      <p>Ajang ini menjadi tolok ukur penguasaan bahasa Arab siswa madrasah dari 38 kabupaten dan kota di Jawa Timur, yang berkompetisi secara sehat dan penuh semangat sportivitas.</p>
    `
  },
  5: {
    title: "MIN 1 Ciamis Jadi Tuan Rumah OBA ke-IX Tingkat Jawa Timur, Ratusan Peserta Hadir dari 38 Kabupaten/Kota",
    date: "27 Agustus 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-5.jpg",
    content: `
      <p>Sebagai tuan rumah penyelenggaraan Olimpiade Bahasa Arab (OBA) ke-IX Jawa Timur, MIN 1 Ciamis menyambut ratusan delegasi peserta dan guru pendamping dari seluruh penjuru Jawa Timur.</p>
      <p>Fasilitas digital madrasah seperti laboratorium komputer canggih dan aula serbaguna disiapkan secara prima guna menjamin kelancaran kompetisi berbasis CBT (Computer Based Test).</p>
    `
  },
  6: {
    title: "MIN 1 Ciamis Jadi Tuan Rumah Kompetisi Sains Nalaria Realistik 8, 1.700 Siswa Se-Malang Raya Adu Kemampuan",
    date: "23 Agustus 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-6.jpeg",
    content: `
      <p>Sebanyak 1.700 siswa jenjang SD/MI se-Malang Raya memadati kampus MIN 1 Ciamis untuk mengikuti Kompetisi Sains Nalaria Realistik (KSNR) ke-8.</p>
      <p>Kompetisi ini bertujuan untuk mengasah nalar kritis dan pemahaman konsep sains secara aplikatif dalam kehidupan sehari-hari bagi generasi muda.</p>
    `
  },
  7: {
    title: "Jawab Kegelisahan Wali Murid, MIN 1 Ciamis Sosialisasikan Transisi Digital Bersama Psikolog Madrasah",
    date: "20 Agustus 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-7.jpg",
    content: `
      <p>Dalam rangka menyelaraskan pemanfaatan teknologi di sekolah dan di rumah, MIN 1 Ciamis mengadakan sosialisasi transisi digital bersama psikolog anak dan tim IT madrasah.</p>
      <p>Sesi interaktif ini memberikan wawasan kepada para orang tua mengenai batasan screen time yang sehat, keamanan berinternet, dan pendampingan pembelajaran daring yang efektif.</p>
    `
  },
  8: {
    title: "MIN 1 Ciamis Ikuti Mini Drama Competition, Bawa Pesan Lingkungan dan Tradisi Topeng Malangan",
    date: "19 Agustus 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-8.jpg",
    content: `
      <p>Kreativitas seni peran siswa MIN 1 Ciamis kembali diuji dalam ajang festival drama kebudayaan. Menampilkan koreografi tradisional dan dialog interaktif bertema konservasi air, para siswa berhasil menyampaikan pesan moral yang mendalam kepada hadirin.</p>
    `
  },
  9: {
    title: "Siswa MIN 1 Ciamis Antusias Sambut Karnaval Kebangsaan SMPN 4 Malang, Belajar Nilai Kemerdekaan",
    date: "16 Agustus 2026",
    category: "Kabar Madrasah",
    image: "assets/img/news-9.jpeg",
    content: `
      <p>Suasana semarak kemerdekaan terasa di sepanjang Jalan Bandung Malang ketika siswa-siswi MIN 1 Ciamis turut menyambut dan memeriahkan kirab karnaval kebangsaan dengan atribut busana adat nusantara yang beraneka warna.</p>
    `
  }
};

const modalDataGeneral = {
  digital: {
    title: "Madrasah Digital Moderat MIN 1 Ciamis",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/madrasah-digital.png" alt="Madrasah Digital MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>Diresmikan oleh Menteri Agama pada 22 Januari 2023 sebagai Madrasah Digital Moderat.</strong></p>
        <p style="margin-top: 10px;">Penerapan program digitalisasi madrasah ini pada empat aspek pelayanan pendidikan: Manajemen Kurikulum, Ketatausahaan (E-Office), Layanan Kesiswaan (RDM & Presensi Digital), serta Kehumasan & Sarpras IT Terpadu.</p>
        <ul style="margin: 12px 0 16px 20px; line-height: 1.6; font-size: 0.9rem;">
          <li>E-Learning Madrasah & Ujian CBT Online</li>
          <li>Smart Classroom & Smart Board Interaktif</li>
          <li>Presensi Digital Siswa & Rapor Digital Madrasah (RDM)</li>
          <li>Administrasi Persuratan Paperless & E-Office</li>
        </ul>
        <div style="margin-top: 18px;">
          <a href="madrasah-digital.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Buka Halaman Madrasah Digital Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  adiwiyata: {
    title: "Sekolah Adiwiyata MIN 1 Ciamis",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/adiwiyata.png" alt="Sekolah Adiwiyata MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>Sesuai visinya, MIN 1 Ciamis meraih predikat Madrasah Adiwiyata tingkat provinsi dan kini berproses menuju tingkat nasional.</strong></p>
        <p style="margin-top: 10px;">Penghargaan ini menjadi bukti komitmen madrasah dalam mengintegrasikan pendidikan lingkungan ke dalam kurikulum serta melibatkan seluruh warga dalam menjaga kebersihan dan kelestarian lingkungan.</p>
        <ul style="margin: 12px 0 16px 20px; line-height: 1.6; font-size: 0.9rem;">
          <li>Integrasi Kurikulum Berwawasan Lingkungan</li>
          <li>Pengelolaan Sampah Mandiri (3R & Bank Sampah)</li>
          <li>Green House, Taman TOGA, dan Hidroponik</li>
          <li>Konservasi Air, Sumur Resapan & Lubang Biopori</li>
          <li>Gerakan Pungut Sampah (GPS) & Jumat Bersih</li>
        </ul>
        <div style="margin-top: 18px;">
          <a href="sekolah-adiwiyata.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Buka Halaman Sekolah Adiwiyata Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  ramahanak: {
    title: "Sekolah Ramah Anak (SRA) MIN 1 Ciamis",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/ramah-anak.png" alt="Sekolah Ramah Anak MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>MIN 1 Ciamis berkomitmen menjadi tempat yang ideal bagi anak-anak/peserta didik dalam mengembangkan potensi diri.</strong></p>
        <p style="margin-top: 10px;">Program SRA menjamin suasana belajar yang aman, nyaman, bebas perundungan (zero bullying), didukung kemitraan parenting bersama orang tua, dan pembinaan dokter kecil madrasah.</p>
        <ul style="margin: 12px 0 16px 20px; line-height: 1.6; font-size: 0.9rem;">
          <li>Lingkungan Aman & Bebas Perundungan (Zero Bullying)</li>
          <li>Parenting Day Bersama Pakar Pendidikan Anak</li>
          <li>Pelatihan Dokter Kecil & UKS Sehat</li>
          <li>Penanaman 9 Nilai Moderasi Beragama Sejak Dini</li>
          <li>Ajang Kreativitas Anak (Kidfest)</li>
        </ul>
        <div style="margin-top: 18px;">
          <a href="sekolah-ramah-anak.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Buka Halaman Sekolah Ramah Anak Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  literasi: {
    title: "Madrasah Literasi - Sekolah Aktif Literasi MIN 1 Ciamis",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/madrasah-literasi.png" alt="Madrasah Literasi MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>MIN 1 Ciamis menaruh perhatian khusus akan program literasi</strong>, dibuktikan tiap tahun semakin banyak karya yang dihasilkan oleh peserta didik dan guru.</p>
        <p style="margin-top: 10px;">Karya-karya tersebut mencakup tulisan, buku, jurnal dan berbagai bentuk kreativitas literasi lainnya yang menunjukkan tingginya minat dan semangat dalam membaca serta menulis di lingkungan madrasah.</p>
        <ul style="margin: 12px 0 16px 20px; line-height: 1.6; font-size: 0.9rem;">
          <li>15 Menit Membaca Buku Setiap Pagi</li>
          <li>Gerakan Sekolah Menulis (Penerbitan Buku Ber-ISBN)</li>
          <li>Journalist Boot Camp & Reporter Cilik</li>
          <li>Penerbitan Majalah Gemintang Ber-ISSN</li>
          <li>Apresiasi Duta Literasi & Pemustaka Teraktif</li>
        </ul>
        <div style="margin-top: 18px;">
          <a href="madrasah-literasi.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Buka Halaman Madrasah Literasi Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  ekstrakurikuler: {
    title: "Ekstrakurikuler MIN 1 Ciamis (17 Cabang Minat Bakat)",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/eskul-1.png" alt="Ekstrakurikuler MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>Pengembangan Minat Bakat MIN 1 Ciamis Tahun Pelajaran 2025-2026</strong> meliputi 17 cabang pilihan:</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin: 12px 0 16px; font-size: 0.9rem;">
          <div>1. 🏀 Basket</div>
          <div>2. ⚽ Futsal</div>
          <div>3. 🏊 Renang</div>
          <div>4. 🏹 Panahan</div>
          <div>5. 🏸 Bulutangkis</div>
          <div>6. 💃 Tari</div>
          <div>7. ♟️ Catur</div>
          <div>8. 🤖 Robotik</div>
          <div>9. 🎤 Menyanyi</div>
          <div>10. 🎨 Mewarna</div>
          <div>11. 🎵 Karawitan</div>
          <div>12. 🎋 Angklung</div>
          <div>13. 🚑 PMR</div>
          <div>14. 📖 MTQ</div>
          <div>15. ✍️ Cerpen & Komik Digital</div>
          <div>16. 🎙️ Presenter</div>
          <div>17. 📹 Konten Kreator</div>
        </div>
        <div style="margin-top: 18px;">
          <a href="ekstrakurikuler.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Buka Halaman Ekstrakurikuler Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  pakubumi: {
    title: "Program PAKUBUMI - MIN 1 Ciamis",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/pakubumi.png" alt="Program PAKUBUMI MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>Program Pengembangan Akhlaqul Karimah Ubudiyah dan Budaya Islami (PAKUBUMI)</strong> di MIN 1 Ciamis bertujuan membentuk karakter Islami pada peserta didik.</p>
        <p style="margin-top: 10px;">Program ini menanamkan nilai-nilai moral yang kuat, akhlak mulia, serta penguatan budaya Islami dalam kehidupan sehari-hari peserta didik. Melalui PAKUBUMI, MIN 1 Ciamis berkomitmen mencetak generasi yang tak hanya berprestasi, tetapi juga berakhlak baik, sehingga menjadi kebanggaan bagi orang tua dan masyarakat.</p>
        <div style="margin-top: 20px;">
          <a href="pakubumi.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Buka Halaman PAKUBUMI Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  kurikulum: {
    title: "Kurikulum MIN 1 Ciamis - A Joy of Discovery",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/kurikulum.png" alt="Infografis Kurikulum MIN 1 Ciamis" style="width: 100%; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--border-color);">
        <p><strong>MIN 1 Ciamis</strong> mengembangkan kurikulum berkarakter dengan melibatkan pihak terkait berpedoman pada panduan penyusunan kurikulum yang disusun oleh <strong>BSNP</strong>.</p>
        <ul style="margin: 12px 0 16px 20px; line-height: 1.6;">
          <li>Kurikulum & pembelajaran diupayakan mampu menumbuhkan dan mengembangkan daya kreasi, inovasi, nalar, dan eksperimentasi untuk menemukan kemungkinan-kemungkinan baru, <em>a joy of discovery</em>.</li>
          <li>Menekankan pada pembelajaran aktif, inovatif, kreatif, efektif, menyenangkan, dan islami (PAIKEMI).</li>
          <li>Memanfaatkan lingkungan sebagai sumber belajar.</li>
        </ul>
        <h4 style="color: var(--primary-color); margin: 16px 0 8px;">Standar Proses Pembelajaran (10 Poin Inti):</h4>
        <ol style="margin: 0 0 16px 20px; line-height: 1.6; font-size: 0.9rem;">
          <li>Lingkungan Madrasah sebagai sumber belajar</li>
          <li>Mengawali Kegiatan dengan Senam dan Mengaji</li>
          <li>Penataan Kelas yang Inspiratif</li>
          <li>Literasi Teknologi Dalam Pembelajaran</li>
          <li>Pembelajaran Tematik Ramah Anak & Ramah Lingkungan</li>
          <li>Pembelajaran HOTS (Higher Order Thinking Skills)</li>
          <li>Memberikan pengalaman belajar bermakna</li>
          <li>Cinta Budaya sebagai identitas Bangsa</li>
          <li>Menghubungkan siswa dengan realitas</li>
          <li>Belajar dari gedung kompleks Parlemen (DPD, DPR, MPR RI)</li>
        </ol>
        <div style="margin-top: 16px;">
          <a href="kurikulum.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none;">
            <span>Lihat Halaman Kurikulum Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `
  },

  profil: {
    title: "Profil MIN 1 Ciamis - Madrasah Maju, Bermutu, Mendunia",
    content: `
      <div style="margin-bottom: 20px;">
        <img src="assets/img/profil-canva.png" alt="Profil MIN 1 Ciamis" style="width: 100%; max-height: 280px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
        <p><strong>MIN 1 Ciamis</strong> adalah madrasah ibtidaiyah negeri yang terletak di Jl. Bandung 7C, Kota Malang. Sebagai madrasah digital moderat, kami mengusung motto <em>“Tiada Hari Tanpa Prestasi”</em> dan berkomitmen membina generasi yang bertakwa, berakhlak mulia, serta unggul dalam prestasi.</p>
        <p>Dengan pendekatan pendidikan holistik berbasis nilai-nilai Islam, MIN 1 Ciamis menciptakan lingkungan belajar yang ramah dan menyenangkan.</p>
        <div style="margin: 24px 0 10px; display: flex; gap: 10px; flex-wrap: wrap;">
          <a href="profil.html" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: 600;">
            <span>Buka Halaman Profil Lengkap</span>
            <i class="fas fa-arrow-right"></i>
          </a>
          <a href="profil.html#sejarah" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border: 1px solid var(--primary-color); color: var(--primary-color); border-radius: 6px; font-weight: 600;">
            <i class="fas fa-history"></i> Linimasa Sejarah
          </a>
          <a href="profil.html#kepala-madrasah" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border: 1px solid var(--primary-color); color: var(--primary-color); border-radius: 6px; font-weight: 600;">
            <i class="fas fa-user-tie"></i> Kepala Madrasah
          </a>
        </div>
      </div>
    `
  },
  program: {
    title: "Program Unggulan MIN 1 Ciamis",
    content: `
      <div style="display: grid; gap: 16px;">
        <div style="background: var(--bg-light); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color); margin-bottom: 6px;">1. PAKUBUMI (Pusat Pengembangan Mutu Siswa)</h4>
          <p>Wadah pembinaan intensif untuk olimpiade sains (KSM/OSN), matematika, riset ilmiah anak, dan robotik.</p>
        </div>
        <div style="background: var(--bg-light); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color); margin-bottom: 6px;">2. Madrasah Literasi & Majalah Gemintang</h4>
          <p>Gerakan membaca buku 15 menit setiap pagi, penerbitan majalah madrasah ber-ISSN, dan penulisan buku karya siswa.</p>
        </div>
        <div style="background: var(--bg-light); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color); margin-bottom: 6px;">3. Sekolah Ramah Anak & Adiwiyata Mandiri</h4>
          <p>Pembelajaran tanpa kekerasan, bank sampah mandiri, taman hidroponik, dan toilet ramah lingkungan.</p>
        </div>
        <div style="background: var(--bg-light); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color); margin-bottom: 6px;">4. Ekstrakurikuler Multitalenta</h4>
          <p>Meliputi Pramuka Garuda, Tahfidz Qur'an, Robotika, Bina Vokal, Teater, Banjari, Marching Band, Silat, Panahan, dan Bahasa Asing.</p>
        </div>
      </div>
    `
  },
  layanan: {
    title: "Layanan Digital MIN 1 Ciamis",
    content: `
      <div style="display: grid; gap: 16px;">
        <div style="border: 1px solid var(--border-color); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color);"><i class="fas fa-desktop"></i> Portal Administrasi Madrasah</h4>
          <p>Layanan terpadu satu pintu untuk permohonan surat keterangan aktif belajar, legalisir ijazah, dan administrasi kesiswaan online.</p>
        </div>
        <div style="border: 1px solid var(--border-color); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color);"><i class="fas fa-graduation-cap"></i> E-Learning Madrasah</h4>
          <p>Platform pembelajaran digital terintegrasi untuk kelas daring, ujian CBT, pengumpulan tugas, dan materi interaktif siswa.</p>
        </div>
        <div style="border: 1px solid var(--border-color); padding: 16px; border-radius: 8px;">
          <h4 style="color: var(--primary-color);"><i class="fas fa-book-reader"></i> Perpustakaan Digital (E-Library)</h4>
          <p>Koleksi ribuan buku bacaan anak, ensiklopedia digital, jurnal pendidikan, dan katalog buku online MIN 1 Ciamis.</p>
        </div>
      </div>
    `
  }
};

function initModals() {
  const modalOverlay = document.getElementById('general-modal');
  const modalCloseBtn = document.getElementById('modal-close-btn');
  const modalTitle = document.getElementById('modal-title');
  const modalContent = document.getElementById('modal-content-area');

  function openGeneralModal(title, htmlContent) {
    if (!modalOverlay) return;
    modalTitle.innerHTML = title;
    modalContent.innerHTML = htmlContent;
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    if (!modalOverlay) return;
    modalOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
  if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeModal();
    });
  }

  // News Card Click Listener
  document.querySelectorAll('.news-card').forEach(card => {
    card.addEventListener('click', () => {
      const id = card.getAttribute('data-news-id');
      let data = newsDatabase[id];
      
      const stored = localStorage.getItem('min1_custom_news');
      if (stored) {
        try {
          const list = JSON.parse(stored);
          const found = list.find(item => item.id == id);
          if (found) data = found;
        } catch(e) {}
      }

      if (data) {
        const articleHtml = `
          <div style="margin-bottom: 20px;">
            <img src="${data.image}" alt="${data.title}" style="width: 100%; max-height: 380px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
            <div style="display: flex; gap: 14px; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
              <span><i class="far fa-calendar-alt"></i> ${data.date}</span>
              <span><i class="fas fa-tag"></i> ${data.category}</span>
              ${data.author ? `<span><i class="fas fa-user-edit"></i> ${data.author}</span>` : ''}
            </div>
            <div style="font-size: 1.05rem; line-height: 1.8; color: var(--text-body);">
              ${data.content.startsWith('<p>') ? data.content : `<p>${data.content}</p>`}
            </div>
          </div>
        `;
        openGeneralModal(data.title, articleHtml);
      }
    });
  });

  // Profil & Program Trigger Buttons
  document.querySelectorAll('[data-modal-trigger]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const type = btn.getAttribute('data-modal-trigger');
      const data = modalDataGeneral[type];
      if (data) {
        openGeneralModal(data.title, data.content);
      }
    });
  });
}



// 21. Interactive Tim Manajemen Cards (Glass Mode & 3D Motion)
function initTimManajemenCards() {
  if (window.innerWidth < 992) return;
  const cards = document.querySelectorAll('.tim-manajemen-card');
  cards.forEach(card => {
    const badge = card.querySelector('.tim-card-glass-badge');
    const img = card.querySelector('.tim-card-img');

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      const tiltX = -y * 12;
      const tiltY = x * 12;

      card.style.transform = `perspective(850px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) translateY(-8px)`;

      if (badge) {
        badge.style.transform = `translateY(-6px) translate3d(${(x * 8).toFixed(1)}px, ${(y * 5).toFixed(1)}px, 16px)`;
      }
      if (img) {
        img.style.transform = `scale(1.06) translate3d(${(-x * 8).toFixed(1)}px, ${(-y * 8).toFixed(1)}px, 0)`;
      }
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
      card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease';
      if (badge) {
        badge.style.transform = '';
      }
      if (img) {
        img.style.transform = '';
      }
    });

    card.addEventListener('mouseenter', () => {
      card.style.transition = 'transform 0.1s ease-out, box-shadow 0.3s ease, border-color 0.3s ease';
    });
  });
}



/* --- 5. VIDEO LIGHTBOX --- */
function initVideoLightbox() {
  const videoWrapper = document.querySelector('.video-wrapper');
  const videoModal = document.getElementById('video-modal');
  const videoCloseBtn = document.getElementById('video-close-btn');
  const videoFrame = document.getElementById('youtube-video-frame');

  if (!videoWrapper || !videoModal || !videoFrame) return;

  videoWrapper.addEventListener('click', () => {
    videoFrame.src = "https://www.youtube.com/embed/ZHVGFNjrX3Q?autoplay=1";
    videoModal.classList.add('active');
    document.body.style.overflow = 'hidden';
  });

  function closeVideoModal() {
    videoModal.classList.remove('active');
    videoFrame.src = "";
    document.body.style.overflow = '';
  }

  if (videoCloseBtn) videoCloseBtn.addEventListener('click', closeVideoModal);
  videoModal.addEventListener('click', (e) => {
    if (e.target === videoModal) closeVideoModal();
  });
}

/* --- 6. SEARCH MODAL & INSTANT FILTER (SEMANTIC AI SEARCH) --- */
function initSearch() {
  const searchModal = document.getElementById('search-modal');
  const searchToggleBtn = document.querySelector('.search-toggle-btn');
  const searchCloseBtn = document.getElementById('search-close-btn');
  const searchInput = document.getElementById('search-input-field');
  const searchResults = document.getElementById('search-results-list');

  const fallbackItems = [
    { title: "Profil Resmi MIN 1 Ciamis", snippet: "Profil madrasah, visi misi, sejarah, dan alamat di Panjalu Ciamis", url: "#profil", category: "Profil Madrasah", icon: "fas fa-school" },
    { title: "Program Unggulan Tahfidz Qur'an", snippet: "Program target hafalan juz 30 dan tartil metode Al-Qur'an", url: "#program", category: "Program Unggulan", icon: "fas fa-quran" },
    { title: "PPDB Online 2026/2027", snippet: "Informasi dan simulasi pendaftaran peserta didik baru MIN 1 Ciamis", url: "#ppdb", category: "Layanan Siswa", icon: "fas fa-user-plus" },
    { title: "Prestasi Santri MIN 1 Ciamis", snippet: "Rekap capaian prestasi lomba kabupaten, provinsi, dan nasional", url: "#prestasi", category: "Prestasi", icon: "fas fa-trophy" },
    { title: "Layanan Digital & RDM", snippet: "Portal e-learning, rapor digital madrasah, dan e-perpustakaan", url: "#layanan", category: "Layanan Digital", icon: "fas fa-laptop" },
    { title: "Kabar Madrasah & Berita", snippet: "Warta kegiatan, upacara hari santri, olimpiade sains KSM", url: "#berita", category: "Berita", icon: "far fa-newspaper" },
    { title: "Kontak & Lokasi Panjalu", snippet: "Alamat Jl. Maparah No. 65 Panjalu Ciamis dan nomor WhatsApp", url: "#kontak", category: "Kontak", icon: "fas fa-map-marker-alt" }
  ];

  if (!searchModal || !searchToggleBtn) return;

  searchToggleBtn.addEventListener('click', () => {
    searchModal.classList.add('active');
    if (searchInput) {
      searchInput.value = '';
      setTimeout(() => searchInput.focus(), 100);
    }
    fetchInitialSuggestions();
    document.body.style.overflow = 'hidden';
  });

  function closeSearchModal() {
    searchModal.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (searchCloseBtn) searchCloseBtn.addEventListener('click', closeSearchModal);
  searchModal.addEventListener('click', (e) => {
    if (e.target === searchModal) closeSearchModal();
  });

  function fetchInitialSuggestions() {
    fetch('/api/semantic-search')
      .then(res => res.json())
      .then(data => {
        if (data.results && data.results.length) {
          renderSearchResults(data.results);
        } else {
          renderSearchResults(fallbackItems);
        }
      })
      .catch(() => renderSearchResults(fallbackItems));
  }

  function renderSearchResults(items, isSearching = false) {
    if (!searchResults) return;
    if (isSearching) {
      searchResults.innerHTML = '<div style="padding: 24px; text-align: center; color: var(--text-muted);"><i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> Menganalisis pencarian secara semantik dengan AI...</div>';
      return;
    }
    if (!items.length) {
      searchResults.innerHTML = '<div style="padding: 24px; text-align: center; color: var(--text-muted);"><i class="fas fa-search" style="font-size: 1.6rem; color: #cbd5e1; margin-bottom: 8px; display: block;"></i> Tidak ada konten yang sesuai dengan pencarian Anda.</div>';
      return;
    }
    searchResults.innerHTML = items.map(item => {
      const icon = item.icon || 'fas fa-chevron-right';
      const simPill = (item.source === 'semantic' && item.similarity)
        ? `<span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);">✨ AI ${item.similarity}% Cocok</span>`
        : (item.category ? `<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; background: rgba(13, 106, 86, 0.1); color: #0D6A56;">${item.category}</span>` : '');

      return `
        <div class="search-result-item" data-url="${item.url || ''}" style="cursor: pointer; padding: 12px 14px; border-radius: 10px; margin-bottom: 8px; transition: background 0.15s ease;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <i class="${icon}" style="color: #0D6A56; font-size: 0.95rem;"></i>
              <strong class="item-title" style="font-size: 0.95rem;">${item.title}</strong>
            </div>
            ${simPill}
          </div>
          <div class="item-snippet" style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; padding-left: 22px;">${item.snippet}</div>
        </div>
      `;
    }).join('');

    searchResults.querySelectorAll('.search-result-item').forEach(el => {
      el.addEventListener('click', () => {
        closeSearchModal();
        const url = el.getAttribute('data-url');
        if (url) {
          window.location.href = url;
        }
      });
    });
  }

  let debounceTimer = null;
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const query = e.target.value.trim();
      clearTimeout(debounceTimer);

      if (query.length < 2) {
        fetchInitialSuggestions();
        return;
      }

      renderSearchResults([], true);

      debounceTimer = setTimeout(() => {
        fetch('/api/semantic-search?q=' + encodeURIComponent(query))
          .then(res => res.json())
          .then(data => {
            if (data.results) {
              renderSearchResults(data.results);
            } else {
              renderSearchResults([]);
            }
          })
          .catch(() => {
            const filtered = fallbackItems.filter(item =>
              item.title.toLowerCase().includes(query.toLowerCase()) ||
              item.snippet.toLowerCase().includes(query.toLowerCase())
            );
            renderSearchResults(filtered);
          });
      }, 300);
    });
  }
}

/* --- 7. MOBILE DRAWER NAVIGATION --- */
function initMobileDrawer() {
  const mobileToggle = document.querySelector('.mobile-toggle-btn');
  const mobileDrawer = document.getElementById('mobile-nav-drawer') || document.getElementById('mobile-drawer');
  const drawerCloseBtn = document.getElementById('mobile-drawer-close') || document.getElementById('drawer-close-btn');
  const drawerOverlay = document.getElementById('mobile-drawer-overlay');

  if (!mobileDrawer) return;

  function openDrawer() {
    mobileDrawer.classList.add('active');
    if (drawerOverlay) drawerOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    mobileDrawer.classList.remove('active');
    if (drawerOverlay) drawerOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  // Make functions globally accessible
  window.openMobileDrawer = openDrawer;
  window.closeMobileDrawer = closeDrawer;

  if (mobileToggle) mobileToggle.addEventListener('click', openDrawer);
  if (drawerCloseBtn) drawerCloseBtn.addEventListener('click', closeDrawer);
  if (drawerOverlay) drawerOverlay.addEventListener('click', closeDrawer);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileDrawer.classList.contains('active')) {
      closeDrawer();
    }
  });

  // Close drawer when clicking leaf links
  document.querySelectorAll('.mobile-drawer-body a:not(.has-sub):not(.has-nested-sub)').forEach(link => {
    link.addEventListener('click', () => {
      closeDrawer();
    });
  });
}



// 21. Interactive Tim Manajemen Cards (Glass Mode & 3D Motion)
function initTimManajemenCards() {
  if (window.innerWidth < 992) return;
  const cards = document.querySelectorAll('.tim-manajemen-card');
  cards.forEach(card => {
    const badge = card.querySelector('.tim-card-glass-badge');
    const img = card.querySelector('.tim-card-img');

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      const tiltX = -y * 12;
      const tiltY = x * 12;

      card.style.transform = `perspective(850px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) translateY(-8px)`;

      if (badge) {
        badge.style.transform = `translateY(-6px) translate3d(${(x * 8).toFixed(1)}px, ${(y * 5).toFixed(1)}px, 16px)`;
      }
      if (img) {
        img.style.transform = `scale(1.06) translate3d(${(-x * 8).toFixed(1)}px, ${(-y * 8).toFixed(1)}px, 0)`;
      }
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
      card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease';
      if (badge) {
        badge.style.transform = '';
      }
      if (img) {
        img.style.transform = '';
      }
    });

    card.addEventListener('mouseenter', () => {
      card.style.transition = 'transform 0.1s ease-out, box-shadow 0.3s ease, border-color 0.3s ease';
    });
  });
}



/* --- 8. TIMELINE SCROLL ANIMATIONS --- */
function initTimelineAnimation() {
  const blocks = document.querySelectorAll('.timeline-block');
  if (!blocks.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
      }
    });
  }, { threshold: 0.2 });

  blocks.forEach(block => observer.observe(block));
}

/* --- 9. DESKTOP DROPDOWN ACCESSIBILITY & CLICK SUPPORT --- */
function initDesktopDropdowns() {
  const menuItems = document.querySelectorAll('.main-navigation .menu-item');
  menuItems.forEach(item => {
    const subMenu = item.querySelector('.sub-menu');
    const link = item.querySelector('.menu-link');
    if (!subMenu || !link) return;

    let closeTimer = null;

    // Desktop hover dengan graceful timeout (320ms) agar menu dropdown tidak keburu hilang
    item.addEventListener('mouseenter', () => {
      if (closeTimer) {
        clearTimeout(closeTimer);
        closeTimer = null;
      }
      menuItems.forEach(other => {
        if (other !== item) other.classList.remove('dropdown-open');
      });
      item.classList.add('dropdown-open');
    });

    item.addEventListener('mouseleave', () => {
      closeTimer = setTimeout(() => {
        item.classList.remove('dropdown-open');
      }, 320);
    });

    // Pertahankan menu tetap terbuka saat kursor masuk ke dalam area subMenu
    subMenu.addEventListener('mouseenter', () => {
      if (closeTimer) {
        clearTimeout(closeTimer);
        closeTimer = null;
      }
      item.classList.add('dropdown-open');
    });

    subMenu.addEventListener('mouseleave', () => {
      closeTimer = setTimeout(() => {
        item.classList.remove('dropdown-open');
      }, 320);
    });

    // Touch and click on arrow / menu link
    link.addEventListener('click', (e) => {
      const arrow = link.querySelector('.arrow');
      if (e.target === arrow || e.target.closest('.arrow') || (window.innerWidth < 992)) {
        e.preventDefault();
        e.stopPropagation();
        item.classList.toggle('dropdown-open');
      }
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.main-navigation')) {
      document.querySelectorAll('.menu-item.dropdown-open').forEach(el => el.classList.remove('dropdown-open'));
    }
  });
}




/* --- DYNAMIC CUSTOM SITE IMAGES (ADMIN MANAGED ACROSS ALL PAGES) --- */
function applySiteImagesData(savedImages) {
  if (!savedImages || Object.keys(savedImages).length === 0) return;

  // 1. Logo
  if (savedImages.logo) {
    document.querySelectorAll('.site-logo, .footer-logo, .dashboard-logo').forEach(img => {
      img.src = savedImages.logo;
    });
  }

  // 2. Banner Hero 1 & 2
  // Slide 1 is permanently dedicated to Lottie Interactive Animation (loti hero min 1.json) without text overlay
  const lottieContainer = document.getElementById('hero-lottie-container');
  if (lottieContainer) {
    lottieContainer.style.setProperty('display', 'flex', 'important');
  }
  const slide1 = document.getElementById('hero-slide-1') || document.querySelector('.hero-slide:nth-child(1)');
  if (slide1) {
    slide1.style.setProperty('background-color', '#F8F9FA', 'important');
    slide1.classList.add('hero-slide-lottie');
    const slide1Bg = slide1.querySelector('.hero-slide-bg');
    if (slide1Bg) slide1Bg.style.setProperty('display', 'none', 'important');
    const slide1Overlay = slide1.querySelector('.hero-slide-overlay');
    if (slide1Overlay) slide1Overlay.style.setProperty('display', 'none', 'important');
    const slide1Content = slide1.querySelector('.hero-slide-content');
    if (slide1Content) slide1Content.style.setProperty('display', 'none', 'important');
  }

  const slide2 = document.querySelector('.hero-slide:nth-child(2)');
  if (slide2) {
    const s2Content = slide2.querySelector('.hero-slide-content');
    if (s2Content) s2Content.style.setProperty('display', 'none', 'important');
    const s2Overlay = slide2.querySelector('.hero-slide-overlay');
    if (s2Overlay) s2Overlay.style.setProperty('display', 'none', 'important');
    if (savedImages.banner2) {
      const slide2Bg = slide2.querySelector('.hero-slide-bg');
      if (slide2Bg) slide2Bg.style.backgroundImage = 'url(' + savedImages.banner2 + ')';
    }
  }

  // 3. About / Sambutan Image
  if (savedImages.about_img) {
    const aboutImg = document.querySelector('.about-image-wrapper img');
    if (aboutImg) aboutImg.src = savedImages.about_img;
  }

  // 4. Video Thumbnail
  if (savedImages.video_thumb) {
    const videoThumb = document.querySelector('.video-thumb-img');
    if (videoThumb) videoThumb.src = savedImages.video_thumb;
  }

  // 5. Alumni Images
  if (savedImages.alumni1) {
    const a1 = document.querySelector('.testimonial-card:nth-child(1) .alumni-avatar');
    if (a1) a1.src = savedImages.alumni1;
  }
  if (savedImages.alumni2) {
    const a2 = document.querySelector('.testimonial-card:nth-child(2) .alumni-avatar');
    if (a2) a2.src = savedImages.alumni2;
  }
  if (savedImages.alumni3) {
    const a3 = document.querySelector('.testimonial-card:nth-child(3) .alumni-avatar');
    if (a3) a3.src = savedImages.alumni3;
  }

  // 6. Trofi Prestasi
  if (savedImages.trophy_left) {
    const p1 = document.querySelector('.total-prestasi-banner img:first-child');
    if (p1) p1.src = savedImages.trophy_left;
  }
  if (savedImages.trophy_right) {
    const p2 = document.querySelector('.total-prestasi-banner img:last-child');
    if (p2) p2.src = savedImages.trophy_right;
  }

  // 7. Profil Madrasah (if on profil.html)
  if (savedImages.kepala_madrasah) {
    const km = document.querySelector('.kepala-madrasah-img');
    if (km) km.src = savedImages.kepala_madrasah;
  }
  if (savedImages.gedung_madrasah) {
    const gm = document.querySelector('.gedung-madrasah-img');
    if (gm) gm.src = savedImages.gedung_madrasah;
  }
}

function initCustomSiteImages() {
  try {
    // 1. Immediately apply cached settings from localStorage for instant rendering
    const cachedImages = JSON.parse(localStorage.getItem('min1_site_images') || '{}');
    if (cachedImages.banner1) {
      delete cachedImages.banner1;
      try { localStorage.setItem('min1_site_images', JSON.stringify(cachedImages)); } catch (e) {}
    }
    applySiteImagesData(cachedImages);

    // 2. Fetch live settings from Server CMS API
    fetch('api/settings.php')
      .then(res => res.json())
      .then(data => {
        if (data.success && data.settings) {
          if (data.settings.banner1) {
            delete data.settings.banner1;
          }
          localStorage.setItem('min1_site_images', JSON.stringify(data.settings));
          applySiteImagesData(data.settings);
        }
      })
      .catch(() => {});
  } catch (e) {
    console.error('Error applying custom images:', e);
  }
}

// Real-time synchronization when images are changed in another tab (e.g., dashboard)
window.addEventListener('storage', (e) => {
  if (e.key === 'min1_site_images') {
    initCustomSiteImages();
  }
});

/* ==========================================================================
   INTERACTIVE UPGRADES (Pilar Explorer, Talent Quiz, Confetti, Tilt, Wisdom)
   ========================================================================== */

// Toast notification helper
function showInteractiveToast(message, icon = 'fas fa-check-circle') {
  const toast = document.getElementById('interactive-toast');
  const msgEl = document.getElementById('toast-message');
  if (!toast || !msgEl) return;

  msgEl.textContent = message;
  const iconEl = toast.querySelector('i');
  if (iconEl) iconEl.className = icon;
  toast.classList.add('show');

  clearTimeout(toast._timeout);
  toast._timeout = setTimeout(() => {
    toast.classList.remove('show');
  }, 3200);
}

// 1. Interactive 6 Pilar Keunggulan Explorer
const pilarData = {
  tahfidz: {
    badge: "Keunggulan Qur'ani",
    icon: "fas fa-quran",
    title: "Program Pakubumi & Tahfidz Al-Qur'an Bersanad",
    desc: "Menumbuhkan kecintaan membaca, menghafal, dan memahami Al-Qur'an secara tartil dengan bimbingan ustadz/ustadzah kompeten, diakhiri dengan wisuda tahfidz dan uji publik berkala.",
    statNumber: "100+",
    statLabel: "Siswa Hafidz Juz 30 & 29",
    statPill: "Program Unggulan MIN 1",
    link: "pakubumi",
    highlights: [
      "Bimbingan makharijul huruf & tajwid bersanad",
      "Uji publik hafalan di hadapan orang tua & asatidz",
      "Target kelulusan minimal hafal 2-3 Juz mutqin"
    ]
  },
  sains: {
    badge: "Inovasi STEM & Masa Depan",
    icon: "fas fa-robot",
    title: "Sains, Robotika & Matematika Nalaria Realistik",
    desc: "Membekali peserta didik dengan kecakapan berpikir kritis, problem solving, dan rekayasa teknologi mutakhir melalui klub robotik dan olimpiade sains berprestasi dunia.",
    statNumber: "75+",
    statLabel: "Medali Robotika & Sains Dunia",
    statPill: "Juara Internasional",
    link: "kurikulum",
    highlights: [
      "Pelatihan robotika analog, digital, & IoT dasar",
      "Klinik pembinaan olimpiade sains terstruktur",
      "Eksperimen laboratorium sains berkala dan aplikatif"
    ]
  },
  digital: {
    badge: "Smart Madrasah Ecosystem",
    icon: "fas fa-laptop-code",
    title: "Madrasah Digital Cerdas & Smart Classroom",
    desc: "Integrasi teknologi mutakhir dalam ekosistem pembelajaran modern: e-learning interaktif, materi digital, perpustakaan daring, dan rapor digital madrasah yang transparan.",
    statNumber: "100%",
    statLabel: "Kelas Berbasis Digital",
    statPill: "Inovasi Pembelajaran",
    link: "madrasah-digital",
    highlights: [
      "Akses materi dan kuis interaktif kapan saja",
      "Pengenalan literasi digital sehat & cyber safety",
      "Sistem administrasi dan presensi daring terpadu"
    ]
  },
  adiwiyata: {
    badge: "Ekologi & Pelestarian Alam",
    icon: "fas fa-leaf",
    title: "Sekolah Adiwiyata Mandiri Nasional",
    desc: "Menanamkan nilai kepedulian lingkungan hidup melalui pengelolaan sampah mandiri, biopori, green house, dan pembiasaan gaya hidup minim sampah (zero waste) sejak dini.",
    statNumber: "Mandiri",
    statLabel: "Predikat Adiwiyata Tertinggi",
    statPill: "Kemenpora & KLHK RI",
    link: "sekolah-adiwiyata",
    highlights: [
      "Kader lingkungan siswa aktif di setiap jenjang kelas",
      "Pengolahan kompos dan kebun edukasi hidroponik",
      "Green school berwawasan kelestarian global"
    ]
  },
  literasi: {
    badge: "Kultur Membaca & Menulis",
    icon: "fas fa-book-reader",
    title: "Gerakan Literasi Madrasah (GELIMAR)",
    desc: "Menghidupkan budaya membaca dan menulis produktif siswa. Diterbitkannya Majalah Gemintang secara berkala serta karya antologi puisi dan cerpen karya siswa-siswi.",
    statNumber: "20.000+",
    statLabel: "Koleksi Buku & E-Library",
    statPill: "Literasi Berkelanjutan",
    link: "madrasah-literasi",
    highlights: [
      "Pojok baca tematik di setiap ruang kelas",
      "Penerbitan majalah madrasah Gemintang berkala",
      "Workshop kepenulisan kreatif dan jurnalis cilik"
    ]
  },
  ramahanak: {
    badge: "Lingkungan Belajar Harmonis",
    icon: "fas fa-child",
    title: "Sekolah Ramah Anak (SRA) Terstandar Nasional",
    desc: "Mewujudkan madrasah yang aman, nyaman, inklusif, dan bebas perundungan. Memastikan setiap anak tumbuh dengan rasa percaya diri, bahagia, dan penuh perlindungan.",
    statNumber: "Terstandar",
    statLabel: "Kementerian PPPA RI",
    statPill: "Anti-Perundungan & Inklusif",
    link: "sekolah-ramah-anak",
    highlights: [
      "Komitmen penuh anti-bullying dan ramah anak",
      "Konseling ramah peserta didik dan pendampingan bakat",
      "Fasilitas sarana prasarana aman, bersih, dan inklusif"
    ]
  }
};

function renderPilarShowcase(pilarKey) {
  const container = document.getElementById('pilar-showcase-container');
  if (!container) return;

  const data = pilarData[pilarKey] || pilarData.tahfidz;
  container.style.opacity = '0';
  container.style.transform = 'translateY(8px)';

  setTimeout(() => {
    container.innerHTML = `
      <div class="pilar-grid">
        <div class="pilar-info">
          <div class="pilar-badge-tag"><i class="${data.icon}"></i> ${data.badge}</div>
          <h3 class="pilar-title">${data.title}</h3>
          <p class="pilar-desc">${data.desc}</p>
          <div class="pilar-highlights">
            ${data.highlights.map(hl => `
              <div class="pilar-hl-item">
                <i class="fas fa-check-circle"></i>
                <span>${hl}</span>
              </div>
            `).join('')}
          </div>
          <a href="${data.link}" class="pilar-cta-btn">
            <span>Pelajari Program ${data.badge}</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        <div class="pilar-visual-wrap">
          <div class="pilar-card-visual tilt-card">
            <div class="pilar-visual-icon"><i class="${data.icon}"></i></div>
            <div class="pilar-visual-stat">${data.statNumber}</div>
            <div class="pilar-visual-label">${data.statLabel}</div>
            <div class="pilar-visual-pill"><i class="fas fa-medal" style="margin-right: 6px; color: #FFE15D;"></i> ${data.statPill}</div>
          </div>
        </div>
      </div>
    `;
    container.style.opacity = '1';
    container.style.transform = 'translateY(0)';
    initInteractiveTilt();
  }, 180);
}

function switchPilarTab(pilarKey) {
  document.querySelectorAll('.pilar-tab-btn').forEach(btn => {
    btn.classList.toggle('active', btn.getAttribute('data-pilar') === pilarKey);
  });
  renderPilarShowcase(pilarKey);
}

function initInteractivePilarExplorer() {
  if (document.getElementById('pilar-showcase-container')) {
    renderPilarShowcase('tahfidz');
  }
}

// 2. Interactive Talent Quiz / Matcher
let currentMinat = 'sains';
let currentGaya = 'praktik';

const talentMatrix = {
  'sains-praktik': {
    match: '99% Sangat Cocok',
    title: 'Klub Robotika & Eksperimen Sains STEM',
    desc: 'Ananda memiliki bakat luar biasa dalam eksplorasi logika, sains terapan, dan ketekunan merakit teknologi. Sangat direkomendasikan mengikuti Ekstrakurikuler Robotika dan Tim Olimpiade Sains.',
    link: 'kurikulum'
  },
  'sains-kolaborasi': {
    match: '96% Sangat Cocok',
    title: 'Digital Project Maker & Math Nalaria',
    desc: 'Ananda senang memecahkan masalah dalam kelompok dan berinovasi. Sangat pas untuk proyek kolaboratif pembuatan aplikasi sains dan kompetisi kelompok.',
    link: 'madrasah-digital'
  },
  'sains-mandiri': {
    match: '98% Sangat Cocok',
    title: 'Klinik Olimpiade Matematika & Sains Cilik',
    desc: 'Fokus dan daya analisis ananda sangat tajam. Program pembinaan intensif olimpiade dan riset sains cilik siap menghantarkan ananda ke medali internasional.',
    link: 'prestasi'
  },
  'quran-praktik': {
    match: '98% Sangat Cocok',
    title: 'Tahfidz Al-Qur\'an Pakubumi & Tilawah',
    desc: 'Kemampuan menyerap hafalan Al-Qur\'an ananda sangat baik. Program Tahfidz Intensif Pakubumi akan membimbing ananda mencapai target juz dengan tajwid tartil.',
    link: 'pakubumi'
  },
  'quran-kolaborasi': {
    match: '95% Sangat Cocok',
    title: 'Da\'i Cilik & Syiar Qur\'ani Madrasah',
    desc: 'Ananda berjiwa komunikatif dan menyukai nilai-nilai keislaman. Sangat cocok dalam pembinaan Da\'i Cilik dan Festival Anak Sholeh.',
    link: 'pakubumi'
  },
  'quran-mandiri': {
    match: '99% Sangat Cocok',
    title: 'Karantina Tahfidz Mutqin & Hifdzil Qur\'an',
    desc: 'Ketekunan dan kedisiplinan mandiri ananda adalah modal istimewa untuk menjadi Hafidz/Hafidzah berprestasi.',
    link: 'pakubumi'
  },
  'seni-praktik': {
    match: '97% Sangat Cocok',
    title: 'Seni Rupa Kaligrafi, Desain Grafis & Membatik',
    desc: 'Kreativitas tangan dan kepekaan visual ananda sangat tinggi. Sangat tepat diasah di Ekskul Kaligrafi Islam dan Desain Kreatif.',
    link: 'ekstrakurikuler'
  },
  'seni-kolaborasi': {
    match: '98% Sangat Cocok',
    title: 'Marching Band Bahana Swara & Teater Cilik',
    desc: 'Kekompakan, rasa musikalitas, dan ekspresi panggung ananda akan bersinar terang di Marching Band kebanggaan MIN 1 Malang!',
    link: 'ekstrakurikuler'
  },
  'seni-mandiri': {
    match: '95% Sangat Cocok',
    title: 'Seni Lukis, Kaligrafi Kontemporer & Ilustrasi',
    desc: 'Imajinasi dan kedalaman ekspresi ananda dapat menghasilkan karya seni dan kaligrafi juara tingkat nasional.',
    link: 'ekstrakurikuler'
  },
  'olahraga-praktik': {
    match: '98% Sangat Cocok',
    title: 'Pencak Silat Pagar Nusa, Karate & Renang',
    desc: 'Kebugaran fisik, reflek motorik, dan semangat sportivitas ananda sangat tinggi. Sangat direkomendasikan pada cabang bela diri dan atletik.',
    link: 'ekstrakurikuler'
  },
  'olahraga-kolaborasi': {
    match: '99% Sangat Cocok',
    title: 'Futsal Champions & Pramuka Garuda Inti',
    desc: 'Jiwa kepemimpinan, kekompakan, dan ketangguhan fisik ananda sangat ideal untuk tim futsal madrasah dan regu utama Pramuka.',
    link: 'ekstrakurikuler'
  },
  'olahraga-mandiri': {
    match: '96% Sangat Cocok',
    title: 'Catur Strategis, Panahan & Atletik Cilik',
    desc: 'Ketepatan konsentrasi, ketenangan, dan akurasi tinggi ananda cocok dalam olahraga panahan islami dan catur berprestasi.',
    link: 'ekstrakurikuler'
  },
  'bahasa-praktik': {
    match: '97% Sangat Cocok',
    title: 'English Club, Arabic Fun & Jurnalis Cilik',
    desc: 'Kecakapan berbahasa asing dan keberanian komunikasi ananda sangat potensial diasah untuk pidato dan broadcasting madrasah.',
    link: 'madrasah-literasi'
  },
  'bahasa-kolaborasi': {
    match: '98% Sangat Cocok',
    title: 'English Mini Drama & Storytelling Antarbangsa',
    desc: 'Kemampuan teater dialog berbahasa Inggris ananda sangat berpotensi mengulang prestasi juara drama bahasa Inggris Expo Jatim!',
    link: 'berita'
  },
  'bahasa-mandiri': {
    match: '96% Sangat Cocok',
    title: 'Kepenulisan Kreatif & Redaksi Majalah Gemintang',
    desc: 'Daya nalar kata dan kecintaan membaca ananda akan menghasilkan karya tulis, artikel majalah, dan puisi yang menginspirasi.',
    link: 'madrasah-literasi'
  }
};

function renderQuizResult() {
  const container = document.getElementById('quiz-result-container');
  if (!container) return;

  const key = `${currentMinat}-${currentGaya}`;
  const res = talentMatrix[key] || talentMatrix['sains-praktik'];

  container.innerHTML = `
    <div class="quiz-result-info">
      <div class="quiz-match-tag"><i class="fas fa-check-double"></i> Rekomendasi Terpilih: ${res.match}</div>
      <h3 class="quiz-result-title">${res.title}</h3>
      <p class="quiz-result-desc">${res.desc}</p>
    </div>
    <div class="quiz-result-actions">
      <a href="${res.link}" class="quiz-cta-btn">
        <span>Pelajari Program</span>
        <i class="fas fa-arrow-right"></i>
      </a>
      <button type="button" class="quiz-reset-btn" onclick="resetTalentQuiz()">
        <i class="fas fa-redo-alt" style="margin-right: 4px;"></i> Ulangi
      </button>
    </div>
  `;
}

function selectQuizMinat(val, btn) {
  currentMinat = val;
  document.querySelectorAll('#quiz-minat-options .quiz-option-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  renderQuizResult();
}

function selectQuizGaya(val, btn) {
  currentGaya = val;
  document.querySelectorAll('#quiz-gaya-options .quiz-option-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  renderQuizResult();
}

function resetTalentQuiz() {
  currentMinat = 'sains';
  currentGaya = 'praktik';
  const firstMinat = document.querySelector('#quiz-minat-options .quiz-option-btn');
  const firstGaya = document.querySelector('#quiz-gaya-options .quiz-option-btn');
  if (firstMinat) selectQuizMinat('sains', firstMinat);
  if (firstGaya) selectQuizGaya('praktik', firstGaya);
  showInteractiveToast("Simulasi minat telah direset. Silakan coba kombinasi lain!");
}

function initInteractiveTalentQuiz() {
  if (document.getElementById('quiz-result-container')) {
    renderQuizResult();
  }
}

// 3. Interactive News Category Filter
function filterNewsCategory(category, btn) {
  document.querySelectorAll('.news-filter-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');

  const cards = document.querySelectorAll('#news-cards-grid .news-card');
  let matchCount = 0;

  cards.forEach(card => {
    const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
    const target = category.toLowerCase();

    let isMatch = false;
    if (target === 'all') {
      isMatch = true;
    } else if (target === 'berita' && (cardCat.includes('berita') || cardCat.includes('kabar') || cardCat === '')) {
      isMatch = true;
    } else if (target === 'adiwiyata' && cardCat.includes('adiwiyata')) {
      isMatch = true;
    } else if (target === 'literasi' && (cardCat.includes('literasi') || cardCat.includes('gemintang'))) {
      isMatch = true;
    } else if (target === 'prestasi' && cardCat.includes('prestasi')) {
      isMatch = true;
    }

    if (isMatch) {
      matchCount++;
      card.style.display = 'flex';
      card.style.animation = 'quizResultFadeIn 0.3s ease-out';
    } else {
      card.style.display = 'none';
    }
  });

  showInteractiveToast(`Menampilkan ${matchCount} warta kategori ${category === 'all' ? 'Semua' : category}`, 'fas fa-newspaper');
}

function initNewsFilter() {
  // Ready
}

// 4. Interactive Confetti Celebration
function triggerPrestasiConfetti(e) {
  const canvas = document.getElementById('confetti-canvas');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  const colors = ['#FFE15D', '#1B9C85', '#10B981', '#38BDF8', '#F43F5E', '#A855F7'];
  const particles = [];
  const particleCount = 110;

  const startX = e && e.clientX ? e.clientX : window.innerWidth / 2;
  const startY = e && e.clientY ? e.clientY : window.innerHeight * 0.7;

  for (let i = 0; i < particleCount; i++) {
    particles.push({
      x: startX,
      y: startY,
      vx: (Math.random() - 0.5) * 18,
      vy: (Math.random() * -18) - 4,
      size: Math.random() * 9 + 4,
      color: colors[Math.floor(Math.random() * colors.length)],
      rotation: Math.random() * 360,
      vRot: (Math.random() - 0.5) * 12,
      opacity: 1,
      gravity: 0.38,
      isStar: Math.random() > 0.65
    });
  }

  let animationFrame;
  function update() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    let alive = false;

    particles.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;
      p.vy += p.gravity;
      p.vx *= 0.98;
      p.rotation += p.vRot;
      p.opacity -= 0.009;

      if (p.opacity > 0) {
        alive = true;
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate((p.rotation * Math.PI) / 180);
        ctx.fillStyle = p.color;
        ctx.globalAlpha = Math.max(0, p.opacity);

        if (p.isStar) {
          ctx.beginPath();
          ctx.arc(0, 0, p.size / 2, 0, Math.PI * 2);
          ctx.fill();
        } else {
          ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.6);
        }
        ctx.restore();
      }
    });

    if (alive) {
      animationFrame = requestAnimationFrame(update);
    } else {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      cancelAnimationFrame(animationFrame);
    }
  }

  update();
  showInteractiveToast("🎉 Masya Allah! 1.924+ Prestasi Gemilang telah diraih oleh siswa MIN 1 Ciamis!", "fas fa-trophy");
}

function initConfettiCelebration() {
  const trophyBanner = document.getElementById('total-prestasi-banner');
  if (trophyBanner) {
    trophyBanner.addEventListener('click', triggerPrestasiConfetti);
  }
}

// 5. Interactive Mutiara Hikmah
const mutiaraQuotes = [
  { text: "Menuntut ilmu adalah kewajiban bagi setiap muslim, dan kebaikan yang ditebarkan akan kembali berlipat ganda.", author: "— HR. Ibnu Majah" },
  { text: "Sebaik-baik kalian adalah orang yang belajar Al-Qur'an dan mengajarkannya kepada sesama.", author: "— HR. Bukhari" },
  { text: "Barangsiapa menempuh jalan untuk mencari ilmu, Allah akan mudahkan jalannya menuju surga.", author: "— HR. Muslim" },
  { text: "Tiada hari tanpa prestasi: hiasilah ikhtiar dengan doa, kesabaran, dan tawakal kepada Allah SWT.", author: "— Motivasi MIN 1 Ciamis" },
  { text: "Kebersihan adalah sebagian dari iman. Madrasah yang asri melahirkan pikiran yang jernih dan berprestasi.", author: "— Adiwiyata MIN 1 Ciamis" },
  { text: "Senyum, salam, dan sapa adalah sedekah termudah yang membuka pintu-pintu keberkahan ukhuwah.", author: "— HR. Tirmidzi" },
  { text: "Pendidikan bukan sekadar mengisi wadah, melainkan menyalakan api keingintahuan dan keluhuran budi pekerti.", author: "— Guru MIN 1 Ciamis" }
];

let currentMutiaraIndex = 0;

function shuffleMutiaraHikmah() {
  const textEl = document.getElementById('mutiara-text');
  const authorEl = document.getElementById('mutiara-author');
  if (!textEl || !authorEl) return;

  textEl.style.opacity = '0';
  textEl.style.transform = 'translateY(-6px)';

  setTimeout(() => {
    let nextIndex;
    do {
      nextIndex = Math.floor(Math.random() * mutiaraQuotes.length);
    } while (nextIndex === currentMutiaraIndex && mutiaraQuotes.length > 1);

    currentMutiaraIndex = nextIndex;
    const q = mutiaraQuotes[currentMutiaraIndex];
    textEl.textContent = `"${q.text}"`;
    authorEl.textContent = q.author;

    textEl.style.opacity = '1';
    textEl.style.transform = 'translateY(0)';
    showInteractiveToast("Mutiara hikmah berganti!", "fas fa-moon");
  }, 200);
}

function copyMutiaraQuote() {
  const textEl = document.getElementById('mutiara-text');
  const authorEl = document.getElementById('mutiara-author');
  if (!textEl) return;

  const fullText = `${textEl.textContent} ${authorEl ? authorEl.textContent : ''}`;
  navigator.clipboard.writeText(fullText).then(() => {
    showInteractiveToast("Kutipan berhasil disalin ke clipboard!", "fas fa-clipboard-check");
  }).catch(() => {
    showInteractiveToast("Kutipan tersalin!");
  });
}

function initMutiaraHikmah() {
  // ready
}

// 6. Interactive 3D Tilt Effect on Cards (Magnetic Hover)
function initInteractiveTilt() {
  if (window.innerWidth < 992) return; // Desktop only

  const tiltCards = document.querySelectorAll('.tilt-card, .stat-card, .service-card, .testimonial-card');
  tiltCards.forEach(card => {
    if (card._tiltInitialized) return;
    card._tiltInitialized = true;

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const centerX = rect.width / 2;
      const centerY = rect.height / 2;

      const rotateX = ((y - centerY) / centerY) * -5.5;
      const rotateY = ((x - centerX) / centerX) * 5.5;

      card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) translateY(0)';
    });
  });
}



// 21. Interactive Tim Manajemen Cards (Glass Mode & 3D Motion)
function initTimManajemenCards() {
  if (window.innerWidth < 992) return;
  const cards = document.querySelectorAll('.tim-manajemen-card');
  cards.forEach(card => {
    const badge = card.querySelector('.tim-card-glass-badge');
    const img = card.querySelector('.tim-card-img');

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      const tiltX = -y * 12;
      const tiltY = x * 12;

      card.style.transform = `perspective(850px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) translateY(-8px)`;

      if (badge) {
        badge.style.transform = `translateY(-6px) translate3d(${(x * 8).toFixed(1)}px, ${(y * 5).toFixed(1)}px, 16px)`;
      }
      if (img) {
        img.style.transform = `scale(1.06) translate3d(${(-x * 8).toFixed(1)}px, ${(-y * 8).toFixed(1)}px, 0)`;
      }
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
      card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease';
      if (badge) {
        badge.style.transform = '';
      }
      if (img) {
        img.style.transform = '';
      }
    });

    card.addEventListener('mouseenter', () => {
      card.style.transition = 'transform 0.1s ease-out, box-shadow 0.3s ease, border-color 0.3s ease';
    });
  });
}



// 7. Interactive 3D Floating Trophy (Piala 3D Mengawang)
function init3dFloatingTrophy() {
  const trophyBanner = document.getElementById('total-prestasi-banner');
  if (trophyBanner && window.innerWidth >= 992) {
    const leftInteractive = trophyBanner.querySelector('.piala-3d-floating-wrapper.left .piala-3d-interactive');
    const rightInteractive = trophyBanner.querySelector('.piala-3d-floating-wrapper.right .piala-3d-interactive');

    trophyBanner.addEventListener('mousemove', (e) => {
      const rect = trophyBanner.getBoundingClientRect();
      const x = e.clientX - rect.left - (rect.width / 2);
      const y = e.clientY - rect.top - (rect.height / 2);

      const rotateY = (x / rect.width) * 20; // -10deg to +10deg
      const rotateX = -(y / rect.height) * 16; // -8deg to +8deg

      if (leftInteractive) {
        leftInteractive.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY - 6}deg) translateZ(35px)`;
      }
      if (rightInteractive) {
        rightInteractive.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY + 6}deg) translateZ(35px)`;
      }
    });

    trophyBanner.addEventListener('mouseleave', () => {
      if (leftInteractive) leftInteractive.style.transform = '';
      if (rightInteractive) rightInteractive.style.transform = '';
    });
  }
}

// 8. Lunar 3D Floating Trophies (Melayang di Bulan - Pinggir Kiri & Kanan)
function initLunarTrophies() {
  const heroSection = document.getElementById('beranda');
  const podLeft = document.getElementById('lunarPodLeft');
  const podRight = document.getElementById('lunarPodRight');

  if (heroSection && (podLeft || podRight) && window.innerWidth >= 992) {
    heroSection.addEventListener('mousemove', (e) => {
      const rect = heroSection.getBoundingClientRect();
      const xPercent = (e.clientX - rect.left) / rect.width; // 0 to 1
      const yPercent = (e.clientY - rect.top) / rect.height; // 0 to 1

      // Subtle zero-g drift responding to mouse
      const tiltX = (yPercent - 0.5) * -14;
      const tiltY = (xPercent - 0.5) * 16;

      if (podLeft) {
        const leftStage = podLeft.querySelector('.lunar-trophy-zero-g');
        if (leftStage && !leftStage.classList.contains('spin-360')) {
          leftStage.style.transform = `perspective(1200px) rotateX(${tiltX}deg) rotateY(${tiltY - 8}deg) translateZ(25px)`;
        }
      }

      if (podRight) {
        const rightStage = podRight.querySelector('.lunar-trophy-zero-g');
        if (rightStage && !rightStage.classList.contains('spin-360')) {
          rightStage.style.transform = `perspective(1200px) rotateX(${tiltX}deg) rotateY(${tiltY + 8}deg) translateZ(25px)`;
        }
      }
    });

    heroSection.addEventListener('mouseleave', () => {
      if (podLeft) {
        const leftStage = podLeft.querySelector('.lunar-trophy-zero-g');
        if (leftStage) leftStage.style.transform = '';
      }
      if (podRight) {
        const rightStage = podRight.querySelector('.lunar-trophy-zero-g');
        if (rightStage) rightStage.style.transform = '';
      }
    });
  }
}

// 360-degree zero-g lunar spin on click
function triggerLunarTrophySpin(side) {
  const stage = document.getElementById(side === 'left' ? 'lunarZeroGLeft' : 'lunarZeroGRight');
  const pod = document.getElementById(side === 'left' ? 'lunarPodLeft' : 'lunarPodRight');

  if (stage) {
    stage.classList.remove('spin-360');
    void stage.offsetWidth; // trigger reflow
    stage.classList.add('spin-360');

    setTimeout(() => {
      stage.classList.remove('spin-360');
    }, 1250);
  }

  // Confetti from trophy position
  if (pod && typeof triggerPrestasiConfetti === 'function') {
    const rect = pod.getBoundingClientRect();
    triggerPrestasiConfetti({
      clientX: rect.left + rect.width / 2,
      clientY: rect.top + rect.height / 2
    });
  }
}

// ============================================================
// 9. ELEGANT ARTICLE GALLERY SLIDER (SLIDE FOTO DOKUMENTASI BERITA)
// ============================================================
let activeLightboxSlides = [];
let activeLightboxIndex = 0;

function initArticleGallerySlider() {
  const article = document.querySelector('article');
  if (!article) return;

  let galleryCount = 0;

  // Helper to normalize WordPress asset URL to local relative path
  function normalizeGallerySrc(url) {
    if (!url) return '';
    return url.replace(/^https?:\/\/min1kotamalang\.sch\.id\/wp-content\/uploads\//i, '/wp-content/uploads/');
  }

  // 1. Process all .wp-block-gallery elements
  const galleries = article.querySelectorAll('.wp-block-gallery');
  galleries.forEach((gallery) => {
    const imgElements = gallery.querySelectorAll('img');
    if (imgElements.length < 2) return; // If only 1 image, keep clean

    const slides = [];
    imgElements.forEach((img, i) => {
      const parentFig = img.closest('figure');
      const figCap = parentFig ? parentFig.querySelector('figcaption') : null;
      slides.push({
        src: normalizeGallerySrc(img.getAttribute('src')),
        srcset: normalizeGallerySrc(img.getAttribute('srcset') || ''),
        alt: img.getAttribute('alt') || `Foto Dokumentasi ${i + 1}`,
        caption: figCap ? figCap.textContent.trim() : ''
      });
    });

    galleryCount++;
    const sliderBox = buildGallerySliderDOM(slides, galleryCount);
    gallery.parentNode.replaceChild(sliderBox, gallery);
    bindGallerySliderEvents(sliderBox, slides);
  });

  // 2. Process consecutive .wp-block-image or images at bottom of article
  const articleChildren = Array.from(article.children);
  const consecutiveImageGroups = [];
  let tempGroup = [];

  articleChildren.forEach(child => {
    const isImageFigure = child.tagName === 'FIGURE' && child.classList.contains('wp-block-image') && !child.closest('.wp-block-gallery') && !child.closest('.article-gallery-slider-box');
    if (isImageFigure) {
      tempGroup.push(child);
    } else {
      if (tempGroup.length >= 2) {
        consecutiveImageGroups.push([...tempGroup]);
      }
      tempGroup = [];
    }
  });
  if (tempGroup.length >= 2) {
    consecutiveImageGroups.push([...tempGroup]);
  }

  consecutiveImageGroups.forEach(group => {
    const slides = [];
    group.forEach((fig, i) => {
      const img = fig.querySelector('img');
      if (img) {
        const figCap = fig.querySelector('figcaption');
        slides.push({
          src: normalizeGallerySrc(img.getAttribute('src')),
          srcset: normalizeGallerySrc(img.getAttribute('srcset') || ''),
          alt: img.getAttribute('alt') || `Foto Dokumentasi ${i + 1}`,
          caption: figCap ? figCap.textContent.trim() : ''
        });
      }
    });

    if (slides.length >= 2) {
      galleryCount++;
      const sliderBox = buildGallerySliderDOM(slides, galleryCount);
      group[0].parentNode.insertBefore(sliderBox, group[0]);
      group.forEach(el => el.remove());
      bindGallerySliderEvents(sliderBox, slides);
    }
  });
}

function buildGallerySliderDOM(slides, id) {
  const box = document.createElement('div');
  box.className = 'article-gallery-slider-box';
  box.setAttribute('id', `articleGallerySlider-${id}`);

  let slidesHtml = '';
  let thumbsHtml = '';

  slides.forEach((s, idx) => {
    const activeClass = idx === 0 ? 'active' : '';
    slidesHtml += `
      <div class="gallery-stage-slide ${activeClass}" data-slide-index="${idx}">
        <img src="${s.src}" srcset="${s.srcset}" alt="${s.alt}" title="Klik untuk memperbesar foto">
      </div>
    `;
    thumbsHtml += `
      <div class="gallery-thumb-item ${activeClass}" data-thumb-index="${idx}" title="${s.alt}">
        <img src="${s.src}" alt="${s.alt}" loading="lazy">
      </div>
    `;
  });

  box.innerHTML = `
    <div class="gallery-slider-header">
      <div class="gallery-slider-title">
        <i class="fas fa-camera-retro"></i>
        <span>Galeri Foto Dokumentasi</span>
      </div>
      <div class="gallery-slider-meta">
        <span class="gallery-counter-badge"><span class="cur-idx">1</span> / ${slides.length}</span>
        <button type="button" class="gallery-expand-btn" title="Lihat Foto Layar Penuh">
          <i class="fas fa-expand"></i> Layar Penuh
        </button>
      </div>
    </div>

    <div class="gallery-stage-container">
      ${slidesHtml}
      <button type="button" class="gallery-arrow-btn prev" aria-label="Foto Sebelumnya">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button type="button" class="gallery-arrow-btn next" aria-label="Foto Berikutnya">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>

    <div class="gallery-caption-bar" style="${slides[0].caption ? '' : 'display: none;'}">
      ${slides[0].caption || ''}
    </div>

    <div class="gallery-thumb-strip">
      ${thumbsHtml}
    </div>
  `;

  return box;
}

function bindGallerySliderEvents(sliderBox, slides) {
  let currentIndex = 0;
  const total = slides.length;
  let validCount = total;
  const failedIndices = new Set();

  const slidesEls = sliderBox.querySelectorAll('.gallery-stage-slide');
  const thumbsEls = sliderBox.querySelectorAll('.gallery-thumb-item');
  const counterEl = sliderBox.querySelector('.gallery-counter-badge .cur-idx');
  const captionEl = sliderBox.querySelector('.gallery-caption-bar');
  const btnPrev = sliderBox.querySelector('.gallery-arrow-btn.prev');
  const btnNext = sliderBox.querySelector('.gallery-arrow-btn.next');
  const btnExpand = sliderBox.querySelector('.gallery-expand-btn');

  function handleImageError(idx, imgEl) {
    if (failedIndices.has(idx)) return;

    // 1. Try clean local fallback if possible
    const src = imgEl.getAttribute('src') || '';
    if (src.includes('/wp-content/uploads/') && !imgEl.dataset.fallbackTried) {
      imgEl.dataset.fallbackTried = '1';
      const cleanName = src.split('/').pop().split('?')[0]
        .replace(/-\d+x\d+(\.[a-zA-Z0-9]+)$/, '$1')
        .replace(/-scaled(\.[a-zA-Z0-9]+)$/, '$1');
      imgEl.src = '/uploads/wp-content/' + cleanName;
      return;
    }

    // 2. Mark index as permanently failed
    failedIndices.add(idx);
    validCount--;

    if (slidesEls[idx]) slidesEls[idx].style.display = 'none';
    if (thumbsEls[idx]) thumbsEls[idx].style.display = 'none';

    // 3. If fewer than 2 valid slides remain, hide sliderBox completely
    if (validCount < 2) {
      sliderBox.style.display = 'none';
      return;
    }

    // 4. If active slide was hidden, navigate to next available slide
    if (currentIndex === idx) {
      for (let i = 0; i < total; i++) {
        if (!failedIndices.has(i)) {
          goToSlide(i);
          break;
        }
      }
    }

    const counterBadge = sliderBox.querySelector('.gallery-counter-badge');
    if (counterBadge) {
      counterBadge.innerHTML = `<span class="cur-idx">${currentIndex + 1}</span> / ${validCount}`;
    }
  }

  slidesEls.forEach((slide, idx) => {
    const img = slide.querySelector('img');
    if (img) {
      img.addEventListener('error', () => handleImageError(idx, img));
    }
  });

  thumbsEls.forEach((thumb, idx) => {
    const img = thumb.querySelector('img');
    if (img) {
      img.addEventListener('error', () => handleImageError(idx, img));
    }
  });

  function goToSlide(idx) {
    if (idx < 0) idx = total - 1;
    if (idx >= total) idx = 0;
    
    // Skip failed slides if any
    if (failedIndices.has(idx)) {
      let attempts = 0;
      while (failedIndices.has(idx) && attempts < total) {
        idx = (idx + 1) % total;
        attempts++;
      }
    }

    currentIndex = idx;

    slidesEls.forEach((s, i) => s.classList.toggle('active', i === currentIndex));
    thumbsEls.forEach((t, i) => {
      const isActive = i === currentIndex;
      t.classList.toggle('active', isActive);
      if (isActive) {
        t.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }
    });

    if (counterEl) counterEl.textContent = currentIndex + 1;

    if (captionEl) {
      const cap = (slides[currentIndex] && slides[currentIndex].caption) ? slides[currentIndex].caption : '';
      if (cap) {
        captionEl.textContent = cap;
        captionEl.style.display = 'block';
      } else {
        captionEl.style.display = 'none';
      }
    }
  }

  if (btnPrev) btnPrev.addEventListener('click', () => goToSlide(currentIndex - 1));
  if (btnNext) btnNext.addEventListener('click', () => goToSlide(currentIndex + 1));

  thumbsEls.forEach((thumb, idx) => {
    thumb.addEventListener('click', () => goToSlide(idx));
  });

  // Click on slide image or expand button opens Lightbox
  slidesEls.forEach((slide, idx) => {
    const img = slide.querySelector('img');
    if (img) {
      img.addEventListener('click', () => {
        openGalleryLightbox(slides, idx);
      });
    }
  });

  if (btnExpand) {
    btnExpand.addEventListener('click', () => {
      openGalleryLightbox(slides, currentIndex);
    });
  }

  // Mobile Touch Swipe support
  let touchStartX = 0;
  let touchEndX = 0;
  const stage = sliderBox.querySelector('.gallery-stage-container');
  if (stage) {
    stage.addEventListener('touchstart', e => {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    stage.addEventListener('touchend', e => {
      touchEndX = e.changedTouches[0].screenX;
      if (touchStartX - touchEndX > 45) {
        goToSlide(currentIndex + 1);
      } else if (touchEndX - touchStartX > 45) {
        goToSlide(currentIndex - 1);
      }
    }, { passive: true });
  }
}

// Lightbox functions
function openGalleryLightbox(slides, initialIdx) {
  activeLightboxSlides = slides;
  activeLightboxIndex = initialIdx;

  const modal = document.getElementById('galleryLightboxModal');
  if (!modal) return;

  renderLightboxCurrent();
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeGalleryLightbox() {
  const modal = document.getElementById('galleryLightboxModal');
  if (modal) {
    modal.classList.remove('active');
  }
  document.body.style.overflow = '';
}

function navigateLightbox(dir) {
  if (!activeLightboxSlides.length) return;
  activeLightboxIndex += dir;
  if (activeLightboxIndex < 0) activeLightboxIndex = activeLightboxSlides.length - 1;
  if (activeLightboxIndex >= activeLightboxSlides.length) activeLightboxIndex = 0;
  renderLightboxCurrent();
}

function renderLightboxCurrent() {
  const slide = activeLightboxSlides[activeLightboxIndex];
  if (!slide) return;

  const img = document.getElementById('lightboxMainImg');
  const counter = document.getElementById('lightboxCounter');
  const caption = document.getElementById('lightboxCaption');

  if (img) {
    img.src = slide.src;
    img.alt = slide.alt;
  }
  if (counter) {
    counter.textContent = `${activeLightboxIndex + 1} / ${activeLightboxSlides.length}`;
  }
  if (caption) {
    caption.textContent = slide.caption || '';
    caption.style.display = slide.caption ? 'block' : 'none';
  }
}

// Keyboard arrow navigation for lightbox
document.addEventListener('keydown', e => {
  const modal = document.getElementById('galleryLightboxModal');
  if (modal && modal.classList.contains('active')) {
    if (e.key === 'Escape') closeGalleryLightbox();
    if (e.key === 'ArrowLeft') navigateLightbox(-1);
    if (e.key === 'ArrowRight') navigateLightbox(1);
  }
});

/* --- 21. KEPALA MADRASAH HISTORIC TIMELINE --- */
function initHeadmasterTimeline() {
  const container = document.querySelector('.historic-timeline-scroll-container');
  if (!container) return;

  // Drag-to-scroll support for horizontal timeline
  let isDown = false;
  let startX;
  let scrollLeft;

  container.addEventListener('mousedown', (e) => {
    isDown = true;
    container.style.cursor = 'grabbing';
    startX = e.pageX - container.offsetLeft;
    scrollLeft = container.scrollLeft;
  });

  container.addEventListener('mouseleave', () => {
    isDown = false;
    container.style.cursor = '';
  });

  container.addEventListener('mouseup', () => {
    isDown = false;
    container.style.cursor = '';
  });

  container.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - container.offsetLeft;
    const walk = (x - startX) * 1.5;
    container.scrollLeft = scrollLeft - walk;
  });

  // Interactive 3D cursor movement on glass pillars & sync with dots
  const cols = container.querySelectorAll('.historic-col');
  const dots = container.querySelectorAll('.axis-node-dot');

  cols.forEach((col, idx) => {
    const frame = col.querySelector('.pillar-frame');
    const img = col.querySelector('.pillar-img');
    const info = col.querySelector('.pillar-info');
    if (!frame) return;

    let isHovered = false;

    col.addEventListener('mouseenter', () => {
      isHovered = true;
      col.style.zIndex = '60';
      frame.style.zIndex = '60';
      frame.style.transition = 'transform 0.08s ease-out, box-shadow 0.25s ease, border-color 0.25s ease';
      if (img) img.style.transition = 'transform 0.1s ease-out';
      
      if (dots[idx]) {
        dots[idx].style.transform = 'scale(1.45)';
        dots[idx].style.backgroundColor = '#1B9C85';
        dots[idx].style.boxShadow = '0 0 0 2px #1B9C85, 0 0 12px rgba(27, 156, 133, 0.5)';
      }
    });

    col.addEventListener('mousemove', (e) => {
      if (!isHovered) return;
      const rect = col.getBoundingClientRect();
      // Mouse coordinates normalized (-0.5 to +0.5 from column center)
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      // Dynamic 3D tilt calculation
      const tiltX = -y * 22; // rotate along X axis
      const tiltY = x * 22;  // rotate along Y axis
      const moveX = x * 10;  // translation X in px
      const moveY = y * 8 - 10; // translation Y in px with upward float

      // Enlarge photo noticeably (scale 1.22) with dynamic 3D perspective and elevation
      frame.style.transform = `perspective(750px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) translate3d(${moveX.toFixed(1)}px, ${moveY.toFixed(1)}px, 24px) scale(1.22)`;

      if (img) {
        // Holographic depth parallax inside the glass frame
        img.style.transform = `scale(1.12) translate3d(${-x * 8}px, ${-y * 8}px, 0)`;
      }

      if (info) {
        info.style.transform = `translate3d(${x * 6}px, -2px, 0)`;
        info.style.transition = 'transform 0.08s ease-out';
      }
    });

    col.addEventListener('mouseleave', () => {
      isHovered = false;
      col.style.zIndex = '';
      frame.style.zIndex = '';
      frame.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease';
      frame.style.transform = 'perspective(750px) rotateX(0deg) rotateY(0deg) translate3d(0, 0, 0) scale(1)';
      
      if (img) {
        img.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
        img.style.transform = 'scale(1) translate3d(0, 0, 0)';
      }
      if (info) {
        info.style.transition = 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
        info.style.transform = 'translate3d(0, 0, 0)';
      }

      if (dots[idx]) {
        dots[idx].style.transform = '';
        dots[idx].style.backgroundColor = '';
        dots[idx].style.boxShadow = '';
      }
    });
  });

  dots.forEach((dot, idx) => {
    dot.addEventListener('mouseenter', () => {
      const frame = cols[idx]?.querySelector('.pillar-frame');
      if (frame) {
        cols[idx].style.zIndex = '60';
        frame.style.zIndex = '60';
        frame.style.transform = 'translateY(-10px) scale(1.20)';
        frame.style.boxShadow = '0 24px 45px -4px rgba(27, 156, 133, 0.35)';
      }
    });
    dot.addEventListener('mouseleave', () => {
      const frame = cols[idx]?.querySelector('.pillar-frame');
      if (frame) {
        cols[idx].style.zIndex = '';
        frame.style.zIndex = '';
        frame.style.transform = '';
        frame.style.boxShadow = '';
      }
    });
  });
}

// 21. Interactive Tim Manajemen Cards (Glass Mode & 3D Motion)
function initTimManajemenCards() {
  if (window.innerWidth < 992) return;
  const cards = document.querySelectorAll('.tim-manajemen-card');
  cards.forEach(card => {
    const badge = card.querySelector('.tim-card-glass-badge');
    const img = card.querySelector('.tim-card-img');

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      const tiltX = -y * 12;
      const tiltY = x * 12;

      card.style.transform = `perspective(850px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) translateY(-8px)`;

      if (badge) {
        badge.style.transform = `translateY(-6px) translate3d(${(x * 8).toFixed(1)}px, ${(y * 5).toFixed(1)}px, 16px)`;
      }
      if (img) {
        img.style.transform = `scale(1.06) translate3d(${(-x * 8).toFixed(1)}px, ${(-y * 8).toFixed(1)}px, 0)`;
      }
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
      card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, border-color 0.4s ease';
      if (badge) {
        badge.style.transform = '';
      }
      if (img) {
        img.style.transform = '';
      }
    });

    card.addEventListener('mouseenter', () => {
      card.style.transition = 'transform 0.1s ease-out, box-shadow 0.3s ease, border-color 0.3s ease';
    });
  });
}


// 22. Highly Interactive 3D Photo on Profile Page
function initProfilBuildingPhoto() {
  const photo = document.getElementById('profilBuildingPhoto');
  if (!photo) return;

  const sheen = photo.querySelector('.profil-photo-sheen');
  let isHovered = false;

  photo.addEventListener('mouseenter', () => {
    isHovered = true;
    photo.style.transition = 'transform 0.08s ease-out, filter 0.15s ease-out';
    if (sheen) sheen.style.opacity = '1';
  });

  photo.addEventListener('mousemove', (e) => {
    if (!isHovered) return;
    const rect = photo.getBoundingClientRect();
    // Normalized coordinates from card center (-0.5 to +0.5)
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;

    // 3D tilt calculation
    const tiltX = -y * 22; // rotate around X
    const tiltY = x * 22;  // rotate around Y
    const moveX = x * 16;  // shift along X
    const moveY = y * 14 - 8; // upward float

    // Dynamic drop shadow displacement opposite to mouse tilt
    const shadowX = -x * 28;
    const shadowY = Math.max(18, -y * 24 + 30);
    photo.style.filter = `drop-shadow(${shadowX.toFixed(1)}px ${shadowY.toFixed(1)}px 36px rgba(15, 23, 42, 0.22)) drop-shadow(0 14px 26px rgba(27, 156, 133, 0.16))`;

    // 3D transform with depth elevation and scaling
    photo.style.transform = `perspective(1000px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) translate3d(${moveX.toFixed(1)}px, ${moveY.toFixed(1)}px, 35px) scale(1.05)`;

    // Specular glossy sheen gliding across the building and badge
    if (sheen) {
      const glareX = ((e.clientX - rect.left) / rect.width) * 100;
      const glareY = ((e.clientY - rect.top) / rect.height) * 100;
      sheen.style.setProperty('--glare-x', `${glareX.toFixed(1)}%`);
      sheen.style.setProperty('--glare-y', `${glareY.toFixed(1)}%`);
    }
  });

  photo.addEventListener('mouseleave', () => {
    isHovered = false;
    photo.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), filter 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
    photo.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translate3d(0, 0, 0) scale(1)';
    photo.style.filter = 'drop-shadow(0 18px 30px rgba(15, 23, 42, 0.12))';
    if (sheen) sheen.style.opacity = '0';
  });

  photo.addEventListener('mousedown', () => {
    photo.style.transform += ' scale(0.98)';
  });
  photo.addEventListener('mouseup', () => {
    photo.style.transform = photo.style.transform.replace(' scale(0.98)', '');
  });
}




window.submitDummyPPDB = function(event) {
  event.preventDefault();
  const nama = document.getElementById('ppdb-nama')?.value || 'Calon Siswa';
  const wa = document.getElementById('ppdb-wa')?.value || '-';
  const jalur = document.getElementById('ppdb-jalur')?.value || 'Reguler';
  
  const contentArea = document.getElementById('modal-content-area');
  if (contentArea) {
    contentArea.innerHTML = `
      <div style="text-align: center; padding: 25px 15px;">
        <div style="width: 72px; height: 72px; background: #ECFDF5; color: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 18px;">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3 style="color: #0F172A; font-family: 'Outfit', sans-serif; font-size: 1.5rem; margin-bottom: 10px;">Alhamdulillah, Data Berhasil Dicatat!</h3>
        <p style="color: #475569; font-size: 0.98rem; line-height: 1.6; max-width: 480px; margin: 0 auto 20px;">
          Formulir pendaftaran dummy atas nama <strong>${nama}</strong> (${jalur}) telah tersimpan dalam simulasi sistem PPDB MIN 1 Ciamis.
        </p>
        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 20px; font-size: 0.88rem; color: #334155; margin-bottom: 24px;">
          Konfirmasi informasi berkas akan disampaikan via WhatsApp ke: <strong>${wa}</strong>
        </div>
        <button onclick="document.getElementById('modal-close-btn').click()" style="background: #1B9C85; color: #FFF; border: none; padding: 10px 24px; border-radius: 9999px; font-weight: 700; cursor: pointer; font-size: 0.92rem;">
          Selesai &amp; Tutup
        </button>
      </div>
    `;
  }
  if (typeof triggerPrestasiConfetti === 'function') {
    triggerPrestasiConfetti(event);
  }
};


/* ==========================================================================
   GURU & TENAGA PENDIDIK INTERACTION & DIRECTORY MODAL
   ========================================================================== */

const GURU_DATABASE = [
  {
    id: 'agus',
    nama: 'Agus Setiawan, S.Pd.',
    peran: 'Guru Mata Pelajaran',
    mapel: 'Matematika & IPA Terpadu',
    nip: '19850712 201001 1 018',
    avatar: 'guru-avatar-1.svg',
    pendidikan: 'S1 Pendidikan Matematika - UPI Bandung',
    kategori: 'mapel',
    motto: 'Menciptakan ruang belajar logika yang ramah, aplikatif, dan memicu daya nalar kritis siswa.'
  },
  {
    id: 'dewi',
    nama: 'Dewi Lestari, S.Si.',
    peran: 'Guru Mata Pelajaran',
    mapel: 'Sains & Pembina Riset Madrasah (KSM)',
    nip: '19880315 201402 2 009',
    avatar: 'guru-avatar-2.svg',
    pendidikan: 'S1 Biologi Murni - Universitas Padjadjaran',
    kategori: 'mapel',
    motto: 'Eksplorasi alam semesta adalah jembatan mengagumi kebesaran Sang Pencipta.'
  },
  {
    id: 'aminah',
    nama: 'Siti Aminah, S.Pd.I.',
    peran: 'Guru Mata Pelajaran',
    mapel: 'Tahfidz, Tartil & Al-Qur\'an Hadits',
    nip: '19901124 201603 2 015',
    avatar: 'guru-avatar-3.svg',
    pendidikan: 'S1 Pendidikan Agama Islam - UIN SGD Bandung',
    kategori: 'mapel',
    motto: 'Menanamkan kecintaan pada Al-Qur\'an sejak dini sebagai penerang jalan kehidupan.'
  },
  {
    id: 'rizal',
    nama: 'Rizal Hidayat, S.Kom.',
    peran: 'Guru Mata Pelajaran',
    mapel: 'TIK & Media Pembelajaran Digital',
    nip: '19920918 201901 1 007',
    avatar: 'guru-avatar-4.svg',
    pendidikan: 'S1 Teknik Informatika - Telkom University',
    kategori: 'mapel',
    motto: 'Membimbing generasi digital madrasah yang bijak, kreatif, dan berdaya saing global.'
  },
  {
    id: 'ahmad',
    nama: 'Drs. H. Ahmad Sobari, M.Pd.I.',
    peran: 'Kepala Madrasah',
    mapel: 'Kepemimpinan & Manajemen Pendidikan',
    nip: '19690412 199403 1 003',
    avatar: 'guru-avatar-5.svg',
    pendidikan: 'S2 Manajemen Pendidikan Islam - Pascasarjana UIN SGD',
    kategori: 'tendik',
    motto: 'Membangun ekosistem madrasah berakhlak mulia, unggul prestasi, dan bermanfaat bagi umat.'
  },
  {
    id: 'asep',
    nama: 'Asep Nurjaman, S.Pd.',
    peran: 'Guru PJOK & Olahraga',
    mapel: 'Pendidikan Jasmani, Olahraga & Kesehatan',
    nip: '19870505 201201 1 012',
    avatar: 'guru-avatar-6.svg',
    pendidikan: 'S1 Pendidikan Kepelatihan Olahraga - STKIP Ciamis',
    kategori: 'mapel',
    motto: 'Tubuh yang sehat dan bugar adalah wadah jiwa yang tangguh dan bersemangat.'
  },
  {
    id: 'nurul',
    nama: 'Hj. Nurul Fitriah, M.Pd.',
    peran: 'Guru Bahasa Asing',
    mapel: 'Bahasa Arab & Bahasa Inggris',
    nip: '19860822 201101 2 011',
    avatar: 'guru-avatar-7.svg',
    pendidikan: 'S2 Pendidikan Bahasa - Universitas Negeri Yogyakarta',
    kategori: 'mapel',
    motto: 'Bahasa adalah kunci membuka khazanah ilmu pengetahuan dunia dan peradaban Islam.'
  },
  {
    id: 'dedi',
    nama: 'Dedi Suhendar, S.Ag.',
    peran: 'Guru PAI & Fiqih',
    mapel: 'Fiqih Ibadah & Sejarah Kebudayaan Islam',
    nip: '19780214 200501 1 004',
    avatar: 'guru-avatar-8.svg',
    pendidikan: 'S1 Syari\'ah & Hukum Islam - IAIC Tasikmalaya',
    kategori: 'mapel',
    motto: 'Amalan ibadah yang benar menumbuhkan ketenangan hati dan kemuliaan budi pekerti.'
  },
  {
    id: 'rina',
    nama: 'Rina Marlina, S.Pd.',
    peran: 'Guru Kelas & Seni Budaya',
    mapel: 'Guru Kelas 4 & Pembina Seni Musik Angklung',
    nip: '19910630 201701 2 008',
    avatar: 'guru-avatar-9.svg',
    pendidikan: 'S1 PGMI - UIN Syarif Hidayatullah Jakarta',
    kategori: 'kelas',
    motto: 'Menghadirkan kehangatan mendidik seperti keluarga kedua bagi para siswa.'
  },
  {
    id: 'endang',
    nama: 'Endang Kurniawan, S.E.',
    peran: 'Kepala Tata Usaha',
    mapel: 'Administrasi Kepegawaian & Tata Usaha',
    nip: '19821110 200801 1 005',
    avatar: 'guru-avatar-10.svg',
    pendidikan: 'S1 Manajemen Keuangan - Universitas Galuh Ciamis',
    kategori: 'tendik',
    motto: 'Layanan administrasi yang ramah, transparan, akuntabel, dan berbasis teknologi.'
  },
  {
    id: 'neneng',
    nama: 'Neneng Hasanah, S.Ptk.',
    peran: 'Tenaga Perpustakaan',
    mapel: 'Pengelola Literasi & Perpustakaan Digital',
    nip: '19930416 202012 2 018',
    avatar: 'guru-avatar-11.svg',
    pendidikan: 'S1 Ilmu Perpustakaan & Informasi - UIN Sunan Kalijaga',
    kategori: 'tendik',
    motto: 'Buku dan literasi adalah gerbang petualangan imajinasi tanpa batas.'
  },
  {
    id: 'yudi',
    nama: 'Yudi Gunawan',
    peran: 'Operator Data & Sarpras',
    mapel: 'Operator EMIS, Simpatika & Sarana Prasarana',
    nip: 'Non-PNS Terdaftar Kemenag',
    avatar: 'guru-avatar-12.svg',
    pendidikan: 'D3 Manajemen Informatika - LP3I',
    kategori: 'tendik',
    motto: 'Akurasi data dan kesiapan sarana adalah fondasi kelancaran pendidikan madrasah.'
  }
];

function initGuruSlider() {
  const track = document.getElementById('guruSliderTrack');
  const prevBtn = document.getElementById('guruSliderPrev');
  const nextBtn = document.getElementById('guruSliderNext');

  if (!track) return;

  const getScrollStep = () => {
    const card = track.querySelector('.guru-card');
    return card ? card.offsetWidth + 22 : 274;
  };

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      track.scrollBy({ left: getScrollStep() * 2, behavior: 'smooth' });
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      track.scrollBy({ left: -getScrollStep() * 2, behavior: 'smooth' });
    });
  }

  // Update disabled state
  const updateArrows = () => {
    if (!prevBtn || !nextBtn) return;
    const maxScroll = track.scrollWidth - track.clientWidth;
    if (track.scrollLeft <= 10) {
      prevBtn.disabled = true;
      prevBtn.classList.add('disabled');
    } else {
      prevBtn.disabled = false;
      prevBtn.classList.remove('disabled');
    }

    if (track.scrollLeft >= maxScroll - 10) {
      nextBtn.disabled = true;
      nextBtn.classList.add('disabled');
    } else {
      nextBtn.disabled = false;
      nextBtn.classList.remove('disabled');
    }
  };

  track.addEventListener('scroll', updateArrows, { passive: true });
  window.addEventListener('resize', updateArrows);
  updateArrows();

  // Mouse Drag to scroll (Bisa digeser-geser dengan mouse)
  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;
  let moved = false;

  track.addEventListener('mousedown', (e) => {
    isDown = true;
    moved = false;
    track.classList.add('dragging');
    startX = e.pageX - track.offsetLeft;
    scrollLeft = track.scrollLeft;
  });

  window.addEventListener('mouseup', () => {
    if (isDown) {
      isDown = false;
      track.classList.remove('dragging');
    }
  });

  track.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - track.offsetLeft;
    const walk = (x - startX) * 1.5;
    if (Math.abs(walk) > 5) moved = true;
    track.scrollLeft = scrollLeft - walk;
  });

  // Prevent card click if dragged
  track.querySelectorAll('.guru-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (moved) {
        e.preventDefault();
        e.stopPropagation();
      }
    }, true);
  });
}

// Global modal opener for Guru detail
window.openGuruDetailModal = function(guruId) {
  const guru = GURU_DATABASE.find(g => g.id === guruId) || GURU_DATABASE[0];
  const modalOverlay = document.getElementById('modal-overlay');
  const modalTitle = document.getElementById('modal-title');
  const modalContent = document.getElementById('modal-content-area');

  if (!modalOverlay || !modalContent) return;

  if (modalTitle) modalTitle.innerHTML = `<i class="fas fa-id-badge" style="color: #0D6A56; margin-right: 8px;"></i> Profil Tenaga Pendidik`;

  modalContent.innerHTML = `
    <div style="display: flex; flex-direction: column; gap: 20px;">
      
      <!-- Top Profile Card -->
      <div style="display: flex; gap: 24px; align-items: center; background: linear-gradient(135deg, #1C4D32 0%, #296945 45%, #B8933C 100%); padding: 24px; border-radius: 20px; color: #FFFFFF; flex-wrap: wrap;">
        <div style="width: 100px; height: 100px; border-radius: 22px; background: #FFFFFF; padding: 4px; box-shadow: 0 8px 24px rgba(0,0,0,0.25); flex-shrink: 0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
          <img src="${guru.avatar}" alt="${guru.nama}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="flex-grow: 1;">
          <div style="display: inline-block; background: rgba(255, 225, 93, 0.25); color: #FFE15D; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 6px;">
            MIN 1 CIAMIS
          </div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 800; margin: 0 0 4px 0; color: #FFFFFF;">${guru.nama}</h3>
          <p style="font-size: 0.95rem; margin: 0; color: #E2E8F0; font-weight: 600;">${guru.peran}</p>
        </div>
      </div>

      <!-- Info Details Grid -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
          <div style="font-size: 0.78rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Mata Pelajaran / Bidang</div>
          <div style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin-top: 4px;">${guru.mapel}</div>
        </div>

        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
          <div style="font-size: 0.78rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Pendidikan Terakhir</div>
          <div style="font-size: 0.95rem; font-weight: 700; color: #0F172A; margin-top: 4px;">${guru.pendidikan}</div>
        </div>

        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
          <div style="font-size: 0.78rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Induk Pegawai (NIP)</div>
          <div style="font-size: 0.92rem; font-weight: 700; color: #0F172A; margin-top: 4px; font-family: monospace;">${guru.nip}</div>
        </div>

        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
          <div style="font-size: 0.78rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Status Penugasan</div>
          <div style="font-size: 0.95rem; font-weight: 700; color: #0D6A56; margin-top: 4px;"><i class="fas fa-check-circle" style="color: #10B981; margin-right: 4px;"></i> Pendidik Aktif</div>
        </div>
      </div>

      <!-- Motto Card -->
      <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 14px; padding: 16px 20px;">
        <div style="font-size: 0.8rem; font-weight: 800; color: #B45309; text-transform: uppercase; margin-bottom: 4px;">Motto Pendidik</div>
        <p style="font-size: 0.95rem; font-style: italic; color: #78350F; margin: 0; line-height: 1.6;">&ldquo;${guru.motto}&rdquo;</p>
      </div>

      <div style="text-align: right; margin-top: 8px;">
        <button onclick="document.getElementById('modal-close-btn').click()" style="background: #0D6A56; color: #FFFFFF; border: none; padding: 10px 24px; border-radius: 9999px; font-weight: 700; cursor: pointer; font-size: 0.9rem;">
          Tutup Profil
        </button>
      </div>

    </div>
  `;

  modalOverlay.classList.add('active');
  document.body.style.overflow = 'hidden';
};

// Global modal opener for Full Directory (Lihat Semua Guru)
window.openGuruDirectoryModal = function() {
  const modalOverlay = document.getElementById('modal-overlay');
  const modalTitle = document.getElementById('modal-title');
  const modalContent = document.getElementById('modal-content-area');

  if (!modalOverlay || !modalContent) return;

  if (modalTitle) modalTitle.innerHTML = `<i class="fas fa-chalkboard-teacher" style="color: #0D6A56; margin-right: 8px;"></i> Direktori Guru &amp; Tenaga Pendidik MIN 1 Ciamis`;

  modalContent.innerHTML = `
    <div style="display: flex; flex-direction: column; gap: 18px;">
      
      <!-- Filter & Search Bar -->
      <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 12px 16px; border-radius: 16px;">
        <div style="position: relative; flex-grow: 1; max-width: 380px;">
          <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
          <input type="text" id="guruSearchInput" placeholder="Cari nama guru atau mata pelajaran..." style="width: 100%; padding: 8px 14px 8px 38px; border-radius: 9999px; border: 1.5px solid #CBD5E1; font-size: 0.88rem; outline: none;" oninput="filterGuruList()">
        </div>
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
          <button type="button" class="guru-filter-tag active" onclick="setGuruFilter('semua', this)" style="border: 1px solid #0D6A56; background: #0D6A56; color: #FFF; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Semua (${GURU_DATABASE.length})</button>
          <button type="button" class="guru-filter-tag" onclick="setGuruFilter('mapel', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Guru Mapel</button>
          <button type="button" class="guru-filter-tag" onclick="setGuruFilter('kelas', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Guru Kelas</button>
          <button type="button" class="guru-filter-tag" onclick="setGuruFilter('tendik', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Tenaga Kependidikan</button>
        </div>
      </div>

      <!-- Grid of Teachers in Modal -->
      <div id="guruModalGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; max-height: 440px; overflow-y: auto; padding: 4px;">
        ${GURU_DATABASE.map(g => `
          <div onclick="openGuruDetailModal('${g.id}')" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 14px; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03);" onmouseover="this.style.borderColor='#0D6A56'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#E2E8F0'; this.style.transform='none'">
            <div style="width: 52px; height: 52px; border-radius: 14px; overflow: hidden; background: #F1F5F9; flex-shrink: 0; border: 1px solid #E2E8F0;">
              <img src="${g.avatar}" alt="${g.nama}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="overflow: hidden;">
              <div style="font-weight: 800; font-size: 0.92rem; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${g.nama}</div>
              <div style="font-size: 0.8rem; color: #0D6A56; font-weight: 700; margin-top: 2px;">${g.peran}</div>
              <div style="font-size: 0.74rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">${g.mapel}</div>
            </div>
          </div>
        `).join('')}
      </div>

      <div style="text-align: right; border-top: 1px solid #E2E8F0; padding-top: 12px;">
        <button onclick="document.getElementById('modal-close-btn').click()" style="background: #0D6A56; color: #FFFFFF; border: none; padding: 10px 24px; border-radius: 9999px; font-weight: 700; cursor: pointer; font-size: 0.9rem;">
          Tutup Direktori
        </button>
      </div>

    </div>
  `;

  window.currentGuruFilter = 'semua';
  modalOverlay.classList.add('active');
  document.body.style.overflow = 'hidden';
};

window.setGuruFilter = function(category, btn) {
  window.currentGuruFilter = category;
  document.querySelectorAll('.guru-filter-tag').forEach(b => {
    b.style.background = '#FFF';
    b.style.color = '#475569';
    b.style.borderColor = '#CBD5E1';
  });
  if (btn) {
    btn.style.background = '#0D6A56';
    btn.style.color = '#FFF';
    btn.style.borderColor = '#0D6A56';
  }
  filterGuruList();
};

window.filterGuruList = function() {
  const query = (document.getElementById('guruSearchInput')?.value || '').toLowerCase();
  const cat = window.currentGuruFilter || 'semua';
  const grid = document.getElementById('guruModalGrid');
  if (!grid) return;

  const filtered = GURU_DATABASE.filter(g => {
    const matchCat = (cat === 'semua') || (g.kategori === cat);
    const matchQuery = g.nama.toLowerCase().includes(query) || g.mapel.toLowerCase().includes(query) || g.peran.toLowerCase().includes(query);
    return matchCat && matchQuery;
  });

  if (filtered.length === 0) {
    grid.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; padding: 40px 10px; color: #64748B;">
      <i class="fas fa-search" style="font-size: 2rem; color: #CBD5E1; margin-bottom: 10px; display: block;"></i>
      Pendidik tidak ditemukan untuk kata kunci "${query}".
    </div>`;
    return;
  }

  grid.innerHTML = filtered.map(g => `
    <div onclick="openGuruDetailModal('${g.id}')" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 14px; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03);" onmouseover="this.style.borderColor='#0D6A56'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#E2E8F0'; this.style.transform='none'">
      <div style="width: 52px; height: 52px; border-radius: 14px; overflow: hidden; background: #F1F5F9; flex-shrink: 0; border: 1px solid #E2E8F0;">
        <img src="${g.avatar}" alt="${g.nama}" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
      <div style="overflow: hidden;">
        <div style="font-weight: 800; font-size: 0.92rem; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${g.nama}</div>
        <div style="font-size: 0.8rem; color: #0D6A56; font-weight: 700; margin-top: 2px;">${g.peran}</div>
        <div style="font-size: 0.74rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">${g.mapel}</div>
      </div>
    </div>
  `).join('');
};

// Auto initialize on DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initGuruSlider);
} else {
  initGuruSlider();
}


/* ==========================================================================
   DUNIA ILMU MIN 1 CIAMIS (MEDIA PEMBELAJARAN DIGITAL)
   ========================================================================== */

const DUNIA_ILMU_DATABASE = [
  {
    id: 'quran',
    nama: 'Al-Qur\'an Digital Kemenag',
    kategori: 'quran',
    kategoriLabel: 'Tahsin & Murottal 30 Juz',
    avatar: 'media-quran.svg',
    url: 'https://quran.kemenag.go.id/',
    deskripsi: 'Baca Al-Qur\'an lengkap dengan tajwid berwarna, terjemah Kemenag RI, tafsir ringkas, serta audio murottal merdu.'
  },
  {
    id: 'phet',
    nama: 'Lab Sains Virtual PhET',
    kategori: 'sains',
    kategoriLabel: 'Simulasi Sains & Matematika',
    avatar: 'media-phet.svg',
    url: 'https://phet.colorado.edu/in/',
    deskripsi: 'Laboratorium virtual interaktif fisika, kimia, dan matematika untuk memahami konsep sains secara visual dan praktis.'
  },
  {
    id: 'perpus',
    nama: 'E-Perpustakaan Madrasah (SIBI)',
    kategori: 'literasi',
    kategoriLabel: 'Buku Teks & Cerita Santri',
    avatar: 'media-perpus.svg',
    url: 'https://buku.kemdikbud.go.id/',
    deskripsi: 'Koleksi buku teks utama Kurikulum Merdeka, fabel edukatif, cerita bergambar, dan buku pengayaan literasi anak.'
  },
  {
    id: 'wordwall',
    nama: 'Game Edukasi Wordwall',
    kategori: 'game',
    kategoriLabel: 'Game Kuis & Evaluasi Belajar',
    avatar: 'media-wordwall.svg',
    url: 'https://wordwall.net/id',
    deskripsi: 'Pembelajaran interaktif yang menyenangkan lewat roda putar kata, teka-teki silang, mencocokkan kartu, dan labirin kuis.'
  },
  {
    id: 'kamus',
    nama: 'Kamus Digital 3 Bahasa',
    kategori: 'literasi',
    kategoriLabel: 'Arab - Inggris - Indonesia',
    avatar: 'media-kamus.svg',
    url: 'https://kamus.kemenag.go.id/',
    deskripsi: 'Portal kamus digital istilah agama Islam, mufrodat bahasa Arab, dan kosakata bahasa Inggris untuk pengayaan santri.'
  },
  {
    id: 'elearning',
    nama: 'E-Learning Madrasah Kemenag',
    kategori: 'quran',
    kategoriLabel: 'Kelas Virtual & Penilaian CBT',
    avatar: 'media-elearning.svg',
    url: 'https://elearning.kemenag.go.id/',
    deskripsi: 'Platform kelas daring resmi madrasah untuk penugasan digital, forum diskusi materi pelajaran, dan ujian CBT daring.'
  },
  {
    id: 'video',
    nama: 'Kanal Video Edukasi Sains',
    kategori: 'sains',
    kategoriLabel: 'Video Eksplorasi Visual',
    avatar: 'media-video.svg',
    url: 'https://www.youtube.com/@KEMENDIKBUD_RI',
    deskripsi: 'Tayangan animasi sains, eksperimen praktis, dan wawasan budaya nusantara yang dikemas menarik untuk anak usia madrasah.'
  },
  {
    id: 'quizizz',
    nama: 'Kuis Interaktif Quizizz',
    kategori: 'game',
    kategoriLabel: 'Tantangan Belajar Seru',
    avatar: 'media-quizizz.svg',
    url: 'https://quizizz.com/join',
    deskripsi: 'Adu ketangkasan menjawab soal harian madrasah secara real-time dengan avatar seru, musik ceria, dan peringkat kelas.'
  },
  {
    id: 'hadits',
    nama: 'Hadits & Doa Harian Santri',
    kategori: 'quran',
    kategoriLabel: 'Hadits Arbain & Adab Harian',
    avatar: 'media-hadits.svg',
    url: 'https://hadits.in/',
    deskripsi: 'Kumpulan hadits shahih tematik, doa-doa harian santri, adab islami, serta panduan ibadah praktis sehari-hari.'
  },
  {
    id: 'belajar',
    nama: 'Rumah Belajar Kemdikbud',
    kategori: 'sains',
    kategoriLabel: 'Bank Modul & Sumber Belajar',
    avatar: 'media-belajar.svg',
    url: 'https://belajar.kemdikbud.go.id/',
    deskripsi: 'Portal sumber belajar terbuka dengan modul ajar mandiri, bank soal latihan berkala, dan peta budaya nusantara.'
  }
];

function initDuniaIlmuSlider() {
  const track = document.getElementById('duniaIlmuTrack');
  const prevBtn = document.getElementById('duniaIlmuPrev');
  const nextBtn = document.getElementById('duniaIlmuNext');

  if (!track) return;

  const getScrollStep = () => {
    const card = track.querySelector('.guru-card');
    return card ? card.offsetWidth + 22 : 274;
  };

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      track.scrollBy({ left: getScrollStep() * 2, behavior: 'smooth' });
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      track.scrollBy({ left: -getScrollStep() * 2, behavior: 'smooth' });
    });
  }

  // Update disabled state
  const updateArrows = () => {
    if (!prevBtn || !nextBtn) return;
    const maxScroll = track.scrollWidth - track.clientWidth;
    if (track.scrollLeft <= 10) {
      prevBtn.disabled = true;
      prevBtn.classList.add('disabled');
    } else {
      prevBtn.disabled = false;
      prevBtn.classList.remove('disabled');
    }

    if (track.scrollLeft >= maxScroll - 10) {
      nextBtn.disabled = true;
      nextBtn.classList.add('disabled');
    } else {
      nextBtn.disabled = false;
      nextBtn.classList.remove('disabled');
    }
  };

  track.addEventListener('scroll', updateArrows, { passive: true });
  window.addEventListener('resize', updateArrows);
  updateArrows();

  // Mouse Drag to scroll (Bisa digeser-geser)
  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;
  let moved = false;

  track.addEventListener('mousedown', (e) => {
    isDown = true;
    moved = false;
    track.classList.add('dragging');
    startX = e.pageX - track.offsetLeft;
    scrollLeft = track.scrollLeft;
  });

  window.addEventListener('mouseup', () => {
    if (isDown) {
      isDown = false;
      track.classList.remove('dragging');
    }
  });

  track.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - track.offsetLeft;
    const walk = (x - startX) * 1.5;
    if (Math.abs(walk) > 5) moved = true;
    track.scrollLeft = scrollLeft - walk;
  });

  // Prevent link click if dragged
  track.querySelectorAll('.dunia-media-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (moved) {
        e.preventDefault();
      }
    });
  });
}

// Global modal opener for Full Dunia Ilmu Directory
window.openDuniaIlmuModal = function() {
  const modalOverlay = document.getElementById('modal-overlay');
  const modalTitle = document.getElementById('modal-title');
  const modalContent = document.getElementById('modal-content-area');

  if (!modalOverlay || !modalContent) return;

  if (modalTitle) modalTitle.innerHTML = `<i class="fas fa-compass" style="color: #0D6A56; margin-right: 8px;"></i> Katalog Media Pembelajaran Digital MIN 1 Ciamis`;

  modalContent.innerHTML = `
    <div style="display: flex; flex-direction: column; gap: 18px;">
      
      <!-- Filter & Search Bar -->
      <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 12px 16px; border-radius: 16px;">
        <div style="position: relative; flex-grow: 1; max-width: 380px;">
          <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
          <input type="text" id="duniaSearchInput" placeholder="Cari media belajar, e-book, atau lab virtual..." style="width: 100%; padding: 8px 14px 8px 38px; border-radius: 9999px; border: 1.5px solid #CBD5E1; font-size: 0.88rem; outline: none;" oninput="filterDuniaList()">
        </div>
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
          <button type="button" class="dunia-filter-tag active" onclick="setDuniaFilter('semua', this)" style="border: 1px solid #0D6A56; background: #0D6A56; color: #FFF; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Semua (${DUNIA_ILMU_DATABASE.length})</button>
          <button type="button" class="dunia-filter-tag" onclick="setDuniaFilter('quran', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Al-Qur'an &amp; Hadits</button>
          <button type="button" class="dunia-filter-tag" onclick="setDuniaFilter('sains', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Sains &amp; Simulasi</button>
          <button type="button" class="dunia-filter-tag" onclick="setDuniaFilter('literasi', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Buku &amp; Bahasa</button>
          <button type="button" class="dunia-filter-tag" onclick="setDuniaFilter('game', this)" style="border: 1px solid #CBD5E1; background: #FFF; color: #475569; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">Game &amp; Kuis</button>
        </div>
      </div>

      <!-- Grid of Media in Modal -->
      <div id="duniaModalGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; max-height: 440px; overflow-y: auto; padding: 4px;">
        ${DUNIA_ILMU_DATABASE.map(item => `
          <a href="${item.url}" target="_blank" rel="noopener noreferrer" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03);" onmouseover="this.style.borderColor='#0D6A56'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 24px rgba(13,106,86,0.12)'" onmouseout="this.style.borderColor='#E2E8F0'; this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.03)'">
            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="width: 54px; height: 54px; border-radius: 14px; overflow: hidden; background: #F8FAFC; flex-shrink: 0; border: 1px solid #E2E8F0;">
                <img src="${item.avatar}" alt="${item.nama}" style="width: 100%; height: 100%; object-fit: cover;">
              </div>
              <div style="overflow: hidden;">
                <div style="font-weight: 800; font-size: 0.96rem; color: #0F172A; line-height: 1.3;">${item.nama}</div>
                <div style="font-size: 0.78rem; color: #0D6A56; font-weight: 700; margin-top: 2px;">${item.kategoriLabel}</div>
              </div>
            </div>
            <p style="font-size: 0.82rem; color: #64748B; margin: 0; line-height: 1.45;">${item.deskripsi}</p>
            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #F1F5F9; padding-top: 8px;">
              <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;"><i class="fas fa-globe"></i> Platform Resmi</span>
              <span style="font-size: 0.82rem; font-weight: 700; color: #0D6A56; display: flex; align-items: center; gap: 4px;">
                Buka Media <i class="fas fa-external-link-alt" style="font-size: 0.75rem;"></i>
              </span>
            </div>
          </a>
        `).join('')}
      </div>

      <div style="text-align: right; border-top: 1px solid #E2E8F0; padding-top: 12px;">
        <button onclick="document.getElementById('modal-close-btn').click()" style="background: #0D6A56; color: #FFFFFF; border: none; padding: 10px 24px; border-radius: 9999px; font-weight: 700; cursor: pointer; font-size: 0.9rem;">
          Tutup Katalog
        </button>
      </div>

    </div>
  `;

  window.currentDuniaFilter = 'semua';
  modalOverlay.classList.add('active');
  document.body.style.overflow = 'hidden';
};

window.setDuniaFilter = function(category, btn) {
  window.currentDuniaFilter = category;
  document.querySelectorAll('.dunia-filter-tag').forEach(b => {
    b.style.background = '#FFF';
    b.style.color = '#475569';
    b.style.borderColor = '#CBD5E1';
  });
  if (btn) {
    btn.style.background = '#0D6A56';
    btn.style.color = '#FFF';
    btn.style.borderColor = '#0D6A56';
  }
  filterDuniaList();
};

window.filterDuniaList = function() {
  const query = (document.getElementById('duniaSearchInput')?.value || '').toLowerCase();
  const cat = window.currentDuniaFilter || 'semua';
  const grid = document.getElementById('duniaModalGrid');
  if (!grid) return;

  const filtered = DUNIA_ILMU_DATABASE.filter(item => {
    const matchCat = (cat === 'semua') || (item.kategori === cat);
    const matchQuery = item.nama.toLowerCase().includes(query) || item.kategoriLabel.toLowerCase().includes(query) || item.deskripsi.toLowerCase().includes(query);
    return matchCat && matchQuery;
  });

  if (filtered.length === 0) {
    grid.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; padding: 40px 10px; color: #64748B;">
      <i class="fas fa-search" style="font-size: 2rem; color: #CBD5E1; margin-bottom: 10px; display: block;"></i>
      Media pembelajaran tidak ditemukan untuk kata kunci "${query}".
    </div>`;
    return;
  }

  grid.innerHTML = filtered.map(item => `
    <a href="${item.url}" target="_blank" rel="noopener noreferrer" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; gap: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03);" onmouseover="this.style.borderColor='#0D6A56'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 24px rgba(13,106,86,0.12)'" onmouseout="this.style.borderColor='#E2E8F0'; this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.03)'">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 54px; height: 54px; border-radius: 14px; overflow: hidden; background: #F8FAFC; flex-shrink: 0; border: 1px solid #E2E8F0;">
          <img src="${item.avatar}" alt="${item.nama}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="overflow: hidden;">
          <div style="font-weight: 800; font-size: 0.96rem; color: #0F172A; line-height: 1.3;">${item.nama}</div>
          <div style="font-size: 0.78rem; color: #0D6A56; font-weight: 700; margin-top: 2px;">${item.kategoriLabel}</div>
        </div>
      </div>
      <p style="font-size: 0.82rem; color: #64748B; margin: 0; line-height: 1.45;">${item.deskripsi}</p>
      <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #F1F5F9; padding-top: 8px;">
        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;"><i class="fas fa-globe"></i> Platform Resmi</span>
        <span style="font-size: 0.82rem; font-weight: 700; color: #0D6A56; display: flex; align-items: center; gap: 4px;">
          Buka Media <i class="fas fa-external-link-alt" style="font-size: 0.75rem;"></i>
        </span>
      </div>
    </a>
  `).join('');
};

// Auto initialize on DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initDuniaIlmuSlider);
} else {
  initDuniaIlmuSlider();
}


/* ==========================================================================
   MOBILE BOTTOM NAV - SCROLL SPY & ACTIVE HIGHLIGHT
   ========================================================================== */
(function() {
  function initMobileBottomNav() {
    const nav = document.getElementById('mobileBottomNav');
    if (!nav) return;

    const items = nav.querySelectorAll('.mobile-bottom-nav__item[data-section]');
    const homeItem = nav.querySelector('.mobile-bottom-nav__item:not([data-section])');
    const sectionIds = [];

    items.forEach(item => {
      const sectionId = item.getAttribute('data-section');
      if (sectionId) sectionIds.push(sectionId);
    });

    // Smooth scroll ke section saat diklik (hanya jika sudah di halaman home)
    items.forEach(item => {
      item.addEventListener('click', function(e) {
        const sectionId = this.getAttribute('data-section');
        const target = document.getElementById(sectionId);
        if (target) {
          e.preventDefault();
          const headerOffset = 80;
          const elementPosition = target.getBoundingClientRect().top + window.scrollY;
          window.scrollTo({
            top: elementPosition - headerOffset,
            behavior: 'smooth'
          });
        }
      });
    });

    // Scroll-spy: update active state berdasarkan posisi scroll
    function updateActiveNav() {
      const scrollPos = window.scrollY + window.innerHeight / 3;
      let activeSection = null;

      sectionIds.forEach(id => {
        const section = document.getElementById(id);
        if (section) {
          const top = section.offsetTop - 100;
          const bottom = top + section.offsetHeight;
          if (scrollPos >= top && scrollPos < bottom) {
            activeSection = id;
          }
        }
      });

      // Reset semua
      items.forEach(item => item.classList.remove('active'));
      if (homeItem) homeItem.classList.remove('active');

      if (activeSection) {
        const activeItem = nav.querySelector(`[data-section="${activeSection}"]`);
        if (activeItem) activeItem.classList.add('active');
      } else {
        // Jika belum scroll ke section manapun, Home aktif
        if (homeItem) homeItem.classList.add('active');
      }
    }

    // Hanya jalankan scroll-spy di halaman home
    if (document.getElementById('beranda') || window.location.pathname === '/') {
      let ticking = false;
      window.addEventListener('scroll', function() {
        if (!ticking) {
          window.requestAnimationFrame(function() {
            updateActiveNav();
            ticking = false;
          });
          ticking = true;
        }
      }, { passive: true });

      // Set initial state
      updateActiveNav();
    }

    // Bottom nav selalu tampil (tidak di-hide saat scroll)
  }

  // Init on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileBottomNav);
  } else {
    initMobileBottomNav();
  }
})();
