<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Brand Logo -->
  <a href="{{ route('pasien.dashboard') }}" class="brand-link">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Amelys Klinik</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

        {{-- Dashboard --}}
        <li class="nav-item">
          <a href="{{ route('pasien.dashboard') }}"
             class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Buat Reservasi --}}
        <li class="nav-item">
          <a href="{{ route('pasien.reservations.create') }}"
             class="nav-link {{ request()->routeIs('pasien.reservations.create') ? 'active' : '' }}">
            <i class="nav-icon fas fa-plus-circle"></i>
            <p>Buat Reservasi</p>
          </a>
        </li>

        {{-- Reservasi Saya --}}
        <li class="nav-item">
          <a href="{{ route('pasien.reservations.index') }}"
             class="nav-link {{ request()->routeIs('pasien.reservations.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>Reservasi Saya</p>
          </a>
        </li>

        {{-- Riwayat Berobat --}}
        <li class="nav-item">
          <a href="{{ route('pasien.medical-records.index') }}"
             class="nav-link {{ request()->routeIs('pasien.medical-records.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-notes-medical"></i>
            <p>Riwayat Berobat</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>