<nav class="main-header navbar navbar-expand {{ auth()->user()->role === 'pasien' ? 'navbar-light bg-white shadow-sm border-bottom-0' : 'navbar-white navbar-light' }}" 
     style="{{ auth()->user()->role === 'pasien' ? 'margin-left: 0 !important;' : '' }}">
    
    <ul class="navbar-nav">
        {{-- FUNGSI BAWAAN: Push Menu --}}
        @if(auth()->user()->role !== 'pasien')
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        @else
            <li class="nav-item d-flex align-items-center ml-3">
                <span class="brand-text font-weight-bold text-primary" style="font-size: 1.2rem;">AMELYS <span class="text-dark">KLINIK</span></span>
            </li>
        @endif

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

        @if(auth()->user()->role === 'pasien')
            <li class="nav-item d-none d-md-inline-block"><a href="{{ url('/pasien/profile') }}" class="nav-link">Profil Saya</a></li>
            <li class="nav-item d-none d-md-inline-block"><a href="{{ url('/pasien/reservations') }}" class="nav-link">Reservasi</a></li>
            <li class="nav-item d-none d-md-inline-block"><a href="{{ url('/pasien/medical-records') }}" class="nav-link">Riwayat Medis</a></li>
        @endif
    </ul>

    <ul class="navbar-nav ml-auto">
        {{-- NOTIFICATION DROPDOWN --}}
        <li class="nav-item dropdown">
            {{-- Tambahkan class 'mark-as-read' di sini agar script terpicu --}}
            <a class="nav-link mark-as-read" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">{{ auth()->user()->unreadNotifications->count() }} Notifikasi Baru</span>
                
                @forelse(auth()->user()->unreadNotifications as $notification)
                    @php
                        $actionUrl = $notification->data['action_url'] ?? null;
                        $isClickable = !empty($actionUrl) && !str_contains($actionUrl, 'localhost');
                    @endphp
                    <div class="dropdown-divider"></div>
                    @if($isClickable)
                        <a href="{{ $actionUrl }}" class="dropdown-item">
                    @else
                        <div class="dropdown-item text-muted">
                    @endif
                            <div class="d-flex align-items-start">
                                <i class="fas fa-calendar-check mr-2 mt-1 text-primary"></i>
                                <div style="white-space: normal; line-height: 1.3;">
                                    <span class="font-weight-bold">{{ $notification->data['pesan'] }}</span>
                                    <br>
                                    <small class="text-muted">{{ $notification->data['waktu'] }}</small>
                                </div>
                            </div>
                    @if($isClickable)
                        </a>
                    @else
                        </div>
                    @endif
                @empty
                    <div class="dropdown-item text-center text-muted">Tidak ada notifikasi baru</div>
                @endforelse
                
                <div class="dropdown-divider"></div>
                @php
                    $notificationRoute = auth()->user()->role . '.notifications.index';
                    if (!Route::has($notificationRoute)) {
                        $notificationRoute = 'notifications.index';
                    }
                @endphp
                <a href="{{ route($notificationRoute) }}" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
            </div>
        </li>

        {{-- USER PROFILE --}}
        <li class="nav-item dropdown ml-2">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none transition">
                        <div class="font-weight-bold">{{ Auth::user()->name }}</div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    @if(auth()->user()->role !== 'pasien')
                        <x-dropdown-link :href="route('profile.edit')"><i class="fas fa-user-edit mr-2"></i> {{ __('Profile') }}</x-dropdown-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </li>

        {{-- FUNGSI BAWAAN: Fullscreen --}}
        <li class="nav-item {{ auth()->user()->role === 'pasien' ? 'd-none d-md-block' : '' }}">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>