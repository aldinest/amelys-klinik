@extends('layouts.app_pasien')
@section('title', 'Profil Saya')

@section('content')
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

<div class="content-wrapper" style="background-color: #f8f9fb;">
    {{-- HEADER --}}
    <section class="content-header pt-3 pb-2">
        <div class="container-fluid">
            <div class="row align-items-end">
                <div class="col-sm-6">
                    <h3 class="m-0 font-weight-bold text-dark" style="letter-spacing: -0.5px; font-size: 1.5rem;">
                        <i class="fas fa-user-circle mr-2 text-primary" style="font-size: 1.3rem;"></i>Profil Saya
                    </h3>
                    <p class="text-muted mb-0 small">Kelola informasi diri dan keamanan akun Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                {{-- SISI KIRI: RINGKASAN PROFIL --}}
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center p-4 mb-4" style="border-radius: 15px;">
                        <div class="position-relative d-inline-block mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=128" 
                                 class="rounded-circle shadow-sm border" 
                                 width="120" alt="User Avatar">
                        </div>
                        <h5 class="font-weight-bold mb-1 text-capitalize text-dark">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                        <span class="badge badge-pill badge-light px-3 py-2 text-primary border" style="font-size: 0.75rem;">Pasien Aktif</span>
                    </div>
                </div>

                {{-- SISI KANAN: FORMULIR --}}
                <div class="col-md-8">
                    {{-- CARD INFORMASI PRIBADI --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-id-card mr-2 text-muted"></i>Informasi Pribadi</h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form action="{{ route('pasien.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" style="border-radius: 10px; height: 45px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Alamat Email</label>
                                        <input type="email" class="form-control bg-light border-0" value="{{ Auth::user()->email }}" readonly style="border-radius: 10px; height: 45px; cursor: not-allowed;">
                                    </div>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold" style="border-radius: 10px; height: 45px;">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- CARD KEAMANAN --}}
                    <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-shield-alt mr-2 text-muted"></i>Ganti Password</h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form action="{{ route('pasien.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Password Baru</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" style="border-radius: 10px; height: 45px;">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" style="border-radius: 10px; height: 45px;">
                                    </div>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="submit" class="btn btn-outline-danger px-4 font-weight-bold" style="border-radius: 10px; height: 45px; border-width: 2px;">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Hilangkan outline default AdminLTE */
    .form-control:focus {
        border-color: #0D8ABC;
        box-shadow: none;
    }

    /* Penyesuaian Ruang Mobile agar tidak tertutup nav-mobile */
    @media (max-width: 768px) {
        .content {
            padding-bottom: 100px !important;
        }
        .card:last-child {
            margin-bottom: 120px !important;
        }
        .btn-block-mobile {
            width: 100%;
        }
    }
</style>
@endsection