<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Amelys Klinik | Login</title>
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logoamelys.png') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    
    <style>
        body.login-page {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 400px; /* Lebar lebih proporsional untuk desktop */
        }
        @media (max-width: 576px) {
            .login-box {
                width: 90%; /* Responsif di HP */
            }
        }
        .card {
            border-top: 5px solid #007bff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
            border-left: none;
            border-right: none;
            border-bottom: none;
        }
        .login-card-body {
            border-radius: 12px;
            padding: 2.5rem 2rem !important;
        }
        .form-control {
            height: 50px; /* Sedikit lebih besar agar nyaman di mobile */
            border-radius: 8px;
        }
        .input-group-text {
            border-radius: 0 8px 8px 0 !important;
            background-color: #f8f9fa;
        }
        .input-group > .form-control {
            border-radius: 8px 0 0 8px !important;
        }
        .btn-primary {
            background-color: #007bff !important;
            height: 50px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }
        .custom-control-label {
            cursor: pointer;
            padding-top: 2px;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo mb-4 text-center">
        <a href="{{ url('/') }}">
            <img src="{{ asset('dist/img/logoamelys.png') }}" alt="Logo" style="height: 70px;" class="mb-2"><br>
            <span style="font-weight: 700; color: #333; font-size: 24px;">AMELYS</span> 
            <span style="font-weight: 300; color: #666; font-size: 24px;">KLINIK</span>
        </a>
    </div>
    
    <div class="card">
        <div class="card-body login-card-body">
            <h5 class="text-center text-bold mb-4">Login Reservasi</h5>

            @if (!empty($maintenanceActive) && $maintenanceActive)
                <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                    <i class="icon fas fa-exclamation-triangle mr-2"></i>
                    <strong>Mode Maintenance Aktif</strong>
                    <p class="mb-0 small">Akses hanya tersedia untuk Admin. Jika Anda adalah pengurus atau pasien, login akan dialihkan ke halaman maintenance.</p>
                    @if(!empty($maintenanceMessage))
                        <p class="mb-0 small mt-1">{{ $maintenanceMessage }}</p>
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <div class="card card-outline card-secondary mb-3 p-3">
                    <p class="mb-1 font-weight-bold">Informasi Login:</p>
                    <ul class="mb-0 pl-3 small text-muted text-left">
                        <li>Gunakan akun Admin untuk melanjutkan selama maintenance.</li>
                        <li>Jika Anda Pengurus atau Pasien, silakan kembali nanti setelah pemeliharaan selesai.</li>
                    </ul>
                </div> -->
            @endif

            @if (session('status'))
                <div class="alert alert-success mb-3 small" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('email') || $errors->has('password'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="icon fas fa-exclamation-circle mr-2"></i>
                    <strong>Login Gagal!</strong><br>
                    <span class="small">{{ $errors->first('email') ?: $errors->first('password') }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group mb-3">
                    <label class="small text-muted font-weight-bold">Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope text-primary"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-danger mt-1 d-block small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="small text-muted font-weight-bold">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <div class="input-group-append" style="cursor: pointer;" id="togglePassword">
                            <div class="input-group-text">
                                <span class="fas fa-eye text-primary" id="eyeIcon"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <span class="text-danger mt-1 d-block small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Bagian Checkbox & Lupa Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label small text-muted" for="remember">Ingat Saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-primary font-weight-bold">Lupa Password?</a>
                    @endif
                </div>

                <!-- Tombol Masuk Full Width -->
                <button type="submit" class="btn btn-primary btn-block shadow-sm">MASUK KE AKUN</button>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0 small text-muted">
                    Belum punya akun? 
                    <a href="https://wa.me/6282335483854" class="text-primary font-weight-bold">Hubungi CS Kami</a>
                </p>
                <!-- <p class="mb-0 small text-muted mt-2">
                    <i class="fas fa-user-shield mr-1"></i> Masuk sebagai Admin hanya bila Anda memiliki akses admin.
                </p> -->
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#togglePassword").click(function() {
            const passwordField = $("#password");
            const eyeIcon = $("#eyeIcon");
            const type = passwordField.attr("type") === "password" ? "text" : "password";
            passwordField.attr("type", type);
            eyeIcon.toggleClass("fa-eye fa-eye-slash");
        });
    });
</script>
</body>
</html>