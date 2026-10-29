<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') - Sistem Informasi MIN 1 Ciamis</title>
  
  <link rel="icon" type="image/png" href="{{ asset('backend/assets/images/favicon.png') }}" sizes="16x16">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/remixicon.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/apexcharts.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/dataTables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/editor-katex.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/editor.atom-one-dark.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/editor.quill.snow.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/flatpickr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/full-calendar.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/magnific-popup.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/slick.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/prism.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/file-upload.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/audioplayer.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">

  <!-- Override Warna Biru menjadi Hijau MIN 1 Ciamis & Custom UI -->
  <style>
    :root, [data-theme="light"], [data-theme="dark"] {
      --primary-50:  #f0fdf4 !important;
      --primary-100: #dcfce7 !important;
      --primary-200: #bbf7d0 !important;
      --primary-300: #86efac !important;
      --primary-400: #4ade80 !important;
      --primary-500: #22c55e !important;
      --primary-600: #16a34a !important;
      --primary-700: #15803d !important;
      --primary-800: #166534 !important;
      --primary-900: #14532d !important;
    }
    /* 1. Buat area body dashboard transparan agar bias warna tidak terpotong di bawah navbar */
    .dashboard-main,
    .dashboard-main-body {
        background: transparent !important;
        position: relative;
        z-index: 1;
    }

    /* 2. Pancarkan bias warna kuning hangat & biru/hijau lembut ke seluruh latar belakang */
    /* 2. Bias Kuning Hangat Full di Atas -> Melebur Halus ke Putih Bersih di Bawah */
    body {
        background-color: #ffffff !important;
        background-image: linear-gradient(
            180deg,
            rgba(253, 230, 138, 0.55) 0%,    /* Kuning hangat merata full di bagian paling atas */
            rgba(254, 243, 199, 0.35) 22%,   /* Kuning lembut di area judul & atas kartu */
            rgba(255, 251, 235, 0.15) 48%,   /* Transisi krem tipis di bagian tengah */
            #ffffff 75%,                     /* Masuk ke putih bersih */
            #ffffff 100%                     /* Putih bersih penuh sampai bagian paling bawah */
        ) !important;
        background-attachment: fixed !important;
        min-height: 100vh;
    }

    /* Merapikan & Mengecilkan Ukuran SweetAlert2 */
    .swal2-popup {
      width: 24em !important;
      padding: 1.25rem !important;
      border-radius: 12px !important;
    }
    .swal2-icon {
      transform: scale(0.8);
      margin: 0.5em auto 0.2em !important;
    }
    .swal2-title {
      font-size: 1.25rem !important;
      font-weight: 600 !important;
      padding-top: 0.2em !important;
    }
    .swal2-html-container {
      font-size: 0.9rem !important;
      margin: 0.5em 1em 0 !important;
    }
    .swal2-actions {
      margin-top: 1.25rem !important;
      gap: 8px;
    }
    .swal2-styled {
      font-size: 0.875rem !important;
      padding: 8px 18px !important;
      border-radius: 8px !important;
    }

    /* Kursor Tangan Saat Menggeser Tabel */
    .table-responsive {
        cursor: grab;
    }
    .table-responsive.active-drag {
        cursor: grabbing !important;
        user-select: none;
    }
    /* 1. Perbaiki Ketebalan Navbar (Atas-Bawah Seimbang) & Kunci Agar Tidak Ditembus Konten */
    .navbar-header {
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
        padding-top: 14px !important;
        padding-bottom: 14px !important;
        min-height: 76px !important;
        display: flex !important;
        align-items: center !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
    }

    /* Warna Navbar saat Mode Terang (Menyatu dengan Bias Kuning Hangat) */
    html[data-theme="light"] .navbar-header {
        background: rgba(254, 243, 199, 0.65) !important;
        border-bottom: 1px solid rgba(253, 230, 138, 0.5) !important;
    }

    /* Warna Navbar saat Mode Malam (Gelap Elegan & Rapi) */
    html[data-theme="dark"] .navbar-header {
        background: rgba(15, 23, 42, 0.88) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* 2. Kontainer Utama Dashboard */
    .dashboard-main,
    .dashboard-main-body {
        background: transparent !important;
        position: relative;
        z-index: 1;
    }

    /* 3. Background Bias Kuning Hangat ke Putih (KHUSUS MODE TERANG) */
    html[data-theme="light"] body {
        background-color: #ffffff !important;
        background-image: linear-gradient(
            180deg,
            rgba(253, 230, 138, 0.55) 0%,
            rgba(254, 243, 199, 0.35) 22%,
            rgba(255, 251, 235, 0.15) 48%,
            #ffffff 75%,
            #ffffff 100%
        ) !important;
        background-attachment: fixed !important;
        min-height: 100vh;
    }

    /* 4. Background Gelap Berbias Halus (KHUSUS MODE MALAM / DARK MODE) */
    html[data-theme="dark"] body {
        background-color: #0f172a !important;
        background-image: linear-gradient(
            180deg,
            rgba(22, 101, 52, 0.25) 0%,
            rgba(30, 41, 59, 0.6) 30%,
            #0f172a 75%,
            #0f172a 100%
        ) !important;
        background-attachment: fixed !important;
        min-height: 100vh;
    }

    /* ==========================================
      PERBAIKAN POSISI NAVBAR KANAN
      ========================================== */
    /* Navbar menggunakan seluruh lebar yang tersedia */
    .navbar-header {
        width: 100% !important;
        box-sizing: border-box !important;
    }
    /* Kontainer isi navbar */
    .navbar-header > .row,
    .navbar-header > .container-fluid {
        width: 100% !important;
    }
    /* Dorong kelompok ikon dan profil ke kanan */
    .navbar-header .navbar-header-right {
        margin-left: auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
    }
    /* Jaga jarak antar ikon tetap rapi */
    .navbar-header .navbar-header-right .d-flex {
        align-items: center !important;
    }
    /* Pastikan profil tidak terpotong */
    .navbar-header .navbar-header-right .dropdown {
        flex-shrink: 0;
    }
  </style>
</head>

<body class="position-relative z-1">

  <!-- Sidebar -->
  @include('layouts.sidebar')

  <main class="dashboard-main">
    <!-- Navbar / Header Atas -->
    @include('layouts.navbar')

    <!-- Konten Utama Halaman -->
    <div class="dashboard-main-body">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('layouts.footer')
  </main>

  <!-- Semua JS -->
  <script src="{{ asset('backend/assets/js/lib/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/apexcharts.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/iconify-icon.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
  <script src="{{ asset('backend/assets/js/lib/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/magnifc-popup.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/slick.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/prism.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/file-upload.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/audioplayer.js') }}"></script>
  <script src="{{ asset('backend/assets/js/app.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Script Global SweetAlert (Notifikasi & Konfirmasi Hapus) -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#16a34a',
                timer: 2500,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                confirmButtonColor: '#dc2626'
            });
        @endif

        document.body.addEventListener('submit', function (e) {
            if (e.target && e.target.classList.contains('form-delete')) {
                e.preventDefault();
                const form = e.target;
                const itemName = form.getAttribute('data-nama') || 'data ini';

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    html: `Data <b>"${itemName}"</b> yang dihapus tidak dapat dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
  </script>

  <!-- Fitur Global: Klik Tahan & Geser Tabel ke Kanan/Kiri dengan Mouse -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sliders = document.querySelectorAll('.table-responsive');
        sliders.forEach(slider => {
            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                if (['BUTTON', 'INPUT', 'SELECT', 'A', 'LABEL'].includes(e.target.tagName)) return;
                isDown = true;
                slider.classList.add('active-drag');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active-drag');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active-drag');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 1.5;
                slider.scrollLeft = scrollLeft - walk;
            });
        });
    });
  </script>

  @stack('scripts')

</body>
</html>