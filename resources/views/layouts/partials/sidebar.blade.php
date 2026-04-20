<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Amelys Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Search -->
        <div class="form-inline mt-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar"
                       type="search"
                       placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Data Master -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Data Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.doctors.index') }}"
                               class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Dokter</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.patients.index') }}"
                               class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pasien</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Manajemen User -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Manajemen User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                               class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Akun User</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Jadwal Dokter Display -->
                <li class="nav-item">
                    <a href="{{ route('admin.doctor-display-schedules.index') }}"
                       class="nav-link {{ request()->routeIs('admin.doctor-display-schedules.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Jadwal Dokter (Display)</p>
                    </a>
                </li>

                <!-- News / Info Terbaru (NEXT UPDATE) -->
                <li class="nav-item">
                    <a href="{{ route('admin.news.index') }}"
                       class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Info & Pengumuman</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>