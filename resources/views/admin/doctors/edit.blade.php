@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Edit Data Dokter</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- Alert Success/Error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible shadow-sm border-0">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible shadow-sm border-0">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            {{-- CARD 1: FORM DATA DOKTER --}}
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header bg-warning">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-edit mr-1"></i> Informasi Profil Dokter
                    </h3>
                </div>
                <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Nama Dokter</label>
                                    <input type="text" name="name" class="form-control" value="{{ $doctor->name }}" placeholder="Masukkan Nama Lengkap" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">No. Telepon / HP</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ $doctor->phone_number }}" placeholder="08xxxxxx">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Jenis Kelamin</label>
                                    <select name="gender" class="form-control">
                                        <option value="L" {{ $doctor->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $doctor->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Spesialis</label>
                                    <input type="text" name="specialist" class="form-control" value="{{ $doctor->specialist }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-muted">Alamat</label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Alamat Lengkap">{{ $doctor->address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Status Aktif</label>
                                    <select name="status" class="form-control">
                                        <option value="aktif" {{ $doctor->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ $doctor->status == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="card-footer bg-light">
                                <div class="d-flex justify-content-start" style="gap: 10px;">
                                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>

                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="fas fa-save mr-1"></i> Update
                                    </button>
                                </div>
                      </div>
                </form>
            </div>

            {{-- CARD 2: AKUN LOGIN (Sudah Rata Kiri & Sejajar) --}}
            <div class="card card-outline card-info shadow-sm mt-4">
                <div class="card-header bg-info">
                    <h3 class="card-title font-weight-bold text-white">
                        <i class="fas fa-key mr-2"></i> Akun Login Dokter
                    </h3>
                </div>
                <div class="card-body">
                    @if(!$doctor->user_id)
                        <div class="callout callout-warning border-left shadow-sm py-2 mb-4">
                            <p class="small mb-0 text-muted">
                                <i class="fas fa-exclamation-triangle mr-1 text-warning"></i> 
                                Dokter ini belum memiliki akun login. Silakan aktivasi email di bawah.
                            </p>
                        </div>

                        <form action="{{ route('admin.doctors.create-account', $doctor->id) }}" method="POST">
                            @csrf
                            <div class="row"> {{-- Row biasa tanpa justify-center biar RATA KIRI --}}
                                <div class="col-md-8">
                                    <label class="small font-weight-bold text-muted text-uppercase">Email Akun (Digunakan untuk Login)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fas fa-envelope text-info"></i>
                                            </span>
                                        </div>
                                        <input type="email" name="email" class="form-control border-left-0" placeholder="contoh: dokter@amelys.com" style="height: 46px;" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm" style="height: 46px;">
                                                <i class="fas fa-user-plus mr-2"></i> Aktivasi Akun
                                            </button>
                                        </div>
                                    </div>
                                    <p class="small text-muted italic mt-2 ml-1">
                                        <i class="fas fa-info-circle mr-1"></i> Password default: <strong class="text-danger">password123</strong>
                                    </p>
                                </div>
                            </div>
                        </form>
                    @else
                        {{-- Tampilan Elegan jika Akun Sudah Ada --}}
                        <div class="py-2">
                            <div class="d-inline-flex align-items-center p-3 border rounded bg-light shadow-none">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 45px; height: 45px;">
                                    <i class="fas fa-check fa-lg"></i>
                                </div>
                                <div class="text-left">
                                    <h6 class="font-weight-bold mb-0 text-success">Akun Sudah Aktif</h6>
                                    <small class="text-muted">Email ini sudah terdaftar dan siap digunakan untuk login ke sistem.</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
</div>

<style>
    /* Styling khusus agar input group menyatu rapi */
    .input-group-text {
        border-top-left-radius: 4px !important;
        border-bottom-left-radius: 4px !important;
    }
    .input-group .form-control {
        border-radius: 0;
    }
    .input-group .btn {
        border-top-right-radius: 4px !important;
        border-bottom-right-radius: 4px !important;
    }
    .italic { font-style: italic; }
</style>
@endsection