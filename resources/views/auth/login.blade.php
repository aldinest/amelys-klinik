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
        }
        .login-card-body {
            border-radius: 10px;
            padding: 2rem !important;
        }
        .card {
            border-top: 5px solid #007bff; /* Biru AdminLTE sesuai gambar lo */
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            height: 45px;
            font-weight: 600;
        }
        .form-control {
            height: 45px;
        }
        .form-control:focus {
            border-color: #007bff;
        }
        .login-logo img {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('dist/img/logoamelys.png') }}" alt="Logo" style="height: 80px;"><br>
            <span style="font-weight: 700; color: #333;">AMELYS</span> <span style="font-weight: 300; color: #666;">KLINIK</span>
        </a>
    </div>
    
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg text-bold">Silakan Login untuk Reservasi</p>

            @if (session('status'))
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group mb-3">
                    <label class="small text-muted">Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email..." value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope text-primary"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="small text-muted">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password..." required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock text-primary"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row mt-4">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" style="font-weight: 400; cursor: pointer;">
                                Ingat Saya
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">MASUK</button>
                    </div>
                </div>
            </form>

            <div class="text-center mt-4">
                <hr>
                @if (Route::has('password.request'))
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}" class="text-primary small">Lupa Password?</a>
                    </p>
                @endif
                <p class="mb-0">
                    <span class="small">Belum punya akun?</span>
                    <a href="https://wa.me/6282335483854" class="text-primary text-bold small"> Hubungi CS Klinik Sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>