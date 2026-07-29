<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Amelys Klinik</title>
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logoamelys.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body { background: #f4f6f9; color: #36454f; }
        .maintenance-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .maintenance-card { max-width: 680px; width: 100%; background: #fff; border-radius: 18px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); padding: 40px; }
        .maintenance-card h1 { font-size: 3rem; font-weight: 800; margin-bottom: 12px; }
        .maintenance-card p { font-size: 1rem; color: #5a6772; margin-bottom: 24px; }
        .maintenance-card .badge-status { font-size: 0.95rem; border-radius: 999px; padding: 0.65rem 1rem; }
        .maintenance-card .btn-home { min-width: 180px; }
        .maintenance-card .text-muted-small { font-size: 0.92rem; color: #8a96a3; }
        .maintenance-card .alert-main { background: #fff4e5; color: #8a5a19; border-color: #f6d29a; }
    </style>
</head>
<body>
    <div class="maintenance-wrapper">
        <div class="maintenance-card text-center">
            <div class="mb-4">
                <span class="badge badge-warning badge-status">Maintenance Aktif</span>
            </div>
            <h1>Sistem Sedang Dalam Perawatan</h1>
            <p class="lead">Akses untuk pengguna non-admin saat ini dibatasi sementara. Mohon maaf atas ketidaknyamanannya.</p>

            @if(session('warning'))
                <div class="alert alert-warning text-left" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="alert alert-main text-left" role="alert">
                <h5 class="mb-2"><i class="fas fa-info-circle"></i> Informasi Pemeliharaan</h5>
                <p class="mb-0">{{ $maintenanceMessage }}</p>
            </div>

            @if(auth()->check())
                <div class="alert alert-secondary text-left" role="alert">
                    <strong>Status Akun:</strong>
                    <span>{{ ucfirst(auth()->user()->role) }} @if(auth()->user()->isAdmin()) (Admin) @endif</span>
                    <br>
                    <small class="text-muted">Jika Anda bukan admin, silakan logout dan coba lagi nanti.</small>
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-primary btn-home"><i class="fas fa-home mr-2"></i> Kembali ke Halaman Utama</a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-home"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin</a>
                    @else
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-home">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-home"><i class="fas fa-sign-in-alt mr-2"></i> Login Sebagai Admin</a>
                @endauth
            </div>

            <p class="text-muted-small mt-4">Admin dapat tetap mengelola aplikasi. Pengurus dan pasien hanya bisa mengakses kembali setelah maintenance selesai.</p>
        </div>
    </div>
</body>
</html>
