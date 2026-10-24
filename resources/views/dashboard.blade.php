<!-- Ini ngasih tau Laravel: "Tolong pakai struktur admin.blade.php" -->
@extends('layouts.admin')

<!-- Ini ngisi tag <title> di atas -->
@section('title', 'Dashboard Utama')

<!-- Ini ngisi bagian @yield('content') di admin.blade.php -->
@section('content')
    
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Dashboard</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">MIN 1 Ciamis</li>
        </ul>
    </div>

    <!-- Nanti kamu tinggal COPY isi chart / card dari index-2.php dan PASTE di sini -->
    <div class="row gy-4">
        <div class="col-12">
            <div class="card p-4">
                <h4>Selamat Datang, Super Admin!</h4>
                <p>Sistem Informasi MIN 1 Ciamis sedang dalam tahap pembangunan.</p>
            </div>
        </div>
    </div>

@endsection

<!-- Ini ngisi bagian @stack('scripts') di admin.blade.php -->
@push('scripts')
    <script src="{{ asset('backend/assets/js/homeTwoChart.js') }}"></script>
@endpush