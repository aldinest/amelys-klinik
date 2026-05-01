<nav class="main-header navbar navbar-expand {{ auth()->user()->role === 'pasien' ? 'navbar-light bg-white shadow-sm border-bottom-0' : 'navbar-white navbar-light' }}" 
     style="{{ auth()->user()->role === 'pasien' ? 'margin-left: 0 !important;' : '' }}">
    
    <ul class="navbar-nav">
        {{-- Tampilkan Hamburger Menu HANYA untuk Admin & Pengurus --}}
        @if(auth()->user()->role !== 'pasien')
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @else
        {{-- Brand Logo untuk Pasien di Mobile/Desktop karena Sidebar Off --}}
        <li class="nav-item d-flex align-items-center ml-2">
            <span class="brand-text font-weight-bold text-primary" style="font-size: 1.2rem;">AMELYS <span class="text-dark">KLINIK</span></span>
        </li>
        @endif

        {{-- Link Home Berdasarkan Role --}}
        <li class="nav-item d-none d-sm-inline-block ml-3">
            @php
                $dashboardRoute = match(auth()->user()->role) {
                    'admin' => 'admin.dashboard',
                    'pengurus' => 'pengurus.dashboard',
                    'pasien' => 'pasien.dashboard',
                    default => '/',
                };
            @endphp
            <a href="{{ Route::has($dashboardRoute) ? route($dashboardRoute) : '/' }}" class="nav-link font-weight-medium">Home</a>
        </li>

        {{-- Navigasi Tambahan Desktop Khusus Pasien (Horizontal Menu) --}}
        @if(auth()->user()->role === 'pasien')
        <li class="nav-item d-none d-md-inline-block">
            <a href="{{ url('/pasien/reservations') }}" class="nav-link">Reservasi</a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <a href="{{ url('/pasien/medical-records') }}" class="nav-link">Riwayat Medis</a>
        </li>
        @endif
    </ul>

    <ul class="navbar-nav ml-auto">
        {{-- User Dropdown --}}
        <li class="nav-item dropdown flex items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div class="max-w-[100px] truncate md:max-w-none font-weight-bold">
                            {{ Auth::user()->name }}
                        </div>

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        <i class="fas fa-user-edit mr-2 text-muted"></i> {{ __('Profile') }}
                    </x-dropdown-link>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" class="text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </li>

        {{-- Fullscreen Widget (Sembunyikan di Mobile Pasien) --}}
        <li class="nav-item {{ auth()->user()->role === 'pasien' ? 'd-none d-md-block' : '' }}">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>

{{-- KHUSUS PASIEN: CSS UNTUK MATIKAN SIDEBAR DI LAYOUT UTAMA --}}
@if(auth()->user()->role === 'pasien')
<style>
    /* Paksa Sidebar hilang untuk Pasien */
    .main-sidebar { display: none !important; }
    body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper, 
    body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer, 
    body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
        margin-left: 0 !important;
    }
</style>
@endif