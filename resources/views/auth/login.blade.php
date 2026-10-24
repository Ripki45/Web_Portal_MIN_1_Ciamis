<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Informasi MIN 1 Ciamis</title>
  
  <link rel="icon" type="image/png" href="{{ asset('backend/assets/images/favicon.png') }}" sizes="16x16">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/remixicon.css') }}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/bootstrap.min.css') }}">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/apexcharts.css') }}">
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/dataTables.min.css') }}">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/editor-katex.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/editor.atom-one-dark.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/editor.quill.snow.css') }}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/flatpickr.min.css') }}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/full-calendar.css') }}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/magnific-popup.css') }}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/slick.css') }}">
  <!-- prism css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/prism.css') }}">
  <!-- file upload css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/file-upload.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/lib/audioplayer.css') }}">
  <!-- main css -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
</head>

<body class="position-relative z-1">

  {{-- Kita matikan sementara theme-customization untuk halaman login --}}
  {{-- @include('layouts._theme-customization') --}}

  <img src="{{ asset('backend/assets/images/body-bg.png') }}" alt="BG Image" class="body-bg position-absolute top-0 start-0 h-100 w-100 z-n1">

  <section class="auth bg-base d-flex flex-wrap">  
      <div class="auth-left d-lg-block d-none">
          <div class="d-flex align-items-center flex-column h-100 justify-content-center">
              <img src="{{ asset('backend/assets/images/auth/auth-img.png') }}" alt="Image">
          </div>
      </div>
      <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
          <div class="max-w-464-px mx-auto w-100">
              <div>
                  <a href="{{ url('/') }}" class="mb-40 max-w-320-px">
                      <!-- Kamu bisa ganti logo.png ini dengan logo MIN 1 Ciamis nanti -->
                      <img src="{{ asset('backend/assets/images/logo_w_name.jpg') }}" alt="Image">
                  </a>
                  <h2 class="h4 mb-12">Sign In ke Dashboard</h2>
                  <p class="mb-32 text-secondary-light text-lg">Selamat datang! Silakan masukkan detail akun Anda.</p>
              </div>

              <!-- Pengganti x-auth-session-status -->
              @if (session('status'))
                  <div class="alert alert-success mb-20" role="alert">
                      {{ session('status') }}
                  </div>
              @endif
              
              <form method="POST" action="{{ route('login') }}">
                  @csrf 

                  <div class="icon-field mb-16">
                      <span class="icon top-50 translate-middle-y">
                          <iconify-icon icon="mage:email"></iconify-icon>
                      </span>
                      <input type="email" name="email" value="{{ old('email') }}" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email" required autofocus>
                  </div>
                  @error('email')
                      <span class="text-danger text-sm mb-16 d-block" style="margin-top: -10px;">{{ $message }}</span>
                  @enderror

                  <div class="position-relative mb-20">
                      <div class="icon-field">
                          <span class="icon top-50 translate-middle-y">
                              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                          </span> 
                          <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Password" required>
                      </div>
                      <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                  </div>
                  @error('password')
                      <span class="text-danger text-sm mb-20 d-block" style="margin-top: -15px;">{{ $message }}</span>
                  @enderror

                  <div class="">
                      <div class="d-flex justify-content-between gap-2">
                          <div class="form-check style-check d-flex align-items-center">
                              <input class="form-check-input border border-neutral-300" type="checkbox" name="remember" id="remeber">
                              <label class="form-check-label" for="remeber">Ingat Saya </label>
                          </div>
                          @if (Route::has('password.request'))
                              <a href="{{ route('password.request') }}" class="text-primary-600 fw-medium">Lupa Password?</a>
                          @endif
                      </div>
                  </div>

                  <button type="submit" class="btn btn-success text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"> Sign In</button>
                  
                  <div class="mt-32 text-center text-sm">
                      <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-primary-600 fw-semibold">Sign Up</a></p>
                  </div>
              </form>
          </div>
      </div>
  </section>

  <!-- jQuery library js -->
  <script src="{{ asset('backend/assets/js/lib/jquery-3.7.1.min.js') }}"></script>
  <!-- Bootstrap js -->
  <script src="{{ asset('backend/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
  <!-- Apex Chart js -->
  <script src="{{ asset('backend/assets/js/lib/apexcharts.min.js') }}"></script>
  <!-- Data Table js -->
  <script src="{{ asset('backend/assets/js/lib/dataTables.min.js') }}"></script>
  <!-- Iconify Font js -->
  <script src="{{ asset('backend/assets/js/lib/iconify-icon.min.js') }}"></script>
  <!-- phosphor -->
  <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
  <!-- jQuery UI js -->
  <script src="{{ asset('backend/assets/js/lib/jquery-ui.min.js') }}"></script>
  <!-- Vector Map js -->
  <script src="{{ asset('backend/assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
  <!-- Popup js -->
  <script src="{{ asset('backend/assets/js/lib/magnifc-popup.min.js') }}"></script>
  <!-- Slick Slider js -->
  <script src="{{ asset('backend/assets/js/lib/slick.min.js') }}"></script>
  <!-- prism js -->
  <script src="{{ asset('backend/assets/js/lib/prism.js') }}"></script>
  <!-- file upload js -->
  <script src="{{ asset('backend/assets/js/lib/file-upload.js') }}"></script>
  <!-- audioplayer -->
  <script src="{{ asset('backend/assets/js/lib/audioplayer.js') }}"></script>
  <!-- main js -->
  <script src="{{ asset('backend/assets/js/app.js') }}"></script>

  <script>
    function getCssVar(name) {
      return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    }
    var primaryColor = getCssVar('--primary-600');
    var trackColor = getCssVar('--primary-100');     // e.g. #EEEDFE
    
    // ================== Password Show Hide Js Start ==========
    function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on('click', function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    initializePasswordToggle('.toggle-password');
    // ========================= Password Show Hide Js End ===========================
  </script>

</body>
</html>