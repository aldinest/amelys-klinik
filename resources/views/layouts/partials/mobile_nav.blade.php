{{-- resources/views/layouts/partials/mobile_nav.blade.php --}}
<style>
    .mobile-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        border-top: 1px solid #edf2f7;
        padding: 12px 0;
        z-index: 1050;
        box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
        border-radius: 20px 20px 0 0;
    }
    @media (max-width: 768px) {
        .mobile-nav { display: flex; }
    }
    .nav-link-mobile {
        flex: 1;
        text-align: center;
        color: #94a3b8;
        font-size: 10px;
        text-decoration: none !important;
    }
    .nav-link-mobile.active { color: #007bff; font-weight: 700; }
    .nav-link-mobile i { font-size: 1.3rem; display: block; margin-bottom: 4px; }
</style>

<div class="mobile-nav">
    <a href="{{ url('/pasien/dashboard') }}" class="nav-link-mobile {{ request()->is('pasien/dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="{{ url('/pasien/reservations/create') }}" class="nav-link-mobile {{ request()->is('pasien/reservations/create') ? 'active' : '' }}">
        <i class="fas fa-calendar-plus"></i>
        <span>Daftar</span>
    </a>
    <a href="{{ url('/pasien/reservations') }}" class="nav-link-mobile {{ request()->is('pasien/reservations') && !request()->is('*/create') ? 'active' : '' }}">
        <i class="fas fa-user-clock"></i>
        <span>Reservasi Saya</span>
    </a>
    <a href="{{ route('pasien.profile.index') }}" 
    class="nav-link-mobile {{ request()->routeIs('pasien.profile.*') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profil</span>
    </a>
</div>