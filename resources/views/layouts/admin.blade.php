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