<footer>
    <div class="section-footer-2 overflow-hidden">
        <div class="container">
            <div class="row g-5 sec-padding">
                <div class="col-lg-4 pe-lg-5">
                    <div class="d-flex align-items-center gap-2">
                        <a class="dark-mode-invert" href="{{ route('home') }}">
                            <img src="{{ asset('frontend/assets/imgs/template/logo/logo-dark.svg') }}" alt="MIN 1 Ciamis">
                        </a>
                        <p class="fs-7 m-0 fw-bold">MIN 1 Ciamis</p>
                    </div>
                    <p class="fs-7 text-dark mt-4">
                        Madrasah Ibtidaiyah Negeri 1 Ciamis berkomitmen mencetak generasi berakhlak mulia, unggul dalam
                        prestasi akademik, sains, keagamaan, dan literasi digital.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div class="row g-4 justify-content-between">
                        <div class="col-lg-4 col-md-4 col-6">
                            <h6 class="mb-3">Navigasi Utama</h6>
                            <ul class="list-unstyled ps-0">
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="#sambutan">Sambutan Kepala</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="#berita-terbaru">Berita Terbaru</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="#direktori-guru">Guru & Staf</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="#ekstrakurikuler">Ekstrakurikuler</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-6">
                            <h6 class="mb-3">Layanan Digital</h6>
                            <ul class="list-unstyled ps-0">
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="{{ route('presensi.scanner') }}">Portal Scanner QR</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="{{ route('login') }}">Login SIAKAD / Guru</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="https://emis.kemenag.go.id" target="_blank">EMIS Kemenag</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="https://rdm.kemenag.go.id" target="_blank">Rapor Digital (RDM)</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <h6 class="mb-3">Informasi</h6>
                            <ul class="list-unstyled ps-0">
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="#program-unggulan">Program Unggulan</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1"
                                        href="#prestasi">Prestasi Siswa & Guru</a></li>
                                <li class="mb-3"><a class="text-500 hover-dark button-effect-1" href="#galeri">Galeri
                                        Dokumentasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div
                        class="bottom-footer2 d-flex flex-wrap justify-content-lg-between justify-content-center align-items-center gap-lg-5 gap-3">
                        <p class="text-500 m-0">{{ date('Y') }} Copyright &copy; <span class="text-dark fw-bold">MIN
                                1 Ciamis</span>. All Rights Reserved.</p>
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-lg-5 gap-4">
                            <a href="{{ route('login') }}"
                                class="text-500 hover-dark d-block fs-7 button-effect-1">Portal Pegawai</a>
                            <a href="{{ route('presensi.scanner') }}"
                                class="text-500 hover-dark d-block fs-7 button-effect-1">Presensi Siswa</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
