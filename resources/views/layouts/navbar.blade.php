<div class="navbar-header">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
          <button type="button" class="twin-collapse line-height-1 sidebar-toggle" aria-label="Collapse menu">
              <i class="ph ph-arrow-u-up-left icon non-active"></i>
              <i class="ph ph-arrow-u-up-right icon active"></i>
          </button>
      
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" class="bg-base" placeholder="Search">
          <button type="submit" class="icon">
            <iconify-icon icon="ion:search-outline" class=""></iconify-icon>
          </button>
        </form>
      </div>
    </div>
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <button type="button" data-theme-toggle
          class="w-44-px h-44-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center bg-base"></button>
   

        <div class="dropdown">
          <button
            class="has-indicator w-44-px h-44-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center bg-base"
            type="button" data-bs-toggle="dropdown">
            <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
          </button>
          <div class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div
              class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h2 class="h6 text-lg text-primary-light fw-semibold mb-0">Message</h2>
              </div>
              <span
                class="text-primary-600 fw-semibold text-lg w-44-px h-44-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
            </div>

            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">

              <a href="javascript:void(0)"
                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                  <span class="w-44-px h-44-px rounded-circle flex-shrink-0 position-relative">
                    <img src="{{ asset('backend/assets/images/notification/profile-3.png') }}" alt="Image">
                    <span class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                  </span>
                  <div>
                    <h2 class="h6 text-md fw-semibold mb-4">Kathryn Murphy</h2>
                    <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...</p>
                  </div>
                </div>
                <div class="d-flex flex-column align-items-end">
                  <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                  <span
                    class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                </div>
              </a>

            </div>
            <div class="text-center py-12 px-16">
              <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Message</a>
            </div>
          </div>
        </div><!-- Message dropdown end -->

        <div class="dropdown">
          <button
            class="has-indicator w-44-px h-44-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center bg-base"
            type="button" data-bs-toggle="dropdown">
            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
          </button>
          <div class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div
              class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h2 class="h6 text-lg text-primary-light fw-semibold mb-0">Notifications</h2>
              </div>
              <span
                class="text-primary-600 fw-semibold text-lg w-44-px h-44-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
            </div>

            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
              <a href="javascript:void(0)"
                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                  <span
                    class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                    <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
                  </span>
                  <div>
                    <h2 class="h6 text-md fw-semibold mb-4">Congratulations</h2>
                    <p class="mb-0 text-sm text-secondary-light text-w-200-px">Your profile has been Verified. Your
                      profile has been Verified</p>
                  </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
              </a>

              <a href="javascript:void(0)"
                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                  <span
                    class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                    <img src="{{ asset('backend/assets/images/notification/profile-1.png') }}" alt="Image">
                  </span>
                  <div>
                    <h2 class="h6 text-md fw-semibold mb-4">Ronald Richards</h2>
                    <p class="mb-0 text-sm text-secondary-light text-w-200-px">You can stitch between artboards</p>
                  </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
              </a>


            </div>

            <div class="text-center py-12 px-16">
              <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Notification</a>
            </div>

          </div>
        </div><!-- Notification dropdown end -->

        <div class="dropdown">
          <button class="d-flex justify-content-center align-items-center rounded-circle bg-transparent" type="button"
            data-bs-toggle="dropdown">
            <span class="d-flex align-items-center gap-12">
                <span class="">
                    <img src="{{ (Auth::user()->gtk && Auth::user()->gtk->foto) ? asset('storage/' . Auth::user()->gtk->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=16a34a&color=fff&bold=true' }}" 
                        alt="Avatar" 
                        class="w-44-px h-44-px object-fit-cover rounded-circle">
                </span>
                <span class="d-sm-block d-none text-start">
                    <span class="text-lg text-primary-light fw-semibold mb-2 d-block">
                        {{ Auth::user()->gtk->nama_lengkap ?? Auth::user()->name }}
                    </span>
                    <span class="text-secondary-light fw-medium text-sm">
                        {{ Auth::user()->gtk->jabatan->nama_jabatan ?? ucfirst(Auth::user()->role ?? 'Super Admin') }}
                    </span>
                </span>
                <span class="text-neutral-500">
                    <i class="ph-bold ph-caret-down"></i>
                </span>
            </span>
          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm">
            <div
              class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h2 class="h6 text-lg text-primary-light fw-semibold mb-2"> {{ Auth::user()->name }}</h2>
                <span class="text-secondary-light fw-medium text-sm">{{ ucfirst(Auth::user()->role ?? 'Super Admin') }}</span>
              </div>
              <button type="button" class="hover-text-danger">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
              </button>
            </div>
            <ul class="to-top-list">
              <li>
                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                  href="{{ route('profile.edit') }}">
                  <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> Profil Saya
                </a>
              </li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Keluar
                  </a>
                </form>
              </li>
            </ul>
          </div>
        </div><!-- Profile dropdown end -->
      </div>
    </div>
  </div>
</div>