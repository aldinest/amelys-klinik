
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('pengurus.dashboard')}}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Amelys Klinik</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{ route('pengurus.dashboard') }}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas"></i>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('pengurus.patients.index') }}" class="nav-link">
              <i class="nav-icon far fa-plus-square"></i>
              <p>
                Data Pasien
                <i class="fas"></i>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('pengurus.doctor_schedules.index') }}" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Jadwal Praktek
                <i class="fas"></i>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('pengurus.reservations.index') }}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Reservasi Pasien
                <i class="fas"></i>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('pengurus.medical-records.index') }}" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Rekam Medis Pasien
                <i class="fas"></i>
              </p>
            </a>
          </li>
          
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
