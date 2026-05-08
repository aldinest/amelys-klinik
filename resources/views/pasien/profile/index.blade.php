@extends('layouts.app_pasien')
@section('title', 'Profil Saya')

@section('content')
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

<div class="content-wrapper" style="background-color: #f4f6f9;">
    {{-- HEADER --}}
    <section class="content-header pt-4 pb-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="m-0 font-weight-bold text-dark" style="letter-spacing: -0.5px;">
                        <i class="fas fa-user-circle mr-2 text-primary"></i>Profil Saya
                    </h3>
                    <p class="text-muted mb-0">Kelola informasi diri dan keamanan akun Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container">
            <div class="row">
                {{-- SISI KIRI: RINGKASAN PROFIL --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 text-center p-4 mb-4" style="border-radius: 15px;">
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=128" 
                                 class="rounded-circle shadow-sm border" 
                                 width="120" alt="User Avatar">
                        </div>
                        <h5 class="font-weight-bold mb-1 text-capitalize text-dark">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                        <div>
                            <span class="badge badge-pill badge-light px-3 py-2 text-primary border" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle mr-1"></i> Pasien Aktif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SISI KANAN: FORMULIR --}}
                <div class="col-lg-8">
                    {{-- CARD INFORMASI PRIBADI --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-id-card mr-2 text-muted"></i>Informasi Pribadi
                            </h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form action="{{ route('pasien.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control custom-input" value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Alamat Email</label>
                                        <input type="email" name="email" class="form-control custom-input" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold btn-custom">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- CARD KEAMANAN --}}
                    <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-shield-alt mr-2 text-muted"></i>Ganti Password
                            </h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form action="{{ route('pasien.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Password Baru</label>
                                        <input type="password" name="password" class="form-control custom-input @error('password') is-invalid @enderror" placeholder="••••••••">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control custom-input" placeholder="••••••••">
                                    </div>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="submit" class="btn btn-outline-danger px-4 font-weight-bold btn-custom" style="border-width: 2px;">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> {{-- End col-lg-8 --}}
            </div> {{-- End row --}}
        </div> {{-- End container --}}
    </section>
</div>

<style>
    .custom-input {
        border-radius: 10px !important;
        height: 45px !important;
        border: 1px solid #dee2e6;
    }

    .btn-custom {
        border-radius: 10px !important;
        height: 45px !important;
    }

    .form-control:focus {
        border-color: #0D8ABC !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 138, 188, 0.1) !important;
    }

    /* Padding bawah ekstra untuk mobile agar tidak tertutup nav-bottom jika ada */
    @media (max-width: 768px) {
        .content {
            padding-bottom: 80px !important;
        }
    }
</style>
@endsection